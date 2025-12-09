{{-- resources/views/profile/profile.blade.php --}}
@include('layout.header')

<link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">

<!-- Cropper.js CSS (CDN) -->
<link href="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.css" rel="stylesheet"/>

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

    /* Profile picture section */
    .profile-picture-section {
        display:flex;
        gap:20px;
        align-items:flex-start;
    }
    .profile-picture-section .left-col {
        min-width: 110px;
        display:flex;
        align-items:flex-start;
        padding-top:6px;
    }

    .profile-pic-wrapper { display:flex; align-items:center; gap:12px; }
    .profile-pic-wrapper img#profilePicPreview {
        width: 96px; height: 96px; border-radius: 50%; object-fit: cover;
        margin-right: 12px; border: 4px solid #fff; box-shadow: 0 4px 12px rgba(2,6,23,0.06);
        flex: 0 0 96px;
        cursor: pointer;
        transition: transform .12s ease;
    }
    .profile-pic-wrapper img#profilePicPreview:hover { transform: scale(1.03); }

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
        .profile-picture-section { flex-direction:column; align-items:flex-start; }
        .profile-picture-section .left-col { min-width:auto; padding-top:0; margin-bottom:6px; }
    }

    /* gaya tombol back */
    .back-button { display:inline-flex; align-items:center; gap:8px; padding:6px 12px; background:#f1f5f9; border-radius:6px; cursor:pointer; color:#334155; margin-bottom:12px; transition:0.15s; }
    .back-button:hover { background:#e2e8f0; }

    /* Preview modal */
    .photo-modal {
        position: fixed;
        inset: 0;
        display: none;
        align-items: center;
        justify-content: center;
        background: rgba(0,0,0,0.6);
        z-index: 99999;
        padding: 20px;
    }
    .photo-modal.active { display: flex; }
    .photo-modal .modal-content {
        max-width: 95%;
        max-height: 95%;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 18px 60px rgba(2,6,23,0.6);
        background: #fff !important;
        position: relative;
        opacity: 1 !important;
        filter: none !important;
        mix-blend-mode: normal !important;
        -webkit-backdrop-filter: none !important;
        backdrop-filter: none !important;
    }
    .photo-modal .modal-content img {
        display:block;
        max-width: 100%;
        max-height: 80vh;
        width: auto;
        height: auto;
        object-fit: contain;
        background: #111;
        opacity: 1 !important;
        filter: none !important;
        mix-blend-mode: normal !important;
    }
    .photo-modal .modal-close-x {
        position: absolute;
        top: 8px;
        right: 8px;
        background: rgba(255,255,255,0.95);
        border-radius: 6px;
        padding: 6px 8px;
        cursor: pointer;
        font-weight: 600;
        border: 1px solid rgba(0,0,0,0.06);
    }

    /* Cropper modal */
    .cropper-modal {
        position: fixed;
        inset: 0;
        display: none;
        align-items: center;
        justify-content: center;
        background: rgba(0,0,0,0.7) !important;
        z-index: 100000 !important;
        padding: 20px;
    }
    .cropper-modal.active { display: flex; }
    .cropper-modal .cropper-content {
        width: min(920px, 96%);
        max-height: 90vh;
        background: #ffffff !important;
        padding: 12px;
        border-radius: 8px;
        box-shadow: 0 18px 60px rgba(2,6,23,0.5) !important;
        opacity: 1 !important;
        position: relative;
        filter: none !important;
        mix-blend-mode: normal !important;
        -webkit-backdrop-filter: none !important;
        backdrop-filter: none !important;
    }
    .cropper-content img {
        display:block;
        max-width:100%;
        height: auto;
        opacity: 1 !important;
        -webkit-user-drag: none;
        user-select: none;
        filter: none !important;
        mix-blend-mode: normal !important;
        background: transparent !important;
    }

    /* toolbar / actions */
    .cropper-toolbar { display:flex; gap:8px; margin-top:8px; justify-content:flex-end; flex-wrap:wrap; }
    .cropper-actions { display:flex; gap:8px; justify-content:flex-end; margin-top:12px; }

    /* STRONG OVERRIDES to dim background but not modal */
    body.modal-open .content-wrapper,
    body.modal-open .header,
    body.modal-open .sidebar,
    body.modal-open #appContent,
    body.modal-open .profile-card {
        transition: none !important;
        opacity: 0.18 !important;
        filter: none !important;
        pointer-events: none !important;
        user-select: none !important;
    }

    body.modal-open .cropper-modal,
    body.modal-open .cropper-modal *,
    body.modal-open .photo-modal,
    body.modal-open .photo-modal * {
        opacity: 1 !important;
        pointer-events: auto !important;
        user-select: auto !important;
        filter: none !important;
    }

    /* MAKE CROP BOX TRANSPARENT (override Cropper.js defaults) */
    .cropper-modal .cropper-container .cropper-view-box {
        box-shadow: 0 0 0 2px rgba(59,130,246,0.95) !important; /* blue frame */
        background: transparent !important;
    }
    .cropper-modal .cropper-container .cropper-face {
        background: transparent !important;
        opacity: 1 !important;
    }
    /* ensure overlay outside crop box not visible */
    .cropper-modal .cropper-container .cropper-modal-overlay,
    .cropper-modal .cropper-container .cropper-modal-overlay * { display: none !important; }

    /* hide any pseudo overlays */
    .cropper-modal::before,
    .photo-modal::before { display: none !important; }

    /* focus ring */
    .cropper-modal .btn:focus, .photo-modal .modal-close-x:focus { outline: 3px solid rgba(59,130,246,0.35); outline-offset: 2px; }
</style>

@include('layout.sidebar')

<main class="content-wrapper" id="appContent">
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

        const labelAvatar = document.querySelector('label[for="avatarInput"]');
        const fileInput = document.getElementById('avatarInput');
        if (labelAvatar && fileInput) {
            labelAvatar.addEventListener('click', function(e){
                e.preventDefault();
                fileInput.click();
            });
        }

        // modal elements
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

        // Lock/unlock body + set aria-hidden/inert on background content
        function lockBodyForModal() {
            document.body.classList.add('modal-open');
            document.body.style.overflow = 'hidden';

            const appContent = document.getElementById('appContent');
            const sidebar = document.querySelector('.sidebar');
            const header = document.querySelector('.header');

            [appContent, sidebar, header].forEach(el => {
                if (!el) return;
                try { el.setAttribute('aria-hidden', 'true'); } catch(e){}
                try { el.inert = true; } catch(e){}
            });
        }
        function unlockBodyForModal() {
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';

            const appContent = document.getElementById('appContent');
            const sidebar = document.querySelector('.sidebar');
            const header = document.querySelector('.header');

            [appContent, sidebar, header].forEach(el => {
                if (!el) return;
                try { el.removeAttribute('aria-hidden'); } catch(e){}
                try { el.inert = false; } catch(e){}
            });
        }

        /* ---------------- Preview modal (X only) ---------------- */
        if (previewImg) {
            previewImg.addEventListener('click', function(){
                if (modalImage) {
                    modalImage.src = previewImg.src;
                    modalImage.alt = previewImg.alt || 'Foto Profil';
                }
                photoModal.classList.add('active');
                photoModal.setAttribute('aria-hidden', 'false');
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
                    photoModal.setAttribute('aria-hidden', 'false');
                    lockBodyForModal();
                    if (photoModalClose) photoModalClose.focus();
                }
            });
        }

        if (photoModalClose) {
            photoModalClose.addEventListener('click', function(){
                photoModal.classList.remove('active');
                photoModal.setAttribute('aria-hidden', 'true');
                unlockBodyForModal();
                if (previewImg) previewImg.focus();
            });
        }

        // Intentionally DO NOT close preview modal on backdrop click or ESC

        /* ---------------- CROPPER ---------------- */
        function openCropperWithFile(file) {
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                cropperImage.src = e.target.result;

                // show modal after src set
                cropperModal.classList.add('active');
                cropperModal.setAttribute('aria-hidden', 'false');
                lockBodyForModal();

                // destroy prior instance
                if (cropperInstance) {
                    try { cropperInstance.destroy(); } catch(e){ }
                    cropperInstance = null;
                }

                // create cropper
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

                // focus the close X so user can cancel quickly
                if (cropperCloseX) cropperCloseX.focus();
            };
            reader.readAsDataURL(file);
        }

        function closeCropperAndCleanup() {
            if (cropperInstance) {
                try { cropperInstance.destroy(); } catch(e){}
                cropperInstance = null;
            }
            cropperModal.classList.remove('active');
            cropperModal.setAttribute('aria-hidden', 'true');
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

        // cropper controls
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

        // Apply crop
        if (applyCropBtn) {
            applyCropBtn.addEventListener('click', function(){
                if (!cropperInstance) return;
                const canvas = cropperInstance.getCroppedCanvas({
                    width: 600,
                    height: 600,
                    imageSmoothingQuality: 'high'
                });

                const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
                avatarCroppedInput.value = dataUrl;
                previewImageFromDataUrl(dataUrl);

                // close and cleanup
                closeCropperAndCleanup();
                fileInput.value = '';
            });
        }

        // Cancel crop (button Batal and X)
        if (cancelCropBtn) cancelCropBtn.addEventListener('click', function(){ closeCropperAndCleanup(); fileInput.value = ''; });
        if (cropperCloseX) cropperCloseX.addEventListener('click', function(){ closeCropperAndCleanup(); fileInput.value = ''; });

        // Do NOT close cropper on backdrop click: remove/ignore any backdrop click handlers
        // (If there exist previous global handlers, they will be overridden by not adding any here)

        // Do not close on Escape — disable Escape behavior for modal/ cropper
        document.addEventListener('keydown', function(e){
            // intentionally ignore Escape to enforce X/Batal/Gunakan Foto only
            if (e.key === 'Escape') {
                e.preventDefault();
                e.stopPropagation();
            }
        });

        // keep original form submission behavior (server handles avatar_cropped or file)
        const profileForm = document.getElementById('profileForm');
        profileForm?.addEventListener('submit', function(e){
            // allow submit as normal
        });
    });
</script>
