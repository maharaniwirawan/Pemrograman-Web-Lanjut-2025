<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) { //$i berfungsi sebagai penjualan id
            for ($j = 0; $j < 3; $j++) {
                DB::table('t_penjualan_detail')->insert([
                    'penjualan_id' => $i,
                    'barang_id' => rand(1, 10), //Memilih barang secara acak dari ID 1 hingga 10.
                    'harga' => rand(10000, 500000), //Harga barang acak antara Rp10.000 hingga Rp500.000
                    'jumlah' => rand(1, 5), //Jumlah barang acak antara 1 hingga 5 unit.
                ]);
            }
        }
    }
}
