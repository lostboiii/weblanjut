@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a href="{{ url('barang/create') }}" class="btn btn-primary btn-sm">Tambah</a>
            <button type="button" onclick="modalAction('{{ url('/barang/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Ajax</button>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Filter:</label>
                    <div class="col-3">
                        <select class="form-control" id="kategori_id" name="kategori_id">
                            <option value="">- Semua -</option>
                            @foreach($kategori as $item)
                                <option value="{{ $item->kategori_id }}">{{ $item->kategori_nama }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Kategori Barang</small>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-bordered table-striped table-hover table-sm" id="table_barang">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga Jual</th>
                    <th>Harga Beli</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection
@push('css')
@endpush
@push('js')
<script>
    function modalAction(url = ''){
    $('#myModal').load(url,function(){
        $('#myModal').modal('show');
    });
}
$(function () {
    var table = $('#table_barang').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('barang/list') }}",
            data: function (d) {
                d.kategori_id = $('#kategori_id').val();
            }
        },
        columns: [
            {data: 'barang_id', name: 'barang_id'},
            {data: 'barang_kode', name: 'barang_kode'},
            {data: 'barang_nama', name: 'barang_nama'},
            {data: 'harga_jual', name: 'harga_jual'},
            {data: 'harga_beli', name: 'harga_beli'},
            {data: 'aksi', name: 'aksi', orderable: false, searchable: false}
        ]
    });

 
    $('#kategori_id').on('change', function() {
        table.draw(); 
    });
});

$('#myModal').on('show.bs.modal', function () {
    console.log('Modal is about to be shown');
});
</script>
@endpush