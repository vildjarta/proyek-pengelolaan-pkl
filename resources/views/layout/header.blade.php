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

        /* Fokus keyboard: tunjukkan ring */
        .profile-dropdown-menu .dropdown-item:focus-visible {
            background: var(--color-light-blue-bg);
            color: var(--color-primary-blue);
            box-shadow: 0 0 0 3px rgba(91,138,210,0.08);
            outline: none;
        }

        /* Pastikan tidak ada underline / warna visited default */
        .profile-dropdown-menu .dropdown-item { text-decoration: none !important; -webkit-text-decoration-skip: none; }

        /* Slight spacing for icon inside dropdown */
        .profile-dropdown-menu .icon { width: 28px; display:inline-flex; align-items:center; justify-content:center; margin-right:8px; color:inherit; }

        /* Ensure dropdown menu aligns under the user-info nicely */
        .profile-dropdown-menu { min-width: 190px; }
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
        {{-- NOTE: removed notification & message icons as requested --}}

        <div class="user-profile-wrapper" id="userProfileWrapper">
            <div class="user-info" id="userInfo"
                 role="button"
                 tabindex="0"
                 aria-haspopup="true"
                 aria-expanded="false"
                 aria-controls="profileDropdown"
                 aria-label="Menu pengguna">
                <span class="text-link" id="userDisplayName">{{ $displayName }}</span>

                <div class="avatar" aria-hidden="true">
                    <img
                        src="{{ asset('storage/' . $avatarPath) }}?v={{ $version }}"
                        alt="User Avatar"
                        onerror="this.onerror=null; this.src='{{ asset('storage/avatars/default.png') }}';">
                </div>
            </div>

            <div class="profile-dropdown-menu" id="profileDropdown" role="menu" aria-labelledby="userInfo" tabindex="-1">
                {{-- Profil Saya --}}
                <a href="{{ route('profile.edit') }}" role="menuitem" class="dropdown-item" tabindex="0">
                    <span class="icon" aria-hidden="true"><i class="fa fa-user-circle"></i></span>
                    Profil Saya
                </a>

                {{-- Logout: gunakan <a> supaya tampilannya sama persis --}}
                <form id="logout-form-header" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
                <a href="#" role="menuitem" id="logoutLinkHeader" class="dropdown-item" aria-label="Logout" tabindex="0">
                    <span class="icon" aria-hidden="true"><i class="fa fa-sign-out-alt"></i></span>
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
        function openDropdown() {
            wrapper.classList.add('open');
            info.setAttribute('aria-expanded', 'true');
            // show dropdown (css controls display via .open)
        }
        function closeDropdown() {
            wrapper.classList.remove('open');
            info.setAttribute('aria-expanded', 'false');
        }
        function toggleDropdown() {
            if (wrapper.classList.contains('open')) closeDropdown();
            else openDropdown();
        }

        // click to toggle
        info.addEventListener('click', function(e){
            e.stopPropagation();
            toggleDropdown();
        });

        // keyboard support for Enter, Space, ArrowDown to open then focus first item
        info.addEventListener('keydown', function(e){
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                e.stopPropagation();
                toggleDropdown();
                // focus first item if opened
                if (wrapper.classList.contains('open')) {
                    const first = dropdown.querySelector('.dropdown-item');
                    if (first) first.focus();
                }
            } else if (e.key === 'ArrowDown') {
                e.preventDefault();
                openDropdown();
                const first = dropdown.querySelector('.dropdown-item');
                if (first) first.focus();
            } else if (e.key === 'Escape') {
                closeDropdown();
                info.focus();
            }
        });

        // close when clicking outside
        document.addEventListener('click', function(e){
            if (!wrapper.contains(e.target)) closeDropdown();
        });

        // close on ESC globally
        document.addEventListener('keydown', function(e){
            if (e.key === 'Escape') closeDropdown();
        });

        // prevent clicks inside dropdown from closing it (so user can click items)
        dropdown.addEventListener('click', function(e){ e.stopPropagation(); });
    }

    // logout link submits form, then blur to avoid focus outline
    if (logoutLink && logoutForm) {
        logoutLink.addEventListener('click', function(e){
            e.preventDefault();
            logoutForm.submit();
            try { logoutLink.blur(); } catch (_) {}
        });
    }

    // sidebar toggle: toggle collapsed class on body
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            document.body.classList.toggle('sidebar-closed');
        });
    }
})();
</script>
