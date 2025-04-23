<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m_supplier')->insert([
            [
                'supplier_kode' => 'SUP001',
                'supplier_nama' => 'PT Maju Jaya',
                'supplier_alamat' => 'Jl. Merdeka No. 10, Malang',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'supplier_kode' => 'SUP002',
                'supplier_nama' => 'CV Sejahtera Abadi',
                'supplier_alamat' => 'Perumahan Griya Indah Blok A No. 5, Surabaya',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'supplier_kode' => 'SUP003',
                'supplier_nama' => 'UD Sumber Rezeki',
                'supplier_alamat' => 'Jl. Gatot Subroto Kav. 22, Jakarta Selatan',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'supplier_kode' => 'SUP004',
                'supplier_nama' => 'Toko Aneka Guna',
                'supplier_alamat' => 'Jl. Diponegoro No. 115, Bandung',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'supplier_kode' => 'SUP005',
                'supplier_nama' => 'Koperasi Mandiri',
                'supplier_alamat' => 'Dusun Mawar RT. 03 RW. 07, Yogyakarta',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}