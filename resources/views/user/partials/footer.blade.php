<footer class="main-footer">
    <div class="footer-container">
        <div class="footer-section branding">
            <img src="{{ asset('public/assets/img/LOGO.png') }}" alt="Logo" class="footer-logo">
            <p class="footer-desc">
                Buana Berlian Travel siap menjadi partner perjalanan Anda dengan layanan terbaik, aman dan nyaman.<br>
                <strong>Percayakan perjalanan Anda kepada kami – Let’s Travel With Us!</strong>
            </p>
        </div>

        <div class="footer-section links">
            <h4 class="footer-title">Navigasi</h4>
            <ul class="footer-list">
                <li><a href="{{ url('/layanan') }}">Pesan Layanan</a></li>
                <li><a href="{{ url('/cek-tiket') }}">Cek Tiket</a></li>
                <li><a href="{{ url('/tentang-kami') }}">Tentang Kami</a></li>
            </ul>
        </div>

        <div class="footer-section contacts">
            <h4 class="footer-title">Hubungi Kami</h4>
            <div class="social-links">
                <a href="https://wa.me/6281803444854" target="_blank" class="footer-social-link">
                    <i class="bi bi-whatsapp"></i> WhatsApp Admin 1
                </a>
                <a href="https://wa.me/6282232485758" target="_blank" class="footer-social-link">
                    <i class="bi bi-whatsapp"></i> WhatsApp Admin 2
                </a>
                <a href="https://instagram.com/buana_berlian" target="_blank" class="footer-social-link">
                    <i class="bi bi-instagram"></i> Instagram
                </a>
                <a href="https://www.tiktok.com/@buana.berlian" target="_blank" class="footer-social-link">
                    <i class="bi bi-tiktok"></i> TikTok Official
                </a>
            </div>
        </div>
    </div>

    <div id="global-copyright-section">
        <p>&copy; 2026 Buana Berlian Tour & Travel. All Rights Reserved.</p>
    </div>
</footer>

<style>
    /* --- WRAPPER UTAMA --- */
    .main-footer {
        background-color: var(--p-color) !important; 
        color: #ffffff !important;
        padding: 80px 8% 40px !important; 
        margin-top: 100px !important;
        width: 100% !important;
        box-sizing: border-box !important;
        border: none !important; 
        outline: none !important;
    }

    .footer-container {
        display: flex;
        justify-content: space-between; 
        gap: 60px;
        max-width: 1200px;
        margin: 0 auto;
        border: none !important;
    }

    .footer-section { 
        flex: 1; 
        border: none !important; 
        outline: none !important;
    }

    .footer-section.branding { flex: 1.5; } 

    /* Paksa kolom Navigasi & Hubungi Kami sejajar lurus ke kiri tanpa border */
    .footer-section.links, .footer-section.contacts { 
        display: flex; 
        flex-direction: column; 
        align-items: flex-start; 
        padding-left: 20px; 
        border: none !important;
    }

    /* Tampilan Normal (Laptop): Rata kiri sejajar dengan teks */
    .footer-logo { height: 50px; filter: brightness(0) invert(1); margin-bottom: 15px; display: block; }
    
    .footer-desc { font-size: 15px !important; line-height: 1.8; color: rgba(255,255,255,0.8) !important; max-width: 350px; margin-top: 0 !important; }
    
    .footer-title { 
        color: var(--accent-gold) !important; 
        font-size: 1.2rem !important; 
        font-weight: 700; 
        margin-bottom: 30px; 
        text-transform: capitalize !important; 
        letter-spacing: 0.5px; 
        border: none !important;
    }

    /* --- LIST & SOSMED (DIBIKIN SEJAJAR LURUS) --- */
    .footer-list, .social-links { 
        list-style: none !important; 
        padding: 0 !important; 
        margin: 0 !important; 
        width: 100%;
        border: none !important;
        display: flex;
        flex-direction: column;
        gap: 18px;
    }
    
    .footer-list li { border: none !important; margin: 0 !important; padding: 0 !important; }

    .footer-list a, .footer-social-link { 
        color: rgba(255, 255, 255, 0.8) !important; 
        text-decoration: none !important; 
        font-size: 15px !important; 
        display: flex; 
        align-items: center; 
        justify-content: flex-start; 
        gap: 15px; 
        transition: all 0.3s ease;
        width: fit-content;
        border: none !important;
        background: none !important;
    }

    /* Kunci lebar ikon biar teks di kanannya lurus sejajar dari atas ke bawah */
    .footer-list a i, .footer-social-link i {
        width: 24px; 
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .footer-list a:hover, .footer-social-link:hover { 
        color: var(--accent-gold) !important; 
        transform: translateX(8px); 
    }

    /* --- COPYRIGHT --- */
    #global-copyright-section { 
        text-align: center !important; 
        margin-top: 60px !important; 
        padding-top: 30px !important; 
        border-top: 1px solid rgba(255, 255, 255, 0.1) !important; 
        width: 100% !important; 
    }
    #global-copyright-section p { font-size: 13px !important; color: rgba(255, 255, 255, 0.5) !important; margin: 0 !important; }

    /* =========================================
       INI YANG HILANG: KUNCI DARK MODE FOOTER 
       ========================================= */
    [data-theme="dark"] .main-footer { 
        background-color: #111111 !important; 
        border-top: 1px solid #222 !important; 
    }

    /* --- MOBILE RESPONSIVE --- */
    @media (max-width: 992px) {
        .footer-container { gap: 30px; }
        .footer-section.links, .footer-section.contacts { padding-left: 0; }
    }

    @media (max-width: 768px) {
        .main-footer { padding: 60px 5% 30px !important; }
        .footer-container { flex-direction: column !important; text-align: center !important; gap: 40px !important; }
        .footer-section.links, .footer-section.contacts { align-items: center; padding-left: 0; }
        .footer-list a, .footer-social-link { justify-content: center; margin: 0 auto; }
        
        /* Tambahan penengah Logo & Teks Deskripsi */
        .footer-logo { margin: 0 auto 15px !important; }
        .footer-desc { margin: 0 auto !important; text-align: center !important; }
    }
</style>