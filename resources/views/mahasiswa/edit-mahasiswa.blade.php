{{-- ====== PANGGIL HEADER & SIDEBAR SEKALI SAJA ====== --}}
@include('layout.header')
@include('layout.sidebar')

{{-- ====== PANGGIL CSS ====== --}}
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/style-pkl.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/edit-mahasiswa.css') }}">

{{-- ====== WRAPPER UTAMA ====== --}}
<div class="main-content-wrapper">
    <div class="content">
        <div class="card shadow border-0 rounded-3">
            <div class="card-header bg-warning text-dark py-3">
                <h4 class="mb-0 fw-bold">
                    <i class="fa fa-edit me-2"></i> Edit Data Mahasiswa
                </h4>
            </div>

            <div class="card-body p-4">
                {{-- Pesan error --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fa fa-exclamation-circle me-2"></i> Terjadi kesalahan!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Form Edit --}}
                <form action="{{ route('mahasiswa.update', $mahasiswa->id_mahasiswa) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        {{-- NIM --}}
                        <div class="col-md-6">
                            <label for="nim" class="form-label fw-bold">NIM</label>
                            <input type="number" name="nim" id="nim"
                                value="{{ old('nim', $mahasiswa->nim) }}"
                                class="form-control" required>
                        </div>

                        {{-- Nama --}}
                        <div class="col-md-6">
                            <label for="nama" class="form-label fw-bold">Nama</label>
                            <input type="text" name="nama" id="nama"
                                value="{{ old('nama', $mahasiswa->nama) }}"
                                class="form-control" required>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input type="email" name="email" id="email"
                                value="{{ old('email', $mahasiswa->email) }}"
                                class="form-control" required>
                        </div>

                        {{-- No HP --}}
                        <div class="col-md-6">
                            <label for="no_hp" class="form-label fw-bold">No HP</label>
                            <input type="number" name="no_hp" id="no_hp"
                                value="{{ old('no_hp', $mahasiswa->no_hp) }}"
                                class="form-control" required>
                        </div>

                        {{-- Prodi --}}
                        <div class="col-md-4">
                            <label for="prodi" class="form-label fw-bold">Prodi</label>
                            <select name="prodi" id="prodi" class="form-control" required>
                                <option value="">-- Pilih Prodi --</option>
                                <option value="Akuntansi" {{ old('prodi', $mahasiswa->prodi) == 'Akuntansi' ? 'selected' : '' }}>Akuntansi</option>
                                <option value="Agroindustri" {{ old('prodi', $mahasiswa->prodi) == 'Agroindustri' ? 'selected' : '' }}>Agroindustri</option>
                                <option value="Teknologi Informasi" {{ old('prodi', $mahasiswa->prodi) == 'Teknologi Informasi' ? 'selected' : '' }}>Teknologi Informasi</option>
                                <option value="Teknologi Otomotif" {{ old('prodi', $mahasiswa->prodi) == 'Teknologi Otomotif' ? 'selected' : '' }}>Teknologi Otomotif</option>
                            </select>
                        </div>

                        {{-- Angkatan --}}
                        <div class="col-md-4">
                            <label for="angkatan" class="form-label fw-bold">Angkatan</label>
                            <input type="number" name="angkatan" id="angkatan"
                                value="{{ old('angkatan', $mahasiswa->angkatan) }}"
                                class="form-control" required>
                        </div>

                        {{-- IPK --}}
                        <div class="col-md-4">
                            <label for="ipk" class="form-label fw-bold">IPK</label>
                            <input type="number" step="0.01" min="0" max="4"
                                name="ipk" id="ipk"
                                value="{{ old('ipk', $mahasiswa->ipk) }}"
                                class="form-control" required>
                        </div>
                    </div>

                    {{-- Tombol --}}
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary rounded-pill px-4">
                            <i class="fa fa-arrow-left me-2"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-success rounded-pill px-4">
                            <i class="fa fa-save me-2"></i> Update Data
                        </button>
                    </div>
                </form>
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
                console.log('✅ Sidebar toggled!');
            });
        } else {
            console.warn('⚠️ Tombol .menu-toggle belum ketemu, nunggu 1 detik...');
            setTimeout(setupSidebarToggle, 1000); // 🔁 Coba ulang setelah 1 detik
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

    // Jalankan fungsi setup
    setupSidebarToggle();
});
</script>
