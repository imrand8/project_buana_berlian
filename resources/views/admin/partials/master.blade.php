<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - Buana Berlian')</title>
    <link rel="icon" href="{{ asset('assets/img/LOGO.png') }}" type="image/png">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --p-color: #483d8b; --s-color: #6c5ce7; --bg-body: #f8faff;
            --sidebar-bg: #ffffff; --card-bg: #ffffff; --text-main: #1a1d23;
            --text-muted: #64748b; --border-color: rgba(0,0,0,0.05);
            --radius: 20px; --btn-light-bg: #f1f5f9; --btn-light-text: #1a1d23;
        }

        [data-theme="dark"] {
            --bg-body: #0f1115; --sidebar-bg: #16191f; --card-bg: #1c2128;
            --text-main: #f1f5f9; --text-muted: #94a3b8;
            --border-color: rgba(255,255,255,0.05);
            --btn-light-bg: #2d333b; --btn-light-text: #f1f5f9;
        }

        /* Global Text Fix */
        [data-theme="dark"] h1, [data-theme="dark"] h2, [data-theme="dark"] h3, 
        [data-theme="dark"] h4, [data-theme="dark"] h5, [data-theme="dark"] h6,
        [data-theme="dark"] .fw-bold, [data-theme="dark"] div, [data-theme="dark"] span { 
            color: var(--text-main) !important; 
        }
        [data-theme="dark"] .text-muted { color: var(--text-muted) !important; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bg-body);
            color: var(--text-main); transition: background 0.3s ease, color 0.3s ease; overflow-x: hidden;
        }

        .wrapper { display: flex; min-height: 100vh; }

        /* SIDEBAR CSS */
        .sidebar {
            width: 280px; background: var(--sidebar-bg); padding: 30px 20px;
            display: flex; flex-direction: column; border-right: 1px solid var(--border-color);
            position: fixed; height: 100vh; z-index: 1050; 
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s ease;
        }
        .brand { display: flex; align-items: center; gap: 12px; padding: 0 15px 40px; white-space: nowrap; }
        .brand-icon {
            width: 40px; height: 40px; background: linear-gradient(135deg, var(--p-color), var(--s-color));
            border-radius: 12px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;
        }
        .nav-label { font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin: 25px 15px 10px; opacity: 0.6; white-space: nowrap; }
        .nav-menu { list-style: none; padding: 0; margin: 0; }
        .nav-link {
            display: flex; align-items: center; gap: 15px; padding: 12px 20px; border-radius: 14px;
            color: var(--text-muted); text-decoration: none; font-weight: 600; transition: 0.3s; white-space: nowrap;
        }
        .nav-link:hover, .nav-link.active { background: rgba(72, 61, 139, 0.08); color: var(--p-color); }
        .nav-link.active { background: var(--p-color); color: white !important; box-shadow: 0 10px 20px rgba(72, 61, 139, 0.2); }

        /* MAIN CONTENT CSS */
        .main-panel { 
            flex: 1; margin-left: 280px; padding: 30px 40px; 
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            width: calc(100% - 280px); 
        }

        /* SIDEBAR MINI MODE */
        body.sidebar-mini .sidebar { width: 85px; padding: 30px 15px; }
        body.sidebar-mini .sidebar .brand h5, 
        body.sidebar-mini .sidebar .nav-label, 
        body.sidebar-mini .sidebar .nav-text { display: none; }
        body.sidebar-mini .sidebar .brand { padding: 0 0 40px; justify-content: center; }
        body.sidebar-mini .sidebar .nav-link { justify-content: center; padding: 12px 0; border-radius: 12px; }
        body.sidebar-mini .sidebar .nav-link i { margin: 0; font-size: 1.3rem; }
        body.sidebar-mini .main-panel { margin-left: 85px; width: calc(100% - 85px); }

        /* TOPBAR CSS */
        .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .sidebar-toggle, .theme-toggle {
            width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center;
            background: var(--card-bg); border: 1px solid var(--border-color); color: var(--text-main); cursor: pointer; transition: 0.3s;
        }
        .sidebar-toggle:hover, .theme-toggle:hover { background: var(--btn-light-bg); border-color: var(--p-color); }

        .user-profile {
            background: var(--card-bg); padding: 6px 15px; border-radius: 50px; display: flex; align-items: center; gap: 10px;
            border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.02); transition: 0.3s;
        }

        /* PERBAIKAN TOMBOL AKSI & IKON MATA */
        .btn-custom-light {
            background-color: var(--btn-light-bg);
            color: var(--text-main) !important;
            border: 1px solid var(--border-color);
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            padding: 8px;
        }
        .btn-custom-light i { color: var(--text-main) !important; font-size: 1.1rem; }
        
        .btn-custom-light:hover {
            background-color: var(--p-color) !important;
            color: white !important;
            border-color: var(--p-color);
            transform: translateY(-2px);
        }
        .btn-custom-light:hover i { color: white !important; }

        /* CARDS & TABLES */
        .stat-card, .content-card {
            background: var(--card-bg); border-radius: var(--radius); padding: 25px; border: 1px solid var(--border-color);
            box-shadow: 0 10px 30px rgba(0,0,0,0.02); transition: 0.3s;
        }
        .stat-icon { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; margin-bottom: 20px; }

        .table { --bs-table-bg: transparent; --bs-table-color: var(--text-main); margin-bottom: 0; }
        .table thead th { background: var(--bg-body); color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; padding: 15px 20px; border-bottom: 1px solid var(--border-color); }
        .table tbody td { padding: 18px 20px; border-bottom: 1px solid var(--border-color); background: transparent !important; vertical-align: middle; }

        .status-badge { padding: 6px 12px; border-radius: 10px; font-size: 0.7rem; font-weight: 700; display: inline-block; }
        .bg-pending { background: rgba(249, 115, 22, 0.1); color: #f97316; }
        .bg-success { background: rgba(34, 197, 94, 0.1); color: #22c55e; }

        /* MOBILE FIX */
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1040; backdrop-filter: blur(4px); }
        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-panel { margin-left: 0; padding: 20px; width: 100%; }
            .sidebar-overlay.show { display: block; }
        }
    </style>
    @stack('styles')
