<?php

namespace App\Http\Controllers;// Menetapkan namespace tempat controller berada


use App\Models\Item; // Menggunakan model Item untuk berinteraksi dengan database
use Illuminate\Http\Request; // Menggunakan Request untuk mengolah data form

class ItemController extends Controller // Mendefinisikan ItemController sebagai turunan dari Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() // Mengambil semua data item dan menampilkannya pada tampilan items.index
    {
        $items = Item::all(); // Mengambil seluruh data dari tabel items
        return view('items.index', compact('items')); // Mengirimkan data ke tampilan items.index
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() // Menampilkan form untuk menambahkan item baru
    {
        return view('items.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) // Memvalidasi dan menyimpan data baru ke database, lalu mengarahkan ke halaman items.index dengan notifikasi sukses
    {
        $request->validate([ // Memastikan name dan description wajib diisi
            'name' => 'required',
            'description' => 'required',
        ]);

        //Item::create($request->all());
        //return redirect()->route('items.index');

        //Masukkan atribut yang diizinkan
         Item::create($request->only(['name', 'description']));
        return redirect()->route('items.index')->with('success', 'Item added successfully.'); // Mengarahkan ke items.index dengan pesan sukses
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item) // Menampilkan detail dari item tertentu
    {
        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item) // Menampilkan form untuk mengedit item tertentu
    {
        return view('items.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item) // Memvalidasi dan memperbarui data item di database, lalu mengarahkan kembali dengan pesan sukses
    {
        $request->validate([ // Memastikan input valid
            'name' => 'required',
            'description' => 'required',
        ]);

        //$Item->update($request->all());
        //return redirect()->route('items.index');
        // Hanya masukkan atribut yang diizinkan
         $item->update($request->only(['name', 'description']));
        return redirect()->route('items.index')->with('success', 'Item updated succesfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item) // Menghapus item dari database dan mengarahkan kembali dengan notifikasi sukses
    {
        //return redirect()->route('items.index');
         $item->delete(); // Menghapus item
        return redirect()->route('items.index')->with('success', 'Item deleted succesfully.'); // Mengarahkan ke halaman items.index dengan pesan sukses
    }
}
