<?php

use App\Http\Controllers\APIController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\PenjualanController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Monolog\Level;

Route::pattern('id','[0-9]+'); //meaning: ketika ada parameter "id" maka nilainya harus angka, yaitu dari 0 sampai 9.

Route::get('login', [AuthController::class, 'login']) -> name('login');
Route::post('login', [AuthController::class, 'postLogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);


Route::middleware(['auth'])->group(function() { //meaning: agar semua method pada web.php meleweati middleware auth.php

Route::get('/import_pfp', [UserController::class, 'import_pfp']); //buka form import
Route::post('/import_ajax_pfp', [UserController::class, 'import_ajax_pfp']); //import foto pfp ajax
Route::post('/edit_profile_save', [UserController::class, 'edit_profile_save']); //simpan edit

Route::get('/edit_profile', [UserController::class, 'edit_profile']); //buka form edit profile

Route::get('/level', [LevelController::class, 'index']);

Route::get('/kategori', [KategoriController::class, 'index']);

Route::get('/', [WelcomeController::class,'index']);

// yg dibawah ini adalah semua route lainnya yg harus login dulu baru bisa akses

Route::group(['prefix' => 'user', 'middleware' => 'authorize:ADM'], function() {
    Route::get('/', [UserController::class, 'index']); //Menampilkan laman awal user
    Route::post('/list', [UserController::class, 'list']); //menampilkan data user dalam bentuk json untuk datatables.
    Route::get('/create', [UserController::class, 'create']); //Membuat data baru
    Route::post('/', [UserController::class, 'store']); //Menyimpan data yang telah dibuat

    Route::get('/create_ajax', [UserController::class, 'create_ajax']); //menambah data user dengan ajax
    Route::post('/ajax', [UserController::class, 'store_ajax']); //menyimpan data yg telah dibuat dengan ajax
    
    Route::get('/{id}', [UserController::class, 'show']); //menampilkan detail data user
    Route::get('/{id}/show_ajax', [UserController::class, 'show_ajax']);

    Route::get('/{id}/edit', [UserController::class, 'edit']); //Edit data user tertentu
    Route::put('/{id}', [UserController::class, 'update']); //Menyimpan perubahan data user 

    Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']); //edit data user dengan ajax
    Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']); //menyimpan perubahan data dengan ajax

    Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); //Munculkan pop up konfirmasi delete dengan ajax
    Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); //Menghapus data user dengan ajax

    Route::get('/import', [UserController::class, 'import']); //import excel
    Route::post('/import_ajax', [UserController::class, 'import_ajax']); //import excel dengan ajax
    Route::get('/export_excel', [UserController::class, 'export_excel']); //export excel
    Route::get('/export_pdf', [UserController::class, 'export_pdf']); //export pdf

    Route::get('/export_pdf', [UserController::class, 'export_pdf']); //export pdf

    Route::delete('/{id}/delete_ajax', [UserController::class, 'destroy']); //Menghapus data user

} );

Route::group(['prefix' => 'level', 'middleware' => 'authorize:ADM'], function() { 
    Route::get('/', [LevelController::class, 'index']); //Menampilkan laman awal level
    Route::post('/list', [LevelController::class, 'list']); //menampilkan data level dalam bentuk json untuk datatables.
    Route::get('/create', [LevelController::class, 'create']); //Membuat data level
    Route::post('/', [LevelController::class, 'store']); //Menyimpan data yang telah dibuat

    Route::get('/create_ajax', [LevelController::class, 'create_ajax']); //Buat data level w ajax
    Route::post('/ajax', [LevelController::class, 'store_ajax']); //menyimpan data level baru w ajax

    Route::get('/{id}', [LevelController::class, 'show']); //menampilkan detail data level?
    Route::get('/{id}/show_ajax', [LevelController::class, 'show_ajax']);

    Route::get('/{id}/edit', [LevelController::class, 'edit']); //Edit data level tertentu
    Route::put('/{id}', [LevelController::class, 'update']); //Menyimpan perubahan data level 

    Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']); //edit data level dengan ajax
    Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']); //menyimpan perubahan data dengan ajax

    Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']); //Munculkan pop up konfirmasi delete dengan ajax
    Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']); //Menghapus data user dengan ajax

    Route::get('/import', [LevelController::class, 'import']); //import excel
    Route::post('/import_ajax', [LevelController::class, 'import_ajax']); //import excel dengan ajax
    Route::get('/export_excel', [LevelController::class, 'export_excel']); //export excel
    Route::get('/export_pdf', [LevelController::class, 'export_pdf']); //export pdf

    Route::delete('/{id}', [LevelController::class, 'destroy']); //Menghapus data level
} );

Route::group(['prefix' => 'kategori', 'middleware' => 'authorize:ADM, MNG'], function() {
    Route::get('/', [KategoriController::class, 'index']); //Menampilkan laman awal kategori
    Route::post('/list', [KategoriController::class, 'list']); //menampilkan data kategori dalam bentuk json untuk datatables.
    Route::get('/create', [KategoriController::class, 'create']); //Membuat data kategori
    Route::post('/', [KategoriController::class, 'store']); //Menyimpan data yang telah dibuat

    Route::get('/create_ajax', [KategoriController::class, 'create_ajax']); //Buat data level w ajax
    Route::post('/ajax', [KategoriController::class, 'store_ajax']); //menyimpan data baru w ajax

    Route::get('/{id}', [KategoriController::class, 'show']); //menampilkan detail data kategori?
    Route::get('/{id}/show_ajax', [KategoriController::class, 'show_ajax']); //menampilkan detail data kategori dengan ajax

    Route::get('/{id}/edit', [KategoriController::class, 'edit']); //Edit data kategori tertentu
    Route::put('/{id}', [KategoriController::class, 'update']); //Menyimpan perubahan data kategori 

    Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']); //edit data kategori dengan ajax
    Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']); //menyimpan perubahan data dengan ajax


    Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']); //Munculkan pop up konfirmasi delete dengan ajax
    Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']); //Menghapus data user dengan ajax

    Route::get('/import', [KategoriController::class, 'import']); //import excel
    Route::post('/import_ajax', [KategoriController::class, 'import_ajax']); //import excel dengan ajax
    Route::get('/export_excel', [KategoriController::class, 'export_excel']); //export excel

    Route::get('/export_pdf', [KategoriController::class, 'export_pdf']); //export pdf

    Route::delete('/{id}', [KategoriController::class, 'destroy']); //Menghapus data kategori
} );

Route::group(['prefix' => 'supplier',  'middleware' => 'authorize:ADM,MNG'], function() {
    Route::get('/', [SupplierController::class, 'index']); // 
    Route::post('/list', [SupplierController::class, 'list']); //
    Route::get('/create', [SupplierController::class, 'create']); //
    Route::post('/', [SupplierController::class, 'store']); //

    Route::get('/create_ajax', [SupplierController::class, 'create_ajax']); //Buat data supplier w ajax
    Route::post('/ajax', [SupplierController::class, 'store_ajax']); //menyimpan data baru w ajax

    Route::get('/{id}/show_ajax', [SupplierController::class, 'show_ajax']); //
    Route::get('/{id}/edit', [SupplierController::class, 'edit']); //
    Route::put('/{id}', [SupplierController::class, 'update']); // 

    Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']); //edit data kategori dengan ajax
    Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax']); //menyimpan perubahan data dengan ajax


    Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']); //Munculkan pop up konfirmasi delete dengan ajax
    Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']); //Menghapus data user dengan ajax


    Route::get('/import', [SupplierController::class, 'import']); //import excel
    Route::post('/import_ajax', [SupplierController::class, 'import_ajax']); //import excel dengan ajax
    Route::get('/export_excel', [SupplierController::class, 'export_excel']); //export excel

    Route::get('/export_pdf', [SupplierController::class, 'export_pdf']); //export pdf

    Route::delete('/{id}', [SupplierController::class, 'destroy']); //
} );

Route::group(['prefix' => 'barang', 'middleware' => 'authorize:ADM,MNG,STF'], function() {
    Route::get('/', [BarangController::class, 'index']); //Menampilkan laman awal barang
    Route::post('/list', [BarangController::class, 'list']); //menampilkan data barang dalam bentuk json untuk datatables.
    Route::get('/create', [BarangController::class, 'create']); //Membuat data barang
    Route::post('/', [BarangController::class, 'store']); //Menyimpan data yang telah dibuat

    Route::get('/create_ajax', [BarangController::class, 'create_ajax']); //menambah data barang dengan ajax
    Route::post('/ajax', [BarangController::class, 'store_ajax']); //menyimpan data yg telah dibuat dengan ajax

    Route::get('/{id}/show_ajax', [BarangController::class, 'show_ajax']); //menampilkan detail data barang?
    Route::get('/{id}/edit', [BarangController::class, 'edit']); //Edit data barangtertentu
    Route::put('/{id}', [BarangController::class, 'update']); //Menyimpan perubahan data barang 

    Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']); //edit data barang dengan ajax
    Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']); //menyimpan perubahan data dengan ajax

    Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']); //Munculkan pop up konfirmasi delete dengan ajax
    Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']); //Menghapus data barang dengan ajax

    Route::get('/import', [BarangController::class, 'import']); //import excel
    Route::post('/import_ajax', [BarangController::class, 'import_ajax']); //import excel dengan ajax

    Route::get('/export_excel', [BarangController::class, 'export_excel']); //export excel
    Route::get('/export_pdf', [BarangController::class, 'export_pdf']); //export pdf

    Route::delete('/{id}', [BarangController::class, 'destroy']); //Menghapus data barang

} );

Route::group(['prefix' => 'stok', 'middleware' => 'authorize:ADM,MNG,STF'], function() {
    Route::get('/', [StokController::class, 'index']); //Menampilkan laman awal stok
    Route::post('/list', [StokController::class, 'list']); //menampilkan data stok dalam bentuk json untuk datatables.

    Route::get('/create_ajax', [StokController::class, 'create_ajax']); //menambah data barang dengan ajax
    Route::post('/ajax', [StokController::class, 'store_ajax']); //menyimpan data yg telah dibuat dengan ajax

    Route::get('/{id}/show_ajax', [StokController::class, 'show_ajax']); //menampilkan detail data stok?
    
    Route::get('/{id}/edit_ajax', [StokController::class, 'edit_ajax']); //edit data barang dengan ajax
    Route::put('/{id}/update_ajax', [StokController::class, 'update_ajax']); //menyimpan perubahan data dengan ajax

    Route::get('/{id}/delete_ajax', [StokController::class, 'confirm_ajax']); //Munculkan pop up konfirmasi delete dengan ajax
    Route::delete('/{id}/delete_ajax', [StokController::class, 'delete_ajax']); //Menghapus data barang dengan ajax

    Route::get('/import', [StokController::class, 'import']); //import excel
    Route::post('/import_ajax', [StokController::class, 'import_ajax']); //import excel dengan ajax

    Route::get('/export_excel', [StokController::class, 'export_excel']); //export excel
    Route::get('/export_pdf', [StokController::class, 'export_pdf']); //export pdf

} );

Route::group(['prefix' => 'penjualan', 'middleware' => 'authorize:ADM,MNG,STF'], function() {
    Route::get('/', [PenjualanController::class, 'index']); //Menampilkan laman awal stok
    Route::post('/list', [PenjualanController::class, 'list']); //menampilkan data stok dalam bentuk json untuk datatables.

    Route::get('/create_ajax', [PenjualanController::class, 'create_ajax']); //menambah data barang dengan ajax
    Route::post('/ajax', [PenjualanController::class, 'store_ajax']); //menyimpan data yg telah dibuat dengan ajax

    Route::get('/{id}/show_ajax', [PenjualanController::class, 'show_ajax']); //menampilkan detail data stok?
    
    Route::get('/{id}/edit_ajax', [PenjualanController::class, 'edit_ajax']); //edit data barang dengan ajax
    Route::put('/{id}/update_ajax', [PenjualanController::class, 'update_ajax']); //menyimpan perubahan data dengan ajax

    Route::get('/{id}/delete_ajax', [PenjualanController::class, 'confirm_ajax']); //Munculkan pop up konfirmasi delete dengan ajax
    Route::delete('/{id}/delete_ajax', [PenjualanController::class, 'delete_ajax']); //Menghapus data barang dengan ajax

    Route::get('/import', [PenjualanController::class, 'import']); //import excel
    Route::post('/import_ajax', [PenjualanController::class, 'import_ajax']); //import excel dengan ajax

    Route::get('/export_excel', [PenjualanController::class, 'export_excel']); //export excel
    Route::get('/export_pdf', [PenjualanController::class, 'export_pdf']); //export pdf

} );
});




