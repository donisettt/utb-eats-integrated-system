<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4 py-3">
    <div class="container-fluid p-0">
        <span class="navbar-text text-muted">
            Selamat Datang, <span class="fw-bold text-utb-blue">Admin Kampus</span>
        </span>

        <div class="d-flex align-items-center">
            <button class="btn btn-light position-relative me-3 rounded-circle">
                <i class="bi bi-bell"></i>
                <span
                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light">
                    0
                </span>
            </button>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle"
                    id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://ui-avatars.com/api/?name=Admin+UTB&background=305089&color=fff" alt="mdo"
                        width="32" height="32" class="rounded-circle me-2">
                </a>

                <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="dropdownUser2">

                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person me-2 text-muted"></i> Profile
                        </a>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger d-flex align-items-center">
                                <i class="bi bi-box-arrow-right me-2"></i> Sign out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
