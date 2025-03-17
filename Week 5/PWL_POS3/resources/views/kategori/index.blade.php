@extends('layouts.app')

{{-- Customize layout sections --}}
@section('subtitle', 'Kategori')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Kategori')

@section('content')
<div class="mt-2 ms-3">
    <a href="{{ url('/kategori/create') }}" class="btn btn-primary btn-sm">Add Kategori</a>
</div>
    <div class="container">
        <div class="card">
            <div class="card-header">Manage Kategori</div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
    <script>
        $(document).on('click', '.delete-btn', function () {
            let url = $(this).data('url');
            let row = $(this).closest('tr');

            if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert(response.message);
                        $('#kategori-table').DataTable().ajax.reload();
                    },
                    error: function(xhr) {
                        alert("Kesalahan saat menghapus data.");
                    }
                });
            }
        });
    </script>
@endpush

