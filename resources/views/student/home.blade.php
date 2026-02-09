@extends('layouts.app')

@section('title', 'Program PKL/Magang - Global Intermedia')
@section('content')

<!-- Hero Section dengan Carousel -->
<section id="intro" style="padding-top: 0;">
    <div class="intro-container">
        <div id="introCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner" role="listbox">
                <!-- Slide 1 -->
                <div class="carousel-item active" style="height: 600px; background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80') center/cover;">
                    <div class="carousel-container">
                        <div class="carousel-content" style="color: white; text-align: center; padding-top: 150px;">
                            <!-- Logo GI -->
                            @php
                                $logoPath = 'uploads/images/logo_gi.png';
                                $logoExists = file_exists(public_path($logoPath));
                            @endphp
                            
                            @if($logoExists)
                                <img src="{{ asset($logoPath) }}" class="mb-4 hero-logo" alt="Global Intermedia">
                            @else
                                <div class="hero-logo-placeholder mb-4">GI</div>
                            @endif
                            
                            <h1 class="hero-title mb-3">PROGRAM <span style="color: #CC0000;">PKL/MAGANG</span></h1>
                            <h3 class="hero-subtitle mb-4">Raih Pengalaman Nyata di Perusahaan Teknologi</h3>
                            <p class="hero-description mb-4">Global Intermedia membuka kesempatan Praktek Kerja Lapangan bagi siswa/mahasiswa yang ingin mengembangkan kompetensi di bidang teknologi informasi</p>
                            
                            <!-- BUTTON CONTAINER - MOBILE FRIENDLY -->
                            <div class="hero-buttons mt-4">
                                <div class="row g-2 justify-content-center">
                                    <div class="col-10 col-sm-5 col-md-4">
                                        <a href="{{ route('student.register') }}" class="btn btn-primary-custom w-100 py-2 hero-btn">
                                            <i class="fas fa-rocket me-1"></i>DAFTAR
                                        </a>
                                    </div>
                                    <div class="col-10 col-sm-5 col-md-4">
                                        <a href="#info" class="btn btn-outline-custom w-100 py-2 hero-btn">
                                            <i class="fas fa-info-circle me-1"></i>CARA DAFTAR
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Slide 2 -->
                <div class="carousel-item" style="height: 600px; background: linear-gradient(rgba(0,0,0,0.7), rgba(204,0,0,0.7)), url('https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80') center/cover;">
                    <div class="carousel-container">
                        <div class="carousel-content" style="color: white; text-align: center; padding-top: 150px;">
                            <h1 class="hero-title mb-3">BELAJAR DARI<br><span style="color: #FFFFFF;">PROFESIONAL IT</span></h1>
                            <h3 class="hero-subtitle mb-4">Dibimbing oleh Tim Berpengalaman 14+ Tahun</h3>
                            <p class="hero-description mb-4">Bergabung dengan perusahaan yang telah mengerjakan 700+ proyek teknologi untuk 120+ instansi pemerintahan dan swasta</p>
                            
                            <!-- BUTTON SLIDE 2 -->
                            <div class="hero-buttons mt-4">
                                <div class="row g-2 justify-content-center">
                                    <div class="col-10 col-sm-6 col-md-5">
                                        <a href="#featured-pengalaman" class="btn btn-outline-light w-100 py-2 hero-btn">
                                            <i class="fas fa-chart-line me-1"></i>Pengalaman KAMI
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Carousel Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#introCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#introCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</section>

<!-- Pengalaman Global Intermedia -->
<section id="featured-pengalaman" class="py-5" style="background: #f8f9fa;">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="section-title">Pengalaman Global Intermedia</h2>
                <p class="section-subtitle">Perusahaan teknologi terkemuka dengan pengalaman luas di seluruh Indonesia</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="achievement-card text-center">
                    <div class="achievement-icon mb-3">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <h3 class="achievement-number mb-2">700+</h3>
                    <h5 class="achievement-title mb-3">Proyek Diselesaikan</h5>
                    <p class="achievement-desc mb-0">Lebih dari 700 proyek teknologi telah kami kerjakan dengan sukses</p>
                </div>
            </div>
            
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="achievement-card text-center">
                    <div class="achievement-icon mb-3">
                        <i class="fas fa-university"></i>
                    </div>
                    <h3 class="achievement-number mb-2">120+</h3>
                    <h5 class="achievement-title mb-3">Instansi Klien</h5>
                    <p class="achievement-desc mb-0">Bekerjasama dengan instansi pemerintah dan swasta terkemuka</p>
                </div>
            </div>
            
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="achievement-card text-center">
                    <div class="achievement-icon mb-3">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3 class="achievement-number mb-2">14+</h3>
                    <h5 class="achievement-title mb-3">Tahun Pengalaman</h5>
                    <p class="achievement-desc mb-0">Berpengalaman sejak 2004 dalam bidang teknologi informasi</p>
                </div>
            </div>
            
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="achievement-card text-center">
                    <div class="achievement-icon mb-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="achievement-number mb-2">88+</h3>
                    <h5 class="achievement-title mb-3">Tim Profesional</h5>
                    <p class="achievement-desc mb-0">Didukung oleh tim ahli yang kompeten di bidangnya masing-masing</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mengapa Memilih PKL di GI -->
