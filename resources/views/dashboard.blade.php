@extends('layouts.app')

@section('content')

    <div class="container-fluid py-4">
        <!-- Informasi Pengguna dan Konten Berdasarkan Role -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Selamat Datang, {{ Auth::user()->nama }}!</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Role Anda: <strong>{{ Auth::user()->roles->pluck('name')->implode(', ') }}</strong></p>

                        <!-- Konten Khusus Berdasarkan Role -->
                        @if(Auth::user()->hasRole('admin'))
                            <div class="alert alert-info">
                                <h4>Anda adalah Admin</h4>
                                <p>Anda memiliki akses penuh ke sistem.</p>
                            </div>
                        @elseif(Auth::user()->hasRole('karyawan'))
                            <div class="alert alert-warning">
                                <h4>Anda adalah Pegawai</h4>
                                <p>Anda dapat mengakses fitur-fitur khusus pegawai.</p>
                            </div>
                        @elseif(Auth::user()->hasRole('operator'))
                            <div class="alert alert-success">
                                <h4>Anda adalah Operator</h4>
                                <p>Anda dapat mengelola data dan operasional sistem.</p>
                            </div>
                        @elseif(Auth::user()->hasRole('atasan'))
                            <div class="alert alert-primary">
                                <h4>Anda adalah Atasan</h4>
                                <p>Anda memiliki akses ke laporan dan monitoring.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Konten Dashboard yang Sudah Ada -->
        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Today's Money</p>
                                    <h5 class="font-weight-bolder">$53,000</h5>
                                    <p class="mb-0">
                                        <span class="text-success text-sm font-weight-bolder">+55%</span>
                                        since yesterday
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                    <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Today's Users</p>
                                    <h5 class="font-weight-bolder">2,300</h5>
                                    <p class="mb-0">
                                        <span class="text-success text-sm font-weight-bolder">+3%</span>
                                        since last week
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                    <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">New Clients</p>
                                    <h5 class="font-weight-bolder">+3,462</h5>
                                    <p class="mb-0">
                                        <span class="text-danger text-sm font-weight-bolder">-2%</span>
                                        since last quarter
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                    <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Sales</p>
                                    <h5 class="font-weight-bolder">$103,430</h5>
                                    <p class="mb-0">
                                        <span class="text-success text-sm font-weight-bolder">+5%</span> than last month
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                    <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- Penutupan row -->
    </div> <!-- Penutupan container-fluid -->
</main>
@endsection