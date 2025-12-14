{{-- resources/views/profile/profile.blade.php --}}
@include('layout.header')

<!-- Stylesheet yang diperlukan (jika punya global, biarkan) -->
<link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">

<!-- Cropper.js CSS (CDN) -->
<link href="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.css" rel="stylesheet"/>

<!-- Perbaikan CSS inline khusus halaman (override jika perlu) -->
<style>
    /* ===============================
       Layout variables (ubah sesuai theme)
       =============================== */
        /* :root {
            --sidebar-width: 285px;
            --sidebar-collapsed-width: 70px;
            --header-height: 64px; 
            --content-vertical-padding: 24px;
            --content-horizontal-padding: 24px;
            --color-primary-blue: #5b8ad2;
        } */

        /* --- Base (jaga tidak ada overflow-y:hidden di sini) --- */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            font-family: 'Poppins', sans-serif;
            background: #f4f7f9;
        }

        /* Header tetap fixed (layout.header di-include)
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            z-index: 13000;
            background: #fff;
            display: flex;
            align-items: center;
            box-shadow: 0 1px 6px rgba(0,0,0,0.06);
        } */

        /* Sidebar: mulai dibawah header, scrollable internal
        .sidebar {
            position: fixed;
            top: var(--header-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--header-height));
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            background: var(--color-primary-blue);
            color: #fff;
            z-index: 12000;
            padding-bottom: 24px;
            box-sizing: border-box;
        } */

        /* Jika sidebar collapsed (body.sidebar-closed) */
        body.sidebar-closed .sidebar {
            width: var(--sidebar-collapsed-width);
        }

        /* Content wrapper: terletak setelah sidebar, juga scrollable internal */
        .content-wrapper {
            position: absolute;
            top: var(--header-height);
            left: var(--sidebar-width);
            right: 0;
            height: calc(100vh - var(--header-height));
            padding: var(--content-vertical-padding) var(--content-horizontal-padding);
            overflow-y: auto;
            box-sizing: border-box;
            transition: left .22s ease, width .22s ease;
            z-index: 11000;
            -webkit-overflow-scrolling: touch;
        }

        /* Jika sidebar collapsed, geser content */
        body.sidebar-closed .content-wrapper {
            left: var(--sidebar-collapsed-width);
        }

        /* Profile card styles (dipertahankan) */
        .profile-page-container { width:100%; }
        .profile-content { width:100%; }
        .profile-card {
            background: #fff;
            border-radius: 8px;
            padding: 1.25rem;
            box-shadow: 0 6px 18px rgba(2,6,23,0.03);
            max-width: 100%;
            margin-bottom: 16px;
        }

        .profile-card .form-group { margin-bottom: 1.25rem; display:block; }
        .profile-card label { display:block; margin-bottom:0.5rem; font-weight:600; color:#0f172a; }
        .profile-card .form-control { display:block; width:100%; padding:10px 12px; border-radius:8px; border:1px solid #e6e9ef; background:#fff; box-sizing:border-box; }

        input[readonly] { background-color: #e9ecef !important; cursor: not-allowed; color:#6c757d; border-color:#ced4da; }

        /* Profile picture */
        .profile-picture-section { display:flex; gap:20px; align-items:flex-start; }
        .profile-picture-section .left-col { min-width:110px; padding-top:6px; }
        .profile-pic-wrapper { display:flex; align-items:center; gap:12px; }
        .profile-pic-wrapper img#profilePicPreview {
            width:96px; height:96px; border-radius:50%; object-fit:cover; margin-right:12px; border:4px solid #fff;
            box-shadow: 0 4px 12px rgba(2,6,23,0.06); cursor:pointer; transition: transform .12s ease;
            flex: 0 0 96px;
        }
        .profile-pic-wrapper img#profilePicPreview:hover { transform: scale(1.03); }
        .upload-button { padding:8px 12px; background:#eef2ff; color:#334155; border-radius:8px; cursor:pointer; margin-left:6px; }

        .form-actions { display:flex; justify-content:flex-end; gap:12px; margin-top:12px; }

        /* Back button */
        .back-button { display:inline-flex; align-items:center; gap:8px; padding:6px 12px; background:#f1f5f9; border-radius:6px; cursor:pointer; color:#334155; margin-bottom:12px; transition:.15s; }
        .back-button:hover { background:#e2e8f0; }

        /* Modal and cropper styles (dipertahankan) */
        .photo-modal, .cropper-modal { position: fixed; inset: 0; display: none; align-items:center; justify-content:center; z-index: 20000; padding: 20px; }
        .photo-modal.active, .cropper-modal.active { display:flex; }
        .photo-modal { background: rgba(0,0,0,0.6); }
        .photo-modal .modal-content { max-width:95%; max-height:95%; border-radius:10px; overflow:hidden; box-shadow:0 18px 60px rgba(2,6,23,0.6); background:#fff; position:relative; }
        .photo-modal .modal-content img { display:block; max-width:100%; max-height:80vh; object-fit:contain; background:#111; }

        .modal-close-x { position:absolute; top:8px; right:8px; background:rgba(255,255,255,0.95); border-radius:6px; padding:6px 8px; cursor:pointer; font-weight:600; border:1px solid rgba(0,0,0,0.06); }

        .cropper-modal { background: rgba(0,0,0,0.7); z-index: 21000; }
        .cropper-modal .cropper-content { width: min(920px, 96%); max-height:90vh; background:#fff; padding:12px; border-radius:8px; box-shadow:0 18px 60px rgba(2,6,23,0.5); overflow:auto; }

        /* Dim background when modal open: controlled with class modal-open */
        body.modal-open {
            overflow: hidden; /* block page scroll while modal open */
        }
        /* But make the modals themselves interactable */
        body.modal-open .photo-modal,
        body.modal-open .cropper-modal { pointer-events: auto; }

        /* Accessibility: ensure sidebar/content focusable */
        .sidebar:focus, .content-wrapper:focus { outline: none; }

        /* Responsive adjustments */
        @media (max-width: 900px) {
            :root { --content-horizontal-padding: 12px; --content-vertical-padding: 16px; }
            .sidebar { transform: translateX(-100%); position: fixed; left:0; }
            body.sidebar-closed .sidebar { transform: translateX(0); }
            .content-wrapper { left: 0; padding: 16px; top: var(--header-height); }
            .profile-pic-wrapper img#profilePicPreview { width:72px; height:72px; flex:0 0 72px; }
            .profile-picture-section { flex-direction: column; align-items:flex-start; }
        }

        /* Minor scrollbar styling */
        .sidebar::-webkit-scrollbar, .content-wrapper::-webkit-scrollbar { width:10px; }
        .sidebar::-webkit-scrollbar-thumb, .content-wrapper::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.08); border-radius:8px; }

    </style>

@include('layout.sidebar')

<main class="content-wrapper" id="appContent" tabindex="-1" role="main" aria-label="Konten utama">
    <div class="profile-page-container">
        <div class="profile-content">
            <div class="profile-card">

                @php
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
                <form id="profileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="form-group profile-picture-section">
                        <div class="left-col">
                            <label style="margin:0; font-weight:700; color:#0f172a;">Foto Profil</label>
                        </div>

                        <div style="flex:1;">
                            <div class="profile-pic-wrapper">
                                @php
                                    $avatarPath = $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/avatars/default.png');
                                @endphp

                                <img id="profilePicPreview"
                                     src="{{ $avatarPath }}?v={{ $user->updated_at ? $user->updated_at->timestamp : now()->timestamp }}"
                                     alt="Foto Profil"
                                     onerror="this.onerror=null; this.src='{{ asset('storage/avatars/default.png') }}'"
                                     tabindex="0"
                                     role="button"
                                     aria-label="Lihat foto profil"
                                >

                                <div>
                                    <label for="avatarInput" class="upload-button" id="avatarLabel">
                                        <i class="fa fa-camera"></i> Ganti Foto
                                    </label>
                                    <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display:none;">
                                    @error('avatar') <div class="error-text" style="color:#d9534f;margin-top:8px;">{{ $message }}</div> @enderror

                                    <input type="hidden" id="avatar_cropped" name="avatar_cropped" value="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KEEP ALL ORIGINAL INPUT FIELDS (NO CHANGES) -->
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
              <h4 style="margin-bottom: 16px; margin-top: 20px;">Keamanan Akun</h4>

<div class="row">
    {{-- LOGIKA: Cek apakah user punya password di database --}}
{{-- LOGIKA: Cek apakah user punya password di database --}}
@if(!empty($user->password) && empty($user->google_id))
        {{-- Skenario A: User Manual (Punya Password) -> Wajib isi password lama --}}
        <div class="col-md-12 mb-3">
            <label for="current_password">Kata Sandi Saat Ini</label>
            <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Masukkan kata sandi lama untuk konfirmasi">
            @error('current_password') 
                <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div> 
            @enderror
        </div>
    @else
        {{-- Skenario B: User Google (Password Kosong) -> Beri info --}}
        <div class="col-md-12 mb-3">
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="fa fa-info-circle me-2"></i> {{-- Saya ganti icon svg dengan fa agar lebih aman jika tidak support svg --}}
                <div>
                    Anda login menggunakan Google. Silakan buat kata sandi baru agar bisa login secara manual (opsional).
                </div>
            </div>
        </div>
    @endif

    <div class="col-md-6 mb-3">
        <label for="new_password">Kata Sandi Baru</label>
        <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Minimal 6 karakter">
        @error('new_password') 
            <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div> 
        @enderror
    </div>
    
    <div class="col-md-6 mb-3">
        <label for="new_password_confirmation">Konfirmasi Kata Sandi Baru</label>
        <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" placeholder="Ulangi kata sandi baru">
    </div>
</div>

<div class="form-actions mt-3">
    <button type="submit" class="btn btn-primary" style="padding:8px 14px; border-radius:8px;">Simpan Perubahan</button>
</div>
                </form>
                @else
                    <p>Data pengguna tidak ditemukan. Silakan login kembali.</p>
                @endif

            </div>
        </div>
    </div>
</main>

<!-- Preview modal (X to close only) -->
<div id="photoModal" class="photo-modal" aria-hidden="true" role="dialog" aria-label="Pratinjau foto">
    <div class="modal-content" role="document">
        <button class="modal-close-x" id="photoModalClose" aria-label="Tutup pratinjau">✕</button>
        <img id="modalImage" src="" alt="Preview Foto Profil">
    </div>
</div>

<!-- Cropper modal (transparent crop box, X to cancel, Batal/Gunakan Foto) -->
<div id="cropperModal" class="cropper-modal" aria-hidden="true" role="dialog" aria-label="Crop Foto Profil">
    <div class="cropper-content" role="document">
        <button class="modal-close-x" id="cropperCloseX" aria-label="Batal mengganti foto">✕</button>

        <div style="max-height:70vh; overflow:auto;">
            <img id="cropperImage" src="" alt="Cropper source" style="max-width:100%; display:block; margin:0 auto;">
        </div>

        <div class="cropper-toolbar" role="toolbar" aria-label="Cropper controls">
            <button id="rotateLeft" class="btn" type="button">⟲</button>
            <button id="rotateRight" class="btn" type="button">⟳</button>
            <button id="zoomIn" class="btn" type="button">+</button>
            <button id="zoomOut" class="btn" type="button">-</button>
            <button id="resetCrop" class="btn" type="button">Reset</button>
            <label style="margin-left:auto; display:flex; gap:8px; align-items:center;">
                <input id="aspectToggle" type="checkbox" checked> Potong Kotak (1:1)
            </label>
        </div>

        <div class="cropper-actions">
            <button id="cancelCrop" class="btn btn-secondary" type="button">Batal</button>
            <button id="applyCrop" class="btn btn-primary" type="button">Gunakan Foto</button>
        </div>
    </div>
</div>

<!-- Cropper.js -->
<script src="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.js"></script>

<!-- Perbaikan JS: handle header/sidebar/content sizes & modal lock -->
<script>
    function goBack() {
        try {
            const backBtn = document.getElementById('backButton');
            if (backBtn) {
                const prev = backBtn.getAttribute('data-prev-url');
                if (prev && prev.trim().length > 0) {
                    try {
                        const prevUrl = new URL(prev);
                        if (prevUrl.origin === location.origin) {
                            window.location.href = prev;
                            return;
                        }
                    } catch (err) {
                        console.warn('Invalid prev url:', err);
                    }
                }
            }
        } catch (e) {
            console.warn(e);
        }

        if (history.length > 1) {
            history.back();
        } else {
            window.location.href = "{{ url('/dashboard') }}";
        }
    }

    function previewImageFromDataUrl(dataUrl) {
        const preview = document.getElementById('profilePicPreview');
        if (preview) preview.src = dataUrl;
        const modalImage = document.getElementById('modalImage');
        if (modalImage) modalImage.src = dataUrl;
    }

    document.addEventListener('DOMContentLoaded', function(){
        // ====================
        // layout sizing helpers
        // ====================
        function updateLayoutSizes() {
            const header = document.querySelector('.header');
            const sidebar = document.querySelector('.sidebar');
            const content = document.querySelector('.content-wrapper');

            const headerHeight = header ? header.offsetHeight : parseInt(getComputedStyle(document.documentElement).getPropertyValue('--header-height')) || 64;
            // set CSS var so CSS uses accurate value
            document.documentElement.style.setProperty('--header-height', headerHeight + 'px');

            if (sidebar) {
                sidebar.style.top = headerHeight + 'px';
                sidebar.style.height = 'calc(100vh - ' + headerHeight + 'px)';
            }
            if (content) {
                content.style.top = headerHeight + 'px';
                content.style.height = 'calc(100vh - ' + headerHeight + 'px)';
                // adjust left based on sidebar state
                const sidebarClosed = document.body.classList.contains('sidebar-closed');
                const leftWidth = sidebarClosed ? getComputedStyle(document.documentElement).getPropertyValue('--sidebar-collapsed-width') : getComputedStyle(document.documentElement).getPropertyValue('--sidebar-width');
                if (leftWidth) content.style.left = leftWidth.trim();
            }
        }

        // initial layout set & on resize
        updateLayoutSizes();
        window.addEventListener('resize', updateLayoutSizes);

        // observe header resize (if header content change) and body class change
        const headerEl = document.querySelector('.header');
        if (window.ResizeObserver && headerEl) {
            try {
                const ro = new ResizeObserver(updateLayoutSizes);
                ro.observe(headerEl);
            } catch (e) { /* ignore */ }
        }
        const bodyObserver = new MutationObserver(updateLayoutSizes);
        bodyObserver.observe(document.body, { attributes: true, attributeFilter: ['class'] });

        // ================
        // modal lock/unlock
        // ================
        window.lockBodyForModal = function() {
            document.body.classList.add('modal-open');
            // mark non-modal areas inert for a11y if supported
            const parts = [document.querySelector('.sidebar'), document.querySelector('.header'), document.querySelector('#appContent')];
            parts.forEach(el => { if (el) { try { el.setAttribute('aria-hidden','true'); el.inert = true; } catch(e) { try { el.setAttribute('aria-hidden','true'); } catch(_){} } }});
        };
        window.unlockBodyForModal = function() {
            document.body.classList.remove('modal-open');
            const parts = [document.querySelector('.sidebar'), document.querySelector('.header'), document.querySelector('#appContent')];
            parts.forEach(el => { if (el) { try { el.removeAttribute('aria-hidden'); el.inert = false; } catch(e) { try { el.removeAttribute('aria-hidden'); } catch(_){} } }});
        };

        // =========================
        // Avatar preview & cropper
        // =========================
        const labelAvatar = document.querySelector('label[for="avatarInput"]');
        const fileInput = document.getElementById('avatarInput');
        if (labelAvatar && fileInput) {
            labelAvatar.addEventListener('click', function(e){
                e.preventDefault();
                fileInput.click();
            });
        }

        const previewImg = document.getElementById('profilePicPreview');
        const photoModal = document.getElementById('photoModal');
        const modalImage = document.getElementById('modalImage');
        const photoModalClose = document.getElementById('photoModalClose');

        const cropperModal = document.getElementById('cropperModal');
        const cropperImage = document.getElementById('cropperImage');
        const cropperCloseX = document.getElementById('cropperCloseX');
        const cancelCropBtn = document.getElementById('cancelCrop');
        const applyCropBtn = document.getElementById('applyCrop');

        const avatarCroppedInput = document.getElementById('avatar_cropped');
        let cropperInstance = null;
        const aspectToggle = document.getElementById('aspectToggle');

        if (previewImg) {
            previewImg.addEventListener('click', function(){
                if (modalImage) {
                    modalImage.src = previewImg.src;
                    modalImage.alt = previewImg.alt || 'Foto Profil';
                }
                photoModal.classList.add('active');
                photoModal.setAttribute('aria-hidden','false');
                lockBodyForModal();
                if (photoModalClose) photoModalClose.focus();
            });
            previewImg.addEventListener('keydown', function(e){
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    if (modalImage) {
                        modalImage.src = previewImg.src;
                        modalImage.alt = previewImg.alt || 'Foto Profil';
                    }
                    photoModal.classList.add('active');
                    photoModal.setAttribute('aria-hidden','false');
                    lockBodyForModal();
                    if (photoModalClose) photoModalClose.focus();
                }
            });
        }

        if (photoModalClose) {
            photoModalClose.addEventListener('click', function(){
                photoModal.classList.remove('active');
                photoModal.setAttribute('aria-hidden','true');
                unlockBodyForModal();
                if (previewImg) previewImg.focus();
            });
        }

        // Cropper open
        function openCropperWithFile(file) {
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                cropperImage.src = e.target.result;

                cropperModal.classList.add('active');
                cropperModal.setAttribute('aria-hidden','false');
                lockBodyForModal();

                if (cropperInstance) {
                    try { cropperInstance.destroy(); } catch(e) {}
                    cropperInstance = null;
                }

                // create cropper instance
                cropperInstance = new Cropper(cropperImage, {
                    viewMode: 1,
                    aspectRatio: aspectToggle && aspectToggle.checked ? 1 : NaN,
                    autoCropArea: 0.8,
                    responsive: true,
                    background: false,
                    movable: true,
                    rotatable: true,
                    zoomable: true,
                    scalable: true,
                    guides: true,
                    center: true,
                });

                if (cropperCloseX) cropperCloseX.focus();
            };
            reader.readAsDataURL(file);
        }

        function closeCropperAndCleanup() {
            if (cropperInstance) {
                try { cropperInstance.destroy(); } catch(e) {}
                cropperInstance = null;
            }
            cropperModal.classList.remove('active');
            cropperModal.setAttribute('aria-hidden','true');
            unlockBodyForModal();
        }

        if (fileInput) {
            fileInput.addEventListener('change', function(e){
                const f = e.target.files && e.target.files[0];
                if (!f) return;
                const maxMB = 4;
                if (f.size > maxMB * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimum ' + maxMB + ' MB.');
                    fileInput.value = '';
                    return;
                }
                openCropperWithFile(f);
            });
        }

        // cropper controls mapping
        const byId = id => document.getElementById(id);
        byId('rotateLeft')?.addEventListener('click', function(){ if (cropperInstance) cropperInstance.rotate(-90); });
        byId('rotateRight')?.addEventListener('click', function(){ if (cropperInstance) cropperInstance.rotate(90); });
        byId('zoomIn')?.addEventListener('click', function(){ if (cropperInstance) cropperInstance.zoom(0.1); });
        byId('zoomOut')?.addEventListener('click', function(){ if (cropperInstance) cropperInstance.zoom(-0.1); });
        byId('resetCrop')?.addEventListener('click', function(){ if (cropperInstance) cropperInstance.reset(); });

        aspectToggle?.addEventListener('change', function() {
            if (!cropperInstance) return;
            if (this.checked) cropperInstance.setAspectRatio(1);
            else cropperInstance.setAspectRatio(NaN);
        });

        if (applyCropBtn) {
            applyCropBtn.addEventListener('click', function(){
                if (!cropperInstance) return;
                const canvas = cropperInstance.getCroppedCanvas({
                    width: 600, height: 600, imageSmoothingQuality: 'high'
                });
                const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
                avatarCroppedInput.value = dataUrl;
                previewImageFromDataUrl(dataUrl);

                closeCropperAndCleanup();
                fileInput.value = '';
            });
        }

        if (cancelCropBtn) cancelCropBtn.addEventListener('click', function(){ closeCropperAndCleanup(); fileInput.value = ''; });
        if (cropperCloseX) cropperCloseX.addEventListener('click', function(){ closeCropperAndCleanup(); fileInput.value = ''; });

        // Disable ESC close to enforce X/Batal/Gunakan Foto only (per spec)
        document.addEventListener('keydown', function(e){
            if (e.key === 'Escape') {
                e.preventDefault();
                e.stopPropagation();
            }
        });

        // keep form submit as-is (server handles avatar_cropped)
        const profileForm = document.getElementById('profileForm');
        if (profileForm) {
            profileForm.addEventListener('submit', function(e){
                /* submit normally - server handles */
            });
        }
    });
</script>
