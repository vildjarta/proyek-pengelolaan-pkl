{{-- sidebar.blade.php --}}
<div class="sidebar" role="navigation" aria-label="Sidebar menu">
    <div class="menu-list">
        @auth
            @php
                $userRole = auth()->user()->role ?? '';
                $currentRoute = request()->path();

                // helper kecil untuk membuka grup jika salah satu route anak cocok
                $isDataAkademikOpen = request()->is('mahasiswa*') || request()->is('transkrip*');

                // PERBAIKAN: tambahkan 'dosen*' agar grup Bimbingan terbuka juga saat /dosen
                $isBimbinganOpen = request()->is('datadosenpembimbing*') || request()->is('dosen_penguji*') || request()->is('dosen*');

                $isPerusahaanOpen = request()->is('perusahaan*');
                $isJadwalOpen = request()->is('jadwal*');
                $isPenilaianOpen = request()->is('penilaian*') || request()->is('penilaian-penguji*') || request()->is('nilai*') || request()->is('ratingperusahaan*');
                $isAkunOpen = request()->is('profile*') || request()->is('manage-users*') || $currentRoute == 'home';
            @endphp

            <h4 class="menu-dropdown-toggle" tabindex="0" data-persist-id="menu-halaman-utama">
                <span><i class="fa fa-home"></i> <span class="label-text">Halaman Utama</span></span>
                <i class="fa dropdown-caret fa-chevron-right" aria-hidden="true"></i>
            </h4>
            <ul class="dropdown-menu {{ $currentRoute == 'home' ? '' : 'collapsed' }}" aria-hidden="{{ $currentRoute == 'home' ? 'false' : 'true' }}">
                <li class="{{ $currentRoute == 'home' ? 'active' : '' }}">
                    <a href="{{ url('/home') }}">
                        <span class="label-text">Beranda</span>
                    </a>
                </li>
            </ul>

            @if($userRole == 'koordinator')
                <h4 class="menu-dropdown-toggle" tabindex="0" data-persist-id="menu-data-akademik">
                    <span><i class="fa fa-graduation-cap"></i> <span class="label-text">Data Akademik</span></span>
                    <i class="fa dropdown-caret fa-chevron-right" aria-hidden="true"></i>
                </h4>
                <ul class="dropdown-menu {{ $isDataAkademikOpen ? '' : 'collapsed' }}" aria-hidden="{{ $isDataAkademikOpen ? 'false' : 'true' }}">
                    <li class="{{ (request()->is('mahasiswa') || request()->is('mahasiswa/*')) ? 'active' : '' }}">
                        <a href="{{ url('/mahasiswa') }}">
                            <span class="label-text">Data Mahasiswa</span>
                        </a>
                    </li>
                    <li class="{{ (request()->is('transkrip') || request()->is('transkrip/*')) ? 'active' : '' }}">
                        <a href="{{ url('/transkrip') }}">
                            <span class="label-text">Transkrip Kelayakan</span>
                        </a>
                    </li>
                </ul>

                <h4 class="menu-dropdown-toggle" tabindex="0" data-persist-id="menu-bimbingan-penguji">
                    <span><i class="fa fa-users"></i> <span class="label-text">Bimbingan & Penguji</span></span>
                    <i class="fa dropdown-caret fa-chevron-right" aria-hidden="true"></i>
                </h4>
                <ul class="dropdown-menu {{ $isBimbinganOpen ? '' : 'collapsed' }}" aria-hidden="{{ $isBimbinganOpen ? 'false' : 'true' }}">
                    <li class="{{ (request()->is('datadosenpembimbing') || request()->is('datadosenpembimbing/*')) ? 'active' : '' }}">
                        <a href="{{ url('/datadosenpembimbing') }}">
                            <span class="label-text">Dosen Pembimbing</span>
                        </a>
                    </li>

                    <li class="{{ (request()->is('dosen_penguji') || request()->is('dosen_penguji/*')) ? 'active' : '' }}">
                        <a href="{{ url('/dosen_penguji') }}">
                            <span class="label-text">Dosen Penguji</span>
                        </a>
                    </li>

                    {{-- ITEM BARU: Dosen (menu umum masuk ke halaman /dosen) --}}
                    <li class="{{ (request()->is('dosen') || request()->is('dosen/*')) ? 'active' : '' }}">
                        <a href="{{ url('/dosen') }}">
                            <span class="label-text">Dosen</span>
                        </a>
                    </li>
                </ul>

                <h4 class="menu-dropdown-toggle" tabindex="0" data-persist-id="menu-perusahaan">
                    <span><i class="fa fa-building"></i> <span class="label-text">Perusahaan PKL</span></span>
                    <i class="fa dropdown-caret fa-chevron-right" aria-hidden="true"></i>
                </h4>
                <ul class="dropdown-menu {{ $isPerusahaanOpen ? '' : 'collapsed' }}" aria-hidden="{{ $isPerusahaanOpen ? 'false' : 'true' }}">
                    <li class="{{ (request()->is('perusahaan') || request()->is('perusahaan/*')) ? 'active' : '' }}">
                        <a href="{{ url('/perusahaan') }}">
                            <span class="label-text">Data Perusahaan</span>
                        </a>
                    </li>
                </ul>

                <h4 class="menu-dropdown-toggle" tabindex="0" data-persist-id="menu-jadwal">
                    <span><i class="fa fa-calendar-alt"></i> <span class="label-text">Jadwal & Kegiatan</span></span>
                    <i class="fa dropdown-caret fa-chevron-right" aria-hidden="true"></i>
                </h4>
                <ul class="dropdown-menu {{ $isJadwalOpen ? '' : 'collapsed' }}" aria-hidden="{{ $isJadwalOpen ? 'false' : 'true' }}">
                    <li class="{{ (request()->is('jadwal') || request()->is('jadwal/*')) ? 'active' : '' }}">
                        <a href="{{ url('/jadwal') }}">
                            <span class="label-text">Jadwal Bimbingan</span>
                        </a>
                    </li>
                </ul>

                <h4 class="menu-dropdown-toggle" tabindex="0" data-persist-id="menu-penilaian">
                    <span><i class="fa fa-clipboard-check"></i> <span class="label-text">Penilaian & Hasil</span></span>
                    <i class="fa dropdown-caret fa-chevron-right" aria-hidden="true"></i>
                </h4>
                <ul class="dropdown-menu {{ $isPenilaianOpen ? '' : 'collapsed' }}" aria-hidden="{{ $isPenilaianOpen ? 'false' : 'true' }}">
                    <li class="{{ (request()->is('penilaian') || request()->is('penilaian/*')) ? 'active' : '' }}">
                        <a href="{{ url('/penilaian') }}">
                            <span class="label-text">Nilai Pembimbing</span>
                        </a>
                    </li>
                    <li class="{{ (request()->is('penilaian-penguji') || request()->is('penilaian-penguji/*')) ? 'active' : '' }}">
                        <a href="{{ url('/penilaian-penguji') }}">
                            <span class="label-text">Nilai Penguji</span>
                        </a>
                    </li>
                    <li class="{{ (request()->is('nilai') || request()->is('nilai/*')) ? 'active' : '' }}">
                        <a href="{{ url('/nilai') }}">
                            <span class="label-text">Nilai Mahasiswa</span>
                        </a>
                    </li>
                    <li class="{{ (request()->is('ratingperusahaan') || request()->is('ratingperusahaan/*')) ? 'active' : '' }}">
                        <a href="{{ url('/ratingperusahaan') }}">
                            <span class="label-text">Rating Perusahaan</span>
                        </a>
                    </li>
                </ul>
            @endif

            {{-- AKUN --}}
            <h4 class="menu-dropdown-toggle" tabindex="0" data-persist-id="menu-akun">
                <span><i class="fa fa-user-circle"></i> <span class="label-text">Akun</span></span>
                <i class="fa dropdown-caret fa-chevron-right" aria-hidden="true"></i>
            </h4>

            <ul class="dropdown-menu {{ $isAkunOpen ? '' : 'collapsed' }}" aria-hidden="{{ $isAkunOpen ? 'false' : 'true' }}">
                @if($userRole == 'koordinator')
                    <li class="{{ (request()->is('manage-users') || request()->is('manage-users/*')) ? 'active' : '' }}">
                        <a href="{{ url('/manage-users') }}">
                            <span class="label-text">Manajemen Users</span>
                        </a>
                    </li>
                @endif

                <li>
                    <a href="#"
                       onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                        <span class="label-text">Logout</span>
                    </a>

                    <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display:none;">
                        @csrf
                    </form>
                </li>
            </ul>
        @endauth
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdownHeaders = document.querySelectorAll('.sidebar h4.menu-dropdown-toggle');

        // Restore persisted open menus (if any)
        try {
            const stored = JSON.parse(localStorage.getItem('sidebar-open-menus') || '{}');
            dropdownHeaders.forEach(function (header) {
                const menu = header.nextElementSibling;
                const caret = header.querySelector('.dropdown-caret');
                const id = header.getAttribute('data-persist-id') || header.textContent.trim();
                if (menu && stored[id]) {
                    menu.classList.remove('collapsed');
                    header.classList.remove('collapsed');
                    menu.setAttribute('aria-hidden', 'false');
                    if (caret) { caret.classList.remove('fa-chevron-right'); caret.classList.add('fa-chevron-down'); }
                } else {
                    if (caret) { caret.classList.remove('fa-chevron-down'); caret.classList.add('fa-chevron-right'); }
                }
            });
        } catch (err) { console.warn('sidebar restore error', err); }

        dropdownHeaders.forEach(function (header, idx) {
            const toggle = function () {
                const menu = header.nextElementSibling;
                const caret = header.querySelector('.dropdown-caret');
                if (menu && menu.classList.contains('dropdown-menu')) {
                    menu.classList.toggle('collapsed');
                    header.classList.toggle('collapsed');
                    const isCollapsed = menu.classList.contains('collapsed');
                    menu.setAttribute('aria-hidden', isCollapsed ? 'true' : 'false');

                    if (caret) {
                        if (isCollapsed) {
                            caret.classList.remove('fa-chevron-down');
                            caret.classList.add('fa-chevron-right');
                        } else {
                            caret.classList.remove('fa-chevron-right');
                            caret.classList.add('fa-chevron-down');
                        }
                    }
                    persistSidebarState();
                }
            };

            if (!header.hasAttribute('data-persist-id')) {
                header.setAttribute('data-persist-id', 'menu-' + idx);
            }

            header.addEventListener('click', toggle);
            header.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggle();
                }
            });
        });

        function persistSidebarState() {
            const state = {};
            document.querySelectorAll('.sidebar h4.menu-dropdown-toggle').forEach(function (header) {
                const id = header.getAttribute('data-persist-id');
                const menu = header.nextElementSibling;
                if (!id || !menu) return;
                state[id] = !menu.classList.contains('collapsed');
            });
            try { localStorage.setItem('sidebar-open-menus', JSON.stringify(state)); }
            catch (e) { console.warn('could not persist sidebar state', e); }
        }
    });
</script>
