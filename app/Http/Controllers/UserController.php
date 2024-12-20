<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;


class UserController extends Controller
{
    public function index() {
         $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home','User']
         ];

         $page = (object) [
            'title' => 'Daftar user yang ada dalam sistem'
         ];

         $activeMenu = 'user'; //set menu yang sedang aktif

         $level = LevelModel::all(); //mengambil data level untuk filtering level

         return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }
    public function list(Request $request){
        $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
            -> with('level');

        if($request->level_id){
            $users->where('level_id', $request->level_id);
        }
        
        return DataTables::of($users)
        ->addIndexColumn()  
        ->addColumn('aksi', function ($user) { 
            /*$btn  = '<a href="'.url('/user/' . $user->user_id).'" class="btn btn-info btn-sm">Detail</a> '; 
            $btn .= '<a href="'.url('/user/' . $user->user_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> '; 
            $btn .= '<form class="d-inline-block" method="POST" action="'. url('/user/'.$user->user_id).'">' 
                    . csrf_field() . method_field('DELETE') .  
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakit menghapus data ini?\');">Hapus</button></form>'; */     
                    $btn = '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                    $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                    $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
            return $btn; 
        }) 
        ->rawColumns(['aksi'])
        ->make(true);
    }

    public function create() {
        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list' => ['Home','User','Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah user baru'
        ];

        $level = LevelModel::all();
        $activeMenu = 'user';

        return view('user.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    public function store(Request $request) {
        $request -> validate ([
            'username'  => 'required|string|min:3|unique:m_user,username',
            'nama'      => 'required|string|max:100',
            'password'  => 'required|min:5',
            'level_id'  => 'required|integer'
        ]);

        UserModel::create([
            'username'  => $request->username,
            'nama'      => $request->nama,
            'password'  => bcrypt($request->password),
            'level_id'  => $request->level_id
        ]);

        return redirect('/user')->with('success', 'Data user berhasil disimpan.');
    }

    public function show(string $id) {
        $user = UserModel::with('level')->find($id);
        $breadcrumb = (object) [
            'title' => 'Detail User',
            'list'  => ['Home','User','Detail']
        ];

        $page = (object) [
            'title' => 'Detail User',
        ];

        $activeMenu = 'user';

        return view('user.show', ['breadcrumb' => $breadcrumb, 'page' => $page,'user' => $user, 'activeMenu' => $activeMenu]);
    }

    public function edit(string $id) {
        $user = UserModel::find($id);
        $level = LevelModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit User',
            'list' => ['Home','User','Edit'],
        ];

        $page = (object) [
            'title' => 'Edit User'
        ];

        $activeMenu = 'user';

        return view('user.edit', ['breadcrumb' => $breadcrumb, 'level' => $level, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
    }

    public function update(Request $request, string $id) {
        $request -> validate( [ 
            'username'  => 'required|string|min:3|unique:m_user,username,'.$id.',user_id',
            'nama'      => 'required|string|max:100',
            'password'  => 'nullable|min:5',
            'level_id'  => 'required|integer'
        ]);

        UserModel::find($id)->update([
            'username'  => $request->username,
            'nama'      => $request->nama,
            'password'  => $request->password?bcrypt($request->password) : UserModel::find($id)->password,
            'level_id'  => $request->level_id
        ]);

        return redirect('/user')->with('success','Data user berhasil diubah');

    }

    public function destroy(string $id) {
        $check = UserModel::find($id);
        
        if (!$check) {
            return response()->json([
                'status' => false,
                'message' => 'Data user tidak ditemukan'
            ]);
        }
    
        try {
            UserModel::destroy($id);
    
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus!'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menghapus data.'
            ]);
        }
    }
    
    public function create_ajax() {
        $level = LevelModel::select('level_id', 'level_nama') -> get();

        return view('user.create_ajax') -> with('level', $level);
    }

    public function store_ajax(Request $request) {

        if ($request -> ajax() || $request -> wantsJson()) {
            $rules = [
                'level_id'  => 'required|integer',
                'username'  => 'required|string|min:3|unique:m_user,username',
                'nama'      => 'required|string|max:100',
                'password'  => 'required|min:6',
                
            ];

            $validator = Validator::make($request -> all(),$rules);

            if ($validator -> fails()) {
                return response() -> json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            UserModel:: create($request->all());
            return response() -> json([
                'status' => true,
                'message' => 'Data berhasil disimpan!'
            ]);
        }

    }

    public function edit_ajax(string $id) {
        $user = UserModel::find($id);
        $level = LevelModel::select('level_id', 'level_nama') -> get();

        return view('user.edit_ajax', ['user' => $user, 'level' => $level]);
    }

    public function update_ajax(Request $request, $id)
    {
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'level_id' => 'required|integer',
            'username' => 'required|max:20|unique:m_user,username',
            'nama' => 'required|max:100',
            'password' => 'nullable|min:6|max:20',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $check = UserModel::find($id);
        
        if ($check) {
            if (!$request->filled('password')) { 
                $request->request->remove('password');
            }

            $request['password'] = Hash::make($request->password);

            $check->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diupdate'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    return redirect('/');
    }

    public function confirm_ajax(string $id) {
        $user = UserModel::find($id);

        return view('user.confirm_ajax', ['user' => $user]);
    } 

    public function delete_ajax(Request $request, $id) {
        if ($request -> ajax() || $request -> wantsJson()) {
            $user = UserModel::find($id);

            if ($user) {
                $user->delete();
                return response() -> json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus!'
                ]);
            } else {
                return response() -> json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan!'
                ]);
            }
        }
        return redirect('/');
    }

    public function export_pdf() {
        $user = UserModel::select('level_id', 'username', 'nama') 
            ->orderBy('level_id') 
            ->orderBy('username') 
            ->with('level') 
            ->get();
        // use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('user.export_pdf', ['user' => $user]);
        $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url $pdf->render();
        return $pdf->stream ('Data User '.date('Y-m-d H:i:s').'.pdf');
    }

    public function import_pfp() {
        return view('import_pfp');
    }
 

    public function import_ajax_pfp(Request $request)
    {
        $request->validate([
            'file_pfp' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);
    
        $file = $request->file('file_pfp');
    
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = public_path('images/pfp');
    
        $file->move($path, $filename);
    
        $user = auth()->user();
        $user->profile_picture = $filename;
        $user->save();
        
        return redirect('/');
    }

    public function show_ajax(string $id) {
        $user = UserModel::find($id);

        return view('user.show_ajax', ['user' => $user]);
    }

    public function edit_profile()
    {
        return view('edit_profile', ['user' => auth()->user()]);
    }
    
    
    public function edit_profile_save(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'nama' => 'required|string|max:255',
        'password' => ['nullable', 'confirmed'], // Ensure 'password_confirmation' exists in the form
    ]);

    // Update name
    $user->nama = $request->nama;

    // Update password 
    if ($request->password) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    if ($request->ajax()) {
        return response()->json(['status' => true, 'message' => 'Profile updated successfully']);
    }

    return redirect('/')->with('status', 'Profile updated successfully!');
}

