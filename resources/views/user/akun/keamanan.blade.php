<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keamanan & Password - Buana Berlian</title>
    <link rel="icon" href="{{ asset('assets/img/LOGO.png') }}" type="image/png">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* --- ANIMASI --- */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-up { animation: fadeInUp 0.6s ease-out forwards; opacity: 0; }
        .delay-1 { animation-delay: 0.1s; }

        body { background-color: var(--bg-color); min-height: 100vh; }
        
        .account-layout {
            display: grid; grid-template-columns: 280px 1fr; gap: 40px;
            max-width: 1100px; margin: 120px auto 80px; padding: 0 5%;
            align-items: start;
        }

        /* --- SIDEBAR MENU --- */
        .sidebar-card {
            background: var(--card-bg); border: 1px solid var(--border-color);
            border-radius: 20px; padding: 30px 20px; text-align: center;
            box-shadow: 0 15px 40px rgba(0,0,0,0.03); position: sticky; top: 100px;
        }
        .profile-avatar {
            width: 100px; height: 100px; border-radius: 50%; object-fit: cover;
            border: 4px solid var(--bg-color); box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            margin: 0 auto 15px; background: #eee;
        }
        .profile-name { font-size: 1.2rem; font-weight: 800; color: var(--text-main); margin-bottom: 5px; }
        
        .status-badge {
            background: rgba(40, 167, 69, 0.1); color: #28a745; border: 1px solid rgba(40, 167, 69, 0.3);
            padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 800;
            display: inline-block; margin-bottom: 25px;
        }
        [data-theme="dark"] .status-badge { background: rgba(40, 167, 69, 0.15); }

        .sidebar-menu { display: flex; flex-direction: column; gap: 8px; text-align: left; }
        .sidebar-menu a {
            display: flex; align-items: center; gap: 12px; padding: 12px 20px;
            border-radius: 12px; color: var(--text-muted); text-decoration: none;
            font-weight: 600; transition: 0.3s;
        }
        .sidebar-menu a:hover { background: var(--bg-color); color: var(--p-color); }
        .sidebar-menu a.active { background: var(--p-color); color: white; box-shadow: 0 5px 15px rgba(72, 61, 139, 0.2); }
        [data-theme="dark"] .sidebar-menu a.active { background: var(--accent-gold); color: black; box-shadow: 0 5px 15px rgba(212, 175, 55, 0.2); }
        [data-theme="dark"] .sidebar-menu a:hover:not(.active) { background: #222; color: var(--accent-gold); }

        /* --- KONTEN UTAMA --- */
        .content-card {
            background: var(--card-bg); border: 1px solid var(--border-color);
            border-radius: 20px; padding: 40px; box-shadow: 0 15px 40px rgba(0,0,0,0.03);
        }
        .section-title { font-size: 1.5rem; font-weight: 800; color: var(--text-main); margin-bottom: 5px; }
        .section-desc { color: var(--text-muted); font-size: 0.95rem; margin-bottom: 30px; }

        .form-group { margin-bottom: 25px; position: relative; }
        .form-group label { display: block; font-size: 0.8rem; font-weight: 700; color: var(--text-muted); margin-bottom: 8px; text-transform: uppercase; }
        
        .form-control {
            width: 100%; padding: 14px 20px; background: var(--bg-color);
            border: 2px solid transparent; border-radius: 12px; color: var(--text-main);
            font-size: 0.95rem; font-weight: 600; outline: none; transition: 0.3s;
        }
        .form-control:focus { border-color: var(--p-color); box-shadow: 0 0 0 4px rgba(72, 61, 139, 0.1); }
        [data-theme="dark"] .form-control { border: 1px solid #333; }
        [data-theme="dark"] .form-control:focus { border-color: var(--accent-gold); box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1); }

        /* Icon Mata untuk Password */
        .toggle-password {
            position: absolute; right: 20px; top: 40px; color: var(--text-muted);
            cursor: pointer; font-size: 1.2rem; transition: 0.2s;
        }
        .toggle-password:hover { color: var(--p-color); }
        [data-theme="dark"] .toggle-password:hover { color: var(--accent-gold); }

        /* Kotak Info/Peringatan */
        .info-box {
            background: rgba(212, 175, 55, 0.1); border: 1px dashed var(--accent-gold);
            padding: 15px 20px; border-radius: 12px; display: flex; gap: 15px; align-items: flex-start;
            margin-bottom: 30px;
        }
        .info-box i { color: var(--accent-gold); font-size: 1.5rem; margin-top: -2px; }
        .info-box p { margin: 0; font-size: 0.85rem; color: var(--text-muted); line-height: 1.6; }

        .btn-save {
            background: linear-gradient(135deg, var(--p-color), #2a2355);
            color: white; padding: 14px 30px; border-radius: 12px; font-weight: 700;
            border: none; cursor: pointer; transition: 0.3s; margin-top: 10px;
            box-shadow: 0 10px 20px rgba(72, 61, 139, 0.2); font-size: 1rem; width: 100%;
        }
        .btn-save:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(72, 61, 139, 0.4); }
        [data-theme="dark"] .btn-save { background: var(--accent-gold); color: black; box-shadow: 0 10px 20px rgba(212, 175, 55, 0.2); }

        @media (max-width: 900px) {
            .account-layout { grid-template-columns: 1fr; margin-top: 100px; gap: 25px; }
            .sidebar-card { position: static; padding: 25px; }
            .content-card { padding: 30px 20px; }
        }
    </style>
</head>
<body data-theme="light">

    @include('user.partials.header')

    <div class="account-layout">
        
        <aside class="sidebar-card animate-up">
            <img src="{{ asset('public/assets/img/developer 2.jpg') }}" alt="Profile" class="profile-avatar">
            <h3 class="profile-name">Imran Darajati</h3>
            <div class="status-badge"><i class="bi bi-check-circle-fill"></i> Mahasiswa Terverifikasi</div>

            <div class="sidebar-menu">
                <a href="{{ url('/pusat-akun?logged_in=1') }}"><i class="bi bi-person-fill"></i> Informasi Profil</a>
                <a href="#" class="active"><i class="bi bi-shield-lock-fill"></i> Keamanan & Password</a>
                <hr style="border:none; border-top: 1px solid var(--border-color); margin: 10px 0;">
                <a href="{{ url('/') }}" style="color: #dc3545;"><i class="bi bi-box-arrow-right"></i> Keluar (Logout)</a>
            </div>
        </aside>

        <main class="content-card animate-up delay-1">
            <h2 class="section-title">Keamanan & Password</h2>
            <p class="section-desc">Pastikan akun Anda tetap aman dengan memperbarui password secara berkala.</p>

            <div class="info-box">
                <i class="bi bi-info-circle-fill"></i>
                <div>
                    <strong style="color: var(--text-main); font-size: 0.9rem; display: block; margin-bottom: 3px;">Tips Keamanan</strong>
                    <p>Gunakan kombinasi huruf, angka, dan simbol. Jangan gunakan password yang sama dengan akun sosial media atau email Anda.</p>
                </div>
            </div>

            <form action="#">
                <div class="form-group">
                    <label>Password Saat Ini</label>
                    <input type="password" class="form-control" id="old-pwd" placeholder="Masukkan password lama Anda" required>
                    <i class="bi bi-eye-slash toggle-password" onclick="toggleVisibility('old-pwd', this)"></i>
                </div>

                <hr style="border:none; border-top: 2px dashed var(--border-color); margin: 30px 0;">

                <div class="form-group">
                    <label>Password Baru</label>
                    <input type="password" class="form-control" id="new-pwd" placeholder="Minimal 8 karakter" required>
                    <i class="bi bi-eye-slash toggle-password" onclick="toggleVisibility('new-pwd', this)"></i>
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password Baru</label>
                    <input type="password" class="form-control" id="conf-pwd" placeholder="Ulangi password baru" required>
                    <i class="bi bi-eye-slash toggle-password" onclick="toggleVisibility('conf-pwd', this)"></i>
                </div>

                <div style="text-align: right;">
                    <button type="submit" class="btn-save">Perbarui Password</button>
                </div>
            </form>
        </main>

    </div>

    @include('user.partials.footer')

    <script>
        // Logika untuk fitur "Show/Hide Password" (Ikon Mata)
        function toggleVisibility(inputId, iconElement) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                iconElement.classList.replace("bi-eye-slash", "bi-eye");
            } else {
                input.type = "password";
                iconElement.classList.replace("bi-eye", "bi-eye-slash");
            }
        }
    </script>
</body>
</html>