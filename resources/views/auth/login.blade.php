<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Daftar - Buana Berlian</title>
    <link rel="icon" href="{{ asset('public/assets/img/LOGO.png') }}" type="image/png">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    </script>

    <style>
        :root {
            --p-color: #483d8b;
            --accent-gold: #d4af37;
            --card-bg: #ffffff;
            --text-main: #333333;
            --text-muted: #777777;
            --input-bg: #f4f6f9;
        }

        [data-theme="dark"] {
            --card-bg: #1e1e1e;
            --text-main: #ffffff;
            --text-muted: #aaaaaa;
            --input-bg: #2a2a2a;
            --p-color: #d4af37;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0; padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center; justify-content: center;
            background: url('{{ asset("public/assets/img/login.jpg") }}') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            overflow-x: hidden;
        }

        /* Overlay putih kabur (Frosted Glass) */
        body::before {
            content: "";
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(12px);
            z-index: 0;
        }
        [data-theme="dark"] body::before {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(15px);
        }

        .nav-tools {
            position: absolute; top: 25px; width: 100%; padding: 0 40px;
            display: flex; justify-content: space-between; z-index: 10;
        }
        .tool-btn {
            background: var(--card-bg); border: none; padding: 8px 18px; border-radius: 50px;
            font-weight: 600; text-decoration: none; color: var(--text-main); 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 8px; transition: 0.3s;
        }
        .tool-btn:hover { transform: translateY(-2px); color: var(--p-color); }

        /* CARD FIXED SIZE & 50:50 PROPORTION */
        .auth-card {
            position: relative; z-index: 1;
            display: flex; width: 1000px; height: 620px; 
            background: var(--card-bg); border-radius: 40px; 
            padding: 20px;
            box-shadow: 0 40px 100px rgba(0,0,0,0.1);
            align-items: stretch;
            transition: background 0.4s ease;
        }

        /* BANNER SIDE (KIRI) */
        .auth-banner {
            flex: 1; /* Setengah Lebar */
            position: relative; border-radius: 30px;
            background: linear-gradient(rgba(0,0,0,0.1), rgba(0,0,0,0.7)), url('{{ asset("public/assets/img/login.jpg") }}');
            background-size: cover; background-position: center;
            color: white; padding: 40px;
            display: flex; flex-direction: column; justify-content: space-between;
            overflow: hidden;
        }

        /* Logo dengan drop-shadow agar tidak polos */
        .banner-logo { 
            width: 140px; 
            filter: brightness(0) invert(1) drop-shadow(0 5px 15px rgba(0,0,0,0.6)); 
        }

        /* CONTENT SIDE (KANAN) */
        .auth-content { 
            flex: 1; /* Setengah Lebar */
            padding: 20px 45px; 
            display: flex; 
            flex-direction: column; 
            justify-content: center;
            position: relative;
        }

        .promo-badge {
            background: rgba(212, 175, 55, 0.2); backdrop-filter: blur(5px);
            border: 1px solid var(--accent-gold); color: var(--accent-gold);
            padding: 6px 14px; border-radius: 50px; display: inline-flex;
            align-items: center; gap: 6px; font-weight: 700; font-size: 0.7rem; margin-bottom: 15px;
        }
        .banner-footer h2 { font-size: 1.8rem; font-weight: 800; line-height: 1.2; margin-bottom: 12px; }
        .banner-footer p { font-size: 0.85rem; opacity: 0.85; line-height: 1.5; margin: 0; }

        .auth-header h3 { font-size: 1.9rem; font-weight: 800; color: var(--text-main); margin-bottom: 5px; }
        .auth-header p { color: var(--text-muted); font-size: 0.85rem; margin-bottom: 25px; }

        .form-label-custom { 
            font-size: 0.7rem; font-weight: 800; color: var(--text-muted); 
            text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 6px; display: block;
        }
        
        .password-wrapper { position: relative; }
        .password-toggle {
            position: absolute; right: 15px; top: 50%; transform: translateY(-50%);
            cursor: pointer; color: var(--text-muted); font-size: 1.1rem;
        }

        .form-control-custom {
            width: 100%; padding: 13px 18px; background: var(--input-bg);
            border: 1px solid transparent; border-radius: 12px;
            color: var(--text-main); font-weight: 500; transition: 0.3s; font-size: 0.9rem;
        }
        .form-control-custom:focus { 
            background: var(--card-bg); border-color: var(--p-color); 
            box-shadow: 0 0 0 4px rgba(72, 61, 139, 0.1); outline: none; 
        }

        .ktm-upload {
            background: var(--input-bg); border: 1px dashed #ccc; border-radius: 12px;
            padding: 15px; text-align: center; cursor: pointer; transition: 0.3s;
            margin-top: 5px; display: flex; flex-direction: column; align-items: center;
        }
        .ktm-upload:hover { border-color: var(--p-color); background: rgba(72, 61, 139, 0.05); }
        .ktm-upload i { font-size: 1.3rem; color: var(--p-color); margin-bottom: 4px; }
        .ktm-upload span { font-size: 0.75rem; font-weight: 500; color: var(--text-muted); }

        .btn-auth {
            width: 100%; padding: 13px; border-radius: 12px; border: none;
            background: var(--p-color); color: white; font-weight: 700;
            font-size: 1rem; margin-top: 20px; transition: 0.3s;
        }
        .btn-auth:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(72, 61, 139, 0.2); }
        [data-theme="dark"] .btn-auth { background: var(--accent-gold); color: black; }

        .switch-text { text-align: center; margin-top: 20px; font-size: 0.85rem; color: var(--text-muted); }
        .switch-text a { color: var(--p-color); font-weight: 700; text-decoration: none; cursor: pointer; }
        [data-theme="dark"] .switch-text a { color: var(--accent-gold); }

        .form-view { display: none; }
        .form-view.active { display: block; animation: fadeIn 0.4s ease; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

/* --- GELEMBUNG CHAT PROMO MAHASISWA --- */
        .chat-bubble-promo {
            position: absolute;
            top: -45px;   /* Dorong ke atas biar keluar dari kotak dan gak nutupin teks */
            right: 0px;   /* Geser mentok ke kanan */
            background: linear-gradient(135deg, var(--p-color), #6c5ce7);
            color: white;
            padding: 12px 18px;
            border-radius: 20px 20px 0px 20px;
            font-size: 0.75rem;
            font-weight: 700;
            box-shadow: 0 10px 25px rgba(72, 61, 139, 0.3);
            z-index: 50;
            display: flex;
            align-items: center;
            gap: 10px;
            max-width: 250px;
            line-height: 1.4;
            animation: floatBubble 3s ease-in-out infinite;
        }

        /* Ekor Gelembung */
        .chat-bubble-promo::after {
            content: '';
            position: absolute;
            bottom: -10px;
            right: 20px; /* Sesuaikan posisi ekornya */
            border-width: 10px 15px 0 0;
            border-style: solid;
            border-color: #6c5ce7 transparent transparent transparent;
        }

        /* Dark Mode Gelembung */
        [data-theme="dark"] .chat-bubble-promo {
            background: linear-gradient(135deg, var(--accent-gold), #b5952f);
            color: #000;
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.3);
        }
        [data-theme="dark"] .chat-bubble-promo::after {
            border-color: #b5952f transparent transparent transparent;
        }

        /* Animasi Mengambang */
        @keyframes floatBubble {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-6px); }
            100% { transform: translateY(0px); }
        }

        /* MOBILE RESPONSIVE */
        @media (max-width: 900px) {
            .auth-card { 
                width: 92%; 
                height: auto; 
                flex-direction: column; 
                padding: 20px; 
                border-radius: 30px; 
                margin-top: 85px; /* FIX: Dorong kotak ke bawah biar gak nabrak tombol Beranda */
                margin-bottom: 30px;
            }
            .auth-banner { display: none; } 
            .auth-content { padding: 10px 0; position: relative; }
            .nav-tools { padding: 0 20px; top: 20px; }
            
            /* Posisi Gelembung di Mobile */
            .chat-bubble-promo {
                position: relative;
                top: 0; right: 0;
                margin-top: 5px;
                margin-bottom: 25px; /* FIX: Beri jarak antara gelembung dan teks "Buat Akun Baru" */
                border-radius: 20px 20px 20px 0px; 
                max-width: 100%;
                width: fit-content;
            }
            .chat-bubble-promo::after {
                bottom: -10px; left: 0; right: auto;
                border-width: 10px 0 0 15px;
            }
        }
    </style>
