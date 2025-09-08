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
