<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetilPenjualan;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\User;
use Jackiedo\Cart\Facades\Cart;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $penjualans = Penjualan::join('users', 'users.id', '=', 'penjualans.user_id')
            ->join('pelanggans', 'pelanggans.id', '=', 'penjualans.pelanggan_id')
            ->select(
                'penjualans.*',
                'users.name as nama_kasir',
                'pelanggans.nama as nama_pelanggan'
            )
            ->orderBy('penjualans.id', 'desc')
            ->when($search, function ($q, $search) {
                return $q->where(
                    'penjualans.nomor_transaksi',
                    'like',
                    "%{$search}%"
                );
            })
            ->paginate(15);

        if ($search) {
            $penjualans->appends(['search' => $search]);
        }

        return view('transaksi.index', [
            'penjualans' => $penjualans
        ]);
    }

    public function create(Request $request)
    {
        $user = $request->user();

        return view('transaksi.create', [
            'nama_kasir' => $user->nama ?? $user->name ?? '',
            'tanggal' => date('d F Y')
        ]);
    }

    public function store(Request $request)
    {
        $cash = (int) preg_replace('/\D/', '', (string) $request->cash);

        $request->merge([
            'cash' => $cash
        ]);

        $request->validate([
            'pelanggan_id' => ['required', 'exists:pelanggans,id'],
            'cash' => ['required', 'numeric', 'gte:total_bayar']
        ], [], [
            'pelanggan_id' => 'pelanggan'
        ]);

        $user = $request->user();
        $lastPenjualan = Penjualan::orderBy('id', 'desc')->first();

        $cart = Cart::name($user->id);
        $cartDetails = $cart->getDetails();

        $items = $cartDetails->get('items');

        if (!$items || count($items) === 0) {
            return back()
                ->withErrors(['cash' => 'Keranjang masih kosong.'])
                ->withInput();
        }

        $total = (float) $cartDetails->get('total');
        $kembalian = $cash - $total;

        $no = $lastPenjualan ? $lastPenjualan->id + 1 : 1;
        $no = sprintf("%04d", $no);

        $penjualan = Penjualan::create([
            'user_id' => $user->id,
            'pelanggan_id' => $request->input('pelanggan_id'),
            'nomor_transaksi' => date('Ymd') . $no,
            'tanggal' => date('Y-m-d H:i:s'),
            'total' => $total,
            'tunai' => $cash,
            'kembalian' => $kembalian,
            'pajak' => $cartDetails->get('tax_amount'),
            'subtotal' => $cartDetails->get('subtotal')
        ]);

        $allItem = $cartDetails->get('items');

        foreach ($allItem as $key => $value) {
            $item = $allItem->get($key);

            DetilPenjualan::create([
                'penjualan_id' => $penjualan->id,
                'produk_id' => $item->id,
                'jumlah' => $item->quantity,
                'harga_produk' => $item->price,
                'subtotal' => $item->subtotal,
            ]);
        }

        $cart->destroy();

        return redirect()->route('transaksi.show', [
            'transaksi' => $penjualan->id
        ]);
    }

    public function show(Request $request, Penjualan $transaksi)
    {
        $pelanggan = Pelanggan::find($transaksi->pelanggan_id);
        $user = User::find($transaksi->user_id);

        $detilPenjualan = DetilPenjualan::join(
            'produks',
            'produks.id',
            '=',
            'detil_penjualans.produk_id'
        )
            ->select('detil_penjualans.*', 'produks.nama_produk')
            ->where('penjualan_id', $transaksi->id)
            ->get();

        return view('transaksi.invoice', [
            'penjualan' => $transaksi,
            'pelanggan' => $pelanggan,
            'user' => $user,
            'detilPenjualan' => $detilPenjualan
        ]);
    }

    public function destroy(Request $request, Penjualan $transaksi)
    {
        $transaksi->update([
            'status' => 'batal'
        ]);

        return back()->with('destroy', 'success');
    }

    public function produk(Request $request)
    {
        $search = $request->search ?? $request->q;

        $produks = Produk::select(
            'id',
            'kode_produk',
            'nama_produk',
            'harga'
        )
            ->when($search, function ($q, $search) {
                return $q->where('nama_produk', 'like', "%{$search}%")
                    ->orWhere('kode_produk', 'like', "%{$search}%");
            })
            ->orderBy('nama_produk')
            ->take(15)
            ->get();

        return response()->json($produks);
    }

    public function pelanggan(Request $request)
    {
        $search = $request->search ?? $request->q;

        $pelanggans = Pelanggan::select('id', 'nama')
            ->when($search, function ($q, $search) {
                return $q->where('nama', 'like', "%{$search}%");
            })
            ->orderBy('nama')
            ->take(15)
            ->get();

        return response()->json($pelanggans);
    }

    public function addPelanggan(Request $request)
    {
        $request->validate([
            'id' => ['required', 'exists:pelanggans,id']
        ]);

        $pelanggan = Pelanggan::find($request->id);
        $cart = Cart::name($request->user()->id);

        $cart->setExtraInfo([
            'pelanggan' => [
                'id' => $pelanggan->id,
                'nama' => $pelanggan->nama,
            ]
        ]);

        return response()->json([
            'message' => 'Berhasil.'
        ]);
    }

    public function cetak(Penjualan $transaksi)
    {
        $pelanggan = Pelanggan::find($transaksi->pelanggan_id);
        $user = User::find($transaksi->user_id);

        $detilPenjualan = DetilPenjualan::join(
            'produks',
            'produks.id',
            '=',
            'detil_penjualans.produk_id'
        )
            ->select('detil_penjualans.*', 'produks.nama_produk')
            ->where('penjualan_id', $transaksi->id)
            ->get();

        return view('transaksi.cetak', [
            'penjualan' => $transaksi,
            'pelanggan' => $pelanggan,
            'user' => $user,
            'detilPenjualan' => $detilPenjualan
        ]);
    }
}
