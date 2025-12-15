{{-- header.blade.php --}}
<head>
    <meta charset="UTF-8">
    <title>Sistem PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- CSS utama --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style-pkl.css') }}">

    {{-- Google Fonts (Poppins) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* === TAMBAHAN: Global Font Setup === */
        body, .header, .dropdown-item, .logo span, .text-link {
            font-family: 'Poppins', sans-serif !important;
        }

        /* === TAMBAHAN: Styling Header === */
        .header {
            background: #ffffff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid #eef2f6;
        }

        .menu-toggle { font-size: 14px; line-height:1; color: #555; }
        
        /* Modifikasi Teks Logo */
        .header .logo span { 
            font-size: 1.2rem; 
            letter-spacing: .5px;
            font-weight: 700; 
            color: #black; 
            text-transform: uppercase;
        }

        /* === BARU: Modifikasi Gambar Logo Header (Agar Bulat) === */
        .header .logo img {
            border-radius: 50%; /* Kunci agar bulat */
            width: 40px;        /* Ukuran ditetapkan agar rasio kotak */
            height: 40px;       /* Sama dengan width */
            object-fit: cover;  /* Agar gambar pas di dalam lingkaran */
            margin-right: 10px; /* Jarak ke teks "PKL JOZZ" */
            border: 1px solid #e6f0fa; /* Opsional: border tipis agar rapi */
        }

        /* Hilangkan outline/blue highlight pada avatar/tap */
        .avatar img { 
            outline: none; 
            -webkit-tap-highlight-color: transparent; 
            object-fit: cover; 
            border-radius: 50%;
            border: 2px solid #e6f0fa; 
            transition: transform 0.2s;
        }
        .avatar img:focus { outline: none; box-shadow: none; }
        .avatar img:hover { transform: scale(1.05); }

        /* Nama User */
        #userDisplayName {
            font-weight: 600;
            color: #444;
        }

        /* Normalisasi teks & fokus pada dropdown items */
        .profile-dropdown-menu {
            min-width: 200px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            overflow: hidden;
            margin-top: 10px;
        }

        .profile-dropdown-menu .dropdown-item,
        .profile-dropdown-menu .dropdown-item:link,
        .profile-dropdown-menu .dropdown-item:visited {
            text-decoration: none;
            color: #555;
            font-weight: 500;
            padding: 12px 20px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
        }

        /* Hover sama untuk semua item */
        .profile-dropdown-menu .dropdown-item:hover {
            background: #f0f7ff;
            color: #769FCD;
            padding-left: 25px;
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
        .profile-dropdown-menu .icon { 
            width: 28px; 
            display:inline-flex; 
            align-items:center; 
            justify-content:center; 
            margin-right:8px; 
            color:inherit; 
        }

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
            {{-- Gambar logo ini sekarang akan tampil bulat karena style CSS di atas --}}
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