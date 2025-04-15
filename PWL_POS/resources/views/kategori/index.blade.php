@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a href="{{ url('kategori/create') }}" class="btn btn-primary btn-sm">Tambah</a>
            <button type="button" onclick="modalAction('{{ url('/kategori/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Ajax</button>
            <button onclick="modalAction('{{ url('/kategori/import') }}')" class="btn btn-info btn-sm">Import Kategori</button>
            <a href="{{ url('/kategori/export_excel') }}" class="btn btn-warning btn-sm"><i class="fa fa-file-excel"></i> Export Kategori</a>
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
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
<script>
     function modalAction(url = ''){
    $('#myModal').load(url,function(){
        $('#myModal').modal('show');
    });
}
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
$('#level_id').on('change', function() {
        table.column(3).search($(this).find('option:selected').text()).draw();
    });
$('#myModal').on('show.bs.modal', function () {
    console.log('Modal is about to be shown');
});
</script>
@endpush