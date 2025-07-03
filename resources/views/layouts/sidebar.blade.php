@php
    $level = Auth::user()->level;
@endphp

<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <div class="sb-sidenav-menu-heading">Menu</div>

            {{-- Dashboard --}}
            <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ url('/dashboard') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
                Dashboard
            </a>

            {{-- Prediksi --}}
            <a class="nav-link {{ request()->is('prediksi') ? 'active' : '' }}" href="{{ url('/prediksi') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                Prediksi
            </a>

            {{-- Lihat Data (Obat & Pemakaian) --}}
            <div class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center {{ request()->is('datapersediaan*') || request()->is('dataobat*') ? '' : 'collapsed' }}" 
                   href="#" data-bs-toggle="collapse" 
                   data-bs-target="#submenuLihatData" 
                   aria-expanded="{{ request()->is('datapersediaan*') || request()->is('dataobat*') ? 'true' : 'false' }}">
                    <div class="d-flex align-items-center">
                        <div class="sb-nav-link-icon"><i class="fas fa-database"></i></div>
                        Lihat Data
                    </div>
                    <i class="fas fa-caret-down"></i>
                </a>
                <div class="collapse {{ request()->is('datapersediaan*') || request()->is('dataobat*') ? 'show' : '' }}" id="submenuLihatData">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('datapersediaan*') ? 'active' : '' }}" href="{{ url('/datapersediaan') }}">Data Pemakaian</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('dataobat*') ? 'active' : '' }}" href="{{ url('/dataobat') }}">Data Obat</a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Histori: Dapat diakses oleh semua (admin & superadmin) --}}
            <a class="nav-link {{ request()->is('histori') ? 'active' : '' }}" href="{{ url('/histori') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-history"></i></div>
                Lihat Histori
            </a>

            {{-- Data Pengguna: Hanya untuk superadmin --}}
            @if($level === 'superadmin')
                <a class="nav-link {{ request()->is('datauser') ? 'active' : '' }}" href="{{ url('/datauser') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    Data Pengguna
                </a>
            @endif

        </div>
    </div>
</nav>
