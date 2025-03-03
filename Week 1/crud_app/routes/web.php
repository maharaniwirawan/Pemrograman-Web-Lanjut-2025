<?php

use Illuminate\Support\Facades\Route; //Mengimpor Route untuk mendefinisikan rute dalam aplikasi 
use App\Http\Controllers\ItemController; //Mengimpor ItemController agar dapat digunakan dalam routing

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

Route::get('/', function () { //Menetapkan rute untuk halaman utama 
    return view("welcome"); //menampilkan tampilan welcome.blade.php.
});

Route::resource('items', ItemController::class); //Mendaftarkan semua rute CRUD secara otomatis untuk ItemController, menghubungkan endpoint /items dengan metode seperti index, create, store, show, edit, update, dan destroy.
