<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; 
use App\Models\PenjualanModel;

class PenjualanController extends Controller
{
    public function index() {
        $barangs = PenjualanModel::all();
        return response()->json($barangs);
    }

    public function store(Request $request) {
        // Set validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'pembeli' => 'required',
            'penjualan_kode' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Tambahkan validasi image
        ]);
        
        // If validations fail
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create barang
        $penjualan = PenjualanModel::create([
            'user_id' => $request->user_id,
            'pembeli' => $request->pembeli,
            'penjualan_kode' => $request->penjualan_kode,
            'penjualan_tanggal' => now(),
            'image' => $request->image->hashName(), // Simpan nama file image yang sudah di-hash
        ]);

        // Return response JSON penjualan is created
        if ($penjualan) {
            return response()->json([
                'success' => true,
                'penjualan' => $penjualan,
            ], 201);
        }

        // Return JSON if process failed
        return response()->json([
            'success' => false,
        ], 409);
    }

    public function show(PenjualanModel $penjualan) {
        return $penjualan;
    }

    public function update(Request $request, PenjualanModel $penjualan) {
        $penjualan->update($request->all());
        return $penjualan;
    }

    public function destroy(PenjualanModel $penjualan) {
        $penjualan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data penjualan terhapus',
        ]);
    }
}