<head>
    <meta charset="UTF-8">
    <title>Sistem PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style-pkl.css') }}">
    
    {{-- Style Anda --}}
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
        .header .logo {
            display:flex;
            align-items:center;
            gap:0.6rem;
            font-weight:700;
            color:#0f172a;
        }
        .header .logo img{ height:34px; width:auto; display:block }
        .header-left { display:flex; align-items:center; gap:0.75rem }
        .menu-right { display:flex; align-items:center; gap:0.75rem }
        .menu-right a.icon-link { color: #374151; padding:6px 8px; border-radius:6px; text-decoration:none }
        .menu-right a.icon-link:hover { background: rgba(15,23,42,0.03) }
        .user-profile-wrapper { position:relative }
        .user-info { display:flex; align-items:center; gap:0.6rem; cursor:pointer }
        .user-info .avatar img{ height:36px; width:36px; border-radius:50%; object-fit:cover }
        .profile-dropdown-menu { position:absolute; right:0; top:calc(100% + 8px); min-width:180px; background:#fff; border:1px solid rgba(0,0,0,0.08); box-shadow:0 6px 18px rgba(2,6,23,0.08); display:none; border-radius:8px; overflow:hidden }
        .profile-dropdown-menu a{ display:block; padding:8px 12px; color:#0f172a; text-decoration:none }
        .profile-dropdown-menu a:hover{ background:#f8fafc }
        .user-profile-wrapper.active .profile-dropdown-menu{ display:block }
        @media (max-width:720px){
            .header { padding:0.5rem }
            .menu-right a.text-link{ display:none }
        }
    </style>

</head>

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

        <div class="user-profile-wrapper">
            <div class="user-info">
                
                <span class="text-link">Ahmad Khairi</span>
                
                <div class="avatar">
                    {{-- 
                      PERUBAHAN DI SINI:
                      Link 'ibb.co' diganti dengan path gambar default 
                      dari file profile.blade.php Anda
                    --}}
                    <img src="{{ asset('storage/avatars/default.png') }}" alt="User Avatar">
                </div>
            </div>
            <div class="profile-dropdown-menu">
                <a href="/profile"><i class="fa fa-user-circle"></i> Profil Saya</a>
                <a href="#"><i class="fa fa-cog"></i> Pengaturan</a>
                
                <a href="#" onclick="event.preventDefault(); alert('Fungsi logout akan diimplementasikan nanti');">
                    <i class="fa fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
        
    </div>
</div>