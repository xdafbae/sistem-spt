@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detail SPT</h5>
            <div>
                @if($spt->status == 'Selesai')
                <a href="{{ route('spts.print', $spt->id) }}" class="btn btn-secondary" target="_blank">
                    <i class="fas fa-print"></i> Cetak
                </a>
                @endif
                <a href="{{ route('spts.index') }}" class="btn btn-primary">Kembali</a>
            </div>
        </div>
        <div class="card-body">
            @if($spt->catatan)
            <div class="alert alert-warning">
                <strong>Catatan:</strong> {{ $spt->catatan }}
            </div>
            @endif
            
            <div class="row mb-3">
                <div class="col-md-3 font-weight-bold">Nomor Surat</div>
                <div class="col-md-9">{{ $spt->nomor_surat }}</div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-3 font-weight-bold">Tanggal Pengajuan</div>
                <div class="col-md-9">{{ $spt->tanggal_pengajuan->format('d-m-Y') }}</div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-3 font-weight-bold">Status</div>
                <div class="col-md-9">
                    <span class="badge 
                        @if($spt->status == 'Menunggu Verifikasi') bg-warning 
                        @elseif($spt->status == 'Diverifikasi Oleh Operator') bg-info 
                        @elseif($spt->status == 'Dikembalikan') bg-danger 
                        @elseif($spt->status == 'Selesai') bg-success 
                        @endif">
                        {{ $spt->status }}
                    </span>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-3 font-weight-bold">Nama</div>
                <div class="col-md-9">{{ $spt->nama }}</div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-3 font-weight-bold">NIP</div>
                <div class="col-md-9">{{ $spt->nip }}</div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-3 font-weight-bold">Tanggal Mulai</div>
                <div class="col-md-9">{{ $spt->tanggal_mulai->format('d-m-Y') }}</div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-3 font-weight-bold">Tanggal Selesai</div>
                <div class="col-md-9">{{ $spt->tanggal_selesai->format('d-m-Y') }}</div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-3 font-weight-bold">Dasar</div>
                <div class="col-md-9">{!! $spt->dasar !!}</div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-3 font-weight-bold">Tujuan</div>
                <div class="col-md-9">{!! $spt->tujuan !!}</div>
            </div>

            <!-- Bagian menampilkan anggota -->
            <div class="row mb-3">
                <div class="col-md-3 font-weight-bold">Anggota SPT</div>
                <div class="col-md-9">
                    @foreach($spt->users as $index => $user)
                        <div class="mb-2">
                            <strong>{{ $index + 1 }}. {{ $user->nama }}</strong><br>
                            NIP: {{ $user->nip }}<br>
                            Pangkat: {{ $user->pangkat }}<br>
                            Jabatan: {{ $user->jabatan }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection