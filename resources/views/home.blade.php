@include('layout.header')
@include('layout.sidebar')

<div class="dashboard container-fluid">
    <div class="dashboard-container">
        <div class="top-row">
            <div class="carousel">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px">
                    <h3 style="margin:0">Selamat Datang</h3>
                    <small style="color:var(--muted)">Dashboard PKL</small>
                </div>
                <div class="slides" id="carouselSlides">
                    <img src="/assets/img/banner1.jpg" alt="banner 1" data-index="0">
                    <img src="/assets/img/banner2.jpg" alt="banner 2" data-index="1" style="display:none">
                    <img src="/assets/img/banner3.jpg" alt="banner 3" data-index="2" style="display:none">
                </div>
                <div class="nav">
                    <button id="carouselPrev">Prev</button>
                    <button id="carouselNext">Next</button>
                </div>
            </div>

            <div style="width:420px">
                <div class="stats">
                    <div class="stat-card">
                        <div class="icon" style="background:linear-gradient(135deg,#5b4ef6,#6c63ff)">M</div>
                        <div class="meta">
                            <div class="num">{{ $countMahasiswa ?? 0 }}</div>
                            <div class="label">Mahasiswa PKL Aktif</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="icon" style="background:linear-gradient(135deg,#06b6d4,#3b82f6)">P</div>
                        <div class="meta">
                            <div class="num">{{ $countPerusahaan ?? 0 }}</div>
                            <div class="label">Perusahaan Mitra</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="icon" style="background:linear-gradient(135deg,#10b981,#34d399)">✓</div>
                        <div class="meta">
                            <div class="num">{{ $percentComplete ?? 0 }}%</div>
                            <div class="label">Penilaian Lengkap</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="icon" style="background:linear-gradient(135deg,#f59e0b,#f97316)">S</div>
                        <div class="meta">
                            <div class="num">{{ $totalSchedulesThisMonth ?? 0 }}</div>
                            <div class="label">Jadwal Bulan Ini</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 360px;gap:16px;margin-top:12px">
            <div>
                <h4 style="margin-bottom:8px">Rating & Review Terbaru</h4>
                <div class="reviews">
                    @forelse($reviews as $r)
                        <div class="review-card">
                            <div class="avatar-initial">{{ strtoupper(substr(optional($r->mahasiswa)->nama ?? 'U',0,1)) }}</div>
                            <div class="body">
                                <div style="display:flex;justify-content:space-between;align-items:center">
                                    <div style="font-weight:700">{{ optional($r->perusahaan)->nama ?? 'Perusahaan' }}</div>
                                    <div class="stars">@for($i=0;$i<5;$i++){!! $i < $r->rating ? '&#9733;' : '&#9734;' !!}@endfor</div>
                                </div>
                                <div style="font-size:13px;color:var(--muted)">{{ optional($r->mahasiswa)->nama ?? 'Mahasiswa' }}</div>
                                <div style="margin-top:8px">{{
                                        Str::limit($r->review, 180)
                                }}</div>
                            </div>
                        </div>
                    @empty
                        <div>Tidak ada review.</div>
                    @endforelse
                </div>
            </div>

            <div>
                <div class="calendar-card">
                    <h5 style="margin-top:0;margin-bottom:8px">Kalender Jadwal Bimbingan</h5>
                    <div id="calendar" class="calendar-grid">
                        {{-- calendar will be rendered by JS --}}
                    </div>
                    <div id="eventDetails" style="margin-top:10px;font-size:13px;color:var(--muted)"></div>
                </div>
            </div>
        </div>

    </div>
</div>

<link rel="stylesheet" href="/assets/css/dashboard.css">

<script>
    // Simple carousel
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
            const h = document.createElement('div'); h.style.fontWeight='700'; h.style.fontSize='12px'; h.textContent = d; calendar.appendChild(h);
        });

        // blank slots
        for(let i=0;i<first.getDay();i++){ const b=document.createElement('div'); calendar.appendChild(b); }

        for(let d=1; d<= last.getDate(); d++){
            const cell = document.createElement('div');
            cell.className='cal-day';
            const dateStr = new Date(year,month,d).toISOString().slice(0,10);
            const dateEl = document.createElement('div'); dateEl.className='date'; dateEl.textContent=d; cell.appendChild(dateEl);

            const events = jadwals.filter(j=> j.tanggal === dateStr);
            if(events.length){ cell.classList.add('has-event');
                events.slice(0,2).forEach(ev=>{
                    const e = document.createElement('div'); e.style.fontSize='12px'; e.style.marginTop='6px'; e.textContent = (ev.waktu_mulai||'') + ' - ' + (ev.mahasiswa||''); cell.appendChild(e);
                });
            }

            cell.addEventListener('click', ()=>{
                const evs = jadwals.filter(j=> j.tanggal === dateStr);
                const details = document.getElementById('eventDetails');
                if(!evs.length){ details.innerHTML = '<i>Tidak ada jadwal</i>'; return; }
                details.innerHTML = evs.map(x=> `<div style="padding:8px;border-bottom:1px solid #eee"><strong>${x.mahasiswa||'Mahasiswa'}</strong><div style="font-size:13px;color:#666">${x.dosen||'Dosen'} · ${x.waktu_mulai}–${x.waktu_selesai}</div></div>`).join('');
            });

            calendar.appendChild(cell);
        }
    })();
</script>

