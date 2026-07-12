<script>
    if (localStorage.getItem('theme') === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
    }
</script>

@php
    $pendingCount = 0;
    if(Auth::check()) {
        // HANYA menghitung pesanan yang berstatus 'ditolak' (butuh upload ulang)
        $pendingTravel = \App\Models\PesananTravel::where('user_id', Auth::id())
                        ->where('status_pesanan', 'ditolak')->count();
                        
        $pendingKargo = \App\Models\PesananKargo::where('user_id', Auth::id())
                        ->where('status_pesanan', 'ditolak')->count();
                        
        $pendingCount = $pendingTravel + $pendingKargo;
    }
@endphp

<header class="main-header">
    <div class="header-container">
        <a href="{{ url('/') }}" class="logo-link">
            <img src="{{ asset('public/assets/img/LOGO.png') }}" alt="Buana Berlian" class="logo-img">
        </a>

        <nav class="nav-menu" id="nav-menu">
            <ul class="nav-list">
                <li class="nav-item"><a href="{{ url('/') }}" class="nav-link {{ Request::is('/') ? 'active' : '' }}">Beranda</a></li>
                <li class="nav-item"><a href="{{ url('/layanan') }}" class="nav-link {{ Request::is('layanan') ? 'active' : '' }}">Pesan Layanan</a></li>
                
                {{-- LOGIKA NOTIFIKASI TIKET BUTUH TINDAKAN --}}
                @php
                    $pendingCount = 0;
                    if(Auth::check()) {
                        $pendingTravel = \App\Models\PesananTravel::where('user_id', Auth::id())
                                        ->whereIn('status_pesanan', ['menunggu_verifikasi', 'ditolak'])->count();
                        $pendingKargo = \App\Models\PesananKargo::where('user_id', Auth::id())
                                        ->whereIn('status_pesanan', ['menunggu_verifikasi', 'ditolak'])->count();
                        $pendingCount = $pendingTravel + $pendingKargo;
                    }
                @endphp
                
                <li class="nav-item">
                    <a href="{{ url('/cek-tiket') }}" class="nav-link {{ Request::is('cek-tiket') ? 'active' : '' }}">
                        Cek Tiket
                        @if($pendingCount > 0)
                            <span class="badge-notif-header">{{ $pendingCount }}</span>
                        @endif
                    </a>
                </li>
                
                <li class="nav-item"><a href="{{ url('/tentang-kami') }}" class="nav-link {{ Request::is('tentang-kami') ? 'active' : '' }}">Tentang Kami</a></li>
                
                @auth
                    <li class="nav-item mobile-only"><hr style="border-top:1px solid var(--border-color); margin: 10px 20px;"></li>
                    <li class="nav-item mobile-only"><a href="{{ route('akun.pusat') }}" class="nav-link" style="color:var(--p-color); font-weight:700;"><i class="bi bi-person"></i> Pusat Akun</a></li>
                    
                    <li class="nav-item mobile-only">
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link" style="color:#dc3545;">
                            <i class="bi bi-box-arrow-right"></i> Keluar
                        </a>
                    </li>
                @else
                    <li class="nav-item mobile-only"><a href="{{ route('login') }}" class="btn-login login-mobile">Masuk / Daftar</a></li>
                @endauth
            </ul>
        </nav>

        <div class="header-actions">
            <button class="theme-toggle-btn" id="theme-toggle" title="Ganti Mode">
                <i class="bi bi-moon-stars-fill"></i>
            </button>
            
            @auth
                <div class="user-dropdown">
                    <button class="btn-user-profile" id="profile-dropdown-btn">
                        @php
                            $words = explode(' ', Auth::user()->name);
                            $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                            $colors = ['bg-primary', 'bg-success', 'bg-danger', 'bg-warning text-dark', 'bg-info text-dark', 'bg-secondary'];
                            $avatarColor = $colors[Auth::user()->id % count($colors)];
                        @endphp

                        @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/app/public/' . Auth::user()->avatar) }}" alt="User" class="user-avatar-header">
                        @else
                            <div class="{{ $avatarColor }}" style="width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: white; font-size: 0.8rem; margin-right: 8px;">{{ $initials }}</div>
                        @endif
                        <span>{{ Auth::user()->name }}</span>
                    </button>
                    <div class="dropdown-menu" id="profile-dropdown-menu">
                        <div class="dropdown-header">
                            <strong>{{ Auth::user()->name }}</strong>
                            <span>{{ Auth::user()->status_mahasiswa === 'terverifikasi' ? 'Mahasiswa' : 'Pelanggan Reguler' }}</span>
                        </div>
                        <a href="{{ route('akun.pusat') }}"><i class="bi bi-person"></i> Pusat Akun</a>
                        
                        <hr>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #dc3545;">
                            <i class="bi bi-box-arrow-right"></i> Keluar
                        </a>
                        
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn-login-desktop">
                    <i class="bi bi-person-circle"></i> Login
                </a>
            @endauth

            <button class="hamburger-btn" id="mobile-menu-toggle" aria-label="Toggle Menu" style="position: relative;">
                <i class="bi bi-list"></i>
                {{-- Munculkan titik merah di Hamburger Menu jika ada notif --}}
                @if($pendingCount > 0)
                    <span class="badge-notif-mobile">{{ $pendingCount }}</span>
                @endif
            </button>
        </div>
    </div>
