@extends('layouts.app')

@section('title', 'Program Magang & PKL - Global Intermedia')
@section('content')

<style>
    :root {
        --gi-red: #CC0000;
        --gi-red-light: #FFEBEB;
        --gi-dark: #1E1E2F;
        --gi-gray: #6c757d;
        --gi-border: #E2E8F0;
        --gi-success: #28a745;
        --gi-warning: #FF9800;
    }
    
    body {
        font-family: 'Inter', sans-serif;
        color: #2D3748;
        background: white;
    }
    
    .carousel-item {
        height: 500px;
        background-size: cover;
        background-position: center;
    }
    
    .carousel-container {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .hero-title {
        font-size: 2.5rem;
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        line-height: 1.2;
    }
    
    .hero-subtitle { font-size: 1.1rem; font-weight: 400; opacity: 0.95; }
    .hero-logo { width: 160px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2)); }
    
    .hero-btn { transition: all 0.3s ease; font-weight: 600; padding: 10px 24px; border-radius: 50px; }
    .hero-btn:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(204,0,0,0.3); }
    
    .section-title { font-size: 1.8rem; font-weight: 700; margin-bottom: 0.75rem; }
    .section-subtitle { font-size: 0.95rem; color: var(--gi-gray); max-width: 600px; margin: 0 auto; }
    .section-header { margin-bottom: 2.5rem; }
    
    .stat-card, .benefit-card { transition: all 0.3s ease; border: 1px solid var(--gi-border); }
    .stat-card:hover, .benefit-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.08) !important; border-color: var(--gi-red); }
    
    .roadmap-container { background: white; border-radius: 16px; padding: 24px 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); border: 1px solid var(--gi-border); }
    
    .roadmap-steps { display: flex; justify-content: space-between; align-items: flex-start; position: relative; gap: 10px; }
    .roadmap-steps::before { content: ''; position: absolute; top: 28px; left: 15%; right: 15%; height: 3px; background: linear-gradient(90deg, var(--gi-success) 0%, var(--gi-warning) 50%, #e9ecef 100%); z-index: 0; }
    
    .roadmap-item { flex: 1; min-width: 85px; text-align: center; position: relative; z-index: 2; background: white; padding: 0 5px; }
    
    .step-icon { width: 50px; height: 50px; background: white; border: 2.5px solid #e9ecef; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-weight: 600; font-size: 1.1rem; color: var(--gi-gray); transition: all 0.3s ease; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
    .roadmap-item.completed .step-icon { background: var(--gi-success); border-color: var(--gi-success); color: white; }
    .roadmap-item.active .step-icon { background: var(--gi-red); border-color: var(--gi-red); color: white; box-shadow: 0 5px 15px rgba(204,0,0,0.2); }
    .roadmap-item.highlight .step-icon { background: var(--gi-warning); border-color: var(--gi-warning); color: white; }
    
    .step-badge { display: inline-block; background: #f1f3f5; color: #495057; font-size: 0.65rem; font-weight: 700; padding: 3px 8px; border-radius: 20px; margin-bottom: 8px; letter-spacing: 0.3px; }
    .step-title { font-weight: 700; font-size: 0.8rem; margin-bottom: 4px; color: #2D3748; }
    .step-desc { font-size: 0.7rem; color: var(--gi-gray); line-height: 1.3; }
    .roadmap-item.completed .step-title { color: var(--gi-success); }
    .roadmap-item.active .step-title { color: var(--gi-red); }
    .roadmap-item.highlight .step-title { color: var(--gi-warning); }
    
    .path-header { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
    .path-icon { width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
    .path-icon.individual { background: var(--gi-red); }
    .path-icon.group { background: var(--gi-dark); }
    
    .accordion-button { font-size: 0.9rem; padding: 15px 20px; font-weight: 500; }
    .accordion-button:not(.collapsed) { background: var(--gi-red-light); color: var(--gi-red); font-weight: 600; }
    .accordion-button:focus { box-shadow: none; border-color: rgba(204,0,0,0.2); }
    .accordion-body { font-size: 0.9rem; line-height: 1.6; }
    
    .alert-important { background: #FFF8E1; border-left: 5px solid #FF9800; border-radius: 10px; padding: 14px 18px; font-size: 0.85rem; }

    .toast-custom { position: fixed; bottom: 25px; right: 25px; z-index: 9999; background: white; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.15); border-left: 5px solid var(--gi-success); animation: slideIn 0.3s ease; }
    .toast-custom.error { border-left-color: #dc3545; }
    .toast-body-custom { display: flex; align-items: center; gap: 12px; padding: 14px 20px; font-size: 0.9rem; }
    
    @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    
    /* ========== RESPONSIVE STYLES ========== */
    @media (max-width: 992px) {
        .roadmap-steps {
            flex-wrap: wrap;
            justify-content: flex-start;
            gap: 20px 15px;
        }
        .roadmap-steps::before { 
            display: none; 
        }
        .roadmap-item { 
            flex: 0 0 calc(33.333% - 15px);
            min-width: 100px;
            margin-bottom: 10px;
        }
    }

    @media (max-width: 768px) {
        .hero-title { font-size: 1.8rem; } 
        .carousel-item { height: 450px; } 
        .section-title { font-size: 1.5rem; }

        .roadmap-steps {
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px 10px;
        }
        
        .roadmap-item {
            flex: 0 0 calc(50% - 10px);
            min-width: 120px;
            padding: 0 8px;
            margin-bottom: 5px;
        }
        
        .roadmap-item:nth-child(1),
        .roadmap-item:nth-child(2),
        .roadmap-item:nth-child(3) {
            flex: 0 0 calc(33.333% - 10px);
        }
        
        .roadmap-item:nth-child(4),
        .roadmap-item:nth-child(5) {
            flex: 0 0 calc(50% - 10px);
        }
        
        .step-icon {
            width: 45px;
            height: 45px;
            font-size: 1rem;
        }
        
        .step-badge {
            font-size: 0.6rem;
            padding: 2px 6px;
        }
        
        .step-title {
            font-size: 0.75rem;
        }
        
        .step-desc {
            font-size: 0.65rem;
        }

        .d-flex.justify-content-end {
            justify-content: center !important;
            flex-wrap: wrap;
        }
        
        .path-header {
            justify-content: center;
            text-align: center;
        }
        
        .path-header .badge {
            display: block;
            margin: 5px auto 0;
        }
    }

    @media (max-width: 576px) { 
        .hero-title { font-size: 1.6rem; } 
        .carousel-item { height: 400px; }

        .roadmap-container {
            padding: 16px 10px;
        }

        .roadmap-steps {
            gap: 15px 8px;
        }
        
        .roadmap-item {
            flex: 0 0 calc(50% - 8px);
            min-width: 100px;
            padding: 0 4px;
        }
        
        .roadmap-item:nth-child(1),
        .roadmap-item:nth-child(2),
        .roadmap-item:nth-child(3) {
            flex: 0 0 calc(33.333% - 8px);
            min-width: 80px;
        }
        
        .roadmap-item:nth-child(4),
        .roadmap-item:nth-child(5) {
            flex: 0 0 calc(50% - 8px);
        }
        
        .step-icon {
            width: 40px;
            height: 40px;
            font-size: 0.9rem;
        }
        
        .step-title {
            font-size: 0.7rem;
        }
        
        .step-desc {
            font-size: 0.6rem;
        }
        
        .step-badge {
            font-size: 0.55rem;
        }
    }
</style>

<!-- HERO SECTION -->
<section id="hero">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80') center/cover;">
                <div class="carousel-container">
                    <div class="carousel-content text-center text-white px-3">
                        @php $logoPath = 'images/logo_gi.png'; $logoExists = file_exists(public_path($logoPath)); @endphp
                        @if($logoExists) <img src="{{ asset($logoPath) }}" class="mb-4 hero-logo" alt="Global Intermedia">
                        @else <div class="mb-4 mx-auto d-flex align-items-center justify-content-center" style="width: 160px; height: 60px; background: #CC0000; color: white; font-weight: bold; font-size: 1.5rem; border-radius: 8px;">GI</div> @endif
                        <h1 class="hero-title mb-3">PROGRAM <span style="color: #FF6B6B;">MAGANG & PKL</span></h1>
                        <h3 class="hero-subtitle mb-3">Raih Pengalaman Nyata di Perusahaan Teknologi</h3>
                        <p class="mb-4" style="font-size: 1rem; max-width: 600px; margin: 0 auto; opacity: 0.9;">Global Intermedia membuka kesempatan magang bagi siswa/mahasiswa di bidang teknologi informasi</p>
                        <div class="d-flex gap-3 justify-content-center flex-wrap">
                            <a href="{{ route('user.pilih-tipe') }}" class="btn btn-danger hero-btn" style="background: #CC0000; border: none;"><i class="fas fa-rocket me-2"></i>DAFTAR SEKARANG</a>
                            <a href="#alur-pendaftaran" class="btn btn-outline-light hero-btn"><i class="fas fa-info-circle me-2"></i>CARA DAFTAR</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item" style="background: linear-gradient(rgba(204,0,0,0.8), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80') center/cover;">
                <div class="carousel-container">
                    <div class="carousel-content text-center text-white px-3">
                        <h1 class="hero-title mb-3">BELAJAR DARI<br><span style="color: #FFD700;">PROFESIONAL IT</span></h1>
                        <h3 class="hero-subtitle mb-3">Dibimbing Tim Berpengalaman 14+ Tahun</h3>
                        <p class="mb-4" style="font-size: 1rem; opacity: 0.9;">700+ proyek teknologi untuk 120+ instansi</p>
                        <a href="#tentang-kami" class="btn btn-outline-light hero-btn"><i class="fas fa-building me-2"></i>TENTANG KAMI</a>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
    </div>
</section>

<!-- TENTANG KAMI - RINGKAS & PROFESIONAL -->
<section id="tentang-kami" class="py-5" style="background: white;">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">Tentang <span style="color: var(--gi-red);">Global Intermedia</span></h2>
            <p class="section-subtitle">Perusahaan Teknologi Informasi Terpercaya Sejak 2004</p>
        </div>
        
        <div class="row g-5 align-items-start">
            <div class="col-lg-7">
                <p style="color: #4A5568; line-height: 1.8; text-align: justify;" class="mb-3">
                    <strong>Global Intermedia (G-IM)</strong> didirikan secara resmi pada 1 Maret 2004 di Yogyakarta. 
                    Berawal dari semangat menghadirkan solusi teknologi informasi yang inovatif, kami kini telah 
                    dipercaya oleh 120+ instansi pemerintah dan swasta di seluruh Indonesia.
                </p>
                <p style="color: #4A5568; line-height: 1.8; text-align: justify;">
                    Spesialisasi kami meliputi konsultasi, analisis, desain, dan implementasi sistem informasi. 
                    Dengan tim yang berpengalaman belasan tahun, kami berkomitmen menghadirkan aplikasi yang handal, 
                    dinamis, dan user-friendly. Kepuasan klien adalah fokus utama kami.
                </p>
                
                <div class="row mt-4 g-3">
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle" style="color: var(--gi-success);"></i>
                            <span style="font-size: 0.9rem;">Software Development</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle" style="color: var(--gi-success);"></i>
                            <span style="font-size: 0.9rem;">IT Infrastructure</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle" style="color: var(--gi-success);"></i>
                            <span style="font-size: 0.9rem;">Digital Transformation</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle" style="color: var(--gi-success);"></i>
                            <span style="font-size: 0.9rem;">IT Consulting</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-5">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="stat-card bg-white p-3 rounded-3 text-center shadow-sm">
                            <i class="fas fa-calendar-alt fa-2x mb-2" style="color: var(--gi-red);"></i>
                            <h3 class="fw-bold mb-1" style="color: var(--gi-red);">2004</h3>
                            <p class="mb-0 small text-muted">Berdiri Sejak</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card bg-white p-3 rounded-3 text-center shadow-sm">
                            <i class="fas fa-map-marker-alt fa-2x mb-2" style="color: var(--gi-red);"></i>
                            <h3 class="fw-bold mb-1" style="color: var(--gi-red);">Yogyakarta</h3>
                            <p class="mb-0 small text-muted">Kantor Pusat</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card bg-white p-3 rounded-3 text-center shadow-sm">
                            <i class="fas fa-users fa-2x mb-2" style="color: var(--gi-red);"></i>
                            <h3 class="fw-bold mb-1" style="color: var(--gi-red);">88+</h3>
                            <p class="mb-0 small text-muted">Tim Profesional</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card bg-white p-3 rounded-3 text-center shadow-sm">
                            <i class="fas fa-trophy fa-2x mb-2" style="color: var(--gi-red);"></i>
                            <h3 class="fw-bold mb-1" style="color: var(--gi-red);">700+</h3>
                            <p class="mb-0 small text-muted">Proyek Selesai</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 p-3" style="background: #F8F9FA; border-radius: 12px;">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-certificate fa-2x" style="color: var(--gi-red);"></i>
                        <div>
                            <small class="text-muted d-block">Terakreditasi & Berizin Resmi</small>
                            <strong>PT. Global Intermedia Nusantara</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Visi & Misi Ringkas -->
        <div class="row mt-5 pt-3 g-4">
            <div class="col-md-4">
                <div class="p-4" style="background: #F8F9FA; border-radius: 16px; height: 100%;">
                    <div class="mb-3">
                        <div style="width: 50px; height: 50px; background: var(--gi-red-light); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-eye fa-xl" style="color: var(--gi-red);"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-2">Visi</h5>
                    <p class="text-muted small">Integrasi Nusantara melalui teknologi informasi yang handal dan inovatif.</p>
                </div>
            </div>
            <div class="col-md-8">
                <div class="p-4" style="background: #F8F9FA; border-radius: 16px; height: 100%;">
                    <div class="mb-3">
                        <div style="width: 50px; height: 50px; background: var(--gi-red-light); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-bullseye fa-xl" style="color: var(--gi-red);"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-3">Misi</h5>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <div class="d-flex gap-2">
                                <i class="fas fa-check-circle" style="color: var(--gi-success); font-size: 0.8rem; margin-top: 2px;"></i>
                                <span class="small">Pelayanan terbaik berorientasi kepuasan pelanggan</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="d-flex gap-2">
                                <i class="fas fa-check-circle" style="color: var(--gi-success); font-size: 0.8rem; margin-top: 2px;"></i>
                                <span class="small">Jaminan kualitas prima</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="d-flex gap-2">
                                <i class="fas fa-check-circle" style="color: var(--gi-success); font-size: 0.8rem; margin-top: 2px;"></i>
                                <span class="small">Pengembangan sistem terintegrasi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ALUR PENDAFTARAN -->
<section id="alur-pendaftaran" class="py-5" style="background: white;">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">Alur <span style="color: var(--gi-red);">Pendaftaran</span></h2>
            <p class="section-subtitle">Pilih jalur dan ikuti langkah-langkahnya</p>
        </div>
        <div class="mb-5">
            <div class="path-header"><div class="path-icon individual"><i class="fas fa-user text-white fa-lg"></i></div><div><h4 class="fw-bold mb-0" style="color: var(--gi-red);">Jalur Individu</h4><span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 mt-1" style="font-size: 0.7rem;">Pendaftaran Perorangan</span></div></div>
            <div class="roadmap-container"><div class="roadmap-steps">
                <div class="roadmap-item completed"><span class="step-badge">Langkah 1</span><div class="step-icon"><i class="fas fa-user-plus"></i></div><div class="step-title">Daftar</div><div class="step-desc">Pilih jalur Individu</div></div>
                <div class="roadmap-item completed"><span class="step-badge">Langkah 2</span><div class="step-icon"><i class="fas fa-file-alt"></i></div><div class="step-title">Isi Form</div><div class="step-desc">Data diri & upload surat</div></div>
                <div class="roadmap-item highlight"><span class="step-badge" style="background: #FFF3E0; color: #E65100;">PENTING ⭐</span><div class="step-icon"><i class="fas fa-key"></i></div><div class="step-title">Salin Kode</div><div class="step-desc">Simpan sebelum submit</div></div>
                <div class="roadmap-item"><span class="step-badge">Langkah 4</span><div class="step-icon"><i class="fas fa-check-circle"></i></div><div class="step-title">Selesai</div><div class="step-desc">Submit & cek status</div></div>
            </div></div>
            <div class="d-flex justify-content-end gap-2 mt-3"><a href="{{ route('user.status') }}" class="btn btn-outline-secondary btn-sm px-4" style="border-radius: 30px;"><i class="fas fa-search me-1"></i>Cek Status</a><a href="{{ route('user.pilih-tipe') }}" class="btn btn-danger btn-sm px-4" style="border-radius: 30px; background: var(--gi-red);"><i class="fas fa-user-plus me-1"></i>Daftar Individu</a></div>
        </div>
        <div>
            <div class="path-header"><div class="path-icon group"><i class="fas fa-users text-white fa-lg"></i></div><div><h4 class="fw-bold mb-0" style="color: var(--gi-dark);">Jalur Kelompok</h4><span class="badge bg-dark bg-opacity-10 text-dark px-2 py-1 mt-1" style="font-size: 0.7rem;">2-5 Orang per Kelompok</span></div></div>
            <div class="roadmap-container"><div class="roadmap-steps">
                <div class="roadmap-item completed"><span class="step-badge">Langkah 1</span><div class="step-icon"><i class="fas fa-users"></i></div><div class="step-title">Daftar</div><div class="step-desc">Pilih jalur Kelompok</div></div>
                <div class="roadmap-item completed"><span class="step-badge">Langkah 2</span><div class="step-icon"><i class="fas fa-clipboard-list"></i></div><div class="step-title">Isi Form</div><div class="step-desc">Data ketua & kelompok</div></div>
                <div class="roadmap-item highlight"><span class="step-badge" style="background: #FFF3E0; color: #E65100;">PENTING ⭐</span><div class="step-icon"><i class="fas fa-key"></i></div><div class="step-title">Salin Kode</div><div class="step-desc">Simpan kode kelompok</div></div>
                <div class="roadmap-item"><span class="step-badge">Langkah 4</span><div class="step-icon"><i class="fas fa-user-friends"></i></div><div class="step-title">Tambah Anggota</div><div class="step-desc">Input data anggota</div></div>
                <div class="roadmap-item"><span class="step-badge">Langkah 5</span><div class="step-icon"><i class="fas fa-flag-checkered"></i></div><div class="step-title">Selesai</div><div class="step-desc">Cek status pendaftaran</div></div>
            </div></div>
            <div class="d-flex justify-content-end gap-2 mt-3"><a href="{{ route('user.status') }}" class="btn btn-outline-secondary btn-sm px-4" style="border-radius: 30px;"><i class="fas fa-search me-1"></i>Cek Status</a><a href="{{ route('user.pilih-tipe') }}" class="btn text-white btn-sm px-4" style="background: var(--gi-dark); border-radius: 30px;"><i class="fas fa-users me-1"></i>Daftar Kelompok</a></div>
        </div>
        <div class="alert-important mt-5"><div class="d-flex gap-3 align-items-start"><i class="fas fa-exclamation-triangle fa-lg" style="color: #E65100;"></i><div><strong class="d-block mb-1">PENTING!</strong><span>Kode pendaftaran akan muncul sebelum Anda menekan tombol submit. Pastikan untuk menyalin dan menyimpan kode tersebut untuk mengecek status pendaftaran.</span></div></div></div>
    </div>
</section>

<!-- MENGAPA GI -->
<section class="py-5" style="background: #F8F9FA;">
    <div class="container">
        <div class="section-header text-center"><h2 class="section-title">Mengapa <span style="color: var(--gi-red);">Global Intermedia</span>?</h2><p class="section-subtitle">Keunggulan magang di perusahaan kami</p></div>
        <div class="row g-4">
            <div class="col-md-4"><div class="benefit-card p-4 bg-white rounded-3 h-100"><div class="mb-3"><div style="width: 55px; height: 55px; background: #FFEBEB; border-radius: 12px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-briefcase fa-xl" style="color: var(--gi-red);"></i></div></div><h5 class="fw-bold mb-2">Pengalaman Nyata</h5><p class="text-muted mb-0 small">Terlibat langsung dalam proyek-proyek real yang dikerjakan oleh tim profesional.</p></div></div>
            <div class="col-md-4"><div class="benefit-card p-4 bg-white rounded-3 h-100"><div class="mb-3"><div style="width: 55px; height: 55px; background: #FFEBEB; border-radius: 12px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-chalkboard-user fa-xl" style="color: var(--gi-red);"></i></div></div><h5 class="fw-bold mb-2">Mentor Berpengalaman</h5><p class="text-muted mb-0 small">Dibimbing langsung oleh para ahli dengan pengalaman lebih dari 5 tahun di bidangnya.</p></div></div>
            <div class="col-md-4"><div class="benefit-card p-4 bg-white rounded-3 h-100"><div class="mb-3"><div style="width: 55px; height: 55px; background: #FFEBEB; border-radius: 12px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-certificate fa-xl" style="color: var(--gi-red);"></i></div></div><h5 class="fw-bold mb-2">Sertifikat Resmi</h5><p class="text-muted mb-0 small">Dapatkan sertifikat yang diakui industri sebagai bukti kompetensi Anda.</p></div></div>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="py-5" style="background: white;">
    <div class="container">
        <div class="section-header text-center"><h2 class="section-title">Pertanyaan <span style="color: var(--gi-red);">Umum</span></h2><p class="section-subtitle">Hal yang sering ditanyakan seputar program magang</p></div>
        <div class="row"><div class="col-lg-8 mx-auto"><div class="accordion" id="faqAccordion">
            <div class="accordion-item mb-3 border-0 shadow-sm rounded-3 overflow-hidden"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1"><i class="fas fa-calendar-alt me-2" style="color: var(--gi-red);"></i> Kapan periode pendaftaran dibuka?</button></h2><div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">Pendaftaran program magang dibuka sepanjang tahun. Namun, kami sarankan untuk mendaftar minimal 2 minggu sebelum periode magang yang diinginkan dimulai.</div></div></div>
            <div class="accordion-item mb-3 border-0 shadow-sm rounded-3 overflow-hidden"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2"><i class="fas fa-code me-2" style="color: var(--gi-red);"></i> Apakah harus mahir coding?</button></h2><div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">Tidak harus. Kami membuka kesempatan untuk berbagai posisi, termasuk non-teknis seperti UI/UX design, project management, dan technical writing. Yang terpenting adalah kemauan untuk belajar.</div></div></div>
            <div class="accordion-item mb-3 border-0 shadow-sm rounded-3 overflow-hidden"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3"><i class="fas fa-clock me-2" style="color: var(--gi-red);"></i> Berapa lama durasi magang?</button></h2><div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">Durasi minimal magang adalah 3 bulan dan maksimal 12 bulan, disesuaikan dengan kebutuhan institusi pendidikan dan ketersediaan posisi.</div></div></div>
            <div class="accordion-item mb-3 border-0 shadow-sm rounded-3 overflow-hidden"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4"><i class="fas fa-key me-2" style="color: var(--gi-red);"></i> Kapan kode pendaftaran muncul?</button></h2><div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">Kode pendaftaran akan muncul secara otomatis setelah semua field formulir terisi dengan benar, sebelum Anda menekan tombol submit.</div></div></div>
        </div></div></div>
    </div>
</section>

<!-- CTA -->
<section class="py-5 text-white text-center" style="background: linear-gradient(135deg, #CC0000 0%, #990000 100%);">
    <div class="container"><h3 class="fw-bold mb-3">Siap Memulai Karir di Dunia IT?</h3><p class="mb-4" style="opacity: 0.95; max-width: 600px; margin: 0 auto;">Daftar sekarang dan dapatkan pengalaman berharga bersama tim profesional Global Intermedia</p><div class="d-flex justify-content-center gap-3 flex-wrap"><a href="{{ route('user.pilih-tipe') }}" class="btn btn-light btn-lg px-5 fw-bold" style="border-radius: 50px;"><i class="fas fa-user me-2"></i>Daftar Individu</a><a href="{{ route('user.pilih-tipe') }}" class="btn btn-outline-light btn-lg px-5 fw-bold" style="border-radius: 50px;"><i class="fas fa-users me-2"></i>Daftar Kelompok</a></div></div>
</section>

<!-- SUPPORT -->
<section class="py-4 bg-white border-top">
    <div class="container"><div class="d-flex flex-wrap align-items-center justify-content-between"><div class="d-flex align-items-center gap-3"><div style="width: 50px; height: 50px; background: #FFEBEB; border-radius: 12px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-headset fa-xl" style="color: var(--gi-red);"></i></div><div><h6 class="fw-bold mb-1">Butuh Bantuan?</h6><p class="text-muted mb-0 small">Tim support kami siap membantu Anda</p><small class="text-muted"><i class="far fa-clock me-1"></i>Senin - Jumat, 08:00 - 17:00 WIB</small></div></div><div class="mt-3 mt-sm-0"><a href="https://wa.me/62817456225" class="btn btn-success px-4 py-2" style="border-radius: 50px;"><i class="fab fa-whatsapp me-2"></i>Chat via WhatsApp</a></div></div></div>
</section>

@push('scripts')
<script>
$(document).ready(function() {
    new bootstrap.Carousel(document.getElementById('heroCarousel'), { interval: 5000, pause: 'hover' });
    
    $('a[href^="#"]').on('click', function(e) {
        if (this.hash !== "") { 
            e.preventDefault(); 
            var target = $(this.hash); 
            if (target.length) { 
                $('html, body').animate({ scrollTop: target.offset().top - 70 }, 500); 
            } 
        }
    });
});
</script>
@endpush

@endsection