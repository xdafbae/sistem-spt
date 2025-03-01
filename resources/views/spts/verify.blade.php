@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Verifikasi SPT</h5>
                <a href="{{ route('spts.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Informasi SPT -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Informasi SPT</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="35%">Nomor Surat</td>
                                    <td width="5%">:</td>
                                    <td><strong>{{ $spt->nomor_surat }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Tanggal Pengajuan</td>
                                    <td>:</td>
                                    <td>{{ $spt->tanggal_pengajuan->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <td>Diajukan Oleh</td>
                                    <td>:</td>
                                    <td>{{ $spt->creator->nama }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="35%">Tanggal Mulai</td>
                                    <td width="5%">:</td>
                                    <td>{{ $spt->tanggal_mulai->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Selesai</td>
                                    <td>:</td>
                                    <td>{{ $spt->tanggal_selesai->format('d F Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Anggota SPT -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Anggota SPT</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama</th>
                                    <th>NIP</th>
                                    <th>Pangkat</th>
                                    <th>Jabatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($spt->users as $index => $user)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $user->nama }}</td>
                                        <td>{{ $user->nip }}</td>
                                        <td>{{ $user->pangkat }}</td>
                                        <td>{{ $user->jabatan }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Dasar dan Tujuan -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Dasar dan Tujuan</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <label class="form-label fw-bold">Dasar:</label>
                            <div class="border rounded p-3">
                                {!! $spt->dasar !!}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Tujuan:</label>
                            <div class="border rounded p-3">
                                {!! $spt->tujuan !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Verifikasi -->
            <form action="{{ route('spts.verify.update', $spt->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Form Verifikasi</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="status" class="required">Status Verifikasi</label>
                            <select class="form-control @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="">Pilih Status</option>
                                <option value="Diverifikasi Oleh Operator">Diverifikasi</option>
                                <option value="Dikembalikan">Dikembalikan</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3" id="catatan-group" style="display: none;">
                            <label for="catatan" class="required">Catatan</label>
                            <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                      id="catatan" name="catatan" rows="3">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Berikan catatan mengapa SPT dikembalikan.
                            </small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                            <a href="{{ route('spts.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.required:after {
    content: ' *';
    color: red;
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    $('#status').change(function() {
        if ($(this).val() == 'Dikembalikan') {
            $('#catatan-group').show();
            $('#catatan').attr('required', true);
        } else {
            $('#catatan-group').hide();
            $('#catatan').attr('required', false);
        }
    });
});
</script>
@endpush
@endsection