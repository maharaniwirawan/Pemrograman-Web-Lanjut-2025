<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    //menampilkan halaman awal user
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];
        
        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];
        
        $activeMenu = 'user'; // set menu yang sedang aktif
        
        $m_user = UserModel::all();
        $level = LevelModel::all(); // ambil data level untuk filter level

        return view('user.index', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);        
    }

    // Ambil data user dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
            ->with('level');

        //Filter data user berdasarkan level_id
        if ($request->level_id) {
            $users->where('level_id', $request->level_id);
        }
        return DataTables::of($users)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('aksi', function ($user) { // menambahkan kolom aksi
                // $btn = '<a href="'.url('/user/' . $user->user_id).'" class="btn btn-info btn-sm">Detail</a> ';
                // $btn .= '<a href="'.url('/user/' . $user->user_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                // $btn .= '<form class="d-inline-block" method="POST" action="'.url('/user/'.$user->user_id).'">'.
                //             csrf_field() . method_field('DELETE') .
                //             '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button>'.
                //         '</form>';
                $btn = '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/show_ajax') . '\')" 
                        class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/edit_ajax') . '\')" 
                        class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/delete_ajax') . '\')" 
                        class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    // Menampilkan halaman form tambah user
    public function create() {
        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list' => ['Home', 'User', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah User Baru'
        ];

        $level = LevelModel::all(); // ambil data level untuk ditampilkan di form
        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }

// Menyimpan data user baru
public function store(Request $request)
{
    $request->validate([
        // username harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_user kolom username
        'username' => 'required|string|min:3|unique:m_user,username',
        // nama harus diisi, berupa string, dan maksimal 100 karakter
        'nama' => 'required|string|max:100',
        // password harus diisi dan minimal 5 karakter
        'password' => 'required|min:5',
        // level_id harus diisi dan berupa angka
        'level_id' => 'required|integer'
    ]);

    UserModel::create([
        'username' => $request->username,
        'nama' => $request->nama,
        'password' => bcrypt($request->password), // password dienkripsi sebelum disimpan
        'level_id' => $request->level_id
    ]);

    return redirect('/user')->with('success', 'Data user berhasil disimpan');
}

// Menampilkan detail user
public function show(string $id)
{
    $user = UserModel::with('level')->find($id);

    $breadcrumb = (object) [
        'title' => 'Detail User',
        'list' => ['Home', 'User', 'Detail']
    ];

    $page = (object) [
        'title' => 'Detail User'
    ];

    $activeMenu = 'user'; // set menu yang sedang aktif

    return view('user.show', compact('breadcrumb', 'page', 'activeMenu', 'user'));
}



// Menampilkan halaman form edit user
public function edit(string $id)
{
    $user = UserModel::find($id);
    $level = LevelModel::all();

    $breadcrumb = (object) [
        'title' => 'Edit User',
        'list' => ['Home', 'User', 'Edit']
    ];

    $page = (object) [
        'title' => 'Edit User'
    ];

    $activeMenu = 'user'; // set menu yang sedang aktif

    return view('user.edit', [
        'breadcrumb' => $breadcrumb,
        'page' => $page,
        'user' => $user,
        'level' => $level,
        'activeMenu' => $activeMenu
    ]);
}

// Menyimpan perubahan data user
public function update(Request $request, string $id)
{
    $request->validate([
        // Username harus diisi, berupa string, minimal 3 karakter,
        // dan harus unik di tabel user kecuali untuk user dengan id yang sedang diedit
        'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
        'nama' => 'required|string|max:100', // Nama harus diisi, berupa string, dan maksimal 100 karakter
        'password' => 'nullable|min:5', // Password bisa diisi (minimal 5 karakter) dan bisa tidak diisi
        'level_id' => 'required|integer' // Level ID harus diisi dan berupa angka
    ]);

    UserModel::find($id)->update([
        'username' => $request->username,
        'nama' => $request->nama,
        'password' => $request->password ? bcrypt($request->password) : UserModel::find($id)->password,
        'level_id' => $request->level_id
    ]);

    return redirect('user')->with('success', 'Data user berhasil diubah');
}

// Menghapus data user
public function destroy(string $id)
{
    $check = UserModel::find($id);
    if (!$check) {  // untuk mengecek apakah data user dengan id yang dimaksud ada atau tidak
        return redirect('/user')->with('error', 'Data user tidak ditemukan');
    }

    try {
        UserModel::destroy($id);  // Hapus data level

        return redirect('/user')->with('success', 'Data user berhasil dihapus');
    } catch (\Illuminate\Database\QueryException $e) {

        // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
        return redirect('/user')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
    }
}

public function create_ajax(){
    $level = LevelModel::select('level_id', 'level_name')->get();
    return view('user.create_ajax')
        ->with('level', $level);
}

