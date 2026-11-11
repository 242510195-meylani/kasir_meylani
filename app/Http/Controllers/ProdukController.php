<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Produk;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $produks = Produk::join('kategori', 'kategori.id', 'produks.kategori_id')
            ->orderBy('produks.id')
            ->select('produks.*', 'nama_kategori')
            ->when($search, function ($q, $search) {
                return $q->where('kode_produk', 'like', "%{$search}%")
                         ->orWhere('nama_produk', 'like', "%{$search}%");
            })
            ->paginate();

        if ($search) $produks->appends(['search' => $search]);

        return view('produk.index', [
            'produks' => $produks
        ]);
    }

    public function create()
    {
        $dataKategori = Kategori::orderBy('nama_kategori')->get();

        $kategori = [
            ['', 'Pilih Kategori']
        ];

        // Variabel penampung diubah menjadi $item agar tidak bentrok
        foreach ($dataKategori as $item) {
            $kategori[] = [$item->id, $item->nama_kategori];
        }

        return view('produk.create', [
            'kategori' => $kategori
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_produk' => ['required', 'max:250', 'unique:produks'],
            'nama_produk' => ['required', 'max:150'],
            'harga' => ['required', 'numeric'],
            'kategori_id' => ['required', 'exists:kategori,id'],
        ]);

        Produk::create($request->all());

        return redirect()->route('produk.index')->with('store', 'success');
    }

    public function show(Produk $produk)
    {
        abort(404);
    }

    public function edit(Produk $produk)
    {
        $dataKategori = Kategori::orderBy('nama_kategori')->get();

        $kategoris = [
            ['', 'Pilih Kategori']
        ];

        foreach ($dataKategori as $item) {
            $kategoris[] = [$item->id, $item->nama_kategori];
        }

        return view('produk.edit', [
            'produk' => $produk,
            'kategoris' => $kategoris,
        ]);
    }

    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'kode_produk' => ['required', 'max:250', 'unique:produks,kode_produk,' . $produk->id],
            'nama_produk' => ['required', 'max:150'],
            'harga' => ['required', 'numeric'],
            'kategori_id' => ['required', 'exists:kategori,id'],
        ]);

        $produk->update($request->all());

        return redirect()->route('produk.index')->with('update', 'success');
    }

    public function destroy(Produk $produk)
    {
        $produk->delete();

        return back()->with('destroy', 'success');
    }
}