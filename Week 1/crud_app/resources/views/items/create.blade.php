<!DOCTYPE html>
<html>
    <head>
        <title>Add Item</title> <!-- Menetapkan judul halaman sebagai "Add Item" -->
    </head>
    <body>
        <h1>Add Item</h1> <!-- Menampilkan judul halaman "Add Item" -->
        <form action="{{ route('items.store')}}" method="POST"> <!-- Membuat form yang mengarah ke route items.store dengan metode POST untuk menyimpan item baru -->
            @csrf <!-- untuk keamanan -->
            <label for="name">Name:</label> <!-- Menampilkan label untuk input nama -->
            <input type="text" name="name" required> <!-- Membuat input teks untuk nama item, wajib diisi -->
            <br>
            <label for="description">Description:</label> <!-- Menampilkan label untuk deskripsi item -->
            <textarea name="description"required></textarea> <!-- Membuat input teksarea untuk deskripsi item, wajib diisi -->
            <br>
            <button type="submit">Add Item</button> <!-- Membuat tombol untuk mengirimkan form dan menambahkan item -->
        </form>
        <a href="{{ route('items.index') }}">Back to List</a> <!-- Membuat link kembali ke daftar item -->
    </body>
</html> 