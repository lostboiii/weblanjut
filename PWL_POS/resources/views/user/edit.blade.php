@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools"></div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('user/'.$user->user_id) }}" class="form-horizontal">
            @csrf
            @method('PUT')
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Level</label>
                <div class="col-11">
                    <select class="form-control" id="level_id" name="level_id" required>
                        <option value="">- Pilih -</option>
                        @foreach($level as $item)
                            <option value="{{ $item->level_id }}" {{ $user->level_id == $item->level_id ? 'selected' : '' }}>
                                {{ $item->level_nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('level_id')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Username</label>
                <div class="col-11">
                    <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                    @error('username')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Nama</label>
                <div class="col-11">
                    <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required>
                    @error('nama')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Password</label>
                <div class="col-11">
                    <input type="password" class="form-control" id="password" name="password">
                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password</small>
                    @error('password')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <div class="offset-1 col-11">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="{{ url('user') }}" class="btn btn-warning">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('css')
@endpush

@push('js')
@endpush