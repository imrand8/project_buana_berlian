@extends('admin.partials.master')

@section('page_title', 'Data Pesanan & Manifest')
@section('page_subtitle', 'Kelola booking harian, edit tiket, dan cetak manifest supir otomatis.')

@section('content')

<style id="custom-print-css">
    /* --- TABS & SEARCH --- */
    .nav-pills-custom { background: #e2e8f0; padding: 6px; border-radius: 16px; display: inline-flex; gap: 4px; border: 1px solid rgba(0,0,0,0.05); }
    [data-theme="dark"] .nav-pills-custom { background: #181a20; border-color: rgba(255,255,255,0.05); }
    .nav-pills-custom .nav-link { border-radius: 12px; padding: 10px 22px; font-weight: 700; color: var(--text-muted); border: none; font-size: 0.85rem; transition: 0.3s; cursor: pointer; }
    .nav-pills-custom .nav-link.active { background: var(--card-bg) !important; color: var(--p-color) !important; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    [data-theme="dark"] .nav-pills-custom .nav-link.active { background: var(--p-color) !important; color: white !important; }

    .search-container { display: flex; align-items: center; background: var(--bg-body); border: 1px solid var(--border-color); border-radius: 12px; padding: 0 15px; height: 48px; width: 100%; max-width: 320px; transition: 0.3s; }
    .search-container:focus-within { border-color: var(--p-color); box-shadow: 0 0 0 3px rgba(72, 61, 139, 0.1); }
    .search-container input { border: none; background: transparent; color: var(--text-main); width: 100%; padding-left: 10px; outline: none; font-size: 0.9rem; }
    [data-theme="dark"] input[type="date"] { color-scheme: dark; }

    /* --- TABLE STYLING --- */
    .table-fixed { table-layout: fixed; width: 100%; min-width: 1000px; }
    .col-id { width: 120px; } .col-tgl { width: 140px; } .col-user { width: 200px; } .col-alamat { width: 300px; } .col-status { width: 150px; } .col-aksi { width: 140px; }
    
    [data-theme="dark"] .table { color: var(--text-main); border-color: var(--border-color); }
    [data-theme="dark"] .table thead th { background: var(--bg-body); color: var(--text-muted); border-bottom: 2px solid var(--border-color); }
    [data-theme="dark"] .table td, [data-theme="dark"] .table th { border-color: var(--border-color); }
    
    /* --- ACTION BUTTONS --- */
    .btn-action-view { width: 34px; height: 34px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; background: var(--bg-body); color: var(--text-main); border: 1px solid var(--border-color); transition: 0.2s; }
    .btn-action-view:hover { background: var(--p-color); color: white; border-color: var(--p-color); }
    .btn-action-assign { width: 34px; height: 34px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; background: rgba(13, 110, 253, 0.1); color: #0d6efd; border: 1px solid rgba(13, 110, 253, 0.3); transition: 0.2s; }
    .btn-action-assign:hover { background: #0d6efd; color: white; border-color: #0d6efd;}
    .btn-action-delete { width: 34px; height: 34px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; background: rgba(220, 53, 69, 0.1); color: #dc3545; border: 1px solid rgba(220, 53, 69, 0.3); transition: 0.2s; padding: 0; }
    .btn-action-delete:hover { background: #dc3545; color: white; border-color: #dc3545;}

    .manifest-panel { background: var(--bg-body); border: 1px solid var(--border-color); border-radius: 16px; padding: 25px; margin-top: 40px; }

    /* --- FIX MODAL DARK MODE --- */
    .custom-input { background: var(--bg-body); color: var(--text-main) !important; border: 1px solid var(--border-color); }
    .custom-input:focus { border-color: var(--p-color); box-shadow: 0 0 0 3px rgba(72, 61, 139, 0.1); outline: none; background: var(--bg-body); }
    
    [data-theme="dark"] .modal-content { background-color: var(--card-bg); color: var(--text-main); border: 1px solid var(--border-color); }
    [data-theme="dark"] .modal-header, [data-theme="dark"] .modal-footer { border-color: var(--border-color); }
    [data-theme="dark"] .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }
    [data-theme="dark"] .custom-input { background: #181a20; border-color: #444; }
    [data-theme="dark"] .alert-wa { background: rgba(37, 211, 102, 0.1) !important; color: #f8fafc !important; border-left: 4px solid #25D366 !important; }

    div.swal2-container { z-index: 20000 !important; }

    /* --- FIX WARNA TOMBOL BATAL & ELEMEN EKSPOR DI DARK MODE --- */
    [data-theme="dark"] .btn-light {
        background-color: var(--btn-light-bg) !important;
        color: var(--btn-light-text) !important;
        border: 1px solid var(--border-color);
    }
    
    [data-theme="dark"] .btn-light:hover {
        background-color: var(--p-color) !important;
        color: white !important;
    }

    /* Perbaiki warna Text Header Kotak WA */
    [data-theme="dark"] .alert-wa h6 {
        color: #25D366 !important;
        opacity: 0.9;
    }

    /* Hack CSS untuk meredupkan background pink tombol Cetak PDF di Dark Mode */
    [data-theme="dark"] button[style*="background: #f8d7da"] {
        background: rgba(220, 53, 69, 0.15) !important;
    }

    /* --- FIX DARK MODE ADMIN PESANAN --- */
    /* 1. Alert Warning (Kuning) Biar Elegan di Dark Mode */
    [data-theme="dark"] .alert-warning { 
        background-color: rgba(255, 193, 7, 0.1) !important; 
        color: #ffc107 !important; 
        border: 1px solid rgba(255, 193, 7, 0.3) !important; 
    }

    /* 2. Kotak Filter Tanggal & Pencarian */
    [data-theme="dark"] #filterTanggal, 
    [data-theme="dark"] .search-container { 
        background-color: #181a20 !important; 
        border-color: rgba(255,255,255,0.1) !important; 
        color: #ffffff !important; 
    }
    [data-theme="dark"] .search-container input::placeholder { 
        color: #777777 !important; 
    }

    /* 3. Panel Manifest Bawah */
    [data-theme="dark"] .manifest-panel { 
        background-color: #1a1c23 !important; 
        border-color: rgba(255,255,255,0.05) !important; 
    }
    
    /* 4. Ikon Empty State (Inbox kosong di tengah tabel) */
    [data-theme="dark"] .bi-inbox { 
        color: #333333 !important; 
    }

/* --- CSS E-TICKET (TAMPIL SEMPURNA SAAT CETAK) --- */
    .ticket-card { background-color: #ffffff !important; position: relative; border: 2px solid #ccc !important; border-radius: 20px !important; overflow: hidden; page-break-inside: avoid; max-width: 800px; margin: 0 auto; font-family: 'Plus Jakarta Sans', 'Poppins', sans-serif !important; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    .ticket-header { padding: 20px 30px !important; color: white !important; display: flex !important; justify-content: space-between !important; align-items: center !important; border-bottom: 3px solid #d4af37 !important; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    .header-travel { background-color: #352877 !important; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    .header-cargo { background-color: #2e7d32 !important; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    .ticket-body { padding: 30px !important; display: grid !important; grid-template-columns: 2fr 1fr !important; gap: 30px !important; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    .ticket-footer { border-top: 2px dashed #dee2e6 !important; padding: 20px 30px !important; display: flex !important; justify-content: space-between !important; align-items: center !important; background-color: #f8f9fa !important; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    .info-item label { display: block; font-size: 0.75rem; color: #6c757d !important; margin-bottom: 5px; text-transform: uppercase; font-weight: 600; }
    .info-item span { font-size: 1.1rem; font-weight: 700; color: #333 !important; }

    @media print {
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        
        /* 1. Paksa kertas jadi putih, dan teks default jadi hitam! */
        body { background-color: white !important; color: black !important; } 
        body * { visibility: hidden; } 
        
        #printableTicketArea, #printableTicketArea * { visibility: visible; }
        #printableManifestArea, #printableManifestArea * { visibility: visible; color: black !important; }
        
        #printableTicketArea, #printableManifestArea { position: absolute; left: 0; top: 0; width: 100%; margin: 0; padding: 0; }
        
        .no-print, .modal, .modal-backdrop { display: none !important; }

        /* 2. SULAP ANTI TEKS MENGHILANG (Paksa warna spesifik untuk E-Ticket) */
        .ticket-card { color: #333333 !important; }
        .ticket-header, .ticket-header p, .ticket-header h4 { color: #ffffff !important; }
        .info-item span, #t_metode, #k_metode, #t_penumpang { color: #333333 !important; }
        .info-item label, .city-name, .ticket-footer label { color: #6c757d !important; }
        
        /* Warna Khusus Travel (Ungu) */
        #t_asal_singkat, #t_tujuan_singkat, #t_harga, #t_kursi { color: #352877 !important; }
        #t_supir { color: #28a745 !important; }
        
        /* Warna Khusus Kargo (Hijau) */
        #k_asal_singkat, #k_tujuan_singkat, #k_harga, #k_berat { color: #2e7d32 !important; }
    }
</style>

<div class="content-card bg-white p-4 rounded-4 shadow-sm" style="background: var(--card-bg) !important; border: 1px solid var(--border-color);">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3 no-print">
        <ul class="nav nav-pills-custom" id="pills-tab" role="tablist">
            <li class="nav-item"><button class="nav-link active" onclick="filterTab('semua', this)">Semua</button></li>
            <li class="nav-item"><button class="nav-link" onclick="filterTab('travel', this)">Travel</button></li>
            <li class="nav-item"><button class="nav-link" onclick="filterTab('kargo', this)">Kargo</button></li>
        </ul>

        <div class="d-flex align-items-center gap-2">
            <input type="date" id="filterTanggal" class="form-control border-0 shadow-sm" style="background: var(--bg-body); color: var(--text-main); border-radius: 12px; height: 48px; width: 160px; padding: 0 15px;" value="{{ $tanggal }}" onchange="window.location.href='?tanggal=' + this.value">
            <div class="search-container"><i class="bi bi-search text-muted"></i><input type="text" id="orderSearch" placeholder="Cari Nama/ID/Alamat..."></div>
        </div>
    </div>

    <div class="table-responsive no-print">
        <table class="table table-fixed align-middle">
            <thead class="text-uppercase small fw-bold text-muted">
                <tr>
                    <th class="col-id">ID Pesanan</th><th class="col-tgl">Jadwal</th><th class="col-user">Pelanggan</th>
                    <th class="col-alamat">Titik Antar/Jemput</th><th class="col-status text-center">Status</th><th class="col-aksi text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="mainTableBody">
                
                @foreach($travels as $travel)
                <tr class="order-row" data-type="travel">
                    <td><span class="fw-bold text-primary">{{ $travel->kode_booking }}</span></td>
                    <td>
                        <div class="fw-bold text-main">{{ \Carbon\Carbon::parse($travel->jadwal->tanggal_berangkat)->translatedFormat('d M Y') }}</div>
                        <div class="text-danger small fw-bold mt-1">{{ $travel->jadwal->jam_berangkat }}</div>
                    </td>
                    <td>
                        <div class="fw-bold text-main">{{ $travel->nama_penumpang }}</div>
                        <div class="text-muted small mt-1"><i class="bi bi-whatsapp me-1"></i> {{ $travel->nomor_wa }}</div>
                    </td>
                    <td>
                        <div class="small mb-1 text-main text-truncate"><strong>Jemput:</strong> {{ $travel->titik_jemput }}</div>
                        <div class="small text-main text-truncate"><strong>Antar:</strong> {{ $travel->titik_antar }}</div>
                        <div class="mt-2">
                            <span class="badge bg-primary" style="font-size: 0.65rem;">SEAT: {{ $travel->nomor_kursi }}</span>
                            <span class="badge ms-1" style="font-size: 0.65rem; background: var(--bg-body); color: var(--text-main); border: 1px solid var(--border-color);">{{ $travel->jadwal->armada->nama_armada }}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $travel->status_pesanan == 'lunas' ? 'bg-success' : 'bg-warning text-dark' }} py-2 px-3 rounded-pill" style="font-size: 0.75rem;">
                            {{ ucwords(str_replace('_', ' ', $travel->status_pesanan)) }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <button class="btn btn-sm btn-primary text-white fw-bold rounded-3 text-nowrap" 
                                onclick="openEditTravel(this)" 
                                data-kode="{{ $travel->kode_booking }}" 
                                data-nama="{{ $travel->nama_penumpang }}"
                                data-kursi="{{ $travel->nomor_kursi }}"
                                data-jemput="{{ $travel->titik_jemput }}"
                                data-antar="{{ $travel->titik_antar }}"
                                data-tgl="{{ \Carbon\Carbon::parse($travel->jadwal->tanggal_berangkat)->format('d M Y') }}"
                                data-jam="{{ \Carbon\Carbon::parse($travel->jadwal->jam_berangkat)->format('H:i') }}"
                                data-armada="{{ $travel->jadwal->armada->nama_armada ?? '-' }}"
                                data-supir="{{ $travel->jadwal->driver->nama_supir ?? '-' }}"
                                data-wasupir="{{ $travel->jadwal->driver->nomor_wa ?? '-' }}"
                                data-metode="{{ $travel->metode_bayar }}"
                                data-harga="{{ $travel->total_harga }}"
                                data-status="{{ $travel->status_pesanan }}"
                                data-wa="{{ $travel->nomor_wa }}">
                                <i class="bi bi-ticket-detailed"></i> Tiket
                            </button>
                            <form id="form-delete-travel-{{ $travel->id }}" action="{{ route('admin.pesanan.travel.destroy', $travel->id) }}" method="POST" class="m-0">
                                @csrf @method('DELETE')
                                <button type="button" class="btn-action-delete" onclick="confirmDelete('{{ $travel->id }}', 'travel')" title="Batalkan"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach

                @foreach($kargos as $kargo)
                <tr class="order-row" data-type="kargo">
                    <td><span class="fw-bold" style="color: #d97706;">{{ $kargo->kode_resi }}</span></td>
                    <td>
                        <div class="fw-bold text-main">{{ \Carbon\Carbon::parse($kargo->tanggal_berangkat)->translatedFormat('d M Y') }}</div>
                        <div class="text-muted small fw-bold mt-1">{{ $kargo->jam_berangkat }}</div>
                    </td>
                    <td>
                        <div class="fw-bold text-main">{{ $kargo->nama_pengirim }}</div>
                        <div class="text-muted small mt-1"><i class="bi bi-whatsapp me-1"></i> {{ $kargo->nomor_wa_pengirim }}</div>
                    </td>
                    <td>
                        <div class="small mb-1 text-main text-truncate"><strong>Asal:</strong> {{ $kargo->kota_asal }}</div>
                        <div class="small text-main text-truncate"><strong>Tujuan:</strong> {{ $kargo->kota_tujuan }}</div>
                        <div class="mt-2">
                            <span class="badge bg-warning text-dark" style="font-size: 0.65rem;">{{ $kargo->berat_barang }} KG</span>
                            @if($kargo->jadwal_id) <span class="badge bg-info text-dark ms-1" style="font-size: 0.65rem;">MOBIL: {{ $kargo->jadwal->armada->nama_armada }}</span>
                            @else <span class="badge bg-danger ms-1" style="font-size: 0.65rem;">BELUM ADA ARMADA</span> @endif
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $kargo->status_pesanan == 'lunas' ? 'bg-success' : 'bg-warning text-dark' }} py-2 px-3 rounded-pill" style="font-size: 0.75rem;">
                            {{ ucwords(str_replace('_', ' ', $kargo->status_pesanan)) }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <button class="btn btn-sm btn-warning text-dark fw-bold rounded-3 text-nowrap" 
                                onclick="openEditKargo(this)" 
                                data-kode="{{ $kargo->kode_resi }}" 
                                data-pengirim="{{ $kargo->nama_pengirim }}"
                                data-wapengirim="{{ $kargo->nomor_wa_pengirim }}"
                                data-penerima="{{ $kargo->nama_penerima }}"
                                data-berat="{{ $kargo->berat_barang }}"
                                data-asal="{{ $kargo->kota_asal }}"
                                data-tujuan="{{ $kargo->kota_tujuan }}"
                                data-tgl="{{ \Carbon\Carbon::parse($kargo->tanggal_berangkat)->format('d M Y') }}"
                                data-armada="{{ $kargo->jadwal->armada->nama_armada ?? '-' }}"
                                data-supir="{{ $kargo->jadwal->driver->nama_supir ?? '-' }}"
                                data-metode="{{ $kargo->metode_bayar ?? 'Manual/Transfer' }}"
                                data-harga="{{ $kargo->total_harga }}"
                                data-status="{{ $kargo->status_pesanan }}"
                                data-wa="{{ $kargo->nomor_wa_penerima }}">
                                <i class="bi bi-box-seam"></i> Resi
                            </button>
                            @if($kargo->status_pesanan === 'lunas')
                                <button class="btn-action-assign" onclick="openAssignModal({{ $kargo->id }}, '{{ $kargo->kode_resi }}', '{{ $kargo->jadwal_id ?? '' }}', '{{ $kargo->kota_asal }}', '{{ $kargo->kota_tujuan }}')" title="Pilih Mobil Pengangkut"><i class="bi bi-truck"></i></button>
                            @else
                                <button class="btn-action-assign" disabled style="opacity: 0.4; cursor: not-allowed; border-color: #ccc; color: #999; background: #f8f9fa;" title="Kargo belum di-ACC"><i class="bi bi-truck"></i></button>
                            @endif
                            <form id="form-delete-kargo-{{ $kargo->id }}" action="{{ route('admin.pesanan.kargo.destroy', $kargo->id) }}" method="POST" class="m-0">
                                @csrf @method('DELETE')
                                <button type="button" class="btn-action-delete" onclick="confirmDelete('{{ $kargo->id }}', 'kargo')" title="Batalkan"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($travels->isEmpty() && $kargos->isEmpty())
            <div class="text-center py-5 text-muted"><i class="bi bi-inbox fs-1"></i><p class="mt-2 fw-bold">Tidak ada pesanan masuk di tanggal ini.</p></div>
        @endif
    </div>

    <div class="manifest-panel no-print">
        <div class="d-flex align-items-center mb-4">
            <div style="width: 50px; height: 50px; border-radius: 14px; background: rgba(72, 61, 139, 0.1); color: var(--p-color); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;" class="me-3"><i class="bi bi-clipboard-check-fill"></i></div>
            <div><h5 class="fw-bold m-0 text-main">Cetak Manifest Keberangkatan</h5><p class="text-muted small m-0">Pilih mobil untuk mencetak daftar penumpang & kargo.</p></div>
        </div>
        <div class="row g-3">
            @forelse($jadwals as $jdw)
                <div class="col-md-4">
                    <button class="btn btn-primary w-100 fw-bold py-2 rounded-3 text-start px-3 shadow-sm" onclick="cetakManifest({{ $jdw->id }}, '{{ $jdw->armada->nama_armada }}', '{{ $jdw->driver->nama_supir ?? 'Belum Ditugaskan' }}', '{{ $jdw->jam_berangkat }}', '{{ $tanggal }}')">
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-printer me-2"></i> {{ $jdw->armada->nama_armada }}</span>                            
                            <span class="badge" style="background: rgba(255,255,255,0.25); color: #ffffff !important; border: 1px solid rgba(255,255,255,0.3);">
                                {{ $jdw->jam_berangkat }}
                            </span>
                        </div>
                        <small class="fw-normal d-block mt-1" style="font-size: 0.75rem; color: rgba(255,255,255,0.8);"><i class="bi bi-person me-1"></i> Supir: {{ $jdw->driver->nama_supir ?? '-' }}</small>
                    </button>
                </div>
            @empty
                <div class="col-12"><div class="alert alert-warning m-0">Belum ada armada yang dijadwalkan hari ini.</div></div>
            @endforelse
        </div>
    </div>
</div>

<div class="modal fade no-print" id="modalEditTravel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header border-bottom-0 pb-0 pt-3 px-3">
                <h5 class="fw-bold m-0 text-main"><i class="bi bi-ticket-detailed text-primary me-2"></i>Tiket <span id="v_tr_kode" class="text-primary ms-1"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <div class="p-3 rounded-3 mb-3" style="background: var(--bg-body); border: 1px solid var(--border-color);">
                    <div class="d-flex justify-content-between mb-3 pb-2 border-bottom border-secondary border-opacity-10">
                        <div>
                            <span class="small text-muted fw-bold d-block">Penumpang</span>
                            <span class="fw-bold text-main" id="v_tr_nama">-</span>
                            <div class="small text-muted mt-1"><i class="bi bi-whatsapp me-1"></i><span id="v_tr_wa">-</span></div>
                        </div>
                        <div class="text-end">
                            <span class="small text-muted fw-bold d-block">Kursi</span>
                            <span class="badge bg-primary" id="v_tr_kursi">-</span>
                        </div>
                    </div>
                    
                    <div class="mb-2">
                        <span class="small text-muted fw-bold d-block mb-1"><i class="bi bi-geo-alt-fill text-danger"></i> Jemput:</span>
                        <span class="fw-bold text-main lh-sm d-block" id="v_tr_jemput">-</span>
                    </div>
                    <div class="mt-3">
                        <span class="small text-muted fw-bold d-block mb-1"><i class="bi bi-flag-fill text-success"></i> Antar:</span>
                        <span class="fw-bold text-main lh-sm d-block" id="v_tr_antar">-</span>
                    </div>
                </div>

                <div class="alert alert-wa border-0 mb-0 rounded-3">
                    <h6 class="fw-bold mb-2" style="color: #128C7E;">Kirim & Cetak Tiket</h6>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn fw-bold text-dark w-50 py-2" style="background: rgba(220, 53, 69, 0.15); color: #dc3545 !important;" onclick="cetakTiket()">
                            <i class="bi bi-printer"></i> Cetak PDF
                        </button>
                        <button type="button" class="btn fw-bold text-white w-50 py-2" style="background: #25D366;" onclick="kirimTicketWa()">
                            <i class="bi bi-whatsapp"></i> Teks WA
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade no-print" id="modalEditKargo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header border-bottom-0 pb-0 pt-3 px-3">
                <h5 class="fw-bold m-0 text-main"><i class="bi bi-box-seam text-warning me-2"></i>Kargo <span id="v_kg_kode" class="text-warning ms-1"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <div class="p-3 rounded-3 mb-3" style="background: var(--bg-body); border: 1px solid var(--border-color);">
                    
                    <div class="mb-2 pb-2 border-bottom border-secondary border-opacity-10">
                        <span class="small text-muted fw-bold d-block">Pengirim</span>
                        <span class="fw-bold text-main" id="v_kg_pengirim">-</span>
                        <div class="small text-muted mt-1"><i class="bi bi-whatsapp me-1"></i><span id="v_kg_wa_pengirim">-</span></div>
                    </div>

                    <div class="d-flex justify-content-between mb-3 pb-2 border-bottom border-secondary border-opacity-10">
                        <div>
                            <span class="small text-muted fw-bold d-block">Penerima</span>
                            <span class="fw-bold text-main" id="v_kg_penerima">-</span>
                            <div class="small text-muted mt-1"><i class="bi bi-whatsapp me-1"></i><span id="v_kg_wa_penerima">-</span></div>
                        </div>
                        <div class="text-end">
                            <span class="small text-muted fw-bold d-block">Berat</span>
                            <span class="badge bg-warning text-dark" id="v_kg_berat">-</span>
                        </div>
                    </div>
                    
                    <div class="mb-2">
                        <span class="small text-muted fw-bold d-block mb-1"><i class="bi bi-box-arrow-up text-primary"></i> Asal:</span>
                        <span class="fw-bold text-main lh-sm d-block" id="v_kg_asal">-</span>
                    </div>
                    <div class="mt-3">
                        <span class="small text-muted fw-bold d-block mb-1"><i class="bi bi-box-arrow-in-down text-success"></i> Tujuan:</span>
                        <span class="fw-bold text-main lh-sm d-block" id="v_kg_tujuan">-</span>
                    </div>
                </div>

                <div class="alert alert-wa border-0 mb-0 rounded-3">
                    <h6 class="fw-bold mb-2" style="color: #128C7E;">Kirim & Cetak Resi</h6>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn fw-bold text-dark w-50 py-2" style="background: rgba(220, 53, 69, 0.15); color: #dc3545 !important;" onclick="cetakTiketKargo()">
                            <i class="bi bi-printer"></i> Cetak PDF
                        </button>
                        <button type="button" class="btn fw-bold text-white w-50 py-2" style="background: #25D366;" onclick="kirimKargoWa()">
                            <i class="bi bi-whatsapp"></i> Teks WA
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade no-print" id="assignModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"> 
        <form id="formAssignKargo" method="POST" class="modal-content rounded-4 shadow-lg">
            @csrf @method('PUT')
            <div class="modal-header border-0 pb-0"><h5 class="fw-bold m-0 text-main">Pilih Mobil Pengangkut</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body p-4">
                <p class="small text-muted mb-3">Tugaskan kargo <span class="fw-bold text-primary" id="assignOrderId">#</span> ke armada yang tersedia hari ini.</p>
                <select name="jadwal_id" class="form-select border-0 shadow-sm mb-4" id="selectArmada" style="background: var(--bg-body); color: var(--text-main); height: 50px; border-radius: 12px; font-weight:600;" required>
                    <option value="" disabled selected>-- Pilih Jadwal Mobil --</option>
                    
                    {{-- FIX: Pakai variabel jadwalsTersedia --}}
                    @forelse($jadwalsTersedia as $jdw) 
                        <option value="{{ $jdw->id }}" data-tgl="{{ $jdw->tanggal_berangkat }}" data-jam="{{ $jdw->jam_berangkat }}" data-asal="{{ strtolower($jdw->rute->kota_asal) }}" data-tujuan="{{ strtolower($jdw->rute->kota_tujuan) }}">
                            {{ $jdw->armada->nama_armada }} (Jam: {{ $jdw->jam_berangkat }}) - {{ $jdw->rute->kota_asal }}➔{{ $jdw->rute->kota_tujuan }}
                        </option> 
                    @empty
                        <option value="" disabled>-- Semua mobil hari ini sudah berangkat --</option>
                    @endforelse
                </select>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary w-50 fw-bold rounded-3 py-2" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary w-50 fw-bold rounded-3 py-2">Simpan Penugasan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="printableManifestArea" style="display: none;">
    <div style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px;">
        <h2 style="margin: 0; text-transform: uppercase;">Buana Berlian Travel</h2><p style="margin: 5px 0 0;">Daftar Manifest Penumpang & Kargo</p>
    </div>
    <div style="margin-bottom: 20px; font-size: 14px;">
        <table style="width: 100%;">
            <tr><td style="width: 120px;">Tanggal</td><td>: <span id="p_tgl"></span></td></tr>
            <tr><td>Armada / Jam</td><td>: <span id="p_armada"></span> / <span id="p_jam"></span></td></tr>
            <tr><td>Supir</td><td>: <span id="p_supir"></span></td></tr>
        </table>
    </div>
    <table style="width: 100%; border-collapse: collapse; border: 1px solid #000; font-size: 12px;">
        <thead><tr style="background: #eee;"><th style="border: 1px solid #000; padding: 8px; width: 50px;">SEAT</th><th style="border: 1px solid #000; padding: 8px; width: 180px;">NAMA / WA</th><th style="border: 1px solid #000; padding: 8px;">JEMPUT (ASAL)</th><th style="border: 1px solid #000; padding: 8px;">ANTAR (TUJUAN)</th></tr></thead>
        <tbody id="p_manifest_body"></tbody>
    </table>
    <div style="margin-top: 30px; text-align: right;"><p>Petugas Admin,</p><br><br><p>( ________________ )</p></div>
</div>

<div id="printableTicketArea" style="display: none; padding: 20px;">
    <div id="print-travel" class="ticket-card" style="display: none;">
        <div class="ticket-header header-travel">
            <div><p style="margin:0; font-size:0.8rem; font-weight:700;"><i class="bi bi-bus-front"></i> TRAVEL REGULER</p><h4 id="t_kode" style="margin:0; font-size:1.5rem; font-weight:700;">-</h4></div>
            <div style="text-align: right;"><img src="{{ asset('public/assets/img/LOGO.png') }}" style="height: 45px; filter: brightness(0) invert(1); object-fit: contain;" alt="Logo"></div>
        </div>
        <div class="ticket-body">
            <div>
                <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 25px;">
                    <div style="text-align: center;"><div style="font-size: 2.2rem; font-weight: 800; color: #352877; line-height:1;" id="t_asal_singkat">-</div><div style="font-size: 0.9rem; color: #6c757d; font-weight: 600;">ASAL</div></div>
                    <div style="font-size: 1.5rem; color: #6c757d;">➔</div>
                    <div style="text-align: center;"><div style="font-size: 2.2rem; font-weight: 800; color: #352877; line-height:1;" id="t_tujuan_singkat">-</div><div style="font-size: 0.9rem; color: #6c757d; font-weight: 600;">TUJUAN</div></div>
                </div>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                    <div class="info-item"><label>Jadwal</label><span id="t_jadwal">-</span></div>
                    <div class="info-item"><label>Armada</label><span id="t_armada">-</span></div>
                    <div class="info-item"><label>Penumpang</label><span id="t_penumpang">-</span></div>
                    <div class="info-item"><label>No. Kursi</label><span style="color: #352877; font-size: 1.3rem;" id="t_kursi">-</span></div>
                    <div class="info-item"><label>Nama Supir</label><span style="color: #28a745;" id="t_supir">-</span> <div style="font-size:0.85rem; color:#6c757d; font-weight: 600;" id="t_wasupir"></div></div>
                </div>
            </div>
            <div style="border-left: 2px dashed #dee2e6; padding-left: 30px; text-align: center;">
                <label style="font-size:0.8rem; color:#6c757d; margin-bottom:10px; font-weight:700;">STATUS TIKET</label>
                <div id="t_icon" style="font-size: 4.5rem; margin-bottom: 10px; line-height: 1;">⏳</div>
                <div id="t_status" style="padding: 8px 18px; border-radius: 30px; font-size: 0.9rem; font-weight: 800; display: inline-block;">-</div>
            </div>
        </div>
        <div class="ticket-footer">
            <div><label style="font-size: 0.75rem; color: #6c757d; display: block; font-weight:600;">Metode Pembayaran</label><div style="font-weight: 800; font-size: 1.2rem; color: #333; text-transform: uppercase;" id="t_metode">-</div></div>
            <div style="text-align: right;"><label style="font-size: 0.75rem; color: #6c757d; display: block; font-weight:600;">Total Harga</label><div style="font-weight: 900; font-size: 1.8rem; color: #352877;" id="t_harga">-</div></div>
        </div>
    </div>

    <div id="print-kargo" class="ticket-card" style="display: none;">
        <div class="ticket-header header-cargo">
            <div><p style="margin:0; font-size:0.8rem; font-weight:700;"><i class="bi bi-box-seam-fill"></i> KARGO EKSPRES</p><h4 id="k_kode" style="margin:0; font-size:1.5rem; font-weight:700;">-</h4></div>
            <div style="text-align: right;"><img src="{{ asset('public/assets/img/LOGO.png') }}" style="height: 45px; filter: brightness(0) invert(1); object-fit: contain;" alt="Logo"></div>
        </div>
        <div class="ticket-body">
            <div>
                <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 25px;">
                    <div style="text-align: center;"><div style="font-size: 2.2rem; font-weight: 800; color: #2e7d32; line-height:1;" id="k_asal_singkat">-</div><div style="font-size: 0.9rem; color: #6c757d; font-weight: 600;">ASAL</div></div>
                    <div style="font-size: 1.5rem; color: #6c757d;">➔</div>
                    <div style="text-align: center;"><div style="font-size: 2.2rem; font-weight: 800; color: #2e7d32; line-height:1;" id="k_tujuan_singkat">-</div><div style="font-size: 0.9rem; color: #6c757d; font-weight: 600;">TUJUAN</div></div>
                </div>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                    <div class="info-item"><label>Tgl Kirim</label><span id="k_jadwal">-</span></div>
                    <div class="info-item"><label>Penerima</label><span id="k_penerima">-</span></div>
                    <div class="info-item"><label>Berat</label><span style="color: #d4af37;" id="k_berat">-</span></div>
                    <div class="info-item"><label>Armada / Supir</label><span style="color: #2e7d32;" id="k_armada">-</span><div style="font-size:0.85rem; color:#6c757d; font-weight: 600;" id="k_supir"></div></div>
                </div>
            </div>
            <div style="border-left: 2px dashed #dee2e6; padding-left: 30px; text-align: center;">
                <label style="font-size:0.8rem; color:#6c757d; margin-bottom:10px; font-weight:700;">STATUS PAKET</label>
                <div id="k_icon" style="font-size: 4.5rem; margin-bottom: 10px; line-height: 1;">⏳</div>
                <div id="k_status" style="padding: 8px 18px; border-radius: 30px; font-size: 0.9rem; font-weight: 800; display: inline-block;">-</div>
            </div>
        </div>
        <div class="ticket-footer">
            <div><label style="font-size: 0.75rem; color: #6c757d; display: block; font-weight:600;">Metode Pembayaran</label><div style="font-weight: 800; font-size: 1.2rem; color: #333; text-transform: uppercase;" id="k_metode">-</div></div>
            <div style="text-align: right;"><label style="font-size: 0.75rem; color: #6c757d; display: block; font-weight:600;">Total Ongkir</label><div style="font-weight: 900; font-size: 1.8rem; color: #2e7d32;" id="k_harga">-</div></div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function filterTab(type, element) {
        document.querySelectorAll('.nav-pills-custom .nav-link').forEach(btn => btn.classList.remove('active'));
        element.classList.add('active');
        document.querySelectorAll('.order-row').forEach(row => { row.style.display = (type === 'semua' || row.getAttribute('data-type') === type) ? '' : 'none'; });
    }

    document.getElementById('orderSearch').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        document.querySelectorAll('.order-row').forEach(row => { row.style.display = row.innerText.toLowerCase().includes(filter) ? '' : 'none'; });
    });

    let tempKargo = {};

    // --- LOGIKA EDIT TRAVEL & CETAK E-TIKET ---
    let tempTiket = {}; // Memori penyimpan data untuk cetak tiket

    const singkatanKota = { 'pacitan': 'PCT', 'malang': 'MLG', 'blitar': 'BLT', 'trenggalek': 'TRG', 'tulungagung': 'TAG' };
    function getCityCode(address) {
        let kota = address ? address.split(' (')[0].toLowerCase() : '';
        return singkatanKota[kota] || (kota ? kota.substring(0,3).toUpperCase() : 'XXX');
    }

    // --- FUNGSI KLIK TIKET TRAVEL ---
    function openEditTravel(btn) {
        let kode = btn.getAttribute('data-kode');
        let nama = btn.getAttribute('data-nama');
        let kursi = btn.getAttribute('data-kursi');
        let jemput = btn.getAttribute('data-jemput');
        let antar = btn.getAttribute('data-antar');

        // 1. Tampilkan teks di panel modal
        document.getElementById('v_tr_kode').innerText = kode;
        document.getElementById('v_tr_nama').innerText = nama;
        document.getElementById('v_tr_wa').innerText = btn.getAttribute('data-wa');
        document.getElementById('v_tr_kursi').innerText = "Seat " + kursi;
        document.getElementById('v_tr_jemput').innerText = jemput;
        document.getElementById('v_tr_antar').innerText = antar;

        // 2. Simpan semua data ke memori sementara (Buat modal cetak & WA)
        tempTiket = { 
            kode: kode, nama: nama, tgl: btn.getAttribute('data-tgl'), 
            jam: btn.getAttribute('data-jam'), armada: btn.getAttribute('data-armada'), 
            supir: btn.getAttribute('data-supir'), wasupir: btn.getAttribute('data-wasupir'), 
            jemput: jemput, antar: antar, metode: btn.getAttribute('data-metode'), 
            harga: btn.getAttribute('data-harga'), status: btn.getAttribute('data-status'), 
            wa: btn.getAttribute('data-wa'), kursi: kursi 
        };
        
        new bootstrap.Modal(document.getElementById('modalEditTravel')).show();
    }

    // --- FUNGSI KLIK RESI KARGO ---
    function openEditKargo(btn) {
        let kode = btn.getAttribute('data-kode');
        let pengirim = btn.getAttribute('data-pengirim');
        let wapengirim = btn.getAttribute('data-wapengirim');
        let penerima = btn.getAttribute('data-penerima');
        let berat = btn.getAttribute('data-berat');
        let asal = btn.getAttribute('data-asal');
        let tujuan = btn.getAttribute('data-tujuan');
        let supir = btn.getAttribute('data-supir'); // AMBIL DATA SUPIR
        let metode = btn.getAttribute('data-metode'); // AMBIL DATA METODE

        document.getElementById('v_kg_kode').innerText = kode;
        document.getElementById('v_kg_pengirim').innerText = pengirim;
        document.getElementById('v_kg_wa_pengirim').innerText = wapengirim;
        document.getElementById('v_kg_penerima').innerText = penerima;
        document.getElementById('v_kg_wa_penerima').innerText = btn.getAttribute('data-wa');
        document.getElementById('v_kg_berat').innerText = berat + " Kg";
        document.getElementById('v_kg_asal').innerText = asal;
        document.getElementById('v_kg_tujuan').innerText = tujuan;

        // MASUKKAN KE MEMORI AGAR TIDAK ERROR SAAT CETAK
        tempKargo = { 
            kode: kode, penerima: penerima, pengirim: pengirim, 
            wa: btn.getAttribute('data-wa'), tgl: btn.getAttribute('data-tgl'), 
            armada: btn.getAttribute('data-armada'), berat: berat, asal: asal, 
            tujuan: tujuan, harga: btn.getAttribute('data-harga'), status: btn.getAttribute('data-status'),
            supir: supir, metode: metode
        };

        new bootstrap.Modal(document.getElementById('modalEditKargo')).show();
    }

    function openAssignModal(kargoId, resi, currentId, kargoAsal, kargoTujuan) {
        document.getElementById('assignOrderId').innerText = resi;
        document.getElementById('formAssignKargo').action = `/admin/kargo/${kargoId}/assign`;
        
        let selectArmada = document.getElementById('selectArmada');
        let sekarang = new Date();

        // FIX: Potong teks di dalam kurung dan bersihkan spasi agar cocok
        let cleanKargoAsal = kargoAsal.split('(')[0].trim().toLowerCase();
        let cleanKargoTujuan = kargoTujuan.split('(')[0].trim().toLowerCase();

        Array.from(selectArmada.options).forEach(opt => {
            if(opt.value === "") return; 

            let tgl = opt.getAttribute('data-tgl');
            let jam = opt.getAttribute('data-jam');
            
            // Ambil rute armada dan bersihkan juga
            let rawAsal = opt.getAttribute('data-asal') || '';
            let rawTujuan = opt.getAttribute('data-tujuan') || '';
            let ruteAsal = rawAsal.split('(')[0].trim().toLowerCase();
            let ruteTujuan = rawTujuan.split('(')[0].trim().toLowerCase();

            let isDisabled = false;
            let labelTambahan = '';

            // 1. CEK VALIDASI RUTE (Sekarang sudah kebal dengan embel-embel "(alun)")
            if (ruteAsal !== cleanKargoAsal || ruteTujuan !== cleanKargoTujuan) {
                isDisabled = true;
                labelTambahan = ' 🔴 (Beda Arah/Rute)';
            }

            // 2. CEK VALIDASI WAKTU
            if (!isDisabled && tgl && jam) {
                let waktuBerangkat = new Date(`${tgl}T${jam}`);
                let batasWaktuAssign = new Date(waktuBerangkat.getTime() - (30 * 60 * 1000));

                if (sekarang >= batasWaktuAssign) {
                    isDisabled = true;
                    labelTambahan = ' 🔴 (Persiapan Jalan)';
                }
            }

            opt.disabled = isDisabled;
            opt.text = opt.text.replace(' 🔴 (Beda Arah/Rute)', '').replace(' 🔴 (Persiapan Jalan)', '');
            if (isDisabled) opt.text += labelTambahan;
        });

        selectArmada.value = currentId || "";
        if (selectArmada.selectedOptions.length > 0 && selectArmada.selectedOptions[0].disabled) {
            selectArmada.value = "";
        }

        new bootstrap.Modal(document.getElementById('assignModal')).show();
    }

    function confirmDelete(id, type) {
        const isDark = document.body.getAttribute('data-theme') === 'dark';
        Swal.fire({
            title: 'Batalkan Pesanan?', text: "Pesanan ini akan dihapus permanen!", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal',
            background: isDark ? 'var(--card-bg)' : '#ffffff', color: isDark ? 'var(--text-main)' : '#000000'
        }).then((result) => { if (result.isConfirmed) document.getElementById('form-delete-' + type + '-' + id).submit(); });
    }

    // --- LOGIC CETAK MANIFEST ---
    const dbTravels = @json($travels);
    const dbKargos = @json($kargos);

    function cetakManifest(jadwalId, armada, supir, jam, tgl) {
        document.getElementById('p_tgl').innerText = tgl;
        document.getElementById('p_armada').innerText = armada;
        document.getElementById('p_supir').innerText = supir;
        document.getElementById('p_jam').innerText = jam;

        let filteredTravel = dbTravels.filter(t => t.jadwal_id == jadwalId);
        let filteredKargo = dbKargos.filter(k => k.jadwal_id == jadwalId);
        let html = '';

        if(filteredTravel.length > 0) {
            filteredTravel.forEach(t => {
                html += `<tr><td style="border:1px solid #000; padding:8px; text-align:center; font-weight:bold;">${t.nomor_kursi}</td><td style="border:1px solid #000; padding:8px;"><b>${t.nama_penumpang}</b><br>${t.nomor_wa}</td><td style="border:1px solid #000; padding:8px;">${t.titik_jemput}</td><td style="border:1px solid #000; padding:8px;">${t.titik_antar}</td></tr>`;
            });
        }
        if(filteredKargo.length > 0) {
            filteredKargo.forEach(k => {
                html += `<tr><td style="border:1px solid #000; padding:8px; text-align:center; font-size:10px; background:#f9f9f9;">PKG<br>${k.berat_barang}kg</td><td style="border:1px solid #000; padding:8px;"><b>(K) ${k.nama_pengirim}</b><br>Penerima: ${k.nama_penerima}</td><td style="border:1px solid #000; padding:8px;">${k.kota_asal}</td><td style="border:1px solid #000; padding:8px;">${k.kota_tujuan}</td></tr>`;
            });
        }
        if(html === '') html = '<tr><td colspan="4" style="text-align:center; padding:20px; border:1px solid #000;">Belum ada muatan di mobil ini.</td></tr>';
        
        document.getElementById('p_manifest_body').innerHTML = html;
        
        const cssStyle = document.getElementById('custom-print-css').outerHTML;
        const area = document.getElementById('printableManifestArea');
        const original = document.body.innerHTML;
        
        document.getElementById('printableTicketArea').style.display = 'none';
        document.getElementById('printableManifestArea').style.display = 'block';
        
        window.print();
        setTimeout(() => { document.getElementById('printableTicketArea').style.display = 'none'; }, 500);
    }

    // --- 1. CETAK TIKET KARGO ---
    function cetakTiketKargo() {
        document.getElementById('print-travel').style.display = 'none';
        document.getElementById('print-kargo').style.display = 'block';

        document.getElementById('k_kode').innerText = tempKargo.kode;
        document.getElementById('k_asal_singkat').innerText = getCityCode(tempKargo.asal);
        document.getElementById('k_tujuan_singkat').innerText = getCityCode(tempKargo.tujuan);
        document.getElementById('k_jadwal').innerText = tempKargo.tgl;
        document.getElementById('k_penerima').innerText = tempKargo.penerima;
        document.getElementById('k_berat').innerText = tempKargo.berat + ' Kg';
        document.getElementById('printableManifestArea').style.display = 'none';
        document.getElementById('printableTicketArea').style.display = 'block';

        document.getElementById('k_armada').innerText = tempKargo.armada;
        document.getElementById('k_supir').innerText = tempKargo.supir;
        document.getElementById('k_metode').innerText = tempKargo.metode.toUpperCase();
        document.getElementById('k_harga').innerText = "Rp " + tempKargo.harga;

        let statusEl = document.getElementById('k_status');
        let iconEl = document.getElementById('k_icon');
        
        // FIX: Pastikan membandingkan dengan huruf kecil (sesuai database)
        let dbStatus = (tempKargo.status || '').toLowerCase();
        let displayStatus = dbStatus.replace('_', ' ').toUpperCase();
        
        statusEl.innerText = displayStatus;

        if(dbStatus === 'lunas') { 
            statusEl.style.background = '#28a745'; statusEl.style.color = '#fff'; iconEl.innerText = '✅'; 
        } else if(dbStatus === 'batal' || dbStatus === 'ditolak') { 
            statusEl.style.background = '#dc3545'; statusEl.style.color = '#fff'; iconEl.innerText = '❌'; 
        } else { 
            statusEl.style.background = '#ffc107'; statusEl.style.color = '#000'; iconEl.innerText = '⏳'; 
        }

        window.print();
        setTimeout(() => { document.getElementById('printableTicketArea').style.display = 'none'; }, 500);
    }

    // --- 2. CETAK TIKET TRAVEL ---
    function cetakTiket() {
        document.getElementById('t_kode').innerText = tempTiket.kode;
        document.getElementById('t_asal_singkat').innerText = getCityCode(tempTiket.jemput);
        document.getElementById('t_tujuan_singkat').innerText = getCityCode(tempTiket.antar);
        document.getElementById('t_jadwal').innerText = `${tempTiket.tgl} (${tempTiket.jam})`;
        document.getElementById('t_armada').innerText = tempTiket.armada;
        document.getElementById('t_penumpang').innerText = tempTiket.nama;
        document.getElementById('t_kursi').innerText = tempTiket.kursi;
        document.getElementById('t_supir').innerText = tempTiket.supir;
        document.getElementById('t_wasupir').innerText = tempTiket.wasupir !== '-' ? `(${tempTiket.wasupir})` : '';
        document.getElementById('t_metode').innerText = tempTiket.metode;
        document.getElementById('t_harga').innerText = `Rp ${tempTiket.harga}`;
        
        document.getElementById('print-travel').style.display = 'block';
        document.getElementById('print-kargo').style.display = 'none';
        
        let statusEl = document.getElementById('t_status');
        let iconEl = document.getElementById('t_icon');
        let statText = tempTiket.status.replace('_', ' ').toUpperCase();
        statusEl.innerText = statText;

        if(tempTiket.status === 'lunas') {
            statusEl.style.background = '#28a745'; statusEl.style.color = '#fff'; iconEl.innerText = '✅';
        } else if(tempTiket.status === 'batal' || tempTiket.status === 'ditolak') {
            statusEl.style.background = '#dc3545'; statusEl.style.color = '#fff'; iconEl.innerText = '❌';
        } else {
            statusEl.style.background = '#ffc107'; statusEl.style.color = '#000'; iconEl.innerText = '⏳';
        }

        document.getElementById('printableManifestArea').style.display = 'none';
        document.getElementById('printableTicketArea').style.display = 'block';
        
        window.print();
        setTimeout(() => { document.getElementById('printableTicketArea').style.display = 'none'; }, 500);
    }

    // --- 3. WHATSAPP KARGO ---
    function kirimKargoWa() {
        let no_wa = tempKargo.wa;
        if(no_wa && no_wa.startsWith('0')) { no_wa = '62' + no_wa.substring(1); }
        let domain = window.location.origin;

        let pesan = `Halo Bosku! \n`;
        pesan += `Terima kasih telah mempercayakan pengiriman kargo Anda kepada *Buana Berlian*.\n\n`;
        pesan += `Pembayaran kargo Anda telah kami terima dan berstatus *${tempKargo.status.replace('_', ' ').toUpperCase()}*. Berikut rinciannya:\n\n`;
        pesan += ` *Kode Resi:* ${tempKargo.kode}\n`;
        pesan += ` *Pengirim:* ${tempKargo.pengirim}\n`;
        pesan += ` *Penerima:* ${tempKargo.penerima}\n`;
        pesan += ` *Dari:* ${tempKargo.asal}\n`;
        pesan += ` *Tujuan:* ${tempKargo.tujuan}\n`;
        pesan += ` *Berat:* ${tempKargo.berat} Kg\n\n`;
        pesan += `Silakan klik link di bawah ini untuk mengunduh struk dan memantau status barang Anda:\n`;
        pesan += ` ${domain}/cek-tiket/${tempKargo.kode}\n\n`;
        pesan += `Terima kasih! `;

        window.open(`https://wa.me/${no_wa}?text=${encodeURIComponent(pesan)}`, '_blank');
    }

    // --- 4. WHATSAPP TRAVEL ---
    function kirimTicketWa() {
        let no_wa = tempTiket.wa;
        if(no_wa && no_wa.startsWith('0')) { no_wa = '62' + no_wa.substring(1); }
        let domain = window.location.origin;

        let pesan = `Halo Bosku! \n`;
        pesan += `Terima kasih telah memilih *Buana Berlian*.\n\n`;
        pesan += `Pembayaran tiket Anda telah kami terima dan berstatus *${tempTiket.status.replace('_', ' ').toUpperCase()}*. Berikut rinciannya:\n\n`;
        pesan += ` *Kode Booking:* ${tempTiket.kode}\n`;
        pesan += ` *Nama:* ${tempTiket.nama}\n`;
        pesan += ` *Jemput:* ${tempTiket.jemput}\n`;
        pesan += ` *Antar:* ${tempTiket.antar}\n`;
        pesan += ` *Jadwal:* ${tempTiket.tgl}, ${tempTiket.jam} WIB\n`;
        pesan += ` *No Kursi:* ${tempTiket.kursi}\n`;
        pesan += ` *Supir:* ${tempTiket.supir} ${tempTiket.wasupir !== '-' ? '('+tempTiket.wasupir+')' : ''}\n\n`;
        pesan += `Silakan klik link di bawah ini untuk melihat dan mengunduh E-Tiket Anda:\n`;
        pesan += ` ${domain}/cek-tiket/${tempTiket.kode}\n\n`;
        pesan += `Semoga perjalanannya aman dan menyenangkan! `;

        window.open(`https://wa.me/${no_wa}?text=${encodeURIComponent(pesan)}`, '_blank');
    }

</script>
@endpush

@endsection