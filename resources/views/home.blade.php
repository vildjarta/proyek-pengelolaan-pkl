@include('layout.header')
@include('layout.sidebar')

<main class="main-content">
    <div class="dashboard-container">
        <!-- Welcome Section -->
        <div style="margin-bottom:24px">
            <h2 style="margin:0 0 8px 0;font-size:28px;font-weight:700">Selamat Datang di Dashboard PKL</h2>
            <p style="margin:0;color:var(--muted);font-size:14px">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>

        <!-- Carousel Section -->
        <div class="top-row">
            <div class="carousel">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
                    <h3 style="margin:0;font-size:16px;font-weight:700">Pengumuman & Acara</h3>
                </div>
                <div class="slides" id="carouselSlides">
                    <img src="{{ asset('assets/images/banner1.jpeg') }}" alt="banner 1" data-index="0" style="object-fit:cover">
                    <img src="{{ asset('assets/images/banner2.jpeg') }}" alt="banner 2" data-index="1" style="display:none;object-fit:cover">
                    <img src="{{ asset('assets/images/banner3.jpeg') }}" alt="banner 3" data-index="2" style="display:none;object-fit:cover">
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
                <div class="icon" style="background:linear-gradient(135deg,#5b4ef6,#6c63ff)"><i class="fa fa-users"></i></div>
                <div class="meta">
                    <div class="num">{{ $countMahasiswa ?? 0 }}</div>
                    <div class="label">Mahasiswa PKL Aktif</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon" style="background:linear-gradient(135deg,#06b6d4,#3b82f6)"><i class="fa fa-building"></i></div>
                <div class="meta">
                    <div class="num">{{ $countPerusahaan ?? 0 }}</div>
                    <div class="label">Perusahaan Mitra</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon" style="background:linear-gradient(135deg,#10b981,#34d399)"><i class="fa fa-check-circle"></i></div>
                <div class="meta">
                    <div class="num">{{ $percentComplete ?? 0 }}%</div>
                    <div class="label">Penilaian Lengkap</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon" style="background:linear-gradient(135deg,#f59e0b,#f97316)"><i class="fa fa-calendar-alt"></i></div>
                <div class="meta">
                    <div class="num">{{ $totalSchedulesThisMonth ?? 0 }}</div>
                    <div class="label">Jadwal Bulan Ini</div>
                </div>
            </div>
        </div>

        <!-- Reviews & Calendar Section -->
        <div style="display:grid;grid-template-columns:1fr 380px;gap:16px">
            <!-- Reviews Section -->
            <div>
                <h4 style="margin:0 0 12px 0;font-size:16px;font-weight:700">Rating & Review Terbaru</h4>
                <div class="reviews">
                    @forelse($reviews as $r)
                        <div class="review-card">
                            <div class="avatar-initial">{{ strtoupper(substr(optional($r->mahasiswa)->nama ?? 'U',0,1)) }}</div>
                            <div class="body">
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
                                    <div style="font-weight:700;color:#111827">{{ optional($r->perusahaan)->nama ?? 'Perusahaan' }}</div>
                                    <div class="stars">@for($i=0;$i<5;$i++){!! $i < $r->rating ? '&#9733;' : '&#9734;' !!}@endfor</div>
                                </div>
                                <div style="font-size:12px;color:var(--muted);margin-bottom:6px">{{ optional($r->mahasiswa)->nama ?? 'Mahasiswa' }}</div>
                                <div style="font-size:13px;line-height:1.4;color:#555">{{ Str::limit($r->review, 140) }}</div>
                            </div>
                        </div>
                    @empty
                        <div style="padding:20px;text-align:center;color:var(--muted);grid-column:1/-1">
                            <i class="fa fa-star" style="font-size:24px;margin-bottom:8px;opacity:0.3;display:block"></i>
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
</main>

<link rel="stylesheet" href="/assets/css/dashboard.css">

<script>
    // Simple carousel with auto-slide
    (function(){
        const slides = document.querySelectorAll('#carouselSlides img');
        let idx = 0;
        const show = i => {
            slides.forEach((s,si)=> s.style.display = si===i? 'block':'none');
        }
        document.getElementById('carouselPrev').addEventListener('click', ()=>{ idx = (idx-1+slides.length)%slides.length; show(idx)});
        document.getElementById('carouselNext').addEventListener('click', ()=>{ idx = (idx+1)%slides.length; show(idx)});
        setInterval(()=>{ idx = (idx+1)%slides.length; show(idx); }, 5000);
        show(0);
    })();

    // Calendar rendering
    (function(){
        const jadwals = @json($jadwals);

        const calendar = document.getElementById('calendar');
        const now = new Date();
        const year = now.getFullYear();
        const month = now.getMonth();
        const first = new Date(year, month, 1);
        const last = new Date(year, month+1, 0);

        // week day headers
        const days = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
        days.forEach(d=>{
            const h = document.createElement('div');
            h.style.fontWeight='700';
            h.style.fontSize='11px';
            h.style.color='var(--muted)';
            h.style.padding='6px 0';
            h.style.textAlign='center';
            h.textContent = d;
            calendar.appendChild(h);
        });

        // blank slots before first day
        for(let i=0;i<first.getDay();i++){
            const b=document.createElement('div');
            calendar.appendChild(b);
        }

        // Calendar days
        for(let d=1; d<= last.getDate(); d++){
            const cell = document.createElement('div');
            cell.className='cal-day';
            const dateStr = new Date(year,month,d).toISOString().slice(0,10);
            const dateEl = document.createElement('div');
            dateEl.className='date';
            dateEl.textContent=d;
            cell.appendChild(dateEl);

            const events = jadwals.filter(j=> j.tanggal === dateStr);
            if(events.length){
                cell.classList.add('has-event');
                events.slice(0,1).forEach(ev=>{
                    const e = document.createElement('div');
                    e.style.fontSize='11px';
                    e.style.marginTop='3px';
                    e.style.color='#555';
                    e.textContent = (ev.waktu_mulai||'').substring(0,5);
                    cell.appendChild(e);
                });
            }

            cell.addEventListener('click', ()=>{
                const evs = jadwals.filter(j=> j.tanggal === dateStr);
                const details = document.getElementById('eventDetails');
                if(!evs.length){
                    details.innerHTML = '<i style="opacity:0.6">Tidak ada jadwal pada tanggal ini</i>';
                    return;
                }
                details.innerHTML = '<strong style="color:#111827">Jadwal ' + d + ':</strong>' + evs.map(x=> `<div style="padding:8px;border-top:1px solid #f0f0f0;margin-top:8px"><div style="font-weight:600;color:#111827">${x.mahasiswa||'Mahasiswa'}</div><div style="font-size:11px;color:#666;margin-top:4px">${x.dosen||'Dosen'} · ${x.waktu_mulai}–${x.waktu_selesai}</div></div>`).join('');
            });

            calendar.appendChild(cell);
        }
    })();
</script>
