<div class="sidebar" role="navigation" aria-label="Sidebar menu">
    <div class="menu-list">
        @auth
            @php
                $userRole = auth()->user()->role ?? '';
                $currentRoute = request()->path();

                // --- 1. DEFINISI HAK AKSES (SESUAI REQUEST USER) ---
                
                // 1. Data Mahasiswa
                // Koordinator (All), Staff (View), Kaprodi (View) -> Semua butuh akses menu
                $showDataMahasiswa = in_array($userRole, ['koordinator', 'staff', 'ketua_prodi']); 

                // 2. Transkrip Kelayakan
                // Koordinator (All), Kaprodi (View), Mahasiswa (Analisa/Edit/Hapus)
                $showTranskrip     = in_array($userRole, ['koordinator', 'ketua_prodi', 'mahasiswa']);

                // 3. Master Data Dosen
                // Koordinator (All)
                $showMasterDosen   = ($userRole == 'koordinator');

                // 4. Dosen Pembimbing
                // Koordinator (All), Staff (View), Mahasiswa (View), DosPem (View/Edit)
                $showDosenPembimbing = in_array($userRole, ['koordinator', 'staff', 'dosen_pembimbing', 'mahasiswa']);

                // 5. Dosen Penguji
                // Koordinator (All), Staff (View), Mahasiswa (View), DosPeng (View/Edit)
                $showDosenPenguji    = in_array($userRole, ['koordinator', 'staff', 'dosen_penguji', 'mahasiswa']);

                // 6. Data Perusahaan
                // Koordinator (All), Staff(V), Mhs(V), Kaprodi(V), DosPeng(V), DosPem(V), Perusahaan(Edit Self)
                $showDataPerusahaan  = in_array($userRole, ['koordinator', 'staff', 'mahasiswa', 'ketua_prodi', 'dosen_penguji', 'dosen_pembimbing', 'perusahaan']); 

                // 7. Jadwal Bimbingan
                // Koordinator (All), Mahasiswa (View/Search), DosPem (All)
                $showJadwal = in_array($userRole, ['koordinator', 'mahasiswa', 'dosen_pembimbing']);

                // 8. Nilai Pembimbing
                // Koordinator (All), DosPem (All)
                $showNilaiPembimbing = in_array($userRole, ['koordinator', 'dosen_pembimbing']);

                // 9. Nilai Penguji
                // Koordinator (All), DosPeng (All)
                $showNilaiPenguji    = in_array($userRole, ['koordinator', 'dosen_penguji']);

                // 10. Nilai Perusahaan
                // Koordinator (All), Perusahaan (All)
                $showNilaiPerusahaan = in_array($userRole, ['koordinator', 'perusahaan']); 

                // 11. Nilai Mahasiswa
                // Koordinator (All), Mhs (View), DosPeng (View), DosPem (View)
                $showNilaiMahasiswa  = in_array($userRole, ['koordinator', 'mahasiswa', 'dosen_penguji', 'dosen_pembimbing']); 

                // 12. Rating Perusahaan
                // Koordinator (All), Mhs (All), Staff(V), Kaprodi(V), DosPem(V), DosPeng(V)
                $showRatingPerusahaan= in_array($userRole, ['koordinator', 'mahasiswa', 'staff', 'ketua_prodi', 'dosen_pembimbing', 'dosen_penguji', 'perusahaan']);

                // 13. Manajemen User
                // Koordinator (All)
                $showManajemenUser = ($userRole == 'koordinator');


                // --- 2. LOGIKA DROPDOWN OPEN/CLOSE (TIDAK BERUBAH BANYAK) ---
                $isDataAkademikOpen = request()->is('mahasiswa*') || request()->is('transkrip*');
                
                $isBimbinganOpen    = request()->is('datadosenpembimbing*') || request()->is('dosen_penguji*') || request()->is('dosen') || request()->is('dosen/*');
                
                $isPerusahaanOpen   = request()->is('perusahaan*');
                $isJadwalOpen       = request()->is('jadwal*');
                $isPenilaianOpen    = request()->is('penilaian*') || request()->is('penilaian-penguji*') || request()->is('penilaian_perusahaan*') || request()->is('nilai*') || request()->is('ratingperusahaan*');
                $isAkunOpen         = request()->is('profile*') || request()->is('manage-users*') || $currentRoute == 'home';
            @endphp

            {{-- HEADER BERANDA --}}
            <h4 class="menu-dropdown-toggle" tabindex="0" data-persist-id="menu-halaman-utama">
                <span><i class="fa fa-home"></i> <span class="label-text">Halaman Utama</span></span>
                <i class="fa fa-chevron-down dropdown-caret"></i>
            </h4>
            <ul class="dropdown-menu {{ $currentRoute == 'home' ? '' : 'collapsed' }}" aria-hidden="{{ $currentRoute == 'home' ? 'false' : 'true' }}">
                <li class="{{ $currentRoute == 'home' ? 'active' : '' }}">
                    <a href="{{ url('/home') }}">
                        <span class="label-text">Beranda</span>
                    </a>
                </li>
            </ul>

            {{-- MENU DATA AKADEMIK --}}
            @if($showDataMahasiswa || $showTranskrip)
                <h4 class="menu-dropdown-toggle" tabindex="0" data-persist-id="menu-data-akademik">
                    <span><i class="fa fa-graduation-cap"></i> <span class="label-text">Data Akademik</span></span>
                    <i class="fa fa-chevron-down dropdown-caret"></i>
                </h4>
                <ul class="dropdown-menu {{ $isDataAkademikOpen ? '' : 'collapsed' }}" aria-hidden="{{ $isDataAkademikOpen ? 'false' : 'true' }}">
                    @if($showDataMahasiswa)
                    <li class="{{ (request()->is('mahasiswa') || request()->is('mahasiswa/*')) ? 'active' : '' }}">
                        <a href="{{ route('mahasiswa.index') }}">
                            <span class="label-text">Data Mahasiswa</span>
                        </a>
                    </li>
                    @endif
                    
                    @if($showTranskrip)
                    <li class="{{ (request()->is('transkrip') || request()->is('transkrip/*')) ? 'active' : '' }}">
                        <a href="{{ route('transkrip.index') }}">
                            <span class="label-text">Transkrip Kelayakan</span>
                        </a>
                    </li>
                    @endif
                </ul>
            @endif

            {{-- MENU DATA DOSEN --}}
            @if($showMasterDosen || $showDosenPembimbing || $showDosenPenguji)
                <h4 class="menu-dropdown-toggle" tabindex="0" data-persist-id="menu-bimbingan-penguji">
                    <span><i class="fa fa-users"></i> <span class="label-text">Data Dosen</span></span>
                    <i class="fa fa-chevron-down dropdown-caret"></i>
                </h4>
                <ul class="dropdown-menu {{ $isBimbinganOpen ? '' : 'collapsed' }}" aria-hidden="{{ $isBimbinganOpen ? 'false' : 'true' }}">
                    
                    @if($showMasterDosen)
                    <li class="{{ (request()->is('dosen') || request()->is('dosen/*') && !request()->is('dosen_penguji*')) ? 'active' : '' }}">
                        <a href="{{ route('dosen.index') }}">
                            <span class="label-text">Data Dosen</span>
                        </a>
                    </li>
                    @endif

                    @if($showDosenPembimbing)
                    <li class="{{ (request()->is('datadosenpembimbing') || request()->is('datadosenpembimbing/*')) ? 'active' : '' }}">
                        <a href="{{ route('datadosenpembimbing.index') }}">
                            <span class="label-text">Dosen Pembimbing</span>
                        </a>
                    </li>
                    @endif

                    @if($showDosenPenguji)
                    <li class="{{ (request()->is('dosen_penguji') || request()->is('dosen_penguji/*')) ? 'active' : '' }}">
                        <a href="{{ route('dosen_penguji.index') }}">
                            <span class="label-text">Dosen Penguji</span>
                        </a>
                    </li>
                    @endif
                </ul>
            @endif

            {{-- MENU PERUSAHAAN --}}
            @if($showDataPerusahaan)
                <h4 class="menu-dropdown-toggle" tabindex="0" data-persist-id="menu-perusahaan">
                    <span><i class="fa fa-building"></i> <span class="label-text">Perusahaan PKL</span></span>
                    <i class="fa fa-chevron-down dropdown-caret"></i>
                </h4>
                <ul class="dropdown-menu {{ $isPerusahaanOpen ? '' : 'collapsed' }}" aria-hidden="{{ $isPerusahaanOpen ? 'false' : 'true' }}">
                    <li class="{{ (request()->is('perusahaan') || request()->is('perusahaan/*')) ? 'active' : '' }}">
                        <a href="{{ route('perusahaan.index') }}">
                            <span class="label-text">Data Perusahaan</span>
                        </a>
                    </li>
                </ul>
            @endif

            {{-- MENU JADWAL --}}
            @if($showJadwal)
                <h4 class="menu-dropdown-toggle" tabindex="0" data-persist-id="menu-jadwal">
                    <span><i class="fa fa-calendar-alt"></i> <span class="label-text">Jadwal & Kegiatan</span></span>
                    <i class="fa fa-chevron-down dropdown-caret"></i>
                </h4>
                <ul class="dropdown-menu {{ $isJadwalOpen ? '' : 'collapsed' }}" aria-hidden="{{ $isJadwalOpen ? 'false' : 'true' }}">
                    <li class="{{ (request()->is('jadwal') || request()->is('jadwal/*')) ? 'active' : '' }}">
                        <a href="{{ route('jadwal.index') }}">
                            <span class="label-text">Jadwal Bimbingan</span>
                        </a>
                    </li>
                </ul>
            @endif

            {{-- MENU PENILAIAN --}}
            @if($showNilaiPembimbing || $showNilaiPenguji || $showNilaiPerusahaan || $showNilaiMahasiswa || $showRatingPerusahaan)
                <h4 class="menu-dropdown-toggle" tabindex="0" data-persist-id="menu-penilaian">
                    <span><i class="fa fa-clipboard-check"></i> <span class="label-text">Penilaian & Hasil</span></span>
                    <i class="fa fa-chevron-down dropdown-caret"></i>
                </h4>
                <ul class="dropdown-menu {{ $isPenilaianOpen ? '' : 'collapsed' }}" aria-hidden="{{ $isPenilaianOpen ? 'false' : 'true' }}">
                    
                    @if($showNilaiPembimbing)
                    <li class="{{ (request()->is('penilaian') || request()->is('penilaian/*') && !request()->is('penilaian_perusahaan*')) ? 'active' : '' }}">
                        <a href="{{ route('penilaian.index') }}">
                            <span class="label-text">Nilai Pembimbing</span>
                        </a>
                    </li>
                    @endif

                    @if($showNilaiPenguji)
                    <li class="{{ (request()->is('penilaian-penguji') || request()->is('penilaian-penguji/*')) ? 'active' : '' }}">
                        <a href="{{ route('penilaian-penguji.index') }}">
                            <span class="label-text">Nilai Penguji</span>
                        </a>
                    </li>
                    @endif

                    @if($showNilaiPerusahaan)
                    <li class="{{ (request()->is('penilaian_perusahaan') || request()->is('penilaian_perusahaan/*')) ? 'active' : '' }}">
                        <a href="{{ route('penilaian-perusahaan.index') }}">
                            <span class="label-text">Nilai Perusahaan</span>
                        </a>
                    </li>
                    @endif

                    @if($showNilaiMahasiswa)
                    <li class="{{ (request()->is('nilai') || request()->is('nilai/*')) ? 'active' : '' }}">
                        <a href="{{ route('nilai.index') }}">
                            <span class="label-text">Nilai Mahasiswa</span>
                        </a>
                    </li>
                    @endif

                    @if($showRatingPerusahaan)
                    <li class="{{ (request()->is('ratingperusahaan') || request()->is('ratingperusahaan/*')) ? 'active' : '' }}">
                        <a href="{{ route('ratingperusahaan') }}">
                            <span class="label-text">Rating Perusahaan</span>
                        </a>
                    </li>
                    @endif
                </ul>
            @endif

            {{-- MENU AKUN --}}
            <h4 class="menu-dropdown-toggle" tabindex="0" data-persist-id="menu-akun">
                <span><i class="fa fa-user-circle"></i> <span class="label-text">Akun</span></span>
                <i class="fa fa-chevron-down dropdown-caret"></i>
            </h4>
            <ul class="dropdown-menu {{ $isAkunOpen ? '' : 'collapsed' }}" aria-hidden="{{ $isAkunOpen ? 'false' : 'true' }}">
                <li>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                        <span class="label-text">Logout</span>
                    </a>

                    @if($showManajemenUser)
                    <li class="{{ (request()->is('manage-users') || request()->is('manage-users/*')) ? 'active' : '' }}">
                        <a href="{{ route('manage-users.index') }}">
                            <span class="label-text">Manajemen Users</span>
                        </a>
                    </li>
                    @endif

                    <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display:none;">
                        @csrf
                    </form>
                </li>
            </ul>
        @endauth
    </div>
