<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Pengelolaan PKL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #4e73df 0%, #1cc88a 100%);
            min-height: 100vh;
        }

        .login-card {
            border-radius: 1rem;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.1);
        }

        .logo-pkl {
            width: 60px;
            height: 60px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem auto;
            font-size: 2rem;
            color: #1cc88a;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>

<body>
    <div class="container d-flex align-items-center justify-content-center" style="min-height:100vh;">
        <div class="col-md-5">
            <div class="card login-card p-4">
                <div class="logo-pkl mb-3">
                    <i class="bi bi-briefcase"></i>
                </div>
                <h3 class="text-center mb-4 text-success">Sistem Pengelolaan PKL</h3>
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
                    <button type="submit" class="btn btn-success w-100">Masuk</button>
                </form>
                <div class="mt-3 text-center">
                    <small>Belum punya akun? <a href="/data">data</a></small>
                </div>
                <div class="mt-3 text-center">
                    <small>sudah punya akun? <a href="/home">home</a></small>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
