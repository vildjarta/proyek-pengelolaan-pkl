<head>
    <meta charset="UTF-8">
    <title>Sistem PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style-pkl.css') }}">
    <style>
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.6rem 1rem;
            background: #fff;
            border-bottom: 1px solid rgba(0,0,0,0.06);
        }
        .header .logo { display:flex; align-items:center; gap:0.6rem; font-weight:700; color:#0f172a; }
        .header .logo img{ height:34px; width:auto; display:block }
        .header-left { display:flex; align-items:center; gap:0.75rem }
        .menu-right { display:flex; align-items:center; gap:0.75rem }
        .menu-right a.icon-link { color: #374151; padding:6px 8px; border-radius:6px; text-decoration:none }
        .menu-right a.icon-link:hover { background: rgba(15,23,42,0.03) }
        .user-profile-wrapper { position:relative }
        .user-info {
            display:flex;
            align-items:center;
            gap:0.6rem;
            cursor:pointer;
            user-select:none;
        }
        .user-info .text-link { font-weight:600; color:#111827; }
        .user-info .avatar img{ height:36px; width:36px; border-radius:50%; object-fit:cover; display:block }
        .profile-dropdown-menu {
            position:absolute;
            right:0;
            top:calc(100% + 8px);
            min-width:200px;
            background:#fff;
            border:1px solid rgba(0,0,0,0.08);
            box-shadow:0 6px 18px rgba(2,6,23,0.08);
            display:none;
            border-radius:8px;
            overflow:hidden;
            z-index:999;
        }
        .profile-dropdown-menu a, .profile-dropdown-menu button {
            display:flex;
            align-items:center;
            gap:0.6rem;
            padding:8px 14px;
            color:#0f172a;
            text-decoration:none;
            background:transparent;
            border:0;
            width:100%;
            text-align:left;
            font-size:0.95rem;
        }
        .profile-dropdown-menu a:hover, .profile-dropdown-menu button:hover{ background:#f8fafc }
        .user-profile-wrapper.open .profile-dropdown-menu{ display:block }
        /* small visual polish */
        .profile-dropdown-menu .icon { width:20px; display:inline-flex; justify-content:center }
        @media (max-width:720px){
            .header { padding:0.5rem }
            .menu-right a.text-link{ display:none }
        }
    </style>
</head>

@php
    $authUser = auth()->user();
    $displayName = $authUser ? ($authUser->name ?? 'User') : 'Guest';
    $avatarPath = ($authUser && $authUser->avatar) ? $authUser->avatar : 'avatars/default.png';
    $version = ($authUser && $authUser->updated_at) ? $authUser->updated_at->timestamp : time();
@endphp

<div class="header">
    <div class="header-left">
        <div class="logo">
            <img src="{{ asset('assets/images/logo-baru.png') }}" alt="Logo PKL JOZZ">
            <span>PKL JOZZ</span>
        </div>
        <button class="fa fa-bars menu-toggle" aria-label="Toggle menu"></button>
    </div>

    <div class="menu-right">
        <a href="#" class="icon-link" title="Notifikasi"><i class="fa fa-bell"></i></a>
        <a href="#" class="icon-link" title="Pesan"><i class="fa fa-envelope"></i></a>

        <div class="user-profile-wrapper" id="userProfileWrapper">
            {{-- clickable area: nama + avatar --}}
            <div class="user-info" id="userInfo"
                 role="button"
                 tabindex="0"
                 aria-haspopup="true"
                 aria-expanded="false"
                 aria-controls="profileDropdown">
                <span class="text-link">{{ $displayName }}</span>

                <div class="avatar" aria-hidden="true">
                    <img
                        src="{{ asset('storage/' . $avatarPath) }}?v={{ $version }}"
                        alt="User Avatar"
                        onerror="this.onerror=null; this.src='{{ asset('storage/avatars/default.png') }}';">
                </div>
            </div>

            <div class="profile-dropdown-menu" id="profileDropdown" role="menu" aria-labelledby="userInfo">
                <a href="{{ route('profile.edit') }}" role="menuitem">
                    <span class="icon"><i class="fa fa-user-circle"></i></span>
                    Profil Saya
                </a>

                <a href="#" role="menuitem">
                    <span class="icon"><i class="fa fa-cog"></i></span>
                    Pengaturan
                </a>

                {{-- logout form (ubah route jika bukan 'logout') --}}
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
                <button type="button" id="logoutButton" role="menuitem" aria-label="Logout">
                    <span class="icon"><i class="fa fa-sign-out-alt"></i></span>
                    Logout
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    (function(){
        const wrapper = document.getElementById('userProfileWrapper');
        const info = document.getElementById('userInfo');
        const dropdown = document.getElementById('profileDropdown');
        const logoutBtn = document.getElementById('logoutButton');

        if (!info || !dropdown || !wrapper) return;

        // Toggle function
        function toggleDropdown(open) {
            const willOpen = (typeof open === 'boolean') ? open : !wrapper.classList.contains('open');
            if (willOpen) {
                wrapper.classList.add('open');
                info.setAttribute('aria-expanded', 'true');
                // focus first item for accessibility
                const firstItem = dropdown.querySelector('[role="menuitem"]');
                if (firstItem) firstItem.focus();
            } else {
                wrapper.classList.remove('open');
                info.setAttribute('aria-expanded', 'false');
            }
        }

        // Click on user-info opens/closes dropdown. Stop propagation to avoid document click handler.
        info.addEventListener('click', function(e){
            e.stopPropagation();
            toggleDropdown();
        });

        // Allow keyboard: Enter or Space toggles dropdown
        info.addEventListener('keydown', function(e){
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                e.stopPropagation();
                toggleDropdown();
            } else if (e.key === 'Escape') {
                toggleDropdown(false);
            }
        });

        // Clicking outside closes dropdown
        document.addEventListener('click', function(e){
            if (!wrapper.contains(e.target)) {
                toggleDropdown(false);
            }
        });

        // Close on Escape globally
        document.addEventListener('keydown', function(e){
            if (e.key === 'Escape') toggleDropdown(false);
        });

        // Prevent clicks inside dropdown from closing (stop propagation)
        dropdown.addEventListener('click', function(e){
            e.stopPropagation();
        });

        // Logout button submits hidden form (or change behavior if needed)
        if (logoutBtn) {
            logoutBtn.addEventListener('click', function(e){
                e.preventDefault();
                // submit logout form if route exists, otherwise fallback to alert
                const form = document.getElementById('logout-form');
                if (form) form.submit();
                else alert('Logout route not configured.');
            });
        }
    })();
</script>
