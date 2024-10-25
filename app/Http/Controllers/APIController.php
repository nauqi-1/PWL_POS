<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\APIModel;

class APIController extends Controller
{
    public function index() {
        $data = APIModel::all();
        return $data;
    }

    public function show(Request $request) {
        $data = APIModel::all()->where('id', $request->id)->first();
        return $data;
    }

    public function store(Request $request) {
        $save = new APIModel;
        $save->nama = $request->nama;
        $save->bank = $request->bank;
        $save->alamat = $request->alamat;
        $save->save();

        return "Berhasil menyimpan data";

    }

    public function edit(Request $request) {
        $data = APIModel::all()->where('id', $request->id)->first();
        $data->nama = $request->nama;
        $data->bank = $request->bank;
        $data->alamat = $request->alamat;
        $data->save();

        return "Berhasil mengubah data";
    }

    public function destroy(Request $request) {
        $del = APIModel::all()->where('id', $request->id)->first();
        $del->delete();

        return "Berhasil menghapus data";
    }
}
