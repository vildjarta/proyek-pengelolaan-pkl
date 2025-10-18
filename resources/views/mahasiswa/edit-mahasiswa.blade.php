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
    <div class="card-header text-white py-3 d-flex justify-content-between align-items-center"
         style="background: linear-gradient(90deg, #007bff 0%, #0056b3 100%);">
        <h4 class="mb-0 fw-bold">
            <i class="fa fa-edit me-2"></i> Edit Data Mahasiswa
        </h4>
        <a href="{{ route('mahasiswa.index') }}" class="btn btn-light text-primary fw-bold">
            <i class="fa fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card-body p-4">
        {{-- Form Edit --}}
        <form action="{{ route('mahasiswa.update', $mahasiswa->id_mahasiswa) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-lg-6 col-md-12">
                    <label for="nim" class="form-label fw-bold">NIM</label>
                    <input type="number" name="nim" id="nim"
                        value="{{ old('nim', $mahasiswa->nim) }}"
                        class="form-control" required>
                </div>

                <div class="col-lg-6 col-md-12">
                    <label for="nama" class="form-label fw-bold">Nama</label>
                    <input type="text" name="nama" id="nama"
                        value="{{ old('nama', $mahasiswa->nama) }}"
                        class="form-control" required>
                </div>

                <div class="col-lg-6 col-md-12">
                    <label for="email" class="form-label fw-bold">Email</label>
                    <input type="email" name="email" id="email"
                        value="{{ old('email', $mahasiswa->email) }}"
                        class="form-control" required>
                </div>

                <div class="col-lg-6 col-md-12">
                    <label for="no_hp" class="form-label fw-bold">No HP</label>
                    <input type="number" name="no_hp" id="no_hp"
                        value="{{ old('no_hp', $mahasiswa->no_hp) }}"
                        class="form-control" required>
                </div>

                <div class="col-lg-4 col-md-12">
                    <label for="prodi" class="form-label fw-bold">Prodi</label>
                    <select name="prodi" id="prodi" class="form-select" required>
                        <option value="">-- Pilih Prodi --</option>
                        <option value="Akuntansi" {{ old('prodi', $mahasiswa->prodi) == 'Akuntansi' ? 'selected' : '' }}>Akuntansi</option>
                        <option value="Agroindustri" {{ old('prodi', $mahasiswa->prodi) == 'Agroindustri' ? 'selected' : '' }}>Agroindustri</option>
                        <option value="Teknologi Informasi" {{ old('prodi', $mahasiswa->prodi) == 'Teknologi Informasi' ? 'selected' : '' }}>Teknologi Informasi</option>
                        <option value="Teknologi Otomotif" {{ old('prodi', $mahasiswa->prodi) == 'Teknologi Otomotif' ? 'selected' : '' }}>Teknologi Otomotif</option>
                    </select>
                </div>

                <div class="col-lg-4 col-md-12">
                    <label for="angkatan" class="form-label fw-bold">Angkatan</label>
                    <input type="number" name="angkatan" id="angkatan"
                        value="{{ old('angkatan', $mahasiswa->angkatan) }}"
                        class="form-control" required>
                </div>

                <div class="col-lg-4 col-md-12">
                    <label for="ipk" class="form-label fw-bold">IPK</label>
                    <input type="number" step="0.01" min="0" max="4"
                        name="ipk" id="ipk"
                        value="{{ old('ipk', $mahasiswa->ipk) }}"
                        class="form-control" required>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary rounded-pill px-4">
                    <i class="fa fa-save me-2"></i> Update Data
                </button>
            </div>
        </form>
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
