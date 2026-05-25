@extends('admin.partials.master')

@section('page_title', 'Validasi Pembayaran')
@section('page_subtitle', 'Periksa bukti transfer dan konfirmasi status pesanan COD pelanggan.')

@section('content')

<style>
    /* --- TABS SINKRONISASI (MODERN) --- */
    .nav-pills-custom {
        background: #e2e8f0; padding: 6px; border-radius: 16px; display: inline-flex; gap: 4px; border: 1px solid rgba(0,0,0,0.05);
    }
    [data-theme="dark"] .nav-pills-custom { background: #16191f; border-color: rgba(255,255,255,0.05); }

    .nav-pills-custom .nav-link {
        border-radius: 12px; padding: 10px 22px; font-weight: 700; color: var(--text-muted); border: none; font-size: 0.85rem; transition: 0.3s; cursor: pointer;
    }
    .nav-pills-custom .nav-link.active {
        background: var(--card-bg) !important; color: var(--p-color) !important; box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    [data-theme="dark"] .nav-pills-custom .nav-link.active { background: var(--p-color) !important; color: white !important; }

    /* --- PAYMENT GRID --- */
    .payment-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 25px;
        margin-top: 25px;
    }

    .payment-card {
        background: var(--card-bg); border-radius: var(--radius); padding: 22px;
        border: 1px solid var(--border-color); box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        display: flex; flex-direction: column; transition: 0.3s;
    }
    .payment-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(0,0,0,0.05); }

    /* --- KOTAK STRUK / COD --- */
    .receipt-box {
        background: #f1f5f9; border-radius: 14px; height: 160px;
        display: flex; align-items: center; justify-content: center; cursor: pointer;
        overflow: hidden; position: relative; border: 1px solid var(--border-color);
    }
    [data-theme="dark"] .receipt-box { background: #0f1115; border-color: rgba(255,255,255,0.05); }

    .receipt-box img { width: 100%; height: 100%; object-fit: cover; opacity: 0.9; transition: 0.3s; }
    .receipt-box:hover img { opacity: 1; transform: scale(1.05); }
    
    .overlay-text { 
        position: absolute; background: rgba(0,0,0,0.7); color: white; 
        padding: 8px 16px; border-radius: 50px; font-size: 0.75rem; font-weight: 700; 
        pointer-events: none; backdrop-filter: blur(4px);
    }

    .info-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.85rem; }
    .info-label { color: var(--text-muted); font-weight: 600; }
    .info-value { color: var(--text-main); font-weight: 800; }

    /* --- TOMBOL AKSI --- */
    .action-buttons { display: flex; gap: 12px; margin-top: 20px; }
    .btn-acc { flex: 1; background: rgba(34, 197, 94, 0.1); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.3); font-weight: 700; padding: 12px; border-radius: 12px; transition: 0.3s; border:none; }
    .btn-acc:hover { background: #22c55e; color: white !important; }
    
    .btn-reject { flex: 1; background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); font-weight: 700; padding: 12px; border-radius: 12px; transition: 0.3s; border:none; }
    .btn-reject:hover { background: #ef4444; color: white !important; }

    /* --- PERBAIKAN MODAL DARK MODE --- */
    .modal-content { background-color: var(--card-bg); color: var(--text-main); border: 1px solid var(--border-color); }
    [data-theme="dark"] .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }
    
    .btn-cancel-custom { background: transparent; color: var(--text-main); border: 1px solid var(--text-muted); transition: 0.3s; }
    .btn-cancel-custom:hover { background: var(--border-color); color: var(--text-main); }

    .reject-option { cursor: pointer; transition: 0.2s; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-main); }
    .reject-option:hover { border-color: #ef4444; background: rgba(239, 68, 68, 0.05); }
    .reject-option:has(input:checked) { border-color: #ef4444; background: rgba(239, 68, 68, 0.1); } /* Kotak nyala saat dipilih */
    
    [data-theme="dark"] .form-check-input { background-color: var(--card-bg); border-color: var(--border-color); }
    [data-theme="dark"] .form-check-input:checked { background-color: #ef4444; border-color: #ef4444; }
    
    .custom-textarea { background: var(--bg-color); color: var(--text-main); border: 1px solid var(--border-color); }
    .custom-textarea:focus { background: var(--bg-color); color: var(--text-main); border-color: var(--p-color); box-shadow: 0 0 0 3px rgba(72, 61, 139, 0.1); outline: none; }
    [data-theme="dark"] .custom-textarea::placeholder { color: rgba(255, 255, 255, 0.4); }

        /* Biar kalender/date picker di dark mode nggak silau */
    [data-theme="dark"] input[type="date"], 
    [data-theme="dark"] input[type="month"] { 
        color-scheme: dark; 
    }

    /* --- FIX ALERT NOTIFIKASI DARK MODE --- */
    [data-theme="dark"] .alert-success { 
        background-color: rgba(34, 197, 94, 0.1) !important; 
        color: #22c55e !important; 
        border: 1px solid rgba(34, 197, 94, 0.3) !important; 
    }
    
    [data-theme="dark"] .alert-danger { 
        background-color: rgba(239, 68, 68, 0.1) !important; 
        color: #ef4444 !important; 
        border: 1px solid rgba(239, 68, 68, 0.3) !important; 
    }

    /* Memastikan background modal penolakan tetap gelap */
    [data-theme="dark"] .modal-content { 
        background-color: var(--card-bg) !important; 
        color: var(--text-main) !important;
        border: 1px solid var(--border-color);
    }

    /* Fix untuk textarea di mode gelap */
    [data-theme="dark"] .custom-textarea {
        background: #181a20 !important;
        color: white !important;
        border-color: #333 !important;
    }
</style>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3 p-3 rounded-4" style="background: var(--bg-color); border: 1px solid var(--border-color);">
    <ul class="nav nav-pills-custom m-0" id="pills-tab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active tab-btn" data-target="pending_tf" onclick="filterPayment('pending_tf', this)">Cek Transfer <span class="badge bg-danger ms-2 rounded-pill" id="badgeTf">0</span></button>
        </li>
        <li class="nav-item">
            <button class="nav-link tab-btn" data-target="pending_cod" onclick="filterPayment('pending_cod', this)">Konfirmasi CASH <span class="badge bg-warning text-dark ms-2 rounded-pill" id="badgeCod">0</span></button>
        </li>
        <li class="nav-item">
            <button class="nav-link tab-btn" data-target="lunas" onclick="filterPayment('lunas', this)">Riwayat Lunas</button>
        </li>
        <li class="nav-item">
            <button class="nav-link tab-btn" data-target="ditolak" onclick="filterPayment('ditolak', this)">Ditolak</button>
        </li>
    </ul>

    <div class="d-flex align-items-center gap-2">
        <input type="date" id="filterTanggal" class="form-control border-0 shadow-sm" style="background: var(--card-bg); color: var(--text-main); border-radius: 12px; height: 48px; width: 160px; padding: 0 15px;" value="{{ $tanggal }}" onchange="window.location.href='?tanggal=' + this.value">
        
        <div class="search-container" style="display: flex; align-items: center; background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px; padding: 0 15px; height: 48px; width: 100%; max-width: 250px;">
            <i class="bi bi-search text-muted"></i>
            <input type="text" id="orderSearch" placeholder="Cari Nama/ID..." style="border: none; background: transparent; color: var(--text-main); width: 100%; padding-left: 10px; outline: none; font-size: 0.9rem;">
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mt-3 mb-0 rounded-3 fw-bold"><i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}</div>
@endif

<div class="payment-grid">
    
    @php
        $semuaPesanan = collect($travels)->merge($kargos)->sortByDesc('created_at');
        $pendingTfCount = 0;
        $pendingCodCount = 0;
    @endphp

    @forelse($semuaPesanan as $pesanan)
        @php
            $isTravel = isset($pesanan->kode_booking);
            $tipe = $isTravel ? 'travel' : 'kargo';
            $kode = $isTravel ? $pesanan->kode_booking : $pesanan->kode_resi;
            $nama = $isTravel ? $pesanan->nama_penumpang : $pesanan->nama_pengirim;
            $noWaRaw = $isTravel ? $pesanan->nomor_wa : $pesanan->nomor_wa_pengirim;
            $buktiPath = $pesanan->bukti_transfer ? asset('storage/' . $pesanan->bukti_transfer) : null;
            $isCOD = strtoupper($pesanan->metode_bayar) === 'CASH' || strtoupper($pesanan->metode_bayar) === 'COD';
            
            // Konversi Nomor WA untuk Link (08 -> 628)
            $noWaFormatted = preg_replace('/^0/', '62', $noWaRaw);
            $waText = urlencode("Halo {$nama}, saya admin Buana Berlian Travel. Mengkonfirmasi pesanan " . ($isTravel ? 'tiket travel' : 'kargo') . " dengan metode COD sebesar Rp" . number_format($pesanan->total_harga,0,',','.') . ". Apakah pesanan ini benar akan dibayar saat penjemputan?");
            $waLink = "https://wa.me/{$noWaFormatted}?text={$waText}";

            // Tentukan Status untuk UI Tab
            $uiStatus = 'unknown';
            if($pesanan->status_pesanan == 'menunggu_verifikasi') {
                if($isCOD) {
                    $uiStatus = 'pending_cod';
                    $pendingCodCount++;
                } else {
                    $uiStatus = 'pending_tf';
                    $pendingTfCount++;
                }
            } elseif($pesanan->status_pesanan == 'lunas') {
                $uiStatus = 'lunas';
            } elseif($pesanan->status_pesanan == 'ditolak'  || $pesanan->status_pesanan == 'batal') {
                $uiStatus = 'ditolak';
            }
        @endphp

        <div class="payment-card" data-status="{{ $uiStatus }}" style="{{ $uiStatus == 'pending_tf' ? '' : 'display: none;' }}">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <span class="fw-bold {{ $isTravel ? 'text-primary' : '' }}" style="{{ !$isTravel ? 'color: #d97706;' : '' }}">#{{ $kode }}</span>
                    <div class="small" style="color: var(--text-muted);">{{ $pesanan->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</div>
                </div>
                
                @if($uiStatus == 'pending_tf')
                    <span class="status-badge" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3); padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold;">Cek Mutasi</span>
                @elseif($uiStatus == 'pending_cod')
                    <span class="status-badge" style="background: rgba(13, 110, 253, 0.1); color: #0d6efd; border: 1px solid rgba(13, 110, 253, 0.3); padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold;">Butuh Konfirmasi</span>
                @elseif($uiStatus == 'lunas')
                    <span class="status-badge" style="background: rgba(34, 197, 94, 0.1); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.3); padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold;">Lunas & Di-ACC</span>
                @elseif($uiStatus == 'ditolak')
                    <span class="status-badge" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold;">Ditolak / Batal</span>
                @endif
            </div>

            @if($isCOD)
                <div class="receipt-box flex-column mb-3 text-center" style="background: rgba(37, 211, 102, 0.05); border: 1px solid rgba(37, 211, 102, 0.2); cursor: default;">
                    <i class="bi bi-whatsapp mb-2" style="font-size: 2.5rem; color: #25D366;"></i>
                    <span class="fw-bold text-main" style="font-size: 0.9rem;">Metode Bayar Tunai (COD)</span>
                    <a href="{{ $waLink }}" target="_blank" class="btn btn-sm mt-2 px-4 shadow-sm" style="background: #25D366; color: white; border-radius: 50px; font-weight: bold; font-size: 0.8rem;">
                        <i class="bi bi-chat-dots me-1"></i> Hubungi Pelanggan
                    </a>
                </div>
            @else
                <div class="receipt-box mb-3" onclick="openReceiptModal('{{ $buktiPath }}', '{{ $uiStatus }}')">
                    @if($buktiPath)
                        <img src="{{ $buktiPath }}" alt="Bukti Transfer" {!! $uiStatus == 'ditolak' ? 'style="filter: grayscale(100%); opacity: 0.6;"' : '' !!}>
                        <div class="overlay-text" {!! $uiStatus == 'ditolak' ? 'style="background: rgba(239, 68, 68, 0.8);"' : '' !!}><i class="bi bi-zoom-in"></i> {{ $uiStatus == 'ditolak' ? 'Struk Ditolak' : 'Lihat Struk' }}</div>
                    @else
                        <div class="text-muted fw-bold d-flex flex-column align-items-center w-100 h-100 justify-content-center" style="background: var(--bg-color);">
                            <i class="bi bi-image-alt" style="font-size: 2.5rem; margin-bottom: 5px;"></i>
                            <span>Bukti Kosong</span>
                        </div>
                    @endif
                </div>
            @endif

            <div class="info-row"><span class="info-label">Pelanggan</span><span class="info-value text-truncate" style="max-width: 150px;">{{ $nama }}</span></div>
            <div class="info-row"><span class="info-label">Layanan</span><span class="info-value">{{ $isTravel ? 'Travel' : 'Kargo' }} ({{ strtoupper($pesanan->metode_bayar) }})</span></div>
            <div class="info-row mt-2 pt-2 border-top">
                <span class="info-label fw-bold">Total Tagihan</span>
                <span class="info-value text-primary fs-5">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
            </div>

            @if($uiStatus == 'pending_tf' || $uiStatus == 'pending_cod')
                <div class="action-buttons">
                    <button class="btn-reject" onclick="openRejectModal('{{ $kode }}', '{{ $tipe }}', {{ $pesanan->id }}, {{ $isCOD ? 'true' : 'false' }})"><i class="bi bi-x-circle me-1"></i> Tolak</button>
                    <button class="btn-acc" onclick="openAccModal('{{ $kode }}', '{{ $tipe }}', {{ $pesanan->id }}, {{ $isCOD ? 'true' : 'false' }})"><i class="bi bi-check-circle me-1"></i> ACC</button>
                </div>
            @endif
        </div>
    @empty
        <div class="col-12 w-100 text-center py-5 text-muted" style="grid-column: 1 / -1;">
            <i class="bi bi-inbox fs-1"></i>
            <p class="mt-2 fw-bold">Belum ada transaksi di tab ini.</p>
        </div>
    @endforelse

</div>

<div class="modal fade" id="receiptModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Detail Bukti Transfer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div id="receiptImageContainer">
                    <img id="receiptImagePreview" src="" class="img-fluid rounded-4 shadow-sm" alt="Struk Penuh">
                </div>
                <div class="mt-4 p-3 rounded-3 text-start" style="background: var(--bg-color); border: 1px solid var(--border-color);">
                    <div class="small fw-bold mb-1" style="color: var(--text-muted);">Panduan Admin:</div>
                    <div class="small fst-italic" style="color: var(--text-main);">Pastikan Nominal dan Tanggal Transfer sesuai dengan mutasi rekening bank perusahaan.</div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="globalAccForm" method="POST" style="display: none;">
    @csrf @method('PUT')
</form>

<div class="modal fade" id="accModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content text-center p-4 shadow-lg" style="border-radius: 20px;">
            <div class="text-success mb-3" style="font-size: 4rem;"><i class="bi bi-check-circle-fill"></i></div>
            <h5 class="fw-bold mb-2">Konfirmasi Valid?</h5>
            <p class="small mb-4" style="color: var(--text-muted);" id="accMessage">Pastikan dana sudah masuk. Status pesanan <strong id="accOrderId" class="text-primary"></strong> akan berubah menjadi LUNAS.</p>
            <div class="d-grid gap-2">
                <button class="btn btn-success fw-bold py-2 rounded-3" onclick="processAcc()">Ya, Konfirmasi</button>
                <button class="btn btn-cancel-custom fw-bold py-2 rounded-3" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="globalRejectForm" method="POST" class="modal-content shadow-lg" style="border-radius: 20px;">
            @csrf @method('PUT')
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold m-0 text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Batalkan Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="small mb-3" style="color: var(--text-muted);">Pilih alasan kenapa pesanan <span class="fw-bold text-primary" id="rejectOrderId"></span> ini dibatalkan.</p>
                
                <div class="d-flex flex-column gap-2" id="rejectOptionsTF">
                    <label class="reject-option p-3 rounded-3 d-flex align-items-center mb-0" for="tolakTF1">
                        <input type="radio" name="alasan_penolakan" id="tolakTF1" class="form-check-input ms-0 me-3" value="Struk Buram / Tidak Valid" required>
                        <span class="fw-bold small">Bukti Transfer Buram / Editan</span>
                    </label>
                    <label class="reject-option p-3 rounded-3 d-flex align-items-center mb-0" for="tolakTF2">
                        <input type="radio" name="alasan_penolakan" id="tolakTF2" class="form-check-input ms-0 me-3" value="Nominal Kurang">
                        <span class="fw-bold small">Nominal Transfer Tidak Sesuai</span>
                    </label>
                    <label class="reject-option p-3 rounded-3 d-flex align-items-center mb-0" for="tolakTF3">
                        <input type="radio" name="alasan_penolakan" id="tolakTF3" class="form-check-input ms-0 me-3" value="Dibatalkan (Permintaan Pelanggan / Spam)">
                        <span class="fw-bold small">Batalkan Pesanan (Refund / Spam / Kadaluarsa)</span>
                    </label>
                </div>

                <div class="d-flex flex-column gap-2" id="rejectOptionsCOD" style="display: none;">
                    <label class="reject-option p-3 rounded-3 d-flex align-items-center mb-0" for="tolakCOD1">
                        <input type="radio" name="alasan_penolakan" id="tolakCOD1" class="form-check-input ms-0 me-3" value="Pelanggan Tidak Bisa Dihubungi" required>
                        <span class="fw-bold small">Pelanggan tidak bisa dihubungi (No Respon)</span>
                    </label>
                    <label class="reject-option p-3 rounded-3 d-flex align-items-center mb-0" for="tolakCOD2">
                        <input type="radio" name="alasan_penolakan" id="tolakCOD2" class="form-check-input ms-0 me-3" value="Orderan Fiktif / Palsu">
                        <span class="fw-bold small">Orderan Fiktif / Palsu</span>
                    </label>
                </div>

                <div class="mt-3">
                    <label class="small fw-bold mb-2" style="color: var(--text-muted);">Alasan Lainnya (Opsional):</label>
                    <textarea name="alasan_custom" class="form-control custom-textarea p-3 rounded-3" rows="2" placeholder="Tulis catatan penolakan tambahan..."></textarea>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-danger fw-bold py-3 rounded-3 shadow-sm">Batalkan Order</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // --- Inisialisasi Badge ---
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('badgeTf').innerText = "{{ $pendingTfCount }}";
        document.getElementById('badgeCod').innerText = "{{ $pendingCodCount }}";
    });


    // --- TAB FILTER LOGIC + MEMORI ---
    function filterPayment(statusTarget, element) {
        document.querySelectorAll('.nav-pills-custom .nav-link').forEach(btn => btn.classList.remove('active'));
        element.classList.add('active');

        document.querySelectorAll('.payment-card').forEach(card => {
            if (card.getAttribute('data-status') === statusTarget) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
        
        // Simpan posisi terakhir ke LocalStorage
        localStorage.setItem('activeValidasiTab', statusTarget);
    }

    // --- FITUR PENCARIAN (SEARCH BAR) ---
    document.getElementById('orderSearch').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let activeTab = localStorage.getItem('activeValidasiTab') || 'pending_tf'; // Cek tab mana yang lagi dibuka
        
        document.querySelectorAll('.payment-card').forEach(card => {
            let text = card.innerText.toLowerCase();
            let status = card.getAttribute('data-status');
            
            // Tampilkan kartu JIKA statusnya cocok dengan tab aktif DAN teksnya cocok dengan pencarian
            if (status === activeTab && text.includes(filter)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Eksekusi otomatis saat halaman selesai loading (Habis refresh)
    document.addEventListener("DOMContentLoaded", function() {
        const activeTabStatus = localStorage.getItem('activeValidasiTab');
        if (activeTabStatus) {
            // Cari tombol yang sesuai dengan memori
            const activeBtn = document.querySelector(`.tab-btn[data-target="${activeTabStatus}"]`);
            if (activeBtn) {
                // Jalankan fungsi klik secara otomatis
                filterPayment(activeTabStatus, activeBtn);
            }
        }
    });

    // --- VIEW STRUK LOGIC ---
    function openReceiptModal(imageUrl, status) {
        if(!imageUrl) return; // COD ngga usah buka gambar
        document.getElementById('receiptImagePreview').src = imageUrl;
        if(status === 'ditolak') {
            document.getElementById('receiptImagePreview').style.filter = "grayscale(100%)";
        } else {
            document.getElementById('receiptImagePreview').style.filter = "none";
        }
        var receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
        receiptModal.show();
    }

    // --- ACC LOGIC ---
    let accSubmitUrl = '';
    function openAccModal(kode, type, id, isCOD) {
        document.getElementById('accOrderId').innerText = '#' + kode;
        
        if(isCOD) {
            document.getElementById('accMessage').innerHTML = `Apakah pelanggan <strong class="text-primary">#${kode}</strong> sudah dikonfirmasi via WA dan valid? Status akan diubah menjadi ACC.`;
        } else {
            document.getElementById('accMessage').innerHTML = `Pastikan dana sudah masuk. Status pesanan <strong class="text-primary">#${kode}</strong> akan berubah menjadi LUNAS.`;
        }

        accSubmitUrl = `/admin/validasi/acc/${type}/${id}`;
        var accModal = new bootstrap.Modal(document.getElementById('accModal'));
        accModal.show();
    }

    function processAcc() {
        let form = document.getElementById('globalAccForm');
        form.action = accSubmitUrl;
        form.submit();
    }

    // --- REJECT LOGIC ---
    function openRejectModal(kode, type, id, isCOD) {
        document.getElementById('rejectOrderId').innerText = '#' + kode;
        document.getElementById('globalRejectForm').action = `/admin/validasi/tolak/${type}/${id}`;
        
        // Atur opsi alasan berdasarkan tipe bayar + MATIKAN YANG SEMBUNYI
        if(isCOD) {
            document.getElementById('rejectOptionsTF').style.display = 'none';
            document.getElementById('rejectOptionsCOD').style.display = 'flex';
            
            // Disable input TF biar form nggak nge-bug saat di-submit
            document.querySelectorAll('#rejectOptionsTF input').forEach(r => r.disabled = true);
            document.querySelectorAll('#rejectOptionsCOD input').forEach(r => r.disabled = false);
        } else {
            document.getElementById('rejectOptionsTF').style.display = 'flex';
            document.getElementById('rejectOptionsCOD').style.display = 'none';
            
            // Disable input COD biar form nggak nge-bug saat di-submit
            document.querySelectorAll('#rejectOptionsCOD input').forEach(r => r.disabled = true);
            document.querySelectorAll('#rejectOptionsTF input').forEach(r => r.disabled = false);
        }
        
        // Reset Form
        let radios = document.querySelectorAll('input[name="alasan_penolakan"]');
        radios.forEach(r => r.checked = false);
        document.querySelector('.custom-textarea').value = '';
        
        var rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
        rejectModal.show();
    }
</script>
@endpush

@endsection 