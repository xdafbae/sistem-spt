<aside
class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
id="sidenav-main">
<div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
        aria-hidden="true" id="iconSidenav"></i>
    <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/argon-dashboard/pages/dashboard.html "
        target="_blank">
        <img src="{{ asset('style/assets/img/logo-ct-dark.png') }}" class="navbar-brand-img h-100"
            style="width: 26px; height: 26px;" alt="main_logo">
        <span class="ms-1 font-weight-bold">Creative Tim</span>
    </a>
</div>
<hr class="horizontal dark mt-0">
<div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <div
                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
                </div>
                <span class="nav-link-text ms-1">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link " href="{{ route('spts.index') }}">
                <div
                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="ni ni-email-83 text-dark text-sm opacity-10"></i>
                </div>
                <span class="nav-link-text ms-1">Surat Perintah Tugas</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link " href="#">
                <div
                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="ni ni-email-83 text-dark text-sm opacity-10"></i>
                </div>
                <span class="nav-link-text ms-1">SPPD</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link " href="../pages/virtual-reality.html">
                <div
                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="ni ni-app text-dark text-sm opacity-10"></i>
                </div>
                <span class="nav-link-text ms-1">Rekap Perjalanan</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link " href="{{ asset('style/pages/rtl.html') }}">
                <div
                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="ni ni-world-2 text-dark text-sm opacity-10"></i>
                </div>
                <span class="nav-link-text ms-1">RTL</span>
            </a>
        </li>
        @role('admin')
        <li class="nav-item mt-3">
            <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account pages</h6>
        </li>
        <li class="nav-item">
            <a class="nav-link " href="{{ route('users.index') }}">
                <div
                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                </div>
                <span class="nav-link-text ms-1">User</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link " href="{{ route('roles.index') }}">
                <div
                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                </div>
                <span class="nav-link-text ms-1">Role</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link " href="{{ route('permissions.index') }}">
                <div
                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                </div>
                <span class="nav-link-text ms-1">Permission</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link " href="../pages/sign-in.html">
                <div
                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="ni ni-button-power text-dark"></i>
                </div>
                <span class="nav-link-text ms-1">Logout</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link " href="../pages/sign-up.html">
                <div
                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="ni ni-collection text-dark text-sm opacity-10"></i>
                </div>
                <span class="nav-link-text ms-1">Sign Up</span>
            </a>
        </li>
    </ul>
</div>
@endrole
</aside>