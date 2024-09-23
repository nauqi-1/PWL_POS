<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    use HasFactory;

    protected $table= 'm_user'; //mendefinisikan nama tabel yang akan digunakan. :o
    protected $primaryKey = 'user_id'; //mendefinisikan primary key dari tabel yang digunakan. x3

    protected $fillable = ['level_id', 'username', 'nama'];
}

