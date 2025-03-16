@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a href="{{ url('supplier/create') }}" class="btn btn-primary btn-sm">Tambah</a>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered table-striped table-hover table-sm" id="table_supplier">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Supplier</th>
                    <th>Kontak Supplier</th>
                    <th>Alamat Supplier</th>
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
    $('#table_supplier').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('supplier/list') }}",
        columns: [
            {data: 'supplier_id', name: 'supplier_id'},
            {data: 'supplier_nama', name: 'supplier_nama'},
            {data: 'supplier_kontak', name: 'supplier_kontak'},
            {data: 'supplier_alamat', name: 'supplier_alamat'},
            {data: 'aksi', name: 'aksi', orderable: false, searchable: false}
        ]
    });
});
</script>
@endpush