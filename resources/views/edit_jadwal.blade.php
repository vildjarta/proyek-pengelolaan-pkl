@include('layout.header')
@include('layout.sidebar')
<link rel="stylesheet" href="{{ asset('assets/css/style-edit-jadwal.css') }}">
<div class="main-content-wrapper">
    <div class="content">
        <div class="form-card">
            <h2>Edit Jadwal Bimbingan</h2>

            {{-- Tampilkan error validasi jika ada --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('jadwal.update', $jadwal->id) }}" method="POST" onsubmit="return confirmSubmit();">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="mahasiswa">Mahasiswa (Opsional)</label>
                    <input type="text" name="mahasiswa" id="mahasiswa" class="form-control" placeholder="Ketik nama mahasiswa..." value="{{ old('mahasiswa', $jadwal->mahasiswa) }}">
                </div>

                <div class="form-group">
                    <label for="dosen">Dosen (Opsional)</label>
                    <input type="text" name="dosen" id="dosen" class="form-control" placeholder="Ketik nama dosen..." value="{{ old('dosen', $jadwal->dosen) }}">
                </div>

                <div class="form-group">
                    <label for="tanggal">Tanggal Bimbingan</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" required value="{{ old('tanggal', $jadwal->tanggal ? date('Y-m-d', strtotime($jadwal->tanggal)) : '') }}">
                </div>

                <div class="form-group">
                    <label for="waktu_mulai">Waktu Mulai</label>
                    <input type="time" name="waktu_mulai" id="waktu_mulai" class="form-control" required value="{{ old('waktu_mulai', $jadwal->waktu_mulai) }}">
                </div>
                
                <div class="form-group">
                    <label for="waktu_selesai">Waktu Selesai</label>
                    <input type="time" name="waktu_selesai" id="waktu_selesai" class="form-control" required value="{{ old('waktu_selesai', $jadwal->waktu_selesai) }}">
                </div>

                <div class="form-group">
                    <label for="topik">Topik Bimbingan (Opsional)</label>
                    <input type="text" name="topik" id="topik" class="form-control" placeholder="Contoh: Revisi Bab 1" value="{{ old('topik', $jadwal->topik) }}">
                </div>
                
                <div class="form-group">
                    <label for="catatan">Aksi / Catatan (Opsional)</label>
                    <input type="text" name="catatan" id="catatan" class="form-control" placeholder="Contoh: Bawa laptop dan hard copy" value="{{ old('catatan', $jadwal->catatan) }}">
                </div>

                <div class="form-actions">
                    <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-success">Update Jadwal</button>
                </div>
            </form>
        </div>
    </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toggleButton = document.querySelector('.menu-toggle');
                const body = document.body;

                function updateSortingLinks() {
                    const sortingLinks = document.querySelectorAll('.table thead a');
                    const isSidebarClosed = body.classList.contains('sidebar-closed');

                    sortingLinks.forEach(link => {
                        let url = new URL(link.href);
                        if (isSidebarClosed) {
                            url.searchParams.set('sidebar', 'closed');
                        } else {
                            url.searchParams.delete('sidebar');
                        }
                        link.href = url.toString();
                    });
                }

                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.get('sidebar') === 'closed') {
                    body.classList.add('sidebar-closed');
                }

                updateSortingLinks();

                if (toggleButton) {
                    toggleButton.addEventListener('click', function() {
                        body.classList.toggle('sidebar-closed');
                        updateSortingLinks();
                    });
                }
                
                const profileWrapper = document.querySelector('.user-profile-wrapper');
                const userinfo = document.querySelector('.user-info');
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
</div>
<script>
    function confirmSubmit() {
        return confirm("Apakah Anda yakin untuk mengubah jadwal ini?");
    }
</script>