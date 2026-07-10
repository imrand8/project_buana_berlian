@extends('admin.partials.master')

@section('page_title', 'Data Master')
@section('page_subtitle', 'Kelola data utama rute travel, tarif kargo, armada, dan supir.')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* --- TABS SINKRONISASI --- */
    .nav-pills-custom { background: #e2e8f0; padding: 6px; border-radius: 16px; display: inline-flex; gap: 4px; border: 1px solid rgba(0,0,0,0.05); overflow-x: auto; max-width: 100%; white-space: nowrap;}
    .nav-pills-custom::-webkit-scrollbar { display: none; }
    [data-theme="dark"] .nav-pills-custom { background: #16191f; border-color: rgba(255,255,255,0.05); }
    .nav-pills-custom .nav-link { border-radius: 12px; padding: 10px 22px; font-weight: 700; color: var(--text-muted); border: none; font-size: 0.85rem; transition: 0.3s; cursor: pointer; }
    .nav-pills-custom .nav-link.active { background: var(--card-bg) !important; color: var(--p-color) !important; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    [data-theme="dark"] .nav-pills-custom .nav-link.active { background: var(--p-color) !important; color: white !important; }

    /* --- CARDS & TABLES --- */
    .custom-card { 
        background: var(--card-bg); 
        border-radius: 16px; 
        padding: 16px; 
        border: 1px solid var(--border-color); 
        box-shadow: 0 4px 15px rgba(0,0,0,0.02); 
        color: var(--text-main); 
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    
    .hover-shadow { transition: all 0.3s ease; border: 1px solid var(--border-color); }
    .hover-shadow:hover { 
        transform: translateY(-4px); 
        box-shadow: 0 12px 24px rgba(0,0,0,0.08) !important; 
        border-color: var(--p-color);
    }
    
    .price-box {
        background: rgba(72, 61, 139, 0.05); 
        border-radius: 10px;
        border: 1px solid rgba(72, 61, 139, 0.1);
    }

    [data-theme="dark"] .price-box {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .action-buttons-wrapper {
        background: var(--bg-body);
        border-radius: 8px;
        border: 1px solid var(--border-color);
    }

    /* --- THEME FIXES --- */
    [data-theme="dark"] .custom-card { background: var(--card-bg); border-color: #2d333b; }
    [data-theme="dark"] .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }
    [data-theme="dark"] .table { color: #f8fafc; }
    [data-theme="dark"] .table th, [data-theme="dark"] .table td { border-color: #2d333b; color: #f8fafc; }
    [data-theme="dark"] .text-muted { color: #94a3b8 !important; }
    [data-theme="dark"] .input-group-text.bg-light { background-color: #1e293b !important; color: #cbd5e1 !important; border-color: #444 !important; }
    
    .badge-info-custom { background: rgba(0,0,0,0.05); color: var(--text-main); border: 1px solid var(--border-color); }
    [data-theme="dark"] .badge-info-custom { background: rgba(255,255,255,0.05); color: #f8fafc; border-color: #2d333b; }

    /* FIX SWEETALERT SUCCESS BG DI DARK MODE */
    [data-theme="dark"] .swal2-success-circular-line-left, 
    [data-theme="dark"] .swal2-success-circular-line-right, 
    [data-theme="dark"] .swal2-success-fix { background-color: var(--card-bg) !important; }
    [data-theme="dark"] .swal2-success-ring { background-color: transparent !important; }
    [data-theme="dark"] div:where(.swal2-container) div:where(.swal2-popup) { background-color: var(--card-bg) !important; border: 1px solid var(--border-color); }
    [data-theme="dark"] .swal2-popup { background-color: var(--card-bg) !important; color: var(--text-main) !important; border: 1px solid var(--border-color); }
    [data-theme="dark"] .swal2-title, [data-theme="dark"] .swal2-html-container { color: var(--text-main) !important; }
    
    /* --- UPGRADE MODAL & INPUT UI --- */
    .modal-content { 
        background-color: var(--card-bg) !important; 
        color: var(--text-main); 
        border: 1px solid rgba(255, 255, 255, 0.08) !important; 
        border-radius: 20px !important; 
        box-shadow: 0 25px 50px rgba(0,0,0,0.3) !important;
    }
    
    .modal-header {
        border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        padding: 20px 24px 16px !important;
    }
    
    .modal-body {
        padding: 20px 24px 24px !important;
    }

    .custom-input { 
        background: rgba(0, 0, 0, 0.15) !important; 
        color: var(--text-main) !important; 
        border: 1px solid rgba(255, 255, 255, 0.1) !important; 
        border-radius: 12px !important;
        padding: 12px 16px !important;
        font-size: 0.95rem !important;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important; 
    }
    
    [data-theme="dark"] .custom-input { 
        background: rgba(0, 0, 0, 0.3) !important; 
        border-color: rgba(255, 255, 255, 0.08) !important; 
    }
    
    .custom-input:focus { 
        background: var(--bg-body) !important; 
        border-color: var(--p-color) !important; 
        box-shadow: 0 0 0 4px rgba(72, 61, 139, 0.25) !important; 
        outline: none;
    }

    .custom-input::file-selector-button {
        background: rgba(255, 255, 255, 0.05);
        color: var(--text-main);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        padding: 6px 14px;
        margin-right: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }
    
    .custom-input::file-selector-button:hover {
        background: var(--p-color);
        color: #fff;
        border-color: var(--p-color);
    }

    .modal-body .btn {
        padding: 12px !important;
        border-radius: 12px !important;
        font-weight: 700 !important;
        letter-spacing: 0.5px;
    }
    
    .btn-cancel-custom { 
        background: rgba(255, 255, 255, 0.05) !important; 
        color: var(--text-main) !important; 
        border: 1px solid rgba(255, 255, 255, 0.1) !important; 
        transition: 0.3s;
    }
    
    .btn-cancel-custom:hover { 
        background: rgba(255, 255, 255, 0.1) !important; 
    }
</style>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <ul class="nav nav-pills-custom" id="pills-tab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-rute">Harga Rute Travel</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-kargo">Tarif Kargo</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-armada">Data Armada</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-supir">Data Supir</button>
        </li>
    </ul>
    <button class="btn btn-primary fw-bold rounded-3 shadow-sm" onclick="openAddModal()"><i class="bi bi-plus-lg me-2"></i>Tambah Data</button>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const isDark = document.body.getAttribute('data-theme') === 'dark';
        const bgColor = isDark ? '#16191f' : '#ffffff';
        const txtColor = isDark ? '#ffffff' : '#000000';

        // 1. Munculkan Notif Success
        @if(session('success'))
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: `{{ session('success') }}`, showConfirmButton: false, timer: 3000, background: bgColor, color: txtColor });
        @endif

        // 2. Munculkan Notif Error (Misal: Rute Duplikat)
        @if($errors->any())
            Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: `{{ $errors->first() }}`, showConfirmButton: false, timer: 4000, background: bgColor, color: txtColor });
        @endif

        // 3. LOGIKA MEMORI TAB (Biar nggak loncat ke awal)
        let activeTab = localStorage.getItem('activeMasterTab');
        if (activeTab) {
            let tabBtn = document.querySelector(`button[data-bs-target="${activeTab}"]`);
            if (tabBtn) new bootstrap.Tab(tabBtn).show();
        }

        document.querySelectorAll('button[data-bs-toggle="pill"]').forEach(btn => {
            btn.addEventListener('shown.bs.tab', function (e) {
                localStorage.setItem('activeMasterTab', e.target.getAttribute('data-bs-target'));
            });
        });
    });
</script>

<div class="tab-content" id="pills-tabContent">
    
    <div class="tab-pane fade show active" id="tab-rute">
    <!-- Header tetap di luar card grid -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold m-0"><i class="bi bi-journal-bookmark text-primary me-2"></i>Tarif Rute Travel (PP)</h5>
        <span class="badge badge-info-custom py-2 px-3"><i class="bi bi-info-circle me-1"></i>Berlaku untuk rute sebaliknya</span>
    </div>

<div class="row g-3"> 
        @foreach($rutes as $rute)
        <div class="col-12">
            <div class="custom-card hover-shadow p-3 d-flex align-items-center">
                
                <div class="row align-items-center w-100 m-0">
                    
                    <div class="col-12 col-md-3 d-flex align-items-center gap-3 mb-2 mb-md-0">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                            <i class="bi bi-geo-alt-fill fs-5"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-bold" style="font-size: 0.75rem;">KOTA ASAL</div>
                            <h6 class="fw-bold text-main m-0" style="font-size: 1.05rem;">{{ $rute->kota_asal }}</h6>
                        </div>
                    </div>

                    <div class="col-12 col-md-1 text-center d-none d-md-block text-primary opacity-75">
                        <i class="bi bi-arrow-left-right fs-4"></i>
                    </div>

                    <div class="col-12 col-md-3 text-start mb-3 mb-md-0 pl-md-4">
                         <div class="text-muted small fw-bold" style="font-size: 0.75rem;">KOTA TUJUAN</div>
                         <h6 class="fw-bold text-main m-0" style="font-size: 1.05rem;">{{ $rute->kota_tujuan }}</h6>
                    </div>

                    <div class="col-12 col-md-3 mb-3 mb-md-0">
                        <div class="price-box py-2 px-3 d-flex flex-column justify-content-center text-center text-md-start">
                            <span class="text-muted fw-bold mb-1" style="font-size: 0.7rem;">HARGA / SEAT</span>
                            <span class="fw-bolder text-primary fs-5 m-0" style="line-height: 1;">Rp {{ number_format($rute->harga_reguler, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="col-12 col-md-2 text-md-end text-center">
                        <div class="action-buttons-wrapper d-inline-flex gap-2 p-1">
                            <button type="button" class="btn btn-sm btn-light text-primary rounded-2 px-3 py-2 border-0" 
                                    onclick="openEditRute({{ $rute->id }}, '{{ $rute->kota_asal }}', '{{ $rute->kota_tujuan }}', {{ $rute->harga_reguler }})" title="Edit">
                                <i class="bi bi-pencil-square fs-5"></i>
                            </button>
                            <form action="{{ route('admin.rute.destroy', $rute->id) }}" method="POST" id="form-delete-rute-{{ $rute->id }}" class="m-0">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-light text-danger rounded-2 px-3 py-2 border-0" 
                                        onclick="confirmDeleteRute({{ $rute->id }}, '{{ $rute->kota_asal }}', '{{ $rute->kota_tujuan }}')" title="Hapus">
                                    <i class="bi bi-trash-fill fs-5"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

    <div class="tab-pane fade" id="tab-kargo">
        <div class="row g-4 align-items-stretch">
            
            <div class="col-md-7">
                <div class="custom-card h-100 p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                        <div>
                            <h5 class="fw-bold m-0"><i class="bi bi-box-seam text-warning me-2"></i>Tarif Kargo Global</h5>
                            <small class="text-muted">Basis perhitungan kargo untuk pelanggan</small>
                        </div>
                        <button type="button" class="btn btn-primary fw-bold px-3 rounded-3 shadow-sm" onclick="openEditKargo()">
                            <i class="bi bi-pencil-square me-2"></i>Edit Tarif
                        </button>
                    </div>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3" style="background: var(--bg-body); border: 1px solid var(--border-color);">
                                <div class="text-muted small fw-bold mb-1">1 Kg Pertama (Tarif Dasar)</div>
                                <div class="fs-4 fw-bolder text-main" id="dispBasePrice">Rp {{ number_format($tarif->harga_dasar ?? 50000, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3" style="background: var(--bg-body); border: 1px solid var(--border-color);">
                                <div class="text-muted small fw-bold mb-1">Kg Selanjutnya (Per Kg)</div>
                                <div class="fs-4 fw-bolder text-main" id="dispNextPrice">Rp {{ number_format($tarif->harga_selanjutnya ?? 25000, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert border-0 bg-primary bg-opacity-10 d-flex align-items-center m-0 rounded-3">
                        <i class="bi bi-info-circle-fill text-primary fs-3 me-3"></i>
                        <div class="small text-main">
                            <strong>Rumus Kargo:</strong> Tarif Dasar (1 Kg) + (Sisa Berat x Harga Kg Selanjutnya). 
                            <br><span class="text-muted opacity-75">Sistem akan otomatis menghitung berdasarkan tarif di atas.</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="custom-card h-100 p-0 overflow-hidden" style="border: none; box-shadow: 0 10px 30px rgba(72, 61, 139, 0.08);">
                    <div class="bg-primary bg-opacity-10 p-4 text-center border-bottom border-primary border-opacity-10">
                        <h6 class="fw-bold m-0 text-primary"><i class="bi bi-calculator me-2"></i>Kalkulator Simulasi</h6>
                    </div>
                    
                    <div class="p-4">
                        <div class="mb-4">
                            <label class="small fw-bold mb-2 text-muted text-center w-100">Masukkan Berat Barang</label>
                            <div class="input-group input-group-lg" style="max-width: 200px; margin: 0 auto;">
                                <input type="number" id="kargoWeight" class="form-control text-center fw-bold bg-light border-primary border-opacity-25" value="5" min="1" style="font-size: 1.5rem; color: var(--p-color) !important;">
                                <span class="input-group-text bg-primary text-white border-primary fw-bold">Kg</span>
                            </div>
                        </div>

                        <div class="p-3 rounded-3 mb-2" style="background: var(--bg-body); border: 1px dashed var(--border-color);">
                            <div class="d-flex justify-content-between small mb-2">
                                <span class="text-muted">1 Kg Pertama:</span>
                                <span class="fw-bold text-main" id="simBasePrice">Rp {{ number_format($tarif->harga_dasar ?? 50000, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between small">
                                <span class="text-muted">Tambahan (<span id="extraWeight">4</span> Kg x <span id="simNextPriceText">{{ number_format(($tarif->harga_selanjutnya ?? 25000), 0, ',', '.') }}</span>):</span>
                                <span class="fw-bold text-main" id="extraPrice">Rp 100.000</span>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                            <span class="fw-bold text-muted">Total Tagihan:</span>
                            <span class="fs-3 fw-black text-primary" id="totalPrice" style="font-weight: 900;">Rp 150.000</span>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <div class="tab-pane fade" id="tab-armada">
        <div class="row g-4">
            @foreach($armadas as $armada)
            <div class="col-md-6 col-lg-4">
                <div class="custom-card h-100 p-0 overflow-hidden hover-shadow border-0" style="box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                    
                    <div class="position-relative d-flex justify-content-center align-items-center" style="height: 160px; background: var(--bg-body); border-bottom: 1px solid var(--border-color);">
                        <span class="position-absolute top-0 end-0 m-3 badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fw-bold">
                            <i class="bi bi-check-circle-fill me-1"></i> Aktif
                        </span>
                        
                        @if($armada->image)
                            <img src="{{ asset('storage/app/public/' . $armada->image) }}" alt="{{ $armada->nama_armada }}" 
                                 style="max-height: 120px; max-width: 80%; object-fit: contain; filter: drop-shadow(0 10px 10px rgba(0,0,0,0.15));"
                                 onerror="this.onerror=null; this.outerHTML='<i class=\'bi bi-car-front-fill text-primary opacity-25\' style=\'font-size: 5rem;\'></i>';">
                        @else
                            <i class="bi bi-car-front-fill text-primary opacity-25" style="font-size: 5rem;"></i>
                        @endif
                    </div>

                    <div class="p-4 text-center">
                        <h5 class="fw-bold mb-1 text-main">{{ $armada->nama_armada }}</h5>
                        <p class="text-muted small mb-3"><i class="bi bi-people-fill me-1"></i>Kapasitas: {{ $armada->kapasitas_kursi }} Seat</p>
                        
                        <div class="d-inline-block px-4 py-2 mb-4 rounded-2" style="background: #ffcc00; border: 2px solid #222; box-shadow: inset 0 0 5px rgba(0,0,0,0.2);">
                            <h5 class="fw-bolder m-0 text-dark" style="font-family: monospace; letter-spacing: 2px;">{{ $armada->plat_nomor }}</h5>
                        </div>
                        
                        <div class="d-flex gap-2 justify-content-center">
                            <button class="btn btn-light text-primary fw-bold w-50" style="background: rgba(72, 61, 139, 0.05);" 
                                    onclick="openEditArmada({{ $armada->id }}, '{{ $armada->nama_armada }}', '{{ $armada->plat_nomor }}')">
                                <i class="bi bi-pencil-square me-1"></i> Edit
                            </button>
                            <form action="{{ route('admin.armada.destroy', $armada->id) }}" method="POST" class="w-50" id="form-delete-armada-{{ $armada->id }}">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-light text-danger fw-bold w-100" style="background: rgba(220, 53, 69, 0.05);" 
                                        onclick="confirmDeleteArmada({{ $armada->id }}, '{{ $armada->nama_armada }}')">
                                    <i class="bi bi-trash-fill me-1"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="tab-pane fade" id="tab-supir">
        <div class="custom-card border-0 shadow-sm p-0 overflow-hidden">
            <div class="p-4 border-bottom">
                <h5 class="fw-bold m-0"><i class="bi bi-person-vcard text-success me-2"></i>Daftar Supir Aktif</h5>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--text-main);">
                    <thead class="bg-primary bg-opacity-10 text-uppercase small fw-bold text-muted">
                        <tr>
                            <th class="ps-4 py-3 border-0" style="width: 60px;">No</th>
                            <th class="py-3 border-0">Profil Supir</th>
                            <th class="py-3 border-0">Kontak WhatsApp</th>
                            <th class="text-end pe-4 py-3 border-0" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($drivers as $index => $driver)
                        <tr style="border-bottom: 1px solid var(--border-color); transition: 0.2s;">
                            <td class="ps-4 fw-bold text-muted">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3 py-2">
                                    <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center flex-shrink-0" style="width: 45px; height: 45px;">
                                        <i class="bi bi-person-fill fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold m-0 text-main">{{ $driver->nama_supir }}</h6>
                                        <small class="text-muted">Mitra Driver</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', $driver->no_hp) }}" target="_blank" class="text-decoration-none">
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill">
                                        <i class="bi bi-whatsapp me-2"></i>{{ $driver->no_hp }}
                                    </span>
                                </a>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-2">
                                    <button class="btn btn-sm btn-light text-primary rounded-circle" style="width: 35px; height: 35px;" 
                                            onclick="openEditSupir({{ $driver->id }}, '{{ $driver->nama_supir }}', '{{ $driver->no_hp }}')" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    
                                    <form action="{{ route('admin.driver.destroy', $driver->id) }}" method="POST" id="form-delete-supir-{{ $driver->id }}" class="m-0">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-light text-danger rounded-circle" style="width: 35px; height: 35px;" 
                                                onclick="confirmDeleteSupir({{ $driver->id }}, '{{ $driver->nama_supir }}')" title="Hapus">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</div>

<div class="modal fade" id="modalEditRute" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <form id="formEditRute" method="POST" class="modal-content shadow-lg" style="border-radius: 16px;">
            @csrf @method('PUT')
            <div class="modal-header border-0 pb-0">
                <h6 class="fw-bold m-0 text-primary"><i class="bi bi-pencil-square me-2"></i>Edit Harga Rute</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="small text-muted mb-3" id="editRuteSubtitle">Malang - Pacitan</p>
                <div class="mb-3">
                    <label class="small fw-bold mb-1 text-muted">Harga per Seat Baru (Rp)</label>
                    <input type="number" class="form-control custom-input fw-bold" name="harga" id="inputEditRutePrice" required>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-cancel-custom w-50 fw-bold rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary w-50 fw-bold rounded-3">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEditKargo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <form action="{{ route('admin.kargo.update') }}" method="POST" class="modal-content shadow-lg" style="border-radius: 16px;">
            @csrf @method('PUT')
            <div class="modal-header border-0 pb-0">
                <h6 class="fw-bold m-0 text-warning"><i class="bi bi-pencil-square me-2"></i>Edit Tarif Kargo</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="small fw-bold mb-1 text-muted">Tarif Dasar (1 Kg Pertama)</label>
                    <input type="number" class="form-control custom-input fw-bold" name="harga_dasar" id="inputEditKargoDasar" required>
                </div>
                <div class="mb-4">
                    <label class="small fw-bold mb-1 text-muted">Tarif Selanjutnya (Per Kg)</label>
                    <input type="number" class="form-control custom-input fw-bold" name="harga_selanjutnya" id="inputEditKargoSelanjutnya" required>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-cancel-custom w-50 fw-bold rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary w-50 fw-bold rounded-3">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEditArmada" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <form id="formEditArmada" method="POST" enctype="multipart/form-data" class="modal-content shadow-lg" style="border-radius: 16px;">
            @csrf @method('PUT')
            <div class="modal-header border-0 pb-0">
                <h6 class="fw-bold m-0 text-success"><i class="bi bi-car-front me-2"></i>Edit Armada</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="small fw-bold mb-1 text-muted">Nama Mobil</label>
                    <input type="text" class="form-control custom-input" name="nama_armada" id="inputEditArmadaName" required>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold mb-1 text-muted">Plat Nomor</label>
                    <input type="text" class="form-control custom-input text-uppercase fw-bold" name="plat_nomor" id="inputEditArmadaPlat" required>
                </div>
                <div class="mb-4">
                    <label class="small fw-bold mb-1 text-muted">Ganti Foto Armada (Opsional)</label>
                    <input type="file" class="form-control custom-input" name="image" accept="image/jpeg, image/png, image/jpg, image/webp" onchange="validateImageUpload(this)">
                    <small class="text-muted" style="font-size: 0.7rem;">*Kosongkan jika tidak ingin mengganti foto.</small>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-cancel-custom w-50 fw-bold rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary w-50 fw-bold rounded-3">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalAddRute" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <form action="{{ route('admin.rute.store') }}" method="POST" class="modal-content shadow-lg" style="border-radius: 16px;">
            @csrf
            <div class="modal-header border-0 pb-0">
                <h6 class="fw-bold m-0 text-primary"><i class="bi bi-plus-circle me-2"></i>Tambah Rute Travel</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="small fw-bold mb-1 text-muted">Kota Asal</label>
                    <input type="text" class="form-control custom-input" name="kota_asal" placeholder="Contoh: Malang" required>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold mb-1 text-muted">Kota Tujuan</label>
                    <input type="text" class="form-control custom-input" name="kota_tujuan" placeholder="Contoh: Pacitan" required>
                </div>
                <div class="mb-4">
                    <label class="small fw-bold mb-1 text-muted">Harga per Seat (Rp)</label>
                    <input type="number" class="form-control custom-input fw-bold" name="harga" placeholder="150000" required>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-cancel-custom w-50 fw-bold rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary w-50 fw-bold rounded-3">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalAddArmada" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <form action="{{ route('admin.armada.store') }}" method="POST" enctype="multipart/form-data" class="modal-content shadow-lg" style="border-radius: 16px;">
            @csrf
            <div class="modal-header border-0 pb-0">
                <h6 class="fw-bold m-0 text-primary"><i class="bi bi-plus-circle me-2"></i>Tambah Armada Baru</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="small fw-bold mb-1 text-muted">Nama / Merk Mobil</label>
                    <input type="text" class="form-control custom-input" name="nama_armada" placeholder="Contoh: Toyota Hiace" required>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold mb-1 text-muted">Plat Nomor</label>
                    <input type="text" class="form-control custom-input text-uppercase fw-bold" name="plat_nomor" placeholder="Contoh: AE 1234 BB" required>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold mb-1 text-muted">Foto Armada</label>
                    <input type="file" class="form-control custom-input" name="image" accept="image/jpeg, image/png, image/jpg, image/webp" onchange="validateImageUpload(this)" required>
                </div>
                <div class="mb-4">
                    <label class="small fw-bold mb-1 text-muted">Kapasitas Kursi</label>
                    <input type="number" class="form-control custom-input fw-bold" name="kapasitas_kursi" value="7" min="1" max="7" required>
                    <small class="text-warning" style="font-size: 0.7rem;"><i class="bi bi-info-circle me-1"></i>Maksimal 7 kursi menyesuaikan layout denah mobil di sistem.</small>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-cancel-custom w-50 fw-bold rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary w-50 fw-bold rounded-3">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalAddSupir" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <form action="{{ route('admin.driver.store') }}" method="POST" class="modal-content shadow-lg" style="border-radius: 16px;">
            @csrf
            <div class="modal-header border-0 pb-0">
                <h6 class="fw-bold m-0 text-success"><i class="bi bi-person-plus me-2"></i>Tambah Supir Baru</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="small fw-bold mb-1 text-muted">Nama Lengkap</label>
                    <input type="text" class="form-control custom-input" name="nama_supir" placeholder="Nama Supir" required>
                </div>
                <div class="mb-4">
                    <label class="small fw-bold mb-1 text-muted">Nomor WhatsApp</label>
                    <input type="text" class="form-control custom-input fw-bold" name="no_hp" placeholder="Contoh: 08123456789" required>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-cancel-custom w-50 fw-bold rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success w-50 fw-bold rounded-3 border-0">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEditSupir" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <form id="formEditSupir" method="POST" class="modal-content shadow-lg" style="border-radius: 16px;">
            @csrf @method('PUT')
            <div class="modal-header border-0 pb-0">
                <h6 class="fw-bold m-0 text-success"><i class="bi bi-pencil-square me-2"></i>Edit Data Supir</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="small fw-bold mb-1 text-muted">Nama Lengkap</label>
                    <input type="text" class="form-control custom-input" name="nama_supir" id="inputEditSupirName" required>
                </div>
                <div class="mb-4">
                    <label class="small fw-bold mb-1 text-muted">Nomor WhatsApp</label>
                    <input type="text" class="form-control custom-input fw-bold" name="no_hp" id="inputEditSupirWa" required>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-cancel-custom w-50 fw-bold rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success w-50 fw-bold rounded-3 border-0">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // --- VARIABEL GLOBAL KARGO (Ditarik dari DB) ---
    let basePrice = {{ $tarif->harga_dasar ?? 50000 }};
    let nextKgPrice = {{ $tarif->harga_selanjutnya ?? 25000 }};

    const inputWeight = document.getElementById('kargoWeight');
    const extraWeightDisplay = document.getElementById('extraWeight');
    const extraPriceDisplay = document.getElementById('extraPrice');
    const totalPriceDisplay = document.getElementById('totalPrice');

    // --- FUNGSI KALKULATOR SIMULASI ---
    function calculateKargo() {
        let weight = parseInt(inputWeight.value);
        if (isNaN(weight) || weight < 1) { weight = 1; }

        let extraWeight = weight > 1 ? weight - 1 : 0;
        let extraPrice = extraWeight * nextKgPrice;
        let totalPrice = basePrice + extraPrice;

        extraWeightDisplay.innerText = extraWeight;
        extraPriceDisplay.innerText = "Rp " + extraPrice.toLocaleString('id-ID');
        totalPriceDisplay.innerText = "Rp " + totalPrice.toLocaleString('id-ID');
    }
    
    // Panggil fungsi saat ada ketikan & saat halaman pertama dimuat
    inputWeight.addEventListener('input', calculateKargo);
    document.addEventListener('DOMContentLoaded', calculateKargo);

    // --- FUNGSI BUKA MODAL ---
    function openEditKargo() {
        // Ambil data lama dari variabel global dan masukkan ke dalam form
        document.getElementById('inputEditKargoDasar').value = basePrice;
        document.getElementById('inputEditKargoSelanjutnya').value = nextKgPrice;
        
        // Tampilkan modal
        new bootstrap.Modal(document.getElementById('modalEditKargo')).show();
    }

    // --- FUNGSI MODAL ---
    function openEditRute(id, asal, tujuan, harga) {
        document.getElementById('editRuteSubtitle').innerText = `${asal} - ${tujuan}`;
        document.getElementById('inputEditRutePrice').value = harga;
        document.getElementById('formEditRute').action = `/admin/rute/${id}`;
        new bootstrap.Modal(document.getElementById('modalEditRute')).show();
    }

    function openEditArmada(id, nama, plat) {
        document.getElementById('inputEditArmadaName').value = nama;
        document.getElementById('inputEditArmadaPlat').value = plat;
        document.getElementById('formEditArmada').action = `/admin/armada/${id}`;
        new bootstrap.Modal(document.getElementById('modalEditArmada')).show();
    }

    function openEditSupir(id, nama, wa) {
        document.getElementById('inputEditSupirName').value = nama;
        document.getElementById('inputEditSupirWa').value = wa;
        document.getElementById('formEditSupir').action = `/admin/driver/${id}`;
        new bootstrap.Modal(document.getElementById('modalEditSupir')).show();
    }

    function openAddModal() {
        const activeTabId = document.querySelector('.nav-pills-custom .nav-link.active').getAttribute('data-bs-target');
        if(activeTabId === '#tab-rute') new bootstrap.Modal(document.getElementById('modalAddRute')).show();
        else if(activeTabId === '#tab-armada') new bootstrap.Modal(document.getElementById('modalAddArmada')).show();
        else if(activeTabId === '#tab-supir') new bootstrap.Modal(document.getElementById('modalAddSupir')).show();
        else {
            const isDark = document.body.getAttribute('data-theme') === 'dark';
            Swal.fire({ icon: 'info', title: 'Tarif Kargo', text: "Klik ikon pensil di kolom harga kargo untuk mengubah.", confirmButtonColor: '#483d8b', background: isDark ? '#16191f' : '#fff', color: isDark ? '#fff' : '#000' });
        }
    }

    // --- SWEET ALERTS KONFIRMASI HAPUS ---
    function confirmDeleteRute(id, asal, tujuan) {
        const isDark = document.body.getAttribute('data-theme') === 'dark';
        Swal.fire({ title: 'Tarik Rute Ini?', text: `Rute ${asal} ➔ ${tujuan} (PP) akan dihapus.`, icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#6c757d', confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal', background: isDark ? '#16191f' : '#fff', color: isDark ? '#fff' : '#000'
        }).then((result) => { if (result.isConfirmed) document.getElementById('form-delete-rute-' + id).submit(); });
    }

    function confirmDeleteArmada(id, nama) {
        const isDark = document.body.getAttribute('data-theme') === 'dark';
        Swal.fire({ title: 'Hapus Armada?', text: `Mobil ${nama} akan ditarik dari sistem.`, icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#6c757d', confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal', background: isDark ? '#16191f' : '#fff', color: isDark ? '#fff' : '#000'
        }).then((result) => { if (result.isConfirmed) document.getElementById('form-delete-armada-' + id).submit(); });
    }

    function confirmDeleteSupir(id, nama) {
        const isDark = document.body.getAttribute('data-theme') === 'dark';
        Swal.fire({ title: 'Hapus Supir?', text: `Data supir atas nama ${nama} akan dihapus.`, icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#6c757d', confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal', background: isDark ? '#16191f' : '#fff', color: isDark ? '#fff' : '#000'
        }).then((result) => { if (result.isConfirmed) document.getElementById('form-delete-supir-' + id).submit(); });
    }

    // --- FUNGSI VALIDASI UPLOAD FOTO ARMADA (MAX 1MB) ---
    function validateImageUpload(input) {
        if (input.files && input.files.length > 0) {
            const file = input.files[0];
            const isDark = document.body.getAttribute('data-theme') === 'dark';
            const bgAlert = isDark ? '#16191f' : '#fff';
            const textAlert = isDark ? '#fff' : '#000';

            // 1. Validasi Tipe File
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                Swal.fire({ 
                    icon: 'error', 
                    title: 'Format Tidak Sesuai', 
                    text: 'Hanya diperbolehkan format JPG, JPEG, PNG, atau WEBP.',
                    background: bgAlert, color: textAlert
                });
                input.value = ''; // Reset input
                return;
            }

            // 2. Validasi Ukuran Maksimal 1 MB
            const fileSizeMB = file.size / 1024 / 1024;
            if (fileSizeMB > 1) {
                Swal.fire({ 
                    icon: 'error', 
                    title: 'Ukuran Terlalu Besar', 
                    text: 'Maksimal ukuran foto armada adalah 1 MB. Silakan kompres foto Anda.',
                    background: bgAlert, color: textAlert
                });
                input.value = ''; // Reset input
                return;
            }
        }
    }
</script>
@endpush

@endsection