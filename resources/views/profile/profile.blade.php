@include('layout.header')

<link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">

<style>
    .content-wrapper {
        margin-left: 250px; /* Sesuaikan 250px dengan lebar sidebar Anda */
        transition: margin-left 0.3s ease-in-out;
        padding: 1.5rem; /* Memberi jarak konten dari tepi */
        width: calc(100% - 250px); /* Memastikan lebar konten benar */
        box-sizing: border-box;
    }

    body.sidebar-closed .content-wrapper {
        margin-left: 60px; /* Sesuaikan 60px dengan lebar sidebar saat tertutup */
        width: calc(100% - 60px); /* Lebar konten saat sidebar tertutup */
    }

    /* * 2. MEMPERBAIKI MASALAH FULL-SCREEN (WIDTH & HEIGHT)
     */
    
    /* (Ini dari jawaban saya sebelumnya, memastikan width 100%) */
    .profile-page-container {
         width: 100%;
         /* Pastikan display: flex (dari profile.css) tetap ada */
         display: flex;
    }

    /* JADIKAN .profile-content SEBAGAI FLEX CONTAINER VERTIKAL */
    .profile-content {
        width: 100%; /* (dari jawaban saya sebelumnya) */
        
        /* -- KODE TAMBAHAN UNTUK FIX TINGGI -- */
        display: flex;          /* <-- KUNCI 1: Jadikan flex container */
        flex-direction: column; /* <-- KUNCI 2: Arah vertikal */
        /* flex: 1; sudah ada di profile.css, jadi ini akan stretch */
    }

    /* BUAT .profile-card (area putih) MENGISI .profile-content */
    .profile-card {
        width: 100%;
        box-sizing: border-box; 
        
        /* -- KODE TAMBAHAN UNTUK FIX TINGGI -- */
        flex: 1; /* <-- KUNCI 3: Buat card memanjang ke bawah */
        
        /* Override max-width dari profile.css */
        max-width: none !important; 
    }
</style>


@include('layout.sidebar')

    {{-- 'content-wrapper' adalah class penting yang akan diatur oleh CSS di atas --}}
    <main class="content-wrapper"> 

        <div class="profile-page-container">
            
            {{-- 
              BAGIAN INI DIHAPUS 
              Blok <div class="profile-sidebar">...</div> adalah sumber 
              munculnya teks "PENGATURAN" dan "Data Diri" 
              yang membuat halaman tidak full-width.
            --}}
            
            {{-- Konten profil sekarang akan otomatis mengisi lebar penuh --}}
            <div class="profile-content">
                <div class="profile-card">
                    {{-- Judul "Pengaturan Data Diri" di dalam card tetap ada --}}
                    <h3>Pengaturan Data Diri</h3>
        
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
        
                    @error('msg')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                    @enderror
                    
                    @if(isset($user))
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
        
                        <div class="form-group profile-picture-section">
                            <label>Foto Profil</label>
                            <div class="profile-pic-wrapper">
                                <img id="profilePicPreview" src="{{ $user->avatar ? asset('storage/'. $user->avatar) : asset('storage/avatars/default.png') }}" alt="Foto Profil" onerror="this.src='{{ asset('storage/avatars/default.png') }}'">
                                <label for="avatarInput" class="upload-button">
                                    <i class="fa fa-camera"></i> Ganti Foto
                                </label>
                                <input type="file" id="avatarInput" name="avatar" accept="image/*" onchange="previewImage(event)">
                                @error('avatar') <span class="error-text">{{ $message }}</span> @enderror
                            </div>
                        </div>
        
                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="@error('name') is-invalid @enderror">
                            @error('name') <span class="error-text">{{ $message }}</span> @enderror
                        </div>
        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="@error('email') is-invalid @enderror">
                            @error('email') <span class="error-text">{{ $message }}</span> @enderror
                        </div>
        
                        <div class="form-group">
                            <label for="phone_number">No. Handphone</label>
                            <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" placeholder="+62 812..." class="@error('phone_number') is-invalid @enderror">
                            @error('phone_number') <span class="error-text">{{ $message }}</span> @enderror
                        </div>
        
                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <div class="radio-group">
                                <label>
                                    <input type="radio" name="gender" value="Laki-laki" {{ old('gender', $user->gender) == 'Laki-laki' ? 'checked' : '' }}> Laki-laki
                                </label>
                                <label>
                                    <input type="radio" name="gender" value="Perempuan" {{ old('gender', $user->gender) == 'Perempuan' ? 'checked' : '' }}> Perempuan
                                </label>
                            </div>
                            @error('gender') <span class="error-text">{{ $message }}</span> @enderror
                        </div>
        
                        <div class="form-actions">
                            <button type="button" class="btn btn-secondary" onclick="window.history.back()">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                    @else
                        <p>Data pengguna tidak ditemukan. Pastikan Anda sudah menjalankan migrasi dan memiliki setidaknya satu user di database.</p>
                    @endif
                </div>
                
                {{-- 
                  Script ini sebaiknya dipindah ke layout global (misal: app.blade.php) 
                  agar tidak ter-load di setiap halaman. 
                  Tapi tidak masalah tetap di sini, script toggle sudah benar.
                --}}
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

    </main>
</div>

<script>
    function previewImage(event) {
        if (event.target.files && event.target.files[0]) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('profilePicPreview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>