@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a href="{{ url('kategori/create') }}" class="btn btn-primary btn-sm">Tambah</a>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <table class="table table-bordered table-striped table-hover table-sm" id="table_kategori">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Kategori</th>
                    <th>Nama Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('js')
<script>
$(function () {
    $('#table_kategori').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('kategori/list') }}",
        columns: [
            {data: 'kategori_id', name: 'kategori_id'},
            {data: 'kategori_kode', name: 'kategori_kode'},
            {data: 'kategori_nama', name: 'kategori_nama'},
            {data: 'aksi', name: 'aksi', orderable: false, searchable: false}
        ]
    });
});
</script>
@endpush