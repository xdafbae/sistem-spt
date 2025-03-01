@extends('layouts.app')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Custom style untuk Select2 */
    .select2-container--default .select2-selection--single {
        height: 38px;
        border: 1px solid #ced4da;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    /* Style untuk form */
    .required:after {
        content: ' *';
        color: red;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Ajukan SPT Baru</h5>
                        <a href="{{ route('spts.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('spts.store') }}" method="POST" id="spt-form">
                        @csrf
                        
                        <!-- Anggota SPT -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Anggota SPT</h6>
                            </div>
                            <div class="card-body">
                                <div id="anggota-container">
                                    <div class="anggota-item mb-3">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="required">Nama</label>
                                                <select class="form-control user-select @error('users.0.id') is-invalid @enderror" 
                                                        name="users[0][id]" required>
                                                </select>
                                                @error('users.0.id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 mt-2">
                                                <label>NIP</label>
                                                <input type="text" class="form-control" readonly disabled>
                                            </div>
                                            <div class="col-md-4 mt-2">
                                                <label>Pangkat</label>
                                                <input type="text" class="form-control" readonly disabled>
                                            </div>
                                            <div class="col-md-4 mt-2">
                                                <label>Jabatan</label>
                                                <input type="text" class="form-control" readonly disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="button" class="btn btn-secondary mt-2" id="add-anggota">
                                    <i class="fas fa-plus"></i> Tambah Anggota
                                </button>
                            </div>
                        </div>

                        <!-- Informasi SPT -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Informasi SPT</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Tanggal Mulai -->
                                    <div class="col-md-6 mb-3">
                                        <label for="tanggal_mulai" class="required">Tanggal Mulai</label>
                                        <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                                               id="tanggal_mulai" name="tanggal_mulai" 
                                               value="{{ old('tanggal_mulai') }}" required>
                                        @error('tanggal_mulai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Tanggal Selesai -->
                                    <div class="col-md-6 mb-3">
                                        <label for="tanggal_selesai" class="required">Tanggal Selesai</label>
                                        <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                               id="tanggal_selesai" name="tanggal_selesai" 
                                               value="{{ old('tanggal_selesai') }}" required>
                                        @error('tanggal_selesai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Dasar -->
                                    <div class="col-md-12 mb-3">
                                        <label for="dasar" class="required">Dasar</label>
                                        <textarea class="form-control ckeditor @error('dasar') is-invalid @enderror" 
                                                  id="dasar" name="dasar" rows="5" required>{{ old('dasar') }}</textarea>
                                        @error('dasar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            Masukkan dasar/alasan dibuatnya SPT ini.
                                        </small>
                                    </div>

                                    <!-- Tujuan -->
                                    <div class="col-md-12 mb-3">
                                        <label for="tujuan" class="required">Tujuan</label>
                                        <textarea class="form-control ckeditor @error('tujuan') is-invalid @enderror" 
                                                  id="tujuan" name="tujuan" rows="5" required>{{ old('tujuan') }}</textarea>
                                        @error('tujuan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            Masukkan tujuan/maksud dari SPT ini.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Ajukan SPT
                            </button>
                            <a href="{{ route('spts.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="//cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
$(document).ready(function() {
    // Inisialisasi CKEditor
    CKEDITOR.replace('dasar', {
        height: 200,
        removeButtons: 'Image,Table,HorizontalRule,SpecialChar,Source'
    });
    CKEDITOR.replace('tujuan', {
        height: 200,
        removeButtons: 'Image,Table,HorizontalRule,SpecialChar,Source'
    });
    
    // Fungsi untuk inisialisasi Select2
    function initializeSelect2(element) {
        $(element).select2({
            placeholder: 'Cari berdasarkan nama atau NIP...',
            minimumInputLength: 2,
            ajax: {
                url: '{{ route("api.users.search") }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.nama + ' (' + item.nip + ')',
                                nip: item.nip,
                                pangkat: item.pangkat,
                                jabatan: item.jabatan
                            };
                        })
                    };
                },
                cache: true
            }
        }).on('select2:select', function(e) {
            var data = e.params.data;
            var container = $(this).closest('.anggota-item');
            container.find('input').eq(0).val(data.nip);
            container.find('input').eq(1).val(data.pangkat);
            container.find('input').eq(2).val(data.jabatan);
        });
    }

    // Inisialisasi Select2 untuk elemen yang sudah ada
    initializeSelect2('.user-select');

    // Counter untuk ID unik
    let counter = 1;

    // Tambah anggota baru
    $('#add-anggota').click(function() {
        let template = `
            <div class="anggota-item mb-3">
                <div class="row">
                    <div class="col-md-12">
                        <label class="required">Nama</label>
                        <select class="form-control user-select" name="users[${counter}][id]" required>
                        </select>
                    </div>
                    <div class="col-md-4 mt-2">
                        <label>NIP</label>
                        <input type="text" class="form-control" readonly disabled>
                    </div>
                    <div class="col-md-4 mt-2">
                        <label>Pangkat</label>
                        <input type="text" class="form-control" readonly disabled>
                    </div>
                    <div class="col-md-4 mt-2">
                        <label>Jabatan</label>
                        <input type="text" class="form-control" readonly disabled>
                    </div>
                    <div class="col-12 mt-2">
                        <button type="button" class="btn btn-danger btn-sm remove-anggota">
                            <i class="fas fa-times"></i> Hapus Anggota
                        </button>
                    </div>
                </div>
                <hr>
            </div>
        `;
        
        $('#anggota-container').append(template);
        initializeSelect2(`select[name="users[${counter}][id]"]`);
        counter++;
    });

    // Hapus anggota
    $(document).on('click', '.remove-anggota', function() {
        $(this).closest('.anggota-item').remove();
    });

    // Validasi form sebelum submit
    $('#spt-form').on('submit', function(e) {
        let valid = true;
        
        // Cek apakah ada anggota yang dipilih
        if ($('.user-select').length === 0) {
            alert('Minimal harus ada satu anggota SPT!');
            valid = false;
        }

        // Cek apakah semua anggota sudah dipilih
        $('.user-select').each(function() {
            if (!$(this).val()) {
                alert('Silakan pilih semua anggota SPT!');
                valid = false;
                return false;
            }
        });

        // Cek tanggal
        const tanggalMulai = new Date($('#tanggal_mulai').val());
        const tanggalSelesai = new Date($('#tanggal_selesai').val());
        if (tanggalSelesai < tanggalMulai) {
            alert('Tanggal selesai tidak boleh lebih awal dari tanggal mulai!');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
        }
    });
});
</script>
@endpush