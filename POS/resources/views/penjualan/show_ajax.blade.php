<div>
    <p><strong>ID:</strong> {{ $penjualan->penjualan_id }}</p>
    <p><strong>User:</strong> {{ $penjualan->user->name }}</p>
    <p><strong>Nama Pembeli:</strong> {{ $penjualan->pembeli }}</p>
    <p><strong>Kode Penjualan:</strong> {{ $penjualan->penjualan_kode }}</p>
    <p><strong>Tanggal Penjualan:</strong> {{ $penjualan->penjualan_tanggal }}</p>
    <p><strong>Dibuat Pada:</strong> {{ $penjualan->created_at }}</p>
    <p><strong>Terakhir Diubah:</strong> {{ $penjualan->updated_at }}</p>
</div>