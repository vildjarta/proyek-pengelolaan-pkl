<head>
    <meta charset="UTF-8">
    <title>Sistem PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style-pkl.css">
    <link rel="stylesheet" href="/assets/css/style-pkl-jadwal.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style-pkl.css') }}">

</head>

<div class="header">
    <div class="header-left">
        <div class="logo">
            <img src="{{ asset('assets/images/logo-baru.png') }}" alt="Logo PKL JOZZ">
            <span>PKL JOZZ</span>
        </div>
        <i class="fa fa-bars menu-toggle"></i>
    </div>

    <div class="menu-right">
        <a href="#">Ajukan Proposal</a>
        <a href="#">Akademik</a>
        <div class="user-profile-wrapper">
            <div class="user-info">
                <span>Nama User</span>
                <div class="avatar">
                    <img src="https://i.ibb.co/L8f3XnS/user-avatar.png" alt="User Avatar">
                </div>
            </div>
            <div class="profile-dropdown-menu">
                <a href="/profile"><i class="fa fa-user-circle"></i> Profil Saya</a>
                <a href="#"><i class="fa fa-cog"></i> Pengaturan</a>
                <a href="#"><i class="fa fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>
</div>
