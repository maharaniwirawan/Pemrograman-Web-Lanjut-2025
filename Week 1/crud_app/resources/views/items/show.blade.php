<!DOCTYPE html>
<html>
    <head>
        <title>Item List</title> <!-- Menetapkan judul halaman sebagai "Item List" -->
    </head>
    <body>
        <h1>Items</h1> <!-- Menampilkan judul utama halaman "Items" -->
        @if (session('succes')) <!-- Memeriksa apakah ada pesan sukses dalam session -->
            <p>{{ session('succes') }}</p> <!-- Menampilkan pesan sukses jika ada -->
        @endif
        <a href="{{ route('items.create') }}">Add Item</a> <!-- Menyediakan link untuk menuju halaman pembuatan item baru -->
        <ul>
            @foreach ($items as $item) <!-- Melakukan loop untuk menampilkan setiap item dalam variabel $items -->
            <li>
                {{ $item->name }} - <!-- Menampilkan nama item dari database -->
                <a href="{{ route('items.edit', $item) }}">Edit</a> <!-- Menyediakan link untuk mengedit item tertentu -->
                <form action="{{ route('items.destroy', $item) }}" method="POST" style="display: inline;"> <!-- Membuat formulir untuk menghapus item -->
                    @csrf
                    @method('DELETE') <!-- Menggunakan metode DELETE -->
                    <button type="submit">Delete</button> <!-- Tombol untuk menghapus item -->
                </form>
            </li>
            @endforeach
        </ul> 
    </body>
</html>