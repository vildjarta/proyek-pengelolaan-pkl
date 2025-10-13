<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Data Mahasiswa - PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Icon & CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #3b82f6;
            color: #fff;
            padding: 10px 20px;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }
        .header-left {
            display: flex;
            align-items: center;
        }
        .logo {
            display: flex;
            align-items: center;
        }
        .logo img {
            height: 35px;
            margin-right: 10px;
        }
        .menu-toggle {
            margin-left: 20px;
            font-size: 20px;
            cursor: pointer;
        }
        .menu-right a {
            margin: 0 10px;
            color: #fff;
            text-decoration: none;
            font-weight: 500;
        }
        .menu-right a:hover {
            text-decoration: underline;
        }

        /* User profile */
        .user-profile-wrapper {
            position: relative;
            display: inline-block;
        }
        .user-info {
            cursor: pointer;
            display: flex;
            align-items: center;
        }
        .user-info span {
            margin-right: 10px;
        }
        .avatar img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
        }
        .profile-dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: #fff;
            color: #000;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            width: 180px;
            z-index: 2000;
        }
        .profile-dropdown-menu a {
            display: block;
            padding: 10px;
            color: #333;
            text-decoration: none;
        }
        .profile-dropdown-menu a:hover {
            background: #f0f0f0;
        }
        .user-profile-wrapper.active .profile-dropdown-menu {
            display: block;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 60px;
            left: 0;
            width: 250px;
            height: calc(100% - 60px);
            background: #1e40af;
            color: #fff;
            overflow-y: auto;
            transition: all 0.3s;
            padding: 20px 0;
        }
        .sidebar h4 {
            padding: 10px 20px;
            font-size: 14px;
            text-transform: uppercase;
            color: #a5b4fc;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0 0 20px 0;
        }
        .sidebar ul li {
            margin: 5px 0;
        }
        .sidebar ul li a {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: #fff;
            text-decoration: none;
            transition: background 0.2s;
        }
        .sidebar ul li a:hover {
            background: rgba(255,255,255,0.1);
        }
        .sidebar ul li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .sidebar-closed .sidebar {
            width: 70px;
        }
        .sidebar-closed .sidebar h4,
        .sidebar-closed .sidebar ul li a span {
            display: none;
        }

        /* Content */
        .content {
            margin-left: 250px;
            padding: 90px 20px 20px 20px;
            transition: margin-left 0.3s;
        }
        .sidebar-closed .content {
            margin-left: 70px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <div class="header-left">
            <div class="logo">
                <img src="https://i.ibb.co/yYtHbDP/logo.png" alt="Logo PKL JOZZ">
                <span>PKL JOZZ</span>
            </div>
            <i class="fa fa-bars menu-toggle"></i>
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
                    <a href="#"><i class="fa fa-user-circle"></i> Profil Saya</a>
                    <a href="#"><i class="fa fa-cog"></i> Pengaturan</a>
                    <a href="#"><i class="fa fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="menu-list">
            <h4>General</h4>
            <ul>
                <li><a href="#"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
            </ul>

            <h4>Mahasiswa</h4>
            <ul>
                <li><a href="#"><i class="fa fa-file-alt"></i> <span>Tambahkan Transkrip</span></a></li>
                <li><a href="#"><i class="fa fa-tasks"></i> <span>Status PKL</span></a></li>
                <li><a href="#"><i class="fa fa-calendar"></i> <span>Jadwal Bimbingan</span></a></li>
                <li><a href="#"><i class="fa fa-chart-bar"></i> <span>Statistik Perusahaan</span></a></li>
                <li><a href="#"><i class="fa fa-user"></i> <span>Profil Mahasiswa</span></a></li>
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

    <!-- Content -->
    <div class="content">
        <div class="card shadow border-0 rounded-3">
            <div class="card-header bg-primary text-white py-3">
                <h4 class="mb-0 fw-bold">
                    <i class="fa fa-plus-circle me-2"></i> Tambah Data Mahasiswa
                </h4>
            </div>

            <div class="card-body p-4">
                {{-- Pesan error --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fa fa-exclamation-circle me-2"></i> Terjadi kesalahan!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Form tambah --}}
                <form action="{{ route('mahasiswa.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nim" class="form-label fw-bold">NIM</label>
                            <input type="number" name="nim" id="nim"
                                value="{{ old('nim') }}"
                                class="form-control"
                                required min="1000000000" max="999999999999"
                                title="NIM harus terdiri dari 10 digit angka.">
                        </div>

                        <div class="col-md-6">
                            <label for="nama" class="form-label fw-bold">Nama</label>
                            <input type="text" name="nama" id="nama"
                                value="{{ old('nama') }}"
                                class="form-control"
                                required pattern="[A-Za-z\s]+"
                                title="Nama hanya boleh berisi huruf dan spasi.">
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input type="email" name="email" id="email"
                                value="{{ old('email') }}"
                                class="form-control"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label for="no_hp" class="form-label fw-bold">No HP</label>
                            <input type="number" name="no_hp" id="no_hp"
                                value="{{ old('no_hp') }}"
                                class="form-control"
                                required min="1000000000" max="999999999999999">
                        </div>

                        <div class="col-md-4">
                            <label for="prodi" class="form-label fw-bold">Prodi</label>
                            <select name="prodi" id="prodi" class="form-select" required>
                                <option value="">-- Pilih Prodi --</option>
                                <option value="Akuntansi">Akuntansi</option>
                                <option value="Agroindustri">Agroindustri</option>
                                <option value="Teknologi Informasi">Teknologi Informasi</option>
                                <option value="Teknologi Otomotif">Teknologi Otomotif</option>
                                <option value="Akuntansi Perpajakan (D4)">Akuntansi Perpajakan (D4)</option>
                                <option value="Teknologi Pakan Ternak (D4)">Teknologi Pakan Ternak (D4)</option>
                                <option value="Teknologi Rekayasa Komputer Jaringan (D4)">Teknologi Rekayasa Komputer Jaringan (D4)</option>
                                <option value="Teknologi Rekayasa Konstruksi Jalan dan Jembatan (D4)">Teknologi Rekayasa Konstruksi Jalan dan Jembatan (D4)</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="angkatan" class="form-label fw-bold">Angkatan</label>
                            <input type="number" name="angkatan" id="angkatan"
                                value="{{ old('angkatan') }}"
                                class="form-control"
                                required min="2009" max="{{ date('Y') + 1 }}">
                        </div>

                        <div class="col-md-4">
                            <label for="ipk" class="form-label fw-bold">IPK</label>
                            <input type="number" step="0.01" min="0" max="4"
                                name="ipk" id="ipk"
                                value="{{ old('ipk') }}"
                                class="form-control"
                                required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary rounded-pill px-4">
                            <i class="fa fa-arrow-left me-2"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="fa fa-save me-2"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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

</body>
</html>
