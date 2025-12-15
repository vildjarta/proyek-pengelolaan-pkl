<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SISTEM PENGELOLAAN PKL</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* === Background & Body Style === */
        body {
            background: url("{{ asset('assets/images/background-pkl.jpg') }}") no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(5, 15, 35, 0.6);
            z-index: -1;
        }

        /* === Card Login (Glassmorphism Effect) === */
        .login-card {
            border-radius: 1.5rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            /* UPDATE: Padding diperkecil agar lebih kompak */
            padding: 2.5rem; 
            color: #fff;
        }

        /* === Judul H3 === */
        .login-title {
            color: #fff;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            /* UPDATE: Font sedikit lebih kecil */
            font-size: 1.75rem; 
            margin-bottom: 1.5rem !important;
        }

        /* === Logo === */
        .logo-pkl {
            /* UPDATE: Logo sedikit diperkecil */
            width: 110px; 
            height: 110px;
            background: rgba(0, 0, 0, 0.2); 
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem auto;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            padding: 0;
            border: 3px solid rgba(255, 255, 255, 0.3);
        }

        .logo-pkl img {
            width: 100%;
            height: 100%;
            object-fit: cover; 
        }

        /* === Form Inputs & Labels === */
        .form-label {
            color: #e0e0e0;
            font-weight: 500;
            font-size: 0.95rem; /* Sedikit lebih kecil */
            margin-bottom: 0.5rem;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            border-radius: 10px;
            padding: 12px; /* Padding input disesuaikan */
            font-size: 1rem;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.2);
            border-color: #769FCD;
            box-shadow: 0 0 0 0.25rem rgba(118, 159, 205, 0.3);
            color: #fff;
        }

        .form-check-label {
            color: #e0e0e0;
            font-size: 0.9rem;
        }

        /* === Tombol Login === */
        .btn-login {
            background: linear-gradient(45deg, #769FCD, #5f88b2);
            color: #fff;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-size: 1.05rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(118, 159, 205, 0.4);
            letter-spacing: 0.5px;
        }

        .btn-login:hover {
            background: linear-gradient(45deg, #5f88b2, #4a6a8a);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(118, 159, 205, 0.5);
        }

        .divider-text {
            color: rgba(255, 255, 255, 0.7) !important;
            font-size: 0.9rem;
        }

        /* === Tombol Google === */
        .btn-google {
            background-color: rgba(255, 255, 255, 0.95);
            color: #444;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            padding: 12px;
            transition: 0.3s ease-in-out;
            text-decoration: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            font-size: 1rem;
        }

        .btn-google:hover {
            background-color: #ffffff;
            color: #333;
            transform: translateY(-1px);
        }
    </style>
</head>

<body>
    <div class="container d-flex align-items-center justify-content-center" style="min-height:100vh; position: relative; z-index: 2;">
        
        <div class="col-md-7 col-lg-5"> 
            
            <div class="card login-card">
                <div class="logo-pkl mb-3">
                    <img src="{{ asset('assets/images/logo-baru.png') }}" alt="Logo PKL">
                </div>

                <h3 class="text-center login-title">SISTEM PENGELOLAAN PKL</h3>

                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Pengguna</label>
                        <input type="email" class="form-control" id="email" name="email" required autofocus
                            placeholder="nama@email.com" value="{{ old('email') }}">

                        @error('email')
                            <div class="mt-1" style="font-size: 0.9rem; color: #ff6b6b;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <input type="password" class="form-control" id="password" name="password" required
                            placeholder="Masukkan kata sandi">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Ingat Saya</label>
                    </div>
                    <button type="submit" class="btn btn-login w-100 mb-3">Masuk</button>
                </form>

                <div class="text-center mb-3">
                    <span class="divider-text">atau</span>
                </div>

                <a href="{{ route('auth.google') }}" class="btn btn-google w-100 d-flex align-items-center justify-content-center">
                    <svg width="20" height="20" viewBox="0 0 24 24" class="me-2">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Masuk dengan Google
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>