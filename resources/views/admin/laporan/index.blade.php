@extends('admin.partials.master')

@section('page_title', 'Laporan & Keuangan')
@section('page_subtitle', 'Pantau omzet bisnis dan tarik laporan transaksi dengan mudah.')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* --- CONTROL PANEL (FILTER & EXPORT) --- */
    .control-panel { background: var(--card-bg); border-radius: 16px; padding: 25px; border: 1px solid var(--border-color); margin-bottom: 25px; }
    .custom-input, .custom-select { background: var(--bg-body); color: var(--text-main); border: 1px solid var(--border-color); border-radius: 10px; padding: 10px 15px; width: 100%; transition: 0.3s; }
    .custom-input:focus, .custom-select:focus { border-color: var(--p-color); box-shadow: 0 0 0 3px rgba(72, 61, 139, 0.1); outline: none; }
    
    [data-theme="dark"] .custom-select option { background: var(--card-bg); color: var(--text-main); }
    [data-theme="dark"] .custom-input, [data-theme="dark"] .custom-select { background: #181a20; border-color: #2d333b; color: #f8fafc; }
    [data-theme="dark"] input[type="date"]::-webkit-calendar-picker-indicator { filter: invert(1); cursor: pointer; }

    /* --- SUMMARY CARDS --- */
    .summary-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 25px; }
    .summary-card { background: var(--card-bg); border-radius: 16px; padding: 20px; border: 1px solid var(--border-color); display: flex; align-items: center; transition: 0.3s; }
    .summary-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
    
    .icon-box { width: 55px; height: 55px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; margin-right: 15px; flex-shrink: 0; }
    
    .card-income { border-bottom: 4px solid #22c55e; }
    .card-income .icon-box { background: rgba(34, 197, 94, 0.1); color: #22c55e; }
    .card-travel { border-bottom: 4px solid var(--p-color); }
    .card-travel .icon-box { background: rgba(72, 61, 139, 0.1); color: var(--p-color); }
    [data-theme="dark"] .card-travel .icon-box { background: rgba(138, 118, 255, 0.15); color: #b3a6ff; }
    
    .summary-title { font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px; }
    .summary-value { font-size: 1.5rem; font-weight: 800; color: var(--text-main); margin: 0; }

    /* --- CHART & TABLE SECTION --- */
    .content-card { background: var(--card-bg); border-radius: 16px; padding: 22px; border: 1px solid var(--border-color); margin-bottom: 25px; }
    .table-fixed { table-layout: fixed; width: 100%; min-width: 800px; }
    .table-fixed th, .table-fixed td { padding: 16px 12px; vertical-align: middle; }
    .status-badge { padding: 6px 14px; border-radius: 50rem; font-size: 0.75rem; font-weight: 700; }
    .status-success { background: rgba(34, 197, 94, 0.1); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.2); }

    [data-theme="dark"] .table { color: #f8fafc; }
    [data-theme="dark"] .table thead th { background: #16191f; border-color: #2d333b; color: #94a3b8; border-bottom-width: 1px; }
    [data-theme="dark"] .table tbody td { border-color: #2d333b; color: #f8fafc; }
</style>

<form action="{{ route('admin.laporan') }}" method="GET" class="control-panel shadow-sm">
    <div class="d-flex align-items-center mb-3">
        <i class="bi bi-sliders text-primary fs-4 me-2"></i>
        <h5 class="fw-bold m-0">Tarik Data Laporan</h5>
    </div>
    <div class="row g-3 align-items-end">
        <div class="col-lg-2 col-md-4">
            <label class="small fw-bold mb-2 text-muted">Dari Tanggal</label>
            <input type="date" name="start_date" id="startDate" class="form-control custom-input fw-bold" value="{{ $startDate }}">
        </div>
        <div class="col-lg-2 col-md-4">
            <label class="small fw-bold mb-2 text-muted">Sampai Tanggal</label>
            <input type="date" name="end_date" id="endDate" class="form-control custom-input fw-bold" value="{{ $endDate }}">
        </div>
        <div class="col-lg-3 col-md-4">
            <label class="small fw-bold mb-2 text-muted">Filter Layanan</label>
            <select name="layanan" id="layananFilter" class="form-select custom-select fw-bold">
                <option value="semua" {{ $layanan == 'semua' ? 'selected' : '' }}>Semua Layanan</option>
                <option value="travel" {{ $layanan == 'travel' ? 'selected' : '' }}>Khusus Travel Penumpang</option>
                <option value="kargo" {{ $layanan == 'kargo' ? 'selected' : '' }}>Khusus Pengiriman Kargo</option>
            </select>
        </div>
        
        <div class="col-lg-5 col-md-12 d-flex gap-2 mt-3 mt-lg-0">
            <button type="submit" class="btn btn-primary fw-bold px-4 py-2 rounded-3">
                <i class="bi bi-search me-1"></i> Lihat
            </button>
            <button type="button" class="btn btn-success fw-bold flex-grow-1 rounded-3" onclick="downloadRekapExcel()">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Unduh Rekap
            </button>
            <button type="button" class="btn btn-outline-secondary fw-bold flex-grow-1 rounded-3" onclick="downloadDetailExcel()" style="background: var(--bg-body); color: var(--text-main);">
                <i class="bi bi-list-columns me-1"></i> Unduh Detail
            </button>
        </div>
    </div>
</form>

<div class="summary-grid">
    <div class="summary-card card-income shadow-sm">
        <div class="icon-box"><i class="bi bi-wallet2"></i></div>
        <div>
            <div class="summary-title">Total Omzet Pendapatan</div>
            <h3 class="summary-value text-success" id="valPendapatan">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
        </div>
    </div>
    <div class="summary-card card-travel shadow-sm">
        <div class="icon-box"><i class="bi bi-ticket-perforated-fill"></i></div>
        <div>
            <div class="summary-title">Tiket & Kargo Terjual</div>
            <h3 class="summary-value"><span id="valTotal">{{ $totalItem }}</span> <span class="fs-6 text-muted fw-normal">Item</span></h3>
        </div>
    </div>
</div>

<div class="content-card shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold m-0"><i class="bi bi-graph-up-arrow text-primary me-2"></i>Tren Pendapatan Bulanan</h5>
    </div>
    <div style="height: 300px; width: 100%;">
        <canvas id="revenueChart"></canvas>
    </div>
</div>

<div class="content-card shadow-sm mb-5">
    <div class="mb-4">
        <h5 class="fw-bold m-0"><i class="bi bi-clock-history text-primary me-2"></i>Riwayat Transaksi Masuk</h5>
        <p class="text-muted small mt-1 mb-0">Daftar lengkap transaksi berstatus Lunas sesuai filter tanggal.</p>
    </div>
    
    <div class="table-responsive">
        <table class="table table-fixed border-top">
            <thead class="text-uppercase small fw-bold text-muted border-0">
                <tr>
                    <th style="width: 130px; border-bottom: 1px solid var(--border-color);">Tanggal</th>
                    <th style="width: 130px; border-bottom: 1px solid var(--border-color);">Order ID</th>
                    <th style="width: 150px; border-bottom: 1px solid var(--border-color);">Layanan</th>
                    <th style="width: 250px; border-bottom: 1px solid var(--border-color);">Pelanggan</th>
                    <th style="width: 150px; border-bottom: 1px solid var(--border-color);">Nominal</th>
                    <th style="width: 100px; border-bottom: 1px solid var(--border-color);" class="text-center">Status</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($semuaTransaksi as $trx)
                    @php
                        $isTravel = isset($trx->kode_booking);
                        $kode = $isTravel ? $trx->kode_booking : $trx->kode_resi;
                        $nama = $isTravel ? $trx->nama_penumpang : $trx->nama_pengirim;
                        $rute = $isTravel 
                            ? explode(' (', $trx->titik_jemput)[0] . ' - ' . explode(' (', $trx->titik_antar)[0]
                            : explode(' (', $trx->kota_asal)[0] . ' - ' . explode(' (', $trx->kota_tujuan)[0];
                    @endphp
                    <tr>
                        <td>
                            <div class="fw-bold text-main">{{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y') }}</div>
                            <div class="small text-muted">{{ \Carbon\Carbon::parse($trx->created_at)->format('H:i') }} WIB</div>
                        </td>
                        <td><span class="fw-bold text-{{ $isTravel ? 'primary' : 'warning text-dark' }}">{{ $kode }}</span></td>
                        <td>
                            @if($isTravel)
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-2 py-1"><i class="bi bi-person-fill me-1"></i>Travel</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-2 py-1" style="color:#d97706!important;"><i class="bi bi-box-seam me-1"></i>Kargo ({{ $trx->berat_barang }}Kg)</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold text-main">{{ $nama }}</div>
                            <div class="small text-muted">{{ $rute }}</div>
                        </td>
                        <td><span class="fw-bold fs-6">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</span></td>
                        <td class="text-center"><span class="status-badge status-success">Lunas</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-2 mb-2 d-block"></i> Tidak ada transaksi lunas di periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    let myChart; 

    function getSwalTheme() {
        const isDark = document.body.getAttribute('data-theme') === 'dark';
        return { background: isDark ? 'var(--card-bg)' : '#ffffff', color: isDark ? 'var(--text-main)' : '#000000' };
    }

    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const isDark = document.body.getAttribute('data-theme') === 'dark';
        const textColor = isDark ? '#94a3b8' : '#64748b';
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';

        myChart = new Chart(ctx, {
            type: 'line', // Ganti ke Line Chart biar beda dari Dashboard
            data: {
                labels: {!! $labels !!},
                datasets: [
                    { label: 'Omzet Harian', data: {!! $data !!}, borderColor: '#22c55e', backgroundColor: 'rgba(34, 197, 94, 0.1)', borderWidth: 3, fill: true, tension: 0.4 }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: textColor, font: {family: "'Plus Jakarta Sans', sans-serif"} } },
                    y: { grid: { color: gridColor }, ticks: { color: textColor, font: {family: "'Plus Jakarta Sans', sans-serif"}, callback: val => 'Rp ' + (val/1000000) + ' Jt' } }
                }
            }
        });
    });

    // --- FUNGSI LIHAT / FILTER ---
    function terapkanFilter() {
        const theme = getSwalTheme();
        const start = document.getElementById('startDate').value;
        const end = document.getElementById('endDate').value;
        const layanan = document.getElementById('layananFilter').value;

        Swal.fire({
            title: "Menarik Data Laporan...",
            text: `Periode: ${start} s/d ${end}`,
            timer: 1000, showConfirmButton: false,
            background: theme.background, color: theme.color,
            didOpen: () => { Swal.showLoading(); }
        }).then(() => {
            // Animasi dummy update angka (UI Only)
            document.getElementById('valPendapatan').innerText = layanan === 'kargo' ? "Rp 4.500.000" : (layanan === 'travel' ? "Rp 13.950.000" : "Rp 18.450.000");
            document.getElementById('valTotal').innerText = layanan === 'kargo' ? "345" : (layanan === 'travel' ? "124" : "469");
            
            // Tampilkan/Sembunyikan baris di tabel sesuai layanan
            const rows = document.querySelectorAll('#tableBody tr');
            rows.forEach(row => {
                if (layanan === 'semua') row.style.display = '';
                else if (layanan === 'travel' && row.classList.contains('row-travel')) row.style.display = '';
                else if (layanan === 'kargo' && row.classList.contains('row-kargo')) row.style.display = '';
                else row.style.display = 'none';
            });
        });
    }

    // --- SETUP TOAST SWEETALERT ---
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    // --- FUNGSI DOWNLOAD REKAP (TOAST) ---
    function downloadRekapExcel() {
        let start = document.getElementById('startDate').value;
        let end = document.getElementById('endDate').value;
        const isDark = document.body.getAttribute('data-theme') === 'dark';
        const bg = isDark ? 'var(--card-bg)' : '#ffffff';
        const color = isDark ? 'var(--text-main)' : '#333333';

        // 1. Munculkan Toast Info
        Toast.fire({
            icon: 'info',
            title: 'Menyiapkan Rekap...',
            background: bg, color: color,
            timer: 1500
        });

        // 2. Eksekusi Download File
        window.location.href = `/admin/laporan/export-rekap?start_date=${start}&end_date=${end}`;

        // 3. Ubah jadi Toast Sukses setelah 1.5 Detik
        setTimeout(() => {
            Toast.fire({
                icon: 'success',
                title: 'Rekap berhasil diunduh!',
                background: bg, color: color,
                timer: 3000
            });
        }, 1500);
    }

    // --- FUNGSI DOWNLOAD DETAIL (TOAST) ---
    function downloadDetailExcel() {
        let start = document.getElementById('startDate').value;
        let end = document.getElementById('endDate').value;
        let layanan = document.getElementById('layananFilter').value;
        
        const isDark = document.body.getAttribute('data-theme') === 'dark';
        const bg = isDark ? 'var(--card-bg)' : '#ffffff';
        const color = isDark ? 'var(--text-main)' : '#333333';

        // 1. Munculkan Toast Info
        Toast.fire({
            icon: 'info',
            title: 'Menyiapkan Rincian...',
            background: bg, color: color,
            timer: 1500
        });

        // 2. Eksekusi Download File
        window.location.href = `/admin/laporan/export-detail?start_date=${start}&end_date=${end}&layanan=${layanan}`;

        // 3. Ubah jadi Toast Sukses setelah 1.5 Detik
        setTimeout(() => {
            Toast.fire({
                icon: 'success',
                title: 'Detail berhasil diunduh!',
                background: bg, color: color,
                timer: 3000
            });
        }, 1500);
    }
</script>
@endpush

@endsection