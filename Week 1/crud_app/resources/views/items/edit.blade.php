<!DOCTYPE html>
<html>
    <head>
        <title>Edit Item</title> <!-- Menetapkan judul halaman sebagai "Edit Item" -->
    </head>
    <body>
        <h1>Edit Item</h1> <!-- Menampilkan judul halaman "Edit Item" -->
        <form action="{{ route('items.update', $item) }}" method="POST"> <!-- Membuat form untuk memperbarui item, dengan mengarah ke route items.update dan mengirimkan item yang sedang diedit -->
            @csrf
            @method('PUT') <!-- Mengubah metode request menjadi PUT agar sesuai dengan rute update -->
            <label for="name">Name:</label> <!-- Menampilkan label untuk input nama -->
            <input type="text" name="name" value="{{ $item->name }}" required> <!-- Membuat input teks dengan nilai default dari nama item yang sedang diedit, dan mewajibkan pengisian -->
            <br>
            <label for="description">Description:</label> <!-- Menampilkan label untuk deskripsi item -->
            <textarea name="description"required>{{ $item->description }}</textarea> <!-- Membuat textarea dengan nilai default dari deskripsi item yang sedang diedit, dan mewajibkan pengisian -->
            <br>
            <button type="submit">Update Item</button> <!-- Membuat tombol untuk mengirimkan form dan memperbarui item -->
        </form>
        <a href="{{ route('items.index') }}">Back to List</a> <!-- Menyediakan tautan untuk kembali ke daftar item -->
    </body>
</html> 