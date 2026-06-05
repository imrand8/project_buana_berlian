<header class="topbar d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-3">
        <button class="sidebar-toggle" onclick="toggleSidebar()" type="button">
            <i class="bi bi-list fs-5"></i>
        </button>
        
        <div class="page-title-group">
            <h4 class="fw-bold m-0 text-main">@yield('page_title', 'Dashboard Overview')</h4>
            <p class="text-muted small m-0 d-none d-md-block">@yield('page_subtitle', 'Selamat datang kembali!')</p>
        </div>
    </div>
    
    <div class="topbar-actions d-flex align-items-center gap-3">
        <button class="theme-toggle" onclick="toggleTheme()" type="button">
            <i class="bi bi-moon-stars theme-icon-el"></i>
        </button>
        
        <a href="{{ url('/admin/profil') }}" class="text-decoration-none">
            <div class="user-profile border-hover d-flex align-items-center gap-2">
                <div class="text-end d-none d-sm-block">
                    <div class="fw-bold text-main" style="font-size: 0.8rem;">{{ Auth::user()->name }}</div>
                    <div class="text-muted" style="font-size: 0.65rem;">Super Administrator</div>
                </div>
                @php
                    $words = explode(' ', Auth::user()->name ?? 'Admin');
                    $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                    $colors = ['bg-primary', 'bg-success', 'bg-danger', 'bg-warning text-dark', 'bg-info text-dark', 'bg-secondary'];
                    $avatarColor = $colors[(Auth::user()->id ?? 1) % count($colors)];
                @endphp

                @if(Auth::user()->avatar)
                    <img src="{{ asset('public/storage/' . Auth::user()->avatar) }}" class="rounded-circle border" width="35" height="35" style="object-fit: cover;">
                @else
                    <div class="{{ $avatarColor }}" style="width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: white; font-size: 0.9rem; border: 1px solid var(--border-color);">{{ $initials }}</div>
                @endif
            </div>
        </a>
    </div>
</header>

<style>
    /* Menjamin topbar mengambil ruang penuh dan elemen terpisah ke ujung */
    .topbar {
        width: 100%;
        margin-bottom: 40px;
        min-height: 60px;
    }

    /* Memisahkan grup judul agar tidak mepet ke tombol toggle */
    .page-title-group {
        display: flex;
        flex-direction: column;
    }

    /* Efek hover pada profil */
    .user-profile { 
        transition: 0.3s; 
        padding: 6px 15px;
        border-radius: 50px;
        background: var(--card-bg);
        border: 1px solid var(--border-color);
    }
    .user-profile:hover { 
        background: var(--btn-light-bg); 
        border-color: var(--p-color); 
    }

    /* Menyesuaikan jarak pada mode mobile */
    @media (max-width: 992px) {
        .topbar {
            flex-direction: row !important; /* Memaksa tetap menyamping di mobile */
            justify-content: space-between !important;
            padding: 0;
        }
        .page-title-group h4 {
            font-size: 1.1rem;
        }
    }
</style>