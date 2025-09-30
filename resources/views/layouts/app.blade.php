<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Font Awesome & Bootstrap --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f8f9fa;
        }

        /* HEADER */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background: #0d6efd; /* biru cerah */
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            z-index: 1000;
        }
        .header .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: bold;
            font-size: 18px;
        }
        .header .logo img {
            height: 35px;
        }
        .header a {
            color: #fff;
            text-decoration: none;
            margin-left: 15px;
            transition: 0.3s;
        }
        .header a:hover {
            color: #ffc107;
        }

        /* USER MENU */
        .user-profile-wrapper {
            position: relative;
            cursor: pointer;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .user-info .avatar img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
        }
        .profile-dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 45px;
            background: #fff;
            color: #000;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            min-width: 160px;
            overflow: hidden;
            z-index: 2000;
        }
        .profile-dropdown-menu a {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            color: #000;
            transition: 0.3s;
        }
        .profile-dropdown-menu a:hover {
            background: #f1f1f1;
        }
        .user-profile-wrapper:hover .profile-dropdown-menu {
            display: block;
        }

        /* SIDEBAR */
        .main-container {
            display: flex;
            margin-top: 60px; /* biar tidak ketutup header */
        }
        .sidebar {
            width: 220px;
            background: #f1f5ff; /* biru muda */
            color: #0d6efd; /* teks biru */
            min-height: 100vh;
            padding: 20px 15px;
            position: fixed;
            top: 60px;
            left: 0;
            overflow-y: auto;
            border-right: 1px solid #d0d7e1;
        }
        .sidebar h4 {
            font-size: 14px;
            margin-top: 20px;
            margin-bottom: 10px;
            color: #0d6efd;
            text-transform: uppercase;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin: 8px 0;
        }
        .sidebar ul li a {
            color: #0d6efd;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            padding: 8px 12px;
            border-radius: 6px;
            transition: 0.3s;
        }
        .sidebar ul li a:hover {
            background: #dbeafe; /* biru hover */
        }

        /* MAIN CONTENT */
        .main-content-wrapper {
            flex: 1;
            margin-left: 220px; /* kasih jarak sesuai lebar sidebar */
            padding: 20px;
        }

        .content {
            margin-top: 10px;
        }
    </style>
</head>
<body>

{{-- HEADER --}}
<div class="header">
    <div class="header-left">
        <div class="logo">
            <img src="https://i.ibb.co/yYtHbDP/logo.png" alt="Logo PKL JOZZ">
            <span>PKL JOZZ</span>
        </div>
    </div>
    
    <div class="menu-right">
        <a href="#">Ajukan Proposal</a>
        <a href="#">Akademik</a>
        
        <div class="user-profile-wrapper">
            <div class="user-info">
                <span>Nama User</span>
                <div class="avatar">
                    <img src="https://i.ibb.co/L8f3XnS/user-avatar.png" alt="User Avatar">
                </div>
            </div>
            <div class="profile-dropdown-menu">
                <a href="/profile"><i class="fa fa-user-circle"></i> Profil Saya</a>
                <a href="#"><i class="fa fa-cog"></i> Pengaturan</a>
                <a href="#"><i class="fa fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>
</div>

{{-- CONTAINER --}}
<div class="main-container">
    {{-- SIDEBAR --}}
    <div class="sidebar">
        <div class="menu-list">
            <h4>General</h4>
            <ul>
                <li><a href="/home"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
            </ul>

            <h4>Mahasiswa</h4>
            <ul>
                <li><a href="/mahasiswa"><i class="fa fa-users"></i> <span>Kelola Mahasiswa</span></a></li>
                <li><a href="/jadwal"><i class="fa fa-calendar"></i> <span>Jadwal Bimbingan</span></a></li>
            </ul>

            <h4>Admin / Koordinator</h4>
            <ul>
                <li><a href="#"><i class="fa fa-database"></i> <span>Data Perusahaan</span></a></li>
                <li><a href="#"><i class="fa fa-check-circle"></i> <span>Validasi Mahasiswa</span></a></li>
            </ul>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="main-content-wrapper">
        <div class="content">
            @yield('content')
        </div>
    </div>
</div>

{{-- JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
