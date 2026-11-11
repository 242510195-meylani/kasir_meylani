<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pelanggan;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Stok;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // User
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'role' => 'admin',
                'password' => bcrypt('password'),
            ]
        );
        
        User::firstOrCreate(
            ['username' => 'petugas'],
            [
                'name' => 'petugas',
                'role' => 'petugas',
                'password' => bcrypt('password'),
            ]
        );

        // Pelanggan
        Pelanggan::firstOrCreate(
            ['nomor_tlp' => '082288877766'],
            [
                'nama' => 'Dodo Sidodo',
                'alamat' => 'Padaherang',
            ]
        );

        Pelanggan::firstOrCreate(
            ['nomor_tlp' => '082288886677'],
            [
                'nama' => 'Hanifah',
                'alamat' => 'Kalipucang',
            ]
        );

        // Kategori
        Kategori::firstOrCreate(['nama_kategori' => 'Makanan']);
        Kategori::firstOrCreate(['nama_kategori' => 'Minuman']);

        // Produk
        Produk::firstOrCreate(
            ['kode_produk' => '1001'],
            [
                'kategori_id' => 1,
                'nama_produk' => 'Chiki Taro',
                'harga' => 5000,
                'stok' => 250,
            ]
        );

        Produk::firstOrCreate(
            ['kode_produk' => '1002'],
            [
                'kategori_id' => 2,
                'nama_produk' => 'Le Mineral',
                'harga' => 3500,
                'stok' => 100,
            ]
        );

        // Stok
        Stok::create([
            'produk_id' => 1,
            'nama_suplier' => 'Toko Haji Usman',
            'jumlah' => 250,
            'tanggal' => date('Y-m-d', strtotime('-1 week'))
        ]);

        Stok::create([
            'produk_id' => 2,
            'nama_suplier' => 'Agen Le Mineral',
            'jumlah' => 100,
            'tanggal' => date('Y-m-d', strtotime('-1 week'))
        ]);
        \App\Models\Penjualan::create([
    'user_id' => 1,
    'pelanggan_id' => 1,
    'nomor_transaksi' => date('Ymd') . '0001',
    'tanggal' => date('Y-m-d H:i:s'),
    'subtotal' => 8500,
    'pajak' => 850,
    'total' => 9350,
    'tunai' => 10000,
    'kembalian' => 650
]);

\App\Models\Penjualan::create([
    'user_id' => 2,
    'pelanggan_id' => 2,
    'nomor_transaksi' => date('Ymd') . '0002',
    'tanggal' => date('Y-m-d H:i:s'),
    'subtotal' => 13500,
    'pajak' => 1350,
    'total' => 14850,
    'tunai' => 20000,
    'kembalian' => 5150
]);

\App\Models\DetilPenjualan::create([
    'penjualan_id' => 1,
    'produk_id' => 1,
    'jumlah' => 1,
    'harga_produk' => 5000,
    'subtotal' => 5000,
]);

\App\Models\DetilPenjualan::create([
    'penjualan_id' => 1,
    'produk_id' => 2,
    'jumlah' => 1,
    'harga_produk' => 3500,
    'subtotal' => 3500,
]);

\App\Models\DetilPenjualan::create([
    'penjualan_id' => 2,
    'produk_id' => 1,
    'jumlah' => 1,
    'harga_produk' => 5000,
    'subtotal' => 10000, // sesuaikan jika mengikuti modul
]);

\App\Models\DetilPenjualan::create([
    'penjualan_id' => 2,
    'produk_id' => 2,
    'jumlah' => 1,
    'harga_produk' => 3500,
    'subtotal' => 3500,
]);
    }
}