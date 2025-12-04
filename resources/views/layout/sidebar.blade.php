<!-- Sidebar Layout -->
<div class="sidebar">
    <div class="menu-list">

        {{-- Hanya tampilkan menu jika pengguna sudah login --}}
        @auth
            @php
                // Ambil role pengguna yang sedang login
                $userRole = auth()->user()->role;
                // Ambil rute saat ini
                $currentRoute = request()->path();
            @endphp

            {{-- HALAMAN UTAMA - Semua Role --}}
            <h4>Halaman Utama</h4>
            <ul>
                <li class="{{ $currentRoute == 'home' ? 'active' : '' }}">
                    <a href="{{ url('/home') }}">
                        <i class="fa fa-home"></i>
                        <span>Beranda</span>
                    </a>
                </li>
            </ul>

            {{-- Menu Admin/Koordinator (Melihat Semua Fitur) --}}
            @if(in_array($userRole, ['admin', 'koordinator']))
                <h4>Data Akademik</h4>
                <ul>
                    <li class="{{ (request()->is('mahasiswa') || request()->is('mahasiswa/*')) ? 'active' : '' }}">
                        <a href="{{ url('/mahasiswa') }}">
                            <i class="fa fa-user-graduate"></i>
                            <span>Data Mahasiswa</span>
                        </a>
                    </li>
                    <li class="{{ (request()->is('transkrip') || request()->is('transkrip/*')) ? 'active' : '' }}">
                        <a href="{{ url('/transkrip') }}">
                            <i class="fa fa-file-alt"></i>
                            <span>Transkrip Kelayakan</span>
                        </a>
                    </li>
                </ul>

                <h4>Bimbingan & Penguji</h4>
                <ul>
                    <li class="{{ (request()->is('datadosenpembimbing') || request()->is('datadosenpembimbing/*')) ? 'active' : '' }}">
                        <a href="{{ url('/datadosenpembimbing') }}">
                            <i class="fa fa-chalkboard-teacher"></i>
                            <span>Dosen Pembimbing</span>
                        </a>
                    </li>
                    <li class="{{ (request()->is('dosen_penguji') || request()->is('dosen_penguji/*')) ? 'active' : '' }}">
                        <a href="{{ url('/dosen_penguji') }}">
                            <i class="fa fa-user-tie"></i>
                            <span>Dosen Penguji</span>
                        </a>
                    </li>
                </ul>

                <h4>Perusahaan PKL</h4>
                <ul>
                    <li class="{{ (request()->is('perusahaan') || request()->is('perusahaan/*')) ? 'active' : '' }}">
                        <a href="{{ url('/perusahaan') }}">
                            <i class="fa fa-building"></i>
                            <span>Data Perusahaan</span>
                        </a>
                    </li>
                </ul>

                <h4>Jadwal & Kegiatan</h4>
                <ul>
                    <li class="{{ (request()->is('jadwal') || request()->is('jadwal/*')) ? 'active' : '' }}">
                        <a href="{{ url('/jadwal') }}">
                            <i class="fa fa-calendar-alt"></i>
                            <span>Jadwal Bimbingan</span>
                        </a>
                    </li>
                </ul>

                <h4>Penilaian & Hasil</h4>
                <ul>
                    <li class="{{ (request()->is('penilaian') || request()->is('penilaian/*')) ? 'active' : '' }}">
                        <a href="{{ url('/penilaian') }}">
                            <i class="fa fa-clipboard-check"></i>
                            <span>Nilai Pembimbing</span>
                        </a>
                    </li>
                    <li class="{{ (request()->is('penilaian-penguji') || request()->is('penilaian-penguji/*')) ? 'active' : '' }}">
                        <a href="{{ url('/penilaian-penguji') }}">
                            <i class="fa fa-clipboard-check"></i>
                            <span>Nilai Penguji</span>
                        </a>
                    </li>
                    <li class="{{ (request()->is('nilai') || request()->is('nilai/*')) ? 'active' : '' }}">
                        <a href="{{ url('/nilai') }}">
                            <i class="fa fa-clipboard-check"></i>
                            <span>Nilai Mahasiswa</span>
                        </a>
                    </li>
                    <li class="{{ (request()->is('ratingperusahaan') || request()->is('ratingperusahaan/*')) ? 'active' : '' }}">
                        <a href="{{ url('/ratingperusahaan') }}">
                            <i class="fa fa-star"></i>
                            <span>Rating Perusahaan</span>
                        </a>
                    </li>
                </ul>

            {{-- Menu Mahasiswa --}}
            @elseif($userRole == 'mahasiswa')
                <h4>Data Akademik</h4>
                <ul>
                    <li class="{{ (request()->is('transkrip') || request()->is('transkrip/*')) ? 'active' : '' }}">
                        <a href="{{ url('/transkrip') }}">
                            <i class="fa fa-file-alt"></i>
                            <span>Cek Kelayakan PKL</span>
                        </a>
                    </li>
                </ul>

                <h4>Jadwal & Kegiatan</h4>
                <ul>
                    <li class="{{ (request()->is('jadwal') || request()->is('jadwal/*')) ? 'active' : '' }}">
                        <a href="{{ url('/jadwal') }}">
                            <i class="fa fa-calendar-alt"></i>
                            <span>Jadwal Bimbingan</span>
                        </a>
                    </li>
                </ul>

                <h4>Penilaian & Hasil</h4>
                <ul>
                    <li class="{{ (request()->is('nilai') || request()->is('nilai/*')) ? 'active' : '' }}">
                        <a href="{{ url('/nilai') }}">
                            <i class="fa fa-clipboard-check"></i>
                            <span>Nilai PKL</span>
                        </a>
                    </li>
                    <li class="{{ (request()->is('ratingperusahaan') || request()->is('ratingperusahaan/*')) ? 'active' : '' }}">
                        <a href="{{ url('/ratingperusahaan') }}">
                            <i class="fa fa-star"></i>
                            <span>Rating Perusahaan</span>
                        </a>
                    </li>
                </ul>

            {{-- Menu Dosen Pembimbing --}}
            @elseif($userRole == 'dosen_pembimbing')
                <h4>Data Akademik</h4>
                <ul>
                    <li class="{{ (request()->is('mahasiswa') || request()->is('mahasiswa/*')) ? 'active' : '' }}">
                        <a href="{{ url('/mahasiswa') }}">
                            <i class="fa fa-user-graduate"></i>
                            <span>Mahasiswa Bimbingan</span>
                        </a>
                    </li>
                </ul>

                <h4>Jadwal & Kegiatan</h4>
                <ul>
                    <li class="{{ (request()->is('jadwal') || request()->is('jadwal/*')) ? 'active' : '' }}">
                        <a href="{{ url('/jadwal') }}">
                            <i class="fa fa-calendar-alt"></i>
                            <span>Jadwal Bimbingan</span>
                        </a>
                    </li>
                </ul>

                <h4>Penilaian & Hasil</h4>
                <ul>
                    <li class="{{ (request()->is('penilaian') || request()->is('penilaian/*')) ? 'active' : '' }}">
                        <a href="{{ url('/penilaian') }}">
                            <i class="fa fa-clipboard-check"></i>
                            <span>Input Nilai Bimbingan</span>
                        </a>
                    </li>
                </ul>

            {{-- Menu Dosen Penguji --}}
            @elseif($userRole == 'dosen_penguji')
                <h4>Penilaian & Hasil</h4>
                <ul>
                    <li class="{{ (request()->is('penilaian-penguji') || request()->is('penilaian-penguji/*')) ? 'active' : '' }}">
                        <a href="{{ url('/penilaian-penguji') }}">
                            <i class="fa fa-clipboard-check"></i>
                            <span>Input Nilai Ujian</span>
                        </a>
                    </li>
                </ul>

            {{-- Menu Perusahaan --}}
            @elseif($userRole == 'perusahaan')
                <h4>Data Akademik</h4>
                <ul>
                    <li class="{{ (request()->is('mahasiswa') || request()->is('mahasiswa/*')) ? 'active' : '' }}">
                        <a href="{{ url('/mahasiswa') }}">
                            <i class="fa fa-user-graduate"></i>
                            <span>Mahasiswa PKL</span>
                        </a>
                    </li>
                </ul>

                <h4>Perusahaan PKL</h4>
                <ul>
                    <li class="{{ (request()->is('perusahaan') || request()->is('perusahaan/*')) ? 'active' : '' }}">
                        <a href="{{ url('/perusahaan') }}">
                            <i class="fa fa-building"></i>
                            <span>Profil Perusahaan</span>
                        </a>
                    </li>
                </ul>

                <h4>Penilaian & Hasil</h4>
                <ul>
                    <li class="{{ (request()->is('penilaian') || request()->is('penilaian/*')) ? 'active' : '' }}">
                        <a href="{{ url('/penilaian') }}">
                            <i class="fa fa-clipboard-check"></i>
                            <span>Penilaian Mahasiswa</span>
                        </a>
                    </li>
                </ul>
            @endif

            {{-- AKUN - Semua Role --}}
            <h4>Akun</h4>
            <ul>
                <li>
                    {{-- Ini adalah tombol logout yang BENAR --}}
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                            {{-- KHUSUS KOORDINATOR (SUPER ADMIN) --}}
                    @if($userRole == 'koordinator')
                        <h4>Super Admin</h4>
                        <ul>
                            <li class="{{ (request()->is('manage-users') || request()->is('manage-users/*')) ? 'active' : '' }}">
                                <a href="{{ url('/manage-users') }}">
                                    <i class="fa fa-users-cog"></i> {{-- Pastikan icon fa-users-cog tersedia, atau pakai fa-cogs --}}
                                    <span>Manajemen Users</span>
                                </a>
                            </li>
                        </ul>
                    @endif
                    {{-- Form ini diperlukan karena logout adalah rute POST --}}
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>

        @endauth {{-- Akhir dari cek @auth --}}
    </div>
</div>
