<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Sistem PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style-pkl.css">
    <link rel="stylesheet" href="assets/css/style-pkl-jadwal.css">
</head>

<body>
<<<<<<< HEAD

    <div class="d-flex">
        {{-- header --}}
        @include('layout.header')
=======
  
<div class="header">
<<<<<<< HEAD
  <div class="header-left">
    <div class="logo">
      <img src="assets/images/logo-baru.png" alt="Logo PKL JOZZ">
      <span>PKL JOZZ</span>
>>>>>>> 52c806c (push)
    </div>

    <div class="d-flex">
        {{-- sidebar --}}
        @include('layout.sidebar')
    </div>

    <div class="main-content-wrapper">
        <div class="content">
            <h2>Selamat Datang di Sistem PKL JOZZ</h2>
            <p>Silakan pilih menu dari sidebar atau gunakan menu atas untuk navigasi cepat.</p>
=======
    <div class="header-left">
        <div class="logo">
            <img src="asset/images/logo-baru.png" alt="Logo PKL JOZZ">
            <span>PKL JOZZ</span>
>>>>>>> f13a77b (menambahkan crud jadwal untuk bimbingan dll)
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.querySelector('.menu-toggle');
            const body = document.body;
            const profileWrapper = document.querySelector('.user-profile-wrapper');
            const userinfo = document.querySelector('.user-info');

<<<<<<< HEAD
            if (toggleButton) {
                toggleButton.addEventListener('click', function() {
                    body.classList.toggle('sidebar-closed');
                });
            }

            if (userinfo) {
                userinfo.addEventListener('click', function(e) {
                    e.preventDefault();
                    profileWrapper.classList.toggle('active');
                });

                document.addEventListener('click', function(e) {
                    if (!profileWrapper.contains(e.target) && profileWrapper.classList.contains('active')) {
                        profileWrapper.classList.remove('active');
                    }
                });
            }
        });
    </script>
=======
        <h4>Mahasiswa</h4>
        <ul>
            <li><a href="#"><i class="fa fa-file-alt"></i> <span>Ajukan Proposal</span></a></li>
            <li><a href="#"><i class="fa fa-tasks"></i> <span>Status Proposal</span></a></li>
            <li><a href="{{ route('jadwal.index') }}"><i class="fa fa-calendar"></i> <span>Jadwal Bimbingan</span></a></li>
            <li><a href="#"><i class="fa fa-chart-bar"></i> <span>Statistik Perusahaan</span></a></li>
            <li><a href="#"><i class="fa fa-user"></i> <span>Profil Mahasiswa</span></a></li>
        </ul>

        <h4>Dosen Pembimbing</h4>
        <ul>
            <li><a href="#"><i class="fa fa-users"></i> <span>Daftar Mahasiswa Bimbingan</span></a></li>
            <li><a href="{{ route('jadwal.index') }}"><i class="fa fa-calendar"></i> <span>Jadwal Bimbingan</span></a></li>
            <li><a href="#"><i class="fa fa-edit"></i> <span>Input Nilai</span></a></li>
            <li><a href="#"><i class="fa fa-building"></i> <span>Statistik Perusahaan</span></a></li>
            <li><a href="#"><i class="fa fa-user-tie"></i> <span>Profil Dosen</span></a></li>
        </ul>

        <h4>Perusahaan</h4>
        <ul>
            <li><a href="#"><i class="fa fa-id-badge"></i> <span>Daftar Mahasiswa PKL</span></a></li>
            <li><a href="#"><i class="fa fa-chart-line"></i> <span>Statistik Perusahaan</span></a></li>
            <li><a href="#"><i class="fa fa-building"></i> <span>Profil Perusahaan</span></a></li>
        </ul>

        <h4>Rating & Review</h4>
        <ul>
            <li><a href="#"><i class="fa fa-star"></i> <span>Beri Rating</span></a></li>
            <li><a href="#"><i class="fa fa-ranking-star"></i> <span>Ranking Perusahaan</span></a></li>
        </ul>

        <h4>Admin / Koordinator</h4>
        <ul>
            <li><a href="#"><i class="fa fa-users-cog"></i> <span>Manajemen User</span></a></li>
            <li><a href="#"><i class="fa fa-database"></i> <span>Data Perusahaan</span></a></li>
            <li><a href="#"><i class="fa fa-check-circle"></i> <span>Validasi Mahasiswa</span></a></li>
            <li><a href="#"><i class="fa fa-clock"></i> <span>Penjadwalan Otomatis</span></a></li>
            <li><a href="#"><i class="fa fa-envelope-open-text"></i> <span>Surat Pengantar</span></a></li>
            <li><a href="#"><i class="fa fa-download"></i> <span>Backup & Laporan</span></a></li>
        </ul>

        <h4>Panduan & Kontak</h4>
        <ul>
            <li><a href="#"><i class="fa fa-book"></i> <span>Panduan Sistem</span></a></li>
            <li><a href="#"><i class="fa fa-headset"></i> <span>Kontak / Helpdesk</span></a></li>
        </ul>

        <h4>Akun</h4>
        <ul>
            <li><a href="#"><i class="fa fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>
</div>

<div class="main-content-wrapper">
    <div class="content">
        @yield('content')
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.querySelector('.menu-toggle');
        const body = document.body;
        const profileWrapper = document.querySelector('.user-profile-wrapper');
        const userinfo = document.querySelector('.user-info');
        
        if (toggleButton) {
            toggleButton.addEventListener('click', function() {
                body.classList.toggle('sidebar-closed');
            });
        }
        
        if (userinfo) {
            userinfo.addEventListener('click', function(e) {
                e.preventDefault(); 
                profileWrapper.classList.toggle('active');
            });
            
            document.addEventListener('click', function(e) {
                if (!profileWrapper.contains(e.target) && profileWrapper.classList.contains('active')) {
                    profileWrapper.classList.remove('active');
                }
            });
        }
    });
</script>
>>>>>>> f13a77b (menambahkan crud jadwal untuk bimbingan dll)

</body>

</html>
