<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="/assets/images/favicon.png" type="">
    <title>Sistem Pengelolaan PKL</title>

 <link rel="stylesheet" href="/assets/css/style-pkl.css">
    
</head>
<body>

    <div class="sidebar">
        <div class="header">
            <img src="/assets/images/logo-sekolah.png" alt="Logo">
            <h1>Sistem Pengelolaan PKL</h1>
        </div>
        <div class="sidebar-nav">
            <p class="nav-title">Menu Utama</p>
            <ul>
                <li><a href="#" class="active">Beranda</a></li>
                <li><a href="#">Profil Sekolah</a></li>
                <li><a href="#">Berita & Kegiatan</a></li>
                <li><a href="#">Hubungi Kami</a></li>
            </ul>
            <p class="nav-title" style="margin-top: 20px;">Area Pengguna</p>
            <ul>
                <li><a href="#">Login</a></li>
                <li><a href="#">Daftar Akun</a></li>
            </ul>
        </div>
    </div>

    <div class="main-content-wrapper">
        <div class="main-content">
            <h2>Selamat Datang di Sistem Pengelolaan PKL</h2>

            <div class="card-container">
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

</body>
</html>