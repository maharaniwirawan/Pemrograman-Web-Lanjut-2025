<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['kategori_id' => 1, 'barang_kode' => 'BRG001', 'barang_nama' => 'Laptop', 'harga_beli' => 5000000, 'harga_jual' => 5500000],
            ['kategori_id' => 1, 'barang_kode' => 'BRG002', 'barang_nama' => 'HP', 'harga_beli' => 2000000, 'harga_jual' => 2300000],
            ['kategori_id' => 2, 'barang_kode' => 'BRG003', 'barang_nama' => 'Kaos', 'harga_beli' => 50000, 'harga_jual' => 75000],
            ['kategori_id' => 2, 'barang_kode' => 'BRG004', 'barang_nama' => 'Jaket', 'harga_beli' => 200000, 'harga_jual' => 250000],
            ['kategori_id' => 3, 'barang_kode' => 'BRG005', 'barang_nama' => 'Roti', 'harga_beli' => 10000, 'harga_jual' => 15000],
            ['kategori_id' => 3, 'barang_kode' => 'BRG006', 'barang_nama' => 'Nasi Kotak', 'harga_beli' => 25000, 'harga_jual' => 30000],
            ['kategori_id' => 4, 'barang_kode' => 'BRG007', 'barang_nama' => 'Air Mineral', 'harga_beli' => 5000, 'harga_jual' => 8000],
            ['kategori_id' => 4, 'barang_kode' => 'BRG008', 'barang_nama' => 'Kopi', 'harga_beli' => 15000, 'harga_jual' => 20000],
            ['kategori_id' => 5, 'barang_kode' => 'BRG009', 'barang_nama' => 'Meja', 'harga_beli' => 300000, 'harga_jual' => 350000],
            ['kategori_id' => 5, 'barang_kode' => 'BRG010', 'barang_nama' => 'Kursi', 'harga_beli' => 150000, 'harga_jual' => 180000],
        ];
        DB::table('m_barang')->insert($data);
    }
}
