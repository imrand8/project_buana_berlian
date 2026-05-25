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
    .custom-card { background: var(--card-bg); border-radius: var(--radius); padding: 22px; border: 1px solid var(--border-color); box-shadow: 0 10px 30px rgba(0,0,0,0.02); color: var(--text-main); }
    .armada-icon { width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; }
    .modal-content { background-color: var(--card-bg); color: var(--text-main); border: 1px solid var(--border-color); }
    .custom-input { background: var(--bg-body); color: var(--text-main) !important; border: 1px solid var(--border-color); transition: 0.3s; }
    .custom-input:focus { background: var(--bg-body); color: var(--text-main); border-color: var(--p-color); box-shadow: 0 0 0 3px rgba(72, 61, 139, 0.1); outline: none; }
    
/* FIX SWEETALERT SUCCESS BG DI DARK MODE */
    [data-theme="dark"] .swal2-success-circular-line-left, 
    [data-theme="dark"] .swal2-success-circular-line-right, 
    [data-theme="dark"] .swal2-success-fix { 
        background-color: var(--card-bg) !important; 
    }
    
    [data-theme="dark"] .swal2-success-ring {
        background-color: transparent !important;
    }
    
    /* Memastikan modal sweetalert benar-benar gelap */
    [data-theme="dark"] div:where(.swal2-container) div:where(.swal2-popup) {
        background-color: var(--card-bg) !important;
        border: 1px solid var(--border-color);
    }

    [data-theme="dark"] .custom-card { background: var(--card-bg); border-color: #2d333b; }
    [data-theme="dark"] .custom-input { background: #181a20; border-color: #444; }
    [data-theme="dark"] .custom-input:focus { border-color: var(--accent-gold); box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.15); background: #222; }
    [data-theme="dark"] .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }
    [data-theme="dark"] .table { color: #f8fafc; }
    [data-theme="dark"] .table th, [data-theme="dark"] .table td { border-color: #2d333b; color: #f8fafc; }
    [data-theme="dark"] .text-muted { color: #94a3b8 !important; }
    
    .badge-info-custom { background: rgba(0,0,0,0.05); color: var(--text-main); border: 1px solid var(--border-color); }
    [data-theme="dark"] .badge-info-custom { background: rgba(255,255,255,0.05); color: #f8fafc; border-color: #2d333b; }
    [data-theme="dark"] .input-group-text.bg-light { background-color: #1e293b !important; color: #cbd5e1 !important; border-color: #444 !important; }
    [data-theme="dark"] .swal2-popup { background-color: var(--card-bg) !important; color: var(--text-main) !important; border: 1px solid var(--border-color); }
    [data-theme="dark"] .swal2-title, [data-theme="dark"] .swal2-html-container { color: var(--text-main) !important; }
    
    .btn-cancel-custom { background: transparent; color: var(--text-main); border: 1px solid var(--text-muted); transition: 0.3s; }
    .btn-cancel-custom:hover { background: var(--border-color); color: var(--text-main); }
    .btn-edit-action { background: #ffffff; color: var(--p-color); border: 1px solid #e2e8f0; transition: 0.2s; }
    .btn-edit-action:hover { background: var(--bg-body); border-color: var(--p-color); }
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
        <div class="custom-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0"><i class="bi bi-journal-bookmark text-primary me-2"></i>Tarif Rute Travel (PP)</h5>
                <span class="badge badge-info-custom py-2 px-3"><i class="bi bi-info-circle me-1"></i>Berlaku untuk rute sebaliknya</span>
            </div>
            <div class="table-responsive">
                <table class="table align-middle border-0">
                    <thead class="text-uppercase small fw-bold text-muted border-0">
                        <tr>
                            <th style="width: 60px; border-bottom: none;">No</th>
                            <th style="border-bottom: none;">Kota Asal</th>
                            <th style="border-bottom: none;">Kota Tujuan</th>
                            <th style="border-bottom: none;">Harga per Seat</th>
                            <th class="text-center" style="width: 100px; border-bottom: none;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rutes as $index => $rute)
                        <tr>
                            <td class="fw-bold text-muted">{{ $index + 1 }}</td>
                            <td class="fw-bold">{{ $rute->kota_asal }}</td>
                            <td class="fw-bold">{{ $rute->kota_tujuan }}</td>
                            <td><span class="fw-bold fs-6">Rp {{ number_format($rute->harga_reguler, 0, ',', '.') }}</span></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <button class="btn btn-sm btn-edit-action rounded-2 px-2" onclick="openEditRute({{ $rute->id }}, '{{ $rute->kota_asal }}', '{{ $rute->kota_tujuan }}', {{ $rute->harga_reguler }})" title="Edit Harga"><i class="bi bi-pencil-square"></i></button>
                                    
                                    <form action="{{ route('admin.rute.destroy', $rute->id) }}" method="POST" id="form-delete-rute-{{ $rute->id }}">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-2 px-2 border-0" onclick="confirmDeleteRute({{ $rute->id }}, '{{ $rute->kota_asal }}', '{{ $rute->kota_tujuan }}')" title="Hapus Rute"><i class="bi bi-trash-fill"></i></button>
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

    <div class="tab-pane fade" id="tab-kargo">
        <div class="row g-4">
            <div class="col-md-7">
                <div class="custom-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold m-0"><i class="bi bi-box-seam text-warning me-2"></i>Pengaturan Tarif Kargo</h5>
                        <span class="badge bg-success text-white border border-success"><i class="bi bi-check-circle me-1"></i>Tersambung Database</span>
                    </div>
                    
                    <div class="mb-4">
                        <label class="small text-muted fw-bold mb-2">Harga 1 Kg Pertama (Tarif Dasar)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted fw-bold border-end-0">Rp</span>
                            <input type="text" id="dispBasePrice" class="form-control custom-input fw-bold fs-5" value="{{ number_format($tarif->harga_dasar ?? 50000, 0, ',', '.') }}" readonly>
                            <button type="button" class="btn btn-outline-secondary" onclick="openEditKargo()"><i class="bi bi-pencil"></i></button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="small text-muted fw-bold mb-2">Harga Kg Selanjutnya (Per Kg)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted fw-bold border-end-0">Rp</span>
                            <input type="text" id="dispNextPrice" class="form-control custom-input fw-bold fs-5" value="{{ number_format($tarif->harga_selanjutnya ?? 25000, 0, ',', '.') }}" readonly>
                            <button type="button" class="btn btn-outline-secondary" onclick="openEditKargo()"><i class="bi bi-pencil"></i></button>
                        </div>
                    </div>
                    
                    <div class="alert border-0 bg-primary bg-opacity-10 d-flex align-items-center mt-4">
                        <i class="bi bi-info-circle-fill text-primary fs-4 me-3"></i>
                        <div class="small text-main">
                            <strong>Rumus Kargo:</strong> Tarif Dasar (1 Kg) + (Sisa Berat x Harga Kg Selanjutnya). 
                            <br><small class="text-muted">*Harga ini digunakan oleh sistem kalkulasi di halaman pelanggan.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="custom-card h-100" style="background: var(--bg-body); border: 1px dashed var(--p-color);">
                    <h6 class="fw-bold mb-3 text-center text-primary"><i class="bi bi-calculator me-2"></i>Simulasi Hitung Kargo</h6>
                    
                    <div class="mb-3">
                        <label class="small fw-bold mb-2 text-muted">Masukkan Berat Barang (Kg):</label>
                        <div class="input-group input-group-lg">
                            <input type="number" id="kargoWeight" class="form-control custom-input text-center fw-bold" value="5" min="1">
                            <span class="input-group-text bg-light text-muted fw-bold border-start-0">Kg</span>
                        </div>
                    </div>

                    <div class="p-3 rounded-3 mt-4" style="background: var(--card-bg); border: 1px solid var(--border-color);">
                        <div class="d-flex justify-content-between small text-muted mb-2">
                            <span>1 Kg Pertama:</span>
                            <span class="fw-bold text-main" id="simBasePrice">Rp {{ number_format($tarif->harga_dasar ?? 50000, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between small text-muted mb-3 border-bottom pb-3">
                            <span>Tambahan (<span id="extraWeight">4</span> Kg x <span id="simNextPriceText">{{ ($tarif->harga_selanjutnya ?? 25000) / 1000 }}rb</span>):</span>
                            <span class="fw-bold text-main" id="extraPrice">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-main">Total Harga:</span>
                            <span class="fs-4 fw-bold text-primary" id="totalPrice">Rp 0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="tab-armada">
        <div class="row g-4">
            @foreach($armadas as $armada)
            <div class="col-md-4">
                <div class="custom-card text-center position-relative pb-3">
                    <span class="position-absolute top-0 end-0 m-3 badge bg-success">Aktif</span>
                    
                    @php
                        $colors = ['primary', 'success', 'info', 'warning', 'danger'];
                        $color = $colors[$loop->index % count($colors)];
                    @endphp
                    
                    <div class="armada-icon bg-{{ $color }} bg-opacity-10 text-{{ $color }} mx-auto mb-3"><i class="bi bi-car-front-fill"></i></div>
                    <h5 class="fw-bold mb-1">{{ $armada->nama_armada }}</h5>
                    <p class="text-muted small mb-3">Kapasitas: {{ $armada->kapasitas_kursi }} Seat</p>
                    <div class="p-2 rounded-3 mb-4" style="background: var(--bg-body); border: 1px solid var(--border-color);">
                        <h4 class="fw-bold text-dark m-0 bg-warning rounded-2 py-1 border border-dark mx-4">{{ $armada->plat_nomor }}</h4>
                    </div>
                    
                    <div class="d-flex gap-2 justify-content-center">
                        <button class="btn btn-sm btn-outline-primary fw-bold w-50" onclick="openEditArmada({{ $armada->id }}, '{{ $armada->nama_armada }}', '{{ $armada->plat_nomor }}')"><i class="bi bi-pencil-square me-1"></i> Edit</button>
                        <form action="{{ route('admin.armada.destroy', $armada->id) }}" method="POST" class="w-50" id="form-delete-armada-{{ $armada->id }}">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-sm btn-outline-danger fw-bold w-100" onclick="confirmDeleteArmada({{ $armada->id }}, '{{ $armada->nama_armada }}')"><i class="bi bi-trash-fill me-1"></i> Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="tab-pane fade" id="tab-supir">
        <div class="custom-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0"><i class="bi bi-person-badge text-success me-2"></i>Daftar Supir Aktif</h5>
            </div>
            <div class="table-responsive">
                <table class="table align-middle border-0">
                    <thead class="text-uppercase small fw-bold text-muted border-0">
                        <tr>
                            <th style="width: 60px; border-bottom: none;">No</th>
                            <th style="border-bottom: none;">Nama Supir</th>
                            <th style="border-bottom: none;">Nomor WhatsApp</th>
                            <th class="text-center" style="width: 100px; border-bottom: none;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($drivers as $index => $driver)
                        <tr>
                            <td class="fw-bold text-muted">{{ $index + 1 }}</td>
                            <td class="fw-bold">{{ $driver->nama_supir }}</td>
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1"><i class="bi bi-whatsapp me-1"></i>{{ $driver->no_hp }}</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <button class="btn btn-sm btn-edit-action rounded-2 px-2" onclick="openEditSupir({{ $driver->id }}, '{{ $driver->nama_supir }}', '{{ $driver->no_hp }}')" title="Edit Supir"><i class="bi bi-pencil-square"></i></button>
                                    
                                    <form action="{{ route('admin.driver.destroy', $driver->id) }}" method="POST" id="form-delete-supir-{{ $driver->id }}">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-2 px-2 border-0" onclick="confirmDeleteSupir({{ $driver->id }}, '{{ $driver->nama_supir }}')" title="Hapus Supir"><i class="bi bi-trash-fill"></i></button>
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
        <form id="formEditArmada" method="POST" class="modal-content shadow-lg" style="border-radius: 16px;">
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
                <div class="mb-4">
                    <label class="small fw-bold mb-1 text-muted">Plat Nomor</label>
                    <input type="text" class="form-control custom-input text-uppercase fw-bold" name="plat_nomor" id="inputEditArmadaPlat" required>
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
        <form action="{{ route('admin.armada.store') }}" method="POST" class="modal-content shadow-lg" style="border-radius: 16px;">
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
                <div class="mb-4">
                    <label class="small fw-bold mb-1 text-muted">Kapasitas Kursi</label>
                    <input type="number" class="form-control custom-input fw-bold" name="kapasitas_kursi" value="7" min="1" required>
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
</script>
@endpush

@endsection