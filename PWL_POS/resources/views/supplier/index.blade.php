@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a href="{{ url('supplier/create') }}" class="btn btn-primary btn-sm">Tambah</a>
            <button type="button" onclick="modalAction('{{ url('/supplier/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Ajax</button>
            <button onclick="modalAction('{{ url('/supplier/import') }}')" class="btn btn-info btn-sm">Import Supplier</button>
            <a href="{{ url('/supplier/export_excel') }}" class="btn btn-warning btn-sm"><i class="fa fa-file-excel"></i> Export Supplier</a>
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
$('#level_id').on('change', function() {
        table.column(3).search($(this).find('option:selected').text()).draw();
    });
$('#myModal').on('show.bs.modal', function () {
    console.log('Modal is about to be shown');
});
</script>
@endpush