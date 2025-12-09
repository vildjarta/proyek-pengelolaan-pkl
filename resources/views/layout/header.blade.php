{{-- header.blade.php --}}
<head>
    <meta charset="UTF-8">
    <title>Sistem PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- CSS utama --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style-pkl.css') }}">

    <style>
        .menu-toggle { font-size: 14px; line-height:1; }
        .header .logo span { font-size: 1rem; letter-spacing: .2px; }

        /* Hilangkan outline/blue highlight pada avatar/tap */
        .avatar img { outline: none; -webkit-tap-highlight-color: transparent; }
        .avatar img:focus { outline: none; box-shadow: none; }

        /* Normalisasi teks & fokus pada dropdown items */
        .profile-dropdown-menu .dropdown-item,
        .profile-dropdown-menu .dropdown-item:link,
        .profile-dropdown-menu .dropdown-item:visited {
            text-decoration: none;
            color: var(--color-text-dark);
            font-weight: 500;
        }

        /* Hover sama untuk semua item */
        .profile-dropdown-menu .dropdown-item:hover {
            background: var(--color-light-blue-bg);
            color: var(--color-primary-blue);
        }

        /* Fokus keyboard: tunjukkan ring, tapi hindari auto-styling saat klik */
        .profile-dropdown-menu .dropdown-item:focus-visible {
            background: var(--color-light-blue-bg);
            color: var(--color-primary-blue);
            box-shadow: 0 0 0 3px rgba(91,138,210,0.08);
            outline: none;
        }

        /* Pastikan tidak ada underline / warna visited default */
        .profile-dropdown-menu .dropdown-item { text-decoration: none !important; -webkit-text-decoration-skip: none; }
    </style>
</head>

@php
    $authUser = auth()->user();
    $displayName = $authUser ? ($authUser->name ?? 'User') : 'Guest';
    $avatarPath = ($authUser && $authUser->avatar) ? $authUser->avatar : 'avatars/default.png';
    $version = ($authUser && $authUser->updated_at) ? $authUser->updated_at->timestamp : time();
@endphp

<div class="header" role="banner">
    <div class="header-left">
        <div class="logo" aria-hidden="true">
            <img src="{{ asset('assets/images/logo-baru.png') }}" alt="Logo PKL JOZZ">
            <span>PKL JOZZ</span>
        </div>

        <button class="menu-toggle" id="sidebarToggle" aria-label="Toggle sidebar" title="Toggle sidebar">
            <i class="fa fa-bars" aria-hidden="true"></i>
        </button>
    </div>

    <div class="menu-right" role="navigation" aria-label="Top navigation">
        <a href="#" class="icon-link" title="Notifikasi" aria-label="Notifikasi"><i class="fa fa-bell"></i></a>
        <a href="#" class="icon-link" title="Pesan" aria-label="Pesan"><i class="fa fa-envelope"></i></a>

        <div class="user-profile-wrapper" id="userProfileWrapper">
            <div class="user-info" id="userInfo"
                 role="button"
                 tabindex="0"
                 aria-haspopup="true"
                 aria-expanded="false"
                 aria-controls="profileDropdown">
                <span class="text-link" id="userDisplayName">{{ $displayName }}</span>

                <div class="avatar" aria-hidden="true">
                    <img
                        src="{{ asset('storage/' . $avatarPath) }}?v={{ $version }}"
                        alt="User Avatar"
                        onerror="this.onerror=null; this.src='{{ asset('storage/avatars/default.png') }}';">
                </div>
            </div>

            <div class="profile-dropdown-menu" id="profileDropdown" role="menu" aria-labelledby="userInfo">
                <a href="{{ route('profile.edit') }}" role="menuitem" class="dropdown-item">
                    <span class="icon"><i class="fa fa-user-circle"></i></span>
                    Profil Saya
                </a>

                <a href="#" role="menuitem" class="dropdown-item">
                    <span class="icon"><i class="fa fa-cog"></i></span>
                    Pengaturan
                </a>

                {{-- Logout: gunakan <a> supaya tampilannya sama persis --}}
                <form id="logout-form-header" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
                <a href="#" role="menuitem" id="logoutLinkHeader" class="dropdown-item" aria-label="Logout">
                    <span class="icon"><i class="fa fa-sign-out-alt"></i></span>
                    Logout
                </a>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    const wrapper = document.getElementById('userProfileWrapper');
    const info = document.getElementById('userInfo');
    const dropdown = document.getElementById('profileDropdown');
    const logoutLink = document.getElementById('logoutLinkHeader');
    const logoutForm = document.getElementById('logout-form-header');
    const sidebarToggle = document.getElementById('sidebarToggle');

    if (info && dropdown && wrapper) {
        function toggleDropdown(open) {
            const willOpen = (typeof open === 'boolean') ? open : !wrapper.classList.contains('open');
            if (willOpen) {
                wrapper.classList.add('open');
                info.setAttribute('aria-expanded', 'true');
                // TIDAK auto-focus item => mencegah auto-blue highlight
            } else {
                wrapper.classList.remove('open');
                info.setAttribute('aria-expanded', 'false');
            }
        }

        info.addEventListener('click', function(e){
            e.stopPropagation();
            toggleDropdown();
        });

        info.addEventListener('keydown', function(e){
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                e.stopPropagation();
                toggleDropdown();
            } else if (e.key === 'Escape') {
                toggleDropdown(false);
            }
        });

        document.addEventListener('click', function(e){
            if (!wrapper.contains(e.target)) toggleDropdown(false);
        });

        document.addEventListener('keydown', function(e){
            if (e.key === 'Escape') toggleDropdown(false);
        });

        // biarkan klik di dalam dropdown tidak menutup otomatis
        dropdown.addEventListener('click', function(e){ e.stopPropagation(); });
    }

    // logout link submits form, lalu blur supaya tidak tinggalkan fokus biru
    if (logoutLink && logoutForm) {
        logoutLink.addEventListener('click', function(e){
            e.preventDefault();
            // submit form
            logoutForm.submit();
            // lepaskan fokus agar tidak tampil biru
            try { logoutLink.blur(); } catch (err) {}
        });
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            document.body.classList.toggle('sidebar-closed');
        });
    }
})();
</script>
