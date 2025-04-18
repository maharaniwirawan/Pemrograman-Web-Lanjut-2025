<?php
 
 namespace App\Http\Controllers;
 
 use App\Models\SupplierModel;
 use Illuminate\Http\Request;
 use Yajra\DataTables\Facades\DataTables;
 use Illuminate\Support\Facades\Validator;
 
 class SupplierController extends Controller
 {
    public function index(){
        $suppliers = SupplierModel::all(); // ambil data supplier untuk ditampilkan di form
    
        $breadcrumb = (object) [
            'title' => 'Daftar Supplier',
            'list' => ['Home', 'Supplier']
        ];
    
        $page = (object)['title' => 'Data Supplier']; // Contoh
        return view('supplier.index', compact('page', 'breadcrumb'))->with(['activeMenu' => 'supplier']);
    }
 
     public function list(Request $request){
         $supplier = SupplierModel::select('supplier_id', 'supplier_kode', 'supplier_nama', 'supplier_alamat');
 
         if ($request->supplier_kode) {
             $supplier->where('supplier_kode', $request->supplier_kode);
         }
 
         return DataTables::of($supplier)
             ->addIndexColumn()
             ->addColumn('aksi', function ($supplier) {
                //  $btn = '<a href="'.url('/supplier/' .$supplier->supplier_id).'" class="btn btn-info btn-sm">Detail</a> ';
                //  $btn .= '<a href="'.url('/supplier/' .$supplier->supplier_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                //  $btn .= '<form class="d-inline-block" method="POST" action="'.url('/supplier/'.$supplier->supplier_id).'">'
                //       . csrf_field() . method_field('DELETE')
                //       . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah anda yakin menghapus data ini?\');">Hapus</button></form>';\
            $btn = '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button>';
            $btn .= '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button>';
            $btn .= '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button>';
                 return $btn; 
             })
             ->rawColumns(['aksi'])
             ->make(true);
     }
 
     public function create(){
         $breadcrumb = (object) [
             'title' => 'Tambah Supplier',
             'list' => ['Home', 'Supplier', 'Tambah Supplier']
         ];
 
         $page = (object) [
             'title' => 'Tambah supplier baru'
         ];
 
         $supplier = SupplierModel::all(); // ambil data supplier untuk ditampilkan di form
         $activeMenu = 'supplier'; // set menu yang aktif
         return view('supplier.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'supplier' => $supplier, 'activeMenu' => $activeMenu]);
     }
 
     public function store(Request $request){
         $request->validate([
             'supplier_kode' => 'required|string|min:3',
             'supplier_nama' => 'required|string',
         ]);
 
         SupplierModel::create([
             'supplier_kode' => $request->supplier_kode,
             'supplier_nama' => $request->supplier_nama,
         ]); 
 
         return redirect('/supplier')->with('status', 'Data supplier berhasil ditambahkan');
     }
 
     public function show(string $id){
         $supplier = SupplierModel::find($id); // ambil data supplier berdasarkan id
         $breadcrumb = (object) [
             'title' => 'Detail Supplier',
             'list' => ['Home', 'Supplier', 'Detail Supplier']
         ];
 
         $page = (object) [
             'title' => 'Detail supplier'
         ];
 
         $activeMenu = 'supplier'; // set menu yang aktif
         return view('supplier.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'supplier' => $supplier, 'activeMenu' => $activeMenu]);
     }
 
     public function edit(string $id){
         $supplier = SupplierModel::find($id); // ambil data supplier untuk ditampilkan di form
         $breadcrumb = (object) [
             'title' => 'Edit Supplier',
             'list' => ['Home', 'Supplier', 'Edit Supplier']
         ];
 
         $page = (object) [
             'title' => 'Edit supplier'
         ];
 
         $activeMenu = 'supplier'; // set menu yang aktif
         return view('supplier.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'supplier' => $supplier, 'activeMenu' => $activeMenu]);
     }
 
     public function update(Request $request, string $id){
         $request->validate([
             'supplier_kode' => 'required|string|min:3',
             'supplier_nama' => 'required|string'
         ]);
 
         SupplierModel::find($id)->update([
             'supplier_kode' => $request->supplier_kode,
             'supplier_nama' => $request->supplier_nama,
         ]);
 
         return redirect('/supplier')->with('success', 'Data supplier berhasil diubah');
     }
 
     public function destroy(string $id){
         $check = SupplierModel::find($id);
         if (!$check) {
             return redirect('/supplier')->with('error', 'Data supplier tidak ditemukan');
         }
 
         try {
             SupplierModel::destroy($id);
             return redirect('/supplier')->with('success', 'Data supplier berhasil dihapus');
         } catch (\Exception $e) {
             return redirect('/supplier')->with('error', 'Data supplier tidak bisa dihapus karena masih digunakan pada tabel lain');
         }
     }

     public function create_ajax() {
        $supplier = SupplierModel::all();
        return view('supplier.create_ajax', ['supplier' => $supplier]);
    }

    public function store_ajax(Request $request) {
        // cek apakah request berupa ajax
        if($request->ajax() || $request->wantsJson()){
            $rules = [
                'supplier_kode' => 'required|string|max:6|regex:/^SUP\d{3}$/',
                'supplier_nama' => 'required|string|min:3|max:50|regex:/^[a-zA-Z\s]+$/',
                'supplier_alamat' => 'required|string|min:3',
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

            SupplierModel::create([
                'supplier_kode' => $request->supplier_kode,
                'supplier_nama' => $request->supplier_nama,
                'supplier_alamat' => $request->supplier_alamat,
            ]);
            
            return response()->json([
                'status' => true,
                'message' => 'Data Supplier berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    // Menampilkan halaman form edit supplier ajax
    public function edit_ajax(string $id) {
        $supplier = SupplierModel::find($id);
        return view('supplier.edit_ajax', ['supplier' => $supplier]);
    }

    public function update_ajax(Request $request, string $id)
    {
        // Cek apakah request dari AJAX
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_kode' => 'required|string|max:6|regex:/^SUP\d{3}$/',
                'supplier_nama' => 'required|string|min:3|max:50|regex:/^[a-zA-Z\s]+$/',
                'supplier_alamat' => 'required|string|min:3'
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

            $check = SupplierModel::find($id);
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
        $supplier = SupplierModel::find($id);
        return view('supplier.confirm_ajax', ['supplier' => $supplier]);
    }

    public function delete_ajax(Request $request, $id) {
        //cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $supplier = SupplierModel::find($id);
            if ($supplier) {
                $supplier->delete();
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
 }