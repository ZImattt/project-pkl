<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="title" content="PKL Global Intermedia">
    <meta name="description" content="Program Praktek Kerja Lapangan (PKL) di Global Intermedia Nusantara untuk mahasiswa dan siswa SMK">
    <meta name="keywords" content="PKL, Magang, Global Intermedia, Praktek Kerja Lapangan, Teknologi Informasi, Software Development">
    
    <title>@yield('title') - PKL/Magang Pt Global Intermedia Nusantara</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('uploads/images/favicon.ico.png') }}">
    
    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    
    <style>
            .mobile-bottom-nav {
            display: none !important;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            background-color: white;
            border-top: 2px solid #cc0000;
            box-shadow: 0 -4px 15px rgba(0,0,0,0.1);
            z-index: 1000;
            height: 80px;
            padding: 0;
            margin: 0;
        }
        
        /* DESKTOP NAV HIDDEN ON MOBILE BY DEFAULT */
        @media (max-width: 992px) {
            #nav-menu-container {
                display: none !important;
            }
            
            body {
                padding-bottom: 80px !important;
            }
            
            .content-wrapper {
                padding-bottom: 80px !important;
            }
            
            /* MOBILE LOGO - DI TENGAH */
            #logo {
                position: absolute !important;
                left: 50% !important;
                transform: translateX(-50%) !important;
                top: 50% !important;
                transform: translate(-50%, -50%) !important;
            }
        }
        
        /* DESKTOP LOGO - POJOK KIRI */
        @media (min-width: 993px) {
            #logo {
                position: absolute !important;
                left: 20px !important;
                top: 50% !important;
                transform: translateY(-50%) !important;
                margin: 0 !important;
            }
            
            #logo a {
                display: flex;
                align-items: center;
                height: auto;
            }
            
            #logo img {
                height: 45px;
                width: auto;
                max-width: 200px;
                object-fit: contain;
            }
            
            /* Desktop Menu Container - dipindah ke kanan */
            #nav-menu-container {
                margin-left: auto;
                display: flex;
                justify-content: flex-end;
                flex: 1;
            }
            
            .nav-menu {
                display: flex;
                margin: 0;
                padding: 0;
                list-style: none;
                align-items: center;
                gap: 10px;
                justify-content: flex-end;
            }
        }
        
        /* ========== POPUP IKLAN STYLES - FIX SIZE ========== */
        #pklPopup {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.85);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.4s ease, visibility 0.4s;
            padding: 20px;
        }
        
        #pklPopup.show {
            opacity: 1;
            visibility: visible;
        }
        
        .popup-content {
            background: white;
            border-radius: 15px;
            width: 100%;
            max-width: 420px;
            overflow: hidden;
            transform: scale(0.9);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
            border: 2px solid #cc0000;
            max-height: 85vh;
            display: flex;
            flex-direction: column;
        }
        
        #pklPopup.show .popup-content {
            transform: scale(1);
        }
        
        .popup-header {
            background: linear-gradient(135deg, #cc0000, #b30000);
            color: white;
            padding: 20px 15px 15px;
            text-align: center;
            position: relative;
            flex-shrink: 0;
        }
        
        .popup-close {
            position: absolute;
            top: 12px;
            right: 12px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
            z-index: 2;
        }
        
        .popup-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }
        
        .popup-icon {
            font-size: 40px;
            margin-bottom: 12px;
            color: white;
        }
        
        .popup-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 5px;
            line-height: 1.3;
        }
        
        .popup-subtitle {
            font-size: 0.9rem;
            opacity: 0.9;
            line-height: 1.4;
        }
        
        .popup-body {
            padding: 20px 20px;
            flex: 1;
            overflow-y: auto;
            max-height: 50vh;
        }
        
        .popup-info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        
        .popup-info-icon {
            background: #ffe6e6;
            color: #cc0000;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            flex-shrink: 0;
            font-size: 16px;
        }
        
        .popup-info-text h4 {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
            line-height: 1.3;
        }
        
        .popup-info-text p {
            color: #666;
            font-size: 0.85rem;
            line-height: 1.5;
            margin: 0;
        }
        
        .popup-highlight {
            background: linear-gradient(135deg, #fff9e6, #fff5d9);
            border-left: 4px solid #ffc107;
            padding: 12px 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        
        .popup-highlight p {
            color: #856404;
            font-weight: 500;
            margin: 0;
            display: flex;
            align-items: flex-start;
            font-size: 0.85rem;
            line-height: 1.4;
        }
        
        .popup-highlight i {
            color: #ffc107;
            margin-right: 10px;
            font-size: 1.1rem;
            margin-top: 2px;
            flex-shrink: 0;
        }
        
        .popup-footer {
            padding: 15px 20px 20px;
            text-align: center;
            border-top: 1px solid #eee;
            flex-shrink: 0;
        }
        
        .popup-btn {
            background: linear-gradient(135deg, #cc0000, #b30000);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            min-width: 180px;
        }
        
        .popup-btn:hover {
            background: linear-gradient(135deg, #b30000, #990000);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(204, 0, 0, 0.3);
        }
        
        .popup-btn:active {
            transform: translateY(0);
        }
        
        .popup-btn i {
            margin-right: 6px;
        }
        
        .dont-show-again {
            margin-top: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            color: #666;
            cursor: pointer;
        }
        
        .dont-show-again input {
            margin-right: 6px;
        }
        
        /* ========== BOTTOM NAVBAR STYLES ========== */
        .nav-inner {
            padding: 12px 15px 20px;
            height: 100%;
        }
        
        .mobile-nav-items {
            display: flex;
            justify-content: space-around;
            list-style: none;
            margin: 0;
            padding: 0;
            height: 100%;
        }
        
        .mobile-nav-item {
            flex: 1;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .mobile-nav-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: #495057;
            padding: 8px 6px;
            border-radius: 12px;
            transition: all 0.2s ease;
            width: 85%;
            height: 100%;
            justify-content: center;
            gap: 6px;
        }
        
        .nav-icon-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            background-color: #f8f9fa;
            transform: translateZ(0);
        }
        
        .nav-icon-container i {
            font-size: 1.3rem;
            color: #495057;
        }
        
        .mobile-nav-link span {
            font-size: 0.7rem;
            font-weight: 600;
            line-height: 1.2;
            transition: color 0.2s ease;
        }
        
        /* Active state */
        .mobile-nav-link.active {
            color: #cc0000;
        }
        
        .mobile-nav-link.active .nav-icon-container {
            background-color: #ffe6e6;
            border: 1px solid #ffcccc;
            transform: translateY(-4px);
        }
        
        .mobile-nav-link.active .nav-icon-container i {
            color: #cc0000;
        }
        
        .mobile-nav-link.active span {
            color: #cc0000;
            font-weight: 700;
        }
        
        /* Hover effects */
        .mobile-nav-link:hover .nav-icon-container {
            background-color: #e9ecef;
            transform: translateY(-2px);
        }
        
        .mobile-nav-link:hover span {
            color: #cc0000;
        }
        
        /* Safe area spacer */
        .safe-area-spacer {
            height: env(safe-area-inset-bottom);
            background-color: white;
        }
        
        /* ========== HEADER STYLES ========== */
        #header {
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: white;
            border-bottom: 2px solid #cc0000;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            height: 70px;
        }
        
        .nav-menu {
            display: flex;
            margin: 0;
            padding: 0;
            list-style: none;
            align-items: center;
            gap: 5px;
        }
        
        .nav-menu li a {
            color: #212529;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 10px 14px;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
            white-space: nowrap;
            display: flex;
            align-items: center;
        }
        
        .nav-menu li a:hover {
            background-color: #ffe6e6;
            color: #cc0000;
            transform: translateY(-1px);
        }
        
        .nav-menu li.active a {
            background-color: #ffe6e6;
            color: #cc0000;
            font-weight: 700;
            box-shadow: 0 2px 6px rgba(204, 0, 0, 0.15);
        }
        
        /* ========== WHATSAPP BUTTON ========== */
        .whatsapp-float {
            position: fixed;
            background-color: #25D366;
            color: white;
            border-radius: 50%;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            text-decoration: none;
            transform: translateZ(0);
        }
        
        .whatsapp-float:hover {
            background-color: #128C7E;
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        /* ========== FOOTER HOVER EFFECTS ========== */
.footer-top a:hover {
    color: #cc0000 !important;
    transform: translateX(5px);
}

.footer-top .social-links a:hover {
    background: #cc0000 !important;
    transform: translateY(-3px);
}

.footer-bottom a:hover {
    color: #cc0000 !important;
}
    </style>
    
    @stack('styles')
</head>
<body style="overflow-x: hidden;">
    
    <!-- POPUP IKLAN - INI MASIH ADA! -->
    <div id="pklPopup">
        <div class="popup-content">
            <div class="popup-header">
                <button class="popup-close" id="closePopup">
                    <i class="fas fa-times"></i>
                </button>
                <div class="popup-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h2 class="popup-title">📍 INFORMASI PENTING</h2>
                <p class="popup-subtitle">Program PKL/Magang Global Intermedia</p>
            </div>
            
            <div class="popup-body">
                <div class="popup-info-item">
                    <div class="popup-info-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="popup-info-text">
                        <h4>Lokasi PKL/Magang</h4>
                        <p>Website ini khusus untuk pendaftaran PKL/Magang di <strong>Kantor Pusat Yogyakarta</strong>.</p>
                    </div>
                </div>
                
                <div class="popup-info-item">
                    <div class="popup-info-icon">
                        <i class="fas fa-map-pin"></i>
                    </div>
                    <div class="popup-info-text">
                        <h4>Alamat Kantor Pusat</h4>
                        <p>Jl. Taman Siswa No.125, Yogyakarta 55151<br>Telp: +62 274 382238</p>
                    </div>
                </div>
                
                <div class="popup-info-item">
                    <div class="popup-info-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="popup-info-text">
                        <h4>Cabang Lain</h4>
                        <p>Untuk pendaftaran di cabang Jayapura atau Nabire, silakan hubungi langsung kantor cabang masing-masing.</p>
                    </div>
                </div>
                
                <div class="popup-highlight">
                    <p><i class="fas fa-exclamation-triangle"></i> Pastikan Anda mendaftar untuk lokasi yang sesuai dengan domisili/perkuliahan Anda.</p>
                </div>
            </div>
            
            <div class="popup-footer">
                <button class="popup-btn" id="understandBtn">
                    <i class="fas fa-check-circle"></i> Saya Mengerti
                </button>
                <div class="dont-show-again">
                    <input type="checkbox" id="dontShowAgain">
                    <label for="dontShowAgain">Jangan tampilkan lagi</label>
                </div>
            </div>
        </div>
    </div>

    <!-- Header - TANPA INLINE STYLE PADA LOGO -->
    <header id="header">
        <div class="container-fluid" style="padding: 0 15px; display: flex; align-items: center; justify-content: center; height: 100%; max-width: 1200px; margin: 0 auto; position: relative;">
            <!-- Logo - POSISI DIKENDALIKAN OLEH CSS MEDIA QUERY -->
            <div id="logo">
                @php
                    $logoPath = 'uploads/images/logo_gi.png';
                    $logoExists = file_exists(public_path($logoPath));
                @endphp
                
                <a href="{{ route('student.home') }}" style="display: flex; align-items: center; height: 70px; text-decoration: none;">
                    @if($logoExists)
                        <img src="{{ asset($logoPath) }}" 
                             alt="Global Intermedia Nusantara"
                             style="height: 40px; width: auto; max-width: 200px; object-fit: contain;">
                    @else
                        <div style="color: #cc0000; font-weight: 700; font-size: 1.3rem; letter-spacing: 0.5px;">
                            <span style="color: #cc0000;">GLOBAL</span> <span style="color: #333;">INTERMEDIA</span>
                        </div>
                    @endif
                </a>
            </div>
            
            <!-- Desktop Menu -->
            <nav id="nav-menu-container">
                <ul class="nav-menu" id="navMenu">
                    <li id="mn_index">
                        <a href="{{ route('student.home') }}">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    <li id="mn_register">
                        <a href="{{ route('student.register') }}">
                            <i class="fas fa-file-alt me-1"></i>Pendaftaran
                        </a>
                    </li>
                    <li id="mn_status">
                        <a href="{{ route('student.status') }}">
                            <i class="fas fa-search me-1"></i>Cek Status
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-bottom-nav" id="mobileBottomNav">
        <div class="nav-inner">
            <ul class="mobile-nav-items">
                <li class="mobile-nav-item">
                    <a href="{{ route('student.home') }}" 
                       class="mobile-nav-link"
                       aria-label="Home">
                        <div class="nav-icon-container">
                            <i class="fas fa-home"></i>
                        </div>
                        <span>Home</span>
                    </a>
                </li>
                <li class="mobile-nav-item">
                    <a href="{{ route('student.register') }}" 
                       class="mobile-nav-link"
                       aria-label="Pendaftaran">
                        <div class="nav-icon-container">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <span>Daftar</span>
                    </a>
                </li>
                <li class="mobile-nav-item">
                    <a href="{{ route('student.status') }}" 
                       class="mobile-nav-link"
                       aria-label="Cek Status">
                        <div class="nav-icon-container">
                            <i class="fas fa-search"></i>
                        </div>
                        <span>Status</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Safe Area Spacer -->
        <div class="safe-area-spacer"></div>
    </nav>

    <!-- WhatsApp Float Button -->
    <a href="https://api.whatsapp.com/send?phone=62817456225" 
       class="whatsapp-float" 
       id="whatsappButton"
       target="_blank"
       aria-label="Hubungi via WhatsApp"
       title="Hubungi kami via WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Main Content -->
    <main class="content-wrapper">
        @yield('content')
    </main>

<!-- Footer -->
<footer id="footer">
    <!-- Footer Top -->
    <div class="footer-top" style="background: linear-gradient(135deg, #8a4343 0%, #5b1515,  #000000 100%); color: white; padding: 60px 0 40px; border-top: 5px solid #cc0000; margin-top: 50px;">
        <div class="container">
            <div class="row">
                <!-- Column 1: Company Info -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-logo mb-4">
                        <div style="color: #cc0000; font-weight: 800; font-size: 1.8rem; letter-spacing: 1px; margin-bottom: 15px;">
                            <span style="color: #cc0000;">GLOBAL</span> <span style="color: black;">INTERMEDIA</span>
                        </div>
                        <p style="color: #aaa; font-size: 0.9rem; line-height: 1.6;">
                            Perusahaan teknologi informasi terkemuka yang berfokus pada pengembangan solusi digital, 
                            software development, dan transformasi digital untuk bisnis di Indonesia.
                        </p>
                    </div>
                    
                    <div class="social-links mt-4">
                        <h6 style="color: #fff; font-weight: 600; margin-bottom: 15px; font-size: 1rem;">Ikuti Kami</h6>
                        <div style="display: flex; gap: 10px;">
                            <a href="https://www.facebook.com/GlobalIntermedia" 
                               target="_blank"
                               style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: rgba(255,255,255,0.1); border-radius: 50%; color: white; text-decoration: none; transition: all 0.3s;">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://www.instagram.com/globalintermedia/" 
                               target="_blank"
                               style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: rgba(255,255,255,0.1); border-radius: 50%; color: white; text-decoration: none; transition: all 0.3s;">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="https://www.linkedin.com/company/global-intermedia-nusantara" 
                               target="_blank"
                               style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: rgba(255,255,255,0.1); border-radius: 50%; color: white; text-decoration: none; transition: all 0.3s;">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="https://www.youtube.com/channel/UCWNaagmPO16cIaR3UkufuKA" 
                               target="_blank"
                               style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: rgba(255,255,255,0.1); border-radius: 50%; color: white; text-decoration: none; transition: all 0.3s;">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 style="color: #fff; font-weight: 700; margin-bottom: 20px; font-size: 1.1rem; position: relative; padding-bottom: 10px;">
                        <span style="color: #cc0000;">#</span> Links
                        <div style="position: absolute; bottom: 0; left: 0; width: 40px; height: 2px; background: #cc0000;"></div>
                    </h5>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="margin-bottom: 10px;">
                            <a href="https://gi.co.id" 
                               target="_blank"
                               style="color: #aaa; text-decoration: none; font-size: 0.9rem; transition: all 0.3s; display: flex; align-items: center;">
                                <i class="fas fa-external-link-alt me-2" style="font-size: 0.8rem;"></i>
                                Website Utama GI
                            </a>
                        </li>
                        <li style="margin-bottom: 10px;">
                            <a href="https://gi.co.id/career.html" 
                               target="_blank"
                               style="color: #aaa; text-decoration: none; font-size: 0.9rem; transition: all 0.3s; display: flex; align-items: center;">
                                <i class="fas fa-briefcase me-2" style="font-size: 0.8rem;"></i>
                                Karir di GI
                            </a>
                        </li>
                        <li style="margin-bottom: 10px;">
                            <a href="https://blog.gi.co.id" 
                               target="_blank"
                               style="color: #aaa; text-decoration: none; font-size: 0.9rem; transition: all 0.3s; display: flex; align-items: center;">
                                <i class="fas fa-blog me-2" style="font-size: 0.8rem;"></i>
                                Blog GI
                            </a>
                        </li>
                        <li style="margin-bottom: 10px;">
                            <a href="{{ route('student.home') }}#about" 
                               style="color: #aaa; text-decoration: none; font-size: 0.9rem; transition: all 0.3s; display: flex; align-items: center;">
                                <i class="fas fa-info-circle me-2" style="font-size: 0.8rem;"></i>
                                Tentang PKL
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Column 3: Contact Info -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 style="color: #fff; font-weight: 700; margin-bottom: 20px; font-size: 1.1rem; position: relative; padding-bottom: 10px;">
                        <span style="color: #cc0000;">#</span> Kontak
                        <div style="position: absolute; bottom: 0; left: 0; width: 40px; height: 2px; background: #cc0000;"></div>
                    </h5>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="margin-bottom: 15px; display: flex; align-items: flex-start;">
                            <i class="fas fa-map-marker-alt me-3" style="color: #cc0000; margin-top: 3px; font-size: 0.9rem;"></i>
                            <div>
                                <strong style="color: #fff; font-size: 0.85rem;">Kantor Pusat Yogyakarta</strong>
                                <p style="color: #aaa; font-size: 0.85rem; margin: 3px 0 0; line-height: 1.4;">
                                    Jl. Taman Siswa No.125, Yogyakarta 55151
                                </p>
                            </div>
                        </li>
                        <li style="margin-bottom: 15px; display: flex; align-items: center;">
                            <i class="fas fa-phone-alt me-3" style="color: #cc0000; font-size: 0.9rem;"></i>
                            <div>
                                <strong style="color: #fff; font-size: 0.85rem;">Telepon</strong>
                                <p style="color: #aaa; font-size: 0.85rem; margin: 3px 0 0;">
                                    +62 274 382238
                                </p>
                            </div>
                        </li>
                        <li style="margin-bottom: 15px; display: flex; align-items: center;">
                            <i class="fas fa-envelope me-3" style="color: #cc0000; font-size: 0.9rem;"></i>
                            <div>
                                <strong style="color: #fff; font-size: 0.85rem;">Email</strong>
                                <p style="color: #aaa; font-size: 0.85rem; margin: 3px 0 0;">
                                    info@gi.co.id
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Column 4: Office Hours -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 style="color: #fff; font-weight: 700; margin-bottom: 20px; font-size: 1.1rem; position: relative; padding-bottom: 10px;">
                        <span style="color: #cc0000;">#</span> Jam Operasional
                        <div style="position: absolute; bottom: 0; left: 0; width: 40px; height: 2px; background: #cc0000;"></div>
                    </h5>
                    <div style="background: rgba(255,255,255,0.05); border-radius: 8px; padding: 20px; border-left: 3px solid #cc0000;">
                        <div style="margin-bottom: 15px;">
                            <strong style="color: #fff; font-size: 0.85rem; display: block; margin-bottom: 5px;">Senin - Jumat</strong>
                            <span style="color: #aaa; font-size: 0.85rem;">08:00 - 17:00 WIB</span>
                        </div>
                        <div>
                            <strong style="color: #fff; font-size: 0.85rem; display: block; margin-bottom: 5px;">Sabtu-Minggu & Hari Libur</strong>
                            <span style="color: #aaa; font-size: 0.85rem;">Tutup</span>
                        </div>
                    </div>
                    
                    <!-- PKL Specific Contact -->
                    <div style="margin-top: 20px; background: rgba(204, 0, 0, 0.1); border-radius: 8px; padding: 15px; border: 1px solid rgba(204, 0, 0, 0.3);">
                        <strong style="color: #fff; font-size: 0.85rem; display: block; margin-bottom: 8px;">
                            <i class="fas fa-user-graduate me-2" style="color: #cc0000;"></i>
                            Kontak PKL/Magang
                        </strong>
                        <p style="color: #aaa; font-size: 0.8rem; margin: 0; line-height: 1.4;">
                            Untuk informasi PKL/Magang: 
                            <a href="mailto:pkl@gi.co.id" style="color: #ff0000; text-decoration: none;">
                                pkl@gi.co.id
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- NON-CRITICAL CSS -->
    <style>
        /* ========== RESPONSIVE POPUP FIXES ========== */
        
        /* Extra small phones */
        @media (max-width: 380px) {
            .popup-content {
                max-width: 340px;
                border-radius: 12px;
            }
            
            .popup-header {
                padding: 18px 12px 12px;
            }
            
            .popup-icon {
                font-size: 36px;
                margin-bottom: 10px;
            }
            
            .popup-title {
                font-size: 1.2rem;
            }
            
            .popup-subtitle {
                font-size: 0.85rem;
            }
            
            .popup-body {
                padding: 15px;
            }
            
            .popup-info-item {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .popup-info-icon {
                margin-right: 0;
                margin-bottom: 8px;
            }
            
            .popup-btn {
                min-width: 160px;
                padding: 9px 20px;
                font-size: 0.9rem;
            }
            
            .dont-show-again {
                font-size: 0.75rem;
            }
        }
        
        /* Very small phones (iPhone SE etc) */
        @media (max-width: 320px) {
            .popup-content {
                max-width: 300px;
            }
            
            .popup-title {
                font-size: 1.1rem;
            }
            
            .popup-body {
                padding: 12px;
            }
            
            .popup-info-text h4 {
                font-size: 0.95rem;
            }
            
            .popup-info-text p {
                font-size: 0.8rem;
            }
            
            .popup-btn {
                min-width: 140px;
                padding: 8px 18px;
            }
        }
        
        /* Tablet/Landscape */
        @media (min-width: 768px) and (max-width: 1024px) {
            .popup-content {
                max-width: 450px;
            }
        }
        
        /* Desktop */
        @media (min-width: 1025px) {
            .popup-content {
                max-width: 420px;
            }
        }
        
        /* Landscape mode on phones */
        @media (max-height: 600px) and (orientation: landscape) {
            .popup-content {
                max-height: 90vh;
                max-width: 500px;
            }
            
            .popup-body {
                max-height: 60vh;
                padding: 15px 20px;
            }
            
            .popup-info-item {
                margin-bottom: 12px;
            }
            
            .popup-highlight {
                margin: 12px 0;
                padding: 10px 12px;
            }
        }
        
        /* ========== RESPONSIVE NAVBAR RULES ========== */
        /* Mobile devices */
        @media (max-width: 992px) {
            .mobile-bottom-nav {
                display: block !important;
            }
            
            .whatsapp-float {
                bottom: 100px !important;
                right: 20px !important;
                width: 60px !important;
                height: 60px !important;
                font-size: 26px !important;
            }
        }
        
        /* Tablet */
        @media (max-width: 768px) {
            .mobile-bottom-nav {
                height: 75px;
            }
            
            .nav-inner {
                padding: 10px 15px 18px;
            }
            
            .mobile-nav-link {
                padding: 6px 4px;
                gap: 5px;
            }
            
            .nav-icon-container {
                width: 42px;
                height: 42px;
            }
            
            .nav-icon-container i {
                font-size: 1.25rem;
            }
            
            .mobile-nav-link span {
                font-size: 0.65rem;
            }
            
            .whatsapp-float {
                bottom: 95px !important;
                width: 55px !important;
                height: 55px !important;
                font-size: 24px !important;
            }
        }
        
        /* Smartphone */
        @media (max-width: 576px) {
            .mobile-bottom-nav {
                height: 70px;
            }
            
            .nav-inner {
                padding: 8px 12px 15px;
            }
            
            .mobile-nav-link {
                padding: 5px 3px;
                gap: 4px;
                width: 80%;
            }
            
            .nav-icon-container {
                width: 40px;
                height: 40px;
                border-radius: 10px;
            }
            
            .nav-icon-container i {
                font-size: 1.2rem;
            }
            
            .mobile-nav-link span {
                font-size: 0.6rem;
            }
            
            .whatsapp-float {
                bottom: 90px !important;
                right: 15px !important;
                width: 50px !important;
                height: 50px !important;
                font-size: 22px !important;
            }
        }
        
        /* Small smartphone */
        @media (max-width: 400px) {
            .mobile-bottom-nav {
                height: 65px;
            }
            
            .nav-inner {
                padding: 6px 10px 12px;
            }
            
            .mobile-nav-link {
                padding: 4px 2px;
                gap: 3px;
                width: 75%;
                border-radius: 10px;
            }
            
            .nav-icon-container {
                width: 36px;
                height: 36px;
                border-radius: 8px;
            }
            
            .nav-icon-container i {
                font-size: 1.1rem;
            }
            
            .mobile-nav-link span {
                font-size: 0.55rem;
            }
            
            .whatsapp-float {
                bottom: 85px !important;
                width: 45px !important;
                height: 45px !important;
                font-size: 20px !important;
            }
        }
        
        /* Desktop */
        @media (min-width: 993px) {
            .mobile-bottom-nav {
                display: none !important;
            }
            
            .whatsapp-float {
                bottom: 30px !important;
                right: 30px !important;
                width: 65px !important;
                height: 65px !important;
                font-size: 28px !important;
            }
            
            .content-wrapper {
                padding-bottom: 0 !important;
            }
            
            /* LOGO DI POJOK KIRI ATAS UNTUK DESKTOP */
            #logo {
                left: 20px !important;
                top: 50% !important;
                transform: translateY(-50%) !important;
                margin: 0 !important;
                position: absolute !important;
            }
            
            /* MENU DI KANAN UNTUK DESKTOP */
            #nav-menu-container {
                margin-left: auto !important;
                display: flex !important;
                justify-content: flex-end !important;
                flex: 1 !important;
            }
            
            .nav-menu {
                gap: 10px !important;
                justify-content: flex-end !important;
            }
            
            .nav-menu li a {
                padding: 10px 18px !important;
                font-size: 0.95rem !important;
            }
        }
        
        /* ========== CONTENT WRAPPER ========== */
        .content-wrapper {
            min-height: calc(100vh - 450px);
        }
    </style>
    
    <!-- SIMPLE JAVASCRIPT - PASTI JALAN -->
    <script>
        // ========== SIMPLE POPUP SCRIPT ==========
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Ready - Setting up popup...');
            
            // Tunggu 1.5 detik baru tampilkan popup
            setTimeout(function() {
                // Cek localStorage untuk "jangan tampilkan lagi"
                var dontShow = localStorage.getItem('pklPopupDontShow');
                
                if (!dontShow) {
                    var popup = document.getElementById('pklPopup');
                    if (popup) {
                        popup.classList.add('show');
                        document.body.style.overflow = 'hidden';
                        console.log('Popup shown!');
                    }
                } else {
                    console.log('Popup disabled by user');
                }
            }, 1500);
            
            // Setup close button
            var closeBtn = document.getElementById('closePopup');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    hidePopup();
                });
            }
            
            // Setup understand button
            var understandBtn = document.getElementById('understandBtn');
            if (understandBtn) {
                understandBtn.addEventListener('click', function() {
                    hidePopup();
                });
            }
            
            // Close with ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    hidePopup();
                }
            });
            
            // Close when clicking outside
            var popup = document.getElementById('pklPopup');
            if (popup) {
                popup.addEventListener('click', function(e) {
                    if (e.target === this) {
                        hidePopup();
                    }
                });
            }
            
            // Function to hide popup
            function hidePopup() {
                var popup = document.getElementById('pklPopup');
                if (popup) {
                    popup.classList.remove('show');
                    document.body.style.overflow = '';
                    
                    // Check "don't show again" checkbox
                    var checkbox = document.getElementById('dontShowAgain');
                    if (checkbox && checkbox.checked) {
                        localStorage.setItem('pklPopupDontShow', 'true');
                        console.log('Popup will not show again');
                    }
                }
            }
            
            // ========== SIMPLE NAVBAR SETUP ==========
            function setupNavbar() {
                var isMobile = window.innerWidth <= 992;
                var mobileNav = document.getElementById('mobileBottomNav');
                var desktopNav = document.getElementById('nav-menu-container');
                var whatsappBtn = document.getElementById('whatsappButton');
                
                if (isMobile) {
                    // Mobile
                    if (mobileNav) mobileNav.style.display = 'block';
                    if (desktopNav) desktopNav.style.display = 'none';
                    
                    var navHeight = mobileNav ? mobileNav.offsetHeight : 80;
                    if (whatsappBtn) whatsappBtn.style.bottom = (navHeight + 20) + 'px';
                    document.body.style.paddingBottom = navHeight + 'px';
                    
                    var contentWrapper = document.querySelector('.content-wrapper');
                    if (contentWrapper) contentWrapper.style.paddingBottom = navHeight + 'px';
                } else {
                    // Desktop
                    if (mobileNav) mobileNav.style.display = 'none';
                    if (desktopNav) desktopNav.style.display = 'flex';
                    
                    if (whatsappBtn) {
                        whatsappBtn.style.bottom = '30px';
                        whatsappBtn.style.right = '30px';
                    }
                    document.body.style.paddingBottom = '0';
                    
                    var contentWrapper = document.querySelector('.content-wrapper');
                    if (contentWrapper) contentWrapper.style.paddingBottom = '0';
                }
                
                // Set active nav
                var currentPath = window.location.pathname;
                
                // Desktop menu
                document.querySelectorAll('.nav-menu li a').forEach(function(link) {
                    var li = link.parentElement;
                    var href = link.getAttribute('href').split('?')[0];
                    
                    if (currentPath === href || (href !== '/' && currentPath.startsWith(href))) {
                        li.classList.add('active');
                    } else {
                        li.classList.remove('active');
                    }
                });
                
                // Mobile menu
                document.querySelectorAll('.mobile-nav-link').forEach(function(link) {
                    var href = link.getAttribute('href').split('?')[0];
                    
                    if (currentPath === href || (href !== '/' && currentPath.startsWith(href))) {
                        link.classList.add('active');
                    } else {
                        link.classList.remove('active');
                    }
                });
            }
            
            // Initial setup
            setupNavbar();
            
            // Handle resize
            var resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(setupNavbar, 100);
            });
            
            // Mobile nav click animation
            document.querySelectorAll('.mobile-nav-link').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    var icon = this.querySelector('.nav-icon-container');
                    if (icon) {
                        icon.style.transform = 'scale(0.9)';
                        setTimeout(function() {
                            icon.style.transform = '';
                        }, 200);
                    }
                });
            });
            
            // Auto dismiss alerts
            setTimeout(function() {
                document.querySelectorAll('.alert').forEach(function(alert) {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        if (alert.parentNode) {
                            alert.parentNode.removeChild(alert);
                        }
                    }, 500);
                });
            }, 5000);
        });
        
        // Fallback load
        window.addEventListener('load', function() {
            console.log('Window loaded');
        });
    </script>
    
    @stack('scripts')
</body>
</html>