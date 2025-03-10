<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\LevelModel;

class UserController extends Controller
{
    public function index()
    {
        // tambah data user dengan Eloquent Model
        // $data = [
            // 'nama' => 'Pelanggan Pertama',
            // 'username' => 'customer-1',
            // 'nama' => 'Pelanggan',
            // 'password' => Hash::make('12345'),
            // 'level_id' => 4
        //     'level_id' => 2,
        //     'username' => 'manager_tiga',
        //     'nama' => 'Manager 3',
        //     'password' => Hash::make('12345')
        // ];
        // UserModel::create($data);
        // UserModel::where('username', 'customer-1')->update($data); // update data user
        // UserModel::insert($data); // tambahkan data ke tabel m_user

        // coba akses model UserModel
        // $user = UserModel::find(1);
        // $user = UserModel::where('level_id', 1)->first();
        // $user = UserModel::firstWhere('level_id', 1);         
        
        // $user = UserModel::findOr(1, ['username', 'nama'], function () {
        //     abort(404);
        // });

        //mengembalikan satu contoh model atau, jika tidak ada hasil yang ditemukan maka akan menjalankan didalam fungsi
        // $user = UserModel::findOr(20, ['username', 'nama'], function () {
        //     abort(404);
        // });

        // $user = UserModel::findOrFail(1); //mengambil hasil pertama dari kueri; namun, jika tidak ada hasil yang ditemukan, sebuah Illuminate\Database\Eloquent\ModelNotFoundException akan dilempar
        // $user = UserModel::where('username', 'manager9')->firstOrFail(); 

        // $user = UserModel::where('level_id', 2)->count();
        // dd($user);

        // $jumlahPengguna = UserModel::where('level_id', 2)->count();
        
        // $user = UserModel::firstOrCreate( //melakukan retrieving data (mengambil data) berdasarkan nilai yang ingin dicari, jika data tidak ditemukan maka method ini akan melakukan insert ke table datadase tersebut sesuai dengan nilai yang dimasukkan
        //     [
        //         'username' => 'manager22',
        //         'nama' => 'Manager Dua Dua',
        //         'password' => Hash::make('12345'),
        //         'level_id' => 2
        //     ],
        // );

        // $user = UserModel::firstOrNew( //menemukan/mengambil record/data dalam database yang cocok dengan atribut yang diberikan
        //     [
        //         'username' => 'manager33',
        //         'nama' => 'Manager Tiga Tiga',
        //         'password' => Hash::make(12345),
        //         'level_id' => 2
        //     ],
        // );
        // $user->save();
        // return view('user', ['data' => $user]);

    //     $user = UserModel::create([
    //         'username' => 'manager55',
    //         'nama' => 'Manager55',
    //         'password' => Hash::make('12345'),
    //         'level_id' => 2,
    //     ]);

    //     $user->username = 'manager56';

    //     $user->isDirty(); // true
    //     $user->isDirty('username'); // true
    //     $user->isDirty('nama'); // false
    //     $user->isDirty(['nama', 'username']); // true

    //     $user->isClean(); // false
    //     $user->isClean('nama'); // true
    //     $user->isClean('username'); // false
    //     $user->isClean(['nama', 'username']); // false

    //     $user->save();

    //     $user->isDirty(); // false
    //     $user->isClean(); // true //menentukan apakah suatu atribut tetap tidak berubah sejak model diambil
    //     dd($user->isDirty()); //menentukan apakah ada atribut model yang telah diubah sejak model diambil
    
        // $user = UserModel::create([
        //     'username' => 'manager66',
        //     'nama' => 'Manager66',
        //     'password' => Hash::make('12345'),
        //     'level_id' => 2,
        // ]);
    
        // $user->username = 'manager65';
    
        // $user->save();
    
        // //menentukan apakah ada atribut yang diubah saat model terakhir disimpan dalam siklus permintaan saat ini
        // $user->wasChanged(); // true
        // $user->wasChanged('username'); // true
        // $user->wasChanged(['username', 'level_id']); // true
        // $user->wasChanged('nama'); // false
        // dd($user->wasChanged(['nama', 'username'])); // true    

        // $user = UserModel::all();
        // return view('user', ['data' => $user]);

        // $user = UserModel::with('level')->get();
        // dd($user);

        $user = UserModel::with('level')->get();
        return view('user', ['data' => $user]);

    }

    public function tambah()
    {
        return view('user_tambah');
    }

    public function tambah_simpan(Request $request)
    {
        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => Hash::make($request->password),
            'level_id' => $request->level_id
        ]);

        return redirect('/user');
    }

    public function ubah($id)
    {
        $user = UserModel::find($id);
        return view('user_ubah', ['data' => $user]);
    }

    public function ubah_simpan($id, Request $request)
    {
        $user = UserModel::find($id);
        $user->username = $request->username;
        $user->nama = $request->nama;
        $user->password = Hash::make($request->password);
        $user->level_id = $request->level_id;

        $user->save();
        return redirect('/user');
    }

    public function hapus($id)
    {
        $user = UserModel::find($id);
        $user->delete();

        return redirect('/user');
    }
}