public function store_ajax(Request $request) {
    // cek apakah request berupa ajax
    if($request->ajax() || $request->wantsJson()){
        $rules = [
            'level_id' => 'required|integer',
            'username' => 'required|string|min:3|unique:m_user,username',
            'nama'     => 'required|string|max:100',
            'password' => 'required|min:6'
        ];

        // use Illuminate\Support\Facades\Validator;
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json([
                'status' => false, // response status, false: error/gagal, true: berhasil
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors() // pesan error validasi
            ]);
        }

        UserModel::create($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Data user berhasil disimpan'
        ]);
    }

    return redirect('/');
} 

    // Menampilkan halaman form edit user ajax
    public function edit_ajax(string $id) {
        $user = UserModel::find($id);
        $level = LevelModel::select('level_id', 'level_name')->get();
        return view('user.edit_ajax', ['user' => $user, 'level' => $level]);
    }
 
    public function update_ajax(Request $request, $id)
    {
        // Cek apakah request dari AJAX
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id' => 'required|integer',
                'username'  => 'required|max:20|unique:m_user,username,'.$id.',user_id',
                'nama'      => 'required|max:100',
                'password'  => 'nullable|min:6|max:20'
            ];

            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false, // Respon JSON, true: berhasil, false: gagal
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors() // Menunjukkan field mana yang error
                ]);
            }

            $check = UserModel::find($id);

            if ($check) {
                // Jika password tidak diisi, hapus dari request
                if (!$request->filled('password')) {
                    $request->request->remove('password');
                }

                $check->update($request->all());

                return response()->json([
                    'status'  => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
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
        //cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $user = UserModel::find($id);
            if ($user) {
                $user->delete();
                return response()->json([
                    'status'  => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function import() {
        return view('user.import');
    }

    public function import_ajax (Request $request) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                //validasi file harus xls atau xlsx, max 1MB
                'file_user' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => ' Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_user'); //ambil file dari request

            $reader = IOFactory::createReader('Xlsx'); //load reader file excel
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath()); //load file excel
            $sheet = $spreadsheet->getActiveSheet(); //ambil sheet yang aktif
            
            $data = $sheet->toArray(null, false, true, true); //ambil data excel

            $insert = [];
            if(count($data) > 1) { //jika data lebih dari 1 baris
                foreach($data as $baris => $value) {
                    if($baris > 1) { //baris ke 1 adalah header, maka lewati
                        $insert[] = [
                            'user_id' => $value['A'],
                            'username' => $value['B'],
                            'nama' => $value['C'],
                            'level_id' => $value['D'],
                            'password' =>Hash::make('12345'),
                            'created_at' => now(),
                        ];
                    }
                }

                if(count($insert) > 0) {
                    //insert data ke database, jika data sudah ada, maka diabaikan
                    UserModel::insertOrIgnore($insert);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport',
                ]);
            }
        }
        return redirect('/');
    }

    public function export_excel() {
        //ambil data user yang akan di export
        $user = UserModel::select('user_id', 'username', 'nama', 'level_id')
            ->orderBy('user_id')
            ->with('level')
            ->get();

        //load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); //ambil sheet yang aktif

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Username');
        $sheet->setCellValue('C1', 'Nama');
        $sheet->setCellValue('D1', 'Level Pengguna');

        $sheet->getStyle('A1:D1')->getFont()->setBold(true); //bold header

        $no = 1; //nomor data dimulai dari 1
        $baris = 2; //baris data dimulai dari baris ke 2

        foreach($user as $key => $value) {
            $sheet->setCellValue('A'.$baris, $no);
            $sheet->setCellValue('B'.$baris, $value->username);
            $sheet->setCellValue('C'.$baris, $value->nama);
            $sheet->setCellValue('D'.$baris, $value->level->level_name);
            $baris++; //nomor baris bertambah 1
            $no++; //nomor data bertambah 1
        }

        foreach(range('A', 'D') as $columID) {
            $sheet->getColumnDimension($columID)->setAutoSize(true); //set auto size untuk kolom
        }

        $sheet->setTitle('Data User'); //set title sheet

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data User '.date('Y-m-d H:i:s').'.xlsx'; 

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheethtml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        header('Chace-Control: max-age=1');
        header('Expires:Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: '. gmdate('D, d M Y H:i:s'). ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }

    public function export_pdf() {

        ini_set('max_execution_time', 300); // atur menjadi 300 detik (5 menit)
        ini_set('memory_limit', '512M'); // tambahkan ini jika data besar

        $user = UserModel::select('user_id', 'username', 'nama', 'level_id')
            ->orderBy('user_id')
            ->with('level')
            ->get();

        //use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('user.export_pdf', ['user' => $user]);
        $pdf->setPaper('A4', 'portrait'); //set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); //set true jika ada gambar dari url
        $pdf->render();

        return $pdf->stream('Data User '.date('Y-m-d H:i:s'). '.pdf');
    }

    public function profile()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect('/login')->with('error', 'Silahkan login terlebih dahulu');
        }

        $breadcrumb = (object) [
            'title' => 'Profile User',
            'list' => ['Home', 'Profile']
        ];

        $page = (object) [
            'title' => 'Profil Pengguna'
        ];


        $activeMenu = 'profile';

        return view('user.profile', compact('user', 'breadcrumb', 'page', 'activeMenu'));
    }

    //Update foto profile user 
    public function updatePhoto(Request $request)
    {
        // Validasi file
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            // auth user yang login
            $user = auth()->user();

            if (!$user) {
                return redirect('/login')->with('error', 'Silahkan login terlebih dahulu');
            }

            // user id
            $userId = $user->user_id;

            $userModel = UserModel::find($userId);

            if (!$userModel) {
                return redirect('/login')->with('error', 'User tidak ditemukan');
            }

            // hapus jika sudah ada foto profile
            if ($userModel->profile_picture && file_exists(storage_path('app/public/' . $userModel->profile_picture))) {
                Storage::disk('public')->delete($userModel->profile_picture);
            }

            // update foto profile baru 
            $fileName = 'profile_' . $userId . '_' . time() . '.' . $request->profile_picture->extension();
            $path = $request->profile_picture->storeAs('profiles', $fileName, 'public');

            // Update
            UserModel::where('user_id', $userId)->update([
                'profile_picture' => $path
            ]);

            return redirect()->back()->with('success', 'Foto profile berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupload foto: ' . $e->getMessage());
        }
    }
}

//     public function level()
// {
//     return $this->belongsTo(LevelModel::class, 'level_id', 'id');
// }

// }