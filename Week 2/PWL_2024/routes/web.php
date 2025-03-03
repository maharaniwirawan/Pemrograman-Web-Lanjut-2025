<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/hello', function () {
    return 'Hello World';
});

Route::get('/world', function () {
    return 'World';
});

Route::get('/welcome', function () {
    return 'Selamat Datang';
});

Route::get('/about', function () {
    return 'Nama: Maharani Wirawan <br> NIM: 2341760111';
});

Route::get('/user/{maharani}', function ($name = 'Maharani Wirawan') {
    return 'Nama saya '. $name;
});

Route::get('/posts/{post}/comments/{comment}', function ($postId, $commentId) {
    return 'Pos ke-'. $postId." Komentar ke-: ".$commentId;
});

Route::get('/articles/{id}', function ($commentId) {
    return 'Halaman Artikel dengan ID '.$commentId;
});

Route::get('/user/{name?}', function ($name=null) {
    return 'Nama saya  '.$name;
});

Route::get('/user/{name?}', function ($name='John') {
    return 'Nama saya  '.$name;
});

Route::get('/hello', [WelcomeController::class,'hello']);

Route::resource('photos', PhotoController::class);

Route::resource('photos', PhotoController::class)->only([
    'index', 'show'
]);

Route::resource('photos', PhotoController::class)->except([
    'create', 'store', 'update', 'destroy'
]);

Route::get('/greeting', function () {
    return view('hello', ['name' => 'Maharani']);
});

Route::get('/greeting', [WelcomeController::class, 'greeting']);

Route::get('/greeting', function () {
    return view('blog.hello', ['name' => "Maharani"], ['occupation' => "a programmer"]);
});


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