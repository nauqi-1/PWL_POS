<?php

namespace App\Http\Controllers;

use App\Models\SupplierModel;
use App\Models\StokModel;
use App\Models\UserModel;
use App\Models\BarangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Database\Seeders\StokSeeder;

class StokController extends Controller
{
    public function index() {
        $breadcrumb = (object) [
           'title' => 'Daftar Stok',
           'list' => ['Home','Stok']
        ];

        $page = (object) [
           'title' => 'Detail transaksi stok.'
        ];

        $activeMenu = 'stok'; //set menu yang sedang aktif

        $supplier = SupplierModel::all(); //mengambil data supplier untuk filtering
        $user = UserModel::all(); //ambil data user untuk filter

        return view('stok.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'supplier' => $supplier,'user' => $user, 'activeMenu' => $activeMenu]);
   }

   public function list(Request $request){
    $stoks = StokModel::select('stok_id', 'supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah')
        -> with(['supplier', 'barang','user']);


        //set data untuk filtering
    if($request->supplier_id){ //filter supplier
        $stoks->where('supplier_id', $request->supplier_id);
    }

    if ($request->user_id) { //filter penerima
        $stoks->where('user_id', $request->user_id);
    }
    
    return DataTables::of($stoks)
    ->addIndexColumn()  
    ->addColumn('aksi', function ($stok) { 

 $btn = '<button onclick="modalAction(\''.url('/stok/' . $stok->stok_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
 $btn .= '<button onclick="modalAction(\''.url('/stok/' . $stok->stok_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
 $btn .= '<button onclick="modalAction(\''.url('/stok/' . $stok->stok_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';

 return $btn; 
    }) 
    ->rawColumns(['aksi'])
    ->make(true);
}
    public function create_ajax() {
    $supplier = SupplierModel::select('supplier_id', 'supplier_nama') -> get();
    $barang = BarangModel::select('barang_id', 'barang_nama') -> get();
    $user = UserModel::select('user_id', 'nama') -> get();


    return view('stok.create_ajax') -> with(['supplier'=> $supplier, 'barang' => $barang, 'user' =>$user]);
}
    public function store_ajax(Request $request) {

    if ($request -> ajax() || $request -> wantsJson()) {
        $rules = [
            'supplier_id'    => 'required|integer',
            'barang_id'    => 'required|integer',
            'user_id'    => 'required|integer',
            'stok_tanggal'     => 'required|date',
            'stok_jumlah'     => 'required|integer'
            
        ];

        $validator = Validator::make($request -> all(),$rules);

        if ($validator -> fails()) {
            return response() -> json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        StokModel:: create($request->all());
        return response() -> json([
            'status' => true,
            'message' => 'Data berhasil disimpan!'
        ]);
    }

    }
    public function edit_ajax(string $id) {
        $stok = StokModel::find($id);
        $supplier = SupplierModel::select('supplier_id', 'supplier_nama') -> get();
        $barang = BarangModel::select('barang_id', 'barang_nama') -> get();
        $user = UserModel::select('user_id', 'nama') -> get();


        return view('stok.edit_ajax') -> with(['stok' => $stok, 'supplier'=> $supplier, 'barang' => $barang, 'user' =>$user]);
    }
    public function update_ajax(Request $request, $id)
    {
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'supplier_id'    => 'required|integer',
            'barang_id'    => 'required|integer',
            'user_id'    => 'required|integer',
            'stok_tanggal'     => 'required|date',
            'stok_jumlah'     => 'required|integer'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $check = StokModel::find($id);
        
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
        $stok = StokModel::find($id);

        return view('stok.confirm_ajax', ['stok' => $stok]);
    } 

    public function delete_ajax(Request $request, $id) {
        if ($request -> ajax() || $request -> wantsJson()) {
            $stok = StokModel::find($id);

            if ($stok) {
                $stok->delete();
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

    public function show_ajax(string $id) {
        $stok = StokModel::find($id);

        return view('stok.show_ajax', ['stok' => $stok]);
    } 

    public function import() {
        return view('stok.import');
    }
 
    public function import_ajax(Request $request) {
        if ($request->ajax() || $request->wantsJson()) {
            
            // Define validation rules
            $rules = [
                'file_stok' => ['required', 'mimes:xlsx', 'max:1024'], // File must be XLSX, max 1MB
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
            $file = $request->file('file_stok');
    
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
                                'supplier_id' => $value['A'],
                                'barang_id' => $value['B'],
                                'user_id' => $value['C'],
                                'stok_tanggal' => $value['D'],
                                'stok_jumlah' => $value['E'],
                                'created_at' => now(),
                            ];
                        }
                    }
    
                    if (count($insert) > 0) {
                        StokModel::insertOrIgnore($insert);
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
        $stok = StokModel::select('supplier_id', 'barang_id', 'user_id','stok_tanggal','stok_jumlah') 
                -> orderBy('stok_tanggal')
                -> with(['supplier','barang','user' ])
                -> get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet-> getActiveSheet();

        $sheet->setCellValue('A1','No');
        $sheet->setCellValue('B1','Supplier');
        $sheet->setCellValue('C1','Stok Barang');
        $sheet->setCellValue('D1','Penerima');
        $sheet->setCellValue('E1','Tanggal Stok');
        $sheet->setCellValue('F1','Jumlah Stok');

        $no = 1;
        $baris = 2;
        foreach($stok as $key => $value) {
            $sheet->setCellValue('A'.$baris,$no);
            $sheet->setCellValue('B'.$baris,$value -> supplier-> supplier_nama);
            $sheet->setCellValue('C'.$baris,$value -> barang -> barang_nama);
            $sheet->setCellValue('D'.$baris,$value -> user -> nama);
            $sheet->setCellValue('E'.$baris,$value -> stok_tanggal);
            $sheet->setCellValue('F'.$baris,$value -> stok_jumlah); 
            $baris++;
            $no++;
        }

        foreach(range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); //set ukuran kolom otomatis
        }

        $sheet->setTitle('Data Stok');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Stok' . date('Y-m-d H:i:s'). '.xlsx';

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

    public function export_pdf() {
        $stok = StokModel::select('supplier_id', 'barang_id', 'user_id','stok_tanggal','stok_jumlah') 
                -> orderBy('stok_tanggal')
                -> with(['supplier','barang','user' ])
                -> get();
        // use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('stok.export_pdf', ['stok' => $stok]);
        $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url $pdf->render();
        return $pdf->stream ('Data Stok '.date('Y-m-d H:i:s').'.pdf');
    }
}
