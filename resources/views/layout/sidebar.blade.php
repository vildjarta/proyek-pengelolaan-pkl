<!-- Sidebar Layout -->
<div class="sidebar">
    <div class="menu-list">

        <h4>General</h4>
        <ul>
            <li class="active">
                <a href="#">
                    <i class="fa fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        </ul>

        <h4>Mahasiswa</h4>
        <ul>
            <li>
                <a href="#">
                    <i class="fa fa-file-alt"></i>
                    <span>Ajukan Proposal</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-tasks"></i>
                    <span>Status Proposal</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-calendar"></i>
                    <span>Jadwal Bimbingan</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-chart-bar"></i>
                    <span>Statistik Perusahaan</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-user"></i>
                    <span>Profil Mahasiswa</span>
                </a>
            </li>
        </ul>

        <h4>Dosen Pembimbing</h4>
        <ul>
            <li>
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span>Daftar Mahasiswa Bimbingan</span>
                </a>
            </li>
        <li>
            <a href="#">
                <i class="fa fa-calendar"></i>
                <span>Jadwal Bimbingan</span>
            </a>
        </li>
            <li>
                <a href="#">
                    <i class="fa fa-edit"></i>
                    <span>Input Nilai</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-building"></i>
                    <span>Statistik Perusahaan</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-user-tie"></i>
                    <span>Profil Dosen</span>
                </a>
            </li>
        </ul>

        <h4>Perusahaan</h4>
        <ul>
            <li>
                <a href="#">
                    <i class="fa fa-id-badge"></i>
                    <span>Daftar Mahasiswa PKL</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-chart-line"></i>
                    <span>Statistik Perusahaan</span>
                </a>
            </li>
            <li>
                <a href="/perusahaan">
                    <i class="fa fa-building"></i>
                    <span>Profil Perusahaan</span>
                </a>
            </li>
        </ul>

        <h4>Rating & Review</h4>
        <ul>
            <li>
                <a href="#">
                    <i class="fa fa-star"></i>
                    <span>Beri Rating</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-ranking-star"></i>
                    <span>Ranking Perusahaan</span>
                </a>
            </li>
        </ul>

        <h4>Admin / Koordinator</h4>
        <ul>
            <li>
                <a href="#">
                    <i class="fa fa-users-cog"></i>
                    <span>Manajemen User</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-database"></i>
                    <span>Data Perusahaan</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-check-circle"></i>
                    <span>Validasi Mahasiswa</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-clock"></i>
                    <span>Penjadwalan Otomatis</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-envelope-open-text"></i>
                    <span>Surat Pengantar</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-download"></i>
                    <span>Backup & Laporan</span>
                </a>
            </li>
        </ul>

        <h4>Panduan & Kontak</h4>
        <ul>
            <li>
                <a href="#">
                    <i class="fa fa-book"></i>
                    <span>Panduan Sistem</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-headset"></i>
                    <span>Kontak / Helpdesk</span>
                </a>
            </li>
        </ul>

        <h4>Akun</h4>
        <ul>
            <li>
                <a href="#">
                    <i class="fa fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>

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
