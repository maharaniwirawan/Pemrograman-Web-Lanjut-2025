<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['barang_id' => 1, 'kategori_id' => 1, 'barang_kode' => 'B001', 'barang_nama' => 'Tas Sekolah Anak', 'harga_beli' => 120000, 'harga_jual' => 150000],
            ['barang_id' => 2, 'kategori_id' => 1, 'barang_kode' => 'B002', 'barang_nama' => 'Mainan Anak', 'harga_beli' => 40000, 'harga_jual' => 50000],
            ['barang_id' => 3, 'kategori_id' => 1, 'barang_kode' => 'B003', 'barang_nama' => 'Susu Formula', 'harga_beli' => 40000, 'harga_jual' => 45000],
            ['barang_id' => 4, 'kategori_id' => 2, 'barang_kode' => 'B004', 'barang_nama' => 'Lipstik', 'harga_beli' => 25000, 'harga_jual' => 30000],
            ['barang_id' => 5, 'kategori_id' => 2, 'barang_kode' => 'B005', 'barang_nama' => 'Micellar Water', 'harga_beli' => 28000, 'harga_jual' => 35000],
            ['barang_id' => 6, 'kategori_id' => 2, 'barang_kode' => 'B006', 'barang_nama' => 'Facial Wash', 'harga_beli' => 35000, 'harga_jual' => 40000],
            ['barang_id' => 7, 'kategori_id' => 3, 'barang_kode' => 'B007', 'barang_nama' => 'Rabokki', 'harga_beli' => 20000, 'harga_jual' => 25000],
            ['barang_id' => 8, 'kategori_id' => 3, 'barang_kode' => 'B008', 'barang_nama' => 'Matcha Latte', 'harga_beli' => 12000, 'harga_jual' => 15000],
            ['barang_id' => 9, 'kategori_id' => 3, 'barang_kode' => 'B009', 'barang_nama' => 'Lemon Tea', 'harga_beli' => 8000, 'harga_jual' => 10000],
            ['barang_id' => 10, 'kategori_id' => 4, 'barang_kode' => 'B010', 'barang_nama' => 'Sabun', 'harga_beli' => 7000, 'harga_jual' => 10000],
            ['barang_id' => 11, 'kategori_id' => 4, 'barang_kode' => 'B010', 'barang_nama' => 'Pengharum Ruangan', 'harga_beli' => 30000, 'harga_jual' => 35000],
            ['barang_id' => 12, 'kategori_id' => 4, 'barang_kode' => 'B010', 'barang_nama' => 'Parfum Baju', 'harga_beli' => 12000, 'harga_jual' => 20000],
        ];

        DB::table('m_barang')->insert($data);
    }
}