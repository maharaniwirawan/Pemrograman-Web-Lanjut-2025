@extends('adminlte::page')

@section('title', 'Edit Kategori')

@section('content_header')
    <h1>Edit Kategori</h1>
@stop

@section('content')
    <form action="{{ route('kategori.update', $kategori->kategori_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Kode Kategori</label>
            <input type="text" name="kategori_kode" value="{{ $kategori->kategori_kode }}" class="form-control">
        </div>

        <div class="form-group">
            <label>Nama Kategori</label>
            <input type="text" name="kategori_nama" value="{{ $kategori->kategori_nama }}" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Update</button>
    </form>
@stop