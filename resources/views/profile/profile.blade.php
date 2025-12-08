@include('layout.header')

<link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">

<style>
    :root {
        --sidebar-width: 250px;
        --sidebar-collapsed-width: 60px;
        --header-safe-height: 72px;
        --content-vertical-padding: 24px;
        --content-horizontal-padding: 24px;
    }

    .content-wrapper {
        margin-left: var(--sidebar-width);
        transition: margin-left 0.3s ease-in-out;
        padding: var(--content-vertical-padding) var(--content-horizontal-padding);
        padding-top: calc(var(--header-safe-height) + var(--content-vertical-padding));
        width: calc(100% - var(--sidebar-width));
        box-sizing: border-box;
        min-height: calc(100vh - var(--header-safe-height));
    }

    body.sidebar-closed .content-wrapper {
        margin-left: var(--sidebar-collapsed-width);
        width: calc(100% - var(--sidebar-collapsed-width));
    }

    .profile-page-container { width: 100%; display: block; }
    .profile-content { width: 100%; display: block; }

    .profile-card {
        width: calc(100% - 48px);
        max-width: none;
        margin: 0;
        background: #fff;
        border-radius: 8px;
        padding: 1.25rem;
        box-shadow: 0 6px 18px rgba(2,6,23,0.03);
    }

    .profile-card .form-group { margin-bottom: 1.25rem; display:block; }
    .profile-card label { display:block; margin-bottom:0.5rem; font-weight:600; color:#0f172a; }
    .profile-card .form-control {
        display:block;
        width:100%;
        padding:10px 12px;
        border-radius:8px;
        border:1px solid #e6e9ef;
        background:#fff;
        box-sizing:border-box;
    }

    input[readonly] {
        background-color: #e9ecef !important;
        cursor: not-allowed;
        color: #6c757d;
        border-color: #ced4da;
    }

    .profile-pic-wrapper { display:flex; align-items:center; gap:12px; }
    .profile-pic-wrapper img#profilePicPreview {
        width: 96px; height: 96px; border-radius: 50%; object-fit: cover;
        margin-right: 12px; border: 4px solid #fff; box-shadow: 0 4px 12px rgba(2,6,23,0.06);
        flex: 0 0 96px;
    }

    .upload-button {
        padding: 8px 12px; background: #eef2ff; color:#334155; border-radius:8px; cursor:pointer; margin-left:6px;
    }

    .form-actions { display:flex; justify-content:flex-end; gap:12px; margin-top:12px; }

    @media (min-width: 1300px) { .profile-card { width: calc(100% - 80px); } }
    @media (max-width: 900px) {
        :root { --sidebar-width: 60px; --content-horizontal-padding: 12px; }
        .content-wrapper { padding-left: var(--content-horizontal-padding); padding-right: var(--content-horizontal-padding); }
        .profile-card { width: calc(100% - 24px); padding:1rem; border-radius:6px; }
        .profile-pic-wrapper img#profilePicPreview { width:72px; height:72px; flex:0 0 72px; }
    }

    /* gaya tombol back */
    .back-button { display:inline-flex; align-items:center; gap:8px; padding:6px 12px; background:#f1f5f9; border-radius:6px; cursor:pointer; color:#334155; margin-bottom:12px; transition:0.15s; }
    .back-button:hover { background:#e2e8f0; }
</style>

@include('layout.sidebar')

<main class="content-wrapper" id="appContent">
    <div class="profile-page-container">
        <div class="profile-content">
            <div class="profile-card">

                {{-- Tombol kembali: gunakan previousUrl yang dikirim controller bila ada --}}
                @php
                    // previousUrl disediakan oleh controller (bisa null)
                    $previousUrl = $previousUrl ?? null;
                @endphp

                <div id="backButton"
                     class="back-button"
                     data-prev-url="{{ $previousUrl ? e($previousUrl) : '' }}"
                     role="button"
                     aria-label="Kembali"
                     onclick="goBack()">
                    <i class="fa fa-arrow-left"></i> Kembali
                </div>

                <h3 style="margin-top:0; margin-bottom:16px;">Pengaturan Data Diri</h3>
                <hr style="border:none; border-top:1px solid #eef2f6; margin-bottom:18px;">

                @if (session('success'))
                    <div class="alert alert-success" style="margin-bottom:16px;">{{ session('success') }}</div>
                @endif

                @error('msg')
                    <div class="alert alert-danger" style="margin-bottom:16px;">{{ $message }}</div>
                @enderror

                @if(isset($user))
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="form-group profile-picture-section" style="display:flex; align-items:center; gap:20px;">
                        <label style="min-width:110px; margin-top:6px;">Foto Profil</label>

                        <div style="flex:1;">
                            <div class="profile-pic-wrapper">
                                @php
                                    $avatarPath = $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/avatars/default.png');
                                @endphp

                                <img id="profilePicPreview"
                                     src="{{ $avatarPath }}?v={{ $user->updated_at ? $user->updated_at->timestamp : now()->timestamp }}"
                                     alt="Foto Profil"
                                     onerror="this.onerror=null; this.src='{{ asset('storage/avatars/default.png') }}'">

                                <div>
                                    <label for="avatarInput" class="upload-button">
                                        <i class="fa fa-camera"></i> Ganti Foto
                                    </label>
                                    <input type="file" id="avatarInput" name="avatar" accept="image/*" onchange="previewImage(event)" style="display:none;">
                                    @error('avatar') <div class="error-text" style="color:#d9534f;margin-top:8px;">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-control">
                        @error('name') <div class="error-text" style="color:#d9534f;">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email <small style="color:#6b7280;">(Terkunci karena menggunakan Login Google)</small></label>
                        <input type="email" id="email" name="email" value="{{ $user->email }}" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="phone_number">No. Handphone</label>
                        <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" placeholder="+62 812..." class="form-control">
                        @error('phone_number') <div class="error-text" style="color:#d9534f;">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <div class="radio-group" style="display:flex; gap:24px; align-items:center;">
                            <label style="display:flex; align-items:center; gap:8px;">
                                <input type="radio" name="gender" value="Laki-laki" {{ old('gender', $user->gender) == 'Laki-laki' ? 'checked' : '' }}> Laki-laki
                            </label>
                            <label style="display:flex; align-items:center; gap:8px;">
                                <input type="radio" name="gender" value="Perempuan" {{ old('gender', $user->gender) == 'Perempuan' ? 'checked' : '' }}> Perempuan
                            </label>
                        </div>
                        @error('gender') <div class="error-text" style="color:#d9534f;">{{ $message }}</div> @enderror
                    </div>

                    <hr style="border:none; border-top:1px solid #eef2f6; margin:18px 0;">

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="goBack()" style="padding:8px 14px; border-radius:8px;">Batal</button>
                        <button type="submit" class="btn btn-primary" style="padding:8px 14px; border-radius:8px;">Simpan</button>
                    </div>
                </form>
                @else
                    <p>Data pengguna tidak ditemukan. Silakan login kembali.</p>
                @endif

            </div>
        </div>
    </div>
</main>

<script>
    /**
     * goBack():
     * - prioritas 1: gunakan data-prev-url yang diset controller (same-origin)
     * - prioritas 2: history.back() bila ada history
     * - fallback: redirect ke /dashboard
     */
    function goBack() {
        try {
            const backBtn = document.getElementById('backButton');
            if (backBtn) {
                const prev = backBtn.getAttribute('data-prev-url');
                if (prev && prev.trim().length > 0) {
                    // safety: pastikan same origin
                    try {
                        const prevUrl = new URL(prev);
                        if (prevUrl.origin === location.origin) {
                            // direct redirect ke previousUrl
                            window.location.href = prev;
                            return;
                        }
                    } catch (err) {
                        // parsing error -> ignore and fallback
                        console.warn('Invalid prev url:', err);
                    }
                }
            }
        } catch (e) {
            console.warn(e);
        }

        // Fallback ke history.back()
        if (history.length > 1) {
            history.back();
        } else {
            // fallback akhir
            window.location.href = "{{ url('/dashboard') }}";
        }
    }

    // preview image sebelum upload
    function previewImage(event) {
        if (event.target.files && event.target.files[0]) {
            var reader = new FileReader();
            reader.onload = function(){
                document.getElementById('profilePicPreview').src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    }

    // adjust top spacing to avoid header overlap
    document.addEventListener('DOMContentLoaded', function(){
        function adjustContentTopSpacing() {
            const header = document.querySelector('.header');
            const content = document.querySelector('.content-wrapper');
            if (!content) return;
            let headerHeight = 72;
            if (header) headerHeight = header.offsetHeight;
            const extra = 16;
            content.style.paddingTop = (headerHeight + extra) + 'px';
            document.documentElement.style.setProperty('--header-safe-height', headerHeight + 'px');
        }

        adjustContentTopSpacing();
        window.addEventListener('resize', adjustContentTopSpacing);

        const headerEl = document.querySelector('.header');
        if (window.ResizeObserver && headerEl) {
            try {
                const ro = new ResizeObserver(adjustContentTopSpacing);
                ro.observe(headerEl);
            } catch(e){}
        }

        // klik label ganti foto trigger input file
        const labelAvatar = document.querySelector('label[for="avatarInput"]');
        const fileInput = document.getElementById('avatarInput');
        if (labelAvatar && fileInput) {
            labelAvatar.addEventListener('click', function(e){
                e.preventDefault();
                fileInput.click();
            });
        }
    });
</script>
