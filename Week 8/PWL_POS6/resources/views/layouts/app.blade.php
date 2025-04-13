<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplikasi')</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</head>
<body>
    <nav>
        <ul>
            <li><a href="{{ url('/level') }}">Level</a></li>
            <li><a href="{{ url('/kategori') }}">Kategori</a></li>
            <li><a href="{{ url('/supplier') }}">Supplier</a></li>
            <li><a href="{{ url('/barang') }}">Barang</a></li>
        </ul>
    </nav>
    <div>
        @yield('content')
    </div>
</body>
</html>
