<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StokModel extends Model
{
    use HasFactory;

    protected $table = 't_stok'; // Nama tabel di database
    protected $primaryKey = 'stok_id'; // Primary key tabel
    public $incrementing = true; 
    public $timestamps = false; // Nonaktifkan timestamps jika tidak digunakan

    protected $fillable = [
        'barang_id', // ID barang yang terkait
        'user_id',   // ID pengguna yang melakukan transaksi
        'stok_tanggal', // Tanggal transaksi
        'stok_jumlah' // Jumlah stok yang ditambahkan atau dikurangi
    ];

    /**
     * Relasi dengan model Barang
     */
    public function barang(): BelongsTo
    {
        return $this->belongsTo(BarangModel::class, 'barang_id', 'barang_id');
    }

    /**
     * Relasi dengan model User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }
}