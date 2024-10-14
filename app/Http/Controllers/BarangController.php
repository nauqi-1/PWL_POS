<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use App\Models\BarangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class BarangController extends Controller
{
    public function index() {
        $breadcrumb = (object) [
           'title' => 'Daftar Barang',
           'list' => ['Home','Barang']
        ];

        $page = (object) [
           'title' => 'Daftar barang yang dijual di toko.'
        ];

        $activeMenu = 'barang'; //set menu yang sedang aktif

        $kategori = KategoriModel::all(); //mengambil data kategori untuk filtering

        return view('barang.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
   }
   public function list(Request $request){
       $barangs = BarangModel::select('barang_id', 'kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual')
           -> with('kategori');


           //set data untuk filtering
       if($request->kategori_id){
           $barangs->where('kategori_id', $request->kategori_id);
       }
       
       return DataTables::of($barangs)
       ->addIndexColumn()  
       ->addColumn('aksi', function ($barang) { 
//           $btn  = '<a href="'.url('/barang/' . $barang->barang_id).'" class="btn btn-info btn-sm">Detail</a> '; 
 //          $btn .= '<a href="'.url('/barang/' . $barang->barang_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> '; 
  //         $btn .= '<form class="d-inline-block" method="POST" action="'. url('/barang/'.$barang->barang_id).'">' 
   //                . csrf_field() . method_field('DELETE') .  
    //               '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';      
    $btn = '<button onclick="modalAction(\''.url('/barang/' . $barang->barang_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
    $btn .= '<button onclick="modalAction(\''.url('/barang/' . $barang->barang_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
    $btn .= '<button onclick="modalAction(\''.url('/barang/' . $barang->barang_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';

    return $btn; 
       }) 
       ->rawColumns(['aksi'])
       ->make(true);
   }

   public function create() {
       $breadcrumb = (object) [
           'title' => 'Tambah Barang',
           'list' => ['Home','Barang','Tambah']
       ];

       $page = (object) [
           'title' => 'Tambah barang baru'
       ];

       $kategori = KategoriModel::all();
       $activeMenu = 'barang';

       return view('barang.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
   }

   public function store(Request $request) {
       $request -> validate ([
           'barang_kode'    => 'required|string|min:3|unique:m_barang,barang_kode',
           'kategori_id'    => 'required|integer',
           'barang_nama'    => 'required|string|max:100',
           'harga_beli'     => 'required|integer',
           'harga_jual'     => 'required|integer'
       ]);

       BarangModel::create([
           'barang_kode'    => $request->barang_kode,
           'kategori_id'    => $request->kategori_id,
           'barang_nama'    => $request->barang_nama,
           'harga_beli'     => $request->harga_beli,
           'harga_jual'     => $request->harga_jual
       ]);

       return redirect('/barang')->with('success', 'Data barang berhasil disimpan.');
   }

   public function show(string $id) {
       $barang = BarangModel::with('kategori')->find($id);
       $breadcrumb = (object) [
           'title' => 'Detail Barang',
           'list'  => ['Home','Barang','Detail']
       ];

       $page = (object) [
           'title' => 'Detail Barang',
       ];

       $activeMenu = 'barang';

       return view('barang.show', ['breadcrumb' => $breadcrumb, 'page' => $page,'barang' => $barang, 'activeMenu' => $activeMenu]);
   }

   public function edit(string $id) {
       $barang = BarangModel::find($id);
       $kategori = KategoriModel::all();

       $breadcrumb = (object) [
           'title' => 'Edit Barang',
           'list' => ['Home','Barang','Edit'],
       ];

       $page = (object) [
           'title' => 'Edit Barang'
       ];

       $activeMenu = 'barang';

       return view('barang.edit', ['breadcrumb' => $breadcrumb, 'kategori' => $kategori, 'page' => $page, 'barang' => $barang, 'activeMenu' => $activeMenu]);
   }

   public function update(Request $request, string $id) {
       $request -> validate( [ 
           'barang_kode'    => 'required|string|min:3|unique:m_barang,barang_kode',
           'kategori_id'    => 'required|integer',
           'barang_nama'    => 'required|string|max:100',
           'harga_beli'     => 'required|integer',
           'harga_jual'     => 'required|integer'
       ]);

       BarangModel::find($id)->update([
           'barang_kode'    => $request->barang_kode,
           'kategori_id'    => $request->kategori_id,
           'barang_nama'    => $request->barang_nama,
           'harga_beli'     => $request->harga_beli,
           'harga_jual'     => $request->harga_jual
       ]);

       return redirect('/barang')->with('success','Data barang berhasil diubah');

   }

   public function destroy(string $id) {
       $check = BarangModel::find($id);
       if(!$check) {
           return redirect('/barang') -> with('error', 'Data barang tidak ditemukan');
       }

       try {
           BarangModel::destroy($id);

           return redirect('/barang')->with('success', 'Data barang berhasil dihapus');
       } catch (\Illuminate\Database\QueryException $e) {
           return redirect('/barang') -> with('error','Data barang gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
       }
   }

    public function create_ajax() {
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama') -> get();

        return view('barang.create_ajax') -> with('kategori', $kategori);
    }
    public function store_ajax(Request $request) {

        if ($request -> ajax() || $request -> wantsJson()) {
            $rules = [
                'barang_kode'    => 'required|string|min:3|unique:m_barang,barang_kode',
                'kategori_id'    => 'required|integer',
                'barang_nama'    => 'required|string|max:100',
                'harga_beli'     => 'required|integer',
                'harga_jual'     => 'required|integer'
                
            ];

            $validator = Validator::make($request -> all(),$rules);

            if ($validator -> fails()) {
                return response() -> json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            BarangModel:: create($request->all());
            return response() -> json([
                'status' => true,
                'message' => 'Data berhasil disimpan!'
            ]);
        }

    }
    public function edit_ajax(string $id) {
        $barang = BarangModel::find($id);
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama') -> get();

        return view('barang.edit_ajax', ['barang' => $barang, 'kategori' => $kategori]);
    }
    public function update_ajax(Request $request, $id)
    {
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'barang_kode'    => 'required|string|min:3|unique:m_barang,barang_kode',
                'kategori_id'    => 'required|integer',
                'barang_nama'    => 'required|string|max:100',
                'harga_beli'     => 'required|integer',
                'harga_jual'     => 'required|integer'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $check = BarangModel::find($id);
        
        if ($check) {

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
        $barang = BarangModel::find($id);

        return view('barang.confirm_ajax', ['barang' => $barang]);
    } 

    public function delete_ajax(Request $request, $id) {
        if ($request -> ajax() || $request -> wantsJson()) {
            $user = BarangModel::find($id);

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

    public function import() {
        return view('barang.import');
    }

    public function import_ajax(Request $request) {
        if ($request->ajax() || $request->wantsJson()) {
            
            // Define validation rules
            $rules = [
                'file_barang' => ['required', 'mimes:xlsx', 'max:1024'], // File must be XLSX, max 1MB
            ];
    
            // Validate the file
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
    
            // Get the uploaded file
            $file = $request->file('file_barang');
    
            try {
                // Load the file using PhpSpreadsheet
                $reader = IOFactory::createReader('Xlsx');
                $reader->setReadDataOnly(true); // Only read data from the file
                $spreadsheet = $reader->load($file->getRealPath());
                $sheet = $spreadsheet->getActiveSheet();
    
                // Convert the sheet data to an array
                $data = $sheet->toArray(null, false, true, true);
    
                $insert = [];
    
                if (count($data) > 1) { // Ensure more than 1 row (skipping header)
                    foreach ($data as $baris => $value) {
                        if ($baris > 1) { // Skip the first row (header)
                            $insert[] = [
                                'kategori_id' => $value['A'],
                                'barang_kode' => $value['B'],
                                'barang_nama' => $value['C'],
                                'harga_beli' => $value['D'],
                                'harga_jual' => $value['E'],
                                'created_at' => now(),
                            ];
                        }
                    }
    
                    if (count($insert) > 0) {
                        // Insert data into the database
                        BarangModel::insertOrIgnore($insert);
                    }
    
                    // Return success response
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil diimport'
                    ]);
                } else {
                    // Return error if no data was imported
                    return response()->json([
                        'status' => false,
                        'message' => 'Tidak ada data yang diimport'
                    ]);
                }
    
            } catch (\Exception $e) {
                // Handle errors in file processing
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat memproses file: ' . $e->getMessage()
                ]);
            }
        }
    
        // If it's not an AJAX request, redirect
        return redirect('/');
    }
}
