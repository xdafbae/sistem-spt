@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Surat Perintah Tugas (SPT)</h5>
            @if(auth()->user()->hasRole('karyawan'))
            <a href="{{ route('spts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajukan SPT Baru
            </a>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="spt-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Surat</th>
                            <th>Nama</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function() {
        $('#spt-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('spts.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'nomor_surat', name: 'nomor_surat' },
                { data: 'nama_anggota', name: 'nama_anggota' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });
</script>
@endpush