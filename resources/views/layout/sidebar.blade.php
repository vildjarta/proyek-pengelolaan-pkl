<!-- Sidebar Layout -->
<div class="sidebar">
    <div class="menu-list">
        @php
            // Dummy role untuk sementara (nanti akan diganti dengan auth()->user()->role)
            $userRole = 'admin'; // Options: admin, mahasiswa, dosen, perusahaan, koordinator
            $currentRoute = request()->path();
        @endphp

        {{-- Dashboard - Semua Role --}}
        <h4>General</h4>
        <ul>
            <li class="{{ $currentRoute == 'home' ? 'active' : '' }}">
                <a href="{{ url('/home') }}">
                    <i class="fa fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        </ul>

        {{-- Menu Admin/Koordinator --}}
        @if(in_array($userRole, ['admin', 'koordinator']))
            <h4>Manajemen Data</h4>
            <ul>
                <li class="{{ str_contains($currentRoute, 'mahasiswa') ? 'active' : '' }}">
                    <a href="{{ url('/mahasiswa') }}">
                        <i class="fa fa-user-graduate"></i>
                        <span>Data Mahasiswa</span>
                    </a>
                </li>
                <li class="{{ str_contains($currentRoute, 'transkrip') ? 'active' : '' }}">
                    <a href="{{ url('/transkrip') }}">
                        <i class="fa fa-file-alt"></i>
                        <span>Transkrip Kelayakan</span>
                    </a>
                </li>
                <li class="{{ str_contains($currentRoute, 'datadosenpembimbing') ? 'active' : '' }}">
                    <a href="{{ url('/datadosenpembimbing') }}">
                        <i class="fa fa-chalkboard-teacher"></i>
                        <span>Dosen Pembimbing</span>
                    </a>
                </li>
                <li class="{{ str_contains($currentRoute, 'dosen_penguji') ? 'active' : '' }}">
                    <a href="{{ url('/dosen_penguji') }}">
                        <i class="fa fa-user-tie"></i>
                        <span>Dosen Penguji</span>
                    </a>
                </li>
                <li class="{{ str_contains($currentRoute, 'perusahaan') ? 'active' : '' }}">
                    <a href="{{ url('/perusahaan') }}">
                        <i class="fa fa-building"></i>
                        <span>Data Perusahaan</span>
                    </a>
                </li>
            </ul>

            <h4>Penjadwalan</h4>
            <ul>
                <li class="{{ str_contains($currentRoute, 'jadwal') ? 'active' : '' }}">
                    <a href="{{ url('/jadwal') }}">
                        <i class="fa fa-calendar-alt"></i>
                        <span>Jadwal Bimbingan</span>
                    </a>
                </li>
            </ul>

            <h4>Penilaian</h4>
            <ul>
                <li class="{{ str_contains($currentRoute, 'penilaian') ? 'active' : '' }}">
                    <a href="{{ url('/penilaian') }}">
                        <i class="fa fa-clipboard-check"></i>
                        <span>Nilai Mahasiswa</span>
                    </a>
                </li>
                <li class="{{ str_contains($currentRoute, 'ratingperusahaan') ? 'active' : '' }}">
                    <a href="{{ url('/ratingperusahaan') }}">
                        <i class="fa fa-star"></i>
                        <span>Rating Perusahaan</span>
                    </a>
                </li>
            </ul>
        @endif

        {{-- Menu Mahasiswa --}}
        @if($userRole == 'mahasiswa')
            <h4>Mahasiswa</h4>
            <ul>
                <li class="{{ str_contains($currentRoute, 'transkrip') ? 'active' : '' }}">
                    <a href="{{ url('/transkrip') }}">
                        <i class="fa fa-file-alt"></i>
                        <span>Cek Kelayakan PKL</span>
                    </a>
                </li>
                <li class="{{ str_contains($currentRoute, 'jadwal') ? 'active' : '' }}">
                    <a href="{{ url('/jadwal') }}">
                        <i class="fa fa-calendar-alt"></i>
                        <span>Jadwal Bimbingan</span>
                    </a>
                </li>
                <li class="{{ str_contains($currentRoute, 'penilaian') ? 'active' : '' }}">
                    <a href="{{ url('/penilaian') }}">
                        <i class="fa fa-clipboard-check"></i>
                        <span>Nilai PKL</span>
                    </a>
                </li>
                <li class="{{ str_contains($currentRoute, 'ratingperusahaan') ? 'active' : '' }}">
                    <a href="{{ url('/ratingperusahaan') }}">
                        <i class="fa fa-star"></i>
                        <span>Rating Perusahaan</span>
                    </a>
                </li>
            </ul>
        @endif

        {{-- Menu Dosen --}}
        @if($userRole == 'dosen')
            <h4>Dosen Pembimbing</h4>
            <ul>
                <li class="{{ str_contains($currentRoute, 'mahasiswa') ? 'active' : '' }}">
                    <a href="{{ url('/mahasiswa') }}">
                        <i class="fa fa-user-graduate"></i>
                        <span>Mahasiswa Bimbingan</span>
                    </a>
                </li>
                <li class="{{ str_contains($currentRoute, 'jadwal') ? 'active' : '' }}">
                    <a href="{{ url('/jadwal') }}">
                        <i class="fa fa-calendar-alt"></i>
                        <span>Jadwal Bimbingan</span>
                    </a>
                </li>
                <li class="{{ str_contains($currentRoute, 'penilaian') ? 'active' : '' }}">
                    <a href="{{ url('/penilaian') }}">
                        <i class="fa fa-clipboard-check"></i>
                        <span>Input Nilai</span>
                    </a>
                </li>
            </ul>
        @endif

        {{-- Menu Perusahaan --}}
        @if($userRole == 'perusahaan')
            <h4>Perusahaan</h4>
            <ul>
                <li class="{{ str_contains($currentRoute, 'mahasiswa') ? 'active' : '' }}">
                    <a href="{{ url('/mahasiswa') }}">
                        <i class="fa fa-user-graduate"></i>
                        <span>Mahasiswa PKL</span>
                    </a>
                </li>
                <li class="{{ str_contains($currentRoute, 'penilaian') ? 'active' : '' }}">
                    <a href="{{ url('/penilaian') }}">
                        <i class="fa fa-clipboard-check"></i>
                        <span>Penilaian Mahasiswa</span>
                    </a>
                </li>
                <li class="{{ str_contains($currentRoute, 'perusahaan') ? 'active' : '' }}">
                    <a href="{{ url('/perusahaan') }}">
                        <i class="fa fa-building"></i>
                        <span>Profil Perusahaan</span>
                    </a>
                </li>
            </ul>
        @endif

        {{-- Logout - Semua Role --}}
        <h4>Akun</h4>
        <ul>
            <li>
                <a href="#" onclick="event.preventDefault(); alert('Logout functionality will be implemented');">
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
