<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;


class UserModel extends Authenticatable implements JWTSubject
{
    use HasFactory;

    public function getJWTIdentifier() {
        return $this -> getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }

    protected $table        = 'm_user'; //mendefinisikan nama tabel yang akan digunakan. :o
    protected $primaryKey   = 'user_id'; //mendefinisikan primary key dari tabel yang digunakan. x3

    protected $fillable     = [
        'level_id',
        'username', 
        'nama', 
        'password', 
        'created_at', 
        'updated_at', 
        'profile_picture',
        'image' //menambahkan kolom image sebagai fillable
    ];

    protected $hidden       = ['password'];

    protected $casts        = ['password' => 'hashed'];

    public function image():Attribute 
    {
        return Attribute::make(
            get: fn ($image) => url('/storage/posts/' . $image),
        );
    }

    public function level():BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }

    public function penjualan() 
    {
        return $this->hasMany(PenjualanModel::class, 'user_id', 'user_id');
    }

    //mendapatkan nama role

    public function getRoleName(): string {
        return $this->level->level_nama;
    }

    //cek apakah user memiliki kode tertentu

    public function hasRole($role): bool {
        return $this->level->level_kode == $role;
    }

    //mendapatkan kode role
    public function getRole(): string {
        return $this->level->level_kode;
    }
}


