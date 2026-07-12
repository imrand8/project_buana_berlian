@extends('admin.partials.master')

@section('page_title', 'Jadwal Keberangkatan')
@section('page_subtitle', 'Buka jadwal harian, tugaskan armada, dan pantau ketersediaan kursi & kargo.')

@section('content')

<style>
    /* CSS TABS & CARDS */
    .nav-pills-custom { background: #e2e8f0; padding: 6px; border-radius: 16px; display: inline-flex; gap: 4px; border: 1px solid rgba(0,0,0,0.05); }
    [data-theme="dark"] .nav-pills-custom { background: #16191f; border-color: rgba(255,255,255,0.05); }
    .nav-pills-custom .nav-link { border-radius: 12px; padding: 10px 22px; font-weight: 700; color: var(--text-muted); border: none; font-size: 0.85rem; transition: 0.3s; }
    .nav-pills-custom .nav-link.active { background: var(--card-bg) !important; color: var(--p-color) !important; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    [data-theme="dark"] .nav-pills-custom .nav-link.active { background: var(--p-color) !important; color: white !important; }

    .schedule-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px; margin-top: 25px; }
    .schedule-card {
        background: var(--card-bg); border-radius: 16px; padding: 20px;
        border: 1px solid var(--border-color); transition: 0.3s; color: var(--text-main);
        animation: fadeIn 0.5s ease;
    }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .schedule-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-color: var(--p-color); }
    
    .route-badge { background: rgba(72, 61, 139, 0.1); color: var(--p-color); padding: 5px 12px; border-radius: 8px; font-weight: 700; font-size: 0.75rem; }
    [data-theme="dark"] .route-badge { background: rgba(138, 118, 255, 0.15); color: #b3a6ff; }

    .seat-map-mini { display: flex; gap: 6px; flex-wrap: wrap; margin: 15px 0; padding: 15px; background: var(--bg-body); border-radius: 12px; border: 1px solid var(--border-color); justify-content: center; }
    .seat-mini { 
        width: 35px; height: 35px; border-radius: 8px; display: flex; align-items: center; justify-content: center; 
        font-size: 0.8rem; font-weight: 800; border: 2px solid; transition: 0.2s;
    }
    
    .seat-interactive { cursor: pointer; }
    .seat-interactive:hover { transform: scale(1.1); box-shadow: 0 4px 10px rgba(0,0,0,0.15); }
    .seat-disabled { cursor: not-allowed; opacity: 0.6; }

    .seat-available { background: rgba(34, 197, 94, 0.1); color: #22c55e; border-color: #22c55e; }
    [data-theme="dark"] .seat-available { background: rgba(34, 197, 94, 0.15); }
    .seat-booked { background: var(--p-color); color: white; border-color: var(--p-color); } 
    .seat-locked { background: #ef4444; color: white; border-color: #ef4444; } 
    .seat-selected-admin { background: #f59e0b !important; color: white !important; border-color: #f59e0b !important; transform: scale(1.15); box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4); z-index: 10;}

    /* FIX DARK MODE MODAL & INPUTS */
    .modal-content { background-color: var(--card-bg); color: var(--text-main); border: 1px solid var(--border-color); }
    [data-theme="dark"] .modal-header, [data-theme="dark"] .modal-footer { border-color: var(--border-color); }
    [data-theme="dark"] .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }
    
    .custom-input, .custom-select { 
        background: var(--bg-body); color: var(--text-main) !important; 
        border: 1px solid var(--border-color); border-radius: 10px; 
        padding: 10px 15px; font-size: 0.9rem; transition: all 0.3s ease;
    }
    .custom-input:focus, .custom-select:focus { 
        border-color: var(--p-color); box-shadow: 0 0 0 4px rgba(72, 61, 139, 0.1); outline: none; background: var(--card-bg);
    }
    
    [data-theme="dark"] .custom-input, [data-theme="dark"] .custom-select { background: #181a20; border-color: #444; color: #ffffff !important; }
    [data-theme="dark"] .custom-input:focus, [data-theme="dark"] .custom-select:focus { border-color: var(--accent-gold); box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.15); background: #222; }
    [data-theme="dark"] .custom-select option { background: #1a1a1a; color: #ffffff; }
    [data-theme="dark"] .custom-input:disabled, [data-theme="dark"] .custom-select:disabled { background: #22252a; border-color: #333; color: #888 !important; opacity: 1; }
    [data-theme="dark"] .custom-input::placeholder { color: #666; opacity: 1; }
    .custom-input::placeholder { color: #999; }
    
    ::-webkit-calendar-picker-indicator { cursor: pointer; opacity: 0.6; transition: 0.2s; }
    ::-webkit-calendar-picker-indicator:hover { opacity: 1; }
    [data-theme="dark"] ::-webkit-calendar-picker-indicator { filter: invert(1); opacity: 0.8; }

    [data-theme="dark"] .modal-content { 
    background-color: #1c2128 !important; 
    border-color: rgba(255,255,255,0.1); 
    }
    [data-theme="dark"] .seat-mini.seat-available { 
        background: rgba(255,255,255,0.05); 
        border-color: #444; 
    }

    /* --- FIX SWEETALERT DARK MODE --- */
    [data-theme="dark"] div:where(.swal2-container) div:where(.swal2-popup) {
        background-color: var(--card-bg) !important;
        color: var(--text-main) !important;
        border: 1px solid var(--border-color);
    }
    [data-theme="dark"] div:where(.swal2-container) .swal2-title, 
    [data-theme="dark"] div:where(.swal2-container) .swal2-html-container {
        color: var(--text-main) !important;
    }

    #passengerDetailBox { background: var(--bg-body); border: 1px solid var(--border-color); border-radius: 16px; padding: 20px; }
    [data-theme="dark"] #passengerDetailBox { background: #181a20; border-color: #2d333b; }
    
    .input-readonly { background-color: rgba(0,0,0,0.04) !important; cursor: not-allowed; color: #6c757d !important; border-style: dashed; }
    [data-theme="dark"] .input-readonly { background-color: rgba(255,255,255,0.03) !important; color: #888 !important; border-color: #444 !important; }

    .form-label-icon { font-size: 0.8rem; font-weight: 700; color: var(--text-muted); margin-bottom: 8px; display: flex; align-items: center; gap: 6px; }
    .form-label-icon i { color: var(--p-color); font-size: 1rem; }
    [data-theme="dark"] .form-label-icon i { color: var(--accent-gold); }

    .kargo-item {
        background: var(--card-bg); border: 1px solid var(--border-color); padding: 10px; border-radius: 10px;
        display: flex; justify-content: space-between; align-items: center; cursor: pointer; transition: 0.2s;
    }
    .kargo-item:hover { border-color: var(--p-color); }
    .kargo-item.active { border-color: #f59e0b; background: rgba(245, 158, 11, 0.1); }
</style>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3 p-3 rounded-4" style="background: var(--bg-body); border: 1px solid var(--border-color);">
    <ul class="nav nav-pills-custom m-0" id="pills-tab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="pills-aktif-tab" data-bs-toggle="pill" data-bs-target="#pills-aktif" type="button">Jadwal Aktif</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="pills-riwayat-tab" data-bs-toggle="pill" data-bs-target="#pills-riwayat" type="button">Riwayat Selesai</button>
        </li>
    </ul>
    
    <div class="d-flex align-items-center gap-2">
        <input type="date" class="form-control border-0 shadow-sm" style="background: var(--card-bg); color: var(--text-main); border-radius: 12px; height: 48px; width: 160px; padding: 0 15px;" 
               value="{{ $filterTanggal }}" onchange="window.location.href='?tanggal=' + this.value">
               
        <button class="btn btn-primary fw-bold rounded-3 px-4 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
            <i class="bi bi-plus-lg me-2"></i>Buka Jadwal
        </button>
    </div>
</div>

<div class="tab-content mt-4" id="pills-tabContent">
    
    <div class="tab-pane fade show active" id="pills-aktif" role="tabpanel">
        <div class="schedule-grid">
            @forelse($jadwalAktif as $jdwl)
                @php
                    $date = \Carbon\Carbon::parse($jdwl->tanggal_berangkat);
                    $formattedDate = $date->translatedFormat('l, d M Y'); 
                    $bookedSeats = [];
                    $pesananJadwalIni = collect($travels)->where('jadwal_id', $jdwl->id);
                    foreach($pesananJadwalIni as $pt) {
                        $kursis = explode(',', $pt->nomor_kursi);
                        foreach($kursis as $k) { $bookedSeats[] = trim($k); }
                    }
                    $kapasitas = $jdwl->armada->kapasitas_kursi ?? 7;
                    $kursiTersedia = $kapasitas - count($bookedSeats);
                @endphp

                <div class="schedule-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="route-badge mb-2 d-inline-block">{{ strtoupper($jdwl->rute->kota_asal) }} <i class="bi bi-arrow-right mx-1"></i> {{ strtoupper($jdwl->rute->kota_tujuan) }}</span>
                            <h5 class="fw-bold m-0 mt-1">{{ $formattedDate }}</h5>
                            <div class="text-danger small fw-bold mt-1"><i class="bi bi-clock me-1"></i>{{ $jdwl->jam_berangkat }}</div>
                        </div>
                        <span class="badge bg-success bg-opacity-10 text-success border border-success">{{ ucfirst($jdwl->status_jadwal) }}</span>
                    </div>

                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3 me-3"><i class="bi bi-car-front-fill fs-5"></i></div>
                        <div>
                            <div class="fw-bold text-main small">{{ $jdwl->armada->nama_armada }} ({{ $jdwl->armada->plat_nomor }})</div>
                            <div class="text-muted small">Supir: <span>{{ $jdwl->driver ? $jdwl->driver->nama_supir : 'Belum Ditugaskan' }}</span></div>
                        </div>
                    </div>

                    <div class="seat-map-mini">
                        <div class="seat-mini {{ in_array('1', $bookedSeats) ? 'seat-booked' : 'seat-available' }}">1</div>
                        <div class="seat-mini bg-secondary text-white border-secondary" style="opacity: 0.5; cursor: default;">S</div>
                        <div class="w-100"></div> 
                        <div class="seat-mini {{ in_array('2', $bookedSeats) ? 'seat-booked' : 'seat-available' }}">2</div>
                        <div class="seat-mini {{ in_array('3', $bookedSeats) ? 'seat-booked' : 'seat-available' }}">3</div>
                        <div class="seat-mini {{ in_array('4', $bookedSeats) ? 'seat-booked' : 'seat-available' }}">4</div>
                        <div class="w-100"></div>
                        <div class="seat-mini {{ in_array('5', $bookedSeats) ? 'seat-booked' : 'seat-available' }}">5</div>
                        <div class="seat-mini {{ in_array('6', $bookedSeats) ? 'seat-booked' : 'seat-available' }}">6</div>
                        <div class="seat-mini {{ in_array('7', $bookedSeats) ? 'seat-booked' : 'seat-available' }}">7</div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                        <span class="small fw-bold text-muted">Kursi Tersedia: <span class="text-primary">{{ $kursiTersedia }}/{{ $kapasitas }}</span></span>
                        <div class="d-flex gap-1">
                            <button type="button" class="btn btn-sm btn-outline-danger fw-bold border-0" title="Hapus Jadwal" onclick="confirmDeleteJadwal({{ $jdwl->id }})"><i class="bi bi-trash-fill"></i></button>
                            <form id="form-delete-jadwal-{{ $jdwl->id }}" action="{{ route('admin.jadwal.destroy', $jdwl->id) }}" method="POST" class="d-none">
                                @csrf @method('DELETE')
                            </form>
                            <button class="btn btn-sm btn-outline-secondary fw-bold px-3" onclick="openViewOnlyManifest({{ $jdwl->id }}, {{ $jdwl->armada_id }}, '{{ $jdwl->jam_berangkat }}', '{{ $jdwl->driver_id ?? '' }}', {{ $jdwl->rute->harga_reguler }})">
                                Kelola
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <h5 class="text-muted fw-bold">Belum Ada Jadwal Aktif</h5>
                    <p class="text-muted small">Jadwal keberangkatan hari ini dan ke depan akan muncul di sini.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="tab-pane fade" id="pills-riwayat" role="tabpanel">
        <div class="schedule-grid">
            @forelse($jadwalSelesai as $jdwl)
                @php
                    $date = \Carbon\Carbon::parse($jdwl->tanggal_berangkat);
                    $formattedDate = $date->translatedFormat('l, d M Y'); 
                    $bookedSeats = [];
                    $pesananJadwalIni = collect($travels)->where('jadwal_id', $jdwl->id);
                    foreach($pesananJadwalIni as $pt) {
                        $kursis = explode(',', $pt->nomor_kursi);
                        foreach($kursis as $k) { $bookedSeats[] = trim($k); }
                    }
                    $kapasitas = $jdwl->armada->kapasitas_kursi ?? 7;
                    $kursiTersedia = $kapasitas - count($bookedSeats);
                @endphp

                <div class="schedule-card" style="opacity: 0.9;">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="route-badge mb-2 d-inline-block bg-secondary text-dark bg-opacity-10">{{ strtoupper($jdwl->rute->kota_asal) }} <i class="bi bi-arrow-right mx-1"></i> {{ strtoupper($jdwl->rute->kota_tujuan) }}</span>
                            <h5 class="fw-bold m-0 mt-1">{{ $formattedDate }}</h5>
                            <div class="text-muted small fw-bold mt-1"><i class="bi bi-clock me-1"></i>{{ $jdwl->jam_berangkat }}</div>
                        </div>
                        <span class="badge bg-secondary text-white">Selesai</span>
                    </div>

                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-secondary bg-opacity-10 text-secondary p-2 rounded-3 me-3"><i class="bi bi-car-front-fill fs-5"></i></div>
                        <div>
                            <div class="fw-bold text-main small">{{ $jdwl->armada->nama_armada }} ({{ $jdwl->armada->plat_nomor }})</div>
                            <div class="text-muted small">Supir: <span>{{ $jdwl->driver ? $jdwl->driver->nama_supir : '-' }}</span></div>
                        </div>
                    </div>

                    <div class="seat-map-mini" style="filter: grayscale(80%);">
                        <div class="seat-mini {{ in_array('1', $bookedSeats) ? 'seat-booked' : 'seat-available' }}">1</div>
                        <div class="seat-mini bg-secondary text-white border-secondary" style="opacity: 0.5; cursor: default;">S</div>
                        <div class="w-100"></div> 
                        <div class="seat-mini {{ in_array('2', $bookedSeats) ? 'seat-booked' : 'seat-available' }}">2</div>
                        <div class="seat-mini {{ in_array('3', $bookedSeats) ? 'seat-booked' : 'seat-available' }}">3</div>
                        <div class="seat-mini {{ in_array('4', $bookedSeats) ? 'seat-booked' : 'seat-available' }}">4</div>
                        <div class="w-100"></div>
                        <div class="seat-mini {{ in_array('5', $bookedSeats) ? 'seat-booked' : 'seat-available' }}">5</div>
                        <div class="seat-mini {{ in_array('6', $bookedSeats) ? 'seat-booked' : 'seat-available' }}">6</div>
                        <div class="seat-mini {{ in_array('7', $bookedSeats) ? 'seat-booked' : 'seat-available' }}">7</div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                        <span class="small fw-bold text-muted">Kursi Terisi: <span class="text-dark">{{ count($bookedSeats) }}/{{ $kapasitas }}</span></span>
                        
                        <button class="btn btn-sm btn-outline-secondary fw-bold px-3" onclick="openViewOnlyManifest({{ $jdwl->id }}, {{ $jdwl->armada_id }}, '{{ $jdwl->jam_berangkat }}', '{{ $jdwl->driver_id ?? '' }}')">
                            <i class="bi bi-eye me-1"></i> Lihat Manifest
                        </button>

                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <h5 class="text-muted fw-bold">Belum Ada Riwayat</h5>
                    <p class="text-muted small">Data jadwal yang sudah berlalu akan otomatis masuk ke sini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<div class="modal fade" id="addScheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.jadwal.store') }}" method="POST" class="modal-content shadow-lg" style="border-radius: 20px;">
            @csrf
            <div class="modal-header border-0 pb-2 px-4 pt-4">
                <h5 class="fw-bold m-0"><i class="bi bi-calendar-plus me-2 text-primary"></i>Buka Jadwal Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label-icon"><i class="bi bi-signpost-split"></i> Trayek Utama</label>
                    <select class="form-select custom-select w-100 shadow-sm" name="rute_id" required>
                        <option value="" selected disabled>-- Pilih Trayek Utama --</option>
                        @foreach($rutes as $rute)
                            @if(($rute->kota_asal == 'Pacitan' && $rute->kota_tujuan == 'Malang') || ($rute->kota_asal == 'Malang' && $rute->kota_tujuan == 'Pacitan'))
                                <option value="{{ $rute->id }}">{{ $rute->kota_asal }} ➔ {{ $rute->kota_tujuan }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label-icon"><i class="bi bi-calendar-event"></i> Tanggal</label>
                        <input type="date" class="form-control custom-input w-100 shadow-sm" name="tanggal_berangkat" id="addTanggal" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label-icon"><i class="bi bi-clock-history"></i> Jam</label>
                        <select class="form-select custom-select w-100 shadow-sm" name="jam_berangkat" id="addJam" required>
                            <option value="08:30:00">08:30 (Pagi)</option>
                            <option value="20:00:00">20:00 (Malam)</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-6">
                        <label class="form-label-icon"><i class="bi bi-car-front"></i> Armada</label>
                        <select class="form-select custom-select w-100 shadow-sm" name="armada_id" id="addArmada" required>
                            <option value="" selected disabled>-- Pilih Armada --</option>
                            @foreach($armadas as $armada)
                                <option value="{{ $armada->id }}">{{ $armada->nama_armada }} ({{ $armada->plat_nomor }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label-icon"><i class="bi bi-person-badge"></i> Supir Tugas</label>
                        <select class="form-select custom-select w-100 shadow-sm" name="driver_id" id="addSupir">
                            <option value="">-- Kosongkan (Bisa Nanti) --</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->nama_supir }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold py-3 rounded-3 shadow">Buka Jadwal ke Website</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="manageSeatModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl"> 
        <div class="modal-content shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div>
                    <h5 class="fw-bold m-0 text-main">Kelola Data Keberangkatan</h5>
                    <p class="small text-muted m-0 mt-1">Jadwal Operasional Admin</p>
                </div>
                <button type="button" class="btn border-0 p-0 align-self-start" data-bs-dismiss="modal" style="color: var(--text-muted);"><i class="bi bi-x-lg fs-4"></i></button>
            </div>
            
            <div class="modal-body px-4 py-3">
                
                <form id="formUpdateJadwal" method="POST" onsubmit="prepareDataBeforeSubmit()">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="manual_passengers" id="manualPassengersInput">
                    <input type="hidden" name="manual_kargos" id="manualKargosInput">

                    <div class="row mb-3 p-3 rounded-3 align-items-end" style="background: var(--bg-body); border: 1px solid var(--border-color);">
                        <div class="col-sm-3 border-end border-sm-0 mb-2 mb-sm-0">
                            <label class="form-label-icon"><i class="bi bi-car-front"></i> Ubah Armada:</label>
                            <select class="form-select custom-select shadow-sm py-1" name="armada_id" id="editArmadaSelect" required>
                                <option value="" disabled>-- Pilih Armada --</option> 
                                
                                @foreach($armadas as $armada)
                                    <option value="{{ $armada->id }}">{{ $armada->nama_armada }} ({{ $armada->plat_nomor }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3 border-end border-sm-0 mb-2 mb-sm-0">
                            <label class="form-label-icon"><i class="bi bi-clock"></i> Ubah Jam Berangkat:</label>
                            <select class="form-select custom-select shadow-sm py-1" name="jam_berangkat" id="editJamSelect" required>
                                <option value="08:30:00">08:30 (Pagi)</option>
                                <option value="20:00:00">20:00 (Malam)</option>
                            </select>
                        </div>
                        <div class="col-sm-4 mb-2 mb-sm-0">
                            <label class="form-label-icon"><i class="bi bi-person-badge"></i> Ubah Supir Bertugas:</label>
                            <select class="form-select custom-select shadow-sm py-1" name="driver_id" id="editSupirSelect">
                                <option value="">-- Belum Ditugaskan --</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}">{{ $driver->nama_supir }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2 text-end">
                            <button type="submit" class="btn btn-success fw-bold w-100 py-2"><i class="bi bi-save me-1"></i> Simpan Semua</button>
                        </div>
                    </div>
                </form>
                
                <div class="row text-start align-items-start mt-4">
                    <div class="col-lg-5 col-md-6 text-center border-end border-md-0 pe-lg-3">
                        <p class="small text-muted mb-2 fw-bold">Peta Kursi & Kargo</p>
                        
                        <div class="d-flex justify-content-center gap-2 mb-3 p-3 rounded-4" style="background: var(--card-bg); border: 1px solid var(--border-color); max-width: 250px; margin: 0 auto; flex-wrap: wrap;" id="modalSeatMap">
                            <div class="w-100 text-start text-muted small fw-bold mb-1" style="font-size: 0.7rem;">Baris Depan</div>
                            <div class="seat-mini seat-available seat-interactive" data-type="seat" data-id="1">1</div> 
                            <div class="seat-mini bg-secondary text-white border-secondary seat-disabled" title="Supir" style="opacity: 0.5; font-size: 1.1rem;"><i class="bi bi-person-fill"></i></div>
                            
                            <div class="w-100 text-start text-muted small fw-bold mt-1 mb-1" style="font-size: 0.7rem;">Baris Tengah</div>
                            <div class="seat-mini seat-available seat-interactive" data-type="seat" data-id="2">2</div>
                            <div class="seat-mini seat-available seat-interactive" data-type="seat" data-id="3">3</div>
                            <div class="seat-mini seat-available seat-interactive" data-type="seat" data-id="4">4</div>

                            <div class="w-100 text-start text-muted small fw-bold mt-1 mb-1" style="font-size: 0.7rem;">Baris Belakang</div>
                            <div class="seat-mini seat-available seat-interactive" data-type="seat" data-id="5">5</div>
                            <div class="seat-mini seat-available seat-interactive" data-type="seat" data-id="6">6</div>
                            <div class="seat-mini seat-available seat-interactive" data-type="seat" data-id="7">7</div>
                        </div>

                        <div class="d-flex justify-content-center gap-3 small text-muted flex-wrap mb-4">
                            <div class="d-flex align-items-center"><div class="seat-mini seat-available me-1" style="width:14px; height:14px; border-radius:3px;"></div> Kosong</div>
                            <div class="d-flex align-items-center"><div class="seat-mini seat-booked me-1" style="width:14px; height:14px; border-radius:3px;"></div> Via Web</div>
                            <div class="d-flex align-items-center"><div class="seat-mini seat-locked me-1" style="width:14px; height:14px; border-radius:3px;"></div> Manual</div>
                        </div>

                        <div class="text-start mt-4 pt-3 border-top">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="small fw-bold text-muted"><i class="bi bi-box-seam me-1"></i> Titipan Kargo WA</span>
                                <button class="btn btn-sm btn-outline-warning text-dark fw-bold py-1 px-2" style="font-size: 0.75rem;" onclick="siapkanKargoBaru()">+ Tambah Kargo</button>
                            </div>
                            <div id="kargoListContainer" class="d-flex flex-column gap-2"></div>
                        </div>
                    </div>

                    <div class="col-lg-7 col-md-6 ps-lg-3 mt-4 mt-md-0">
                        <div id="passengerDetailBox" class="shadow-sm">
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                <h6 class="fw-bold m-0 text-main" style="font-size: 1rem;"><i class="bi bi-pencil-square me-2 text-primary" id="formIcon"></i><span id="formTitle">Informasi Kursi / Kargo</span> <span id="selectedSeatNumLabel" class="text-primary fs-5 ms-1">?</span></h6>
                                <span class="badge bg-secondary px-3 py-2 rounded-pill" id="seatStatusLabel">Pilih kursi / kargo</span>
                            </div>

                            <div class="row">
                                <div class="col-sm-6 mb-2">
                                    <label class="small fw-bold text-muted mb-1" id="labelName">Nama Penumpang</label>
                                    <input type="text" class="form-control custom-input w-100" id="psgName" placeholder="Ketik nama..." disabled>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <label class="small fw-bold text-muted mb-1">No. HP / WA</label>
                                    <input type="text" class="form-control custom-input w-100" id="psgHp" placeholder="Contoh: 08..." disabled>
                                </div>
                            </div>

                            <div class="row" id="rowPenerima" style="display: none;">
                                <div class="col-sm-6 mb-2">
                                    <label class="small fw-bold text-muted mb-1">Nama Penerima</label>
                                    <input type="text" class="form-control custom-input w-100" id="psgPenerima" placeholder="Nama penerima..." disabled>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <label class="small fw-bold text-muted mb-1">No. HP Penerima</label>
                                    <input type="text" class="form-control custom-input w-100" id="psgHpPenerima" placeholder="WA penerima..." disabled>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-6 mb-2">
                                    <label class="small fw-bold text-muted mb-1" id="labelPick">Alamat Jemput</label>
                                    <input type="text" class="form-control custom-input w-100" id="psgPick" placeholder="Detail alamat..." disabled>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label class="small fw-bold text-muted mb-1" id="labelDrop">Alamat Tujuan</label>
                                    <input type="text" class="form-control custom-input w-100" id="psgDrop" placeholder="Detail tujuan..." disabled>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="small fw-bold text-muted mb-1" id="labelNotes">Catatan / Barang Bawaan</label>
                                <textarea class="form-control custom-input w-100" id="psgNotes" rows="2" placeholder="Cth: Bawa 1 koper besar..." disabled></textarea>
                            </div>

                            <div class="mb-3 p-3 rounded-3" id="rowPindahKursi" style="display: none; background: rgba(59, 130, 246, 0.05); border: 1px dashed #3b82f6;">
                                <label class="small fw-bold text-primary mb-1"><i class="bi bi-arrow-left-right me-1"></i> Pindah ke Kursi Lain?</label>
                                <select class="form-select custom-select w-100 border-primary" id="psgMoveSeat">
                                    <option value="">-- Tetap di Kursi Ini --</option>
                                </select>
                            </div>
                            <div class="row mb-4 p-3 rounded-3 mx-0" style="background: rgba(72, 61, 139, 0.05); border: 1px dashed var(--p-color);">
                                <div class="col-sm-6 mb-2 mb-sm-0">
                                    <label class="small fw-bold text-muted mb-1">Status Pembayaran</label>
                                    <select class="form-select custom-select w-100 fw-bold text-primary" id="psgPayStatus">
                                        <option value="Lunas (Manual WA)">Lunas (Titip Supir/WA)</option>
                                        <option value="Lunas (Transfer)">Lunas (Transfer)</option>
                                        <option value="Belum Bayar">Belum Bayar</option>
                                        <option value="Lunas (Via Web)" id="optWeb" style="display:none;">Lunas (Via Web)</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label class="small fw-bold text-muted mb-1">Harga (Rp)</label>
                                    <div class="input-group shadow-sm">
                                        <span class="input-group-text bg-light border-end-0 fw-bold text-muted" style="border-radius: 10px 0 0 10px;">Rp</span>
                                        <input type="number" class="form-control custom-input border-start-0 ps-0 fw-bold text-main" id="psgPrice" style="border-radius: 0 10px 10px 0;">
                                    </div>
                                </div>
                            </div>
                            <div id="webNoticeContainer" class="small text-danger fw-bold p-2 mb-3 rounded-2 mt-2 shadow-sm" style="display: none; background: rgba(220,53,69,0.05); border: 1px dashed #dc3545; font-size: 0.75rem;">
                                <i class="bi bi-info-circle-fill me-1"></i> Data Web dapat diedit di sini, tapi penghapusan/pembatalan hanya lewat menu Pesanan Masuk.
                            </div>

                            <div class="d-flex w-100 gap-2" id="actionButtonsContainer" style="display: none !important;">
                                <button class="btn btn-outline-danger fw-bold py-2" style="font-size: 0.85rem; width: 35%;" id="btnEmptySeat" onclick="kosongkanData()">Kosongkan</button>
                                <button class="btn btn-primary fw-bold py-2 shadow-sm flex-fill" style="font-size: 0.85rem;" id="btnSaveSeat" onclick="simpanDataLokal()">Kunci Posisi Manual</button>
                            </div>
                        </div>                
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById("addTanggal").setAttribute('min', today);
    });

    // 1. DATA DATABASE
    const dbTravels = @json($travels);
    const dbKargos = @json($kargos);

    let passengerData = {};
    let kargoData = {};
    let currentSelectedId = null;
    let currentSelectedType = null; 
    let currentJadwalHarga = 0; // Untuk menyimpan harga rute saat ini

    // --- 1. MUNCULKAN SWEETALERT JIKA ADA ERROR DARI CONTROLLER ---
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Jadwal Bentrok!',
            text: '{{ session("error") }}',
            confirmButtonColor: '#dc3545'
        });
    @endif
    
    @if(session('success'))
        Swal.fire({
            icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}',
            showConfirmButton: false, timer: 2500
        });
    @endif

    // --- 2. LOGIKA DROPDOWN PINTAR ---
    const allActiveSchedules = @json($jadwalAktif);
    let currentEditJadwalId = null;
    let currentEditTanggal = null;
    let isJadwalPast = false; // VARIABEL GLOBAL BARU UNTUK STATUS VIEW ONLY

    // A. Fungsi Kunci Dropdown Modal "Buka Jadwal Baru"
    function checkAvailability() {
        const selectedDate = document.getElementById('addTanggal').value;
        if(!selectedDate) return; 

        const selectJam = document.getElementById('addJam');
        Array.from(selectJam.options).forEach(opt => opt.disabled = false);

        // Kunci jam jika waktu saat ini + 30 menit sudah melewati jadwal
        const sekarang = new Date();
        const tglDipilih = new Date(selectedDate);
        
        if (tglDipilih.toDateString() === sekarang.toDateString()) {
            Array.from(selectJam.options).forEach(opt => {
                let [jamOpt, menitOpt] = opt.value.split(':');
                let waktuOpt = new Date();
                waktuOpt.setHours(parseInt(jamOpt), parseInt(menitOpt), 0);

                let waktuBatas = new Date(sekarang.getTime() + (30 * 60 * 1000)); 
                if (waktuBatas >= waktuOpt) {
                    opt.disabled = true;
                }
            });
        }

        if (selectJam.selectedOptions.length === 0 || selectJam.selectedOptions[0]?.disabled || !selectJam.value) {
            let firstAvailable = Array.from(selectJam.options).find(opt => !opt.disabled);
            selectJam.value = firstAvailable ? firstAvailable.value : ""; 
        }

        const selectedTime = selectJam.value;
        if(!selectedTime) return; 

        const bookedArmadas = allActiveSchedules.filter(j => j.tanggal_berangkat === selectedDate && j.jam_berangkat === selectedTime).map(j => j.armada_id.toString());
        const bookedDrivers = allActiveSchedules.filter(j => j.tanggal_berangkat === selectedDate && j.jam_berangkat === selectedTime && j.driver_id).map(j => j.driver_id.toString());

        const armadaSelect = document.getElementById('addArmada');
        if(armadaSelect) {
            Array.from(armadaSelect.options).forEach(opt => {
                if(opt.value === "") return;
                if(bookedArmadas.includes(opt.value)) {
                    opt.disabled = true; if(!opt.text.includes('(Sedang Beroperasi)')) opt.text += ' 🔴 (Sedang Beroperasi)';
                } else {
                    opt.disabled = false; opt.text = opt.text.replace(' 🔴 (Sedang Beroperasi)', '');
                }
            });
        }

        const supirSelect = document.getElementById('addSupir');
        if(supirSelect) {
            Array.from(supirSelect.options).forEach(opt => {
                if(opt.value === "") return;
                if(bookedDrivers.includes(opt.value)) {
                    opt.disabled = true; if(!opt.text.includes('(Sedang Bertugas)')) opt.text += ' 🔴 (Sedang Bertugas)';
                } else {
                    opt.disabled = false; opt.text = opt.text.replace(' 🔴 (Sedang Bertugas)', '');
                }
            });            
        }

        if (armadaSelect && armadaSelect.selectedOptions[0]?.disabled) armadaSelect.value = "";
        if (supirSelect && supirSelect.selectedOptions[0]?.disabled) supirSelect.value = "";
    }

    // B. Fungsi Kunci Dropdown Modal "Kelola Jadwal"
    function checkEditAvailability() {
        if(!currentEditJadwalId || !currentEditTanggal) return;
        
        const selectJam = document.getElementById('editJamSelect');
        Array.from(selectJam.options).forEach(opt => opt.disabled = false);

        const sekarang = new Date();
        const tglDipilih = new Date(currentEditTanggal);
        
        if (tglDipilih.toDateString() === sekarang.toDateString()) {
            Array.from(selectJam.options).forEach(opt => {
                let [jamOpt, menitOpt] = opt.value.split(':');
                let waktuOpt = new Date();
                waktuOpt.setHours(parseInt(jamOpt), parseInt(menitOpt), 0);

                let waktuBatas = new Date(sekarang.getTime() + (30 * 60 * 1000)); 
                if (waktuBatas >= waktuOpt) {
                    opt.disabled = true;
                }
            });
        }

        if (selectJam.selectedOptions.length === 0 || selectJam.selectedOptions[0]?.disabled || !selectJam.value) {
            let firstAvailable = Array.from(selectJam.options).find(opt => !opt.disabled);
            selectJam.value = firstAvailable ? firstAvailable.value : ""; 
        }

        const selectedTime = selectJam.value;
        if(!selectedTime) return;

        const bookedArmadas = allActiveSchedules.filter(j => j.tanggal_berangkat === currentEditTanggal && j.jam_berangkat === selectedTime && j.id != currentEditJadwalId).map(j => j.armada_id.toString());
        const bookedDrivers = allActiveSchedules.filter(j => j.tanggal_berangkat === currentEditTanggal && j.jam_berangkat === selectedTime && j.driver_id && j.id != currentEditJadwalId).map(j => j.driver_id.toString());

        const armadaSelect = document.getElementById('editArmadaSelect');
        Array.from(armadaSelect.options).forEach(opt => {
            if(opt.value === "") return;
            if(bookedArmadas.includes(opt.value)) {
                opt.disabled = true; if(!opt.text.includes('(Sedang Beroperasi)')) opt.text += ' 🔴 (Sedang Beroperasi)';
            } else {
                opt.disabled = false; opt.text = opt.text.replace(' 🔴 (Sedang Beroperasi)', '');
            }
        });

        const supirSelect = document.getElementById('editSupirSelect');
        Array.from(supirSelect.options).forEach(opt => {
            if(opt.value === "") return;
            if(bookedDrivers.includes(opt.value)) {
                opt.disabled = true; if(!opt.text.includes('(Sedang Bertugas)')) opt.text += ' 🔴 (Sedang Bertugas)';
            } else {
                opt.disabled = false; opt.text = opt.text.replace(' 🔴 (Sedang Bertugas)', '');
            }
        });

        if (armadaSelect && armadaSelect.selectedOptions[0]?.disabled) armadaSelect.value = "";
        if (supirSelect && supirSelect.selectedOptions[0]?.disabled) supirSelect.value = "";
    }

    setTimeout(() => {
        let addTgl = document.getElementById('addTanggal');
        let addJam = document.getElementById('addJam');
        let editJam = document.getElementById('editJamSelect');
        
        if(addTgl) addTgl.addEventListener('change', checkAvailability);
        if(addJam) addJam.addEventListener('change', checkAvailability);
        if(editJam) editJam.addEventListener('change', checkEditAvailability);
    }, 500);

    document.getElementById('addTanggal').addEventListener('change', checkAvailability);
    document.getElementById('addJam').addEventListener('change', checkAvailability);

    // SWEETALERT DELETE JADWAL
    function confirmDeleteJadwal(id) {
        const isDark = document.body.getAttribute('data-theme') === 'dark';
        Swal.fire({
            title: 'Hapus Jadwal?', text: "Jadwal ini akan ditarik dari peredaran!", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal',
            background: isDark ? 'var(--card-bg)' : '#ffffff', color: isDark ? 'var(--text-main)' : '#000000'
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('form-delete-jadwal-' + id).submit();
        });
    }

    function prepareDataSubmit(e) {
        document.getElementById('hiddenPassengers').value = JSON.stringify(passengerData);
        document.getElementById('hiddenKargos').value = JSON.stringify(kargoData);
    }

    // 3. LOAD DATA SAAT MODAL KELOLA DIBUKA
    function openKelolaModal(jadwalId, armadaId, jam, driverId, hargaRute) {
    currentJadwalHarga = hargaRute; // Simpan harga rute ke memori

        const currentJadwal = allActiveSchedules.find(j => j.id == jadwalId);
        currentEditTanggal = currentJadwal ? currentJadwal.tanggal_berangkat : null;
        
        // --- REVISI: Gunakan variabel global dan kurangi waktu 30 menit ---
        isJadwalPast = false; 
        if (currentEditTanggal) {
            let waktuBerangkat = new Date(`${currentEditTanggal}T${jam}`);
            let sekarang = new Date();
            let batasWaktuEdit = new Date(waktuBerangkat.getTime() - (30 * 60 * 1000));
            isJadwalPast = (sekarang >= batasWaktuEdit); 
        }

        let submitBtn = document.querySelector('#formUpdateJadwal button[type="submit"]');
        let btnKargoBaru = document.querySelector('button[onclick="siapkanKargoBaru()"]');
        
        if (isJadwalPast) {
            // MODE VIEW-ONLY JIKA WAKTU SUDAH LEWAT/KURANG DARI 30 MENIT
            if(submitBtn) submitBtn.style.display = 'none';
            if(btnKargoBaru) btnKargoBaru.style.display = 'none';
            document.getElementById('editArmadaSelect').disabled = true;
            document.getElementById('editJamSelect').disabled = true;
            document.getElementById('editSupirSelect').disabled = true;
            document.querySelectorAll('.seat-interactive').forEach(s => { s.style.pointerEvents = 'none'; });
        } else {
            // MODE EDIT NORMAL
            if(submitBtn) submitBtn.style.display = 'block';
            if(btnKargoBaru) btnKargoBaru.style.display = 'inline-block';
            document.getElementById('editArmadaSelect').disabled = false;
            document.getElementById('editJamSelect').disabled = false;
            document.getElementById('editSupirSelect').disabled = false;
            document.querySelectorAll('.seat-interactive').forEach(s => { s.style.pointerEvents = 'auto'; });
        }

        document.getElementById('formUpdateJadwal').action = `/admin/jadwal/${jadwalId}`;
        document.getElementById('editArmadaSelect').value = armadaId;
        document.getElementById('editJamSelect').value = jam;
        document.getElementById('editSupirSelect').value = driverId || ""; 
        currentEditJadwalId = jadwalId;

        checkEditAvailability(); 
        
        passengerData = {}; kargoData = {}; currentSelectedId = null; currentSelectedType = null;

        let currentTravels = dbTravels.filter(t => t.jadwal_id == jadwalId);
        currentTravels.forEach(t => {
            let kursis = t.nomor_kursi.split(','); 
            let hargaSatuan = t.total_harga / kursis.length; 
            let isManual = t.kode_booking && t.kode_booking.startsWith('MNL-');

            kursis.forEach(k => {
                k = k.trim();
                passengerData[k] = {
                    id: t.id, 
                    kode_booking: t.kode_booking,
                    name: t.nama_penumpang, hp: t.nomor_wa, pick: t.titik_jemput, drop: t.titik_antar,
                    notes: t.keterangan_barang || '', payStatus: isManual ? "Lunas (Manual WA)" : "Lunas (Via Web)",
                    price: hargaSatuan, status: isManual ? 'manual' : 'web'
                };
            });
        });

        let currentKargos = dbKargos.filter(k => k.jadwal_id == jadwalId);
        currentKargos.forEach(k => {
            let isManual = k.kode_resi && k.kode_resi.startsWith('MNL-');
            kargoData[k.kode_resi] = {
                id: k.id,
                kode_resi: k.kode_resi,
                name: k.nama_pengirim, hp: k.nomor_wa_pengirim, pick: k.kota_asal, drop: k.kota_tujuan,
                penerima: k.nama_penerima, hp_penerima: k.nomor_wa_penerima, 
                notes: k.keterangan_barang, payStatus: isManual ? "Lunas (Manual WA)" : "Lunas (Via Web)",
                price: k.total_harga, status: isManual ? 'manual' : 'web'
            };
        });

        refreshModalSeatMap(); renderKargoList(); selectItem('seat', '1'); 
        new bootstrap.Modal(document.getElementById('manageSeatModal')).show();
    }

    function refreshModalSeatMap() {
        for(let i = 1; i <= 7; i++) {
            let seatEl = document.querySelector(`.seat-interactive[data-id="${i}"]`);
            if(seatEl) {
                seatEl.classList.remove('seat-booked', 'seat-locked', 'seat-available', 'seat-selected-admin');
                if(passengerData[i]) {
                    seatEl.classList.add(passengerData[i].status === 'web' ? 'seat-booked' : 'seat-locked');
                } else { seatEl.classList.add('seat-available'); }
            }
        }
    }

    function renderKargoList() {
        const container = document.getElementById('kargoListContainer');
        container.innerHTML = '';
        let count = 0;
        for (const [id, data] of Object.entries(kargoData)) {
            count++;
            const div = document.createElement('div');
            div.className = `kargo-item ${currentSelectedId === id ? 'active' : ''}`;
            div.innerHTML = `<div><div class="fw-bold text-main small">${data.name}</div><div class="text-muted" style="font-size: 0.7rem;">${data.notes} - Rp ${data.price}</div></div><span class="badge ${data.status === 'web' ? 'bg-primary' : 'bg-danger'} px-2 py-1" style="font-size: 0.65rem;">${data.status === 'web' ? 'WEB' : 'MANUAL'}</span>`;
            div.onclick = () => selectItem('kargo', id);
            container.appendChild(div);
        }
        if(count === 0) container.innerHTML = '<div class="small text-muted fst-italic text-center py-2">Belum ada kargo di mobil ini.</div>';
    }

    document.querySelectorAll('.seat-interactive').forEach(seat => {
        seat.addEventListener('click', function() { selectItem('seat', this.getAttribute('data-id')); });
    });

    function selectItem(type, id) {
        currentSelectedType = type; 
        currentSelectedId = id;
        
        document.querySelectorAll('.seat-interactive').forEach(s => s.classList.remove('seat-selected-admin'));
        renderKargoList(); 

        const inputName = document.getElementById('psgName');
        const inputHp = document.getElementById('psgHp');
        const inputPick = document.getElementById('psgPick');
        const inputDrop = document.getElementById('psgDrop');
        const inputNotes = document.getElementById('psgNotes');
        const inputPay = document.getElementById('psgPayStatus');
        const inputPrice = document.getElementById('psgPrice');
        const inputPenerima = document.getElementById('psgPenerima');
        const inputHpPenerima = document.getElementById('psgHpPenerima');
        const btnContainer = document.getElementById('actionButtonsContainer');
        const webNotice = document.getElementById('webNoticeContainer');
        const labelNum = document.getElementById('selectedSeatNumLabel');
        const statusLabel = document.getElementById('seatStatusLabel');

        // --- REVISI: Gunakan status global agar tidak kebingungan saat jam kosong ---
        let isPast = isJadwalPast;

        [inputName, inputHp, inputPick, inputDrop, inputNotes, inputPay, inputPrice, inputPenerima, inputHpPenerima].forEach(el => {
            if(el) { 
                el.disabled = isPast; 
                if (isPast) el.classList.add('input-readonly'); else el.classList.remove('input-readonly');
            }
        });
        
        const rowPenerima = document.getElementById('rowPenerima');
        if(rowPenerima) rowPenerima.style.display = (type === 'kargo') ? 'flex' : 'none';

        let moveContainer = document.getElementById('rowPindahKursi');
        if(type === 'seat' && id !== 'NEW' && !isPast) { 
            let moveSelect = document.getElementById('psgMoveSeat');
            
            if(moveSelect) {
                moveSelect.innerHTML = '<option value="">-- Tetap di Kursi Ini --</option>';
                let adaKosong = false;
                
                for(let i=1; i<=7; i++) {
                    if(!passengerData[i] && i != id) { 
                        moveSelect.innerHTML += `<option value="${i}">Geser ke Kursi ${i}</option>`;
                        adaKosong = true;
                    }
                }
                if (moveContainer) {
                    moveContainer.style.display = (passengerData[id] && adaKosong) ? 'block' : 'none';
                }
            }
        } else {
            if (moveContainer) moveContainer.style.display = 'none';
        }

        if (type === 'kargo') {
            document.getElementById('formTitle').innerText = 'Informasi Kargo WA';
            document.getElementById('formIcon').className = 'bi bi-box-seam me-2 text-warning';
            document.getElementById('labelName').innerText = 'Nama Pengirim'; document.getElementById('labelPick').innerText = 'Alamat Ambil';
            document.getElementById('labelDrop').innerText = 'Alamat Antar'; document.getElementById('labelNotes').innerText = 'Detail Barang';
            labelNum.innerText = ``; labelNum.className = 'text-warning fs-5 ms-1';
        } else {
            document.getElementById('formTitle').innerText = 'Informasi Kursi';
            document.getElementById('formIcon').className = 'bi bi-person-vcard-fill me-2 text-primary';
            document.getElementById('labelName').innerText = 'Nama Penumpang'; document.getElementById('labelPick').innerText = 'Alamat Jemput';
            document.getElementById('labelDrop').innerText = 'Alamat Tujuan'; document.getElementById('labelNotes').innerText = 'Catatan Bawaan';
            labelNum.innerText = id; labelNum.className = 'text-primary fs-5 ms-1';
            
            if(id !== 'NEW') {
                let seatEl = document.querySelector(`.seat-interactive[data-id="${id}"]`);
                if(seatEl) seatEl.classList.add('seat-selected-admin');
            }
        }

        let data = type === 'kargo' ? kargoData[id] : passengerData[id];

        if (data) {
            inputName.value = data.name || ''; inputHp.value = data.hp || ''; 
            inputPick.value = data.pick || ''; inputDrop.value = data.drop || ''; 
            inputNotes.value = data.notes || ''; inputPay.value = data.payStatus || 'Lunas (Manual WA)'; 
            inputPrice.value = data.price || '';
            
            if(type === 'kargo') {
                inputPenerima.value = data.penerima || '';
                inputHpPenerima.value = data.hp_penerima || '';
            }

            if (data.status === 'web') { 
                statusLabel.className = 'badge bg-primary px-3 py-2 rounded-pill'; 
                statusLabel.innerText = 'Pesanan via Web (Bisa Diedit)';
                
                inputPrice.disabled = true; inputPrice.classList.add('input-readonly');
                inputPay.disabled = true; inputPay.classList.add('input-readonly');
                
                btnContainer.style.setProperty('display', 'flex', 'important'); 
                document.getElementById('btnEmptySeat').style.display = 'none'; 
                document.getElementById('btnSaveSeat').innerText = 'Update Data Web';
                
                if(webNotice) webNotice.style.display = 'block'; 
            } else { 
                statusLabel.className = 'badge bg-danger px-3 py-2 rounded-pill'; 
                statusLabel.innerText = 'Input Manual (WA)';
                
                btnContainer.style.setProperty('display', 'flex', 'important'); 
                document.getElementById('btnEmptySeat').style.display = 'block'; 
                document.getElementById('btnSaveSeat').innerText = 'Kunci Posisi Manual';
                
                if(webNotice) webNotice.style.display = 'none';
            }

            if(isPast) {
                btnContainer.style.setProperty('display', 'none', 'important');
                if(webNotice) webNotice.style.display = 'none';
            }
        } 
        else {
                inputName.value = ''; inputHp.value = ''; inputPick.value = ''; inputDrop.value = '';
                inputNotes.value = ''; 

                // Pilih opsi default pembayaran
                inputPay.value = 'Lunas (Manual WA)'; 

                // AUTO FILL HARGA khusus untuk Kursi (Kargo dibiarkan kosong karena beda rumus)
                inputPrice.value = type === 'seat' ? currentJadwalHarga : ''; 

                if(type === 'kargo') { inputPenerima.value = ''; inputHpPenerima.value = ''; }

                statusLabel.className = 'badge bg-success px-3 py-2 rounded-pill'; 
                statusLabel.innerText = type === 'kargo' ? 'Kargo Baru' : 'Kursi Kosong';

                btnContainer.style.setProperty('display', 'flex', 'important'); 
                document.getElementById('btnEmptySeat').style.display = 'block'; 
                document.getElementById('btnSaveSeat').innerText = 'Kunci Posisi Manual';

                if(webNotice) webNotice.style.display = 'none';
                if(document.getElementById('optWeb')) document.getElementById('optWeb').style.display = 'none'; // Sembunyikan opsi web untuk input manual

                if(isPast) btnContainer.style.setProperty('display', 'none', 'important');
            }
    }

    function siapkanKargoBaru() {
        let newIdNum = Object.keys(kargoData).length + 1;
        selectItem('kargo', 'M-PKG-BARU-' + newIdNum);
    }

    function simpanDataLokal() {
        if(!currentSelectedId) return;
        
        const existingData = (currentSelectedType === 'kargo' ? kargoData[currentSelectedId] : passengerData[currentSelectedId]) || {};
        const newStatus = existingData.status === 'web' ? 'web' : 'manual';

        const dataObj = {
            id: existingData.id || null, 
            kode_booking: existingData.kode_booking || null,
            kode_resi: existingData.kode_resi || null,
            name: document.getElementById('psgName').value.trim(), hp: document.getElementById('psgHp').value.trim(),
            penerima: document.getElementById('psgPenerima').value.trim(), hp_penerima: document.getElementById('psgHpPenerima').value.trim(),
            pick: document.getElementById('psgPick').value.trim(), drop: document.getElementById('psgDrop').value.trim(),
            notes: document.getElementById('psgNotes').value.trim(), payStatus: document.getElementById('psgPayStatus').value,
            price: document.getElementById('psgPrice').value.trim(), 
            status: newStatus 
        };

        // --- REVISI: Alert SweetAlert2 untuk validasi kosong ---
        if(!dataObj.name || !dataObj.hp) { 
            const isDark = document.body.getAttribute('data-theme') === 'dark';
            Swal.fire({
                icon: 'warning',
                title: 'Data Belum Lengkap!',
                text: 'Nama dan No. HP harus diisi!',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#dc3545',
                background: isDark ? 'var(--card-bg)' : '#ffffff',
                color: isDark ? 'var(--text-main)' : '#333333'
            });
            return; 
        }

        let pindahKe = "";
        if (currentSelectedType === 'kargo') { 
            kargoData[currentSelectedId] = dataObj; 
            renderKargoList(); 
        } else { 
            pindahKe = document.getElementById('psgMoveSeat').value;
            if(pindahKe !== "") {
                passengerData[pindahKe] = dataObj; 
                delete passengerData[currentSelectedId]; 
                currentSelectedId = pindahKe; 
            } else {
                passengerData[currentSelectedId] = dataObj; 
            }
            refreshModalSeatMap(); 
        }
        
        const isDark = document.body.getAttribute('data-theme') === 'dark';
        Swal.fire({ 
            toast: true, position: 'top-end', icon: 'success', 
            title: pindahKe !== "" ? `Berhasil digeser ke Kursi ${pindahKe}!` : (newStatus === 'web' ? `Data Web Siap Diupdate!` : `Terkunci!`), 
            showConfirmButton: false, timer: 3000,
            background: isDark ? '#16191f' : '#ffffff', color: isDark ? '#ffffff' : '#333333'
        });
        
        selectItem(currentSelectedType, currentSelectedId);
    }

    function kosongkanData() {
        if(!currentSelectedId) return;
        
        if (currentSelectedType === 'kargo') {
            delete kargoData[currentSelectedId]; currentSelectedId = null; renderKargoList();
            document.getElementById('psgName').value = ''; document.getElementById('psgHp').value = '';
            document.getElementById('psgPenerima').value = ''; document.getElementById('psgHpPenerima').value = ''; 
            document.getElementById('psgPick').value = ''; document.getElementById('psgDrop').value = '';
            document.getElementById('psgNotes').value = ''; document.getElementById('psgPrice').value = '';
            document.getElementById('seatStatusLabel').className = 'badge bg-secondary px-3 py-2 rounded-pill'; 
            document.getElementById('seatStatusLabel').innerText = 'Pilih kursi / kargo';
            document.getElementById('actionButtonsContainer').style.setProperty('display', 'none', 'important');
        } else {
            delete passengerData[currentSelectedId]; refreshModalSeatMap();
            document.getElementById('psgName').value = ''; document.getElementById('psgHp').value = '';
            document.getElementById('psgPick').value = ''; document.getElementById('psgDrop').value = '';
            document.getElementById('psgNotes').value = ''; document.getElementById('psgPrice').value = '';
            document.getElementById('seatStatusLabel').className = 'badge bg-success px-3 py-2 rounded-pill'; 
            document.getElementById('seatStatusLabel').innerText = 'Kursi Kosong';
        }
    }

    // --- FUNGSI OPEN MANIFEST (Hanya Lihat, Tidak Bisa Edit) ---
    function openViewOnlyManifest(jadwalId, armadaId, jam, driverId, hargaRute) {
        // 2. Teruskan parameter hargaRute ke fungsi utama
        openKelolaModal(jadwalId, armadaId, jam, driverId, hargaRute); 

        // 3. Matikan semua interaksi user (Timeout agar menunggu modal selesai me-render data)
        setTimeout(() => {
            let submitBtn = document.querySelector('#formUpdateJadwal button[type="submit"]');
            if(submitBtn) submitBtn.style.display = 'none';
            document.getElementById('editArmadaSelect').disabled = true;
            document.getElementById('editJamSelect').disabled = true;
            document.getElementById('editSupirSelect').disabled = true;

            let btnKargoBaru = document.querySelector('button[onclick="siapkanKargoBaru()"]');
            if (btnKargoBaru) btnKargoBaru.style.display = 'none';

            // Kunci semua kursi agar tidak bisa diubah-ubah
            document.querySelectorAll('.seat-interactive').forEach(s => {
                s.style.pointerEvents = 'none';
            });

            let actionContainer = document.getElementById('actionButtonsContainer');
            if(actionContainer) actionContainer.style.setProperty('display', 'none', 'important');
        }, 50); 
    }

    function prepareDataBeforeSubmit() {
        document.getElementById('manualPassengersInput').value = JSON.stringify(passengerData);
        document.getElementById('manualKargosInput').value = JSON.stringify(kargoData);
    }
</script>
@endpush

@endsection