</header>

<style>
    /* =========================================================
       1. GLOBAL VARIABLES & DARK MODE CONFIG
       ========================================================= */
    :root {
        --p-color: #483d8b;
        --p-hover: #352d6b;
        --accent-gold: #d4af37;
        --bg-color: #f8f9fa;      
        --text-main: #333333;     
        --text-muted: #666666;    
        --card-bg: #ffffff;       
        --nav-bg: #ffffff;        
        --border-color: #eeeeee;
    }

    /* Dark Mode Overrides (Berlaku global dari html) */
    [data-theme="dark"] body {
        --bg-color: #121212;
        --text-main: #e0e0e0;
        --text-muted: #aaaaaa;
        --card-bg: #1e1e1e;
        --nav-bg: #1a1a1a;
        --border-color: #333333;
        --p-color: #d4af37;
    }

    body {
        background-color: var(--bg-color);
        background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%239C92AC' fill-opacity='0.07' fill-rule='evenodd'%3E%3Ccircle cx='3' cy='3' r='1'/%3E%3Ccircle cx='13' cy='13' r='1'/%3E%3C/g%3E%3C/svg%3E");
        background-attachment: fixed; 
        color: var(--text-main);
    }

    [data-theme="dark"] body {
        background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.03' fill-rule='evenodd'%3E%3Ccircle cx='3' cy='3' r='1'/%3E%3Ccircle cx='13' cy='13' r='1'/%3E%3C/g%3E%3C/svg%3E");
    }

    /* =========================================================
       2. HEADER STYLES
       ========================================================= */
    .main-header { position: fixed; top: 0; left: 0; width: 100%; z-index: 1000; background-color: var(--nav-bg); box-shadow: 0 2px 10px rgba(0,0,0,0.05); transition: background-color 0.3s, border-color 0.3s; height: 80px; display: flex; align-items: center; border-bottom: 1px solid var(--border-color); }
    .header-container { width: 100%; max-width: 1200px; margin: 0 auto; padding: 0 5%; display: flex; justify-content: space-between; align-items: center; }
    .logo-img { height: 50px; width: auto; object-fit: contain; transition: 0.3s; }
    
    [data-theme="dark"] .logo-img { filter: brightness(0) invert(1); }
    
    .nav-list { display: flex; gap: 30px; list-style: none; margin: 0; padding: 0; align-items: center; }
    .nav-link { text-decoration: none; color: var(--text-main); font-weight: 500; font-size: 0.95rem; transition: color 0.3s; position: relative; }
    .nav-link:hover, .nav-link.active { color: var(--accent-gold) !important; }
    .nav-link::after { content: ''; position: absolute; width: 0; height: 2px; bottom: -5px; left: 0; background-color: var(--accent-gold); transition: width 0.3s; }
    .nav-link:hover::after, .nav-link.active::after { width: 100%; }
    
    .header-actions { display: flex; align-items: center; gap: 15px; }
    .theme-toggle-btn { background: none; border: none; font-size: 1.25rem; cursor: pointer; color: var(--text-main); transition: transform 0.3s, color 0.3s; display: flex; align-items: center; padding: 5px; }
    .theme-toggle-btn:hover { transform: rotate(15deg); color: var(--accent-gold); }
    
    .btn-login-desktop { background-color: var(--p-color); color: white !important; padding: 8px 25px; border-radius: 50px; text-decoration: none; font-weight: 600; border: 2px solid var(--p-color); transition: all 0.3s ease; display: flex; align-items: center; gap: 8px; }
    .btn-login-desktop:hover { background-color: transparent !important; color: var(--p-color) !important; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(72, 61, 139, 0.2); }
    
    [data-theme="dark"] .btn-login-desktop { background-color: var(--accent-gold); border-color: var(--accent-gold); color: #000 !important; }
    [data-theme="dark"] .btn-login-desktop:hover { background-color: transparent !important; color: var(--accent-gold) !important; box-shadow: 0 5px 15px rgba(212, 175, 55, 0.2); }

    .hamburger-btn { display: none; }
    .mobile-only { display: none; }

    /* DROPDOWN USER PROFILE */
    .user-dropdown { position: relative; display: inline-block; }
    .btn-user-profile { display: flex; align-items: center; background: transparent; border: 1px solid var(--border-color); padding: 5px 15px 5px 5px; border-radius: 30px; cursor: pointer; color: var(--text-main); font-weight: 700; transition: 0.3s; font-size: 0.9rem; }
    .btn-user-profile:hover, .btn-user-profile.active { border-color: var(--p-color); background: rgba(72, 61, 139, 0.05); }
    
    [data-theme="dark"] .btn-user-profile:hover, [data-theme="dark"] .btn-user-profile.active { border-color: var(--accent-gold); background: rgba(212, 175, 55, 0.1); }
    
    .user-avatar-header { width: 30px; height: 30px; border-radius: 50%; object-fit: cover; margin-right: 8px; }
    
    .dropdown-menu { position: absolute; right: 0; top: 130%; background: var(--card-bg); min-width: 220px; border-radius: 16px; box-shadow: 0 15px 40px rgba(0,0,0,0.1); border: 1px solid var(--border-color); opacity: 0; visibility: hidden; transform: translateY(10px); transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1); z-index: 1000; overflow: hidden; }
    [data-theme="dark"] .dropdown-menu { box-shadow: 0 15px 40px rgba(0,0,0,0.5); }
    
    .dropdown-menu.show { opacity: 1; visibility: visible; transform: translateY(0); top: 115%; }

    .dropdown-header { padding: 15px 20px; background: rgba(72, 61, 139, 0.05); border-bottom: 1px solid var(--border-color); }
    [data-theme="dark"] .dropdown-header { background: rgba(212, 175, 55, 0.05); }
    
    .dropdown-header strong { display: block; font-size: 1rem; color: var(--text-main); }
    .dropdown-header span { display: block; font-size: 0.8rem; color: var(--text-muted); margin-top: 2px; }
    
    .dropdown-menu a { display: flex; align-items: center; gap: 10px; padding: 12px 20px; color: var(--text-main); text-decoration: none; font-weight: 600; font-size: 0.9rem; transition: 0.2s; }
    .dropdown-menu a:hover { background: rgba(72, 61, 139, 0.05); color: var(--p-color); padding-left: 25px; }
    
    [data-theme="dark"] .dropdown-menu a:hover { background: rgba(212, 175, 55, 0.1); color: var(--accent-gold); }
    
    .dropdown-menu hr { border: none; border-top: 1px solid var(--border-color); margin: 0; }

    @media (max-width: 991px) {
        .hamburger-btn { display: flex; background: none; border: none; font-size: 1.8rem; color: var(--text-main); cursor: pointer; }
        .btn-login-desktop, .user-dropdown { display: none; }
        .nav-menu { position: absolute; top: 80px; left: 0; width: 100%; background-color: var(--nav-bg); box-shadow: 0 10px 20px rgba(0,0,0,0.1); padding: 20px 0; flex-direction: column; transform: translateY(-150%); transition: transform 0.4s ease-in-out; z-index: -1; border-bottom: 1px solid var(--border-color); }
        .nav-menu.show { transform: translateY(0); } 
        .nav-list { flex-direction: column; gap: 5px; text-align: center; }
        .nav-link { font-size: 1.1rem; padding: 12px; display: block; }
        .mobile-only { display: block; margin-top: 5px; } 
        .login-mobile { display: inline-block; background: var(--p-color); color: white !important; padding: 10px 40px; border-radius: 30px; font-weight: 700; }
        
        [data-theme="dark"] .login-mobile { background-color: var(--accent-gold); color: #000 !important; }
    }

    /* CSS UNTUK BADGE NOTIFIKASI HEADER */
    .badge-notif-header {
        background-color: #dc3545;
        color: white;
        font-size: 0.65rem;
        font-weight: 800;
        padding: 2px 6px;
        border-radius: 50rem;
        margin-left: 4px;
        vertical-align: top;
        box-shadow: 0 2px 5px rgba(220, 53, 69, 0.4);
        display: inline-block;
        line-height: 1;
    }

    /* CSS UNTUK BADGE NOTIFIKASI DI TOMBOL HAMBURGER (MOBILE) */
    .badge-notif-mobile {
        position: absolute;
        top: 2px;
        right: -2px;
        background-color: #dc3545;
        color: white;
        font-size: 0.6rem;
        font-weight: 800;
        padding: 2px 5px;
        border-radius: 50rem;
        box-shadow: 0 2px 5px rgba(220, 53, 69, 0.4);
        line-height: 1;
        border: 2px solid var(--nav-bg); /* Biar ada jarak efek potongan dari ikon */
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // 1. Logika Hamburger Menu
        const hamburger = document.getElementById('mobile-menu-toggle');
        const navMenu = document.getElementById('nav-menu');
        const icon = hamburger ? hamburger.querySelector('i') : null;

        if(hamburger && navMenu) {
            hamburger.addEventListener('click', () => {
                navMenu.classList.toggle('show');
                if (navMenu.classList.contains('show')) {
                    icon.classList.replace('bi-list', 'bi-x-lg');
                } else {
                    icon.classList.replace('bi-x-lg', 'bi-list');
                }
            });
        }

        // 2. Logika Profil Dropdown
        const profileBtn = document.getElementById('profile-dropdown-btn');
        const profileMenu = document.getElementById('profile-dropdown-menu');

        if(profileBtn && profileMenu) {
            profileBtn.addEventListener('click', function(e) {
                e.stopPropagation(); 
                profileMenu.classList.toggle('show');
                profileBtn.classList.toggle('active');
            });

            document.addEventListener('click', function(e) {
                if(!profileMenu.contains(e.target) && !profileBtn.contains(e.target)) {
                    profileMenu.classList.remove('show');
                    profileBtn.classList.remove('active');
                }
            });
        }

        // 3. Logika Dark Mode Bebas Flicker
        const themeBtn = document.getElementById('theme-toggle');
        const iconTheme = themeBtn ? themeBtn.querySelector('i') : null;
        const rootElement = document.documentElement; // Mengakses elemen <html>

        // Menyesuaikan ikon saat pertama kali load
        if (localStorage.getItem('theme') === 'dark' && iconTheme) {
            iconTheme.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
        }

        if(themeBtn) {
            themeBtn.addEventListener('click', () => {
                if (rootElement.getAttribute('data-theme') === 'dark') {
                    rootElement.removeAttribute('data-theme');
                    localStorage.setItem('theme', 'light');
                    iconTheme.classList.replace('bi-sun-fill', 'bi-moon-stars-fill');
                } else {
                    rootElement.setAttribute('data-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                    iconTheme.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
                }
            });
        }
    });
</script>