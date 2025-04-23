<?php

namespace App\Http\Controllers;

use App\Models\PenjualanModel;
use App\Models\PenjualanDetailModel;
use App\Models\BarangModel;
use App\Models\StokModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;



class PenjualanController extends Controller
{
    // Menampilkan halaman utama
    public function index()
    {
        $activeMenu = 'penjualan';
        $breadcrumb = (object) [
            'title' => 'Data Transaksi Penjualan',
            'list' => ['Home', 'Penjualan']
        ];

        return view('penjualan.index', [
            'activeMenu' => $activeMenu,
            'breadcrumb' => $breadcrumb
        ]);
    }

    // Mengambil data untuk DataTables (POST /penjualan/list)
    public function list(Request $request)
    {
        try {
            $penjualan = PenjualanModel::with(['user', 'details'])
                ->select('t_penjualan.*');

            return DataTables::eloquent($penjualan)
                ->addIndexColumn()
                ->addColumn('penjualan_kode', fn($s) => $s->penjualan_kode)
                ->addColumn('pembeli', fn($s) => $s->pembeli)
                ->addColumn('penjualan_tanggal', function($s) {
                    return $s->penjualan_tanggal->format('d-m-Y H:i'); // Format lokal
                })
                ->addColumn('total', function ($s) {
                    return 'Rp ' . number_format(
                        $s->details->sum(fn($detail) => $detail->harga * $detail->jumlah),
                        0,
                        ',',
                        '.'
                    );
                })
                ->addColumn('nama', fn($s) => $s->user->nama ?? 'System')
                ->addColumn('aksi', function ($s) {
                    return '<div class="text-center">' .
                        '<button onclick="showDetail(\'' . route('penjualan.show', $s->penjualan_id) . '\')" class="btn btn-sm btn-info mr-1">Detail</button>' .
                        //'<button onclick="confirmDelete(\'' . route('penjualan.delete_ajax', $s->penjualan_id) . '\')" class="btn btn-sm btn-danger">Hapus</button>' .
                        '</div>';
                })
                ->rawColumns(['aksi'])
                ->toJson();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Menampilkan detail transaksi (GET /penjualan/{id})
    public function show($id)
    {
        $sale = PenjualanModel::with(['details.barang'])->find($id);
        return response()->json($sale);
    }

    public function create_ajax()
    {
        // Ambil barang yang memiliki stok > 0
        $barang = BarangModel::with(['stok'])
            ->whereHas('stok', function ($query) {
                $query->where('stok_jumlah', '>', 0);
            })
            ->select('barang_id', 'barang_nama', 'harga_jual')
            ->get();

        return view('penjualan.create_ajax', ['barang' => $barang]);
    }

    public function store_ajax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pembeli' => 'required|string|max:100',
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:m_barang,barang_id',
            'items.*.jumlah' => 'required|integer|min:1'
        ], [
            'pembeli.required' => 'Nama pembeli wajib diisi',
            'items.required' => 'Minimal 1 barang harus dipilih',
            'items.*.jumlah.min' => 'Jumlah minimal 1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Generate kode transaksi
            $lastId = PenjualanModel::max('penjualan_id') ?? 0;
            $penjualan_kode = 'TRX' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

            // Simpan header transaksi
            $sale = PenjualanModel::create([
                'user_id' => auth()->user()->user_id,
                'pembeli' => $request->pembeli,
                'penjualan_kode' => $penjualan_kode,
                'penjualan_tanggal' => now()->setTimezone('Asia/Jakarta'), // Waktu lokal
            ]);

            // Simpan item dan update stok
            foreach ($request->items as $item) {
                $barang = BarangModel::with('stok')->findOrFail($item['barang_id']);

                // Validasi stok
                if (!$barang->stok || $barang->stok->stok_jumlah < $item['jumlah']) {
                    throw new \Exception("Stok {$barang->barang_nama} tidak mencukupi!");

                }

                // Simpan detail
                PenjualanDetailModel::create([
                    'penjualan_id' => $sale->penjualan_id,
                    'barang_id' => $item['barang_id'],
                    'harga' => $barang->harga_jual,
                    'jumlah' => $item['jumlah']
                ]);

                // Update stok
                $barang->stok->update([
                    'stok_jumlah' => $barang->stok->stok_jumlah - $item['jumlah'],
                    'user_id' => auth()->user()->user_id
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Transaksi berhasil disimpan',
                'sale_id' => $sale->penjualan_id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

//     public function edit_ajax($id)
// {
//     $penjualan = PenjualanModel::with('details.barang')->find($id);
//     $barang = BarangModel::select('barang_id', 'barang_nama')->get();

//     return view('penjualan.edit_ajax', compact('penjualan', 'barang'));
// }

// public function confirm_ajax(string $id)
// {
//     $penjualan = PenjualanModel::find($id);
//     return view('penjualan.confirm_ajax', compact('penjualan'));
// }

// public function delete_ajax(Request $request, $id)
// {
//     if ($request->ajax()) {
//         $penjualan = PenjualanModel::with('detail')->find($id);
//         if ($penjualan) {
//             foreach ($penjualan->detail ?? [] as $detail) {
//                 $barang = BarangModel::find($detail->barang_id);
//                 $barang->update([
//                     'stok' => $barang->stok + $detail->jumlah
//                 ]);
//             }
//             PenjualanDetailModel::where('penjualan_id', $id)->delete();
//             $penjualan->delete();

//             return response()->json(['status' => true, 'message' => 'Data berhasil dihapus']);
//         } else {
//             return response()->json(['status' => false, 'message' => 'Data tidak ditemukan']);
//         }
//     }

//     return redirect('/');
// }
}