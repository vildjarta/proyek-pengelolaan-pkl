@include('layout.header')
@include('layout.sidebar')
<style>
    /* ===== Download Cards ===== */
    .download-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        text-decoration: none;
        color: inherit;
        transition: box-shadow 0.2s, transform 0.2s, border-color 0.2s;
        cursor: pointer;
    }

    .download-card:hover {
        box-shadow: 0 4px 16px rgba(91, 78, 246, 0.12);
        border-color: #5b4ef6;
        transform: translateY(-2px);
    }

    .dl-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 20px;
        flex-shrink: 0;
    }

    .dl-meta {
        flex: 1;
        min-width: 0;
    }

    .dl-title {
        font-size: 13px;
        font-weight: 700;
        color: #111827;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .dl-sub {
        font-size: 11px;
        color: var(--muted, #6b7280);
        margin-top: 2px;
    }

    .dl-badge {
        display: inline-block;
        margin-top: 5px;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 4px;
        background: #fee2e2;
        color: #b91c1c;
        letter-spacing: 0.5px;
    }

    .dl-arrow {
        font-size: 14px;
        color: #9ca3af;
        flex-shrink: 0;
        transition: color 0.2s, transform 0.2s;
    }

    .download-card:hover .dl-arrow {
        color: #5b4ef6;
        transform: translateY(2px);
    }
</style>
<main class="main-content">
    <div class="dashboard-container">
        <!-- Welcome Section -->
        <div style="margin-bottom:24px">
            <h2 style="margin:0 0 8px 0;font-size:28px;font-weight:700">Selamat Datang di Dashboard PKL</h2>
            <p style="margin:0;color:var(--muted);font-size:14px">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
        <i class="fa fa-bars menu-toggle"></i>
    </div>

        <!-- Carousel Section -->
        <div class="top-row">
            <div class="carousel">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
                    <h3 style="margin:0;font-size:16px;font-weight:700">Pengumuman & Acara</h3>
                </div>
                <div class="slides" id="carouselSlides">
                    <img src="{{ asset('assets/images/banner1.jpeg') }}" alt="banner 1" data-index="0"
                        style="object-fit:cover">
                    <img src="{{ asset('assets/images/banner2.jpeg') }}" alt="banner 2" data-index="1"
                        style="display:none;object-fit:cover">
                    <img src="{{ asset('assets/images/banner3.jpeg') }}" alt="banner 3" data-index="2"
                        style="display:none;object-fit:cover">
                </div>
                <div class="nav">
                    <button id="carouselPrev">← Sebelumnya</button>
                    <button id="carouselNext">Selanjutnya →</button>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:24px;margin-top:24px">
            <div class="stat-card">
                <div class="icon" style="background:linear-gradient(135deg,#5b4ef6,#6c63ff)"><i
                        class="fa fa-users"></i></div>
                <div class="meta">
                    <div class="num">{{ $countMahasiswa ?? 0 }}</div>
                    <div class="label">Mahasiswa PKL Aktif</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon" style="background:linear-gradient(135deg,#06b6d4,#3b82f6)"><i
                        class="fa fa-building"></i></div>
                <div class="meta">
                    <div class="num">{{ $countPerusahaan ?? 0 }}</div>
                    <div class="label">Perusahaan Mitra</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon" style="background:linear-gradient(135deg,#10b981,#34d399)"><i
                        class="fa fa-check-circle"></i></div>
                <div class="meta">
                    <div class="num">{{ $percentComplete ?? 0 }}%</div>
                    <div class="label">Penilaian Lengkap</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon" style="background:linear-gradient(135deg,#f59e0b,#f97316)"><i
                        class="fa fa-calendar-alt"></i></div>
                <div class="meta">
                    <div class="num">{{ $totalSchedulesThisMonth ?? 0 }}</div>
                    <div class="label">Jadwal Bulan Ini</div>
                </div>
            </div>
        </div>

        <!-- Reviews & Calendar Section -->
        {{-- ===== Download Template Section ===== --}}
        <div style="margin-bottom:24px">
            <h4 style="margin:0 0 12px 0;font-size:16px;font-weight:700">
                <i class="fa fa-download" style="margin-right:6px;color:#5b4ef6"></i>Unduh Template & Dokumen
            </h4>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px">

                {{-- File 1: Buku Pedoman PKL --}}
                <a href="{{ asset('assets/Buku Pedoman PKL Tahun Akademik 2023.2024 versi 5.2 .pdf') }}" download
                    class="download-card">
                    <div class="dl-icon" style="background:linear-gradient(135deg,#ef4444,#f97316)">
                        <i class="fa fa-file-pdf-o"></i>
                    </div>
                    <div class="dl-meta">
                        <div class="dl-title">Buku Pedoman PKL</div>
                        <div class="dl-sub">TA 2023/2024 · Versi 5.2</div>
                        <span class="dl-badge">PDF</span>
                    </div>
                    <i class="fa fa-download dl-arrow"></i>
                </a>

                {{-- File 2: Undangan Seminar PKL --}}
                <a href="{{ asset('assets/Undangan Seminar PKL.pdf') }}" download class="download-card">
                    <div class="dl-icon" style="background:linear-gradient(135deg,#ef4444,#f97316)">
                        <i class="fa fa-file-pdf-o"></i>
                    </div>
                    <div class="dl-meta">
                        <div class="dl-title">Undangan Seminar PKL</div>
                        <div class="dl-sub">Surat Undangan Resmi</div>
                        <span class="dl-badge">PDF</span>
                    </div>
                    <i class="fa fa-download dl-arrow"></i>
                </a>

                {{-- File 3: Template Proposal PKL TI --}}
                <a href="{{ asset('assets/Template Proposal PKL TI .docx.pdf') }}" download class="download-card">
                    <div class="dl-icon" style="background:linear-gradient(135deg,#ef4444,#f97316)">
                        <i class="fa fa-file-pdf-o"></i>
                    </div>
                    <div class="dl-meta">
                        <div class="dl-title">Template Proposal PKL TI</div>
                        <div class="dl-sub">Format Dokumen PDF</div>
                        <span class="dl-badge">PDF</span>
                        {{-- <span class="dl-badge" style="background:#dbeafe;color:#1d4ed8">DOCX</span> --}}
                    </div>
                    <i class="fa fa-download dl-arrow"></i>
                </a>

            </div>
        </div>
        {{-- ===== End Download Template Section ===== --}}
        <div style="display:grid;grid-template-columns:1fr 380px;gap:16px">
            <!-- Reviews Section -->
            <div>
                <h4 style="margin:0 0 12px 0;font-size:16px;font-weight:700">Rating & Review Terbaru</h4>
                <div class="reviews">
                    @forelse($reviews as $r)
                        <div class="review-card">
                            <div class="avatar-initial">
                                {{ strtoupper(substr(optional($r->mahasiswa)->nama ?? 'U', 0, 1)) }}</div>
                            <div class="body">
                                <div
                                    style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
                                    <div style="font-weight:700;color:#111827">
                                        {{ optional($r->perusahaan)->nama ?? 'Perusahaan' }}</div>
                                    <div class="stars">
                                        @for ($i = 0; $i < 5; $i++)
                                            {!! $i < $r->rating ? '&#9733;' : '&#9734;' !!}
                                        @endfor
                                    </div>
                                </div>
                                <div style="font-size:12px;color:var(--muted);margin-bottom:6px">
                                    {{ optional($r->mahasiswa)->nama ?? 'Mahasiswa' }}</div>
                                <div style="font-size:13px;line-height:1.4;color:#555">
                                    {{ Str::limit($r->review, 140) }}</div>
                            </div>
                        </div>
                    @empty
                        <div style="padding:20px;text-align:center;color:var(--muted);grid-column:1/-1">
                            <i class="fa fa-star"
                                style="font-size:24px;margin-bottom:8px;opacity:0.3;display:block"></i>
                            Tidak ada review.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Calendar Section -->
            <div>
                <h4 style="margin:0 0 12px 0;font-size:16px;font-weight:700">Jadwal Bimbingan</h4>
                <div class="calendar-card">
                    <div id="calendar" class="calendar-grid">
                        {{-- calendar will be rendered by JS --}}
                    </div>
                    <div id="eventDetails" style="margin-top:12px;font-size:12px;color:var(--muted)"></div>
                </div>
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

<script>
    // Simple carousel with auto-slide
    (function() {
        const slides = document.querySelectorAll('#carouselSlides img');
        let idx = 0;
        const show = i => {
            slides.forEach((s, si) => s.style.display = si === i ? 'block' : 'none');
        }
        document.getElementById('carouselPrev').addEventListener('click', () => {
            idx = (idx - 1 + slides.length) % slides.length;
            show(idx)
        });
        document.getElementById('carouselNext').addEventListener('click', () => {
            idx = (idx + 1) % slides.length;
            show(idx)
        });
        setInterval(() => {
            idx = (idx + 1) % slides.length;
            show(idx);
        }, 5000);
        show(0);
    })();

    // Calendar rendering
    (function() {
        const jadwals = @json($jadwals);

        const calendar = document.getElementById('calendar');
        const now = new Date();
        const year = now.getFullYear();
        const month = now.getMonth();
        const first = new Date(year, month, 1);
        const last = new Date(year, month + 1, 0);

        // week day headers
        const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        days.forEach(d => {
            const h = document.createElement('div');
            h.style.fontWeight = '700';
            h.style.fontSize = '11px';
            h.style.color = 'var(--muted)';
            h.style.padding = '6px 0';
            h.style.textAlign = 'center';
            h.textContent = d;
            calendar.appendChild(h);
        });

        // blank slots before first day
        for (let i = 0; i < first.getDay(); i++) {
            const b = document.createElement('div');
            calendar.appendChild(b);
        }

        // Calendar days
        for (let d = 1; d <= last.getDate(); d++) {
            const cell = document.createElement('div');
            cell.className = 'cal-day';
            const dateStr = new Date(year, month, d).toISOString().slice(0, 10);
            const dateEl = document.createElement('div');
            dateEl.className = 'date';
            dateEl.textContent = d;
            cell.appendChild(dateEl);

            const events = jadwals.filter(j => j.tanggal === dateStr);
            if (events.length) {
                cell.classList.add('has-event');
                events.slice(0, 1).forEach(ev => {
                    const e = document.createElement('div');
                    e.style.fontSize = '11px';
                    e.style.marginTop = '3px';
                    e.style.color = '#555';
                    e.textContent = (ev.waktu_mulai || '').substring(0, 5);
                    cell.appendChild(e);
                });
            }

            cell.addEventListener('click', () => {
                const evs = jadwals.filter(j => j.tanggal === dateStr);
                const details = document.getElementById('eventDetails');
                if (!evs.length) {
                    details.innerHTML = '<i style="opacity:0.6">Tidak ada jadwal pada tanggal ini</i>';
                    return;
                }
                details.innerHTML = '<strong style="color:#111827">Jadwal ' + d + ':</strong>' + evs.map(
                    x =>
                    `<div style="padding:8px;border-top:1px solid #f0f0f0;margin-top:8px"><div style="font-weight:600;color:#111827">${x.mahasiswa||'Mahasiswa'}</div><div style="font-size:11px;color:#666;margin-top:4px">${x.dosen||'Dosen'} · ${x.waktu_mulai}–${x.waktu_selesai}</div></div>`
                ).join('');
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
