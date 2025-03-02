@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail SPT</h5>
                <div>
                    @if($spt->status == 'Selesai')
                        <div class="btn-group">
                            <a href="{{ route('spts.print', $spt->id) }}" class="btn btn-secondary" target="_blank">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                            <a href="{{ route('spts.export-word', $spt->id) }}" class="btn btn-secondary">
                                <i class="fas fa-file-word"></i> Word
                            </a>
                        </div>
                    @endif

                    @if(auth()->user()->hasRole('karyawan') && $spt->status == 'Dikembalikan' && $spt->user_id == auth()->id())
                        <a href="{{ route('spts.edit', $spt->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    @endif

                    @if(auth()->user()->hasRole('operator') && in_array($spt->status, ['Menunggu Verifikasi', 'Dikembalikan']))
                        <a href="{{ route('spts.verify', $spt->id) }}" class="btn btn-warning">
                            <i class="fas fa-check"></i> Verifikasi
                        </a>
                    @endif

                    @if(auth()->user()->hasRole('atasan') && $spt->status == 'Diverifikasi Oleh Operator')
                        <a href="{{ route('spts.approve', $spt->id) }}" class="btn btn-success">
                            <i class="fas fa-check-double"></i> Setujui
                        </a>
                    @endif

                    <a href="{{ route('spts.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            @if($spt->catatan)
                <div class="alert alert-warning">
                    <strong><i class="fas fa-info-circle"></i> Catatan:</strong> {{ $spt->catatan }}
                </div>
            @endif

            <!-- Informasi Umum -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Informasi Umum</h6>
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
                                    <td width="35%">Status</td>
                                    <td width="5%">:</td>
                                    <td>
                                        <span class="badge {{ $statusBadgeClass[$spt->status] }}">
                                            {{ $spt->status }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tanggal Mulai</td>
                                    <td>:</td>
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

            <!-- Riwayat Status (Opsional) -->
            @if($spt->status != 'Menunggu Verifikasi')
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Riwayat Status</h6>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-0">Pengajuan SPT</h6>
                                    <small class="text-muted">
                                        {{ $spt->created_at->format('d F Y H:i') }} oleh {{ $spt->creator->nama }}
                                    </small>
                                </div>
                            </div>
                            <!-- Tambahkan riwayat status lainnya sesuai kebutuhan -->
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Style untuk timeline */
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-top: 5px;
}

.timeline-content {
    padding-bottom: 15px;
    border-bottom: 1px dashed #dee2e6;
}

.timeline-item:last-child .timeline-content {
    border-bottom: none;
    padding-bottom: 0;
}
</style>
@endsection