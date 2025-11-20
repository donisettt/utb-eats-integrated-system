<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UTB Eats Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #eef2f6;
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            /* Menggunakan min-height agar tidak terpotong di layar kecil */
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        /* Main Card Styling */
        .login-card {
            background: #fff;
            width: 100%;
            max-width: 950px;
            /* Lebar ideal agar proporsional */
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            margin: 20px;
            border: none;
            /* Hilangkan border default */
        }

        /* -- KIRI: FORM AREA -- */
        .form-side {
            padding: 3.5rem;
            /* Padding lebih lega */
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .brand-logo {
            height: 45px;
            /* Ukuran logo pas */
        }

        .form-label {
            color: #64748b;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .input-group-text {
            background: #f8fafc;
            border-right: none;
            border-color: #e2e8f0;
            color: #94a3b8;
            border-radius: 10px 0 0 10px;
            padding-left: 18px;
        }

        .form-control {
            background: #f8fafc;
            border-left: none;
            border-color: #e2e8f0;
            padding: 12px 15px;
            border-radius: 0 10px 10px 0;
            color: #334155;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .form-control:focus {
            background: #fff;
            box-shadow: none;
            border-color: #67B342;
            /* Fokus warna hijau juga */
        }

        .form-control:focus+.input-group-text,
        .input-group:focus-within .input-group-text {
            background: #fff;
            border-color: #67B342;
        }

        /* Tombol Hijau UTB */
        .btn-primary-utb {
            background-color: #67B342;
            /* WARNA HIJAU UTB */
            border: none;
            padding: 14px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1rem;
            width: 100%;
            margin-top: 1rem;
            transition: all 0.3s;
            color: white;
            box-shadow: 0 4px 12px rgba(103, 179, 66, 0.2);
            /* Shadow hijau halus */
        }

        .btn-primary-utb:hover {
            background-color: #559635;
            /* Hijau lebih gelap saat hover */
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(103, 179, 66, 0.3);
            color: white;
        }

        /* -- KANAN: VISUAL AREA -- */
        .visual-side {
            background: linear-gradient(135deg, #305089 0%, #267ED1 100%);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            padding: 3rem;
            text-align: center;
        }

        /* Lingkaran Dekorasi */
        .circle-bg {
            position: absolute;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
        }

        .visual-content {
            position: relative;
            z-index: 2;
        }

        .slider-dots span {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            margin: 0 4px;
            transition: all 0.3s;
        }

        .slider-dots span.active {
            background-color: #fff;
            width: 20px;
            border-radius: 10px;
        }

        /* Link Styles */
        a {
            text-decoration: none;
            transition: color 0.2s;
        }

        .forgot-link {
            color: #305089;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .support-link {
            color: #67B342;
            font-weight: 700;
        }
    </style>
</head>

<body>

    <div class="row g-0 login-card">

        <div class="col-lg-6 form-side">
            <div class="mb-5">
                <div class="d-flex align-items-center mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo UTB" class="brand-logo me-3">
                    <div>
                        <h4 class="fw-bold text-dark m-0 lh-1">UTB Eats</h4>
                        <small class="text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">ADMIN PANEL</small>
                    </div>
                </div>

                <h2 class="fw-bold text-dark mb-2">Log in to Dashboard</h2>
                <p class="text-muted" style="line-height: 1.6;">
                    Selamat datang kembali! Silakan masukan email dan password Anda untuk melanjutkan.
                </p>
            </div>

            @if ($errors->any())
                <div
                    class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-3 mb-4 py-2 small d-flex align-items-center">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="nama@utb.ac.id"
                            value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember"
                            style="cursor: pointer;">
                        <label class="form-check-label text-muted small" for="remember" style="cursor: pointer;">
                            Ingat saya
                        </label>
                    </div>
                    <a href="#" class="forgot-link">Lupa Password?</a>
                </div>

                <button type="submit" class="btn btn-primary-utb">
                    Masuk Sekarang
                </button>

                <div class="text-center mt-4">
                    <p class="text-muted small mb-0">
                        Belum punya akun? <a href="#" class="support-link">Hubungi IT Kampus</a>
                    </p>
                </div>
            </form>
        </div>

        <div class="col-lg-6 visual-side d-none d-lg-flex">
            <div class="circle-bg"></div>

            <div class="visual-content">
                <img src="{{ asset('images/login-ill.svg') }}" alt="Illustration" class="img-fluid mb-4"
                    style="max-height: 320px; filter: drop-shadow(0 15px 30px rgba(0,0,0,0.2));">

                <h3 class="fw-bold mb-2">Digitalisasi Kantin Kampus.</h3>
                <p class="opacity-75 mb-4 px-4" style="line-height: 1.6;">
                    Pantau transaksi, kelola menu, dan lihat laporan penjualan harian dengan mudah dan transparan.
                </p>

                <div class="slider-dots">
                    <span class="active"></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>

    </div>

</body>

</html>
