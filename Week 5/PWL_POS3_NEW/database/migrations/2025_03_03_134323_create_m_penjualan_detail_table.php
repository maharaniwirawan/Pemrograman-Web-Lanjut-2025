<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('t_penjualan_detail', function (Blueprint $table) {
            $table->id('detail_id');
            $table->foreignId('penjualan_id')->constrained('t_penjualan', 'penjualan_id')->onDelete('cascade');
            //membuat kolom penjualan_id sebagai foreignkey
            //(constrained) foreign key merujuk ke kolom penjualan_id pada tabel t_penjualan
            //(onDelete('cascade')) jika data tabel t_penjuaan dihapus maka data penjualan_id yang sama pada tabel juga ikut terhapus otomatis
            $table->foreignId('barang_id')->constrained('m_barang', 'barang_id');
            //membuat kolom barang_id sebagai foreignkey
            //(constrained) foreign key merujuk ke kolom barang_id pada tabel m_barang
            $table->integer('harga');
            $table->integer('jumlah');
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_penjualan_detail');
    }
};