<section class="py-5" style="background: white;">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-12 text-center">
                <h2 class="section-title">Mengapa Memilih PKL di Global Intermedia?</h2>
                <p class="section-subtitle">Keunggulan program magang di perusahaan teknologi terkemuka</p>
            </div>
        </div>
        
        <div class="row">
            <!-- Benefit 1 -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="pkl-benefit-card h-100">
                    <div class="benefit-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="benefit-content">
                        <h4>Pengalaman Industri Nyata</h4>
                        <p>Belajar langsung dari proyek-proyek implementasi sistem informasi untuk pemerintahan dan perusahaan.</p>
                    </div>
                </div>
            </div>
            
            <!-- Benefit 2 -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="pkl-benefit-card h-100">
                    <div class="benefit-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="benefit-content">
                        <h4>Tim Berpengalaman</h4>
                        <p>Dibimbing oleh profesional yang telah belasan tahun berkecimpung di industri teknologi informasi.</p>
                    </div>
                </div>
            </div>
            
            <!-- Benefit 3 -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="pkl-benefit-card h-100">
                    <div class="benefit-icon">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <div class="benefit-content">
                        <h4>Sertifikat Resmi</h4>
                        <p>Dapatkan sertifikat PKL yang diakui dari perusahaan teknologi ternama dengan pengalaman 14+ tahun.</p>
                    </div>
                </div>
            </div>
            
            <!-- Benefit 4 -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="pkl-benefit-card h-100">
                    <div class="benefit-icon">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <div class="benefit-content">
                        <h4>Portfolio Nyata</h4>
                        <p>Kontribusi pada proyek nyata untuk klien pemerintah dan swasta yang dapat menjadi nilai tambah CV Anda.</p>
                    </div>
                </div>
            </div>
            
            <!-- Benefit 5 -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="pkl-benefit-card h-100">
                    <div class="benefit-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="benefit-content">
                        <h4>Lokasi Strategis</h4>
                        <p>Kantor pusat di Yogyakarta dengan cabang di Kalimantan dan Papua untuk kemudahan akses.</p>
                    </div>
                </div>
            </div>
            
            <!-- Benefit 6 -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="pkl-benefit-card h-100">
                    <div class="benefit-icon">
                        <i class="fas fa-network-wired"></i>
                    </div>
                    <div class="benefit-content">
                        <h4>Jaringan Profesional</h4>
                        <p>Bangun koneksi dengan profesional IT dan berkesempatan untuk bergabung dengan tim kami setelah lulus.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SIMPLE INFO PENDAFTARAN -->
