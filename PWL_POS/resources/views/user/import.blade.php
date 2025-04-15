@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Import Data User</h3>
        <div class="card-tools"></div>
    </div>
    <div class="card-body">
        <form id="importForm" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file_user">File Excel</label>
                <input type="file" class="form-control" id="file_user" name="file_user" accept=".xlsx" required>
                <small class="form-text text-muted">Format file harus .xlsx</small>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary" id="btnImport">Import</button>
                <a href="{{ url('user') }}" class="btn btn-default">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('css')
@endpush

@push('js')
<script>
    $(document).ready(function() {
        $('#importForm').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            
            $.ajax({
                url: "{{ url('user/import_ajax') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status) {
                        alert(response.message);
                        window.location.href = "{{ url('user') }}";
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan saat mengimport data');
                }
            });
        });
    });
</script>
@endpush 