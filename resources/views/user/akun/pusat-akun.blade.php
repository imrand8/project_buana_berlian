<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Akun - Buana Berlian</title>
    <link rel="icon" href="{{ asset('public/assets/img/LOGO.png') }}" type="image/png">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --p-color: #352877; 
            --p-hover: #261c55;
            --accent-gold: #d4af37;
            --bg-color: #f4f6f9; 
            --card-bg: #ffffff;
            --border-color: #dee2e6; 
            --text-main: #333333;
            --text-muted: #6c757d;
        }

        [data-theme="dark"] {
            --bg-color: #121212;
            --card-bg: #1a1a1a;
            --border-color: #333333;
            --text-main: #ffffff;
            --text-muted: #aaaaaa;
        }

        body { font-family: 'Poppins', sans-serif !important; background-color: var(--bg-color); min-height: 100vh; color: var(--text-main); overflow-x: hidden; }
        .page-content-wrapper { padding-top: 120px; padding-bottom: 80px; min-height: 100vh; box-sizing: border-box; }

        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: none; } }
        .animate-up { animation: fadeInUp 0.6s ease-out forwards; opacity: 0; }
        .delay-1 { animation-delay: 0.1s; }
        
        .account-layout { display: flex; align-items: flex-start; gap: 40px; max-width: 1100px; margin: 0 auto; padding: 0 5%; }

        /* --- SIDEBAR MENU & FOTO PROFIL --- */
        .sidebar-card { width: 280px; flex-shrink: 0; background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 20px; padding: 30px 20px; text-align: center; box-shadow: 0 15px 40px rgba(0,0,0,0.03); position: sticky; top: 110px; z-index: 10; }
        
        /* CSS Baru untuk Upload Foto Profil */
        .profile-upload { position: relative; width: 110px; height: 110px; margin: 0 auto 15px; }
        .profile-upload img { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 4px solid var(--bg-color); box-shadow: 0 10px 20px rgba(0,0,0,0.1); background: #eee; }
        .upload-btn {
            position: absolute; bottom: 0px; right: 0px; width: 35px; height: 35px; border-radius: 50%;
            background: var(--p-color); color: white; border: 3px solid var(--card-bg);
            display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.3s;
        }
        .upload-btn:hover { transform: scale(1.1); }
        [data-theme="dark"] .upload-btn { background: var(--accent-gold); color: black; }

        .delete-avatar-btn {
            position: absolute; bottom: 0px; left: 0px; width: 35px; height: 35px; border-radius: 50%;
            background: #dc3545; color: white; border: 3px solid var(--card-bg);
            display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.3s;
        }
        .delete-avatar-btn:hover { transform: scale(1.1); background: #b02a37; }

        .profile-name { font-size: 1.2rem; font-weight: 800; color: var(--text-main); margin-bottom: 5px; }
        .status-badge { background: rgba(40, 167, 69, 0.1); color: #28a745; border: 1px solid rgba(40, 167, 69, 0.3); padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 800; display: inline-block; margin-bottom: 25px; transition: 0.3s;}
        [data-theme="dark"] .status-badge { background: rgba(40, 167, 69, 0.15); }

        .sidebar-menu { display: flex; flex-direction: column; gap: 8px; text-align: left; }
        .sidebar-menu button, .sidebar-menu a { display: flex; align-items: center; gap: 12px; padding: 12px 20px; border-radius: 12px; color: var(--text-muted); text-decoration: none; font-weight: 600; transition: 0.3s; border: none; background: transparent; width: 100%; cursor: pointer; text-align: left; font-size: 0.95rem; }
        .sidebar-menu button:hover, .sidebar-menu a:hover { background: var(--bg-color); color: var(--p-color); }
        .sidebar-menu button.active { background: var(--p-color); color: white; box-shadow: 0 5px 15px rgba(72, 61, 139, 0.2); }
        [data-theme="dark"] .sidebar-menu button.active { background: var(--accent-gold); color: black; box-shadow: 0 5px 15px rgba(212, 175, 55, 0.2); }
        [data-theme="dark"] .sidebar-menu button:hover:not(.active), [data-theme="dark"] .sidebar-menu a:hover { background: #222; color: var(--accent-gold); }

        /* --- KONTEN UTAMA --- */
        .content-card { flex: 1; min-width: 0; background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 20px; padding: 40px; box-shadow: 0 15px 40px rgba(0,0,0,0.03); }
        .section-title { font-size: 1.5rem; font-weight: 800; color: var(--text-main); margin-bottom: 5px; }
        .section-desc { color: var(--text-muted); font-size: 0.95rem; margin-bottom: 30px; }

        .form-group { margin-bottom: 25px; }
        .form-group label { display: flex; align-items: center; gap: 8px; font-size: 0.8rem; font-weight: 700; color: var(--text-muted); margin-bottom: 8px; text-transform: uppercase; }
        .form-group label i { color: var(--p-color); font-size: 1rem; }
        [data-theme="dark"] .form-group label i { color: var(--accent-gold); }
        
        .form-control-custom { width: 100%; padding: 14px 20px; background: var(--bg-color); border: 2px solid transparent; border-radius: 12px; color: var(--text-main); font-size: 0.95rem; font-weight: 600; outline: none; transition: 0.3s; }
        select.form-control-custom { cursor: pointer; appearance: none; background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23888%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E"); background-repeat: no-repeat; background-position: right 1rem top 50%; background-size: 0.65rem auto; padding-right: 40px; }
        .form-control-custom:focus { border-color: var(--p-color); box-shadow: 0 0 0 4px rgba(72, 61, 139, 0.1); background: var(--card-bg); }
        [data-theme="dark"] .form-control-custom { border: 1px solid #333; background: #111; color: white; }
        [data-theme="dark"] .form-control-custom:focus { border-color: var(--accent-gold); box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1); background: #222; }
        .form-control-custom:disabled { background-color: rgba(0,0,0,0.05) !important; cursor: not-allowed; color: #6c757d !important; border: 1px dashed var(--border-color); }
        [data-theme="dark"] .form-control-custom:disabled { background-color: rgba(255,255,255,0.03) !important; border-color: #444 !important; }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; }

        .toggle-pwd { position: absolute; right: 18px; top: 50%; transform: translateY(-50%); color: var(--text-muted); cursor: pointer; font-size: 1.2rem; transition: 0.2s; padding: 5px;}
        .toggle-pwd:hover { color: var(--p-color); }
        [data-theme="dark"] .toggle-pwd:hover { color: var(--accent-gold); }
        .pe-custom { padding-right: 50px !important; }

        /* --- KOTAK KTM --- */
        #ktm-section { transition: all 0.3s ease; }
        .ktm-box { border: 2px dashed var(--p-color); border-radius: 16px; padding: 30px; text-align: center; background: rgba(72, 61, 139, 0.03); position: relative; margin-top: 10px; transition: 0.3s; display: none; }
        [data-theme="dark"] .ktm-box { border-color: var(--accent-gold); background: rgba(212, 175, 55, 0.05); }
        .ktm-preview { width: 100%; max-width: 250px; border-radius: 10px; margin-top: 15px; display: none; border: 2px solid var(--border-color); }
        
        .ktm-verified { background: rgba(40, 167, 69, 0.08); border: 1px solid rgba(40, 167, 69, 0.3); border-radius: 16px; padding: 25px; display: flex; align-items: center; gap: 20px; margin-top: 15px; }
        .btn-change-ktm { margin-left: auto; background: var(--bg-color); border: 1px solid var(--border-color); color: var(--text-main); font-weight: 700; font-size: 0.85rem; padding: 10px 20px; border-radius: 10px; cursor: pointer; transition: 0.2s; }
        .btn-change-ktm:hover { border-color: var(--p-color); color: var(--p-color); box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        [data-theme="dark"] .btn-change-ktm:hover { border-color: var(--accent-gold); color: var(--accent-gold); }

        .btn-save { background: linear-gradient(135deg, var(--p-color), #2a2355); color: white; padding: 14px 35px; border-radius: 12px; font-weight: 700; border: none; cursor: pointer; transition: 0.3s; margin-top: 30px; box-shadow: 0 10px 20px rgba(72, 61, 139, 0.2); font-size: 1rem; display: flex; align-items: center; gap: 8px; width: fit-content; margin-left: auto; }
        .btn-save:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(72, 61, 139, 0.4); }
        [data-theme="dark"] .btn-save { background: var(--accent-gold); color: black; box-shadow: 0 10px 20px rgba(212, 175, 55, 0.2); }

        .tab-content { display: none; animation: fadeIn 0.4s ease-out forwards; }
        .tab-content.active-tab { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }

        [data-theme="dark"] .swal2-popup { background: var(--card-bg) !important; color: var(--text-main) !important; border: 1px solid var(--border-color); }
        [data-theme="dark"] .swal2-title, [data-theme="dark"] .swal2-html-container { color: var(--text-main) !important; }

        /* --- STYLING UNTUK ALERT TOLAK KTM --- */
        .alert-ktm-tolak {
            border-radius: 12px; font-size: 0.85rem; border: 1px dashed #dc3545; background: rgba(220,53,69,0.05); margin-bottom: 20px;
        }
        .alert-ktm-tolak .text-danger { color: #dc3545 !important; }
        .alert-ktm-tolak .text-muted { color: #6c757d !important; }
        
        [data-theme="dark"] .alert-ktm-tolak { background: rgba(220,53,69,0.15); border-color: rgba(220,53,69,0.4); }
        [data-theme="dark"] .alert-ktm-tolak .text-danger { color: #ff6b81 !important; } /* Warna merah lebih terang untuk dark mode */
        [data-theme="dark"] .alert-ktm-tolak .text-muted { color: #ced4da !important; } /* Warna putih keabuan untuk keterangan */

        @media (max-width: 900px) {
            .page-content-wrapper { padding-top: 100px; }
            .account-layout { flex-direction: column; align-items: stretch; gap: 25px; }
            .sidebar-card { width: 100%; position: static; padding: 25px; }
            .grid-2 { grid-template-columns: 1fr; gap: 15px; }
            .content-card { padding: 30px 20px; }
            .ktm-verified { flex-direction: column; text-align: center; }
            .btn-change-ktm { margin: 10px auto 0; }
            .btn-save { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body data-theme="light">

    @include('user.partials.header')

    <div class="page-content-wrapper">
        <div class="account-layout">
            
            <aside class="sidebar-card animate-up">
                <div class="profile-upload">
                    @php
                        $words = explode(' ', Auth::user()->name);
                        $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                        $colors = ['bg-primary', 'bg-success', 'bg-danger', 'bg-warning text-dark', 'bg-info text-dark', 'bg-secondary'];
                        $avatarColor = $colors[Auth::user()->id % count($colors)];
                    @endphp

                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/app/public/' . Auth::user()->avatar) }}" alt="Profile" id="preview-avatar">
                    @else
                        <div class="{{ $avatarColor }}" id="preview-avatar-initial" style="width: 100%; height: 100%; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: 800; color: white; border: 4px solid var(--bg-color); box-shadow: 0 10px 20px rgba(0,0,0,0.1);">{{ $initials }}</div>
                        <img src="" alt="Profile" id="preview-avatar" style="display: none;">
                    @endif

                    <button type="button" class="upload-btn border-0" title="Ganti Foto" onclick="document.getElementById('avatar-upload').click()">
                        <i class="bi bi-camera-fill"></i>
                    </button>

                    @if(Auth::user()->avatar)
                        <button type="button" class="delete-avatar-btn" title="Hapus Foto" onclick="hapusFotoProfil()">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    @endif
                </div>

                <h3 class="profile-name">{{ Auth::user()->name }}</h3>
                
                @if(Auth::user()->status_mahasiswa === 'terverifikasi')
                    <div class="status-badge" id="sidebar-status-badge"><i class="bi bi-check-circle-fill"></i> Mahasiswa Terverifikasi</div>
                @elseif(Auth::user()->status_mahasiswa === 'menunggu_verifikasi')
                    <div class="status-badge" id="sidebar-status-badge" style="background: rgba(255,193,7,0.1); color: #d39e00; border-color: rgba(255,193,7,0.3);"><i class="bi bi-hourglass-split"></i> Menunggu Verifikasi</div>
                @elseif(Auth::user()->alasan_tolak_ktm)
                    <div class="status-badge" id="sidebar-status-badge" style="background: rgba(220,53,69,0.1); color: #dc3545; border-color: rgba(220,53,69,0.3);"><i class="bi bi-x-circle-fill"></i> KTM Ditolak</div>
                @else
                    <div class="status-badge" id="sidebar-status-badge" style="display: none;"><i class="bi bi-person"></i> Pelanggan Reguler</div>
                @endif

                <div class="sidebar-menu">
                    <button type="button" id="btn-tab-profil" class="active" onclick="switchTab('profil')">
                        <i class="bi bi-person-vcard"></i> Informasi Profil
                    </button>
                    <button type="button" id="btn-tab-keamanan" onclick="switchTab('keamanan')">
                        <i class="bi bi-shield-lock"></i> Keamanan & Password
                    </button>
                    <hr style="border:none; border-top: 1px solid var(--border-color); margin: 15px 0;">
                    
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" style="color: #dc3545; background:transparent; border:none; width:100%; text-align:left; padding: 12px 20px; font-weight:600;"><i class="bi bi-box-arrow-right"></i> Keluar (Logout)</button>
                    </form>
                </div>
            </aside>

            <main class="content-card animate-up delay-1">
                
                <div id="tab-profil" class="tab-content active-tab">
                    <h2 class="section-title">Detail Akun</h2>
                    <p class="section-desc">Kelola informasi pribadi dan verifikasi status mahasiswa Anda.</p>

                    <form id="form-profil" action="{{ route('user.profil.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="remove_avatar" id="input-remove-avatar" value="0">

                        <div class="grid-2">
                            
                        <input id="avatar-upload" type="file" name="avatar" style="display: none;" accept="image/jpeg, image/png, image/jpg, image/webp" onchange="validateAvatar(this)">

                            <div class="form-group">
                                <label><i class="bi bi-person-lines-fill"></i> Nama Lengkap (Sesuai KTP)</label>
                                <input type="text" name="name" class="form-control-custom" value="{{ Auth::user()->name }}" required>
                            </div>
                            <div class="form-group">
                                <label><i class="bi bi-whatsapp"></i> Nomor WhatsApp</label>
                                <input type="tel" name="phone" class="form-control-custom" value="{{ Auth::user()->phone }}" required>
                            </div>
                            <div class="form-group" style="margin-bottom: 30px;">
                                <label><i class="bi bi-envelope-at-fill"></i> Email Terdaftar</label>
                                <input type="email" class="form-control-custom" value="{{ Auth::user()->email }}" disabled title="Email sebagai identitas unik tidak dapat diubah">
                            </div>
                            <div class="form-group" style="margin-bottom: 30px;">
                                <label><i class="bi bi-briefcase-fill"></i> Pekerjaan</label>
                                <select name="pekerjaan" class="form-control-custom" id="select-pekerjaan">
                                    <option value="umum" {{ Auth::user()->status_mahasiswa == 'reguler' && empty(Auth::user()->alasan_tolak_ktm) ? 'selected' : '' }}>Umum (Pekerja / Lainnya)</option>
                                    <option value="mahasiswa" {{ Auth::user()->status_mahasiswa != 'reguler' || !empty(Auth::user()->alasan_tolak_ktm) ? 'selected' : '' }}>Mahasiswa (S1/D3/D4)</option>
                                </select>
                            </div>
                        </div>

                        <div id="ktm-section" style="display: {{ Auth::user()->status_mahasiswa != 'reguler' || !empty(Auth::user()->alasan_tolak_ktm) ? 'block' : 'none' }}">
                            <hr style="border:none; border-top: 2px dashed var(--border-color); margin: 20px 0 30px;">
                            
                            @if(Auth::user()->alasan_tolak_ktm)
                                <div class="alert alert-ktm-tolak d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle-fill fs-3 me-3 text-danger"></i>
                                    <div>
                                        <strong class="text-danger">KTM Ditolak!</strong><br>
                                        <span class="text-muted">Alasan: <b>{{ Auth::user()->alasan_tolak_ktm }}</b>. Silakan unggah ulang foto yang sesuai.</span>
                                    </div>
                                </div>
                            @endif

                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                                <div>
                                    <h3 style="font-size: 1.2rem; font-weight: 800; color: var(--text-main); margin: 0;">Kartu Tanda Mahasiswa (KTM)</h3>
                                </div>
                            </div>

                            @if(Auth::user()->status_mahasiswa === 'terverifikasi')
                                <div class="ktm-verified" id="ktm-info-verified">
                                    <i class="bi bi-shield-check" style="font-size: 2.8rem; color: #28a745;"></i>
                                    <div>
                                        <h4 style="color: #28a745; font-weight: 800; margin: 0 0 5px 0;">KTM Valid & Terverifikasi</h4>
                                        <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0;">Anda berhak mendapatkan diskon mahasiswa.</p>
                                    </div>
                                    <button type="button" class="btn-change-ktm" onclick="showUploadForm()">Perbarui KTM</button>
                                </div>
                            @endif

                            <div class="ktm-box" id="ktm-upload-form" style="display: {{ Auth::user()->status_mahasiswa != 'terverifikasi' ? 'block' : 'none' }}">
                                <input type="file" name="ktm" id="ktm-upload" accept="image/jpeg, image/png, image/jpg, image/webp" style="position: absolute; inset:0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                                <div id="upload-state">
                                    <i class="bi bi-cloud-arrow-up" style="font-size: 3rem; color: var(--p-color); margin-bottom: 10px; display: block;"></i>
                                    <h4 style="color: var(--text-main); font-weight: 700; margin: 0 0 5px;">Klik untuk unggah file KTM Anda</h4>
                                </div>
                                <img id="ktm-preview" class="ktm-preview" src="{{ Auth::user()->ktm_path ? asset('storage/app/public/' . Auth::user()->ktm_path) : '' }}" style="display: {{ Auth::user()->ktm_path ? 'inline-block' : 'none' }}">
                            </div>
                        </div>

                        <div style="display: flex;">
                            <button type="submit" class="btn-save"><i class="bi bi-floppy-fill"></i> Simpan Profil</button>
                        </div>
                    </form>
                </div>

                <div id="tab-keamanan" class="tab-content {{ session('tab') == 'keamanan' ? 'active-tab' : '' }}">
                    <h2 class="section-title">Keamanan & Password</h2>
                    
                    @if($errors->has('old_password') || $errors->has('password'))
                        <div class="alert alert-danger" style="border-radius: 12px; font-size: 0.85rem;">
                            {{ $errors->first('old_password') }} {{ $errors->first('password') }}
                        </div>
                    @endif

                    <form action="{{ route('user.password.update') }}" method="POST">
                        @csrf
                        <div class="form-group" style="max-width: 450px;">
                            <label><i class="bi bi-key-fill"></i> Password Lama</label>
                            <div style="position: relative;">
                                <input type="password" name="old_password" id="old-pwd" class="form-control-custom pe-custom" required>
                                <i class="bi bi-eye-slash toggle-pwd" onclick="togglePassword('old-pwd', this)"></i>
                            </div>
                        </div>

                        <hr style="border:none; border-top: 2px dashed var(--border-color); margin: 35px 0; max-width: 550px;">

                        <div class="form-group" style="max-width: 450px;">
                            <label><i class="bi bi-shield-lock-fill"></i> Password Baru</label>
                            <div style="position: relative;">
                                <input type="password" name="password" id="new-pwd" class="form-control-custom pe-custom" required minlength="8">
                                <i class="bi bi-eye-slash toggle-pwd" onclick="togglePassword('new-pwd', this)"></i>
                            </div>
                        </div>

                        <div class="form-group" style="max-width: 450px;">
                            <label><i class="bi bi-shield-check"></i> Konfirmasi Password Baru</label>
                            <div style="position: relative;">
                                <input type="password" name="password_confirmation" id="confirm-pwd" class="form-control-custom pe-custom" required minlength="8">
                                <i class="bi bi-eye-slash toggle-pwd" onclick="togglePassword('confirm-pwd', this)"></i>
                            </div>
                        </div>

                        <div style="display: flex;">
                            <button type="submit" class="btn-save"><i class="bi bi-shield-shaded"></i> Perbarui Password</button>
                        </div>
                    </form>

                    <hr style="border:none; border-top: 2px dashed var(--border-color); margin: 40px 0 20px; max-width: 550px;">
                    <div>
                        <h5 style="color: #dc3545; font-size: 1.1rem; font-weight: 700; margin-bottom: 5px;">Hapus Akun Permanen</h5>
                        <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 15px; max-width: 500px;">Tindakan ini tidak dapat dibatalkan. Semua data pesanan dan tiket Anda akan terhapus selamanya.</p>
                        
                        <form id="form-hapus-akun" action="{{ route('user.akun.hapus') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="konfirmasiHapus()" style="background: transparent; border: 1px solid #dc3545; color: #dc3545; padding: 10px 20px; border-radius: 10px; font-weight: 600; font-size: 0.9rem; transition: 0.3s;" onmouseover="this.style.background='#dc3545'; this.style.color='#fff';" onmouseout="this.style.background='transparent'; this.style.color='#dc3545';"><i class="bi bi-trash3-fill"></i> Ya, Hapus Akun Saya</button>
                        </form>
                    </div>
                </div>

            </main>
        </div>
    </div>

    @include('user.partials.footer')
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function konfirmasiHapus() {
            const theme = getSwalTheme();
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Semua data akan hilang permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: theme.btnColor,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: theme.background,
                color: theme.color
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-hapus-akun').submit();
                }
            });
        }


        function getSwalTheme() {
            const isDark = document.body.getAttribute('data-theme') === 'dark';
            return { background: isDark ? 'var(--card-bg)' : '#ffffff', color: isDark ? 'var(--text-main)' : '#000000', btnColor: isDark ? 'var(--accent-gold)' : '#352877' };
        }

        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active-tab'));
            document.querySelectorAll('.sidebar-menu button').forEach(btn => btn.classList.remove('active'));
            document.getElementById('tab-' + tabName).classList.add('active-tab');
            document.getElementById('btn-tab-' + tabName).classList.add('active');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const theme = getSwalTheme();
            
            @if(session('success'))
                Swal.fire({ title: 'Berhasil!', text: "{{ session('success') }}", icon: 'success', background: theme.background, color: theme.color, confirmButtonColor: theme.btnColor });
            @endif

            @if(session('success_password'))
                switchTab('keamanan');
                Swal.fire({ title: 'Keamanan Diperbarui!', text: "{{ session('success_password') }}", icon: 'success', background: theme.background, color: theme.color, confirmButtonColor: theme.btnColor });
            @endif
            
            @if(session('tab') == 'keamanan')
                switchTab('keamanan');
            @endif
        });

        // --- Logika Dropdown Mahasiswa ---
        const selectPekerjaan = document.getElementById('select-pekerjaan');
        const ktmSection = document.getElementById('ktm-section');
        const badgeSidebar = document.getElementById('sidebar-status-badge');

        selectPekerjaan.addEventListener('change', function() {
            if (this.value === 'mahasiswa') {
                ktmSection.style.display = 'block';
                if(badgeSidebar) badgeSidebar.style.display = 'inline-block';
            } else {
                ktmSection.style.display = 'none';
                if(badgeSidebar) badgeSidebar.style.display = 'none';
            }
        });

        // --- VALIDASI & PREVIEW FOTO PROFIL (AVATAR) ---
        function validateAvatar(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const theme = getSwalTheme();

                // 1. Validasi Format
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({ icon: 'error', title: 'Format Salah', text: 'Hanya format JPG, PNG, atau WEBP.', background: theme.background, color: theme.color, confirmButtonColor: theme.btnColor });
                    input.value = ''; return;
                }

                // 2. Validasi Ukuran 1MB
                if (file.size / 1024 / 1024 > 1) {
                    Swal.fire({ icon: 'error', title: 'File Terlalu Besar', text: 'Maksimal foto profil 1 MB.', background: theme.background, color: theme.color, confirmButtonColor: theme.btnColor });
                    input.value = ''; return;
                }

                // 3. Tampilkan Preview
                document.getElementById('preview-avatar').src = window.URL.createObjectURL(file);
                document.getElementById('preview-avatar').style.display = 'block';
                if(document.getElementById('preview-avatar-initial')) { document.getElementById('preview-avatar-initial').style.display = 'none'; }
            }
        }

        // --- VALIDASI & PREVIEW KTM ---
        function showUploadForm() {
            document.getElementById('ktm-info-verified').style.display = 'none';
            document.getElementById('ktm-upload-form').style.display = 'block';
        }

        const ktmInput = document.getElementById('ktm-upload');
        const ktmPreview = document.getElementById('ktm-preview');
        const uploadState = document.getElementById('upload-state');

        if(ktmInput) {
            ktmInput.addEventListener('change', function(e) {
                const file = this.files[0];
                if (file) {
                    const theme = getSwalTheme();

                    // 1. Validasi Format
                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                    if (!allowedTypes.includes(file.type)) {
                        Swal.fire({ icon: 'error', title: 'Format Salah', text: 'Hanya format JPG, PNG, atau WEBP.', background: theme.background, color: theme.color, confirmButtonColor: theme.btnColor });
                        this.value = ''; return;
                    }

                    // 2. Validasi Ukuran 1MB
                    if (file.size / 1024 / 1024 > 1) {
                        Swal.fire({ icon: 'error', title: 'File Terlalu Besar', text: 'Maksimal foto KTM 1 MB.', background: theme.background, color: theme.color, confirmButtonColor: theme.btnColor });
                        this.value = ''; return;
                    }

                    // 3. Tampilkan Preview
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        uploadState.style.display = 'none'; 
                        ktmPreview.style.display = 'inline-block'; 
                        ktmPreview.src = event.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        // --- Toggle Password ---
        function togglePassword(inputId, iconEl) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                iconEl.classList.remove("bi-eye-slash");
                iconEl.classList.add("bi-eye");
                iconEl.style.color = "var(--p-color)";
            } else {
                input.type = "password";
                iconEl.classList.remove("bi-eye");
                iconEl.classList.add("bi-eye-slash");
                iconEl.style.color = "var(--text-muted)";
            }
        }

        function hapusFotoProfil() {
            const theme = getSwalTheme();
            Swal.fire({
                title: 'Hapus Foto Profil?',
                text: "Foto akan dihapus permanen dan kembali ke inisial nama Anda.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: theme.btnColor,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: theme.background,
                color: theme.color
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('input-remove-avatar').value = '1';
                    document.getElementById('form-profil').submit(); // Otomatis simpan
                }
            });
        }
    </script>
</body>
</html>