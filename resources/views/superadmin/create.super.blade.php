@include('layout.header')
@include('layout.sidebar')

<link rel="stylesheet" href="{{ asset('assets/css/createeditsuper.css') }}">

<div class="main-content-wrapper">
    <div class="content sa-wrapper">

        <div class="sa-header">
            <div>
                <h2 class="sa-title">Tambah Pengguna Baru</h2>
                <a href="{{ route('manage-users.index') }}" class="text-muted text-decoration-none">&larr; Kembali ke daftar</a>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="sa-card p-4">
            <form action="{{ route('manage-users.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control" required>
                    <small class="text-muted"></small>
                </div>
{{-- 
                <div class="mb-3">
                    <label class="form-label fw-bold">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div> --}}

                <div class="mb-3">
                    <label class="form-label fw-bold">Role</label>
                    <select name="role" class="form-control" required>
                        <option value="" disabled selected>-- Pilih Role --</option>

                        {{-- DAFTAR ROLE YANG BENAR (Tanpa Admin, Ada Staff) --}}
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="koordinator">Koordinator</option>
                        <option value="staff">Staff</option>  {{-- Role Baru --}}
                        <option value="dosen_pembimbing">Dosen Pembimbing</option>
                        <option value="dosen_penguji">Dosen Penguji</option>
                        <option value="perusahaan">Perusahaan</option>
                        <option value="ketua_prodi">Ketua Prodi</option>

                    </select>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary px-4">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.querySelector('.menu-toggle');
    const body = document.body;
    const profileWrapper = document.querySelector('.user-profile-wrapper');
    const userinfo = document.querySelector('.user-info');

    if (toggleButton) {
        toggleButton.addEventListener('click', function() {
            body.classList.toggle('sidebar-closed');
        });
    }

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
});
</script>
</div>
