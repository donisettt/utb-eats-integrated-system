<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel UTB Eats')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --utb-blue: #305089;
            --utb-green: #67B342;
            --utb-light-blue: #267ED1;
            --bg-gray: #f4f6f9;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-gray);
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        .sidebar {
            min-width: 260px;
            max-width: 260px;
            background: var(--utb-blue);
            color: #fff;
            min-height: 100vh;
            transition: all 0.3s;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            margin-bottom: 5px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            font-weight: 500;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .sidebar .nav-link.active {
            background-color: var(--utb-green);
            color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            font-size: 1.1rem;
        }

        /* Main Content */
        .main-content {
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Card Styling Premium */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
        }

        .btn-utb {
            background-color: var(--utb-green);
            color: white;
            font-weight: 600;
        }

        .btn-utb:hover {
            background-color: #559635;
            color: white;
        }

        .text-utb-blue {
            color: var(--utb-blue);
        }

        .text-utb-green {
            color: var(--utb-green);
        }

        /* Table Styling */
        .table thead th {
            background-color: #f8f9fa;
            color: #6c757d;
            font-weight: 600;
            border-bottom: 2px solid #e9ecef;
        }
    </style>
</head>

<body>

    <div class="d-flex">
        @include('partials.admin.sidebar')

        <div class="main-content">
            @include('partials.admin.navbar')

            <div class="p-4">
                @if (session('success'))
                    <div class="alert alert-success border-0 shadow-sm d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                <h2 class="fw-bold text-utb-blue mb-4">@yield('title')</h2>

                @yield('content')
            </div>

            @include('partials.admin.footer')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session("success") }}',
            confirmButtonColor: '#305089', // Biru UTB
            timer: 3000 // Otomatis tutup dalam 3 detik
        });
    </script>
    @endif
    
</body>

</html>
