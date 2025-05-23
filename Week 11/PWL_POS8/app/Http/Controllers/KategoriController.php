<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\KategoriModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class KategoriController extends Controller
{
   public function index() 
   {
      $kategori = KategoriModel::all(); // ambil data kategori untuk ditampilkan di form
 
      $breadcrumb = (object) [
          'title' => 'Daftar Kategori',
          'list' => ['Home', 'Kategori']
      ];

      $page = (object) [
          'title' => 'Daftar kategori yang terdaftar dalam sistem'
      ];

      $acttiveMenu = 'kategori'; // set menu yang aktif

      return view('kategori.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $acttiveMenu]);
  }

  // ambil data kategori dalam bentuk json untuk datatables
  public function list(Request $request)
  {
      $kategori = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama');

      if ($request->kategori_kode) {
          $kategori->where('kategori_kode', $request->kategori_kode);
      }

      return DataTables::of($kategori)
          ->addIndexColumn()
          ->addColumn('aksi', function ($kategori) {
            //   $btn = '<a href="'.url('/kategori/' .$kategori->kategori_id).'" class="btn btn-info btn-sm">Detail</a> ';
            //   $btn .= '<a href="'.url('/kategori/' .$kategori->kategori_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
            //   $btn .= '<form class="d-inline-block" method="POST" action="'.url('/kategori/'.$kategori->kategori_id).'">'
            //        . csrf_field() . method_field('DELETE')
            //        . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah anda yakin menghapus data ini?\');">Hapus</button></form>';
            $btn = '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button>';
            $btn .= '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button>';
            $btn .= '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button>';
            return $btn; 
          })
          ->rawColumns(['aksi'])
          ->make(true);
  }

  public function create()
  {
      $breadcrumb = (object) [
          'title' => 'Tambah Kategori',
          'list' => ['Home', 'Kategori', 'Tambah Kategori']
      ];

      $page = (object) [
          'title' => 'Tambah kategori baru'
      ];

      $kategori = KategoriModel::all(); // ambil data kategori untuk ditampilkan di form
      $activeMenu = 'kategori'; // set menu yang aktif
      return view('kategori.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
  }

  public function store(Request $request) {
      $request->validate([
          'kategori_kode' => 'required|string',
          'kategori_nama' => 'required|string'
      ]);

      KategoriModel::create([
          'kategori_kode' => $request->kategori_kode,
          'kategori_nama' => $request->kategori_nama
      ]);

      return redirect('/kategori')->with('status', 'Data kategori berhasil ditambahkan');
  }

  public function show(string $id) {
      $kategori = KategoriModel::find($id); // ambil data kategori untuk ditampilkan di form
      $breadcrumb = (object) [
          'title' => 'Detail Kategori',
          'list' => ['Home', 'Kategori', 'Detail Kategori']
      ];

      $page = (object) [
          'title' => 'Detail kategori'
      ];

      $activeMenu = 'kategori'; // set menu yang aktif
      return view('kategori.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
  }

  public function edit(string $id) {
      $kategori = KategoriModel::find($id); // ambil data kategori untuk ditampilkan di form
      $breadcrumb = (object) [
          'title' => 'Edit Kategori',
          'list' => ['Home', 'Kategori', 'Edit Kategori']
      ];

      $page = (object) [
          'title' => 'Edit kategori'
      ];

      $activeMenu = 'kategori'; // set menu yang aktif
      return view('kategori.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
  }

  public function update(Request $request, string $id) {
      $request->validate([
          'kategori_kode' => 'required|string',
          'kategori_nama' => 'required|string'
      ]);

      KategoriModel::where('kategori_id', $id)->update([
          'kategori_kode' => $request->kategori_kode,
          'kategori_nama' => $request->kategori_nama
      ]);

      return redirect('/kategori')->with('success', 'Data kategori berhasil diubah');
  }

  public function destroy(string $id) {
      $check = KategoriModel::find($id);
      if (!$check) {
          return redirect('/kategori')->with('error', 'Data kategori tidak ditemukan');
      }

      try {
          KategoriModel::destroy($id);
          return redirect('/kategori')->with('success', 'Data kategori berhasil dihapus');
      } catch (\Exception $e) {
          return redirect('/kategori')->with('error', 'Data kategori tidak bisa dihapus karena terdapat data yang terkait');
      }
   }

    public function create_ajax() {
        $kategori = KategoriModel::all();
        return view('kategori.create_ajax', ['kategori' => $kategori]);
    }

    public function store_ajax(Request $request) {
        // cek apakah request berupa ajax
        if($request->ajax() || $request->wantsJson()){
            $rules = [
                'kategori_kode' => 'required|string|max:6|regex:/^[A-Z0-9]+$/',
                'kategori_nama' => 'required|string|min:3|max:50|regex:/^[a-zA-Z\s]+$/'
            ];

            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()) {
                return response()->json([
                    'status' => false, //response sttaus, false: error/gagal, true=berhasil
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(), // pesan error validasi
                ]);
            }

            KategoriModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data Level berhasil disimpan'
            ]);
        }
        redirect('/kategori');
    }

    // Menampilkan halaman form edit kategori ajax
    public function edit_ajax(string $id) {
        $kategori = kategoriModel::find($id);
        return view('kategori.edit_ajax', ['kategori' => $kategori]);
    }

    public function update_ajax(Request $request, string $id)
    {
        // Cek apakah request dari AJAX
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_kode' => 'required|string|max:6|regex:/^[A-Z0-9]+$/',
                'kategori_nama' => 'required|string|min:3|max:50|regex:/^[a-zA-Z\s]+$/'
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

            $check = KategoriModel::find($id);
            if ($check) {
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

    public function confirm_ajax($id) {
        $kategori = KategoriModel::find($id);
        return view('kategori.confirm_ajax', ['kategori' => $kategori]);
    }

    public function delete_ajax(Request $request, $id) {
        //cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $kategori = KategoriModel::find($id);
            if ($kategori) {
                $kategori->delete();
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
        return view('kategori.import');
    }

    public function import_ajax (Request $request) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                //validasi file harus xls atau xlsx, max 1MB
                'file_kategori' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => ' Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_kategori'); //ambil file dari request

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
                            'kategori_id' => $value['A'],
                            'kategori_kode' => $value['B'],
                            'kategori_nama' => $value['C'],
                            'created_at' => now(),
                        ];
                    }
                }

                if(count($insert) > 0) {
                    //insert data ke database, jika data sudah ada, maka diabaikan
                    KategoriModel::insertOrIgnore($insert);
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
        //ambil data kategori yang akan di export
        $kategori = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama')
            ->orderBy('kategori_id')
            ->get();

        //load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); //ambil sheet yang aktif

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Kategori');
        $sheet->setCellValue('C1', 'Nama Kategori');

        $sheet->getStyle('A1:C1')->getFont()->setBold(true); //bold header

        $no = 1; //nomor data dimulai dari 1
        $baris = 2; //baris data dimulai dari baris ke 2

        foreach($kategori as $key => $value) {
            $sheet->setCellValue('A'.$baris, $no);
            $sheet->setCellValue('B'.$baris, $value->kategori_kode);
            $sheet->setCellValue('C'.$baris, $value->kategori_nama);
            $baris++; //nomor baris bertambah 1
            $no++; //nomor data bertambah 1
        }

        foreach(range('A', 'C') as $columID) {
            $sheet->getColumnDimension($columID)->setAutoSize(true); //set auto size untuk kolom
        }

        $sheet->setTitle('Data Kategori'); //set title sheet

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Kategori '.date('Y-m-d H:i:s').'.xlsx'; 

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

        $kategori = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama')
            ->orderBy('kategori_id')
            ->get();

        //use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('kategori.export_pdf', ['kategori' => $kategori]);
        $pdf->setPaper('A4', 'portrait'); //set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); //set true jika ada gambar dari url
        $pdf->render();

        return $pdf->stream('Data Kategori '.date('Y-m-d H:i:s'). '.pdf');
    }
}