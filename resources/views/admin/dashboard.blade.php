@extends('admin.partials.master')

@section('page_title', 'Dashboard Overview')
@section('page_subtitle', 'Selamat datang kembali, Admin Buana! Pantau ringkasan operasional hari ini.')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* --- FLOATING WELCOME TOAST (FIX MOBILE RESPONSIVE) --- */
    .welcome-toast-container {
        position: fixed;
        top: 25px;
        left: 280px; 
        right: 0;
        display: flex;
        justify-content: center;
        z-index: 1060;
        pointer-events: none; 
        transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 0 20px; /* Kasih jarak aman biar nggak mentok tepi layar HP */
    }
    body.sidebar-mini .welcome-toast-container { left: 85px; } 
    @media (max-width: 992px) { .welcome-toast-container { left: 0; } }

    .welcome-toast {
        pointer-events: auto;
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        box-shadow: 0 15px 40px rgba(0,0,0,0.08);
        border-radius: 20px;
        padding: 16px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        width: 100%;
        max-width: 500px; /* Bikin auto menyesuaikan layar, tapi maksimal 500px di Desktop */
        animation: dropDownAndFade 6s cubic-bezier(0.2, 1, 0.2, 1) forwards;
    }
    
    [data-theme="dark"] .welcome-toast {
        background: #1c2128;
        box-shadow: 0 15px 40px rgba(0,0,0,0.4);
    }

    .welcome-icon-box {
        width: 50px; height: 50px; border-radius: 14px; 
        background: var(--p-color); color: white; 
        display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;
    }

    /* Penyesuaian Ekstra untuk Layar HP (Mobile) */
    @media (max-width: 576px) {
        .welcome-toast {
            padding: 12px 15px;
            gap: 12px;
            border-radius: 16px;
        }
        .welcome-icon-box { width: 40px; height: 40px; font-size: 1.2rem; border-radius: 10px; }
        .welcome-toast h5 { font-size: 0.95rem !important; margin-bottom: 2px !important; }
        .welcome-toast p { font-size: 0.75rem !important; line-height: 1.3; }
        .welcome-toast .btn { padding: 4px 10px !important; } /* Mengecilkan tombol close */
    }

    @keyframes dropDownAndFade {
        0%   { opacity: 0; transform: translateY(-50px) scale(0.95); }
        10%  { opacity: 1; transform: translateY(0) scale(1); }
        85%  { opacity: 1; transform: translateY(0) scale(1); }
        100% { opacity: 0; transform: translateY(-30px) scale(0.95); }
    }

    .stat-card { 
        text-decoration: none !important; 
        transition: transform 0.2s, box-shadow 0.2s; 
        cursor: pointer;
    }
    .stat-card:hover { 
        transform: translateY(-5px); 
        box-shadow: 0 10px 20px rgba(0,0,0,0.1); 
    }
    [data-theme="dark"] .stat-card:hover { 
        box-shadow: 0 10px 20px rgba(0,0,0,0.4); 
    }

    .btn-close-toast { background: var(--bg-body); color: var(--text-muted); border: 1px solid var(--border-color); transition: 0.2s; padding: 6px 12px; }
    .btn-close-toast:hover { background: var(--border-color); color: var(--text-main); }

    /* --- STAT CARDS --- */
    .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 25px; }
    .stat-card {
        background: var(--card-bg); border-radius: 16px; padding: 20px;
        border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between;
        transition: 0.3s; color: var(--text-main);
    }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-color: var(--p-color); }
    
    .stat-info .stat-title { font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
    .stat-info .stat-value { font-size: 1.8rem; font-weight: 800; margin: 5px 0 0 0; }
    .stat-info .stat-desc { font-size: 0.75rem; font-weight: 600; margin-top: 5px; }

    .stat-icon { width: 60px; height: 60px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; flex-shrink: 0; }
    
    /* Card Colors */
    .c-blue .stat-icon { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .c-yellow .stat-icon { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .c-green .stat-icon { background: rgba(34, 197, 94, 0.1); color: #22c55e; }
    .c-purple .stat-icon { background: rgba(72, 61, 139, 0.1); color: var(--p-color); }
    [data-theme="dark"] .c-purple .stat-icon { background: rgba(138, 118, 255, 0.15); color: #b3a6ff; }

    /* --- WIDGET CARDS --- */
    .widget-card { background: var(--card-bg); border-radius: 16px; padding: 22px; border: 1px solid var(--border-color); color: var(--text-main); }
    .widget-title { font-size: 1.1rem; font-weight: 800; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
    
    .btn-outline-custom { background: var(--bg-body); color: var(--text-main); border: 1px solid var(--border-color); transition: 0.3s; }
    .btn-outline-custom:hover, .btn-outline-custom:focus { background: var(--border-color); color: var(--text-main); }
    
    .dropdown-menu-custom { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px; overflow: hidden; padding: 8px; }
    .dropdown-menu-custom .dropdown-item { color: var(--text-main); font-size: 0.85rem; font-weight: 600; padding: 10px 15px; border-radius: 8px; transition: 0.2s; }
    .dropdown-menu-custom .dropdown-item:hover { background: var(--bg-body); color: var(--p-color); }
</style>

{{-- Logic: Hanya muncul jika ada minimal satu notifikasi --}}
@if($pesananHariIni > 0 || $menungguValidasi > 0 || $ktmMenunggu > 0)
<div id="welcomeToastContainer" class="welcome-toast-container">
    <div class="welcome-toast">
        <div class="d-flex align-items-center gap-3">
            <div class="welcome-icon-box"><i class="bi bi-bell-fill"></i></div>
            <div>
                <h5 class="fw-bold mb-1 text-main">Waktunya Cek Pesanan!</h5>
                
                {{-- Info Pesanan & Pembayaran --}}
                <p class="text-muted mb-0">
                    Hari ini ada <strong class="text-primary">{{ $pesananHariIni }} Pesanan Baru</strong> 
                    dan <strong class="text-warning" style="color: #d97706 !important;">{{ $menungguValidasi }} Pembayaran</strong> 
                    yang menunggu validasimu.
                </p>
                
                {{-- Info Tambahan untuk KTM Mahasiswa --}}
                @if($ktmMenunggu > 0)
                    <p class="text-danger fw-bold mt-1 mb-0" style="font-size: 0.75rem;">
                        <i class="bi bi-person-vcard me-1"></i> Plus, ada {{ $ktmMenunggu }} Mahasiswa menunggu verifikasi KTM!
                    </p>
                @endif
            </div>
        </div>
        <button class="btn btn-close-toast fw-bold rounded-3" onclick="document.getElementById('welcomeToastContainer').remove()">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
</div>
@endif

<div class="stat-grid">
    <a href="{{ route('admin.pesanan') }}" class="stat-card c-blue">
        <div class="stat-info">
            <div class="stat-title">Booking Aktif</div>
            <div class="stat-value">{{ $bookingAktif }}</div>
            <div class="stat-desc text-success"><i class="bi bi-arrow-right-short"></i> Lihat Semua Pesanan</div>
        </div>
        <div class="stat-icon"><i class="bi bi-ticket-detailed-fill"></i></div> </a>

    <a href="{{ route('admin.validasi') }}" class="stat-card c-yellow">
        <div class="stat-info">
            <div class="stat-title">Perlu Validasi</div>
            <div class="stat-value">{{ $menungguValidasi }}</div>
            <div class="stat-desc text-warning"><i class="bi bi-clock-history me-1"></i> Segera Cek Struk</div>
        </div>
        <div class="stat-icon"><i class="bi bi-clipboard-check-fill"></i></div> </a>

    <a href="{{ route('admin.pesanan') }}" class="stat-card c-green">
        <div class="stat-info">
            <div class="stat-title">Berangkat Hari Ini</div>
            <div class="stat-value">{{ $berangkatHariIni }}</div>
            <div class="stat-desc text-muted"><i class="bi bi-truck-front-fill me-1"></i> Cek Manifest Supir</div>
        </div>
        <div class="stat-icon"><i class="bi bi-truck-front-fill"></i></div> </a>

    <a href="{{ route('admin.laporan') }}" class="stat-card c-purple">
        <div class="stat-info">
            <div class="stat-title">Omzet Hari Ini</div>
            <div class="stat-value fs-4 mt-2">Rp {{ number_format($omzetHariIni / 1000, 0, ',', '.') }}K</div>
            <div class="stat-desc text-success"><i class="bi bi-graph-up-arrow me-1"></i> Pemasukan Lunas</div>
        </div>
        <div class="stat-icon"><i class="bi bi-cash-coin"></i></div>
    </a>
</div>

<div class="widget-card shadow-sm mb-4">
    <div class="widget-title">
        <span><i class="bi bi-activity text-primary me-2"></i>Trend Pemesanan</span>
        
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-custom fw-bold rounded-3 dropdown-toggle px-3 py-2" type="button" data-bs-toggle="dropdown" id="btnFilterChart">
                7 Hari Terakhir
            </button>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom shadow">
                <li><a class="dropdown-item" href="javascript:void(0)" onclick="updateChart('mingguan', '7 Hari Terakhir')">7 Hari Terakhir</a></li>
                <li><a class="dropdown-item" href="javascript:void(0)" onclick="updateChart('bulanan', 'Bulan Ini')">Bulan Ini</a></li>
                <li><a class="dropdown-item" href="javascript:void(0)" onclick="updateChart('tahunan', 'Tahun Ini')">Tahun Ini</a></li>
            </ul>
        </div>
    </div>
    <div style="height: 320px; width: 100%;">
        <canvas id="bookingChart"></canvas>
    </div>
</div>

@push('scripts')
<script>
    let bookingChart;

    // TANGKAP 3 JENIS DATA DARI CONTROLLER
    const dataMingguan = {!! json_encode($dataMingguan) !!};
    const labelMingguan = {!! json_encode($labelMingguan) !!};
    
    const dataBulanan = {!! json_encode($dataBulanan) !!};
    const labelBulanan = {!! json_encode($labelBulanan) !!};
    
    const dataTahunan = {!! json_encode($dataTahunan) !!};
    const labelTahunan = {!! json_encode($labelTahunan) !!};

    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(() => {
            const toast = document.getElementById('welcomeToastContainer');
            if (toast) { toast.remove(); }
        }, 6500); 

        const ctx = document.getElementById('bookingChart').getContext('2d');
        const isDarkTheme = document.body.getAttribute('data-theme') === 'dark';
        const textColor = isDarkTheme ? '#94a3b8' : '#64748b';
        const gridColor = isDarkTheme ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';

        let gradient = ctx.createLinearGradient(0, 0, 0, 320);
        gradient.addColorStop(0, 'rgba(72, 61, 139, 0.5)'); 
        gradient.addColorStop(1, 'rgba(72, 61, 139, 0.0)'); 

        bookingChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labelMingguan, // Default tampil mingguan
                datasets: [{
                    label: 'Total Pesanan (Travel & Kargo)',
                    data: dataMingguan, // Default tampil mingguan
                    borderColor: '#483d8b',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#483d8b',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { padding: 12 } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: textColor, font: {family: "'Plus Jakarta Sans', sans-serif"} } },
                    y: { 
                        grid: { color: gridColor, drawBorder: false }, 
                        ticks: { color: textColor, font: {family: "'Plus Jakarta Sans', sans-serif"} } 
                    }
                },
                interaction: { intersect: false, mode: 'index' }
            }
        });

        // Observer Dark Mode
        const observer = new MutationObserver(() => {
            const dark = document.body.getAttribute('data-theme') === 'dark';
            bookingChart.options.scales.x.ticks.color = dark ? '#94a3b8' : '#64748b';
            bookingChart.options.scales.y.ticks.color = dark ? '#94a3b8' : '#64748b';
            bookingChart.options.scales.y.grid.color = dark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';
            bookingChart.update();
        });
        observer.observe(document.body, { attributes: true, attributeFilter: ['data-theme'] });
    });

    // FUNGSI GANTI FILTER GRAFIK DINAMIS
    function updateChart(tipe, teksTombol) {
        document.getElementById('btnFilterChart').innerText = teksTombol;

        if(tipe === 'mingguan') {
            bookingChart.data.labels = labelMingguan;
            bookingChart.data.datasets[0].data = dataMingguan;
        } 
        else if(tipe === 'bulanan') {
            bookingChart.data.labels = labelBulanan;
            bookingChart.data.datasets[0].data = dataBulanan;
        } 
        else if(tipe === 'tahunan') {
            bookingChart.data.labels = labelTahunan;
            bookingChart.data.datasets[0].data = dataTahunan;
        }
        
        bookingChart.update();
    }
</script>
@endpush

@endsection