@extends('layouts.template')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center mb-3">
                            <img class="profile-user-img img-fluid img-circle" 
                                src="{{ Auth::user()->foto ? asset('storage/profile/' . Auth::user()->foto) : asset('adminlte/dist/img/user4-128x128.jpg') }}" 
                                alt="Foto Profil" 
                                id="preview-image">
                        </div>

                        <h3 class="profile-username text-center">{{ Auth::user()->nama }}</h3>
                        <p class="text-muted text-center">{{ Auth::user()->level->level_nama ?? 'User' }}</p>

                        <form action="{{ url('/profile/upload-image') }}" method="POST" enctype="multipart/form-data" 
                            id="upload-form" class="mt-4">
                            @csrf
                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="foto" name="foto" 
                                        accept="image/*" required>
                                    <label class="custom-file-label" for="foto">Pilih File</label>
                                </div>
                                @error('foto')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-upload mr-2"></i> Unggah Foto Profil
                                </button>
                            </div>
                        </form>

                        <div class="text-muted mt-3 small">
                            <p class="mb-1"><i class="fas fa-info-circle mr-1"></i> Persyaratan Gambar:</p>
                            <ul class="pl-4">
                                <li>Ukuran maksimal file: 2MB</li>
                                <li>Format yang diperbolehkan: JPG, JPEG, PNG</li>
                                <li>Ukuran yang disarankan: 200x200 piksel</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('css')
<style>
.profile-user-img {
    border: 3px solid #adb5bd;
    margin: 0 auto;
    padding: 3px;
    width: 200px;
    height: 200px;
    object-fit: cover;
}

.custom-file-label::after {
    content: "Pilih";
}
</style>
@endpush

@push('js')
<script>
$(document).ready(function() {
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
        
        const file = this.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(event) {
                $('#preview-image').attr('src', event.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

    $('#upload-form').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin mr-2"></i> Mengunggah...');
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: xhr.responseJSON.message || 'Terjadi kesalahan!',
                });
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false)
                    .html('<i class="fas fa-upload mr-2"></i> Unggah Foto Profil');
            }
        });
    });
});
</script>
@endpush