</head>
<body>

    <div class="nav-tools">
        <a href="{{ url('/') }}" class="tool-btn"><i class="bi bi-arrow-left"></i> Beranda</a>
        <button class="tool-btn" id="theme-toggle"><i class="bi bi-moon-stars-fill"></i></button>
    </div>

    <div class="auth-card">
        <div class="auth-banner">
            <img src="{{ asset('public/assets/img/LOGO.png') }}" alt="Logo" class="banner-logo">
            <div class="banner-footer">
                <h2>Perjalanan Nyaman, Harga Aman di Kantong.</h2>
                <p>Daftar sekarang dan nikmati kemudahan booking travel reguler & kargo kilat. Khusus Mahasiswa, dapatkan potongan harga spesial!</p>
            </div>
        </div>

        <div class="auth-content">
            
            <div class="chat-bubble-promo">
                <div style="background: rgba(255,255,255,0.2); width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="bi bi-mortarboard-fill fs-4"></i>
                </div>
                <span>Psst! Khusus Mahasiswa ada diskon tiket <strong>Rp 10.000</strong> lho! Yuk daftar! </span>
            </div>

            @if(session('success'))
                <div class="alert alert-success text-center mb-4" style="font-size: 0.85rem; border-radius: 12px; font-weight: 600;">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger" style="font-size: 0.8rem; border-radius: 10px; padding: 10px;">
                    <ul class="mb-0 px-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div id="login-view" class="form-view active">
                <div class="auth-header mb-3">
                    <h3>Selamat Datang!</h3>
                    <p class="mb-1">Silakan masuk untuk melanjutkan pesanan Anda.</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label-custom">Alamat Email</label>
                        <input type="email" name="email" class="form-control-custom" value="{{ old('email') }}" placeholder="Masukkan email..." required autofocus>
                    </div>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label-custom mb-0">Kata Sandi</label>
                            
                            <!-- LINK LUPA PASSWORD VIA WA -->
                            <a href="https://wa.me/6281803444854?text=Halo%20Admin%2C%20saya%20lupa%20password%20akun%20Buana%20Berlian%20Travel.%20Mohon%20bantuannya%20untuk%20reset%20password%20akun%20dengan%20email%3A%20..." 
                               target="_blank" 
                               style="font-size: 0.75rem; font-weight: 700; color: var(--p-color); text-decoration: none; transition: 0.3s;">
                               Lupa Password?
                            </a>
                        </div>
                        
                        <div class="password-wrapper">
                            <input type="password" name="password" id="login-pass" class="form-control-custom" placeholder="Masukkan password..." required>
                            <i class="bi bi-eye-slash password-toggle" onclick="togglePass('login-pass', this)"></i>
                        </div>
                    </div>
                    <button type="submit" class="btn-auth">Masuk Sekarang</button>
                </form>

                <div class="switch-text">
                    Belum punya akun? <a onclick="toggleAuth('register')">Daftar di sini</a>
                </div>
            </div>

            <div id="register-view" class="form-view">
                <div class="auth-header mb-3">
                    <h3>Buat Akun Baru</h3>
                    <p class="mb-1">Bergabunglah dengan Buana Berlian hari ini.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row g-2 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label-custom">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control-custom py-2" placeholder="Sesuai KTP" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label-custom">No. WhatsApp</label>
                            <input type="tel" name="phone" class="form-control-custom py-2" placeholder="0812..." value="{{ old('phone') }}" required>
                        </div>
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label-custom">Alamat Email</label>
                            <input type="email" name="email" class="form-control-custom py-2" placeholder="email@contoh.com" value="{{ old('email') }}" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label-custom">Kata Sandi</label>
                            <div class="password-wrapper">                            
                                <input type="password" name="password" id="reg-pass" class="form-control-custom py-2" placeholder="Min. 8 Karakter" required minlength="8">
                                <i class="bi bi-eye-slash password-toggle" onclick="togglePass('reg-pass', this)" style="font-size: 1rem;"></i>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-end mb-1">
                            <label class="form-label-custom mb-0">Kartu Tanda Mahasiswa</label>
                            <span class="badge bg-warning text-dark" style="font-size: 0.6rem;">OPSIONAL UNTUK DISKON</span>
                        </div>
                        
                        <input type="file" name="ktm" id="ktmInput" class="d-none" accept="image/jpeg, image/png, image/jpg" onchange="updateFileName(this)">
                        
                        <label for="ktmInput" class="ktm-upload w-100 m-0 py-3" id="ktmLabel">
                            <i class="bi bi-cloud-arrow-up fs-4 mb-1"></i>
                            <span id="ktmText" class="d-block fw-bold" style="font-size: 0.8rem;">Klik untuk unggah foto KTM</span>
                            <small class="text-muted" style="font-size: 0.65rem;">Format .JPG atau .PNG (Maks 2MB)</small>
                        </label>
                    </div>

                    <button type="submit" class="btn-auth mt-2">Daftar Akun Sekarang</button>
                </form>

                <div class="switch-text mt-3">
                    Sudah punya akun? <a onclick="toggleAuth('login')">Masuk di sini</a>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Toggle Antara Login dan Register
        function toggleAuth(view) {
            document.querySelectorAll('.form-view').forEach(v => v.classList.remove('active'));
            document.getElementById(view + '-view').classList.add('active');
        }

        // Lihat Password
        function togglePass(id, icon) {
            const input = document.getElementById(id);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace("bi-eye-slash", "bi-eye");
            } else {
                input.type = "password";
                icon.classList.replace("bi-eye", "bi-eye-slash");
            }
        }

        // Script Baru untuk efek UI Upload File KTM
        function updateFileName(input) {
            const labelText = document.getElementById('ktmText');
            const labelBox = document.getElementById('ktmLabel');
            
            if (input.files && input.files[0]) {
                labelText.innerHTML = `<span class="text-success"><i class="bi bi-check-circle-fill me-1"></i> ${input.files[0].name}</span>`;
                labelBox.style.borderColor = '#22c55e';
                labelBox.style.background = 'rgba(34, 197, 94, 0.05)';
            } else {
                labelText.innerHTML = 'Klik untuk unggah foto KTM';
                labelBox.style.borderColor = '#ccc';
                labelBox.style.background = 'var(--input-bg)';
            }
        }

        // Toggle Dark Mode
        const themeBtn = document.getElementById('theme-toggle');
        const iconTheme = themeBtn.querySelector('i');
        const root = document.documentElement;

        themeBtn.addEventListener('click', () => {
            if (root.getAttribute('data-theme') === 'dark') {
                root.removeAttribute('data-theme');
                localStorage.setItem('theme', 'light');
                iconTheme.classList.replace('bi-sun-fill', 'bi-moon-stars-fill');
            } else {
                root.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
                iconTheme.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
            }
        });
    </script>
</body>
</html>