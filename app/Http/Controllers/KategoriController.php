<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use App\Models\KategoriModel;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;



class KategoriController extends Controller
{

      public function index() {

        $breadcrumb = (object) [
            'title' => 'Daftar Kategori',
            'list' => ['Home','Kategori']
         ];

         $page = (object) [
            'title' => 'Daftar kategori yang ada'
         ];

         $activeMenu = 'kategori'; //set menu yang sedang aktif

         return view('kategori.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request){
        $kategoris = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama');

        
        return DataTables::of($kategoris)
        ->addIndexColumn()  
        ->addColumn('aksi', function ($kategori) { 
$btn = '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
$btn .= '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
$btn .= '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
    
return $btn; 
        }) 
        ->rawColumns(['aksi'])
        ->make(true);
    }

    public function create() {
        $breadcrumb = (object) [
            'title' => 'Tambah Kategori',
            'list' => ['Home','Kategori','Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah kategori baru'
        ];

        $activeMenu = 'kategori';

        return view('kategori.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function store(Request $request) {
        $request -> validate ([
            'kategori_kode'  => 'required|string|unique:m_kategori,kategori_kode',
            'kategori_nama'  => 'required|string|max:100',

        ]);

        KategoriModel::create([
            'kategori_kode'  => $request->kategori_kode,
            'kategori_nama'  => $request->kategori_nama
        ]);

        return redirect('/kategori')->with('success', 'Data kategori berhasil disimpan.');
    }

    public function show(string $id) {
        $kategori = KategoriModel::find($id);
        $breadcrumb = (object) [
            'title' => 'Detail Kategori',
            'list'  => ['Home','Kategori','Detail']
        ];

        $page = (object) [
            'title' => 'Detail Kategori',
        ];

        $activeMenu = 'kategori';

        return view('kategori.show', ['breadcrumb' => $breadcrumb, 'page' => $page,'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }

    public function edit(string $id) {
        $kategori = KategoriModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Edit Kategori',
            'list' => ['Home','Kategori','Edit'],
        ];

        $page = (object) [
            'title' => 'Edit Kategori'
        ];

        $activeMenu = 'kategori';

        return view('kategori.edit', ['breadcrumb' => $breadcrumb, 'kategori' => $kategori, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function update(Request $request, string $id) {
        $request -> validate( [ 
            'kategori_kode'  => 'required|string|unique:m_kategori,kategori_kode',
            'kategori_nama'  => 'required|string|max:100',
        ]);

        kategoriModel::find($id)->update([
            'kategori_kode'  => $request->kategori_kode,
            'kategori_nama'  => $request->kategori_nama
        ]);

        return redirect('/kategori')->with('success','Data kategori berhasil diubah');

    }

    public function destroy(string $id) {
        $check = kategoriModel::find($id);
        if(!$check) {
            return redirect('/kategori') -> with('error', 'Data kategori tidak ditemukan');
        }

        try {
            kategoriModel::destroy($id);

            return redirect('/kategori')->with('success', 'Data kategori berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/kategori') -> with('error','Data kategori gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function create_ajax() {

        return view('kategori.create_ajax');
    }

    public function store_ajax(Request $request) {

        if ($request -> ajax() || $request -> wantsJson()) {
            $rules = [
                'kategori_kode'  => 'required|string|unique:m_kategori,kategori_kode',
                'kategori_nama'  => 'required|string|max:100',
            ];

            $validator = Validator::make($request -> all(),$rules);

            if ($validator -> fails()) {
                return response() -> json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            KategoriModel:: create($request->all());
            return response() -> json([
                'status' => true,
                'message' => 'Data berhasil disimpan!'
            ]);
        }
        redirect('/');
    }

    public function edit_ajax(string $id) {
        $kategori = KategoriModel::find($id);

        return view('kategori.edit_ajax', ['kategori' => $kategori]);
    }

    public function update_ajax(Request $request, $id)
    {
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'kategori_kode'  => 'required|string|unique:m_kategori,kategori_kode',
                'kategori_nama'  => 'required|string|max:100',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $check = KategoriModel::find($id);
        
        if ($check) {
            if (!$request->filled('password')) { 
                $request->request->remove('password');
            }

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
        $kategori = KategoriModel::find($id);

        return view('kategori.confirm_ajax', ['kategori' => $kategori]);
    } 

    public function delete_ajax(Request $request, $id) {
        if ($request -> ajax() || $request -> wantsJson()) {
            $kategori = KategoriModel::find($id);

            if ($kategori) {
                $kategori->delete();
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
        $kategori = KategoriModel::select( 'kategori_kode', 'kategori_nama',) 
            ->orderBy('kategori_kode') 
            ->get();
        // use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('kategori.export_pdf', ['kategori' => $kategori]);
        $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url $pdf->render();
        return $pdf->stream ('Data Barang '.date('Y-m-d H:i:s').'.pdf');
    }

    public function show_ajax(string $id) {
        $kategori = KategoriModel::find($id);

        return view('kategori.show_ajax', ['kategori' => $kategori]);
    }
    
    public function import() {
        return view('kategori.import');
    }
 
    public function import_ajax(Request $request) {
        if ($request->ajax() || $request->wantsJson()) {
            
            $rules = [
                'file_kategori' => ['required', 'mimes:xlsx', 'max:1024'], 
            ];
    
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
    
            $file = $request->file('file_kategori');
    
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
                                'kategori_kode' => $value['A'],
                                'kategori_nama' => $value['B'],
                                'created_at' => now(),
                            ];
                        }
                    }
    
                    if (count($insert) > 0) {
                        KategoriModel::insertOrIgnore($insert);
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
        $kategori = KategoriModel::select('kategori_kode', 'kategori_nama') 
                -> orderBy('kategori_kode')
                -> get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet-> getActiveSheet();

        $sheet->setCellValue('A1','No');
        $sheet->setCellValue('B1','Kode');
        $sheet->setCellValue('C1','Nama');

        $no = 1;
        $baris = 2;
        foreach($kategori as $key => $value) {
            $sheet->setCellValue('A'.$baris,$no);
            $sheet->setCellValue('B'.$baris,$value -> kategori_kode);
            $sheet->setCellValue('C'.$baris,$value -> kategori_nama);
            $baris++;
            $no++;
        }

        foreach(range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); //set ukuran kolom otomatis
        }

        $sheet->setTitle('Data Kategori');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Kategori' . date('Y-m-d H:i:s'). '.xlsx';

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
