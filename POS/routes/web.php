<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::pattern('id', '[0-9]+'); //artinya ketika ada parameter {id}, maka harus berupa angka

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'postRegister']);

Route::middleware(['auth'])->group(function() {
    Route::get('/', [WelcomeController::class, 'index']);

    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/profile/update-photo', [UserController::class, 'updatePhoto'])->name('user.updatePhoto');

    Route::middleware(['authorize:ADM'])->group(function() {
    Route::group(['prefix' => 'user'], function () {
    Route::get('/', [UserController::class, 'index']); // menampilkan halaman awal user
    Route::post('/list', [UserController::class, 'list']); // menampilkan data user dalam bentuk json untuk datatables
    Route::get('/create', [UserController::class, 'create']); // menampilkan halaman form tambah user
    Route::post('/', [UserController::class, 'store']); // menyimpan data user baru
    Route::get('/create_ajax', [UserController::class, 'create_ajax']); // menampilkan halaman form tambah user Ajax
    Route::post('/ajax', [UserController::class, 'store_ajax']); // menyimpan data user baru Ajax
    Route::get('/{id}', [UserController::class, 'show']); // menampilkan detail user
    Route::get('/{id}/edit', [UserController::class, 'edit']); // menampilkan halaman form edit user
    Route::put('/{id}', [UserController::class, 'update']); // menyimpan perubahan data user
    Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']); // menampilkan halaman form edit data user Ajax
    Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']); // menyimpan perubahan data user Ajax
    Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); // untuk tampilkan form confirm delet user Ajax
    Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); // untuk hapus data user Ajax
    Route::get('/import', [UserController::class, 'import']); //ajax form upload excel
    Route::post('/import_ajax', [UserController::class, 'import_ajax']); //ajax import excel
    Route::get('/export_excel', [UserController::class, 'export_excel']); //ajax form export excel
    Route::get('/export_pdf', [UserController::class, 'export_pdf']); //ajax form export pdf
    Route::delete('/{id}', [UserController::class, 'destroy']); // menghapus data user
    });
});

Route::get('/', [WelcomeController::class,'index']);

Route::group(['prefix' => 'level'], function () {
    Route::middleware(['authorize:ADM,MNG'])->group(function() {
    Route::get('/',[LevelController::class, 'index']);
    Route::post('/list',[LevelController::class, 'list']);
    Route::get('/create',[LevelController::class, 'create']);
    Route::get('/create_ajax', [LevelController::class, 'create_ajax']); // menampilkan halaman form tambah level Ajax
    Route::post('/ajax', [LevelController::class, 'store_ajax']); // menyimpan data level baru Ajax
    Route::post('/',[LevelController::class, 'store']);
    Route::get('/{id}',[LevelController::class, 'show']);
    Route::get('/{id}/edit',[LevelController::class, 'edit']);
    Route::put('/{id}',[LevelController::class, 'update']);
    Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']); // menampilkan halaman form edit data level Ajax
    Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']); // menyimpan perubahan data level Ajax
    Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']); // untuk tampilkan form confirm delete level Ajax
    Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']); // untuk hapus data level Ajax
    Route::get('/import', [LevelController::class, 'import']); //ajax form upload excel
    Route::post('/import_ajax', [LevelController::class, 'import_ajax']); //ajax import excel
    Route::get('/export_excel', [LevelController::class, 'export_excel']); //ajax form export excel
    Route::get('/export_pdf', [LevelController::class, 'export_pdf']); //ajax form export pdf
    Route::delete('/{id}',[LevelController::class, 'destroy']);
    });
});

Route::group(['prefix' => 'kategori'], function () {
    Route::middleware(['authorize:ADM,MNG'])->group(function() {
    Route::get('/',[KategoriController::class, 'index']);
    Route::post('/list',[KategoriController::class, 'list']);
    Route::get('/create',[KategoriController::class, 'create']);
    Route::get('/create_ajax', [KategoriController::class, 'create_ajax']); // menampilkan halaman form tambah kategori Ajax
    Route::post('/ajax', [KategoriController::class, 'store_ajax']); // menyimpan data kategori baru Ajax
    Route::post('/',[KategoriController::class, 'store']);
    Route::get('/{id}',[KategoriController::class, 'show']);
    Route::get('/{id}/edit',[KategoriController::class, 'edit']);
    Route::put('/{id}',[KategoriController::class, 'update']);
    Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']); // menampilkan halaman form edit data kategori Ajax
    Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']); // menyimpan perubahan data kategori Ajax
    Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']); // untuk tampilkan form confirm delete kategori Ajax
    Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']); // untuk hapus data kategori Ajax
    Route::get('/import', [KategoriController::class, 'import']); //ajax form upload excel
    Route::post('/import_ajax', [KategoriController::class, 'import_ajax']); //ajax import excel
    Route::get('/export_excel', [KategoriController::class, 'export_excel']); //ajax form export excel
    Route::get('/export_pdf', [KategoriController::class, 'export_pdf']); //ajax form export pdf
    Route::delete('/{id}',[KategoriController::class, 'destroy']);
    });
});

