<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Sistem Pengelolaan PKL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #F7FBFC 0%, #D6E6F2 35%, #B9D7EA 70%, #769FCD 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .register-card {
            border-radius: 1rem;
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.15);
            background-color: #ffffff;
            padding: 2rem;
            border: 1px solid #e6f0fa;
        }
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
        .btn-register {
            background-color: #769FCD;
            color: #fff;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            padding: 10px;
            transition: 0.3s ease-in-out;
        }
        .btn-register:hover {
            background-color: #5f88b2;
        }
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
        <div class="col-md-6 col-lg-5">
            <div class="card register-card">
                <!-- Logo -->
                <div class="logo-pkl mb-3">
                    <img src="{{ asset('assets/images/logo-baru.png') }}" alt="Logo PKL">
                </div>

                <h3 class="text-center mb-4" style="color:#769FCD;">Buat Akun PKL</h3>

                <!-- Form Registrasi -->
                <form method="POST" action="#">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="name" name="name" required placeholder="Nama Lengkap">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Pengguna</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="nama@email.com">
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required placeholder="Username">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="********">
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="********">
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Daftar Sebagai</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="" disabled selected>Pilih peran</option>
                            <option value="siswa">Siswa</option>
                            <option value="guru">Guru</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-register w-100">Daftar</button>
                </form>

                <!-- Link Login dan Home -->
                <div class="mt-3 text-center text-link">
                    <small>Sudah punya akun? <a href="/login">Masuk</a></small>
                </div>
                <div class="mt-2 text-center text-link">
                    <small>Kembali ke <a href="/home">Home</a></small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
