<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() {
        $user=UserModel::findOr(20, ['username', 'nama' ], function() {
            abort(404);
        });
        return view('user', ['data' => $user]);


        /*
        $data= [
            'level_id' => 2,
            'username' => 'manager_tiga',
            'nama' => 'Manager 3',
            'password' => Hash::make('12345') 
        ];
        UserModel::create($data);

        $user = UserModel::all();
        return view ('user', ['data' => $user]);
        */
    }
    /*
    
    public function index () {
        /*
        //tambah data baruu 0o0
        $data= [
            'username' => 'customer-1',
            'nama' => 'Pelanggan',
            'password' => Hash::make('12345'),
            'level_id' => 4
        ];

        UserModel::insert($data); //masukkan data baru ke database  
        

        //tambah data baru dengan eloquent model
        $data = [
            'nama' => 'Pelanggan Pertama',
        ];
        UserModel::where('username', 'customer-1')->update($data); //update data pada tabel m_user

        // akses userModel >-<
        $user=UserModel::all(); //ambil semua data dari tabel m_user :3
        return view('user', ['data' => $user]);
    }
        */

        
    }