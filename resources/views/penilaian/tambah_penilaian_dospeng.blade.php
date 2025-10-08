<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Penilaian Dosen Penguji - PKL JOZZ</title>
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

            <h4>Penilaian</h4>
            <ul>
                <li><a href="#"><i class="fa fa-plus-circle"></i> <span>Tambah Penilaian</span></a></li>
                <li><a href="#"><i class="fa fa-list"></i> <span>Daftar Penilaian</span></a></li>
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
            <div class="card-header bg-success text-white py-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold">
                    <i class="fa fa-plus-circle me-2"></i> Tambah Penilaian Dosen Penguji
                </h4>
                <a href="{{ route('penilaian.index') }}" class="btn btn-light btn-sm text-success fw-bold">
                    <i class="fa fa-list me-1"></i> Daftar Penilaian
                </a>
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

                {{-- Form tambah penilaian --}}
                <form action="{{ route('penilaian.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">NIP</label>
                            <input type="text" name="nip" value="{{ old('nip') }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Dosen</label>
                            <input type="text" name="nama_dosen" value="{{ old('nama_dosen') }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Mahasiswa</label>
                            <input type="text" name="nama_mahasiswa" value="{{ old('nama_mahasiswa') }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tanggal Ujian</label>
                            <input type="date" name="tanggal_ujian" value="{{ old('tanggal_ujian') }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Jenis Ujian</label>
                            <select name="jenis_ujian" class="form-select" required>
                                <option value="">-- Pilih Jenis Ujian --</option>
                                <option value="Seminar Proposal">Seminar Proposal</option>
                                <option value="Seminar Hasil">Seminar Hasil</option>
                                <option value="Sidang Akhir">Sidang Akhir</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nilai</label>
                            <input type="number" step="0.01" min="0" max="100" name="nilai" value="{{ old('nilai') }}" class="form-control" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Judul</label>
                            <textarea name="judul" class="form-control" rows="2" required>{{ old('judul') }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Sikap</label>
                            <textarea name="sikap" class="form-control" rows="2">{{ old('sikap') }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Penguasaan</label>
                            <textarea name="penguasaan" class="form-control" rows="2">{{ old('penguasaan') }}</textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Komentar</label>
                            <textarea name="komentar" class="form-control" rows="3">{{ old('komentar') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('penilaian.index') }}" class="btn btn-secondary rounded-pill px-4">
                            <i class="fa fa-arrow-left me-2"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-success rounded-pill px-4">
                            <i class="fa fa-save me-2"></i> Simpan Penilaian
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
