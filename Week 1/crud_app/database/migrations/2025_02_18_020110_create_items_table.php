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
        Schema::create('items', function (Blueprint $table) {
            $table->id(); // Menambahkan kolom id bertipe bigint dengan auto-increment.
            $table->string('name'); // Menambahkan kolom name bertipe VARCHAR untuk teks pendek.
            $table->text('description'); // Menambahkan kolom description bertipe TEXT untuk teks panjang.
            $table->timestamps();// Menambahkan kolom untuk mencatat waktu pembuatan dan pembaruan data.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    } 
};
