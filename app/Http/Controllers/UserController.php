<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() {
        $user = UserModel::create(
            [
                'username' => 'manager69',
                'nama' => 'Manager11',
                'password' => Hash::make('12345'),
                'level_id' => 2
            ]
        );

        $user -> username = 'manager68';

        $user -> save();

        $user -> wasChanged(); // true??? what
        $user -> wasChanged('username'); //true ig
        $user -> wasChanged(['username', 'level_id']); //true >///<
        $user -> wasChanged('nama'); //false
        

        dd($user -> wasChanged(['nama','username'])); //true 
        

    }
}
        /*
            $user = UserModel::firstOrNew(
            [
                'username' => 'manager55',
                'nama' => 'Manager55',
                'password' => Hash::make('12345'),
                'level_id' => 2
            ]
        );

        $user -> username = 'manager56';

        $user -> isDirty(); // true??? what
        $user -> isDirty('username'); //true ig
        $user -> isDirty('nama'); //false
        $user -> isDirty(['nama','username']); //true >///<

        $user -> isClean(); // false??? what
        $user -> isClean('username'); //false ig
        $user -> isClean('nama'); //true
        $user -> isClean(['nama','username']); //false >///<

        $user -> save();

        $user -> isDirty(); //false
        $user -> isClean(); //true
        dd($user -> isDirty());

        $user = UserModel::firstOrNew(
            [
                'username' => 'manager33',
                'nama' => 'Manager Tiga Tiga',
                'password' => Hash::make('12345'),
                'level_id' => 2
            ]
        );

        $user -> save();
        return view('user', ['data' => $user]);

    $user = UserModel::firstOrCreate(
            [
                'username' => 'manager22',
                'nama' => 'Manager Dua Dua',
                'password' => Hash::make('12345'),
                'level_id' => 2 
            ]
        );

        $user = UserModel::where('level_id' , 2) -> count();
        
        return view('user', ['data' => $user]);
        */
        /*
        $user=UserModel::findOr(20, ['username', 'nama' ], function() {
            abort(404);
        });
        return view('user', ['data' => $user]);
        */

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

        
    