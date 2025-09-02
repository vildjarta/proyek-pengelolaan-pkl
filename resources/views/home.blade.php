<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - Sistem Pengelolaan PKL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #4e73df 0%, #1cc88a 100%);
            min-height: 100vh;
        }

        .home-card {
            border-radius: 1rem;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.1);
        }

        .logo-pkl {
            width: 70px;
            height: 70px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem auto;
            font-size: 2.5rem;
            color: #4e73df;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>

<body>
    <div class="container d-flex align-items-center justify-content-center" style="min-height:100vh;">
        <div class="col-md-7">
            <div class="card home-card p-5">
                <div class="logo-pkl mb-3">
                    <i class="bi bi-house-door"></i>
                </div>
                <h2 class="text-center mb-4 text-primary">Selamat Datang di Sistem Pengelolaan PKL</h2>
                <p class="text-center">
                    Sistem ini membantu pengelolaan data Praktik Kerja Lapangan (PKL) di sekolah atau institusi pendidikan secara efisien dan transparan.
                </p>
                <div class="row mt-4 mb-2">
                    <div class="col-12 col-md-6 mb-2">
                        <a href="/login" class="btn btn-success w-100">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login
                        </a>
                    </div>
                    <div class="col-12 col-md-6 mb-2">
                        <a href="/about" class="btn btn-outline-primary w-100">
                            <i class="bi bi-info-circle me-2"></i>Tentang Aplikasi
                        </a>
                    </div>
                </div>
                <div class="mt-3 text-center">
                    <small class="text-muted">© {{ date('Y') }} Sistem Pengelolaan PKL</small>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>