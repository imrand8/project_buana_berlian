@extends('admin.partials.master')

@section('page_title', 'Data Pelanggan')
@section('page_subtitle', 'Kelola database pelanggan dan verifikasi status Mahasiswa (KTM).')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* --- TABS & SEARCH SINKRONISASI --- */
    .nav-pills-custom { background: #e2e8f0; padding: 6px; border-radius: 16px; display: inline-flex; gap: 4px; border: 1px solid rgba(0,0,0,0.05); }
    [data-theme="dark"] .nav-pills-custom { background: #16191f; border-color: rgba(255,255,255,0.05); }
    .nav-pills-custom .nav-link { border-radius: 12px; padding: 10px 22px; font-weight: 700; color: var(--text-muted); border: none; font-size: 0.85rem; transition: 0.3s; }
    .nav-pills-custom .nav-link.active { background: var(--card-bg) !important; color: var(--p-color) !important; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    [data-theme="dark"] .nav-pills-custom .nav-link.active { background: var(--p-color) !important; color: white !important; }

    /* --- SEARCH BOX SIZING --- */
    .search-container {
        display: flex; align-items: center; background: var(--bg-body); 
        border: 1px solid var(--border-color); border-radius: 12px; padding: 0 15px; 
        height: 48px; width: 100%; max-width: 320px; transition: 0.3s;
    }
    .search-container:focus-within { border-color: var(--p-color); box-shadow: 0 0 0 3px rgba(72, 61, 139, 0.1); }
    .search-container input { border: none; background: transparent; color: var(--text-main); width: 100%; padding-left: 10px; outline: none; font-size: 0.9rem; }

    /* --- TABLE LAYOUT --- */
    .table-fixed { table-layout: fixed; width: 100%; min-width: 900px; }
    [data-theme="dark"] .table { color: var(--text-main); }
    [data-theme="dark"] .table thead th { background: #16191f; border-color: #2d333b; color: var(--text-muted); }
    [data-theme="dark"] .table tbody td { border-color: #2d333b; }

    /* User Avatar & Layout */
    .user-avatar { width: 42px; height: 42px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: white; }
    
    /* KTM Image Preview */
    .ktm-preview { width: 100%; height: 220px; border-radius: 12px; object-fit: cover; border: 1px solid var(--border-color); }

    /* --- KOTAK KTM ZOOM (Sama seperti Validasi Pembayaran) --- */
    .receipt-box {
        background: #f1f5f9; border-radius: 14px; height: 180px;
        display: flex; align-items: center; justify-content: center; cursor: pointer;
        overflow: hidden; position: relative; border: 1px solid var(--border-color);
        margin-bottom: 20px;
    }
    [data-theme="dark"] .receipt-box { background: #0f1115; border-color: rgba(255,255,255,0.05); }

    .receipt-box img { width: 100%; height: 100%; object-fit: cover; opacity: 0.9; transition: 0.3s; }
    .receipt-box:hover img { opacity: 1; transform: scale(1.05); }
    
    .overlay-text { 
        position: absolute; background: rgba(0,0,0,0.7); color: white; 
        padding: 8px 16px; border-radius: 50px; font-size: 0.75rem; font-weight: 700; 
        pointer-events: none; backdrop-filter: blur(4px);
    }

    /* Modal Styling Fix untuk Dark Mode */
    .modal-content { background-color: var(--card-bg); color: var(--text-main); border: 1px solid var(--border-color); }
    .info-box { background: var(--bg-body); border: 1px solid var(--border-color); padding: 15px; border-radius: 12px; margin-bottom: 15px; text-align: left; }
    .info-label { font-size: 0.7rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; margin-bottom: 3px; }
    .info-value { font-size: 0.95rem; color: var(--text-main); font-weight: 700; }

    /* Radio Label Styling (Penolakan) */
    .reject-option { cursor: pointer; transition: 0.2s; border: 1px solid var(--border-color); background: var(--bg-body); color: var(--text-main); }
    .reject-option:hover { border-color: #ef4444; background: rgba(239, 68, 68, 0.05); }
    
    .custom-textarea { background: var(--bg-body); color: var(--text-main); border: 1px solid var(--border-color); border-radius: 10px; padding: 10px; }
    .custom-textarea:focus { border-color: var(--p-color); outline: none; box-shadow: 0 0 0 3px rgba(72, 61, 139, 0.1); }

    /* Tombol Cancel Fix untuk Dark Mode */
    .btn-cancel-custom { background: transparent; color: var(--text-main); border: 1px solid var(--text-muted); transition: 0.3s; }
    .btn-cancel-custom:hover { background: var(--border-color); color: var(--text-main); }

    /* Border tambahan untuk modal detail */
    [data-theme="dark"] .border-end-custom { border-right: 1px solid #333333 !important; }
    .border-end-custom { border-right: 1px solid var(--border-color); }
</style>

<div class="content-card bg-white p-4 rounded-4 shadow-sm" style="background: var(--card-bg) !important; border: 1px solid var(--border-color);">
    
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <ul class="nav nav-pills-custom" id="pills-tab" role="tablist">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" onclick="filterPelanggan('semua')">Semua</button></li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="pill" onclick="filterPelanggan('menunggu')">
                    Menunggu Verifikasi 
                    @if($menungguCount > 0)
                        <span class="badge bg-danger ms-1 rounded-pill" id="badge-count">{{ $menungguCount }}</span>
                    @endif
                </button>
            </li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" onclick="filterPelanggan('mahasiswa')">Mahasiswa</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" onclick="filterPelanggan('reguler')">Reguler</button></li>
        </ul>

        <div class="search-container">
            <i class="bi bi-search text-muted"></i>
            <input type="text" id="userSearch" placeholder="Cari Nama, Email, atau No. WA..." onkeyup="searchTable()">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-fixed align-middle" id="pelangganTable">
            <thead class="text-uppercase small fw-bold text-muted">
                <tr>
                    <th style="width: 250px;">Informasi Pelanggan</th>
                    <th style="width: 150px;">Kontak</th>
                    <th style="width: 180px;">Tipe Akun</th>
                    <th style="width: 140px;">Bergabung</th>
                    <th style="width: 120px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="pelangganTableBody">
                
                @forelse($pelanggans as $p)
                    @php
                        // Set kategori tab
                        $statusData = 'reguler';
                        if($p->status_mahasiswa == 'menunggu_verifikasi') $statusData = 'menunggu';
                        if($p->status_mahasiswa == 'terverifikasi') $statusData = 'mahasiswa';
                        
                        // Bikin inisial nama (Misal: Imran Darajati -> ID)
                        $words = explode(' ', $p->name);
                        $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                        
                        // Warna Avatar Random berdasarkan ID
                        $colors = ['bg-primary', 'bg-success', 'bg-danger', 'bg-warning text-dark', 'bg-info text-dark', 'bg-secondary'];
                        $avatarColor = $colors[$p->id % count($colors)];
                        
                        // 🚀 Ambil hasil hitungan dari controller (Travel + Kargo)
                        $totalOrder = $p->pesanan_travel_count + $p->pesanan_kargo_count;
                    @endphp

                    <tr class="pelanggan-row" data-status="{{ $statusData }}" id="row-{{ $p->id }}">
                        <td>
                            <div class="d-flex align-items-center">
                                @if($p->avatar)
                                    <img src="{{ asset('storage/' . $p->avatar) }}" class="user-avatar me-3" style="object-fit: cover;">
                                @else
                                    <div class="user-avatar {{ $avatarColor }} me-3">{{ $initials }}</div>
                                @endif
                                <div>
                                    <div class="fw-bold text-main search-name">{{ $p->name }}</div>
                                    <div class="small text-muted">ID: #CUST-{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small text-main search-email"><i class="bi bi-envelope me-1 text-muted"></i> {{ $p->email }}</div>
                            <div class="small text-main mt-1 search-phone"><i class="bi bi-whatsapp me-1 text-muted"></i> {{ $p->phone }}</div>
                        </td>
                        <td id="badge-col-{{ $p->id }}">
                            @if($p->status_mahasiswa == 'menunggu_verifikasi')
                                <span class="badge bg-warning text-dark border border-warning"><i class="bi bi-hourglass-split me-1"></i>Cek KTM</span>
                            @elseif($p->status_mahasiswa == 'terverifikasi')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success"><i class="bi bi-check-circle-fill me-1"></i>Mahasiswa</span>
                            @else
                                @if($p->alasan_tolak_ktm)
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger"><i class="bi bi-x-circle-fill me-1"></i>KTM Ditolak</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary"><i class="bi bi-person-fill me-1"></i>Reguler</span>
                                @endif
                            @endif
                        </td>
                        <td>
                            <span class="fw-bold text-main" style="font-size: 0.85rem;">{{ $p->created_at->format('d M Y') }}</span>
                        </td>
                        <td class="text-center" id="btn-col-{{ $p->id }}">
                            @if($p->status_mahasiswa == 'menunggu_verifikasi')
                                <button class="btn btn-sm btn-primary fw-bold px-3 rounded-3" onclick="bukaModalKTM({{ $p->id }}, '{{ $p->name }}', '{{ asset('storage/' . $p->ktm_path) }}')">Verifikasi</button>
                            @else
                                <button class="btn btn-sm btn-outline-secondary fw-bold px-3 rounded-3" onclick="bukaDetail({{ $p->id }}, '{{ $p->name }}', '{{ $p->email }}', '{{ $p->phone }}', '{{ $p->status_mahasiswa }}', '{{ $p->created_at->format('d M Y') }}', '{{ $initials }}', '{{ $avatarColor }}', {{ $totalOrder }})"><i class="bi bi-eye"></i> Detail</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i> Belum ada data pelanggan terdaftar.
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="ktmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold m-0 text-main"><i class="bi bi-person-vcard text-primary me-2"></i>Verifikasi KTM</h5>
                <button type="button" class="btn border-0 p-0" data-bs-dismiss="modal" style="color: var(--text-muted);"><i class="bi bi-x-lg fs-5"></i></button>
            </div>
            <div class="modal-body p-4 text-center">
                
                <div class="info-box">
                    <div class="row">
                        <div class="col-12 text-center">
                            <div class="info-label">Nama Pendaftar</div>
                            <div class="info-value" id="modal-ktm-name">Memuat...</div>
                        </div>
                    </div>
                </div>

                <p class="small text-muted mb-2 text-start fw-bold">Foto Kartu Tanda Mahasiswa (KTM):</p>
                
                <!-- Pakai gaya receipt-box biar sama persis -->
                <div class="receipt-box" onclick="bukaZoomKTM()">
                    <img id="modal-ktm-image" src="" alt="KTM Preview">
                    <div class="overlay-text"><i class="bi bi-zoom-in"></i> Perbesar KTM</div>
                </div>

                <div class="row g-2">
                    <div class="col-6">
                        <button class="btn btn-outline-danger w-100 fw-bold py-2 rounded-3" id="btn-tolak-modal">Tolak & Kembalikan</button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-success w-100 fw-bold py-2 rounded-3" id="btn-terima-modal">ACC Mahasiswa</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ZOOM KTM -->
<div class="modal fade" id="zoomKtmModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Detail Foto KTM</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <!-- Gambar KTM (max-height dikurangi sedikit biar panduannya muat di layar) -->
                <img id="zoom-ktm-image-preview" src="" class="img-fluid rounded-4 shadow-sm" alt="KTM Penuh" style="max-height: 65vh; object-fit: contain;">
                
                <!-- KOTAK PANDUAN ADMIN -->
                <div class="mt-4 p-3 rounded-3 text-start" style="background: var(--bg-body); border: 1px solid var(--border-color);">
                    <div class="small fw-bold mb-1" style="color: var(--text-muted);">Panduan Admin:</div>
                    <div class="small fst-italic" style="color: var(--text-main);">Pastikan Nama pada Kartu Tanda Mahasiswa (KTM) sesuai dengan Nama Pendaftar, dan pastikan dokumen terlihat jelas (asli/tidak buram).</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectKtmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold m-0">Alasan Penolakan KTM</h5>
                <button type="button" class="btn border-0 p-0" data-bs-dismiss="modal" style="color: var(--text-muted);"><i class="bi bi-x-lg fs-5"></i></button>
            </div>
            <div class="modal-body p-4">
                <p class="small text-muted mb-3">Pilih alasan penolakan agar pelanggan mengetahui letak kesalahan dan dapat mengunggah ulang di akunnya.</p>
                
                <input type="hidden" id="reject-user-id">

                <div class="d-flex flex-column gap-2 mb-3">
                    <label class="reject-option p-3 rounded-3 d-flex align-items-center mb-0">
                        <input type="radio" name="ktm_reason" class="form-check-input ms-0 me-3" value="Buram">
                        <span class="fw-bold small">Foto KTM Buram / Tidak Terbaca</span>
                    </label>
                    <label class="reject-option p-3 rounded-3 d-flex align-items-center mb-0">
                        <input type="radio" name="ktm_reason" class="form-check-input ms-0 me-3" value="Beda Nama">
                        <span class="fw-bold small">Nama di KTM tidak sesuai akun</span>
                    </label>
                    <label class="reject-option p-3 rounded-3 d-flex align-items-center mb-0">
                        <input type="radio" name="ktm_reason" class="form-check-input ms-0 me-3" value="Palsu">
                        <span class="fw-bold small">Terindikasi file bukan KTM Asli</span>
                    </label>
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-danger fw-bold py-3 rounded-3 shadow-sm" onclick="prosesTolakKTM()"><i class="bi bi-send-fill me-2"></i>Kirim Penolakan</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold m-0">Detail Profil Pelanggan</h5>
                <button type="button" class="btn border-0 p-0" data-bs-dismiss="modal" style="color: var(--text-muted);"><i class="bi bi-x-lg fs-5"></i></button>
            </div>
            <div class="modal-body p-4 text-center">
                
                <div id="detail-avatar" class="user-avatar mx-auto mb-3" style="width: 70px; height: 70px; font-size: 1.5rem;">XX</div>
                <h5 class="fw-bold mb-1 text-main" id="detail-name">Nama</h5>
                <span id="detail-badge" class="badge mb-3">Status</span>
                
                <div class="info-box d-flex justify-content-between text-center mb-4 mt-2 px-3 py-3" style="border-radius: 16px;">
                    <div class="w-100 border-end-custom">
                        <div class="info-label" style="font-size: 0.65rem;">ID Pelanggan</div>
                        <div class="info-value text-main fs-6" id="detail-id">#CUST-0000</div>
                    </div>
                    <div class="w-100 border-end-custom">
                        <div class="info-label" style="font-size: 0.65rem;">Total Order</div>
                        <div class="info-value text-primary fs-6" id="detail-order">0x</div>
                    </div>
                    <div class="w-100">
                        <div class="info-label" style="font-size: 0.65rem;">Bergabung</div>
                        <div class="info-value text-main fs-6" id="detail-date">Tanggal</div>
                    </div>
                </div>

                <div class="text-start mb-3">
                    <div class="small fw-bold text-muted mb-2">INFO KONTAK:</div>
                    <div class="d-flex align-items-center mb-2 text-main"><i class="bi bi-envelope fs-5 text-muted me-3"></i> <span id="detail-email">email</span></div>
                    <div class="d-flex align-items-center mb-2 text-main"><i class="bi bi-whatsapp fs-5 text-success me-3"></i> <span id="detail-phone">phone</span></div>
                </div>

                <!-- Hidden Input untuk nyimpen ID User yang lagi dibuka -->
                <input type="hidden" id="detail-user-id">

                <!-- Tombol Aksi Bahaya -->
                <div class="row g-2 mt-2 mb-2">
                    <div class="col-6">
                        <button class="btn btn-warning w-100 fw-bold py-2 rounded-3 text-dark" onclick="resetPasswordUser()"><i class="bi bi-key-fill"></i> Reset PW</button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-danger w-100 fw-bold py-2 rounded-3" onclick="hapusAkunUser()"><i class="bi bi-trash3-fill"></i> Hapus Akun</button>
                    </div>
                </div>

                <button class="btn btn-cancel-custom w-100 fw-bold py-2 rounded-3 mt-3" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function getSwalTheme() {
        const isDark = document.body.getAttribute('data-theme') === 'dark';
        return { background: isDark ? 'var(--card-bg)' : '#ffffff', color: isDark ? 'var(--text-main)' : '#000000' };
    }

    // --- FUNGSI FILTER TAB ---
    function filterPelanggan(status) {
        let rows = document.querySelectorAll('.pelanggan-row');
        rows.forEach(row => {
            if (status === 'semua' || row.getAttribute('data-status') === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // --- FUNGSI SEARCH BOX ---
    function searchTable() {
        let input = document.getElementById("userSearch").value.toLowerCase();
        let rows = document.querySelectorAll(".pelanggan-row");
        
        rows.forEach(row => {
            let name = row.querySelector(".search-name").innerText.toLowerCase();
            let email = row.querySelector(".search-email").innerText.toLowerCase();
            let phone = row.querySelector(".search-phone").innerText.toLowerCase();
            
            if (name.includes(input) || email.includes(input) || phone.includes(input)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    // --- BUKA MODAL DETAIL UPDATE (Tambah argumen totalOrder) ---
    function bukaDetail(id, name, email, phone, status, date, initials, colorClass, totalOrder) {
        document.getElementById('detail-name').innerText = name;
        document.getElementById('detail-email').innerText = email;
        document.getElementById('detail-phone').innerText = phone;
        document.getElementById('detail-date').innerText = date;
        document.getElementById('detail-id').innerText = '#CUST-' + String(id).padStart(4, '0');
        document.getElementById('detail-order').innerText = totalOrder + 'x'; // Inject Total Order
        document.getElementById('detail-user-id').value = id;
        
        let avatar = document.getElementById('detail-avatar');
        avatar.className = 'user-avatar mx-auto mb-3 ' + colorClass;
        avatar.innerText = initials;

        let badge = document.getElementById('detail-badge');
        if(status === 'terverifikasi') {
            badge.className = 'badge bg-success bg-opacity-10 text-success border border-success mb-3';
            badge.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>Mahasiswa Aktif';
        } else {
            badge.className = 'badge bg-secondary bg-opacity-10 text-secondary border border-secondary mb-3';
            badge.innerHTML = '<i class="bi bi-person-fill me-1"></i>Reguler';
        }

        var myModal = new bootstrap.Modal(document.getElementById('detailModal'));
        myModal.show();
    }

    // --- FUNGSI RESET PASSWORD ---
    function resetPasswordUser() {
        let id = document.getElementById('detail-user-id').value;
        const theme = getSwalTheme();

        Swal.fire({
            title: 'Reset Password?',
            text: "Password akan diubah menjadi '12345678'. Beritahu pelanggan setelah mereset.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Reset!',
            background: theme.background, color: theme.color
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Mereset...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() }, background: theme.background, color: theme.color });
                fetch(`/admin/pelanggan/${id}/reset-password`, {
                    method: 'PUT',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire({ title: "Berhasil!", text: data.message, icon: "success", background: theme.background, color: theme.color });
                });
            }
        });
    }

    // --- FUNGSI HAPUS AKUN ---
    function hapusAkunUser() {
        let id = document.getElementById('detail-user-id').value;
        const theme = getSwalTheme();

        Swal.fire({
            title: 'Hapus Akun Permanen?',
            text: "Semua data profil pelanggan ini akan lenyap selamanya!",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Musnahkan!',
            background: theme.background, color: theme.color
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() }, background: theme.background, color: theme.color });
                fetch(`/admin/pelanggan/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire({ title: "Terhapus!", text: data.message, icon: "success", background: theme.background, color: theme.color }).then(() => {
                        location.reload(); // Refresh halaman setelah dihapus
                    });
                });
            }
        });
    }

    // --- BUKA MODAL KTM (Siapkan Data) ---
    let ktmModalInstance;
    function bukaModalKTM(id, name, imagePath) {
        document.getElementById('modal-ktm-name').innerText = name;
        document.getElementById('modal-ktm-image').src = imagePath;
        
        document.getElementById('btn-terima-modal').setAttribute('onclick', `prosesTerimaKTM(${id})`);
        document.getElementById('btn-tolak-modal').setAttribute('onclick', `bukaModalTolak(${id})`);

        ktmModalInstance = new bootstrap.Modal(document.getElementById('ktmModal'));
        ktmModalInstance.show();
    }
    
    // --- BUKA MODAL ZOOM KTM (MENGADOPSI LOGIKA VALIDASI) ---
    function bukaZoomKTM() {
        let imgSrc = document.getElementById('modal-ktm-image').src;
        document.getElementById('zoom-ktm-image-preview').src = imgSrc;
        
        let zoomModal = new bootstrap.Modal(document.getElementById('zoomKtmModal'));
        zoomModal.show();
    }

    // --- PROSES ACC KTM (AJAX) ---
    function prosesTerimaKTM(id) {
        const theme = getSwalTheme();
        
        Swal.fire({ title: 'Memproses...', text: 'Memverifikasi status mahasiswa.', allowOutsideClick: false, didOpen: () => { Swal.showLoading() }, background: theme.background, color: theme.color });

        fetch(`/admin/pelanggan/${id}/terima`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            ktmModalInstance.hide();
            Swal.fire({ title: "Verifikasi Berhasil!", text: data.message, icon: "success", background: theme.background, color: theme.color, confirmButtonColor: "#22c55e" });
            
            let row = document.getElementById(`row-${id}`);
            row.setAttribute('data-status', 'mahasiswa');
            
            document.getElementById(`badge-col-${id}`).innerHTML = '<span class="badge bg-success bg-opacity-10 text-success border border-success"><i class="bi bi-check-circle-fill me-1"></i>Mahasiswa</span>';
            // Reload page is easiest to reflect new states without massive DOM manipulation
            document.getElementById(`btn-col-${id}`).innerHTML = `<button class="btn btn-sm btn-outline-secondary fw-bold px-3 rounded-3" onclick="location.reload()"><i class="bi bi-eye"></i> Detail</button>`;
            
            let badge = document.getElementById('badge-count');
            if(badge) {
                let count = parseInt(badge.innerText) - 1;
                if(count > 0) badge.innerText = count;
                else badge.style.display = 'none';
            }
        });
    }

    // --- BUKA MODAL TOLAK ---
    let rejectModalInstance;
    function bukaModalTolak(id) {
        ktmModalInstance.hide();
        document.getElementById('reject-user-id').value = id;
        
        rejectModalInstance = new bootstrap.Modal(document.getElementById('rejectKtmModal'));
        rejectModalInstance.show();
    }

    // --- PROSES TOLAK KTM (AJAX) ---
    function prosesTolakKTM() {
        let id = document.getElementById('reject-user-id').value;
        let reasonSelected = document.querySelector('input[name="ktm_reason"]:checked');
        const theme = getSwalTheme();

        if (!reasonSelected) {
            Swal.fire({ title: "Pilih Alasan!", text: "Kamu harus memilih salah satu alasan penolakan.", icon: "warning", background: theme.background, color: theme.color, confirmButtonColor: "#483d8b" });
            return;
        }

        Swal.fire({ title: 'Menolak KTM...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() }, background: theme.background, color: theme.color });

        fetch(`/admin/pelanggan/${id}/tolak`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ alasan: reasonSelected.value })
        })
        .then(async response => {
            if (!response.ok) {
                const text = await response.text();
                throw new Error(text);
            }
            return response.json();
        })
        .then(data => {
            rejectModalInstance.hide();
            Swal.fire({ title: 'KTM Ditolak!', text: data.message, icon: 'success', background: theme.background, color: theme.color, confirmButtonColor: '#483d8b' });
            
            let row = document.getElementById(`row-${id}`);
            row.setAttribute('data-status', 'reguler');
            
            document.getElementById(`badge-col-${id}`).innerHTML = '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger"><i class="bi bi-x-circle-fill me-1"></i>KTM Ditolak</span>';
            document.getElementById(`btn-col-${id}`).innerHTML = `<button class="btn btn-sm btn-outline-secondary fw-bold px-3 rounded-3" onclick="location.reload()"><i class="bi bi-eye"></i> Detail</button>`;
            
            let badge = document.getElementById('badge-count');
            if(badge) {
                let count = parseInt(badge.innerText) - 1;
                if(count > 0) badge.innerText = count;
                else badge.style.display = 'none';
            }
        })
        .catch(error => {
            rejectModalInstance.hide();
            console.error(error);
            Swal.fire({ title: 'Sistem Error!', text: 'Terjadi kesalahan saat menyimpan ke database.', icon: 'error', background: theme.background, color: theme.color });
        });
    }
</script>
@endpush

@endsection