</head>
<body data-theme="light">

<div class="sidebar-overlay" id="overlay" onclick="toggleSidebar()"></div>

<div class="wrapper">
    @include('admin.partials.sidebar')
    <main class="main-panel">
        @include('admin.partials.topbar')
        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // --- Memori Sidebar & Tema ---
    document.addEventListener("DOMContentLoaded", function() {
        // Cek Sidebar
        const savedSidebar = localStorage.getItem('sidebar-status');
        if (savedSidebar === 'minimized' && window.innerWidth > 992) {
            document.body.classList.add('sidebar-mini');
        }
        // Cek Tema
        if(localStorage.getItem('theme') === 'dark') {
            document.body.setAttribute('data-theme', 'dark');
            updateThemeIcons(true);
        }
    });

    function toggleSidebar() {
        if (window.innerWidth > 992) {
            document.body.classList.toggle('sidebar-mini');
            const status = document.body.classList.contains('sidebar-mini') ? 'minimized' : 'expanded';
            localStorage.setItem('sidebar-status', status);
        } else {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('overlay').classList.toggle('show');
        }
    }

    function updateThemeIcons(isDark) {
        document.querySelectorAll('.theme-icon-el').forEach(icon => {
            if (isDark) {
                icon.classList.replace('bi-moon-stars', 'bi-sun-fill');
            } else {
                icon.classList.replace('bi-sun-fill', 'bi-moon-stars');
            }
        });
    }

    function toggleTheme() {
        if(document.body.getAttribute('data-theme') === 'dark') {
            document.body.setAttribute('data-theme', 'light');
            localStorage.setItem('theme', 'light');
            updateThemeIcons(false);
        } else {
            document.body.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark');
            updateThemeIcons(true);
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Cek tema saat ini biar notifnya otomatis nyesuain gelap/terang
        const isDark = document.body.getAttribute('data-theme') === 'dark';
        const swalBg = isDark ? '#16191f' : '#ffffff';
        const swalColor = isDark ? '#ffffff' : '#333333';

        // 1. Tangkap Notif Sukses (Toast Pojok Kanan Atas)
        @if(session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: `{{ session('success') }}`,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: swalBg,
                color: swalColor,
                customClass: {
                    popup: 'swal2-custom-toast'
                }
            });
        @endif

        // 2. Tangkap Notif Error Validasi (Popup Tengah)
        @if($errors->any())
            let errorHtml = '<ul style="text-align: left; margin: 0; padding-left: 20px;">';
            @foreach($errors->all() as $error)
                errorHtml += '<li>{{ $error }}</li>';
            @endforeach
            errorHtml += '</ul>';

            Swal.fire({
                icon: 'error',
                title: 'Gagal Menyimpan!',
                html: errorHtml,
                confirmButtonColor: '#ef4444',
                background: swalBg,
                color: swalColor
            });
        @endif

        // 3. Tangkap Notif Peringatan Biasa
        @if(session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: `{{ session('warning') }}`,
                confirmButtonColor: '#f59e0b',
                background: swalBg,
                color: swalColor
            });
        @endif
    });
</script>
@stack('scripts')
</body>
</html>