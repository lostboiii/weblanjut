@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <td>{{ $supplier->supplier_id }}</td>
            </tr>
            <tr>
                <th>Nama Supplier</th>
                <td>{{ $supplier->supplier_nama }}</td>
            </tr>
            <tr>
                <th>Kontak Supplier</th>
                <td>{{ $supplier->supplier_kontak }}</td>
            </tr>
            <tr>
                <th>Alamat Supplier</th>
                <td>{{ $supplier->supplier_alamat }}</td>
            </tr>
        </table>
        <a href="{{ url('supplier') }}" class="btn btn-warning">Kembali</a>
    </div>
</div>
@endsection