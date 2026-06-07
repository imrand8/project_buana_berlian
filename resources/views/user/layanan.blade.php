<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Layanan - Buana Berlian</title>
    <link rel="icon" href="{{ asset('public/assets/img/LOGO.png') }}" type="image/png">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif !important; background-color: var(--bg-color); color: var(--text-main); }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .animate-up { animation: fadeInUp 0.6s cubic-bezier(0.165, 0.84, 0.44, 1) forwards; opacity: 0; }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .booking-layout { display: grid; grid-template-columns: 1.6fr 1fr; gap: 30px; align-items: start; margin-top: 30px; }
        .glass-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); margin-bottom: 25px; position: relative; transition: background-color 0.4s ease, border-color 0.4s ease; }
        [data-theme="dark"] .glass-card { box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
        h4.card-title { font-size: 1.1rem; font-weight: 800; margin-bottom: 25px; color: var(--text-main); display: flex; align-items: center; gap: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        h4.card-title i { color: var(--p-color); font-size: 1.3rem; }
        [data-theme="dark"] h4.card-title i { color: var(--accent-gold); }
        .service-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .service-option { border: 2px solid var(--border-color); border-radius: 16px; padding: 20px; text-align: center; cursor: pointer; transition: 0.2s; background: var(--bg-color); }
        .service-option:hover { transform: translateY(-3px); border-color: var(--p-color); }
        .service-option.active { background: rgba(72, 61, 139, 0.05); border-color: var(--p-color); box-shadow: 0 5px 15px rgba(72, 61, 139, 0.15); }
        [data-theme="dark"] .service-option.active { background: rgba(212, 175, 55, 0.1); border-color: var(--accent-gold); box-shadow: 0 5px 15px rgba(212, 175, 55, 0.15); }
        .service-option i { font-size: 2rem; display: block; margin-bottom: 10px; color: var(--text-muted); }
        .service-option.active i { color: var(--p-color); }
        [data-theme="dark"] .service-option.active i { color: var(--accent-gold); }
        .form-row { margin-bottom: 25px; }
        .grid-2-col { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .input-group-custom label { display: block; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
        .form-control-custom { width: 100%; padding: 12px 15px; background: var(--bg-color); border: 1px solid var(--border-color); border-radius: 10px; color: var(--text-main); font-size: 1rem; font-weight: 600; outline: none; transition: 0.3s; }
        .form-control-custom:focus { border-color: var(--p-color); box-shadow: 0 0 0 3px rgba(72, 61, 139, 0.1); }
        [data-theme="dark"] .form-control-custom:focus { border-color: var(--accent-gold); box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1); }
        select.form-control-custom { cursor: pointer; appearance: none; }
        [data-theme="dark"] select.form-control-custom option { background-color: #1e1e1e !important; color: #ffffff !important; padding: 10px; }
        [data-theme="dark"] ::-webkit-calendar-picker-indicator { filter: invert(1); cursor: pointer; opacity: 0.7; }
        [data-theme="dark"] ::-webkit-calendar-picker-indicator:hover { opacity: 1; }
        ::placeholder { color: #999; opacity: 1; font-weight: 400; }
        [data-theme="dark"] ::placeholder { color: #777; }
        .route-picker-container { display: flex; align-items: center; justify-content: space-between; background: var(--bg-color); border: 1px dashed var(--border-color); border-radius: 16px; padding: 20px; gap: 15px; }
        .btn-swap { width: 40px; height: 40px; border-radius: 50%; background: var(--card-bg); border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.3s; color: var(--p-color); }
        .btn-swap:hover { transform: rotate(180deg); border-color: var(--p-color); background: var(--p-color); color: white; }
        [data-theme="dark"] .btn-swap { color: var(--accent-gold); }
        [data-theme="dark"] .btn-swap:hover { border-color: var(--accent-gold); background: var(--accent-gold); color: black; }
        .sidebar-card { position: sticky; top: 100px; text-align: center; }
        .seat-map-container { background: var(--bg-color); padding: 20px; border-radius: 16px; border: 1px solid var(--border-color); margin-bottom: 20px; }

        /* --- DESAIN KURSI MODERN (TOP-DOWN VIEW) --- */
        .seat-box { 
            position: relative;
            height: 55px; 
            border-radius: 6px 6px 12px 12px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-weight: 800; 
            font-size: 1.1rem;
            cursor: pointer; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            background: var(--card-bg); 
            border: 2px solid var(--border-color); 
            border-top-width: 10px; 
            color: var(--text-main); 
            text-decoration: none; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.03);
        }
        .seat-box:hover { color: var(--text-main); }

        .seat-box:not(.occupied):not(.driver-box):hover { 
            background: var(--p-color); 
            color: white; 
            border-color: var(--p-color); 
            border-top-color: #2a2355; 
            transform: translateY(-4px); 
            box-shadow: 0 8px 15px rgba(72, 61, 139, 0.2); 
        }
        [data-theme="dark"] .seat-box:not(.occupied):not(.driver-box):hover { 
            background: var(--accent-gold); 
            color: black; 
            border-color: var(--accent-gold); 
            border-top-color: #a67c00; 
        }

        .seat-box.selected { 
            background: var(--p-color) !important; 
            color: white !important; 
            border-color: var(--p-color) !important; 
            border-top-color: #2a2355 !important; 
            box-shadow: 0 5px 15px rgba(72, 61, 139, 0.3); 
            transform: scale(1.05);
        }
        [data-theme="dark"] .seat-box.selected { 
            background: var(--accent-gold) !important; 
            color: black !important; 
            border-color: var(--accent-gold) !important; 
            border-top-color: #a67c00 !important; 
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3); 
        }

        .seat-box.occupied { 
            background: #e0e0e0 !important; 
            border-color: #cccccc !important; 
            border-top-color: #b0b0b0 !important; 
            color: #999 !important; 
            cursor: not-allowed; 
            opacity: 0.8;
        }
        [data-theme="dark"] .seat-box.occupied { 
            background: #2a2a2a !important; 
            border-color: #333 !important; 
            border-top-color: #1a1a1a !important; 
            color: #555 !important; 
        }

        .driver-box { 
            background: #333 !important; 
            color: #fff !important; 
            border: 2px solid #222 !important; 
            border-top-width: 10px !important;
            border-top-color: #000 !important; 
            cursor: default; 
            font-size: 0.75rem; 
            letter-spacing: 1px;
        }
        [data-theme="dark"] .driver-box { 
            background: #1a1a1a !important; 
            color: #888 !important; 
            border-color: #222 !important; 
            border-top-color: #000 !important; 
        }

        .btn-confirm { display: block; width: 100%; padding: 15px; border-radius: 12px; background: linear-gradient(135deg, var(--p-color), #2a2355); color: white; font-weight: 700; font-size: 1rem; border: none; cursor: pointer; margin-top: 20px; transition: 0.3s; box-shadow: 0 10px 20px rgba(72, 61, 139, 0.2); }
        .btn-confirm:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(72, 61, 139, 0.4); }
        .btn-confirm:disabled { background: #ccc !important; color: #888 !important; cursor: not-allowed; box-shadow: none; transform: none; }
        [data-theme="dark"] .btn-confirm { background: var(--accent-gold); color: black; box-shadow: 0 10px 20px rgba(212, 175, 55, 0.2); }
        [data-theme="dark"] .btn-confirm:disabled { background: #444 !important; color: #777 !important; box-shadow: none; }
        
        .payment-modal { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); backdrop-filter: blur(8px); align-items: center; justify-content: center; padding: 20px; }
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
        div.swal2-container { z-index: 20000 !important; }
        [data-theme="dark"] div.swal2-popup { background-color: #1e1e1e !important; color: #ffffff !important; border: 1px solid #444; }
        [data-theme="dark"] div.swal2-title { color: var(--accent-gold) !important; }
        [data-theme="dark"] div.swal2-html-container { color: #cccccc !important; }
        [data-theme="dark"] button.swal2-confirm { background-color: var(--accent-gold) !important; color: #000000 !important; border: none !important; font-weight: bold; }
        [data-theme="dark"] button.swal2-cancel { background-color: #333333 !important; color: #ffffff !important; }
        @media (max-width: 900px) { .booking-layout { grid-template-columns: 1fr; gap: 30px; } .grid-2-col { grid-template-columns: 1fr; gap: 15px; } .route-picker-container { flex-direction: column; } .btn-swap { transform: rotate(90deg); } }
    </style>
</head>
<body data-theme="light">
    
    @include('user.partials.header')

    @php
        $dateParam = request('tanggal', date('Y-m-d'));
        $passParam = request('kursi', 1);
        $catParam  = request('kategori', 'travel'); 
        $beratParam = request('berat', '');
        $asalParam = request('kota_asal', 'Pacitan');
        $tujuanParam = request('kota_tujuan', 'Malang');
    @endphp

    <main style="padding-top: 120px; min-height: 100vh; padding-bottom: 80px;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 5%;">
            
            <div style="text-align: center; margin-bottom: 40px;" class="animate-up">
                <h1 style="font-size: 2.2rem; font-weight: 800; color: var(--text-main); margin-bottom: 10px;">Form Pemesanan</h1>
                <p style="color: var(--text-muted); font-size: 1rem;">Cari jadwal, pilih armada, dan pesan kursi secara real-time.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success animate-up" style="border-radius: 15px; font-weight: bold;">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger animate-up" style="border-radius: 15px;">
                    <ul style="margin: 0;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="formTransaksi" action="{{ route('pesan.travel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <input type="hidden" name="jadwal_id" id="input_jadwal_id">
                <input type="hidden" name="nomor_kursi" id="input_nomor_kursi">
                <input type="hidden" name="total_harga" id="input_total_harga">
                <input type="hidden" name="metode_bayar" id="input_metode_bayar">

                <div class="booking-layout">
                    <div>
                        <div class="glass-card animate-up delay-1">
                            <h4 class="card-title"><i class="bi bi-grid-fill"></i> Pilih Layanan</h4>
                            <div class="service-grid">
                                <div id="opt-travel" class="service-option" onclick="switchService('travel')">
                                    <i class="bi bi-bus-front"></i>
                                    <div style="font-size: 1rem; font-weight: 700; color: var(--text-main);">Travel Reguler</div>
                                    <span style="font-size: 0.8rem; color: var(--text-muted);">Layanan Antar Jemput</span>
                                </div>
                                <div id="opt-kargo" class="service-option" onclick="switchService('kargo')">
                                    <i class="bi bi-box-seam-fill"></i>
                                    <div style="font-size: 1rem; font-weight: 700; color: var(--text-main);">Kirim Kargo</div>
                                    <span style="font-size: 0.8rem; color: var(--text-muted);">Paket Kilat Rute Sama</span>
                                </div>
                            </div>
                        </div>

                        <div class="glass-card animate-up delay-2">
                            <h4 class="card-title"><i class="bi bi-person-lines-fill"></i> Pencarian Jadwal & Data Diri</h4>
                            
                            <div class="form-row">
                                <div class="route-picker-container">
                                    <div style="flex:1;">
                                        <label style="font-size:0.7rem; font-weight:800; color:var(--text-muted); text-transform:uppercase; display:block; margin-bottom:5px;">DARI</label>
                                        <select id="origin" name="kota_asal" class="form-control-custom" onchange="filterCities()" style="border:none; background:transparent; font-size:1.2rem; font-weight:800; color:var(--p-color); padding:0;">
                                            @php $kotaList = collect($rutes)->pluck('kota_asal')->merge(collect($rutes)->pluck('kota_tujuan'))->unique(); @endphp
                                            @foreach($kotaList as $kota)
                                                <option value="{{ $kota }}" {{ $kota == $asalParam ? 'selected' : '' }}>{{ ucfirst($kota) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="btn-swap" onclick="swap()"><i class="bi bi-arrow-left-right"></i></div>
                                    <div style="flex:1; text-align:right;">
                                        <label style="font-size:0.7rem; font-weight:800; color:var(--text-muted); text-transform:uppercase; display:block; margin-bottom:5px;">KE</label>
                                        <select id="destination" name="kota_tujuan" class="form-control-custom" style="border:none; background:transparent; font-size:1.2rem; font-weight:800; color:var(--p-color); direction: rtl; padding:0;" onchange="checkSchedule()">
                                            @foreach($kotaList as $kota)
                                                <option value="{{ $kota }}" {{ $kota == $tujuanParam ? 'selected' : '' }}>{{ ucfirst($kota) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row grid-2-col">
                                <div class="input-group-custom">
                                    <label>Tanggal Keberangkatan</label>
                                    <input type="date" id="date-pick" name="tanggal_berangkat" class="form-control-custom" value="{{ $dateParam }}" onchange="checkSchedule()">
                                </div>
                                <div class="input-group-custom">
                                    <label>Jam Keberangkatan</label>
                                    <select id="time-pick" name="jam_berangkat" class="form-control-custom" onchange="checkSchedule()">
                                        <option value="08:30:00">Pagi (08:30 WIB)</option>
                                        <option value="20:00:00">Malam (20:00 WIB)</option>
                                    </select>
                                </div>
                            </div>

                            <div id="travel-passenger" class="form-row">
                                <div class="input-group-custom">
                                    <label>Jumlah Penumpang</label>
                                    <select id="pass-count" name="jumlah_kursi" class="form-control-custom" onchange="updatePassengerCount()">
                                        <option value="1" {{ $passParam == 1 ? 'selected' : '' }}>1 Orang</option>
                                        <option value="2" {{ $passParam == 2 ? 'selected' : '' }}>2 Orang</option>
                                        <option value="3" {{ $passParam == 3 ? 'selected' : '' }}>3 Orang</option>
                                        <option value="4" {{ $passParam == 4 ? 'selected' : '' }}>4 Orang</option>
                                    </select>
                                </div>
                            </div>

                            <div style="margin: 30px 0 15px; border-bottom: 2px dashed var(--border-color);"></div>

                            <div class="form-row grid-2-col">
                                <div class="input-group-custom">
                                    <label id="label-nama-pengirim">Nama Penumpang (Sesuai KTP)</label>
                                    <input type="text" id="cust-name" name="nama_penumpang" class="form-control-custom" value="{{ Auth::check() ? Auth::user()->name : '' }}" placeholder="Nama Lengkap">
                                </div>
                                <div class="input-group-custom" id="nama-penerima-container" style="display: none;">
                                    <label>Nama Penerima</label>
                                    <input type="text" id="rcv-name" name="nama_penerima" class="form-control-custom" placeholder="Nama Penerima Paket">
                                </div>
                            </div>

                            <div class="form-row grid-2-col">
                                <div class="input-group-custom">
                                    <label id="label-wa-pengirim">Nomor WA Penumpang / Aktif</label>
                                    <input type="tel" id="cust-wa" name="nomor_wa" class="form-control-custom" placeholder="Contoh: 08123456789">
                                </div>
                                <div class="input-group-custom" id="wa-penerima-container" style="display: none;">
                                    <label>Nomor WA Penerima</label>
                                    <input type="tel" id="rcv-wa" name="nomor_wa_penerima" class="form-control-custom" placeholder="Contoh: 08987654321">
                                </div>
                            </div>

                            <div class="form-row grid-2-col">
                                <div class="input-group-custom">
                                    <label>Alamat Jemput / Asal</label>
                                    <textarea id="addr-pick" name="titik_jemput" class="form-control-custom" rows="2" placeholder="Contoh: Jl. Ahmad Yani No. 10"></textarea>
                                </div>
                                <div class="input-group-custom">
                                    <label>Alamat Tujuan (Antar)</label>
                                    <textarea id="addr-drop" name="titik_antar" class="form-control-custom" rows="2" placeholder="Contoh: Kos Putri Buana"></textarea>
                                </div>
                            </div>

                            <div id="travel-baggage" class="form-row">
                                <div class="input-group-custom">
                                    <label>Keterangan Barang Bawaan (Opsional)</label>
                                    <input type="text" id="baggage-info" name="keterangan_barang" class="form-control-custom" placeholder="Contoh: 1 Koper besar, 2 Kardus oleh-oleh">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="booking-sidebar">
                        <div class="glass-card sidebar-card animate-up delay-2">
                            
                            <div id="fleet-image-wrapper">
                                <div style="margin-bottom: 20px; padding: 15px; background: var(--bg-color); border-radius: 16px; border: 1px dashed var(--border-color);">
                                    <img id="fleet-image" src="{{ asset('public/assets/img/innova hitam.png') }}" alt="Armada" style="width: 100%; height: auto; object-fit: contain; filter: drop-shadow(0 5px 5px rgba(0,0,0,0.1)); transition: 0.3s;"> 
                                </div>
                            </div>

                            <h4 id="sidebar-title" style="justify-content: center; margin-bottom: 15px; color: var(--text-main); font-size: 1rem; font-weight: 800;">Armada & Kursi</h4>
                            
                            <div id="fleet-selector" style="margin-bottom: 20px; text-align: left;">
                                    <label style="font-size: 0.7rem; font-weight: 800; color: var(--text-muted); margin-bottom: 5px; display: block;">UNIT TERSEDIA (MAX 7 SEAT)</label>
                                    <select id="fleet-pick" class="form-control-custom" onchange="changeFleet()">
                                    </select>
                                </div>

                            <div id="seat-view">
                                <div class="seat-map-container">
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px;">
                                        <a href="javascript:void(0)" class="seat-box" id="seat-1" onclick="pick(this, 1)">1</a>
                                        <div class="seat-box driver-box">SUPIR</div>
                                    </div>
                                    <div style="font-size: 0.6rem; color: var(--text-muted); text-align: left; margin-bottom: 5px;">BARIS TENGAH</div>
                                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 15px;">
                                        <a href="javascript:void(0)" class="seat-box" id="seat-2" onclick="pick(this, 2)">2</a>
                                        <a href="javascript:void(0)" class="seat-box" id="seat-3" onclick="pick(this, 3)">3</a>
                                        <a href="javascript:void(0)" class="seat-box" id="seat-4" onclick="pick(this, 4)">4</a>
                                    </div>
                                    <div style="font-size: 0.6rem; color: var(--text-muted); text-align: left; margin-bottom: 5px;">BARIS BELAKANG</div>
                                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
                                        <a href="javascript:void(0)" class="seat-box" id="seat-5" onclick="pick(this, 5)">5</a>
                                        <a href="javascript:void(0)" class="seat-box" id="seat-6" onclick="pick(this, 6)">6</a>
                                        <a href="javascript:void(0)" class="seat-box" id="seat-7" onclick="pick(this, 7)">7</a>
                                    </div>
                                </div>
                                <p style="font-size: 0.85rem; color: var(--text-main);">Pilihan Anda: <strong id="s-label" style="color: var(--p-color);">Belum memilih</strong></p>
                            </div>

                            <div id="kargo-view" style="display: none; padding: 10px 0;">
                                <div class="form-row">
                                    <div class="input-group-custom" style="text-align: left;">
                                        <label>Jenis Barang</label>
                                        <input type="text" id="cargo-type" name="keterangan_barang" class="form-control-custom" placeholder="Contoh: Dokumen / Makanan">
                                    </div>
                                </div>
                                <div class="form-row grid-2-col" style="margin-bottom: 0;">
                                    <div class="input-group-custom" style="text-align: left;">
                                        <label>Jml Koli (Pcs)</label>
                                        <input type="number" id="koli-qty" class="form-control-custom" value="1" min="1" oninput="calcKargo()" style="text-align: center; font-size: 1.2rem; font-weight: 800;">
                                    </div>
                                    <div class="input-group-custom" style="text-align: left;">
                                        <label>Berat (Kg)</label>
                                        <input type="number" id="w-kg" name="berat_barang" value="{{ $beratParam }}" oninput="calcKargo()" placeholder="0" class="form-control-custom" style="text-align: center; font-size: 1.2rem; font-weight: 800;">
                                    </div>
                                </div>
                            </div>

                            <hr style="margin: 25px 0; border: none; border-top: 2px dashed var(--border-color);">

                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                                <span style="font-weight: 600; color: var(--text-muted); font-size: 0.9rem;">Total Tagihan:</span>
                                <span id="price" style="font-size: 1.5rem; font-weight: 800; color: var(--p-color);">Rp 0</span>
                            </div>
                            <button type="button" id="btn-lanjut-bayar" class="btn-confirm" onclick="openPaymentModal()">Lanjut Pembayaran <i class="bi bi-arrow-right"></i></button>
                        </div>
                    </div>
                </div>

                <div id="paymentModal" class="payment-modal">
                    <div class="payment-content">
                        
                        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                            <h3 class="modal-title" style="margin: 0; font-size: 1.2rem; color: var(--text-main); font-weight: 800;">Pilih Metode Bayar</h3>
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

                            <div class="payment-option" onclick="selectPayment('cash')">
                                <div style="background: rgba(40, 167, 69, 0.1); padding: 10px; border-radius: 10px;"><i class="bi bi-cash-stack" style="font-size: 1.3rem; color: #28a745;"></i></div>
                                <div style="text-align: left;">
                                    <h5 style="margin: 0; font-size: 0.95rem; font-weight: 700; color: var(--text-main);">Bayar Tunai (CASH)</h5>
                                    <small style="color: var(--text-muted); font-size: 0.8rem;">Bayar Tunai ke Supir</small>
                                </div>
                                <i class="bi bi-chevron-right" style="margin-left: auto; color: var(--text-muted);"></i>
                            </div>
                        </div>

                        <div id="step-cash" style="display: none; animation: popIn 0.3s;">
                            <div style="text-align: center; padding: 30px 20px; background: var(--bg-color); border-radius: 16px; border: 1px solid var(--border-color);">
                                <div style="background: rgba(40, 167, 69, 0.1); width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                                    <i class="bi bi-check-lg" style="font-size: 2.5rem; color: #28a745;"></i>
                                </div>
                                <h4 style="color: var(--text-main); margin-bottom: 10px; font-weight: 800;">Siapkan Uang Tunai</h4>
                                <p style="color: var(--text-muted); line-height: 1.5; font-size: 0.9rem;">Admin akan menghubungi Anda. Mohon siapkan uang pas untuk diserahkan kepada Supir saat penjemputan.</p>
                            </div>
                            <div style="margin-top: 20px;">
                                <button type="button" class="btn-confirm" style="background: #28a745;" onclick="finishOrder('CASH')">Konfirmasi & Pesan (CASH)</button>
                                <button type="button" onclick="resetPayment()" style="width: 100%; margin-top: 10px; padding: 10px; background: none; border: none; color: var(--text-muted); cursor: pointer; font-weight: 600; font-size: 0.9rem;">Ganti Metode Lain</button>
                            </div>
                        </div>

                        <div id="step-bsi" style="display: none; animation: popIn 0.3s;">
                            <p style="color: var(--text-main); font-size: 0.9rem; text-align: center; margin-bottom: 5px;">Transfer ke rekening BSI:</p>
                            <div class="payment-detail-box bsi-box">
                                <div class="logo-wrapper"><img src="https://upload.wikimedia.org/wikipedia/commons/a/a0/Bank_Syariah_Indonesia.svg" alt="Logo BSI"></div>
                                <h2 class="rek-number">7095570188</h2>
                                <p style="margin: 5px 0 15px; font-size: 0.9rem; color: var(--text-muted); font-weight: 600;">a.n Malta A QQ Hanum Floreta</p>
                                <button type="button" onclick="salinTeks('7095570188')" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 6px 18px; border-radius: 20px; font-size: 0.8rem; color: var(--text-main); cursor: pointer; transition: 0.3s; font-weight: 600;"><i class="bi bi-clipboard"></i> Salin Nomor</button>
                            </div>
                            <div class="input-group-custom" style="text-align: left;">
                                <label style="margin-bottom: 8px;">Upload Bukti Transfer BSI <span style="color:red">*</span></label>
                                <input type="file" id="proof-bsi" name="bukti_transfer" class="form-control-custom" accept="image/jpeg, image/png, image/jpg, image/webp" style="padding: 10px; height: auto;">
                            </div>
                            <div style="margin-top: 20px;">
                                <button type="button" class="btn-confirm" style="background: linear-gradient(135deg, #00a39d, #007c77);" onclick="finishOrder('BSI')"><i class="bi bi-send-fill"></i> Selesaikan Pesanan</button>
                                <button type="button" onclick="resetPayment()" style="width: 100%; margin-top: 10px; padding: 10px; background: none; border: none; color: var(--text-muted); cursor: pointer; font-weight: 600; font-size: 0.9rem;">Kembali Menu Awal</button>
                            </div>
                        </div>

                        <div id="step-dana" style="display: none; animation: popIn 0.3s;">
                            <p style="color: var(--text-main); font-size: 0.9rem; text-align: center; margin-bottom: 5px;">Kirim saldo ke nomor DANA berikut:</p>
                            <div class="payment-detail-box dana-box">
                                <div class="logo-wrapper"><img src="https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_dana_blue.svg" alt="Logo DANA"></div>
                                <h2 class="rek-number">082142303088</h2> <p style="margin: 5px 0 15px; font-size: 0.9rem; color: var(--text-muted); font-weight: 600;">a.n Malta Anantyasari</p>
                                <button type="button" onclick="salinTeks('082142303088')" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 6px 18px; border-radius: 20px; font-size: 0.8rem; color: var(--text-main); cursor: pointer; transition: 0.3s; font-weight: 600;"><i class="bi bi-clipboard"></i> Salin Nomor DANA</button>
                            </div>
                            <div class="input-group-custom" style="text-align: left;">
                                <label style="margin-bottom: 8px;">Upload Bukti Transfer DANA <span style="color:red">*</span></label>
                                <input type="file" id="proof-dana" name="bukti_transfer" class="form-control-custom" accept="image/jpeg, image/png, image/jpg, image/webp" disabled style="padding: 10px; height: auto;">
                            </div>
                            <div style="margin-top: 20px;">
                                <button type="button" class="btn-confirm" style="background: linear-gradient(135deg, #118EEA, #0a65a8);" onclick="finishOrder('DANA')"><i class="bi bi-send-fill"></i> Selesaikan Pesanan</button>
                                <button type="button" onclick="resetPayment()" style="width: 100%; margin-top: 10px; padding: 10px; background: none; border: none; color: var(--text-muted); cursor: pointer; font-weight: 600; font-size: 0.9rem;">Kembali Menu Awal</button>
                            </div>
                        </div>

                    </div> 
                </div>
            </form>
        </div>
    </main>

    @include('user.partials.footer')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // 1. DATA MASTER DARI DATABASE
        const dbJadwals = @json($jadwals ?? []);
        const dbBookedTravels = @json($travels ?? []);
        const dbRutes = @json($rutes ?? []);
        const dbTarifKargo = @json($tarifKargo ?? null);
        const baseImgUrl = "{{ asset('public/assets/img') }}";
        
        let maxSeats = {{ $passParam }};
        let selectedSeats = [];
        let currentService = '{{ $catParam }}';
        let currentPricePerSeat = 0;

        document.addEventListener('DOMContentLoaded', () => {
            const dateInput = document.getElementById('date-pick');
            const today = new Date().toISOString().split('T')[0];
            dateInput.min = today; 
            switchService(currentService);
        });

        // 2. FUNGSI CEK JADWAL & HARGA DINAMIS DARI DATABASE
        function checkSchedule() {
            let asal = document.getElementById('origin').value.toLowerCase();
            let tujuan = document.getElementById('destination').value.toLowerCase();
            let tgl = document.getElementById('date-pick').value;
            let jam = document.getElementById('time-pick').value; // isinya "08:30:00" atau "20:00:00"
            let fleetSelect = document.getElementById('fleet-pick');
            
            fleetSelect.innerHTML = ''; 
            if(asal === tujuan) { swap(); return; }

            // LOGIKA TRAYEK INDUK (MAGIC!)
            const urutanKota = ['pacitan', 'trenggalek', 'tulungagung', 'blitar', 'malang'];
            let idxAsal = urutanKota.indexOf(asal);
            let idxTujuan = urutanKota.indexOf(tujuan);
            
            // Tentukan ke arah mana mobil ini jalan
            let trayekAsal = idxAsal < idxTujuan ? 'pacitan' : 'malang';
            let trayekTujuan = idxAsal < idxTujuan ? 'malang' : 'pacitan';

            // Filter jadwal berdasarkan Trayek Induknya, Tanggal, dan Jam
            let available = dbJadwals.filter(j => {
                let isMatch = j.rute && 
                    j.rute.kota_asal.toLowerCase() === trayekAsal && 
                    j.rute.kota_tujuan.toLowerCase() === trayekTujuan &&
                    j.tanggal_berangkat === tgl &&
                    j.jam_berangkat.startsWith(jam.substring(0, 5));
                
                if (!isMatch) return false;

                // --- TAMBAHAN REVISI: HIDE JADWAL KALAU KURANG DARI 30 MENIT ---
                let waktuBerangkat = new Date(`${j.tanggal_berangkat}T${j.jam_berangkat}`);
                let sekarang = new Date();
                
                // Hitung selisih dalam menit
                let selisihMenit = (waktuBerangkat - sekarang) / 60000;

                // Return true (tampilkan) HANYA JIKA masih ada waktu 30 menit atau lebih
                return selisihMenit >= 30;
            });

            if(available.length > 0) {
                // 1. Cek status mahasiswa
                const isMahasiswa = "{{ Auth::check() && Auth::user()->status_mahasiswa === 'terverifikasi' ? 'true' : 'false' }}";
                
                // 2. Cari rute di database
                let ruteAsli = dbRutes.find(r => r.kota_asal.toLowerCase() === asal && r.kota_tujuan.toLowerCase() === tujuan);
                
                // 3. LOGIKA DISKON OTOMATIS (Potong 10rb)
                let hargaDb = 100000; // Harga default darurat
                if (ruteAsli) {
                    let hargaNormal = parseInt(ruteAsli.harga_reguler);
                    
                    if (isMahasiswa === 'true') {
                        hargaDb = hargaNormal - 10000; // Otomatis minus 10rb untuk mahasiswa!
                    } else {
                        hargaDb = hargaNormal; // Harga normal untuk user biasa
                    }
                }

                // Ada Mobil!
                available.forEach(j => {
                    let opt = document.createElement('option');
                    opt.value = j.id; 
                    opt.text = `${j.armada.nama_armada} (${j.armada.plat_nomor})`;
                    
                // MENGGUNAKAN TRIK ASSET STORAGE APP PUBLIC
                let imageUrl = j.armada.image ? `{{ asset('storage/app/public') }}/${j.armada.image}` : `{{ asset('public/assets/img/innova hitam.png') }}`;
                opt.setAttribute('data-img', imageUrl);
                    
                    // Set harga yang sudah difilter
                    opt.setAttribute('data-price', hargaDb);
                    
                    fleetSelect.appendChild(opt);
                });
                
                document.querySelector('.btn-confirm').disabled = false;
                document.querySelector('.btn-confirm').innerHTML = 'Lanjut Pembayaran <i class="bi bi-arrow-right"></i>';
                changeFleet(); 
            } else {
                // Kosong
                let opt = document.createElement('option');
                opt.value = ""; opt.text = "-- Tidak ada armada tersedia --";
                fleetSelect.appendChild(opt);
                
                document.querySelector('.btn-confirm').disabled = true;
                document.querySelector('.btn-confirm').innerHTML = "<i class='bi bi-x-circle'></i> Jadwal Penuh / Tidak Tersedia";
                document.getElementById('input_jadwal_id').value = '';
                
                for (let i = 1; i <= 7; i++) {
                    const seatEl = document.getElementById(`seat-${i}`);
                    if(seatEl) { seatEl.className = 'seat-box occupied'; seatEl.onclick = null; }
                }
                
                currentPricePerSeat = 0;
                document.getElementById('price').innerText = "Rp 0";
                document.getElementById('input_total_harga').value = 0;
                document.getElementById('s-label').innerText = "Tidak tersedia";
            }
        }

        // PERBAIKAN: Fungsi untuk melarang kota tujuan sama dengan kota asal
        function filterCities() {
            const selectedOrigin = document.getElementById('origin').value;
            const destSelect = document.getElementById('destination');
            for (let i = 0; i < destSelect.options.length; i++) {
                destSelect.options[i].style.display = destSelect.options[i].value === selectedOrigin ? 'none' : 'block';
                destSelect.options[i].disabled = destSelect.options[i].value === selectedOrigin;
            }
            if (destSelect.value === selectedOrigin) {
                for (let i = 0; i < destSelect.options.length; i++) {
                    if (destSelect.options[i].value !== selectedOrigin) { destSelect.value = destSelect.options[i].value; break; }
                }
            }
            checkSchedule();
        }

        function swap() {
            const temp = document.getElementById('origin').value; 
            document.getElementById('origin').value = document.getElementById('destination').value; 
            filterCities();
        }

        function changeFleet() {
            const fleetSelect = document.getElementById('fleet-pick');
            if(!fleetSelect.value) return;

            const imgUrlLengkap = fleetSelect.options[fleetSelect.selectedIndex].getAttribute('data-img');
            const imgElement = document.getElementById('fleet-image');
            if(imgElement) imgElement.src = imgUrlLengkap;

            document.getElementById('input_jadwal_id').value = fleetSelect.value;
            
            // Tarik harga yang sudah diperbaiki
            currentPricePerSeat = parseFloat(fleetSelect.options[fleetSelect.selectedIndex].getAttribute('data-price'));
            if (isNaN(currentPricePerSeat)) currentPricePerSeat = 150000;
            
            // Kunci kursi yang sudah dibooking di database
            let occupiedSeats = [];
            let relatedTravels = dbBookedTravels.filter(t => t.jadwal_id == fleetSelect.value);
            relatedTravels.forEach(t => {
                t.nomor_kursi.split(',').forEach(s => occupiedSeats.push(parseInt(s.trim())));
            });

            selectedSeats = [];
            for (let i = 1; i <= 7; i++) {
                const seatEl = document.getElementById(`seat-${i}`);
                if (!seatEl) continue;
                seatEl.className = 'seat-box'; seatEl.innerHTML = i;
                seatEl.onclick = function() { pick(this, i); };
                if (occupiedSeats.includes(i)) { seatEl.classList.add('occupied'); seatEl.onclick = null; }
            }

            document.getElementById('s-label').innerText = "Belum memilih";
            document.getElementById('input_nomor_kursi').value = ''; 
            
            if(currentService === 'travel') updateHargaTravel();
        }

        function pick(el, n) {
            if(el.classList.contains('occupied')) return; 
            if (selectedSeats.includes(n)) {
                selectedSeats = selectedSeats.filter(s => s !== n); el.classList.remove('selected');
            } else {
                if (selectedSeats.length < maxSeats) { selectedSeats.push(n); el.classList.add('selected'); } 
                else { Swal.fire({ icon: 'info', title: 'Maksimal ' + maxSeats + ' Kursi', timer: 1500, showConfirmButton: false }); }
            }
            document.getElementById('s-label').innerText = selectedSeats.length > 0 ? selectedSeats.sort().join(', ') : "Belum memilih";
            document.getElementById('input_nomor_kursi').value = selectedSeats.join(','); 
        }

        function updatePassengerCount() { maxSeats = parseInt(document.getElementById('pass-count').value); changeFleet(); }
        
        function updateHargaTravel() {
            let total = currentPricePerSeat * maxSeats;
            document.getElementById('price').innerText = "Rp " + total.toLocaleString('id-ID');
            document.getElementById('input_total_harga').value = total; 
        }

        function calcKargo() {
            if(currentService === 'travel') return;
            const w = parseFloat(document.getElementById('w-kg').value) || 0;
            const koli = parseInt(document.getElementById('koli-qty').value) || 1;
            
            // 🚀 TARIK HARGA DINAMIS (Pakai nama kolom harga_selanjutnya)
            // Jika Admin belum isi database, otomatis pakai harga default 50rb & 25rb
            let hargaDasar = dbTarifKargo && dbTarifKargo.harga_dasar ? parseInt(dbTarifKargo.harga_dasar) : 50000;
            let hargaSelanjutnya = dbTarifKargo && dbTarifKargo.harga_selanjutnya ? parseInt(dbTarifKargo.harga_selanjutnya) : 25000;

            let t = 0;
            if (w > 0) {
                // Logika: 1Kg pertama kena hargaDasar, sisa Kg kena hargaSelanjutnya
                t = ((w <= 1) ? hargaDasar : hargaDasar + (Math.ceil(w - 1) * hargaSelanjutnya)) * koli;
            }
            
            document.getElementById('price').innerText = "Rp " + t.toLocaleString('id-ID');
            document.getElementById('input_total_harga').value = t; 
        }

        function switchService(t) {
            currentService = t; const isT = t === 'travel';
            document.getElementById('formTransaksi').action = isT ? "{{ route('pesan.travel') }}" : "{{ route('pesan.kargo') }}";
            ['pass-count', 'baggage-info'].forEach(id => document.getElementById(id).disabled = !isT);
            ['cargo-type', 'w-kg', 'rcv-name', 'rcv-wa'].forEach(id => document.getElementById(id).disabled = isT);
            document.getElementById('cust-name').name = isT ? 'nama_penumpang' : 'nama_pengirim';
            document.getElementById('cust-wa').name = isT ? 'nomor_wa' : 'nomor_wa_pengirim';
            
            // FIX BUG DI SINI: Hapus 'opt-travel' dari array biar tombolnya nggak ikutan ngilang!
            ['fleet-image-wrapper', 'seat-view', 'fleet-selector', 'travel-passenger', 'travel-baggage'].forEach(id => document.getElementById(id).style.display = isT ? 'block' : 'none');
            
            ['kargo-view', 'wa-penerima-container', 'nama-penerima-container'].forEach(id => document.getElementById(id).style.display = isT ? 'none' : 'block');
            document.getElementById('opt-travel').classList.toggle('active', isT); 
            document.getElementById('opt-kargo').classList.toggle('active', !isT);
            
            filterCities(); if(!isT) calcKargo();
        }

        /* LOGIKA MODAL PEMBAYARAN */
        function openPaymentModal() {
            if(!document.getElementById('input_jadwal_id').value) return; 
            
            const name = document.getElementById('cust-name').value.trim(); 
            const wa = document.getElementById('cust-wa').value.trim();
            
            // 1. Validasi Identitas Utama
            if(!name || !wa) { 
                Swal.fire({ icon: 'error', title: 'Data Belum Lengkap', text: 'Mohon isi Nama dan Nomor WhatsApp.'}); 
                return; 
            }
            
            // 2. Validasi Khusus TRAVEL
            if(currentService === 'travel') {
                if(selectedSeats.length < maxSeats) { 
                    Swal.fire({ icon: 'warning', title: 'Pilih Kursi', text: 'Silakan pilih '+maxSeats+' kursi di denah mobil.'}); 
                    return; 
                }
            } 
            // 3. Validasi Khusus KARGO (Ini yang nendang error tadi Bos!)
            else {
                const jenisBarang = document.getElementById('cargo-type').value.trim();
                const namaPenerima = document.getElementById('rcv-name').value.trim();
                const waPenerima = document.getElementById('rcv-wa').value.trim();

                if(!jenisBarang || !namaPenerima || !waPenerima) {
                    Swal.fire({ icon: 'error', title: 'Data Kargo Belum Lengkap', text: 'Mohon isi Jenis Barang, Nama Penerima, dan WA Penerima sebelum lanjut membayar!'}); 
                    return;
                }
            }

            document.getElementById('paymentModal').style.display = 'flex';
        }
        
        function closePaymentModal() { document.getElementById('paymentModal').style.display = 'none'; }

        function selectPayment(type) { 
            document.getElementById('step-method').style.display = 'none'; 
            
            document.getElementById('proof-bsi').disabled = true;
            document.getElementById('proof-dana').disabled = true;

            if (type === 'bsi') { 
                document.getElementById('step-bsi').style.display = 'block'; 
                document.getElementById('proof-bsi').disabled = false; 
            } 
            else if (type === 'dana') { 
                document.getElementById('step-dana').style.display = 'block'; 
                document.getElementById('proof-dana').disabled = false; 
            } 
            else { 
                document.getElementById('step-cash').style.display = 'block'; // Ubah ke step-cash
            } 
        }

        function resetPayment() { 
            document.getElementById('step-method').style.display = 'block'; 
            document.getElementById('step-bsi').style.display = 'none'; 
            document.getElementById('step-dana').style.display = 'none'; 
            document.getElementById('step-cash').style.display = 'none'; // Ubah ke step-cash
            
            document.getElementById('proof-bsi').value = '';
            document.getElementById('proof-dana').value = '';
        }

        function finishOrder(methodName) {
            let fileInput;
            
            // Tentukan input mana yang dipakai
            if (methodName === 'BSI') {
                fileInput = document.getElementById('proof-bsi');
            } else if (methodName === 'DANA') {
                fileInput = document.getElementById('proof-dana');
            }

            // Validasi Khusus Transfer (BSI / DANA)
            if (methodName === 'BSI' || methodName === 'DANA') {
                // 1. Cek apakah kosong
                if (fileInput.files.length === 0) { 
                    Swal.fire({ icon: 'warning', title: 'Bukti Kosong', text: `Silakan pilih foto bukti transfer ${methodName} Anda terlebih dahulu.` }); 
                    return; 
                }

                const file = fileInput.files[0];

                // 2. Cek Format File (Hanya izinkan gambar)
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({ icon: 'error', title: 'Format Salah', text: 'Hanya boleh mengunggah foto dengan format JPG, JPEG, PNG, atau WEBP.' });
                    fileInput.value = ''; // Reset input
                    return;
                }

                // 3. Cek Ukuran File (Maksimal 1 MB)
                const fileSizeMB = file.size / 1024 / 1024; 
                if (fileSizeMB > 1) {
                    Swal.fire({ icon: 'error', title: 'File Terlalu Besar', text: 'Ukuran foto maksimal adalah 1 MB. Silakan kompres atau pilih foto lain.' }); 
                    fileInput.value = ''; // Reset input
                    return;
                }
            }

            // Munculkan loading...
            Swal.fire({
                title: 'Memproses Pesanan...',
                text: 'Mohon tunggu, sedang mengupload bukti transfer.',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => { Swal.showLoading(); }
            });

            document.getElementById('input_metode_bayar').value = methodName;
            document.getElementById('formTransaksi').submit();
        }
        
        function salinTeks(text) { navigator.clipboard.writeText(text); Swal.fire({toast:true, position:'top-end', icon:'success', title:'Disalin!', showConfirmButton:false, timer:1500}); }
    </script>
</body>
</html>