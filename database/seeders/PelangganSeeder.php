<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelanggan;

class PelangganSeeder extends Seeder
{
    public function run(): void
    {
        Pelanggan::create([
            'nama' => 'Dodo Sidodo',
            'nomor_tlp' => '082288877766',
            'alamat' => 'Padaherang'
        ]);

        Pelanggan::create([
            'nama' => 'Hanifah',
            'nomor_tlp' => '082288866677',
            'alamat' => 'Kalipucang'
        ]);
    }
}