Route::group(['prefix' => 'supplier'], function(){
    Route::middleware(['authorize:ADM,MNG'])->group(function() {
    Route::get('/', [SupplierController::class, 'index'])->name('supplier.index'); // menampilkan halaman awal supplier
    Route::post('/list', [SupplierController::class, 'list']); // menampilkan data supplier dalam bentuk json untuk datables
    Route::get('/create', [SupplierController::class, 'create'])->name('supplier.create'); // menampilkan halaman form tambah supplier
    Route::get('/create_ajax', [SupplierController::class, 'create_ajax']); // menampilkan halaman form tambah supplier Ajax
    Route::post('/ajax', [SupplierController::class, 'store_ajax']); // menyimpan data supplier baru Ajax
    Route::post('/', [SupplierController::class, 'store']); // menyimpan data supplier baru
    Route::get('/{id}', [SupplierController::class, 'show']); // menampilkan detail supplier
    Route::get('/{id}/edit', [SupplierController::class, 'edit']); // menampilkan halaman form edit supplier
    Route::put('/{id}', [SupplierController::class, 'update']); // menyimpan data supplier yang diubah
    Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']); // menampilkan halaman form edit data supplier Ajax
    Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax']); // menyimpan perubahan data supplier Ajax
    Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']); // untuk tampilkan form confirm delete supplier Ajax
    Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']); // untuk hapus data supplier Ajax
    Route::get('/import', [SupplierController::class, 'import']); //ajax form upload excel
    Route::post('/import_ajax', [SupplierController::class, 'import_ajax']); //ajax import excel
    Route::get('/export_excel', [SupplierController::class, 'export_excel']); //ajax form export excel
    Route::get('/export_pdf', [SupplierController::class, 'export_pdf']); //ajax form export pdf
    Route::delete('/{id}', [SupplierController::class, 'destroy']); // menghapus data supplier
    });
});

Route::group(['prefix' => 'barang'], function () {
    Route::middleware(['authorize:ADM,MNG,STF'])->group(function() {
    Route::get('/',[BarangController::class, 'index']);
    Route::post('/list',[BarangController::class, 'list']);
    Route::get('/create',[BarangController::class, 'create']);
    Route::get('/create_ajax', [BarangController::class, 'create_ajax']); // menampilkan halaman form tambah barang Ajax
    Route::post('/ajax', [BarangController::class, 'store_ajax']); // menyimpan data barang baru Ajax
    Route::post('/',[BarangController::class, 'store']);
    Route::get('/{id}',[BarangController::class, 'show']);
    Route::get('/{id}/edit',[BarangController::class, 'edit']);
    Route::put('/{id}',[BarangController::class, 'update']);
    Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']); // menampilkan halaman form edit data barang Ajax
    Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']); // menyimpan perubahan data barang Ajax
    Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']); // untuk tampilkan form confirm delete barang Ajax
    Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']); // untuk hapus data barang Ajax
    Route::get('/import', [BarangController::class, 'import']); //ajax form upload excel
    Route::post('/import_ajax', [BarangController::class, 'import_ajax']); //ajax import excel
    Route::get('/export_excel', [BarangController::class, 'export_excel']); //ajax form export excel
    Route::get('/export_pdf', [BarangController::class, 'export_pdf']); //ajax form export pdf
    Route::delete('/{id}',[BarangController::class, 'destroy']);
    });
});