public function import() {
    return view('user.import');
}

public function import_ajax(Request $request) {
    if ($request->ajax() || $request->wantsJson()) {
        
        $rules = [
            'file_user' => ['required', 'mimes:xlsx', 'max:1024'], 
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $file = $request->file('file_user');

        try {
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true); 
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();

            $data = $sheet->toArray(null, false, true, true);

            $insert = [];

            if (count($data) > 1) { 
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { 
                        $insert[] = [
                            'username' => $value['A'],
                            'nama' => $value['B'],
                            'password' => $value['C'],
                            'level_id' => $value['D'],
                            'created_at' => now(),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    UserModel::insertOrIgnore($insert);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat memproses file: ' . $e->getMessage()
            ]);
        }
    }

    return redirect('/');
}

public function export_excel() {
    $user = UserModel::select('level_id', 'username', 'nama', 'password') 
            -> orderBy('username')
            -> with('level')
            -> get();

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet-> getActiveSheet();

    $sheet->setCellValue('A1','No');
    $sheet->setCellValue('B1','Username');
    $sheet->setCellValue('C1','Nama');
    $sheet->setCellValue('D1','Password');
    $sheet->setCellValue('E1','Level');



    $no = 1;
    $baris = 2;
    foreach($user as $key => $value) {
        $sheet->setCellValue('A'.$baris,$no);
        $sheet->setCellValue('B'.$baris,$value -> username);
        $sheet->setCellValue('C'.$baris,$value -> nama);
        $sheet->setCellValue('D'.$baris,$value -> password);
        $sheet->setCellValue('E'.$baris,$value -> level -> level_nama);

        $baris++;
        $no++;
    }

    foreach(range('A', 'E') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true); //set ukuran kolom otomatis
    }

    $sheet->setTitle('Data User');

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $filename = 'Data User' . date('Y-m-d H:i:s'). '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'.$filename.'"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 22 Agustus 2025 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');

    $writer->save('php://output');
    exit;

}

    


}