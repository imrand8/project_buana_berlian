<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - Buana Berlian</title>
    <link rel="icon" href="{{ asset('public/assets/img/LOGO.png') }}" type="image/png">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
            --p-color: #d4af37;
            --p-hover: #e5c158;
        }

        body { font-family: 'Poppins', sans-serif !important; background-color: var(--bg-color); color: var(--text-main); transition: 0.3s; overflow-x: hidden; }

        /* --- ANIMASI --- */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .animate-up { animation: fadeInUp 0.8s cubic-bezier(0.165, 0.84, 0.44, 1) forwards; opacity: 0; }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.3s; }

        /* --- HERO SECTION --- */
        .about-hero {
            position: relative; background: linear-gradient(135deg, var(--p-color), #241d49);
            padding: 150px 20px 100px; color: white; text-align: center;
            border-radius: 0 0 50% 50% / 30px; margin-bottom: 70px;
            box-shadow: 0 15px 40px rgba(72, 61, 139, 0.25);
        }
        .about-title { font-size: 3rem; font-weight: 800; margin-bottom: 15px; position: relative; z-index: 2; }
        .about-subtitle { font-size: 1.15rem; opacity: 0.9; max-width: 650px; margin: 0 auto; position: relative; z-index: 2; }

        .content-container { max-width: 1200px; margin: 0 auto; padding: 0 25px 100px; }
        .section-header { text-align: center; margin-bottom: 40px; }
        .section-tag { color: var(--p-color); font-weight: 800; text-transform: uppercase; letter-spacing: 2px; font-size: 0.85rem; display: inline-block; padding: 8px 18px; background: rgba(72, 61, 139, 0.08); border-radius: 50px; margin-bottom: 15px; }
        .section-title { font-size: 2.2rem; font-weight: 800; color: var(--text-main); margin: 0; }

        /* --- STORY GRID --- */
        .story-grid { display: grid; grid-template-columns: 1.2fr 1fr; gap: 60px; margin-bottom: 100px; align-items: start; }
        .story-content h3 { font-size: 1.8rem; font-weight: 800; color: var(--text-main); margin-bottom: 25px; display: flex; align-items: center; gap: 15px; }
        .story-content h3::before { content: ''; width: 5px; height: 30px; background: var(--accent-gold); border-radius: 5px; display: block; }
        .story-content p { color: var(--text-muted); line-height: 1.8; font-size: 1.05rem; text-align: justify; margin-bottom: 20px; }

        .vm-wrapper { display: flex; flex-direction: column; gap: 25px; }
        .vm-card { background: var(--card-bg); border: 1px solid var(--border-color); padding: 30px; border-radius: 20px; transition: 0.4s ease; }
        .vm-card:hover { transform: translateY(-5px); border-color: var(--p-color); box-shadow: 0 15px 30px rgba(0,0,0,0.08); }
        .vm-header { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; }
        .vm-icon { width: 50px; height: 50px; background: rgba(72, 61, 139, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--p-color); font-size: 1.5rem; }
        .vm-title { font-size: 1.3rem; font-weight: 800; color: var(--text-main); margin: 0; }
        .vm-list { list-style: none; padding: 0; margin: 0; }
        .vm-list li { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 12px; color: var(--text-muted); font-size: 0.95rem; line-height: 1.5; }
        .vm-list li i { color: var(--accent-gold); font-size: 1.1rem; margin-top: 2px; }

        /* ==========================================================
           SLIDER TIM (DESAIN WATERMARK / SILUET KANAN KIRI)
           ========================================================== */
        .team-carousel-container {
            width: 100%; overflow: hidden; padding: 10px 0 20px;
        }
        
        .team-track {
            display: flex; transition: transform 0.6s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        .team-slide {
            flex: 0 0 25%; /* Desktop: 4 per Halaman */
            padding: 0 15px; 
            box-sizing: border-box;
        }
        
        .team-card {
            height: 420px; position: relative; border-radius: 20px; overflow: hidden; 
            background: var(--border-color); box-shadow: 0 10px 30px rgba(0,0,0,0.06); transition: 0.4s ease;
        }
        .team-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.15); }
        
        /* Foto Latar Belakang */
        .card-img {
            width: 100%; height: 100%; object-fit: cover; object-position: top center;
            position: absolute; top: 0; left: 0; z-index: 1; transition: all 0.5s ease;
        }
        .team-card:hover .card-img { 
            transform: scale(1.05); 
            filter: brightness(0.85); /* Sedikit saja meredup agar foto tetap terlihat cantik */
        }
        
        /* --- KOTAK INFO "ID CARD" TENGAH --- */
        .team-info {
            position: absolute; bottom: 15px; left: 15px; right: 15px; 
            background: var(--card-bg); border-radius: 12px; padding: 18px 15px; 
            box-shadow: 0 10px 20px rgba(0,0,0,0.1); z-index: 2; 
            border-bottom: 4px solid transparent; transition: 0.3s;
            text-align: center; 
            overflow: hidden; /* Mengunci ikon raksasa agar tidak meluber keluar kotak putih */
        }

        /* Pastikan Teks Berada di Atas Siluet */
        .team-info-text {
            position: relative; z-index: 5;
        }
        
        .team-info h5 { font-size: 1.05rem; font-weight: 800; color: var(--text-main); margin: 0 0 3px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .team-info p.role { font-size: 0.75rem; color: var(--text-muted); margin: 0; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

        /* Warna Garis Bawah saat Hover */
        .card-admin:hover .team-info { border-bottom-color: var(--p-color); }
        .card-driver:hover .team-info { border-bottom-color: #198754; }
        .card-dev:hover .team-info { border-bottom-color: var(--text-main); }
        [data-theme="dark"] .card-dev:hover .team-info { border-bottom-color: var(--accent-gold); }

        /* --- IKON SILUET RAKSASA DI KANAN KIRI ATAS --- */
        .team-siluet { 
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            z-index: 1; 
            opacity: 0; transition: all 0.5s ease;
            pointer-events: none; 
        }
        
        .team-card:hover .team-siluet { opacity: 1; }
        
        .team-siluet i { 
            position: absolute;
            font-size: 5rem; 
            opacity: 0.12; 
            color: var(--text-muted);
            transition: 0.4s ease;
        }

        .team-siluet i:first-child { 
            top: -20px; 
            left: -15px; 
            transform: rotate(-45deg); 
        }

        .team-siluet i:last-child { 
            top: -20px; 
            right: -15px; 
            transform: rotate(45deg); 
        }

        .card-admin:hover .team-siluet i { color: var(--p-color); }
        .card-driver:hover .team-siluet i { color: #198754; }
        .card-dev:hover .team-siluet i { color: var(--text-main); }
        [data-theme="dark"] .card-dev:hover .team-siluet i { color: var(--accent-gold); }

        /* --- TITIK NAVIGASI (DOTS) --- */
        .carousel-dots { display: flex; justify-content: center; align-items: center; gap: 8px; margin-top: 30px; }
        .dot { width: 10px; height: 10px; border-radius: 50%; background-color: var(--text-muted); opacity: 0.3; border: none; cursor: pointer; transition: 0.3s ease; padding: 0; }
        .dot.active { opacity: 1; width: 30px; border-radius: 10px; background-color: var(--p-color); }
        [data-theme="dark"] .dot.active { background-color: var(--accent-gold); }

        /* --- MAPS --- */
        .location-wrapper { display: flex; background: var(--card-bg); border-radius: 24px; overflow: hidden; border: 1px solid var(--border-color); box-shadow: 0 15px 40px rgba(0,0,0,0.05); height: 400px; margin-top: 80px;}
        .map-frame { flex: 1.5; height: 100%; border: none; background: var(--bg-color); }
        .address-box { flex: 1; padding: 50px; display: flex; flex-direction: column; justify-content: center; position: relative; }
        .address-box::before { content: ''; position: absolute; left: 0; top: 15%; height: 70%; width: 3px; background: linear-gradient(to bottom, transparent, var(--p-color), transparent); }

        /* --- DARK MODE FIXES --- */
        [data-theme="dark"] .about-hero { background: linear-gradient(135deg, #0a0a0a, #1a1a2e); box-shadow: none; border-bottom: 1px solid #333; }
        [data-theme="dark"] .vm-card { background: #1a1a1a; border-color: #333; }
        [data-theme="dark"] .team-info { background: #202020; box-shadow: 0 10px 20px rgba(0,0,0,0.8); border-color: #333; }
        [data-theme="dark"] .address-box { background: #1a1a1a; }

        /* RESPONSIVE SLIDER */
        @media (max-width: 992px) { 
            .team-slide { flex: 0 0 33.333%; } 
            .story-grid { grid-template-columns: 1fr; gap: 40px; }
            .location-wrapper { flex-direction: column; height: auto; }
            .map-frame { height: 250px; }
        }
        @media (max-width: 768px) { .team-slide { flex: 0 0 50%; } }
        @media (max-width: 576px) { .team-slide { flex: 0 0 100%; } }
    </style>
</head>
<body data-theme="light">

    @include('user.partials.header')

    <div class="about-hero animate-up">
        <h1 class="about-title">Buana Berlian Tour & Travel</h1>
        <p class="about-subtitle">Layanan transportasi profesional antar kota yang mengutamakan kenyamanan, keamanan, dan ketepatan waktu untuk solusi mobilitas Anda.</p>
    </div>

    <main class="content-container">
        
        <div class="story-grid animate-up delay-1">
            <div class="story-content">
                <h3>Tentang Kami</h3>
                <p><strong>Buana Berlian Travel</strong> adalah layanan transportasi profesional yang melayani perjalanan reguler antar kota serta berbagai kebutuhan perjalanan lainnya dengan mengutamakan kenyamanan, keamanan dan ketepatan waktu.</p>
                <p>Kami hadir untuk menjadi solusi mobilitas masyarakat dengan layanan yang terpercaya dan berkualitas. Dengan pengalaman dalam melayani pelanggan, Buana Berlian Travel terus berkomitmen memberikan pelayanan terbaik baik untuk perjalanan pribadi, bisnis, maupun kebutuhan logistik.</p>
                
                <h3 style="margin-top: 40px; font-size: 1.5rem;">Keunggulan Kami</h3>
                <ul class="vm-list" style="margin-top: 15px;">
                    <li><i class="bi bi-door-open-fill"></i> Sistem door to door (antar jemput langsung)</li>
                    <li><i class="bi bi-car-front-fill"></i> Armada nyaman dan terawat</li>
                    <li><i class="bi bi-person-vcard-fill"></i> Driver berpengalaman dan profesional</li>
                    <li><i class="bi bi-clock-fill"></i> Tepat waktu & terpercaya</li>
                    <li><i class="bi bi-tags-fill"></i> Harga kompetitif</li>
                </ul>
            </div>
            
            <div class="vm-wrapper">
                <div class="vm-card">
                    <div class="vm-header"><div class="vm-icon"><i class="bi bi-eye"></i></div><h4 class="vm-title">Visi</h4></div>
                    <p style="margin:0; font-size:0.95rem; color:var(--text-muted); line-height:1.6;">Menjadi perusahaan jasa transportasi terpercaya dan pilihan utama Masyarakat Pacitan dalam menyediakan solusi mobilitas yang aman, nyaman serta berorientasi pada kepuasan pelanggan.</p>
                </div>
                
                <div class="vm-card">
                    <div class="vm-header"><div class="vm-icon"><i class="bi bi-bullseye"></i></div><h4 class="vm-title">Misi</h4></div>
                    <ul class="vm-list">
                        <li><i class="bi bi-check-circle-fill"></i> Menyelenggarakan layanan transportasi yang aman, nyaman dan tepat waktu.</li>
                        <li><i class="bi bi-check-circle-fill"></i> Layanan pengiriman paket dan dokumen yang cepat dan aman.</li>
                        <li><i class="bi bi-check-circle-fill"></i> Layanan carter dan wisata profesional dan kompetitif.</li>
                        <li><i class="bi bi-check-circle-fill"></i> Peningkatan kualitas SDM dan operasional armada yang prima.</li>
                        <li><i class="bi bi-check-circle-fill"></i> Mengutamakan kepuasan dan kebutuhan pelanggan.</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="section-header animate-up delay-2">
            <span class="section-tag">Tim Solid</span>
            <h2 class="section-title">Orang Dibalik Layar</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 10px;">Geser untuk melihat semua anggota tim kami.</p>
        </div>

        <div class="team-carousel-container animate-up delay-2">
            <div class="team-track" id="teamTrack">
                
                <div class="team-slide">
                    <div class="team-card card-admin">
                        <img src="{{ asset('public/assets/img/shavira.png') }}" class="card-img" alt="Shavira" onerror="this.src='https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=400&auto=format&fit=crop'">
                        <div class="team-info">
                            <div class="team-siluet">
                                <i class="bi bi-whatsapp"></i>
                                <i class="bi bi-headset"></i>
                            </div>
                            <div class="team-info-text">
                                <h5>Shavira</h5>
                                <p class="role">Admin 1</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="team-slide">
                    <div class="team-card card-admin">
                        <img src="{{ asset('public/assets/img/meilina.png') }}" class="card-img" alt="Admin 2" onerror="this.src='https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=400&auto=format&fit=crop'">
                        <div class="team-info">
                            <div class="team-siluet">
                                <i class="bi bi-whatsapp"></i>
                                <i class="bi bi-laptop"></i>
                            </div>
                            <div class="team-info-text">
                                <h5>Meilina</h5>
                                <p class="role">Admin 2</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="team-slide">
                    <div class="team-card card-driver">
                        <img src="{{ asset('public/assets/img/dika.png') }}" class="card-img" alt="Dika" onerror="this.src='https://images.unsplash.com/photo-1566492031773-4f4e44671857?q=80&w=400&auto=format&fit=crop'">
                        <div class="team-info">
                            <div class="team-siluet">
                                <i class="bi bi-car-front-fill"></i>
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div class="team-info-text">
                                <h5>Dika</h5>
                                <p class="role">Driver</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="team-slide">
                    <div class="team-card card-driver">
                        <img src="{{ asset('public/assets/img/kaka.png') }}" class="card-img" alt="Kaka" onerror="this.src='https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=400&auto=format&fit=crop'">
                        <div class="team-info">
                            <div class="team-siluet">
                                <i class="bi bi-box-seam-fill"></i>
                                <i class="bi bi-truck"></i>
                            </div>
                            <div class="team-info-text">
                                <h5>Kaka</h5>
                                <p class="role">Driver</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="team-slide">
                    <div class="team-card card-driver">
                        <img src="{{ asset('public/assets/img/rama.png') }}" class="card-img" alt="Rama" onerror="this.src='https://images.unsplash.com/photo-1552058544-f2b08422138a?q=80&w=400&auto=format&fit=crop'">
                        <div class="team-info">
                            <div class="team-siluet">
                                <i class="bi bi-car-front-fill"></i>
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div class="team-info-text">
                                <h5>Rama</h5>
                                <p class="role">Driver</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="team-slide">
                    <div class="team-card card-driver">
                        <img src="{{ asset('public/assets/img/shandy.png') }}" class="card-img" alt="Shandy" onerror="this.src='https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=400&auto=format&fit=crop'">
                        <div class="team-info">
                            <div class="team-siluet">
                                <i class="bi bi-box-seam-fill"></i>
                                <i class="bi bi-truck"></i>
                            </div>
                            <div class="team-info-text">
                                <h5>Shandy</h5>
                                <p class="role">Driver</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="team-slide">
                    <div class="team-card card-driver">
                        <img src="{{ asset('public/assets/img/eko.png') }}" class="card-img" alt="Driver" onerror="this.src='https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?q=80&w=400&auto=format&fit=crop'">
                        <div class="team-info">
                            <div class="team-siluet">
                                <i class="bi bi-car-front-fill"></i>
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div class="team-info-text">
                                <h5>Eko</h5>
                                <p class="role">Driver</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="team-slide">
                    <div class="team-card card-dev">
                        <img src="{{ asset('public/assets/img/imran2.png') }}" class="card-img" alt="Imran Darajati" onerror="this.src='https://images.unsplash.com/photo-1517694712202-14dd9538aa97?q=80&w=400&auto=format&fit=crop'">
                        <div class="team-info">
                            <div class="team-siluet">
                                <i class="bi bi-code-slash"></i>
                                <i class="bi bi-github"></i>
                            </div>
                            <div class="team-info-text">
                                <h5>Imran</h5>
                                <p class="role">System Developer</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        
        <div class="carousel-dots animate-up delay-2" id="carouselDots"></div>

        <div class="section-header animate-up delay-3" style="margin-top: 80px;">
            <h2 class="section-title">Kunjungi Kami</h2>
        </div>

        <div class="location-wrapper animate-up delay-3">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3949.072785508729!2d111.09169727484878!3d-8.195422991836335!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e79613aafe2f86d%3A0x6d71e3ae661d44e!2sBuana%20Berlian%20Tour%20%26%20Travel!5e0!3m2!1sid!2skr!4v1773530030594!5m2!1sid!2skr" class="map-frame" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            
            <div class="address-box">
                <div style="margin-bottom: 20px;"><i class="bi bi-geo-alt-fill" style="font-size: 2.5rem; color: var(--p-color);"></i></div>
                <h5 style="font-size:1.4rem; font-weight:800; color:var(--text-main); margin-bottom:15px;">Kantor Pusat</h5>
                <p style="color:var(--text-muted); font-size:1rem; line-height: 1.8; margin-bottom: 30px;">
                    <strong>Jl. Yos Sudarso 1 (Kios No. 3)</strong><br>
                    Ruko samping Pengadilan Negeri Pacitan<br>
                    Kelurahan Sidoharjo, Kecamatan Pacitan<br>
                    Kabupaten Pacitan
                </p>
            </div>
        </div>

    </main>

    @include('user.partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const track = document.getElementById('teamTrack');
            const slides = document.querySelectorAll('.team-slide');
            const dotsContainer = document.getElementById('carouselDots');
            
            let cardsPerView = 4;
            let currentPage = 0;

            function updateView() {
                if (window.innerWidth <= 576) cardsPerView = 1;
                else if (window.innerWidth <= 768) cardsPerView = 2;
                else if (window.innerWidth <= 992) cardsPerView = 3;
                else cardsPerView = 4;

                const totalPages = Math.ceil(slides.length / cardsPerView);
                
                if (currentPage >= totalPages) currentPage = totalPages - 1;
                if (currentPage < 0) currentPage = 0;

                dotsContainer.innerHTML = '';
                for (let i = 0; i < totalPages; i++) {
                    const dot = document.createElement('button');
                    dot.className = `dot ${i === currentPage ? 'active' : ''}`;
                    dot.addEventListener('click', () => {
                        currentPage = i;
                        slide();
                    });
                    dotsContainer.appendChild(dot);
                }
                slide();
            }

            function slide() {
                const slideWidth = slides[0].offsetWidth;
                const scrollDistance = currentPage * cardsPerView * slideWidth;
                track.style.transform = `translateX(-${scrollDistance}px)`;
                
                document.querySelectorAll('.dot').forEach((d, idx) => {
                    d.classList.toggle('active', idx === currentPage);
                });
            }

            let startX = 0;
            let endX = 0;
            track.addEventListener('touchstart', e => { startX = e.changedTouches[0].screenX; });
            track.addEventListener('touchend', e => {
                endX = e.changedTouches[0].screenX;
                const totalPages = Math.ceil(slides.length / cardsPerView);
                if (startX - endX > 50 && currentPage < totalPages - 1) {
                    currentPage++; slide();
                } else if (endX - startX > 50 && currentPage > 0) {
                    currentPage--; slide();
                }
            });

            window.addEventListener('resize', updateView);
            updateView();
        });
    </script>
</body>
</html>