Route::group(['prefix' => 'stok'], function () {
    Route::middleware(['authorize:ADM,MNG,STF'])->group(function() {
        Route::get('/', [StokController::class, 'index']); // Menampilkan daftar stok
        Route::post('/list', [StokController::class, 'list']); // Mengambil data stok untuk DataTable
        Route::get('/create', [StokController::class, 'create']); // Menampilkan form tambah stok
        Route::get('/create_ajax', [StokController::class, 'create_ajax']); // Menampilkan form tambah stok dengan Ajax
        Route::post('/ajax', [StokController::class, 'store_ajax']); // Menyimpan data stok baru dengan Ajax
        Route::post('/', [StokController::class, 'store']); // Menyimpan data stok baru
        Route::get('/{id}', [StokController::class, 'show']); // Menampilkan detail stok
        Route::get('/{id}/edit', [StokController::class, 'edit']); // Menampilkan form edit stok
        Route::put('/{id}', [StokController::class, 'update']); // Mengupdate data stok
        Route::get('/{id}/edit_ajax', [StokController::class, 'edit_ajax']); // Menampilkan form edit stok dengan Ajax
        Route::put('/{id}/update_ajax', [StokController::class, 'update']); // Mengupdate data stok dengan Ajax
        Route::get('/{id}/delete_ajax', [StokController::class, 'confirm_ajax']); // Menampilkan konfirmasi hapus stok dengan Ajax
        Route::delete('/{id}/delete_ajax', [StokController::class, 'delete_ajax']); // Menghapus data stok dengan Ajax
        Route::get('/import', [StokController::class, 'import']); // Menampilkan form untuk mengimpor stok
        Route::post('/import_ajax', [StokController::class, 'import_ajax']); // Mengimpor stok dengan Ajax
        Route::get('/export_excel', [StokController::class, 'exportExcel']); // Mengekspor stok ke Excel
        Route::get('/export_pdf', [StokController::class, 'export_pdf']); // Mengekspor stok ke PDF
        Route::delete('/{id}', [StokController::class, 'destroy']); // Menghapus data stok
    });
});

    // Route untuk semua role (ADM, MNG, STF) - Hanya View
    Route::middleware(['authorize:ADM,MNG,STF'])->group(function () {
        Route::group(['prefix' => 'penjualan'], function () {
            Route::get('/', [PenjualanController::class, 'index'])->name('penjualan.index'); // Beri nama
            Route::post('/list', [PenjualanController::class, 'list'])->name('penjualan.list'); //
            Route::get('/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');
            //Route::get('/{id}', [PenjualanController::class, 'show']); // Jika ada halaman detail non-ajax
            Route::get('/{id}/show_ajax', [PenjualanController::class, 'show_ajax'])->name('penjualan.show.ajax'); // Menampilkan detail penjualan dengan Ajax
    
        });
    });

    // Route khusus Admin & Staff/Kasir (ADM, STF) - CRUD
    Route::middleware(['authorize:ADM,STF'])->group(function () {
        Route::group(['prefix' => 'penjualan'], function () {
            // Create
            Route::get('/create_ajax', [PenjualanController::class, 'create_ajax'])->name('penjualan.create_ajax');
            Route::post('/ajax', [PenjualanController::class, 'store_ajax'])->name('penjualan.store_ajax');

            // Update
            Route::get('/{id}/edit_ajax', [PenjualanController::class, 'edit_ajax'])->name('penjualan.edit_ajax');
            Route::put('/{id}/update_ajax', [PenjualanController::class, 'update_ajax'])->name('penjualan.update_ajax');

            // Delete
            Route::get('/{id}/delete_ajax', [PenjualanController::class, 'confirm_ajax'])->name('penjualan.confirm_ajax');
            Route::delete('/{id}/delete_ajax', [PenjualanController::class, 'delete_ajax'])->name('penjualan.delete_ajax');
        });
    });
});

// Route::group(['prefix' => 'penjualan'], function () {
//     // Middleware otorisasi (sesuaikan dengan kebutuhan Anda)
//     Route::middleware(['authorize:ADM,MNG,STF'])->group(function() {
//         Route::get('/', [PenjualanController::class, 'index'])->name('penjualan.index'); // Menampilkan daftar penjualan
//         Route::post('/list', [PenjualanController::class, 'list'])->name('penjualan.list'); // Mengambil data penjualan untuk DataTable

//         // Operasi Tambah
//         Route::get('/create', [PenjualanController::class, 'create']); // Jika ada halaman form create non-ajax
//         Route::get('/create_ajax', [PenjualanController::class, 'create_ajax'])->name('penjualan.create.ajax'); // Menampilkan form tambah penjualan dengan Ajax
//         Route::post('/', [PenjualanController::class, 'store']); // Jika ada submit form create non-ajax
//         Route::post('/ajax', [PenjualanController::class, 'store_ajax'])->name('penjualan.store.ajax'); // Menyimpan data penjualan baru dengan Ajax

        // Operasi Detail
        Route::get('/{id}', [PenjualanController::class, 'show']); // Jika ada halaman detail non-ajax
        Route::get('/{id}/show_ajax', [PenjualanController::class, 'show_ajax'])->name('penjualan.show.ajax'); // Menampilkan detail penjualan dengan Ajax

//         // Operasi Edit
//         Route::get('/{id}/edit', [PenjualanController::class, 'edit']); // Jika ada halaman form edit non-ajax
//         Route::get('/{id}/edit_ajax', [PenjualanController::class, 'edit_ajax'])->name('penjualan.edit.ajax'); // Menampilkan form edit penjualan dengan Ajax
//         Route::put('/{id}', [PenjualanController::class, 'update']); // Jika ada submit form edit non-ajax
//         Route::put('/{id}/update_ajax', [PenjualanController::class, 'update_ajax'])->name('penjualan.update.ajax'); // Mengupdate data penjualan dengan Ajax

//         // Operasi Hapus
//         Route::get('/{id}/delete_ajax', [PenjualanController::class, 'confirm_ajax'])->name('penjualan.delete.confirm.ajax'); // Menampilkan konfirmasi hapus penjualan dengan Ajax
//         Route::delete('/{id}', [PenjualanController::class, 'destroy']); // Jika ada request hapus non-ajax
//         Route::delete('/{id}/delete_ajax', [PenjualanController::class, 'delete_ajax'])->name('penjualan.delete.ajax'); // Menghapus data penjualan dengan Ajax
//     });
// });
//});

