<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- 1. FAVICON (Ini yang bikin logo bagus & kotak di pencarian Google & Tab Browser) -->
    <link rel="icon" href="{{ asset('public/assets/img/favicon.png') }}" type="image/png">

    <!-- 2. SEO DASAR GOOGLE -->
    <title>Buana Berlian - Travel Reguler & Kargo Malang Pacitan</title>
    <meta name="description" content="Layanan travel eksekutif dan kargo ekspres rute Malang - Pacitan PP. Pesan tiket online sekarang untuk perjalanan nyaman dan aman bersama Buana Berlian Tour & Travel.">
    <meta name="keywords" content="travel malang pacitan, travel pacitan malang, kargo malang pacitan, travel buana berlian, tiket travel online, travel eksekutif pacitan">
    <meta name="author" content="Buana Berlian Tour & Travel">
    <meta name="robots" content="index, follow">

    <!-- 3. OPEN GRAPH (Preview Banner Lebar untuk WhatsApp/Sosmed) -->
    <meta property="og:title" content="Buana Berlian - Travel Malang Pacitan">
    <meta property="og:description" content="Pesan tiket travel eksekutif dan kargo rute Malang - Pacitan PP secara online. Cepat, aman, dan nyaman!">
    <meta property="og:image" content="{{ asset('public/assets/img/LOGO.png') }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:type" content="website">
    
    <meta name="google-site-verification" content="XdUheWTDnH1joHtbHVJlyANCOwF8_YlAfEpDZSLdFMk" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* =========================================
           GLOBAL DARK MODE & TYPOGRAPHY
           ========================================= */
        body {
            font-family: 'Poppins', sans-serif !important; 
            background-color: #fdfdfd;
            color: #333333;
            transition: background-color 0.4s ease, color 0.4s ease;
            overflow-x: hidden;
        }

        body[data-theme="dark"] {
            background-color: #121212;
            color: #e0e0e0;
        }

        body[data-theme="dark"] h1, 
        body[data-theme="dark"] h2, 
        body[data-theme="dark"] h3,
        body[data-theme="dark"] h4 {
            color: #ffffff;
        }

        body[data-theme="dark"] #layanan-utama {
            background-color: #1a1a1a !important;
        }

        /* --- ANIMASI --- */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .animate-up { animation: fadeInUp 0.8s cubic-bezier(0.165, 0.84, 0.44, 1) forwards; opacity: 0; }
        .delay-1 { animation-delay: 0.2s; }
        .delay-2 { animation-delay: 0.4s; }
        .delay-3 { animation-delay: 0.6s; }

        /* --- HERO SLIDER --- */
        .hero { position: relative; height: 85vh; border-radius: 0 0 30px 30px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        .hero-slide { background-size: cover; background-position: center; display: flex; align-items: center; justify-content: center; position: relative; }
        .hero-content { position: relative; z-index: 2; text-align: center; color: white; padding: 0 20px; max-width: 800px; text-shadow: 0 2px 10px rgba(0,0,0,0.5); }
        .hero-content h1 { font-size: 3.5rem; font-weight: 800; margin-bottom: 15px; letter-spacing: -1px; }
        .hero-content p { font-size: 1.2rem; font-weight: 500; opacity: 0.9; }

        /* --- SEARCH BOX --- */
        .search-section { margin-top: -60px; padding: 0 20px; position: relative; z-index: 10; margin-bottom: 80px; }
        .search-container {
            background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);
            border-radius: 20px; padding: 30px; box-shadow: 0 20px 50px rgba(0,0,0,0.1);
            max-width: 1000px; margin: 0 auto;
            display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr; gap: 20px; align-items: end;
            border: 1px solid rgba(255,255,255,0.2);
        }
        [data-theme="dark"] .search-container { background: rgba(30, 30, 30, 0.95); border-color: #444; }

        .search-group label { display: block; font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px; }
        .custom-input, .custom-select { width: 100%; border: none; background: transparent; border-bottom: 2px solid var(--border-color); padding: 10px 0; font-weight: 700; font-size: 1.1rem; color: var(--text-main); outline: none; transition: 0.3s; }
        .custom-input:focus, .custom-select:focus { border-color: var(--p-color); }
        [data-theme="dark"] .custom-select option { background: #222; color: white; }
        
        .btn-search { width: 100%; padding: 15px; border-radius: 12px; border: none; background: linear-gradient(135deg, var(--p-color), #2a2355); color: white; font-weight: 700; font-size: 1rem; cursor: pointer; transition: 0.3s; box-shadow: 0 10px 20px rgba(72, 61, 139, 0.2); }
        .btn-search:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(72, 61, 139, 0.3); }
        [data-theme="dark"] .btn-search { background: var(--accent-gold); color: black; box-shadow: 0 10px 20px rgba(212, 175, 55, 0.2); }

        /* --- SECTION HEADER --- */
        .section-header { text-align: center; margin-bottom: 50px; }
        .section-tag { color: var(--p-color); font-weight: 800; text-transform: uppercase; letter-spacing: 2px; font-size: 0.8rem; display: inline-block; padding: 6px 16px; background: rgba(72, 61, 139, 0.08); border-radius: 50px; margin-bottom: 15px; }
        [data-theme="dark"] .section-tag { background: rgba(212, 175, 55, 0.15); color: var(--accent-gold); }
        .section-title { font-size: 2.2rem; font-weight: 800; color: var(--text-main); margin: 0; }

        /* --- GRID SYSTEM --- */
        .service-grid-4 {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .ticket-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; max-width: 1200px; margin: 0 auto;
        }

        /* --- CARD STYLES --- */
        .unified-card { 
            background-color: var(--card-bg); border: 1px solid var(--border-color); border-radius: 24px; padding: 30px; 
            position: relative; transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s ease, border-color 0.4s ease, background-color 0.4s ease; 
            overflow: visible; height: 100%; display: flex; flex-direction: column; 
        }
        
        .unified-card:hover { transform: translateY(-12px); box-shadow: 0 25px 50px rgba(0,0,0,0.12); border-color: var(--p-color); }
        [data-theme="dark"] .unified-card { background-color: #1e1e1e; border-color: #333333; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2); }
        [data-theme="dark"] .unified-card:hover { border-color: var(--accent-gold); box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5); transform: translateY(-12px); }

        .icon-circle { width: 70px; height: 70px; background: var(--bg-color); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: var(--p-color); margin-bottom: 20px; box-shadow: 0 10px 20px rgba(0,0,0,0.05); transition: 0.3s; }
        .unified-card:hover .icon-circle { background: var(--p-color); color: white; transform: scale(1.1) rotate(-5deg); }
        [data-theme="dark"] .unified-card:hover .icon-circle { background: var(--accent-gold); color: black; }

        .card-image-box { height: 180px; width: 100%; border-radius: 16px; overflow: hidden; margin-bottom: 20px; position: relative; }
        .card-image-box img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
        .unified-card:hover .card-image-box img { transform: scale(1.1); }
        .card-badge { position: absolute; top: 10px; right: 10px; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 800; color: black; background: var(--accent-gold); z-index: 2; box-shadow: 0 5px 10px rgba(0,0,0,0.2); }

        .btn-detail { margin-top: auto; width: 100%; padding: 12px; border-radius: 12px; border: 1px solid var(--border-color); background: transparent; color: var(--text-main); font-weight: 700; cursor: pointer; transition: 0.3s; text-align: center; display: block; text-decoration: none; }
        .unified-card:hover .btn-detail { background: var(--p-color); color: white; border-color: var(--p-color); }
        [data-theme="dark"] .unified-card:hover .btn-detail { background: var(--accent-gold); color: black; border-color: var(--accent-gold); }

        /* --- MODAL KARGO --- */
        .modal-overlay { display:none; position:fixed; z-index:10002; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.85); backdrop-filter: blur(8px); align-items:center; justify-content:center; padding: 20px; }
        .modal-box { background:var(--card-bg); padding:35px; border-radius:24px; width:100%; max-width:450px; border: 1px solid var(--border-color); box-shadow: 0 25px 50px rgba(0,0,0,0.5); text-align: center; animation: popIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        @keyframes popIn { from {transform: scale(0.8); opacity: 0;} to {transform: scale(1); opacity: 1;} }

        /* --- MOBILE RESPONSIVE --- */
        @media (max-width: 1024px) {
            .search-container { grid-template-columns: 1fr 1fr; }
            .ticket-grid { grid-template-columns: repeat(2, 1fr); }
            .service-grid-4 { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .hero { height: 70vh; } 
            .hero-content h1 { font-size: 2.2rem; }
            .hero-content p { font-size: 1rem; }
            .search-section { margin-top: -40px; margin-bottom: 60px; }
            .search-container { grid-template-columns: 1fr; gap: 20px; padding: 25px; }
            .ticket-grid { grid-template-columns: 1fr; gap: 20px; }
            .service-grid-4 { grid-template-columns: 1fr; gap: 20px; }
            .section-title { font-size: 1.8rem; }
        }
    </style>
</head>
<body data-theme="light">

    @include('user.partials.header')

    <main>
        <header class="swiper hero">
            <div class="swiper-wrapper">
                <div class="swiper-slide hero-slide" style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.6)), url('{{ asset('public/assets/img/perjalanan.jpg') }}');">
                    <div class="hero-content animate-up">
                        <h1>Perjalanan Nyaman, Hati Tenang</h1>
                        <p>Layanan travel reguler terbaik rute Pacitan - Malang dengan armada eksekutif.</p>
                    </div>
                </div>
                <div class="swiper-slide hero-slide" style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.6)), url('{{ asset('public/assets/img/antar.jpg') }}');">
                    <div class="hero-content">
                        <h1>Antar Jemput Depan Rumah</h1>
                        <p>Nikmati kemudahan layanan door-to-door. Kami jemput dan antar sampai tujuan.</p>
                    </div>
                </div>
                <div class="swiper-slide hero-slide" style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.6)), url('{{ asset('public/assets/img/tujuan.webp') }}');">
                    <div class="hero-content">
                        <h1>Tiba di Tujuan Tepat Waktu</h1>
                        <p>Menghubungkan Pacitan, Malang, Blitar, dan Tulungagung setiap hari.</p>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </header>

        <section class="search-section animate-up delay-1">
            <form action="{{ route('layanan.index') }}" method="GET" class="search-container">
                <div class="search-group">
                    <label><i class="bi bi-geo-alt-fill"></i> Rute</label>
                    <select class="custom-select" onchange="document.getElementById('homeAsal').value = this.value.split('-')[0]; document.getElementById('homeTujuan').value = this.value.split('-')[1];">
                        <option value="Pacitan-Malang">Pacitan ➝ Malang</option>
                        <option value="Malang-Pacitan">Malang ➝ Pacitan</option>
                    </select>
                    <input type="hidden" name="kota_asal" id="homeAsal" value="Pacitan">
                    <input type="hidden" name="kota_tujuan" id="homeTujuan" value="Malang">
                </div>
                <div class="search-group">
                    <label><i class="bi bi-calendar-event"></i> Tanggal</label>
                    <input type="date" id="dateInput" name="tanggal" required class="custom-input">
                </div>
                <div class="search-group">
                    <label><i class="bi bi-people-fill"></i> Kursi</label>
                    <select name="kursi" class="custom-select">
                        <option value="1">1 Orang</option>
                        <option value="2">2 Orang</option>
                        <option value="3">3 Orang</option>
                        <option value="4">4 Orang</option>
                    </select>
                </div>
                <button type="submit" class="btn-search">
                    <i class="bi bi-search"></i> Cari Tiket
                </button>
            </form>
        </section>

        <section style="padding: 0 5% 80px; background-color: var(--bg-color);">
            <div class="section-header animate-up delay-1" style="padding-top: 20px;">
                <span class="section-tag">Solusi Mobilitas</span>
                <h2 class="section-title">Layanan Kami</h2>
                <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 10px;">Berbagai solusi perjalanan dan pengiriman untuk kebutuhan Anda.</p>
            </div>
            
            <div class="service-grid-4">
                <div class="unified-card animate-up delay-1" style="align-items: center; text-align: center;">
                    <div class="icon-circle"><i class="bi bi-bus-front"></i></div>
                    <h3 style="font-size:1.2rem; font-weight:800; color:var(--text-main); margin-bottom:10px;">Travel Reguler</h3>
                    <p style="color:var(--text-muted); font-size:0.9rem; line-height:1.6;">Antar jemput Pacitan - Malang (PP). Berangkat Pagi & Malam setiap hari.</p>
                </div>
                <div class="unified-card animate-up delay-2" style="align-items: center; text-align: center;">
                    <div class="icon-circle"><i class="bi bi-box-seam"></i></div>
                    <h3 style="font-size:1.2rem; font-weight:800; color:var(--text-main); margin-bottom:10px;">Kirim Paket</h3>
                    <p style="color:var(--text-muted); font-size:0.9rem; line-height:1.6;">Layanan kilat dan aman untuk pengiriman paket barang maupun dokumen.</p>
                </div>
                <div class="unified-card animate-up delay-2" style="align-items: center; text-align: center;">
                    <div class="icon-circle"><i class="bi bi-car-front-fill"></i></div>
                    <h3 style="font-size:1.2rem; font-weight:800; color:var(--text-main); margin-bottom:10px;">Carter Kendaraan</h3>
                    <p style="color:var(--text-muted); font-size:0.9rem; line-height:1.6;">Armada nyaman untuk perjalanan pribadi, keluarga, maupun keperluan bisnis.</p>
                </div>
                <div class="unified-card animate-up delay-3" style="align-items: center; text-align: center;">
                    <div class="icon-circle"><i class="bi bi-map-fill"></i></div>
                    <h3 style="font-size:1.2rem; font-weight:800; color:var(--text-main); margin-bottom:10px;">Paket Wisata</h3>
                    <p style="color:var(--text-muted); font-size:0.9rem; line-height:1.6;">Nikmati perjalanan liburan ke berbagai destinasi wisata dengan armada kami.</p>
                </div>
            </div>
        </section>

        <section id="layanan-utama" style="padding: 60px 5% 100px;">
            <div class="section-header animate-up delay-2">
                <span class="section-tag">Booking Tiket</span>
                <h2 class="section-title">Pilih Perjalanan Anda</h2>
            </div>

            <div class="ticket-grid">
                
                <div class="unified-card animate-up delay-2">
                    <div class="card-image-box">
                        <span class="card-badge">POPULER</span>
                        <img src="{{ asset('public/assets/img/bromo.webp') }}" alt="Rute Malang">
                    </div>
                    <h3 style="font-size:1.3rem; font-weight:800; color:var(--text-main); margin-bottom:5px; text-align: left;">Travel Malang</h3>
                    <div style="text-align: left; margin-bottom: 15px;">
                        <span style="font-size:1.2rem; font-weight:800; color:var(--p-color);">Rp 180.000</span>
                        <span style="font-size:0.85rem; color:var(--text-muted);">/ Kursi</span>
                    </div>
                    <p style="text-align: left; color:var(--text-muted); font-size:0.9rem; margin-bottom: 20px;">
                        Rute Pacitan - Malang via Batu/Blitar. Armada Hiace/Innova Reborn.
                    </p>
                    <a href="{{ route('layanan.index', ['rute' => 'PACITAN_MALANG']) }}" class="btn-detail">Pesan Sekarang</a>
                </div>

                <div class="unified-card animate-up delay-2">
                    <div class="card-image-box">
                        <span class="card-badge" style="background:#483d8b; color:white;">PEMANDANGAN</span>
                        <img src="{{ asset('public/assets/img/blitar.jpg') }}" alt="Rute Blitar">
                    </div>
                    <h3 style="font-size:1.3rem; font-weight:800; color:var(--text-main); margin-bottom:5px; text-align: left;">Via JLS (Blitar)</h3>
                    <div style="text-align: left; margin-bottom: 15px;">
                        <span style="font-size:1.2rem; font-weight:800; color:var(--p-color);">Rp 170.000</span>
                        <span style="font-size:0.85rem; color:var(--text-muted);">/ Kursi</span>
                    </div>
                    <p style="text-align: left; color:var(--text-muted); font-size:0.9rem; margin-bottom: 20px;">
                        Perjalanan lewat Jalur Lintas Selatan dengan pemandangan laut indah.
                    </p>
                    <a href="{{ route('layanan.index', ['rute' => 'PACITAN_BLITAR']) }}" class="btn-detail">Pesan Sekarang</a>
                </div>

                <div class="unified-card animate-up delay-2">
                    <div class="card-image-box">
                        <span class="card-badge" style="background:#28a745; color:white;">KILAT</span>
                        <img src="{{ asset('public/assets/img/kargo3.jpg') }}" alt="Layanan Kargo">
                    </div>
                    <h3 style="font-size:1.3rem; font-weight:800; color:var(--text-main); margin-bottom:5px; text-align: left;">Kirim Paket</h3>
                    <div style="text-align: left; margin-bottom: 15px;">
                        <span style="font-size:1.2rem; font-weight:800; color:var(--p-color);">Mulai Rp 50.000</span>
                    </div>
                    <p style="text-align: left; color:var(--text-muted); font-size:0.9rem; margin-bottom: 20px;">
                        Titip paket lewat travel. Cepat sampai (1 hari). Aman dan terpercaya.
                    </p>
                    <button class="btn-detail" id="openKargo">Cek Ongkir</button>
                </div>

            </div>
        </section>
    </main>

    @include('user.partials.footer')

    <div id="kargoModal" class="modal-overlay">
        <div class="modal-box">
            <div style="margin-bottom: 20px;">
                <div style="background: rgba(72, 61, 139, 0.1); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                    <i class="bi bi-box-seam-fill" style="font-size: 3rem; color: var(--p-color);"></i>
                </div>
                <h3 style="color:var(--text-main); font-weight: 800; margin: 0;">Cek Ongkir Kargo</h3>
                <p style="color:var(--text-muted); font-size: 0.9rem;">Masukkan estimasi berat barang (Kg).</p>
            </div>
            
            <div style="margin-bottom: 25px;">
                <input type="number" id="beratBarang" placeholder="0" min="1" 
                       style="width:100%; padding:15px; border:2px solid var(--border-color); border-radius:12px; background:var(--bg-color); color:var(--text-main); outline:none; font-size: 1.5rem; font-weight: bold; text-align: center;">
            </div>

            <div style="background:rgba(212, 175, 55, 0.1); border: 1px dashed var(--accent-gold); padding:15px; border-radius:12px; margin-bottom:25px;">
                <p style="margin:0; font-size:0.85rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Estimasi Biaya</p>
                <h2 id="totalOngkir" style="margin:5px 0; color:var(--p-color); font-size: 2rem; font-weight: 800;">Rp 0</h2>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button id="closeKargo" style="flex: 1; padding:12px; background:transparent; color:var(--text-muted); border:1px solid var(--border-color); border-radius:10px; cursor:pointer; font-weight:600;">Batal</button>
                <a href="#" id="btnLanjutKargo" style="flex: 2; padding:12px; background:var(--p-color); color:white; border:none; border-radius:10px; cursor:pointer; font-weight:600; text-align: center; text-decoration: none;">
                    Lanjut Order
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Swiper Hero
            const swiper = new Swiper('.hero', { loop: true, autoplay: { delay: 5000 }, pagination: { el: '.swiper-pagination', clickable: true }, effect: 'fade' });

            // Logic Tanggal
            const dateInput = document.getElementById('dateInput');
            if(dateInput) {
                const today = new Date().toISOString().split('T')[0];
                dateInput.setAttribute('min', today); dateInput.value = today;
            }

            // Logic Modal Kargo
            const btnKargo = document.getElementById('openKargo');
            const btnCloseKargo = document.getElementById('closeKargo');
            const modalKargo = document.getElementById('kargoModal');
            const inputBerat = document.getElementById('beratBarang');
            const displayTotal = document.getElementById('totalOngkir');
            const btnLanjut = document.getElementById('btnLanjutKargo');

            if(btnKargo) btnKargo.onclick = () => { modalKargo.style.display = 'flex'; inputBerat.focus(); };
            if(btnCloseKargo) btnCloseKargo.onclick = () => { modalKargo.style.display = 'none'; resetModal(); };

            // 🚀 Tangkap data tarif kargo dari database   
            const dbTarifKargo = @json($tarifKargo ?? null);

            inputBerat.oninput = function() {
                let berat = Math.ceil(parseFloat(inputBerat.value));
                if (!berat || berat <= 0) { displayTotal.innerText = "Rp 0"; btnLanjut.href = "#"; btnLanjut.style.opacity = "0.5"; btnLanjut.style.pointerEvents = "none"; return; }
                
                // Ambil harga dinamis, kalau kosong pakai default
                let hargaDasar = dbTarifKargo && dbTarifKargo.harga_dasar ? parseInt(dbTarifKargo.harga_dasar) : 50000;
                let hargaSelanjutnya = dbTarifKargo && dbTarifKargo.harga_selanjutnya ? parseInt(dbTarifKargo.harga_selanjutnya) : 25000;

                // Logika kalkulator kargo dinamis
                let total = (berat <= 1) ? hargaDasar : hargaDasar + ((berat - 1) * hargaSelanjutnya);
                
                displayTotal.innerText = "Rp " + total.toLocaleString('id-ID');
                btnLanjut.href = "{{ route('layanan.index') }}?kategori=kargo&berat=" + berat;
                btnLanjut.style.opacity = "1"; btnLanjut.style.pointerEvents = "auto";
            };

            window.onclick = function(event) { if (event.target == modalKargo) { modalKargo.style.display = "none"; resetModal(); } }
            function resetModal() { inputBerat.value = ''; displayTotal.innerText = 'Rp 0'; btnLanjut.href = "#"; btnLanjut.style.opacity = "0.5"; btnLanjut.style.pointerEvents = "none"; }
            resetModal();
        });
    </script>
</body>
</html>