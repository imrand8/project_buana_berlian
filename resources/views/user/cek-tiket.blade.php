<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Tiket & Riwayat - Buana Berlian</title>
    <link rel="icon" href="{{ asset('public/assets/img/LOGO.png') }}" type="image/png">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root { --p-color: #352877; --p-hover: #261c55; --accent-gold: #d4af37; --bg-color: #f4f6f9; --card-bg: #ffffff; --border-color: #dee2e6; --text-main: #333333; --text-muted: #6c757d; }
        [data-theme="dark"] { --bg-color: #121212; --card-bg: #1a1a1a; --border-color: #333333; --text-main: #ffffff; --text-muted: #aaaaaa; --p-color: #d4af37; --p-hover: #e5c158; }
        body { font-family: 'Poppins', sans-serif !important; background-color: var(--bg-color); color: var(--text-main); }
        .page-content-wrapper { padding-top: 100px; padding-bottom: 80px; min-height: 100vh; }
        .history-layout { max-width: 1100px; margin: 0 auto; padding: 0 5%; }
        .swal2-container { z-index: 999999 !important; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: none; } }
        .animate-up { animation: fadeInUp 0.5s ease-out forwards; opacity: 0; }
        .search-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 20px; padding: 40px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.03); margin-bottom: 40px; }
        .search-title { font-size: 1.8rem; font-weight: 800; color: var(--p-color); margin-bottom: 10px; }
        .search-desc { color: var(--text-muted); margin-bottom: 25px; font-size: 0.95rem; }
        .input-group-search { display: flex; gap: 15px; max-width: 600px; margin: 0 auto; }
        .input-search { flex: 1; padding: 12px 20px; border-radius: 12px; border: 2px solid var(--border-color); background-color: var(--bg-color); color: var(--text-main); outline: none; transition: 0.3s; font-size: 1rem; text-transform: uppercase;}
        .input-search:focus { border-color: var(--p-color); }
        .btn-lacak { background-color: var(--p-color); color: white; border: none; padding: 0 30px; border-radius: 12px; font-weight: 600; cursor: pointer; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .btn-lacak:hover { background-color: var(--p-hover); transform: translateY(-2px); }
        [data-theme="dark"] .btn-lacak { background-color: var(--accent-gold); color: black; }
        [data-theme="dark"] .btn-lacak:hover { background-color: #cda831; transform: translateY(-2px); }
        .search-error-msg { display: none; color: #dc3545; font-size: 0.9rem; font-weight: 700; margin-top: 15px; background: rgba(220, 53, 69, 0.1); padding: 10px; border-radius: 8px; max-width: 600px; margin-left: auto; margin-right: auto; border: 1px solid rgba(220, 53, 69, 0.3); }
        .ticket-wrapper { margin-bottom: 30px; box-shadow: 0 15px 40px rgba(0,0,0,0.08); border-radius: 20px; overflow: hidden; }
        .ticket-card { background-color: var(--card-bg); position: relative; }
        .ticket-card::before, .ticket-card::after { content: ''; position: absolute; top: 50%; width: 30px; height: 30px; background-color: var(--bg-color); border-radius: 50%; transform: translateY(-50%); z-index: 2; border: 1px solid var(--border-color); }
        .ticket-card::before { left: -15px; border-right: none; } 
        .ticket-card::after { right: -15px; border-left: none; }
        .ticket-header { padding: 20px 30px; color: white; display: flex; justify-content: space-between; align-items: center; border-bottom: 3px solid #d4af37; }
        .header-travel { background-color: #352877 !important; }
        .header-cargo { background-color: #2e7d32 !important; }
        .ticket-logo-wrapper img { height: 45px; filter: brightness(0) invert(1); object-fit: contain; }
        .ticket-body { padding: 30px; display: grid; grid-template-columns: 2fr 1fr; gap: 30px; }
        .ticket-route { display: flex; align-items: center; gap: 20px; margin-bottom: 25px; }
        .city-code { font-size: 2rem; font-weight: 800; color: #352877; line-height: 1; }
        [data-theme="dark"] .city-code { color: var(--accent-gold); }
        .city-name { font-size: 0.9rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; font-weight: 600; }
        .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        .info-item label { display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.5px; } 
        .info-item span { font-size: 1.1rem; font-weight: 700; color: var(--text-main); }
        .status-badge-large { padding: 8px 15px; border-radius: 30px; font-size: 0.85rem; font-weight: 700; display: inline-block; margin-bottom: 15px; }
        .ticket-footer { border-top: 2px dashed var(--border-color); padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; background-color: rgba(0,0,0,0.02); }
        [data-theme="dark"] .ticket-footer { background-color: rgba(255,255,255,0.02); }
        .content-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 20px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); }
        .section-title { font-size: 1.5rem; font-weight: 800; color: var(--text-main); margin-bottom: 5px; }
        .filter-tabs { display: flex; gap: 25px; border-bottom: 2px solid var(--border-color); margin-bottom: 30px; overflow-x: auto; white-space: nowrap; padding-bottom: 2px; }
        .filter-tabs::-webkit-scrollbar { display: none; }
        .filter-tabs a { color: var(--text-muted); text-decoration: none; font-weight: 700; padding: 10px 5px; border-bottom: 3px solid transparent; transition: 0.3s; cursor: pointer; }
        .filter-tabs a.active, .filter-tabs a:hover { color: var(--p-color); border-bottom-color: var(--p-color); }
        .order-list { display: flex; flex-direction: column; gap: 15px; }
        .order-card { border: 1px solid var(--border-color); border-radius: 16px; padding: 25px; background: var(--card-bg); transition: 0.4s ease; }
        .order-card:hover { border-color: var(--p-color); box-shadow: 0 8px 25px rgba(0,0,0,0.05); }
        .order-card.is-cancelled { background: rgba(220, 53, 69, 0.02); opacity: 0.8; }
        .order-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px dashed var(--border-color); padding-bottom: 15px; margin-bottom: 15px; }
        .order-id { font-size: 0.9rem; color: var(--text-muted); font-weight: 600; display: flex; align-items: center; gap: 8px; }
        .order-id span { color: var(--text-main); font-weight: 800; }
        .badge-status { padding: 6px 12px; border-radius: 8px; font-size: 0.8rem; font-weight: 800; display: inline-flex; align-items: center; gap: 5px; }
        .badge-pending { background: rgba(255, 193, 7, 0.1); color: #d39e00; border: 1px solid rgba(255, 193, 7, 0.3); }
        .badge-success { background: rgba(40, 167, 69, 0.1); color: #28a745; border: 1px solid rgba(40, 167, 69, 0.3); }
        .badge-failed { background: rgba(220, 53, 69, 0.1); color: #dc3545; border: 1px solid rgba(220, 53, 69, 0.3); }
        .order-body { display: flex; justify-content: space-between; align-items: center; }
        .route-info { display: flex; align-items: center; gap: 20px; }
        .route-icon { width: 55px; height: 55px; background: rgba(53, 40, 119, 0.1); color: #352877; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; }
        [data-theme="dark"] .route-icon { background: rgba(212, 175, 55, 0.1); color: var(--accent-gold); }
        .route-icon.kargo-icon { background: rgba(46, 125, 50, 0.1); color: #2e7d32; }
        .route-text h4 { margin: 0 0 6px 0; font-size: 1.15rem; font-weight: 800; color: var(--text-main); }
        .route-text p { margin: 0; font-size: 0.9rem; color: var(--text-muted); display: flex; gap: 15px; flex-wrap: wrap; }
        .order-footer { text-align: right; }
        .order-price { font-size: 1.25rem; font-weight: 900; color: var(--text-main); margin-bottom: 10px; }
        .action-buttons { display: flex; gap: 8px; justify-content: flex-end; flex-wrap: wrap; }
        .btn-action { padding: 8px 16px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: 0.3s; display: inline-flex; align-items: center; gap: 8px; border: none; }
        .btn-primary { background: var(--p-color); color: white; }
        .btn-primary:hover { opacity: 0.9; }
        [data-theme="dark"] .btn-primary { background: var(--accent-gold); color: black; }
        .btn-outline { background: transparent; border: 1px solid var(--border-color); color: var(--text-main); }
        .btn-outline:hover { border-color: var(--p-color); color: var(--p-color); }
        [data-theme="dark"] .btn-outline:hover { border-color: var(--accent-gold); color: var(--accent-gold); }
        /* Hover Merah Solid (Batalkan) */
        .btn-danger-outline { background: transparent; border: 1px solid #dc3545; color: #dc3545; transition: 0.2s ease-in-out; }
        .btn-danger-outline:hover { background: #dc3545; color: #ffffff; }

        /* Hover Biru Solid (Ubah ke TF) */
        .btn-info-outline { background: transparent; border: 1px solid #118EEA; color: #118EEA; transition: 0.2s ease-in-out; }
        .btn-info-outline:hover { background: #118EEA; color: #ffffff; }

        /* --- SINKRONISASI MODAL LAYANAN --- */
        .form-control-custom { width: 100%; padding: 12px 15px; background: var(--bg-color); border: 1px solid var(--border-color); border-radius: 10px; color: var(--text-main); font-size: 1rem; font-weight: 600; outline: none; transition: 0.3s; }
        .form-control-custom:focus { border-color: var(--p-color); box-shadow: 0 0 0 3px rgba(72, 61, 139, 0.1); }
        [data-theme="dark"] .form-control-custom:focus { border-color: var(--accent-gold); box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1); }
        .payment-modal { display: none; position: fixed; z-index: 99999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); backdrop-filter: blur(8px); align-items: center; justify-content: center; padding: 20px; }
        .payment-content { background: var(--card-bg); width: 100%; max-width: 480px; padding: 35px; border-radius: 24px; border: 1px solid var(--border-color); animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); max-height: 90vh; overflow-y: auto;}
        @keyframes popIn { from {transform: scale(0.9); opacity: 0;} to {transform: scale(1); opacity: 1;} }
        .payment-option { border: 1px solid var(--border-color); border-radius: 12px; padding: 15px; margin-bottom: 12px; cursor: pointer; display: flex; align-items: center; gap: 15px; transition: 0.3s; background: var(--bg-color); }
        .payment-option:hover { transform: translateY(-2px); border-color: var(--p-color); background: rgba(72, 61, 139, 0.05); }
        [data-theme="dark"] .payment-option:hover { border-color: var(--accent-gold); background: rgba(212, 175, 55, 0.05); }
        .payment-detail-box { background: var(--bg-color); border: 2px dashed var(--border-color); border-radius: 16px; padding: 25px 20px; text-align: center; margin: 15px 0 25px; }
        .payment-detail-box.bsi-box { border-color: #00a39d; background: rgba(0, 163, 157, 0.05); }
        [data-theme="dark"] .payment-detail-box.bsi-box { background: rgba(0, 163, 157, 0.1); }
        .payment-detail-box.dana-box { border-color: #118EEA; background: rgba(17, 142, 234, 0.05); }
        [data-theme="dark"] .payment-detail-box.dana-box { background: rgba(17, 142, 234, 0.1); }
        .logo-wrapper { background: #ffffff; padding: 10px 20px; border-radius: 12px; display: inline-block; margin-bottom: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); border: 1px solid #eaeaea; }
        .logo-wrapper img { height: 28px; object-fit: contain; display: block; }
        .rek-number { font-size: 1.8rem; font-weight: 800; letter-spacing: 2px; margin: 0 0 5px; color: var(--text-main); }
        input[type="file"]::file-selector-button { margin-right: 15px; border: none; background: var(--p-color); padding: 8px 16px; border-radius: 6px; color: #fff; cursor: pointer; font-weight: 700; transition: 0.2s; font-size: 0.8rem; }
        [data-theme="dark"] input[type="file"]::file-selector-button { background: var(--accent-gold); color: black; }
        .btn-confirm { display: block; width: 100%; padding: 15px; border-radius: 12px; background: linear-gradient(135deg, var(--p-color), #2a2355); color: white; font-weight: 700; font-size: 1rem; border: none; cursor: pointer; margin-top: 20px; transition: 0.3s; box-shadow: 0 10px 20px rgba(72, 61, 139, 0.2); }
        .btn-confirm:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(72, 61, 139, 0.4); }
        [data-theme="dark"] .btn-confirm { background: var(--accent-gold); color: black; box-shadow: 0 10px 20px rgba(212, 175, 55, 0.2); }

        /* --- STYLING UNTUK KOTAK LOGIN PROMPT (CEK TIKET) --- */
        .empty-state-wrapper { display: flex; justify-content: center; align-items: center; min-height: 50vh; padding: 20px; }
        .locked-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 24px; padding: 50px 40px; text-align: center; max-width: 650px; width: 100%; box-shadow: 0 20px 40px rgba(0,0,0,0.05); position: relative; overflow: hidden; transition: 0.3s; margin: 0 auto; }
        [data-theme="dark"] .locked-card { box-shadow: 0 20px 40px rgba(0,0,0,0.3); }
        .locked-card::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 6px; background: linear-gradient(90deg, var(--p-color), var(--accent-gold)); }
        
        /* Ikon & Glow - DEFAULT LIGHT MODE (UNGU) */
        .icon-glow-wrapper { width: 110px; height: 110px; margin: 0 auto 25px; background: rgba(53, 40, 119, 0.1); border: 2px dashed rgba(53, 40, 119, 0.4); border-radius: 50%; display: flex; justify-content: center; align-items: center; position: relative; transition: 0.3s; }
        .icon-glow-wrapper i.main-icon { font-size: 3.5rem; color: var(--p-color); animation: floatIcon 3s ease-in-out infinite; transition: 0.3s; }
        @keyframes floatIcon { 0% { transform: translateY(0px); } 50% { transform: translateY(-5px); } 100% { transform: translateY(0px); } }
        .icon-badge { position: absolute; bottom: 0px; right: 0px; background: var(--card-bg); border-radius: 50%; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.1); transition: 0.3s; }
        .icon-badge i { font-size: 1.2rem; color: #dc3545; }
        
        .locked-title { font-size: 1.4rem; font-weight: 800; margin-bottom: 12px; color: var(--text-main); }
        .locked-desc { color: var(--text-muted); font-size: 0.95rem; line-height: 1.6; margin-bottom: 35px; }
        
        /* Tombol - DEFAULT LIGHT MODE (UNGU) */
        .btn-login-cta { background: var(--p-color); color: #ffffff; border: none; padding: 15px 40px; border-radius: 50px; font-weight: 800; font-size: 1.05rem; transition: all 0.3s; display: inline-flex; align-items: center; gap: 10px; text-decoration: none; box-shadow: 0 10px 20px rgba(53, 40, 119, 0.2); }
        .btn-login-cta:hover { transform: translateY(-3px); box-shadow: 0 15px 25px rgba(53, 40, 119, 0.4); color: #ffffff; }

        /* --- DARK MODE OVERRIDES (KUNING EMAS) --- */
        [data-theme="dark"] .icon-glow-wrapper { background: rgba(212, 175, 55, 0.1); border-color: rgba(212, 175, 55, 0.4); }
        [data-theme="dark"] .icon-glow-wrapper i.main-icon { color: var(--accent-gold); }
        [data-theme="dark"] .btn-login-cta { background: var(--accent-gold); color: #000000; box-shadow: 0 10px 20px rgba(212, 175, 55, 0.2); }
        [data-theme="dark"] .btn-login-cta:hover { box-shadow: 0 15px 25px rgba(212, 175, 55, 0.4); color: #000000; }

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

        [data-theme="dark"] .text-muted { color: var(--text-muted) !important; }
        
        @media print {
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            body { background-color: white !important; }
            body * { visibility: hidden; }
            #searchResultArea, #searchResultArea * { visibility: visible; }
            #hide-on-print, #hide-on-print * { display: none !important; }
            #searchResultArea { position: absolute; left: 0; top: 0; width: 100%; margin: 0; padding: 0; }
            .btn-action, header, footer, .search-card, #historyArea { display: none !important; }
            .ticket-card { box-shadow: none !important; border: 2px solid #ccc !important; page-break-inside: avoid; margin: 0 !important; }
            .header-travel { background-color: #352877 !important; color: white !important; }
            .header-cargo { background-color: #2e7d32 !important; color: white !important; }
            .ticket-header { display: flex !important; justify-content: space-between !important; align-items: center !important; }
        }
        @media screen and (max-width: 768px) { 
            .search-card, .content-card { padding: 25px 20px; }
            .input-group-search { flex-direction: column; } 
            .btn-lacak { width: 100%; justify-content: center; padding: 12px; margin-top: 5px; }
            .order-card { padding: 20px 15px; }
            .order-header { flex-direction: column; align-items: flex-start; gap: 10px; }
            .order-body { flex-direction: column; align-items: flex-start; gap: 15px; } 
            .route-info { align-items: flex-start; gap: 15px; width: 100%; }
            .route-text { width: 100%; }
            .route-text p { display: flex; flex-direction: column; gap: 5px; } 
            .order-footer { display: flex; flex-direction: column; width: 100%; align-items: flex-start; gap: 15px; margin-top: 15px; padding-top: 15px; border-top: 1px dashed var(--border-color); } 
            .order-price { font-size: 1.5rem; margin-bottom: 0; }
            .action-buttons { width: 100%; display: flex; flex-direction: column; gap: 10px; } 
            .btn-action { width: 100%; justify-content: center; margin: 0 !important; padding: 10px; }
            .ticket-header { flex-direction: row; gap: 15px; }
            .ticket-body { grid-template-columns: 1fr; gap: 25px; padding: 20px; } 
            .ticket-route { justify-content: space-between; } 
            .ticket-footer { flex-direction: column; gap: 15px; text-align: center; }
            .ticket-footer div { text-align: center !important; width: 100%; }
            .modal-box-custom { padding: 25px 20px; }
        }
    </style>
</head>
<body data-theme="light">

    @include('user.partials.header')

    <div class="page-content-wrapper">
        <div class="history-layout">
            
            @if(session('success'))
                <div class="alert alert-success animate-up mb-4" style="border-radius: 15px; font-weight: bold;">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                </div>
            @endif

            <div id="searchResultArea" style="display: none; margin-bottom: 40px;">
                <div id="hide-on-print" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px;">
                    <h4 style="font-weight: 800; margin: 0; color: var(--text-main);"><i class="bi bi-ticket-detailed"></i> Hasil Pencarian</h4>
                    <div style="display: flex; gap: 10px;">
                        <button class="btn-action btn-primary" onclick="window.print()" style="padding: 5px 12px; font-size: 0.8rem; border-radius: 8px;">
                            <i class="bi bi-printer"></i> Cetak PDF
                        </button>
                        <button class="btn-action btn-outline" style="padding: 5px 12px; font-size: 0.8rem;" onclick="tutupPencarian()">Tutup</button>
                    </div>
                </div>
                
                <div class="ticket-wrapper" id="wrapper-travel-result" style="display: none;">
                    <div class="ticket-card">
                        <div class="ticket-header header-travel">
                            <div>
                                <p style="margin:0; font-size:0.8rem; font-weight:700;"><i class="bi bi-bus-front"></i> TRAVEL REGULER</p>
                                <h4 id="res-tr-kode" style="margin:0; font-size:1.5rem; font-weight:700;">-</h4>
                            </div>
                            <div style="text-align: right;">
                                <div class="ticket-logo-wrapper"><img src="{{ asset('public/assets/img/LOGO.png') }}" alt="Logo"></div>
                            </div>
                        </div>
                        <div class="ticket-body">
                            <div>
                                <div class="ticket-route">
                                    <div style="text-align: center;"><div class="city-code" id="res-tr-asal">-</div><div class="city-name">ASAL</div></div>
                                    <i class="bi bi-arrow-right" style="font-size: 1.5rem; color: var(--text-muted);"></i>
                                    <div style="text-align: center;"><div class="city-code" id="res-tr-tujuan">-</div><div class="city-name">TUJUAN</div></div>
                                </div>
                                <div class="info-grid">
                                    <div class="info-item"><label>Jadwal</label><span id="res-tr-jadwal">-</span></div>
                                    <div class="info-item"><label>Armada</label><span id="res-tr-armada">-</span></div>
                                    <div class="info-item"><label>Penumpang</label><span id="res-tr-nama">-</span></div>
                                    <div class="info-item"><label>No. Kursi</label><span style="color: var(--p-color);" id="res-tr-kursi">-</span></div>
                                    <div class="info-item"><label>Nama Supir</label><span style="color: #28a745;" id="res-tr-supir">-</span></div>
                                </div>
                            </div>
                            <div style="border-left: 2px dashed var(--border-color); padding-left: 30px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                                <label style="font-size:0.8rem; color:var(--text-muted); margin-bottom:10px;">STATUS TIKET</label>
                                <i id="res-tr-icon" class="bi" style="font-size: 4rem; margin-bottom: 10px;"></i>
                                <div id="res-tr-badge" class="status-badge-large">-</div>
                            </div>
                        </div>
                        <div class="ticket-footer">
                            <div><label style="font-size: 0.75rem; color: var(--text-muted); display: block;">Metode Pembayaran</label><div style="font-weight: 700; color: var(--text-main);" id="res-tr-metode">-</div></div>
                            <div style="text-align: right;"><label style="font-size: 0.75rem; color: var(--text-muted); display: block;">Total Harga</label><div style="font-weight: 800; font-size: 1.5rem; color: var(--p-color);" id="res-tr-harga">-</div></div>
                        </div>
                    </div>
                </div>

                <div class="ticket-wrapper" id="wrapper-cargo-result" style="display: none;">
                    <div class="ticket-card">
                        <div class="ticket-header header-cargo">
                            <div>
                                <p style="margin:0; font-size:0.8rem; font-weight:700;"><i class="bi bi-box-seam-fill"></i> KARGO EKSPRES</p>
                                <h4 id="res-kg-kode" style="margin:0; font-size:1.5rem; font-weight:700;">-</h4>
                            </div>
                            <div style="text-align: right;">
                                <div class="ticket-logo-wrapper"><img src="{{ asset('public/assets/img/LOGO.png') }}" alt="Logo"></div>
                            </div>
                        </div>
                        <div class="ticket-body">
                            <div>
                                <div class="ticket-route">
                                    <div style="text-align: center;"><div class="city-code" id="res-kg-asal">-</div><div class="city-name">ASAL</div></div>
                                    <i class="bi bi-arrow-right" style="font-size: 1.5rem; color: var(--text-muted);"></i>
                                    <div style="text-align: center;"><div class="city-code" id="res-kg-tujuan">-</div><div class="city-name">TUJUAN</div></div>
                                </div>
                                <div class="info-grid">
                                    <div class="info-item"><label>Tgl Kirim</label><span id="res-kg-jadwal">-</span></div>
                                    <div class="info-item"><label>Jenis Barang</label><span id="res-kg-jenis">-</span></div>
                                    <div class="info-item"><label>Penerima</label><span id="res-kg-penerima">-</span></div>
                                    <div class="info-item"><label>Berat</label><span style="color: #d4af37;" id="res-kg-berat">-</span></div>
                                    <div class="info-item"><label>Kurir (Supir)</label><span style="color: #2e7d32;" id="res-kg-supir">-</span></div>
                                </div>
                            </div>
                            <div style="border-left: 2px dashed var(--border-color); padding-left: 30px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                                <label style="font-size:0.8rem; color:var(--text-muted); margin-bottom:10px;">STATUS PAKET</label>
                                <i id="res-kg-icon" class="bi" style="font-size: 4rem; margin-bottom: 10px;"></i>
                                <div id="res-kg-badge" class="status-badge-large">-</div>
                            </div>
                        </div>
                        <div class="ticket-footer">
                            <div><label style="font-size: 0.75rem; color: var(--text-muted); display: block;">Metode Pembayaran</label><div style="font-weight: 700; color: var(--text-main);" id="res-kg-metode">-</div></div>
                            <div style="text-align: right;"><label style="font-size: 0.75rem; color: var(--text-muted); display: block;">Total Ongkir</label><div style="font-weight: 800; font-size: 1.5rem; color: var(--p-color);" id="res-kg-harga">-</div></div>
                        </div>
                    </div>
                </div>
            </div>

            @auth
            <main id="historyArea" class="content-card">
                <h2 class="section-title">Riwayat & Tiket Saya</h2>
                
                <div class="filter-tabs">
                    <a class="filter-btn active" data-filter="all">Semua</a>
                    <a class="filter-btn" data-filter="pending">Butuh Tindakan</a>
                    <a class="filter-btn" data-filter="success">Lunas / Selesai</a>
                    <a class="filter-btn" data-filter="cancelled">Dibatalkan</a>
                </div>

                <div class="order-list">
                    @php
                        $semuaPesanan = collect($travels)->merge($kargos)->sortByDesc('created_at');
                    @endphp

                    @forelse($semuaPesanan as $pesanan)
                        @php
                            $isTravel = isset($pesanan->kode_booking);
                            $kode = $isTravel ? $pesanan->kode_booking : $pesanan->kode_resi;
                            $tipe = $isTravel ? 'travel' : 'kargo';
                            
                            // Map Status
                            $statusFilter = 'pending';
                            $badgeClass = 'badge-pending';
                            $iconClass = 'bi-hourglass-split';
                            $statusText = 'Menunggu Verifikasi';

                            if($pesanan->status_pesanan == 'lunas') {
                                $statusFilter = 'success';
                                $badgeClass = 'badge-success';
                                $iconClass = 'bi-check-circle-fill';
                                $statusText = 'Lunas';
                            } elseif($pesanan->status_pesanan == 'batal' || $pesanan->status_pesanan == 'ditolak') {
                                $statusFilter = 'cancelled';
                                $badgeClass = 'badge-failed';
                                $iconClass = 'bi-x-circle-fill';
                                $statusText = $pesanan->status_pesanan == 'batal' ? 'Dibatalkan' : 'Ditolak (Upload Ulang)';
                            }

                            // INJEKSI: LOGIKA RUTE ASLI (MENGHILANGKAN NAMA JALAN)
                            $kotaAsal = $isTravel ? explode(' (', $pesanan->titik_jemput)[0] : explode(' (', $pesanan->kota_asal)[0];
                            $kotaTujuan = $isTravel ? explode(' (', $pesanan->titik_antar)[0] : explode(' (', $pesanan->kota_tujuan)[0];
                            $tglBerangkat = $isTravel ? ($pesanan->jadwal->tanggal_berangkat ?? $pesanan->created_at) : $pesanan->tanggal_berangkat;
                        @endphp

                        <div class="order-card {{ $statusFilter == 'cancelled' ? 'is-cancelled' : '' }}" data-status="{{ $statusFilter }}">
                            <div class="order-header">
                                <div class="order-id">
                                    <i class="bi {{ $isTravel ? 'bi-receipt' : 'bi-box-seam' }}"></i> 
                                    <span>{{ $kode }}</span>
                                </div>
                                <div class="badge-status {{ $badgeClass }}"><i class="bi {{ $iconClass }}"></i> {{ $statusText }}</div>
                            </div>
                            
                            @if($pesanan->alasan_tolak && ($statusFilter == 'cancelled' || $pesanan->status_pesanan == 'ditolak'))
                                <div style="background: rgba(220, 53, 69, 0.05); border: 1px dashed #dc3545; border-radius: 12px; padding: 12px 15px; margin-bottom: 15px;">
                                    <span style="color: #dc3545; font-size: 0.85rem; font-weight: 700;"><i class="bi bi-exclamation-triangle-fill me-1"></i> Alasan Admin:</span>
                                    <p style="margin: 3px 0 0; font-size: 0.85rem; color: var(--text-main);">{{ $pesanan->alasan_tolak }}</p>
                                </div>
                            @endif

                            <div class="order-body">
                                <div class="route-info">
                                    <div class="route-icon {{ !$isTravel ? 'kargo-icon' : '' }}"><i class="bi {{ $isTravel ? 'bi-bus-front-fill' : 'bi-box-seam-fill' }}"></i></div>
                                <div class="route-text">
                                    <h4>{{ ucfirst($kotaAsal) }} <i class="bi bi-arrow-right" style="font-size: 0.9rem; margin: 0 5px;"></i> {{ ucfirst($kotaTujuan) }}</h4>
                                    <p>
                                        <span><i class="bi bi-calendar-event"></i> {{ \Carbon\Carbon::parse($tglBerangkat)->format('d M Y') }}</span> 
                                        <span><i class="bi bi-person-fill"></i> {{ $isTravel ? $pesanan->nama_penumpang : 'Penerima: '.$pesanan->nama_penerima }}</span>
                                    </p>
                                </div>
                                </div>
                                <div class="order-footer">
                                    <div class="order-price">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</div>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-primary" onclick="lihatResi('{{ $kode }}')"><i class="bi bi-ticket-perforated"></i> E-Ticket</button>
                                        
                                        {{-- 1. DETEKTOR WAKTU 30 MENIT --}}
                                        @php
                                            $isLewatBatas = false; // Default aman jika jadwal tidak ada
                                            if ($pesanan->jadwal) {
                                                $waktuBerangkat = \Carbon\Carbon::parse($pesanan->jadwal->tanggal_berangkat . ' ' . $pesanan->jadwal->jam_berangkat, 'Asia/Jakarta');
                                                $isLewatBatas = \Carbon\Carbon::now('Asia/Jakarta')->addMinutes(30)->greaterThanOrEqualTo($waktuBerangkat);
                                            }
                                        @endphp

                                        {{-- 2. TOMBOL UBAH KE TF --}}
                                        @if($pesanan->status_pesanan == 'menunggu_verifikasi' && in_array(strtoupper($pesanan->metode_bayar), ['CASH', 'COD']))
                                            @if(!$isLewatBatas)
                                                <button class="btn-action btn-info-outline" onclick="openPaymentModal('{{ $pesanan->id }}', '{{ $tipe }}', 'UBAH')"><i class="bi bi-arrow-left-right"></i> Ubah ke TF (BSI/DANA)</button>
                                            @endif
                                        @endif

                                        {{-- 3. TOMBOL BATALKAN / AJUKAN BATAL (DISEMBUNYIKAN JIKA SUDAH BATAL) --}}
                                        @if($statusFilter != 'cancelled')
                                            
                                            @if($statusFilter != 'success')
                                                <form action="{{ route('user.pesanan.batal', ['type' => $tipe, 'id' => $pesanan->id]) }}" method="POST" class="m-0" id="formBatal-{{$pesanan->id}}">
                                                    @csrf @method('DELETE')
                                                    <button type="button" class="btn-action btn-danger-outline" onclick="confirmBatal('{{$pesanan->id}}', '{{ strtoupper($pesanan->metode_bayar) }}', '{{ $kode }}', '{{ $isTravel ? $pesanan->nama_penumpang : $pesanan->nama_penerima }}', '{{ number_format($pesanan->total_harga, 0, ',', '.') }}')">Batalkan</button>
                                                </form>
                                            @else
                                                <button type="button" class="btn-action btn-danger-outline" onclick="batalLunas('{{ $kode }}', '{{ $isTravel ? $pesanan->nama_penumpang : $pesanan->nama_penerima }}')">Ajukan Batal</button>
                                            @endif
                                            
                                        @endif                                   

                                        {{-- 4. KONDISI JIKA DITOLAK (UPLOAD ULANG) --}}
                                        @if($pesanan->status_pesanan == 'ditolak')
                                            @if(in_array(strtoupper($pesanan->metode_bayar), ['CASH', 'COD']))
                                                <a href="https://wa.me/6281803444854?text=Halo%20Admin,%20pesanan%20saya%20dengan%20kode%20*{{$kode}}*%20ditolak.%20Boleh%20minta%20penjelasannya?" target="_blank" class="btn-action btn-outline" style="text-decoration:none;">
                                                    <i class="bi bi-whatsapp text-success"></i> Hubungi Admin
                                                </a>
                                            @else
                                                @if(!$isLewatBatas)
                                                    <button class="btn-action btn-primary" onclick="openPaymentModal('{{ $pesanan->id }}', '{{ $tipe }}', 'ULANG')"><i class="bi bi-cloud-upload"></i> Upload Ulang</button>
                                                @else
                                                    <div class="alert alert-danger p-2 m-0 mt-2 text-center" style="font-size: 0.75rem; width: 100%;">
                                                        Mohon maaf, batas waktu unggah bukti transfer telah habis (kurang dari 30 menit sebelum keberangkatan). Silakan lakukan pembayaran secara Tunai (Cash) kepada supir atau hubungi WA Admin.
                                                    </div>
                                                @endif
                                            @endif
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1"></i>
                            <p class="mt-2 fw-bold">Belum ada riwayat pesanan.</p>
                        </div>
                    @endforelse
                </div>
            </main>
            @else
            <main class="empty-state-wrapper">
                <div class="locked-card animate-up">
                    <div class="icon-glow-wrapper">
                        <i class="bi bi-ticket-perforated-fill main-icon"></i>
                        <div class="icon-badge"><i class="bi bi-lock-fill"></i></div>
                    </div>
                    <h3 class="locked-title">Akses Tiket & Pesanan Anda</h3>
                    <p class="locked-desc">Silakan masuk ke akun Anda untuk mengecek e-ticket, melacak kargo, atau mulai merencanakan perjalanan baru bersama Buana Berlian.</p>
                    <a href="{{ route('login') }}" class="btn-login-cta">
                        Masuk ke Akun <i class="bi bi-box-arrow-in-right"></i>
                    </a>
                </div>
            </main>
            @endauth

        </div>
    </div>

    <div id="paymentModal" class="payment-modal">
        <form action="{{ route('user.pesanan.upload') }}" method="POST" enctype="multipart/form-data" class="payment-content">
            @csrf
            <input type="hidden" name="pesanan_id" id="modal_pesanan_id">
            <input type="hidden" name="tipe_pesanan" id="modal_tipe_pesanan">
            <input type="hidden" name="metode_bayar" id="modal_metode_bayar">

            <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 class="modal-title" style="margin: 0; font-size: 1.2rem; color: var(--text-main); font-weight: 800;">Upload Bukti Baru</h3>
                <button type="button" onclick="closePaymentModal()" style="background: none; border: none; font-size: 1.5rem; color: var(--text-muted); cursor: pointer;">&times;</button>
            </div>
            
            <div id="step-method">
                <div class="payment-option" onclick="selectPayment('bsi')">
                    <div style="background: rgba(0, 163, 157, 0.1); padding: 10px; border-radius: 10px;"><i class="bi bi-bank2" style="font-size: 1.3rem; color: #00a39d;"></i></div>
                    <div style="text-align: left;">
                        <h5 style="margin: 0; font-size: 0.95rem; font-weight: 700; color: var(--text-main);">Transfer Bank BSI</h5>
                        <small style="color: var(--text-muted); font-size: 0.8rem;">Cek Manual via Admin</small>
                    </div>
                    <i class="bi bi-chevron-right" style="margin-left: auto; color: var(--text-muted);"></i>
                </div>

                <div class="payment-option" onclick="selectPayment('dana')">
                    <div style="background: rgba(17, 142, 234, 0.1); padding: 10px; border-radius: 10px;"><i class="bi bi-wallet2" style="font-size: 1.3rem; color: #118EEA;"></i></div>
                    <div style="text-align: left;">
                        <h5 style="margin: 0; font-size: 0.95rem; font-weight: 700; color: var(--text-main);">E-Wallet DANA</h5>
                        <small style="color: var(--text-muted); font-size: 0.8rem;">Transfer ke DANA Owner</small>
                    </div>
                    <i class="bi bi-chevron-right" style="margin-left: auto; color: var(--text-muted);"></i>
                </div>
            </div>

            <div id="step-bsi" style="display: none; animation: popIn 0.3s;">
                <p style="color: var(--text-main); font-size: 0.9rem; text-align: center; margin-bottom: 5px;">Transfer ke rekening BSI:</p>
                <div class="payment-detail-box bsi-box">
                    <div class="logo-wrapper"><img src="https://upload.wikimedia.org/wikipedia/commons/a/a0/Bank_Syariah_Indonesia.svg" alt="Logo BSI"></div>
                    <h2 class="rek-number">7095570188</h2>
                    <p style="margin: 5px 0 15px; font-size: 0.9rem; color: var(--text-muted); font-weight: 600;">a.n Malta A QQ Hanum Floreta</p>
                    <button type="button" onclick="navigator.clipboard.writeText('7095570188'); Swal.fire({toast:true, position:'top-end', icon:'success', title:'Disalin!', showConfirmButton:false, timer:1500});" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 6px 18px; border-radius: 20px; font-size: 0.8rem; color: var(--text-main); cursor: pointer; transition: 0.3s; font-weight: 600;"><i class="bi bi-clipboard"></i> Salin Nomor</button>
                </div>
                <div style="text-align: left; margin-bottom: 15px;">
                    <label style="margin-bottom: 8px; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Upload Bukti Transfer BSI <span style="color:red">*</span></label>
                    <input type="file" id="proof-bsi" name="bukti_transfer" class="form-control-custom" style="padding: 10px; height: auto;" disabled required>
                </div>
                <div style="margin-top: 20px;">
                    <button type="button" class="btn-confirm" style="background: linear-gradient(135deg, #00a39d, #007c77);" onclick="finishUpload('BSI')"><i class="bi bi-send-fill"></i> Kirim Bukti Baru</button>
                    <button type="button" onclick="resetPaymentModal()" style="width: 100%; margin-top: 10px; padding: 10px; background: none; border: none; color: var(--text-muted); cursor: pointer; font-weight: 600; font-size: 0.9rem;">Kembali Menu Awal</button>
                </div>
            </div>

            <div id="step-dana" style="display: none; animation: popIn 0.3s;">
                <p style="color: var(--text-main); font-size: 0.9rem; text-align: center; margin-bottom: 5px;">Kirim saldo ke nomor DANA berikut:</p>
                <div class="payment-detail-box dana-box">
                    <div class="logo-wrapper"><img src="https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_dana_blue.svg" alt="Logo DANA"></div>
                    <h2 class="rek-number">082142303088</h2> <p style="margin: 5px 0 15px; font-size: 0.9rem; color: var(--text-muted); font-weight: 600;">a.n Malta Anantyasari</p>
                    <button type="button" onclick="navigator.clipboard.writeText('082142303088'); Swal.fire({toast:true, position:'top-end', icon:'success', title:'Disalin!', showConfirmButton:false, timer:1500});" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 6px 18px; border-radius: 20px; font-size: 0.8rem; color: var(--text-main); cursor: pointer; transition: 0.3s; font-weight: 600;"><i class="bi bi-clipboard"></i> Salin Nomor DANA</button>
                </div>
                <div style="text-align: left; margin-bottom: 15px;">
                    <label style="margin-bottom: 8px; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Upload Bukti Transfer DANA <span style="color:red">*</span></label>
                    <input type="file" id="proof-dana" name="bukti_transfer" class="form-control-custom" style="padding: 10px; height: auto;" disabled required>
                </div>
                <div style="margin-top: 20px;">
                    <button type="button" class="btn-confirm" style="background: linear-gradient(135deg, #118EEA, #0a65a8);" onclick="finishUpload('DANA')"><i class="bi bi-send-fill"></i> Kirim Bukti Baru</button>
                    <button type="button" onclick="resetPaymentModal()" style="width: 100%; margin-top: 10px; padding: 10px; background: none; border: none; color: var(--text-muted); cursor: pointer; font-weight: 600; font-size: 0.9rem;">Kembali Menu Awal</button>
                </div>
            </div>
        </form>
    </div>

    @include('user.partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // --- 1. FILTER TABS ---
        const filterBtns = document.querySelectorAll('.filter-btn');
        const orderCards = document.querySelectorAll('.order-card');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const filterValue = this.getAttribute('data-filter');

                orderCards.forEach(card => {
                    if (filterValue === 'all' || card.getAttribute('data-status') === filterValue) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // --- 2. LOGIKA SEARCH E-TICKET DINAMIS ---
        const travels = @json($travels ?? []);
        const kargos = @json($kargos ?? []);
        const semuaData = [...travels, ...kargos];

        function lihatResi(kode) { 
            window.scrollTo({ top: 0, behavior: 'smooth' }); 
            cariBooking(kode); // Langsung lempar kodenya tanpa lewat kotak input
        }
        
        function cariBooking(kodeInput) {
            // Cari di array data
            let tiket = semuaData.find(item => (item.kode_booking === kodeInput || item.kode_resi === kodeInput));
            if(!tiket) return; // Kalau nggak ketemu, stop aja (meski mustahil karena diklik dari riwayat)

            const singkatanKota = {
                'pacitan': 'PCT',
                'malang': 'MLG',      
                'blitar': 'BLT',
                'trenggalek': 'TRG',  
                'tulungagung': 'TAG'  
            };
            
            const isTravel = kodeInput.includes('TRV');
            document.getElementById('historyArea').style.display = 'none';
            document.getElementById('searchResultArea').style.display = 'block';
            document.getElementById('wrapper-travel-result').style.display = 'none';
            document.getElementById('wrapper-cargo-result').style.display = 'none';

            let icon = 'bi-hourglass-split'; let color = '#d39e00'; let bg = '#fff3cd'; let text = 'Menunggu Verifikasi';
            if(tiket.status_pesanan === 'lunas') { icon = 'bi-check-circle-fill'; color = '#28a745'; bg = '#d4edda'; text = 'Lunas / Sukses'; }
            if(tiket.status_pesanan === 'ditolak' || tiket.status_pesanan === 'batal') { icon = 'bi-x-circle-fill'; color = '#dc3545'; bg = '#f8d7da'; text = tiket.status_pesanan.toUpperCase(); }

            if(isTravel) {
                let asalAsli = tiket.titik_jemput.split(' (')[0];
                let tujuanAsli = tiket.titik_antar.split(' (')[0];
                let tgl = (tiket.jadwal) ? tiket.jadwal.tanggal_berangkat : 'Menunggu Update';

                document.getElementById('wrapper-travel-result').style.display = 'block';
                document.getElementById('res-tr-kode').innerText = tiket.kode_booking;
                document.getElementById('res-tr-asal').innerText = singkatanKota[asalAsli.toLowerCase()] || asalAsli.substring(0,3).toUpperCase();
                document.getElementById('res-tr-tujuan').innerText = singkatanKota[tujuanAsli.toLowerCase()] || tujuanAsli.substring(0,3).toUpperCase();
                document.getElementById('res-tr-jadwal').innerText = tgl;
                document.getElementById('res-tr-armada').innerText = (tiket.jadwal && tiket.jadwal.armada) ? tiket.jadwal.armada.nama_armada : 'Menunggu Update';
                document.getElementById('res-tr-nama').innerText = tiket.nama_penumpang;
                document.getElementById('res-tr-kursi').innerText = tiket.nomor_kursi;
                
                if (tiket.jadwal && tiket.jadwal.driver) {
                    let waSupir = tiket.jadwal.driver.no_hp.replace(/^0/, '62');
                    document.getElementById('res-tr-supir').innerHTML = `${tiket.jadwal.driver.nama_supir} <br><a href="https://wa.me/${waSupir}" target="_blank" class="badge bg-success text-white text-decoration-none mt-1 d-inline-block" style="font-size: 0.75rem; padding: 5px 10px;"><i class="bi bi-whatsapp"></i> Hubungi Supir</a>`;
                } else {
                    document.getElementById('res-tr-supir').innerText = 'Menunggu Admin';
                    document.getElementById('res-tr-supir').style.color = 'var(--text-muted)';
                }

                document.getElementById('res-tr-metode').innerText = tiket.metode_bayar.toUpperCase();
                document.getElementById('res-tr-harga').innerText = 'Rp ' + Number(tiket.total_harga).toLocaleString('id-ID');
                
                document.getElementById('res-tr-icon').className = `bi ${icon}`; document.getElementById('res-tr-icon').style.color = color;
                document.getElementById('res-tr-badge').innerText = text; document.getElementById('res-tr-badge').style.background = bg; document.getElementById('res-tr-badge').style.color = color;
            } else {
                let asalAsli = tiket.kota_asal.split(' (')[0];
                let tujuanAsli = tiket.kota_tujuan.split(' (')[0];

                document.getElementById('wrapper-cargo-result').style.display = 'block';
                document.getElementById('res-kg-kode').innerText = tiket.kode_resi;
                document.getElementById('res-kg-asal').innerText = singkatanKota[asalAsli.toLowerCase()] || asalAsli.substring(0,3).toUpperCase();
                document.getElementById('res-kg-tujuan').innerText = singkatanKota[tujuanAsli.toLowerCase()] || tujuanAsli.substring(0,3).toUpperCase();
                document.getElementById('res-kg-jadwal').innerText = tiket.tanggal_berangkat;
                document.getElementById('res-kg-jenis').innerText = tiket.keterangan_barang;
                document.getElementById('res-kg-penerima').innerText = tiket.nama_penerima;
                document.getElementById('res-kg-berat').innerText = tiket.berat_barang + ' Kg';

                if (tiket.jadwal && tiket.jadwal.driver) {
                    let waSupirKg = tiket.jadwal.driver.no_hp.replace(/^0/, '62');
                    document.getElementById('res-kg-supir').innerHTML = `${tiket.jadwal.driver.nama_supir} <br><a href="https://wa.me/${waSupirKg}" target="_blank" class="badge bg-success text-white text-decoration-none mt-1 d-inline-block" style="font-size: 0.75rem; padding: 5px 10px;"><i class="bi bi-whatsapp"></i> Hubungi Supir</a>`;
                }else {
                    document.getElementById('res-kg-supir').innerText = 'Menunggu Admin';
                    document.getElementById('res-kg-supir').style.color = 'var(--text-muted)';
                }

                document.getElementById('res-kg-metode').innerText = tiket.metode_bayar.toUpperCase();
                document.getElementById('res-kg-harga').innerText = 'Rp ' + Number(tiket.total_harga).toLocaleString('id-ID');
                
                document.getElementById('res-kg-icon').className = `bi ${icon}`; document.getElementById('res-kg-icon').style.color = color;
                document.getElementById('res-kg-badge').innerText = text; document.getElementById('res-kg-badge').style.background = bg; document.getElementById('res-kg-badge').style.color = color;
            }
        }

        function tutupPencarian() {
            document.getElementById('searchResultArea').style.display = 'none';
            if(document.getElementById('historyArea')) document.getElementById('historyArea').style.display = 'block';
        }

        // --- 3. MODAL UPLOAD BUKTI (UBAH/ULANG) ---
        const modalPayment = document.getElementById('paymentModal');
        
        function openPaymentModal(id, type, mode) { 
            document.getElementById('modal_pesanan_id').value = id;
            document.getElementById('modal_tipe_pesanan').value = type;
            resetPaymentModal(); 
                        
            const judulModal = document.querySelector('.modal-title');
            if(mode === 'UBAH') {
                judulModal.innerText = 'Ubah Metode ke Transfer';
            } else {
                judulModal.innerText = 'Upload Ulang Bukti TF';
            }
            
            modalPayment.style.display = 'flex'; 
        }
        function closePaymentModal() { modalPayment.style.display = 'none'; }
        function resetPaymentModal() {
            document.getElementById('step-method').style.display = 'block';
            document.getElementById('step-bsi').style.display = 'none';
            document.getElementById('step-dana').style.display = 'none';
            document.getElementById('proof-bsi').value = '';
            document.getElementById('proof-dana').value = '';
            document.getElementById('proof-bsi').disabled = true;
            document.getElementById('proof-dana').disabled = true;
        }
        function selectPayment(bank) {
            document.getElementById('step-method').style.display = 'none';
            document.getElementById('modal_metode_bayar').value = bank;
            
            if (bank === 'bsi') {
                document.getElementById('step-bsi').style.display = 'block';
                document.getElementById('proof-bsi').disabled = false;
            } else {
                document.getElementById('step-dana').style.display = 'block';
                document.getElementById('proof-dana').disabled = false;
            }
        }
        function updateFileName(input) {
            if(input.files && input.files.length > 0) {
                document.getElementById('displayNamaFoto').innerText = input.files[0].name;
            }
        }
        function finishUpload(bank) {
            let inputId = bank === 'BSI' ? 'proof-bsi' : 'proof-dana';
            if (document.getElementById(inputId).files.length === 0) { 
                Swal.fire({ icon: 'warning', title: 'Bukti Kosong', text: `Silakan pilih foto struk ${bank}.` }); 
                return; 
            }
            
            Swal.fire({ title: 'Mengirim Bukti...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
            document.getElementById('modal_metode_bayar').value = bank; 
            document.querySelector('.payment-content').submit();
        }

        // --- 4. SWEET ALERT BATALKAN (LOGIKA TRANSFER VS CASH) ---
        function confirmBatal(id, metodeBayar, kode, nama, nominal) {
            const isDark = document.body.getAttribute('data-theme') === 'dark';
            const isCash = ['CASH', 'COD'].includes(metodeBayar);

            if (!isCash) {
                // LOGIKA TRANSFER: Arahkan ke WA Admin untuk Refund
                Swal.fire({
                    title: 'Batalkan & Refund?',
                    text: "Pembatalan tiket via Transfer bank harus menghubungi Admin agar dana Anda dapat dikembalikan (Refund).",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#25D366', // Warna Hijau WA
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="bi bi-whatsapp"></i> Chat Admin',
                    cancelButtonText: 'Kembali',
                    background: isDark ? '#1a1a1a' : '#ffffff',
                    color: isDark ? '#ffffff' : '#333333'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let textWa = `Halo Admin, saya ingin membatalkan pesanan dengan rincian:%0A%0A*Kode:* ${kode}%0A*Atas Nama:* ${nama}%0A*Nominal:* Rp ${nominal}%0A*Metode:* ${metodeBayar}%0A%0AMohon panduannya untuk proses pembatalan dan pengembalian dana (refund). Terima kasih.`;
                        window.open(`https://wa.me/6281803444854?text=${textWa}`, '_blank');
                    }
                });
            } else {
                // LOGIKA CASH: Langsung hanguskan di sistem
                Swal.fire({
                    title: 'Yakin Batalkan?',
                    text: "Pesanan CASH yang dibatalkan tidak bisa dikembalikan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Batalkan!',
                    background: isDark ? '#1a1a1a' : '#ffffff',
                    color: isDark ? '#ffffff' : '#333333'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('formBatal-'+id).submit();
                    }
                });
            }
        }

        // --- 5. SWEET ALERT BATALKAN UNTUK TIKET LUNAS ---
        function batalLunas(kode, nama) {
            const isDark = document.body.getAttribute('data-theme') === 'dark';
            
            Swal.fire({
                title: 'Ajukan Pembatalan?',
                text: "Tiket ini sudah LUNAS. Pembatalan harus dilakukan melalui Admin karena terkait ketersediaan kursi dan kebijakan Refund.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#25D366', // Warna Hijau WA
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-whatsapp"></i> Chat Admin',
                cancelButtonText: 'Tutup',
                background: isDark ? '#1a1a1a' : '#ffffff',
                color: isDark ? '#ffffff' : '#333333'
            }).then((result) => {
                if (result.isConfirmed) {
                    let textWa = `Halo Admin, saya ingin mengajukan pembatalan untuk tiket yang sudah Lunas dengan rincian:%0A%0A*Kode:* ${kode}%0A*Atas Nama:* ${nama}%0A%0AMohon info prosedur pembatalan dan kebijakan pengembalian dananya (Refund). Terima kasih.`;
                    window.open(`https://wa.me/6281803444854?text=${textWa}`, '_blank');
                }
            });
        }
    </script>
</body>
</html>