</div>

{{-- SCRIPT TETAP --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdownHeaders = document.querySelectorAll('.sidebar h4.menu-dropdown-toggle');
        
        try {
            const stored = JSON.parse(localStorage.getItem('sidebar-open-menus') || '{}');
            dropdownHeaders.forEach(function (header) {
                const menu = header.nextElementSibling;
                const id = header.getAttribute('data-persist-id') || header.textContent.trim();
                if (menu && stored[id]) {
                    menu.classList.remove('collapsed');
                    header.classList.remove('collapsed');
                    menu.setAttribute('aria-hidden', 'false');
                }
            });
        } catch (err) { console.warn('sidebar restore error', err); }

        dropdownHeaders.forEach(function (header, idx) {
            const toggle = function () {
                const menu = header.nextElementSibling;
                if (menu && menu.classList.contains('dropdown-menu')) {
                    menu.classList.toggle('collapsed');
                    header.classList.toggle('collapsed');
                    const isCollapsed = menu.classList.contains('collapsed');
                    menu.setAttribute('aria-hidden', isCollapsed ? 'true' : 'false');
                    persistSidebarState();
                }
            };
            if (!header.hasAttribute('data-persist-id')) {
                header.setAttribute('data-persist-id', 'menu-' + idx);
            }
            header.addEventListener('click', toggle);
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