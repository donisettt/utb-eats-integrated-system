<div class="sidebar d-flex flex-column p-3 shadow">
    <div class="text-center mb-4 pt-2">
        <div class="bg-white rounded p-2 d-inline-block mb-2">
            <img src="{{ asset('images/logo.png') }}" alt="UTB Logo" width="50">
        </div>
        <h5 class="fw-bold m-0">UTB Eats</h5>
        <small class="text-white-50">Admin Panel</small>
    </div>

    <hr class="text-white-50">

    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}"
                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.merchants') }}"
                class="nav-link {{ request()->routeIs('admin.merchants*') ? 'active' : '' }}">
                <i class="bi bi-shop"></i>
                Data Mitra
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.products') }}"
                class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                <i class="bi bi-basket"></i> Data Menu
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.students') }}"
                class="nav-link {{ request()->routeIs('admin.students*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Data Pengguna
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.fees') }}"
                class="nav-link {{ request()->routeIs('admin.fees*') ? 'active' : '' }}">
                <i class="bi bi-cash-stack"></i> Iuran Mitra
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.banners') }}"
                class="nav-link {{ request()->routeIs('admin.banners*') ? 'active' : '' }}">
                <i class="bi bi-images"></i> Banner Promo
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center justify-content-between" data-bs-toggle="collapse"
                href="#reportSubmenu" role="button" aria-expanded="false">
                <div>
                    <i class="bi bi-file-earmark-bar-graph"></i>
                    <span>Pusat Laporan</span>
                </div>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.reports*') ? 'show' : '' }}" id="reportSubmenu">
                <ul class="nav flex-column ms-3 border-start border-white border-opacity-25 ps-2">
                    <li class="nav-item">
                        <a href="{{ route('admin.reports') }}"
                            class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }} py-1 small">
                            Trans. Penjualan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.reports.fees') }}"
                            class="nav-link {{ request()->routeIs('admin.reports.fees') ? 'active' : '' }} py-1 small">
                            Iuran Mitra
                        </a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</div>
