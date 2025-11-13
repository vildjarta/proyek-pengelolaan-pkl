<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Pengelolaan PKL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* === Background Baru === */
        body {
            background: linear-gradient(135deg, #F7FBFC 0%, #D6E6F2 35%, #B9D7EA 70%, #769FCD 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* === Card Login === */
        .login-card {
            border-radius: 1rem;
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.15);
            background-color: #ffffff;
            padding: 2rem;
            border: 1px solid #e6f0fa;
        }

        /* === Logo === */
        .logo-pkl {
            width: 90px;
            height: 90px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem auto;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .logo-pkl img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* === Tombol Login === */
        .btn-login {
            background-color: #769FCD;
            color: #fff;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            padding: 10px;
            transition: 0.3s ease-in-out;
        }

        .btn-login:hover {
            background-color: #5f88b2;
        }

        /* === Tombol Google === */
        .btn-google {
            background-color: #fff;
            color: #444;
            font-weight: 600;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            transition: 0.3s ease-in-out;
            text-decoration: none;
        }

        .btn-google:hover {
            background-color: #f8f9fa;
            border-color: #999;
            color: #444;
        }

        /* === Link bawah === */
        .text-link a {
            color: #769FCD;
            text-decoration: none;
            font-weight: 600;
        }

        .text-link a:hover {
            color: #5f88b2;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container d-flex align-items-center justify-content-center" style="min-height:100vh;">
        <div class="col-md-5">
            <div class="card login-card">
                <!-- Logo -->
                <div class="logo-pkl mb-3">
                    <img src="{{ asset('assets/images/logo-baru.png') }}" alt="Logo PKL">
                </div>

                <h3 class="text-center mb-4" style="color:#769FCD;">Sistem Pengelolaan PKL</h3>

                <!-- Form Login -->
                <form method="POST" action="#">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Pengguna</label>
                        <input type="email" class="form-control" id="email" name="email" required autofocus
                            placeholder="nama@email.com">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <input type="password" class="form-control" id="password" name="password" required
                            placeholder="********">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Ingat Saya</label>
                    </div>
                    <button type="submit" class="btn btn-login w-100">Masuk</button>
                </form>

                <!-- Divider -->
                <div class="text-center my-3">
                    <span style="color: #999;">atau</span>
                </div>

                <!-- Login dengan Google -->
                <a href="{{ route('auth.google') }}" class="btn btn-google w-100 d-flex align-items-center justify-content-center">
                    <svg width="20" height="20" viewBox="0 0 24 24" class="me-2">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Masuk dengan Google
                </a>

                <!-- Link Daftar dan Home -->
                <div class="mt-3 text-center text-link">
                    <small>Belum punya akun? <a href="/registrasi">Buat akun</a></small>
                </div>
                <div class="mt-2 text-center text-link">
                    <small>Sudah punya akun? <a href="/home">home</a></small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
