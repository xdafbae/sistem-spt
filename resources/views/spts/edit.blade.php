@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Edit SPT</h5>
        </div>
        <div class="card-body">
            @if($spt->catatan)
            <div class="alert alert-warning">
                <strong>Catatan:</strong> {{ $spt->catatan }}
            </div>
            @endif
            
            <form action="{{ route('spts.update', $spt->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group mb-3">
                    <label for="nomor_surat">Nomor Surat</label>
                    <input type="text" class="form-control" id="nomor_surat" value="{{ $spt->nomor_surat }}" readonly>
                </div>
                
                <div class="form-group mb-3">
                    <label for="tanggal_pengajuan">Tanggal Pengajuan</label>
                    <input type="text" class="form-control" id="tanggal_pengajuan" value="{{ $spt->tanggal_pengajuan->format('d-m-Y') }}" readonly>
                </div>
                
                <div class="form-group mb-3">
                    <label for="nama">Nama</label>
                    <select class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" required>
                        <option value="">Pilih Nama</option>
                        @foreach($users as $user)
                            <option value="{{ $user->nama }}">
                                {{ $user->nama }} ({{ $user->nip }})
                            </option>
                        @endforeach
                    </select>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                {{-- Tambahkan ini untuk debug --}}
                @if(count($users) == 0)
                    <div class="alert alert-warning">
                        Tidak ada data karyawan yang ditemukan.
                    </div>
                @endif
                
                <div class="form-group mb-3">
                    <label for="nip">NIP</label>
                    <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip" name="nip" value="{{ old('nip', $spt->nip) }}" readonly>
                    @error('nip')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group mb-3">
                    <label for="tanggal_mulai">Tanggal Mulai</label>
                    <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai', $spt->tanggal_mulai->format('Y-m-d')) }}" required>
                    @error('tanggal_mulai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group mb-3">
                    <label for="tanggal_selesai">Tanggal Selesai</label>
                    <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai', $spt->tanggal_selesai->format('Y-m-d')) }}" required>
                    @error('tanggal_selesai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group mb-3">
                    <label for="dasar">Dasar</label>
                    <textarea class="form-control ckeditor @error('dasar') is-invalid @enderror" id="dasar" name="dasar" rows="5" required>{{ old('dasar', $spt->dasar) }}</textarea>
                    @error('dasar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group mb-3">
                    <label for="tujuan">Tujuan</label>
                    <textarea class="form-control ckeditor @error('tujuan') is-invalid @enderror" id="tujuan" name="tujuan" rows="5" required>{{ old('tujuan', $spt->tujuan) }}</textarea>
                    @error('tujuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Perbarui SPT</button>
                    <a href="{{ route('spts.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="//cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    $(document).ready(function() {
        // Inisialisasi CKEditor
        CKEDITOR.replace('dasar');
        CKEDITOR.replace('tujuan');
        
        // Auto-fill NIP berdasarkan nama
        $('#nama').change(function() {
            var nama = $(this).val();
            if (nama) {
                $.ajax({
                    url: "{{ route('api.get-nip') }}",
                    type: "GET",
                    data: { nama: nama },
                    success: function(data) {
                        $('#nip').val(data.nip);
                    }
                });
            } else {
                $('#nip').val('');
            }
        });
        
        // Trigger change jika ada nilai default
        if ($('#nama').val()) {
            $('#nama').trigger('change');
        }
    });
</script>
@endpush