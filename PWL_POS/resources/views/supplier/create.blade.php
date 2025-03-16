@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('supplier') }}">
            @csrf
            <div class="form-group">
                <label for="supplier_nama">Nama Supplier</label>
                <input type="text" class="form-control" id="supplier_nama" name="supplier_nama" required>
            </div>
            <div class="form-group">
                <label for="supplier_kontak">Kontak Supplier</label>
                <input type="text" class="form-control" id="supplier_kontak" name="supplier_kontak">
            </div>
            <div class="form-group">
                <label for="supplier_alamat">Alamat Supplier</label>
                <textarea class="form-control" id="supplier_alamat" name="supplier_alamat"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ url('supplier') }}" class="btn btn-warning">Kembali</a>
        </form>
    </div>
</div>
@endsection