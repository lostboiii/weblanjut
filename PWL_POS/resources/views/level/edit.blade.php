@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools"></div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('level/'.$level->level_id) }}" class="form-horizontal">
            @csrf
            @method('PUT')
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Kode</label>
                <div class="col-11">
                    <input type="text" class="form-control" id="level_kode" name="level_kode" value="{{ old('level_kode', $level->level_kode) }}" required>
                    @error('level_kode')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Nama</label>
                <div class="col-11">
                    <input type="text" class="form-control" id="level_nama" name="level_nama" value="{{ old('level_nama', $level->level_nama) }}" required>
                    @error('level_nama')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <div class="offset-1 col-11">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="{{ url('level') }}" class="btn btn-warning">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection