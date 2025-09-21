<!DOCTYPE html>
<html>

<head>
    <!-- Basic -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Site Metas -->
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="/assets/images/favicon.png" type="">
    <title> Feane </title>

    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css" />

    <!--owl slider stylesheet -->
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <!-- nice select  -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css"
        integrity="sha512-CruCP+TD3yXzlvvijET8wV5WxxEh5H8P4cmz0RFbKK6FlZ2sYl3AEsKlLPHbniXKSrDdFewhbmBK5skbdsASbQ=="
        crossorigin="anonymous" />
    <!-- font awesome style -->
    <link href="/assets/css/font-awesome.min.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="/assets/css/style.css" rel="stylesheet" />
    <!-- responsive style -->
    <link href="/assets/css/responsive.css" rel="stylesheet" />

</head>
<body>

  <!-- Navbar -->
  <div class="navbar">
    <div class="logo"></div>
    <div class="menu">
      <a href="#">Beranda</a>
      <a href="#">Panduan</a>
      <a href="#">Kontak</a>
    </div>
    <div class="right-menu">
      <button>Ajukan Proposal</button>
      <button>Akademik</button>
      <div class="profile">
        <img src="https://via.placeholder.com/40" alt="User Profile" onclick="toggleDropdown()">
        <div class="dropdown" id="dropdownMenu">
          <a href="#">Profil</a>
          <a href="#">Pengaturan</a>
          <a href="#">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Layout -->
  <div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
      <a href="#">Mahasiswa</a>
      <a href="#">Dosen Pembimbing</    a>
      <a href="#">Perusahaan PKL</a>
      <a href="#">Rating & Review</a>
      <a href="#">Admin / Koordinator</a>
    </div>

    <!-- Content -->
    <div class="content">
      <h2>Selamat Datang di Sistem Pengelolaan PKL</h2>

      <div style="display: flex; gap: 20px; flex-wrap: wrap;">

        <!-- Kolom kiri: Jadwal -->
        <div style="flex: 1; min-width: 320px;">
          <div class="card">
            <h3>📅 Jadwal Minggu Ini</h3>
            <p>Hari ini: {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
            <table>
              <tr>
                <th>Hari</th>
                <th>Tanggal</th>
                <th>Kegiatan</th>
              </tr>
              <tr>
                <td>Senin</td>
                <td>08/09</td>
                <td>Bimbingan Proposal</td>
              </tr>
              <tr>
                <td>Rabu</td>
                <td>10/09</td>
                <td>Presentasi Kemajuan</td>
              </tr>
              <tr>
                <td>Jumat</td>
                <td>12/09</td>
                <td>Seminar Hasil</td>
              </tr>
            </table>
          </div>
        </div>

        <!-- Kolom kanan: Tempat Favorit -->
        <div style="flex: 1; min-width: 320px;">
          <div class="card">
            <h3>⭐ Tempat PKL Terfavorit</h3>

            <div class="favorite-card">
              <h4>PT Teknologi Nusantara</h4>
              <p>⭐ 4.9 | Bidang: IT & Software</p>
              <p><b>Alamat:</b> Jakarta</p>
            </div>

            <div class="favorite-card">
              <h4>CV Kreatif Digital</h4>
              <p>⭐ 4.8 | Bidang: Desain & Multimedia</p>
              <p><b>Alamat:</b> Bandung</p>
            </div>

            <div class="favorite-card">
              <h4>Bank Syariah Sejahtera</h4>
              <p>⭐ 4.7 | Bidang: Perbankan</p>
              <p><b>Alamat:</b> Surabaya</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script>
    function toggleDropdown() {
      const dropdown = document.getElementById("dropdownMenu");
      dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    }

    // Klik di luar dropdown → tutup otomatis
    window.onclick = function(event) {
      if (!event.target.matches('.profile img')) {
        const dropdown = document.getElementById("dropdownMenu");
        if (dropdown.style.display === "block") {
          dropdown.style.display = "none";
        }
      }
    }
  </script>

</body>
</html>