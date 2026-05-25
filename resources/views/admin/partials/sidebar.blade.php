@php
    // 1. Hitung Pesanan Masuk (Total yang butuh diproses: menunggu bayar & menunggu verifikasi)
    $countPesanan = \App\Models\PesananTravel::whereIn('status_pesanan', ['menunggu_pembayaran', 'menunggu_verifikasi'])->count() 
                  + \App\Models\PesananKargo::whereIn('status_pesanan', ['menunggu_pembayaran', 'menunggu_verifikasi'])->count();

    // 2. Hitung Validasi Pembayaran (Khusus yang sudah setor bukti / COD dan siap di-ACC = menunggu_verifikasi)
    $countValidasi = \App\Models\PesananTravel::where('status_pesanan', 'menunggu_verifikasi')->count() 
                   + \App\Models\PesananKargo::where('status_pesanan', 'menunggu_verifikasi')->count();

    // 3. Hitung Mahasiswa Menunggu Verifikasi KTM
    $ktmPending = \App\Models\User::where('status_mahasiswa', 'menunggu_verifikasi')->count();
@endphp

<style>
    /* --- FIX SIDEBAR SCROLL (MOBILE & LAYAR KECIL) --- */
    #sidebar {
        overflow-y: auto;
        overflow-x: hidden;
    }
    
    /* Mempercantik Scrollbar Sidebar Biar Tipis & Elegan */
    #sidebar::-webkit-scrollbar { width: 4px; }
    #sidebar::-webkit-scrollbar-track { background: transparent; }
    #sidebar::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }
    [data-theme="dark"] #sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); }

    /* --- STYLE KHUSUS BADGE NOTIFIKASI SIDEBAR --- */
    
    /* 1. Mode Biasa (Expanded) */
    .sidebar-badge {
        font-size: 0.65rem;
        padding: 4px 7px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        margin-left: -5px; 
        border-radius: 50rem;
    }

    .sidebar .nav-link { position: relative; }

    /* 2. Mode Kecil (Minimized) - Fix Nempel di Pojok Ikon */
    body.sidebar-mini .sidebar-badge {
        position: absolute;
        top: 6px;
        left: 50%;          
        margin-left: 2px;   
        right: auto;        
        padding: 3px 5px;
        font-size: 0.55rem;
        border: 2px solid var(--sidebar-bg); 
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        z-index: 10;
    }

    /* Fix warna border badge saat menu sedang aktif */
    body.sidebar-mini .nav-link.active .sidebar-badge {
        border-color: var(--p-color);
    }
</style>

<aside class="sidebar" id="sidebar">
    <div class="brand border-bottom border-secondary border-opacity-10 pb-4 mb-2">
        <div class="brand-icon"><i class="bi bi-gem"></i></div>
        <h5 class="m-0 fw-bold nav-text" style="letter-spacing: -1px;">Admin Buana</h5>
    </div>

    <div class="nav-menu pt-0"> 
        <div class="nav-label mt-2">Utama</div>
        <div class="nav-item">
            <a href="{{ url('/admin/dashboard') }}" class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> <span class="nav-text">Dashboard</span>
            </a>
        </div>
        
        <div class="nav-label">Transaksi</div>
        
        <div class="nav-item">
            <a href="{{ url('/admin/pesanan') }}" class="nav-link {{ Request::is('admin/pesanan*') ? 'active' : '' }}">
                <i class="bi bi-inbox-fill"></i> 
                <span class="nav-text">Pesanan Masuk</span>
                @if($countPesanan > 0)
                    <span class="badge bg-danger shadow-sm sidebar-badge">{{ $countPesanan }}</span>
                @endif
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ url('/admin/validasi') }}" class="nav-link {{ Request::is('admin/validasi*') ? 'active' : '' }}">
                <i class="bi bi-cash-stack"></i> 
                <span class="nav-text">Pembayaran</span>
                @if($countValidasi > 0)
                    <span class="badge bg-danger shadow-sm sidebar-badge">{{ $countValidasi }}</span>
                @endif
            </a>
        </div>
        
        <div class="nav-label">Manajemen</div>
        <div class="nav-item">
            <a href="{{ url('/admin/jadwal') }}" class="nav-link {{ Request::is('admin/jadwal*') ? 'active' : '' }}">
                <i class="bi bi-calendar-event-fill"></i> <span class="nav-text">Jadwal Operasional</span>
            </a>
        </div>
        
        <div class="nav-item">
            <a href="{{ url('/admin/pelanggan') }}" class="nav-link {{ Request::is('admin/pelanggan*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> <span class="nav-text">Data Pelanggan</span>
                @if($ktmPending > 0)
                    <span class="badge bg-warning text-dark shadow-sm sidebar-badge">{{ $ktmPending }}</span>
                @endif
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.data_master') }}" class="nav-link {{ Request::is('admin/data-master*') ? 'active' : '' }}">
                <i class="bi bi-database-fill-gear"></i> 
                <span class="nav-text">Data Master</span>
            </a>
        </div>

        <div class="nav-label">Laporan</div>
        <div class="nav-item">
            <a href="{{ url('/admin/laporan') }}" class="nav-link {{ Request::is('admin/laporan*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-bar-graph-fill"></i> <span class="nav-text">Laporan Transaksi</span>
            </a>
        </div>
    </div>

    <div class="mt-auto p-2 pt-4">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-link border-0 bg-transparent w-100 text-danger text-start">
                <i class="bi bi-box-arrow-left"></i> <span class="nav-text">Keluar Sistem</span>
            </button>
        </form>
    </div>
</aside>