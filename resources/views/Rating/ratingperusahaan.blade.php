<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ranking Perusahaan - Sistem PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style-pkl.css') }}">
    <style>
        /* style sama seperti sebelumnya */
        .page-title {color: var(--color-text-dark);font-weight: 600;margin-bottom: 20px;}
        .ranking-container {background: #fff;padding: 30px;border-radius: 10px;box-shadow: 0 4px 15px var(--color-shadow);overflow-x: auto;}
        table {width: 100%;border-collapse: collapse;margin-top: 20px;}
        th, td {text-align: left;padding: 15px;border-bottom: 1px solid #e0e6ef;}
        th {background: #f8f9fa;color: #6c757d;font-weight: 600;text-transform: uppercase;font-size: 0.9em;}
        tr:hover {background: #f0f4f8;}
        .stars {color: #ffc107;}
        .stars .fa-star:not(.filled) {color: #e0e0e0;}
        .action-btn {background: #3b5998;color: #fff;border: none;padding: 10px 15px;border-radius: 5px;cursor: pointer;transition: 0.3s;font-size: 0.9em;min-width: 150px;}
        .action-btn:hover {background: #2e4a86;}
        .btn-view {background: #28a745;}
        .btn-view:hover {background: #1f8b36;}
        .search-box {margin-bottom: 15px;}
        .search-box input {padding: 10px;width: 100%;max-width: 350px;border: 1px solid #ccc;border-radius: 5px;}
        th:first-child, td:first-child {text-align: center;vertical-align: middle;font-weight: bold;width: 100px;}
        th:last-child, td:last-child {text-align: center;vertical-align: middle;}
        td:last-child {display: flex;justify-content: center;gap: 10px;}
    </style>
</head>
<body>
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

    <div class="sidebar">
        <div class="menu-list">
            <h4>General</h4>
            <ul>
                <li class="active"><a href="#"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
            </ul>

            <h4>Mahasiswa</h4>
            <ul>
                <li><a href="#"><i class="fa fa-file-alt"></i> <span>Ajukan Proposal</span></a></li>
                <li><a href="#"><i class="fa fa-tasks"></i> <span>Status Proposal</span></a></li>
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
                <li><a href="/ratingperusahaan"><i class="fa fa-ranking-star"></i> <span>Ranking Perusahaan</span></a></li>
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

    <!-- Main Content -->
    <div class="main-content-wrapper">
        <div class="content">
            <h2 class="page-title">Ranking Perusahaan</h2>

            <!-- 🔎 Input Pencarian -->
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Cari perusahaan...">
            </div>

            <div class="ranking-container">
                <table id="rankingTable">
                    <thead>
                        <tr>
                            <th>Peringkat</th>
                            <th>ID Perusahaan</th>
                            <th>Rata-rata Rating</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $index => $review)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $review->id_perusahaan }}</td>
                                <td>
                                    <span class="stars">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= round($review->avg_rating) ? 'filled' : '' }}"></i>
                                        @endfor
                                    </span>
                                    ({{ number_format($review->avg_rating, 1) }})
                                </td>
                                <td>
                                    <a href="{{ route('tambahratingdanreview') }}">
                                        <button class="action-btn">Rating & Review</button>
                                    </a>
                                    <a href="{{ route('lihatratingdanreview') }}">
                                        <button class="action-btn btn-view">Lihat Rating & Review</button>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center">Belum ada data perusahaan yang dirating</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Script -->
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
            
            document.getElementById('searchInput').addEventListener('keyup', function () {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll("#rankingTable tbody tr");
                rows.forEach(row => {
                    const namaPerusahaan = row.cells[1].textContent.toLowerCase();
                    row.style.display = namaPerusahaan.includes(filter) ? "" : "none";
                });
            });
        });
    </script>
</body>
</html>
