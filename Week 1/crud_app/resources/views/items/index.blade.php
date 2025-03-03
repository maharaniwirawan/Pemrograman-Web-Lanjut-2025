<!DOCTYPE html> //Menentukan bahwa dokumen menggunakan format HTML.
<html>
    <head> //menampung informasi meta dan judul halaman

        <title>Item List</title> //Menetapkan judul halaman sebagai "Item List".
    </head> 
    <body>
        <h1>Items</h1> //Menampilkan judul utama "Items" 
        @if (session('succes')) //Menggunakan kondisi untuk menampilkan pesan sukses.
            <p>{{session('succes') }}</p> //
        @endif
        <a href="{{ route('items.create') }}">Add Item</a> //Membuat link untuk menambahkan item.
        <ul>
            @foreach ( $items as $item) //melakukan iterasi untuk setiap item dalam $items.
            <li>
                {{ $item->name }} - //Menampilkan nama item 
                <a href="{{ route('items.edit', $item) }}">Edit</a> //menambahkan tautan untuk mengedit item
                <form action="{{ route('items.destroy', $item) }}" method="POST" style="display: inline;"> //Membuat formulir dengan metode POST untuk menghapus item
                    @csrf //untuk keamanan
                    @method('DELETE') //metode delete
                    <button type="submit">Delete</button> //Menyediakan tombol hapus dalam formulir untuk menghapus item
                </form>
            </li>
            @endforeach
        </ul>
    </body>
</html> 

