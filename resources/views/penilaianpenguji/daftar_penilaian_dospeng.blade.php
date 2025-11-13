{{-- ====== PANGGIL HEADER & SIDEBAR SEKALI SAJA ====== --}}
@include('layout.header')
@include('layout.sidebar')

{{-- ====== PANGGIL CSS ====== --}}
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/style-pkl.css') }}">

{{-- ====== WRAPPER UTAMA ====== --}}
<div class="main-content-wrapper">
    <div class="content">
        <div class="card shadow border-0 rounded-3">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold">
                    <i class="fa fa-clipboard me-2"></i> Daftar Penilaian Dosen Penguji
                </h4>
                <a href="{{ route('penilaian.create') }}" class="btn btn-light btn-sm text-primary fw-bold">
    <i class="fa fa-plus me-1"></i> Tambah Penilaian
</a>
            </div>

            <div class="card-body p-4">
                {{-- Pesan sukses --}}
                @if(session('success'))
                    <div class="alert alert-success fw-bold">
                        <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                    </div>
                @endif

                {{-- TABEL PENILAIAN --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center">
                        <thead class="table-dark">
                            <tr>
                                <th rowspan="2">NIP</th>
                                <th rowspan="2">Nama Dosen</th>
                                <th rowspan="2">Nama Mahasiswa</th>
                                <th rowspan="2">Judul</th>
                                <th colspan="5">Komponen Penilaian</th>
                                <th rowspan="2">Total Nilai</th>
                                <th rowspan="2">Nilai Akhir (20%)</th>
                                <th rowspan="2">Tanggal</th>
                                <th rowspan="2">Aksi</th>
                            </tr>
                            <tr>
                                <th>Presentasi (10%)</th>
                                <th>Pemahaman Materi (15%)</th>
                                <th>Hasil (40%)</th>
                                <th>Objektivitas (20%)</th>
                                <th>Laporan (15%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($penilaian as $p)
                            <tr>
                                <td>{{ $p->dosen->nip ?? '-' }}</td>
                                <td>{{ $p->dosen->nama_dosen ?? '-' }}</td>
                                <td>{{ $p->nama_mahasiswa }}</td>
                                <td>{{ $p->judul }}</td>
                                <td>{{ $p->presentasi }}</td>
                                <td>{{ $p->materi }}</td>
                                <td>{{ $p->hasil }}</td>
                                <td>{{ $p->objektif }}</td>
                                <td>{{ $p->laporan }}</td>
                                <td>{{ $p->total_nilai }}</td>
                                <td>{{ $p->nilai_akhir }}</td>
                                <td>{{ $p->tanggal_ujian }}</td>
                                <td>
                                    <a href="{{ route('penilaian.edit', $p->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('penilaian.destroy', $p->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin hapus data ini?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="13" class="text-center fw-bold">Belum ada data penilaian</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ====== JAVASCRIPT ====== --}}
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    function setupSidebarToggle() {
        const toggleButton = document.querySelector('.menu-toggle');
        const body = document.body;
        const profileWrapper = document.querySelector('.user-profile-wrapper');
        const userinfo = document.querySelector('.user-info');

        // 🔹 Toggle sidebar
        if (toggleButton) {
            toggleButton.addEventListener('click', function(e) {
                e.preventDefault();
                body.classList.toggle('sidebar-closed');
            });
        } else {
            setTimeout(setupSidebarToggle, 1000);
        }

        // 🔹 Dropdown profil user
        if (userinfo && profileWrapper) {
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
    }

    setupSidebarToggle();
});
</script>
