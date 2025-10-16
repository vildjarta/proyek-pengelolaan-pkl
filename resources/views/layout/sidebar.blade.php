<!-- Sidebar Layout -->
<div class="sidebar">
    <div class="menu-list">
        @php
            // Dummy role untuk sementara (nanti akan diganti dengan Auth::user()->role)
            $userRole = 'admin'; // Bisa diganti: 'mahasiswa', 'dosen', 'perusahaan', 'admin'
            $currentRoute = request()->path();
        @endphp

        {{-- Dashboard - Semua Role--}}
        <h4>General</h4>
        <ul>
            <li class="{{ $currentRoute == 'home' ? 'active' : '' }}">
                <a href="{{ url('/home') }}">
                    <i class="fa fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        </ul>

        {{-- Menu Mahasiswa--}}
        @if(in_array($userRole, ['mahasiswa', 'admin']))
        <h4>Mahasiswa</h4>
        <ul>
            <li class="{{ $currentRoute == 'transkrip' ? 'active' : '' }}">
                <a href="{{ url('/transkrip') }}">
                    <i class="fa fa-file-alt"></i>
                    <span>Transkrip Kelayakan PKL</span>
                </a>
            </li>
            <li class="{{ $currentRoute == 'mahasiswa' ? 'active' : '' }}">
                <a href="{{ url('/mahasiswa') }}">
                    <i class="fa fa-user-graduate"></i>
                    <span>Data Mahasiswa</span>
                </a>
            </li>
        </ul>
        @endif

        {{-- Menu Dosen Pembimbing--}}
        @if(in_array($userRole, ['dosen', 'admin']))
        <h4>Dosen Pembimbing</h4>
        <ul>
            <li class="{{ $currentRoute == 'datadosenpembimbing' ? 'active' : '' }}">
                <a href="{{ url('/datadosenpembimbing') }}">
                    <i class="fa fa-chalkboard-teacher"></i>
                    <span>Data Dosen Pembimbing</span>
                </a>
            </li>
            <li class="{{ $currentRoute == 'jadwal' ? 'active' : '' }}">
                <a href="{{ url('/jadwal') }}">
                    <i class="fa fa-calendar-alt"></i>
                    <span>Jadwal Bimbingan</span>
                </a>
            </li>
            <li class="{{ $currentRoute == 'dosen_penguji' ? 'active' : '' }}">
                <a href="{{ url('/dosen_penguji') }}">
                    <i class="fa fa-user-tie"></i>
                    <span>Dosen Penguji</span>
                </a>
            </li>
        </ul>
        @endif

        {{-- Menu Penilaian--}}
        @if(in_array($userRole, ['dosen', 'perusahaan', 'admin']))
        <h4>Penilaian</h4>
        <ul>
            <li class="{{ $currentRoute == 'penilaian' ? 'active' : '' }}">
                <a href="{{ url('/penilaian') }}">
                    <i class="fa fa-clipboard-check"></i>
                    <span>Nilai Mahasiswa PKL</span>
                </a>
            </li>
        </ul>
        @endif

        {{-- Menu Perusahaan--}}
        @if(in_array($userRole, ['perusahaan', 'admin']))
        <h4>Perusahaan</h4>
        <ul>
            <li class="{{ $currentRoute == 'perusahaan' ? 'active' : '' }}">
                <a href="{{ url('/perusahaan') }}">
                    <i class="fa fa-building"></i>
                    <span>Data Perusahaan</span>
                </a>
            </li>
            <li class="{{ $currentRoute == 'ratingperusahaan' ? 'active' : '' }}">
                <a href="{{ url('/ratingperusahaan') }}">
                    <i class="fa fa-star"></i>
                    <span>Rating Perusahaan</span>
                </a>
            </li>
        </ul>
        @endif

        {{-- Menu Admin--}}
        @if($userRole == 'admin')
        <h4>Administrasi</h4>
        <ul>
            <li>
                <a href="#">
                    <i class="fa fa-users-cog"></i>
                    <span>Manajemen User</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-file-export"></i>
                    <span>Laporan & Export</span>
                </a>
            </li>
        </ul>
        @endif

        {{-- Menu Bantuan - Semua Role--}}
        <h4>Bantuan</h4>
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
                    <span>Hubungi Admin</span>
                </a>
            </li>
        </ul>

        {{-- Logout - Semua Role--}}
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
