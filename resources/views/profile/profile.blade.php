<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f6fa;
            margin: 0;
            padding: 20px;
        }

        .profile-container {
            max-width: 500px;
            margin: 50px auto;
            background: #fff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            position: relative;
        }

        /* Tombol kembali */
        .btn-back {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 40px;
            height: 40px;
            background: #3498db;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 18px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.15);
            transition: background 0.3s ease;
        }

        .btn-back:hover {
            background: #2980b9;
        }

        /* Foto profil bulat */
        .profile-pic {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            margin: 0 auto 20px;
            border: 4px solid #3498db;
        }

        .profile-container h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .profile-container p {
            text-align: center;
            color: #7f8c8d;
            margin-bottom: 30px;
        }

        .info-group {
            margin-bottom: 20px;
        }

        .info-label {
            font-weight: 600;
            color: #34495e;
            margin-bottom: 6px;
        }

        .info-value {
            background: #f9f9f9;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            font-size: 14px;
            color: #2c3e50;
        }
    </style>
</head>
<body>

<div class="profile-container">
    <!-- Tombol kembali ke home.blade.php -->
    <a href="{{ url('/home') }}" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i>
    </a>

    <!-- Foto Profil -->
    <img src="https://via.placeholder.com/120" alt="Foto Profil" class="profile-pic">

    <h2>Nama User</h2>
    <p><i class="fa-solid fa-circle-user"></i> Akun Mahasiswa</p>

    <!-- Informasi User -->
    <div class="info-group">
        <div class="info-label">Nama Lengkap</div>
        <div class="info-value">Nur Anisa</div>
    </div>

    <div class="info-group">
        <div class="info-label">Email</div>
        <div class="info-value">user@example.com</div>
    </div>

    <div class="info-group">
        <div class="info-label">Nomor Telepon</div>
        <div class="info-value">081234567890</div>
    </div>

    <div class="info-group">
        <div class="info-label">Alamat</div>
        <div class="info-value">Jl. Pendidikan No. 123, Politala</div>
    </div>
</div>

</body>
</html>
