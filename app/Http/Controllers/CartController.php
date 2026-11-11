<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use Jackiedo\Cart\Facades\Cart;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = Cart::name($request->user()->id);

        $cart->applyTax([
            'id' => 1,
            'rate' => 10,
            'title' => 'Pajak PPN 10%'
        ]);

        return response()->json($cart->getDetails());
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_produk' => ['required_without_all:id,produk_id'],
            'id' => ['nullable'],
            'produk_id' => ['nullable']
        ]);

        $id = $request->input('id') ?? $request->input('produk_id');
        $kodeProduk = $request->input('kode_produk');

        $produk = $id
            ? Produk::find($id)
            : Produk::where('kode_produk', $kodeProduk)->first();

        if (!$produk) {
            return response()->json([
                'message' => 'Produk tidak ditemukan.'
            ], 404);
        }

        $cart = Cart::name($request->user()->id);

        $cart->addItem([
            'id' => $produk->id,
            'title' => $produk->nama_produk,
            'quantity' => 1,
            'price' => $produk->harga
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Produk berhasil ditambahkan.'
        ]);
    }

    public function update(Request $request, $hash)
    {
        $cart = Cart::name($request->user()->id);
        $item = $cart->getItem($hash);

        if (!$item) {
            return response()->json([
                'message' => 'Produk tidak ditemukan di keranjang.'
            ], 404);
        }

        $perubahan = (int) $request->input('qty', 0);

        if ($perubahan === 0) {
            return response()->json([
                'message' => 'Jumlah tidak valid.'
            ], 422);
        }

        $jumlahBaru = $item->getQuantity() + $perubahan;

        if ($jumlahBaru <= 0) {
            $cart->removeItem($item->getHash());
        } else {
            $cart->updateItem($item->getHash(), [
                'quantity' => $jumlahBaru
            ]);
        }

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function destroy(Request $request, $hash)
    {
        $cart = Cart::name($request->user()->id);
        $cart->removeItem($hash);

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function clear(Request $request)
    {
        Cart::name($request->user()->id)->destroy();

        return redirect()->back();
    }
}
