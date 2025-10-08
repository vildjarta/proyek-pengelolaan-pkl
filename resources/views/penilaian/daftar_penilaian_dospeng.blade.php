<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Penilaian Dosen Penguji - PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Font Awesome & CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style-pkl.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

{{-- HEADER --}}
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
                <a href="/profile"><i class="fa fa-user-circle"></i> Profil Saya</a>
                <a href="#"><i class="fa fa-cog"></i> Pengaturan</a>
                <a href="#"><i class="fa fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>
</div>

{{-- SIDEBAR --}}
<div class="sidebar">
    <div class="menu-list">
        <h4>General</h4>
        <ul>
            <li><a href="/home"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
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
            <li class="active"><a href="#"><i class="fa fa-clipboard"></i> <span>Daftar Penilaian</span></a></li>
            <li><a href="#"><i class="fa fa-building"></i> <span>Statistik Perusahaan</span></a></li>
            <li><a href="#"><i class="fa fa-user-tie"></i> <span>Profil Dosen</span></a></li>
        </ul>

        <h4>Admin / Koordinator</h4>
        <ul>
            <li><a href="#"><i class="fa fa-database"></i> <span>Data Perusahaan</span></a></li>
            <li><a href="#"><i class="fa fa-check-circle"></i> <span>Validasi Mahasiswa</span></a></li>
        </ul>
    </div>
</div>

{{-- MAIN CONTENT --}}
<div class="main-content" style="margin-left:220px; padding:20px; margin-top:70px;">
    <div class="card shadow border-0 rounded-3">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold">
                <i class="fa fa-clipboard me-2"></i> Daftar Penilaian Dosen Penguji
            </h4>
            <a href="{{ route('penilaian.create') }}" class="btn btn-light btn-sm text-primary fw-bold">
                <i class="fa fa-plus me-1"></i> Tambah Penilaian
            </a>
        </div>

        <div class="card-body p-3">
            @if(session('success'))
                <div class="alert alert-success fw-bold">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>NIP</th>
                        <th>Nama Dosen</th>
                        <th>Nama Mahasiswa</th>
                        <th>Judul</th>
                        <th>Nilai</th>
                        <th>Jenis Ujian</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($penilaian as $p)
                    <tr>
                        <td>{{ $p->nip }}</td>
                        <td>{{ $p->nama_dosen }}</td>
                        <td>{{ $p->nama_mahasiswa }}</td>
                        <td>{{ $p->judul }}</td>
                        <td>{{ $p->nilai }}</td>
                        <td>{{ $p->jenis_ujian }}</td>
                        <td>{{ $p->tanggal_ujian }}</td>
                        <td>
                            <a href="{{ route('penilaian.edit', $p->id) }}" class="btn btn-warning btn-sm">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('penilaian.destroy', $p->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin hapus data ini?')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center fw-bold">Belum ada data penilaian</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- SCRIPT --}}
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
