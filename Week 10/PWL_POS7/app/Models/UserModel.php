<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LevelModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable; //implementasi class Authenticatable

class UserModel extends Authenticatable implements JWTSubject
{
    use HasFactory;

    public function getJWTIdentifier(){
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $table = 'm_user'; // Mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'user_id'; // Mendefinisikan primary key dari tabel yang digunakan

    protected $fillable = ['username', 'nama', 'password', 'level_id', 'profile_picture', 'created_at', 'updated_at'];
    protected $hidden = ['password']; //jangan ditampilkan saat select
    protected $casts = ['password' => 'hashed']; //casting password agar otomatis di hash

    public function level(): BelongsTo {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }

    //mendapatkan nama role
    public function getRoleName(): string {
        return $this->level->level_name;
    }

    //cek apakah user memiliki role tertentu
    public function hasRole($role): bool {
        return $this->level->level_kode == $role;
    }

    //mendapatkan kode role
    public function getRole(){
        return $this->level->level_kode;
    }

    // Menampilkan foto profil user secara otomatis.
    public function getProfilePictureUrlAttribute()
    {
        return $this->profile_picture
            ? asset('storage/profile_pictures/' . $this->profile_picture)
            : asset('images/default-profile.png');
    }
}


