<?php

namespace App\Models;
use App\Models\UserModel;

use Illuminate\Database\Eloquent\Model;

class LevelModel extends Model
{
    protected $table = 'm_level'; // Sesuaikan dengan nama tabel yang benar
    protected $primaryKey = 'level_id';
    protected $fillable = ['level_id', 'kode_level', 'nama_level']; // Sesuaikan dengan kolom yang ada
}
