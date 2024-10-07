<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use App\Models\LevelModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function create()
    {
        $level = LevelModel::select('level_id', 'level_nama') -> get();

        return view('auth.register') -> with('level', $level); //view untuk laman registrasi
        
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:20|unique:m_user,username', 
            'password' => 'required|min:5|max:20|',
            'nama' => 'required|max:100', 
            'level_id' => 'required|integer',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'msgField' => $validator->errors(),
                'message' => 'Validation failed.'
            ]);
        }

        UserModel::create([
            'username' => $request->username,
            'password' => Hash::make($request->password), 
            'nama' => $request->nama, 
            'level_id' => $request->level_id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Registrasi Berhasil.'
        ]);
    }
}
