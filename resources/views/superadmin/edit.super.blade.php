@include('layout.header')
@include('layout.sidebar')

<link rel="stylesheet" href="{{ asset('assets/css/createeditsuper.css') }}">

<div class="main-content-wrapper">
    <div class="content sa-wrapper">
        
        <div class="sa-header">
            <div>
                <h2 class="sa-title">Edit Pengguna: {{ $user->name }}</h2>
                <a href="{{ route('manage-users.index') }}" class="text-muted text-decoration-none">&larr; Kembali ke daftar</a>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
            </div>
        @endif

        <div class="sa-card p-4">
            {{-- Form Edit Mengarah ke Route Update --}}
            <form action="{{ route('manage-users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Role</label>
                    <select name="role" class="form-control" required>
                        {{-- UPDATE ARRAY DI SINI: Hapus 'admin', Tambah 'staff' --}}
                        @foreach(['mahasiswa', 'koordinator', 'staff', 'dosen_pembimbing', 'dosen_penguji', 'perusahaan', 'ketua_prodi'] as $role)
                            <option value="{{ $role }}" {{ $user->role == $role ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $role)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3 bg-light p-3 rounded">
                    <label class="form-label fw-bold">Ubah Password (Opsional)</label>
                    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary px-4">Update Data</button>
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