<section id="info" class="py-5" style="background: #f8f9fa;">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title">Cara Daftar PKL/Magang</h2>
                <p class="section-subtitle">Proses pendaftaran yang mudah dan cepat</p>
            </div>
        </div>
        
        <div class="row">
            <!-- Langkah 1 -->
            <div class="col-md-4 mb-4">
                <div class="text-center p-4 h-100 bg-white rounded shadow-sm langkah-card">
                    <div class="langkah-icon mb-3">
                        <div class="circle-number">1</div>
                    </div>
                    <h4 class="fw-bold mb-3">Isi Form Online</h4>
                    <p class="text-muted mb-0">
                        Isi data diri lengkap di formulir pendaftaran. 
                        Cuma butuh kisaran 5-10 menit aja.
                    </p>
                </div>
            </div>
            
            <!-- Langkah 2 -->
            <div class="col-md-4 mb-4">
                <div class="text-center p-4 h-100 bg-white rounded shadow-sm langkah-card">
                    <div class="langkah-icon mb-3">
                        <div class="circle-number">2</div>
                    </div>
                    <h4 class="fw-bold mb-3">Upload Dokumen</h4>
                    <p class="text-muted mb-0">
                        Pastikan memiliki surat Surat Pengantar dari Sekokah/Kampus
                    </p>
                </div>
            </div>
            
            <!-- Langkah 3 -->
            <div class="col-md-4 mb-4">
                <div class="text-center p-4 h-100 bg-white rounded shadow-sm langkah-card">
                    <div class="langkah-icon mb-3">
                        <div class="circle-number">3</div>
                    </div>
                    <h4 class="fw-bold mb-3">Tunggu Konfirmasi</h4>
                    <p class="text-muted mb-0">
                        Tim kami akan hubungi via email/WhatsApp dalam 3-5 hari kerja 
                        untuk jadwal interview.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- CTA -->
        <div class="text-center mt-4">
            <a href="{{ route('student.register') }}" class="btn btn-danger btn-lg px-4 py-3 fw-bold daftar-btn">
                <i class="fas fa-paper-plane me-2"></i>LANGSUNG ISI FORMULIR
            </a>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h3 class="fw-bold mb-4 text-center section-title">Pertanyaan yang Sering Ditanya</h3>
                
                <div class="accordion" id="faqAccordion">
                    <!-- FAQ 1 -->
                    <div class="accordion-item mb-2">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Kapan aja bisa daftar?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Bisa daftar kapan aja. Tapi kalau mau mulai bulan depan, 
                                daftar minimal 2 minggu sebelumnya.
                            </div>
                        </div>
                    </div>
                    
                    <!-- FAQ 2 -->
                    <div class="accordion-item mb-2">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Harus jago coding?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Gak harus expert. Yang penting ada basic dan mau belajar. 
                                Kita sesuaikan sama level skill Anda.
                            </div>
                        </div>
                    </div>
                    
                    <!-- FAQ 3 -->
                    <div class="accordion-item mb-2">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Berapa lama durasinya?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Minimal 3 bulan, standar kampus. Tapi bisa juga 6 bulan atau 1 tahun 
                                kalau mau pengalaman lebih dalam.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5" style="background: linear-gradient(135deg, #ff0000 0%, #000000fa 100%); color: white; padding: 60px 0 40px; border-top: 5px solid #cc0000; margin-top: 50px;">
    <div class="container text-center">
        <h2 class="fw-bold mb-3 cta-title">Siap Bergabung dengan Tim Kami?</h2>
        <p class="lead mb-4 cta-description">Daftarkan diri Anda sekarang dan mulai perjalanan karir IT bersama Global Intermedia. Berpengalaman sejak 2004, telah menyelesaikan 700+ proyek untuk 120+ instansi.</p>
        
        <div class="cta-buttons mb-4">
            <div class="row g-2 justify-content-center">
                <div class="col-10 col-sm-6 col-md-4">
                    <a href="{{ route('student.register') }}" class="btn btn-light w-100 py-2 cta-btn">
                        <i class="fas fa-file-signature me-2" style="color: #CC0000;"></i>DAFTAR SEKARANG
                    </a>
                </div>
                <div class="col-10 col-sm-6 col-md-4">
                    <a href="{{ route('student.status') }}" class="btn btn-outline-light w-100 py-2 cta-btn">
                        <i class="fas fa-search me-2"></i>CEK STATUS
                    </a>
                </div>
            </div>
        </div>
        
        <div class="mt-4 pt-3 border-top border-light border-opacity-25">
            <div class="row justify-content-center contact-info">
                <div class="col-md-4 col-sm-6 mb-3">
                    <p class="mb-2">
                        <i class="fas fa-phone-alt me-2"></i>Telepon: (0274) 382238
                    </p>
                </div>
                <div class="col-md-4 col-sm-6 mb-3">
                    <p class="mb-2">
                        <i class="fab fa-whatsapp me-2"></i>WhatsApp: 0817-4562-25
                    </p>
                </div>
                <div class="col-md-4 col-sm-6 mb-3">
                    <p class="mb-2">
                        <i class="fas fa-envelope me-2"></i>Email: pkl@gi.co.id
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
    /* Base Variables */
    :root {
        --primary-red: #CC0000;
        --light-red: #ffe6e6;
        --dark-gray: #333333;
        --text-color: #555555;
        --medium-gray: #e0e0e0;
    }
    
    /* ========== HERO SECTION ========== */
    /* Desktop */
    .hero-title {
        color: white;
        font-weight: 700;
        margin-bottom: 20px;
        font-size: 2.8rem;
        line-height: 1.2;
    }
    
    .hero-subtitle {
        color: rgba(255,255,255,0.9);
        font-size: 1.4rem;
        margin-bottom: 25px;
        line-height: 1.3;
    }
    
    .hero-description {
        color: rgba(255,255,255,0.85);
        font-size: 1.1rem;
        line-height: 1.6;
        max-width: 800px;
        margin: 0 auto 30px;
    }
    
    .hero-logo {
        width: 220px;
        max-width: 100%;
        height: auto;
        margin-bottom: 30px;
    }
    
    .hero-logo-placeholder {
        width: 220px;
        height: 70px;
        background-color: #CC0000;
        color: white;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.8rem;
        margin: 0 auto 30px;
    }
    
    /* Mobile Responsive Hero */
    @media (max-width: 992px) {
        .carousel-content {
            padding-top: 130px !important;
        }
    }
    
    @media (max-width: 768px) {
        .carousel-item {
            height: 500px !important;
        }
        
        .carousel-content {
            padding-top: 120px !important;
        }
        
        .hero-title {
            font-size: 2.2rem !important;
            padding: 0 15px;
        }
        
        .hero-subtitle {
            font-size: 1.2rem !important;
            padding: 0 15px;
            margin-bottom: 20px !important;
        }
        
        .hero-description {
            font-size: 1rem !important;
            padding: 0 15px;
            margin-bottom: 25px !important;
        }
        
        .hero-logo {
            width: 180px !important;
            margin-bottom: 20px !important;
        }
        
        .hero-logo-placeholder {
            width: 180px !important;
            height: 60px !important;
            font-size: 1.5rem !important;
            margin-bottom: 20px !important;
        }
    }
    
    @media (max-width: 576px) {
        .carousel-item {
            height: 480px !important;
        }
        
        .carousel-content {
            padding-top: 110px !important;
        }
        
        .hero-title {
            font-size: 1.8rem !important;
            padding: 0 10px;
            margin-bottom: 15px !important;
        }
        
        .hero-subtitle {
            font-size: 1.1rem !important;
            padding: 0 10px;
            margin-bottom: 15px !important;
        }
        
        .hero-description {
            font-size: 0.95rem !important;
            padding: 0 10px;
            margin-bottom: 20px !important;
            line-height: 1.4 !important;
        }
        
        .hero-buttons {
            padding: 0 15px;
        }
        
        .hero-btn {
            padding: 10px 12px !important;
            font-size: 0.9rem !important;
        }
        
        .hero-logo {
            width: 140px !important;
            margin-bottom: 15px !important;
        }
        
        .hero-logo-placeholder {
            width: 140px !important;
            height: 50px !important;
            font-size: 1.3rem !important;
            margin-bottom: 15px !important;
        }
    }
    
    @media (max-width: 375px) {
        .carousel-item {
            height: 460px !important;
        }
        
        .carousel-content {
            padding-top: 100px !important;
        }
        
        .hero-title {
            font-size: 1.6rem !important;
        }
        
        .hero-subtitle {
            font-size: 1rem !important;
        }
        
        .hero-description {
            font-size: 0.9rem !important;
        }
        
        .hero-btn {
            font-size: 0.85rem !important;
            padding: 8px 10px !important;
        }
    }
    
    /* ========== SECTION STYLES ========== */
    .section-title {
        color: var(--dark-gray);
        font-weight: 700;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--primary-red);
        display: inline-block;
        font-size: 1.8rem;
    }
    
    .section-subtitle {
        color: var(--text-color);
        margin-bottom: 30px;
    }
    
    /* ========== ACHIEVEMENT CARDS ========== */
    .achievement-card {
        background: white;
        border-radius: 15px;
        padding: 25px 20px;
        border: 1px solid var(--medium-gray);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .achievement-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(204, 0, 0, 0.1);
        border-color: var(--primary-red);
    }
    
    .achievement-icon {
        width: 70px;
        height: 70px;
        background: var(--light-red);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-red);
        font-size: 1.8rem;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }
    
    .achievement-number {
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--primary-red);
        margin-bottom: 10px;
    }
    
    .achievement-title {
        color: var(--dark-gray);
        font-weight: 600;
        margin-bottom: 15px;
        font-size: 1.1rem;
    }
    
    .achievement-desc {
        color: var(--text-color);
        line-height: 1.5;
        font-size: 0.9rem;
        margin-bottom: 0;
    }
    
    /* ========== BENEFIT CARDS ========== */
    .pkl-benefit-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border: 1px solid var(--medium-gray);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .pkl-benefit-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        border-color: var(--primary-red);
    }
    
    .benefit-icon {
        width: 60px;
        height: 60px;
        background: var(--light-red);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        font-size: 1.5rem;
        color: var(--primary-red);
        transition: all 0.3s ease;
    }
    
    .benefit-content h4 {
        color: var(--dark-gray);
        font-weight: 600;
        margin-bottom: 15px;
        font-size: 1.15rem;
    }
    
    .benefit-content p {
        color: var(--text-color);
        line-height: 1.5;
        font-size: 0.9rem;
        margin-bottom: 0;
    }
    
    /* ========== LANGKAH CARDS ========== */
    .langkah-card {
        transition: all 0.3s ease;
    }
    
    .langkah-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important;
    }
    
    .circle-number {
        width: 60px;
        height: 60px;
        background: #cc0000;
        color: white;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: bold;
        margin: 0 auto;
    }
    
    /* ========== ACCORDION ========== */
    .accordion-button:not(.collapsed) {
        background-color: #ffe6e6;
        color: #cc0000;
        font-weight: 600;
    }
    
    /* ========== CTA SECTION ========== */
    .cta-title {
        font-size: 1.8rem;
    }
    
    .cta-description {
        max-width: 700px;
        margin: 0 auto;
        color: rgba(255,255,255,0.9);
    }
    
    .cta-btn {
        font-weight: 600;
    }
    
    .contact-info p {
        margin-bottom: 0.5rem;
    }
    
    /* ========== BUTTONS ========== */
    .btn-primary-custom {
        background-color: var(--primary-red) !important;
        color: white !important;
        border: none !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        transition: all 0.3s ease !important;
    }
    
    .btn-primary-custom:hover {
        background-color: #b30000 !important;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(204, 0, 0, 0.3);
    }
    
    .btn-outline-custom {
        color: var(--primary-red) !important;
        border: 2px solid var(--primary-red) !important;
        background: transparent !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        transition: all 0.3s ease !important;
    }
    
    .btn-outline-custom:hover {
        background-color: var(--primary-red) !important;
        color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(204, 0, 0, 0.3);
    }
    
    .btn-outline-light:hover {
        background-color: white !important;
        color: var(--primary-red) !important;
    }
    
    .daftar-btn {
        transition: all 0.3s ease;
    }
    
    .daftar-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(204, 0, 0, 0.3);
    }
    
    /* ========== MOBILE RESPONSIVE ========== */
    @media (max-width: 768px) {
        /* Section Titles */
        .section-title {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        
        .section-subtitle {
            font-size: 0.95rem;
            margin-bottom: 25px;
        }
        
        /* Cards */
        .achievement-card {
            padding: 20px 15px;
        }
        
        .achievement-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
        
        .achievement-number {
            font-size: 1.8rem;
        }
        
        .achievement-title {
            font-size: 1rem;
        }
        
        .achievement-desc {
            font-size: 0.85rem;
        }
        
        .pkl-benefit-card {
            padding: 20px;
        }
        
        .benefit-icon {
            width: 50px;
            height: 50px;
            font-size: 1.3rem;
            margin-bottom: 15px;
        }
        
        .benefit-content h4 {
            font-size: 1.05rem;
        }
        
        .benefit-content p {
            font-size: 0.85rem;
        }
        
        .langkah-card {
            padding: 20px !important;
        }
        
        .circle-number {
            width: 50px;
            height: 50px;
            font-size: 1.3rem;
        }
        
        /* CTA Section */
        .cta-title {
            font-size: 1.5rem;
            padding: 0 15px;
        }
        
        .cta-description {
            font-size: 1rem;
            padding: 0 15px;
        }
        
        .cta-btn {
            padding: 10px 15px !important;
            font-size: 0.9rem !important;
        }
        
        .daftar-btn {
            font-size: 1rem !important;
            padding: 12px 20px !important;
        }
        
        /* Contact Info */
        .contact-info p {
            font-size: 0.9rem;
        }
    }
    
    @media (max-width: 576px) {
        /* Extra small devices */
        .achievement-card,
        .pkl-benefit-card,
        .langkah-card {
            margin-bottom: 20px;
        }
        
        .cta-title {
            font-size: 1.3rem;
        }
        
        .cta-description {
            font-size: 0.95rem;
        }
        
        .daftar-btn {
            width: 100% !important;
            max-width: 280px !important;
            font-size: 0.9rem !important;
            padding: 10px 15px !important;
        }
        
        /* FAQ */
        .accordion-button {
            font-size: 0.9rem;
            padding: 12px 15px;
        }
        
        .accordion-body {
            font-size: 0.85rem;
            padding: 15px;
        }
    }
    
    @media (max-width: 375px) {
        /* Very small phones */
        .hero-btn, 
        .cta-btn {
            font-size: 0.8rem !important;
            padding: 8px 10px !important;
        }
        
        .daftar-btn {
            font-size: 0.85rem !important;
        }
        
        .contact-info .col-sm-6 {
            margin-bottom: 10px;
        }
        
        .contact-info p {
            font-size: 0.8rem;
        }
    }
    
    /* Landscape mode */
    @media (max-height: 600px) and (orientation: landscape) {
        .carousel-item {
            height: 400px !important;
        }
        
        .carousel-content {
            padding-top: 90px !important;
        }
        
        .hero-title {
            font-size: 1.6rem !important;
            margin-bottom: 10px !important;
        }
        
        .hero-subtitle {
            font-size: 1rem !important;
            margin-bottom: 10px !important;
        }
        
        .hero-description {
            display: none;
        }
        
        .hero-buttons {
            margin-top: 20px !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        console.log('HOME PAGE - FULLY RESPONSIVE ✅');
        
        // Initialize carousel
        const myCarousel = new bootstrap.Carousel(document.getElementById('introCarousel'), {
            interval: 5000,
            wrap: true
        });
        
        // Smooth scrolling for anchor links
        $('a[href^="#"]').on('click', function(event) {
            if (this.hash !== "") {
                event.preventDefault();
                
                const hash = this.hash;
                const target = $(hash);
                
                if (target.length) {
                    const headerHeight = $('#header').outerHeight() || 70;
                    
                    $('html, body').animate({
                        scrollTop: target.offset().top - headerHeight
                    }, 800);
                }
            }
        });
        
        // Add animation on scroll
        function animateOnScroll() {
            $('.achievement-card, .pkl-benefit-card, .langkah-card').each(function() {
                const elementTop = $(this).offset().top;
                const elementBottom = elementTop + $(this).outerHeight();
                const viewportTop = $(window).scrollTop();
                const viewportBottom = viewportTop + $(window).height();
                
                if (elementBottom > viewportTop && elementTop < viewportBottom) {
                    $(this).addClass('animate__animated animate__fadeInUp');
                }
            });
        }
        
        // Initial animation check
        animateOnScroll();
        
        // Check on scroll
        $(window).on('scroll', function() {
            animateOnScroll();
        });
        
        // Hover effects
        $('.achievement-card, .pkl-benefit-card, .langkah-card').hover(
            function() {
                $(this).addClass('animate__animated animate__pulse');
            },
            function() {
                $(this).removeClass('animate__animated animate__pulse');
            }
        );
        
        // Auto-rotate carousel
        let carouselInterval = setInterval(function() {
            myCarousel.next();
        }, 5000);
        
        // Pause carousel on hover
        $('#introCarousel').hover(
            function() {
                clearInterval(carouselInterval);
            },
            function() {
                carouselInterval = setInterval(function() {
                    myCarousel.next();
                }, 5000);
            }
        );
        
        // Mobile detection for better UX
        if(window.innerWidth <= 768) {
            // Add touch swipe support for mobile
            let touchStartX = 0;
            let touchEndX = 0;
            
            $('#introCarousel').on('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
            });
            
            $('#introCarousel').on('touchend', function(e) {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            });
            
            function handleSwipe() {
                const swipeThreshold = 50;
                
                if (touchEndX < touchStartX - swipeThreshold) {
                    // Swipe left - next slide
                    myCarousel.next();
                }
                if (touchEndX > touchStartX + swipeThreshold) {
                    // Swipe right - previous slide
                    myCarousel.prev();
                }
            }
        }
    });
</script>
@endpush