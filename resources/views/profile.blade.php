<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Profil Pengguna</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/style-pkl.css">
</head>
<body>
  
<div class="header">
  <div class="header-left">
    <div class="logo">
      <img src="assets/images/logo-baru.png" alt="Logo PKL JOZZ">
      <span>PKL JOZZ</span>
    </div>
    <i class="fa fa-bars menu-toggle"></i>
  </div>
  <div class="menu-right">
    <a href="#">Ajukan Proposal</a>
    <a href="#">Akademik</a>
    <a href="/profile" class="profile-link">
      <div class="user-info">
        <span>Nama User</span>
        <div class="avatar"></div>
      </div>
    </a>
  </div>
</div>

<div class="sidebar">
  <div class="menu-list">
    <h4>General</h4>
    <ul>
      <li><a href="/"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
    </ul>

    <h4>Mahasiswa</h4>
    <ul>
      <li><a href="#"><i class="fa fa-file-alt"></i> <span>Ajukan Proposal</span></a></li>
      <li><a href="#"><i class="fa fa-tasks"></i> <span>Status Proposal</span></a></li>
      <li><a href="#"><i class="fa fa-calendar"></i> <span>Jadwal Bimbingan</span></a></li>
      <li><a href="#"><i class="fa fa-chart-bar"></i> <span>Statistik Perusahaan</span></a></li>
      <li class="active"><a href="/profile"><i class="fa fa-user"></i> <span>Profil Mahasiswa</span></a></li>
    </ul>

    <h4>Dosen Pembimbing</h4>
    <ul>
      <li><a href="#"><i class="fa fa-users"></i> <span>Daftar Mahasiswa Bimbingan</span></a></li>
      <li><a href="#"><i class="fa fa-calendar-check"></i> <span>Jadwal Bimbingan</span></a></li>
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
    <h2><i class="fa fa-user"></i> Profil Pengguna</h2>
    <p>Perbarui informasi akun Anda di sini.</p>
    
    <div class="form-container">
      <form action="/profile/update" method="POST">
        <div class="form-group">
          <label for="nama">Nama Lengkap</label>
          <input type="text" id="nama" name="nama" value="Nama User" required>
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" value="user@example.com" required>
        </div>
        <div class="form-group">
          <label for="telepon">Nomor Telepon</label>
          <input type="tel" id="telepon" name="telepon" value="081234567890">
        </div>
        <div class="form-group">
          <label for="password">Password Baru</label>
          <input type="password" id="password" name="password" placeholder="Masukkan password baru">
        </div>
        <div class="button-group">
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.querySelector('.menu-toggle').addEventListener('click', function() {
    document.body.classList.toggle('sidebar-closed');
  });
</script>

</body>
</html>