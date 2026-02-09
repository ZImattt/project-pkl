@extends('layouts.app')

@section('title', 'Cek Status Pendaftaran')
@section('content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header Compact -->
            <div class="text-center mb-4">
                <h1 class="hero-title mb-2">
                    <i class="fas fa-search me-2"></i>Cek Status Pendaftaran
                </h1>
                <p class="text-muted">
                    Masukkan nomor WhatsApp yang Anda gunakan saat pendaftaran untuk mengecek status.
                </p>
            </div>

            <!-- SETELAH HEADER, SEBELUM CARD FORM -->
<div class="info-card mb-4">
    <div class="info-card-header">
        <i class="fas fa-info-circle me-2"></i>
        <span class="fw-bold">Cara Cek Status & Info Penting</span>
    </div>
    <div class="info-card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="info-content">
                        <h6 class="fw-bold mb-1">Waktu Verifikasi</h6>
                        <p class="mb-0 small">1-3 hari kerja setelah pendaftaran</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div class="info-content">
                        <h6 class="fw-bold mb-1">Notifikasi WhatsApp</h6>
                        <p class="mb-0 small">Hasil dikirim ke nomor yang didaftarkan</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div class="info-content">
                        <h6 class="fw-bold mb-1">Status "Menunggu"</h6>
                        <p class="mb-0 small">Data sedang dalam proses peninjauan admin</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="info-content">
                        <h6 class="fw-bold mb-1">Keamanan Data</h6>
                        <p class="mb-0 small">Hanya bisa diakses dengan nomor WA Anda</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
            <!-- Search Form -->
            <div class="card-custom mb-3">
                <div class="card-header-custom">
                    <h4 class="mb-0"><i class="fas fa-mobile-alt me-2"></i>Cari Pendaftaran</h4>
                </div>
                <div class="card-body p-3">
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('student.status.check') }}" method="POST" id="searchForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="search_whatsapp" class="form-label-custom">
                                <i class="fab fa-whatsapp me-1"></i> Nomor WhatsApp <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">+62</span>
                                <input type="text" class="form-control-custom @error('whatsapp') is-invalid @enderror" 
                                       id="search_whatsapp" name="whatsapp" 
                                       value="{{ old('whatsapp', request('whatsapp')) }}" 
                                       placeholder="81234567890" 
                                       pattern="[0-9]{9,13}" 
                                       title="Masukkan nomor tanpa 0 di depan" required>
                            </div>
                            @error('whatsapp')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Masukkan nomor yang Anda daftarkan (tanpa 0 di depan)</small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary-custom" id="searchBtn">
                                <i class="fas fa-search me-2"></i>Cari Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results - AUTO SCROLL KE SINI SETELAH SEARCH -->
            <div id="searchResults">
                @if(isset($registration))
                <div class="card-custom mb-3" id="resultCard">
                    <div class="card-header-custom">
                        <h4 class="mb-0">
                            <i class="fas fa-id-card me-2"></i>
                            Data Pendaftaran
                        </h4>
                    </div>
                    <div class="card-body p-3">
                        <!-- Status Badge -->
                        <div class="text-center mb-4">
                            @php
                                $statusText = match($registration->status) {
                                    'pending' => 'Menunggu',
                                    'approved' => 'Disetujui',
                                    'rejected' => 'Ditolak',
                                    default => $registration->status
                                };
                            @endphp
                            
                            @if($registration->status == 'pending')
                                <div class="status-badge status-pending">
                                    <div class="status-icon">
                                        <i class="fas fa-clock fa-2x"></i>
                                    </div>
                                    <div class="status-content">
                                        <div class="status-title">MENUNGGU VERIFIKASI</div>
                                        <div class="status-desc">Pendaftaran sedang dalam proses peninjauan oleh admin</div>
                                    </div>
                                </div>
                            @elseif($registration->status == 'approved')
                                <div class="status-badge status-approved">
                                    <div class="status-icon">
                                        <i class="fas fa-check-circle fa-2x"></i>
                                    </div>
                                    <div class="status-content">
                                        <div class="status-title">DISETUJUI</div>
                                        <div class="status-desc">Selamat! Pendaftaran Anda telah diterima</div>
                                    </div>
                                </div>
                            @elseif($registration->status == 'rejected')
                                <div class="status-badge status-rejected">
                                    <div class="status-icon">
                                        <i class="fas fa-times-circle fa-2x"></i>
                                    </div>
                                    <div class="status-content">
                                        <div class="status-title">DITOLAK</div>
                                        <div class="status-desc">Maaf, pendaftaran Anda tidak dapat diproses</div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Registration Details -->
                        <div class="row">
                            <!-- Basic Info -->
                            <div class="col-md-6 mb-3">
                                <div class="detail-card">
                                    <label class="form-label text-muted small">Nama Lengkap</label>
                                    <p class="fw-bold detail-value">{{ $registration->nama_lengkap }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="detail-card">
                                    <label class="form-label text-muted small">ID Pendaftaran</label>
                                    <p class="fw-bold detail-value text-primary">{{ $registration->registration_id ?? 'N/A' }}</p>
                                </div>
                            </div>
                            
                            <!-- Contact Info -->
                            <div class="col-md-6 mb-3">
                                <div class="detail-card">
                                    <label class="form-label text-muted small">Email</label>
                                    <p class="fw-bold detail-value">{{ $registration->email }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="detail-card">
                                    <label class="form-label text-muted small">WhatsApp</label>
                                    <p class="fw-bold detail-value text-primary">{{ $registration->no_whatsapp }}</p>
                                </div>
                            </div>
                            
                            <!-- Education Info -->
                            <div class="col-md-6 mb-3">
                                <div class="detail-card">
                                    <label class="form-label text-muted small">Jenis Pendidikan</label>
                                    <p class="fw-bold detail-value">
                                        @if($registration->jenis_pendidikan == 'smk')
                                            <span class="badge bg-primary">SMK/SMA</span>
                                        @else
                                            <span class="badge bg-success">UNIVERSITAS</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="detail-card">
                                    <label class="form-label text-muted small">{{ $registration->jenis_pendidikan == 'smk' ? 'Kelas' : 'Semester' }}</label>
                                    <p class="fw-bold detail-value">
                                        @if($registration->jenis_pendidikan == 'smk')
                                            {{ $registration->kelas ?? '-' }}
                                        @else
                                            {{ $registration->semester ?? '-' }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <div class="detail-card">
                                    <label class="form-label text-muted small">Institusi</label>
                                    <p class="fw-bold detail-value">
                                        @if($registration->jenis_pendidikan == 'smk')
                                            {{ $registration->sekolah ?? '-' }}
                                        @else
                                            {{ $registration->universitas ?? '-' }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <div class="detail-card">
                                    <label class="form-label text-muted small">Jurusan/Program Studi</label>
                                    <p class="fw-bold detail-value">
                                        @if($registration->jenis_pendidikan == 'smk')
                                            {{ $registration->jurusan_smk ?? '-' }}
                                        @else
                                            {{ $registration->jurusan_univ ?? '-' }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <!-- PKL Period -->
                            <div class="col-md-6 mb-3">
                                <div class="detail-card">
                                    <label class="form-label text-muted small">Tanggal Mulai PKL</label>
                                    <p class="fw-bold detail-value">
                                        {{ \Carbon\Carbon::parse($registration->tanggal_mulai)->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="detail-card">
                                    <label class="form-label text-muted small">Tanggal Selesai PKL</label>
                                    <p class="fw-bold detail-value">
                                        {{ \Carbon\Carbon::parse($registration->tanggal_selesai)->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Duration -->
                            <div class="col-md-12 mb-3">
                                <div class="detail-card">
                                    @php
                                        $start = \Carbon\Carbon::parse($registration->tanggal_mulai);
                                        $end = \Carbon\Carbon::parse($registration->tanggal_selesai);
                                        $duration = $start->diffInDays($end);
                                    @endphp
                                    <label class="form-label text-muted small">Durasi PKL</label>
                                    <p class="fw-bold detail-value">{{ $duration }} hari</p>
                                </div>
                            </div>
                            
                        <!-- Registration Dates - FIXED TIMESTAMP -->
                        <div class="col-md-6 mb-3">
                            <div class="detail-card">
                                <label class="form-label text-muted small">Tanggal Daftar</label>
                                <p class="fw-bold detail-value">
                                    {{ \Carbon\Carbon::parse($registration->created_at)->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="detail-card">
                                <label class="form-label text-muted small">Status Update Terakhir</label>
                                <p class="fw-bold detail-value">
                                    @if($registration->status_updated_at)
                                        {{ \Carbon\Carbon::parse($registration->status_updated_at)->format('d/m/Y') }}
                                    @else
                                        {{ \Carbon\Carbon::parse($registration->created_at)->format('d/m/Y') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                            
                            <!-- Surat Pengantar -->
                            @if($registration->upload_surat_pengantar)
                            <div class="col-12 mb-4">
                                <div class="detail-card">
                                    <label class="form-label text-muted small">Surat Pengantar</label>
                                    <div class="d-flex align-items-center mt-1">
                                        <i class="fas fa-file-pdf text-danger me-2 fs-5"></i>
                                        <a href="{{ route('view.surat', ['filename' => $registration->upload_surat_pengantar]) }}" 
                                           target="_blank" class="file-link">
                                            <span>Lihat Surat Pengantar</span>
                                            <i class="fas fa-external-link-alt ms-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Admin Notes if exists -->
                            @if($registration->admin_notes)
                            <div class="col-12 mb-4">
                                <div class="admin-notes-card">
                                    <div class="d-flex">
                                        <i class="fas fa-sticky-note me-3 fa-lg"></i>
                                        <div>
                                            <h6 class="mb-2 fw-bold">Catatan Admin:</h6>
                                            <p class="mb-0">{{ $registration->admin_notes }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Important Notice -->
                        <div class="notice-card notice-warning mt-4">
                            <div class="d-flex">
                                <i class="fas fa-info-circle me-3 fa-lg"></i>
                                <div>
                                    <h6 class="mb-2 fw-bold">Informasi Penting</h6>
                                    <p class="mb-1">• Status penerimaan akan diinformasikan via WhatsApp oleh admin.</p>
                                    <p class="mb-1">• Pastikan WhatsApp Anda aktif untuk menerima notifikasi.</p>
                                    <p class="mb-0">• Hubungi admin jika ada pertanyaan terkait pendaftaran.</p>
                                </div>
                            </div>
                        </div>

                        <!-- WhatsApp Contact Info -->
                        <div class="notice-card notice-info mt-3">
                            <div class="d-flex align-items-center">
                                <i class="fab fa-whatsapp me-3 fa-lg"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold">Butuh bantuan?</h6>
                                    <p class="mb-0">Hubungi admin PKL: <strong>+62 812-3456-7890</strong></p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <a href="{{ route('student.register') }}" class="btn btn-outline-custom flex-grow-1">
                                <i class="fas fa-file-alt me-2"></i>Daftar Lagi
                            </a>
                            <a href="{{ route('student.home') }}" class="btn btn-outline-custom flex-grow-1">
                                <i class="fas fa-home me-2"></i>Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>
                @elseif(session('not_found'))
                <div class="no-data-card text-center p-5" id="resultCard">
                    <div class="no-data-icon mb-3">
                        <i class="fas fa-search fa-3x"></i>
                    </div>
                    <h5 class="mb-2 fw-bold">Data Tidak Ditemukan</h5>
                    <p class="mb-4">Nomor WhatsApp <strong>{{ session('search_number') }}</strong> tidak terdaftar dalam sistem.</p>
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <a href="{{ route('student.register') }}" class="btn btn-primary-custom">
                            <i class="fas fa-file-alt me-2"></i>Daftar Sekarang
                        </a>
                        <a href="{{ route('student.status') }}" class="btn btn-outline-custom">
                            <i class="fas fa-search me-2"></i>Coba Lagi
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* ========== INFO CARD STYLES - RED VERSION ========== */
.info-card {
    background: linear-gradient(135deg, #fff5f5 0%, #ffe6e6 100%);
    border-radius: 12px;
    border: 2px solid #ffcccc;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(204, 0, 0, 0.05);
}

.info-card:hover {
    border-color: var(--primary-red);
    box-shadow: 0 8px 20px rgba(204, 0, 0, 0.1);
    transform: translateY(-3px);
}

.info-card-header {
    background: linear-gradient(135deg, #cc0000 0%, #b30000 100%);
    color: white;
    padding: 0.85rem 1.25rem;
    font-size: 1rem;
    display: flex;
    align-items: center;
    border-bottom: 2px solid #990000;
}

.info-card-header i {
    font-size: 1.2rem;
}

.info-card-body {
    padding: 1.5rem;
}

.info-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 1rem;
    background: white;
    border-radius: 10px;
    border: 1px solid #ffe6e6;
    border-left: 4px solid var(--primary-red);
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    height: 100%;
    box-shadow: 0 2px 4px rgba(0,0,0,0.03);
}

.info-item:hover {
    transform: translateX(8px);
    border-color: var(--primary-red);
    box-shadow: 0 6px 15px rgba(204, 0, 0, 0.12);
}

.info-icon {
    width: 42px;
    height: 42px;
    background: linear-gradient(135deg, #ffe6e6 0%, #ffcccc 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-red);
    font-size: 1.1rem;
    flex-shrink: 0;
    transition: all 0.3s ease;
    border: 1px solid #ffcccc;
}

.info-item:hover .info-icon {
    background: linear-gradient(135deg, #cc0000 0%, #b30000 100%);
    color: white;
    transform: rotate(10deg) scale(1.1);
    border-color: #cc0000;
}

.info-content h6 {
    font-size: 0.95rem;
    color: #333;
    margin-bottom: 0.35rem;
    font-weight: 700;
}

.info-content p {
    color: #666;
    font-size: 0.85rem;
    line-height: 1.4;
    margin-bottom: 0;
}

/* Hover effect khusus untuk setiap icon */
.info-item:nth-child(1):hover .info-icon {
    background: linear-gradient(135deg, #ff6b6b 0%, #ff5252 100%);
}

.info-item:nth-child(2):hover .info-icon {
    background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
}

.info-item:nth-child(3):hover .info-icon {
    background: linear-gradient(135deg, #ffa726 0%, #ff9800 100%);
}

.info-item:nth-child(4):hover .info-icon {
    background: linear-gradient(135deg, #5a67d8 0%, #4c51bf 100%);
}

/* Animasi untuk card muncul */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.info-card {
    animation: fadeInUp 0.6s ease forwards;
}

/* Responsive */
@media (max-width: 768px) {
    .info-card {
        margin-left: -10px;
        margin-right: -10px;
        border-radius: 8px;
    }
    
    .info-card-body {
        padding: 1.25rem;
    }
    
    .info-item {
        padding: 0.85rem;
    }
    
    .info-icon {
        width: 38px;
        height: 38px;
        font-size: 1rem;
    }
}

@media (max-width: 576px) {
    .info-card {
        border-radius: 6px;
    }
    
    .info-card-header {
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
    }
    
    .info-card-body {
        padding: 1rem;
    }
    
    .info-item {
        padding: 0.75rem;
        gap: 10px;
    }
    
    .info-icon {
        width: 36px;
        height: 36px;
    }
    
    .info-content h6 {
        font-size: 0.9rem;
    }
    
    .info-content p {
        font-size: 0.8rem;
    }
}

@media (max-width: 400px) {
    .info-item {
        flex-direction: column;
        text-align: center;
        gap: 8px;
    }
    
    .info-icon {
        margin: 0 auto;
    }
}

    /* Status Badge Styles */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 1rem 1.5rem;
        border-radius: 10px;
        text-align: left;
        max-width: 500px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }
    
    .status-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }
    
    .status-icon {
        font-size: 2rem;
        margin-right: 1rem;
        flex-shrink: 0;
    }
    
    .status-content {
        flex-grow: 1;
    }
    
    .status-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }
    
    .status-desc {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    /* Pending Status */
    .status-pending {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 2px solid #6c757d;
        color: #6c757d;
    }
    
    /* Approved Status */
    .status-approved {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border: 2px solid #28a745;
        color: #155724;
    }
    
    /* Rejected Status */
    .status-rejected {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        border: 2px solid #dc3545;
        color: #721c24;
    }
    
    /* Detail Cards */
    .detail-card {
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid var(--primary-red);
        height: 100%;
        transition: all 0.3s ease;
    }
    
    .detail-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .detail-value {
        color: #333;
        margin-top: 0.25rem;
        font-size: 1rem;
    }
    
    /* File Link Styling */
    .file-link {
        display: inline-flex;
        align-items: center;
        color: var(--primary-red);
        text-decoration: none;
        font-weight: 600;
        padding: 0.5rem 1rem;
        background: #fff5f5;
        border-radius: 6px;
        transition: all 0.3s ease;
        border: 1px solid var(--primary-red);
    }
    
    .file-link:hover {
        background: var(--primary-red);
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.2);
    }
    
    /* Admin Notes Card */
    .admin-notes-card {
        padding: 1.25rem;
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        border-radius: 10px;
        border-left: 4px solid #2196f3;
    }
    
    /* Notice Cards */
    .notice-card {
        padding: 1.25rem;
        border-radius: 10px;
        border-left: 4px solid;
        transition: all 0.3s ease;
    }
    
    .notice-card:hover {
        transform: translateX(5px);
    }
    
    .notice-warning {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        border-left-color: #ffc107;
        color: #856404;
    }
    
    .notice-info {
        background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
        border-left-color: #17a2b8;
        color: #0c5460;
    }
    
    /* No Data Card */
    .no-data-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
        border: 2px dashed #6c757d;
    }
    
    .no-data-icon {
        color: #6c757d;
        opacity: 0.7;
    }
    
    /* Badge styling */
    .badge {
        padding: 0.5rem 0.75rem;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    /* Button styling */
    .btn-outline-custom {
        border: 1px solid var(--primary-red);
        color: var(--primary-red);
        font-weight: 500;
    }
    
    .btn-outline-custom:hover {
        background: var(--primary-red);
        color: white;
    }
    
    /* ========== SEARCH BUTTON STYLES ========== */
    .btn-primary-custom {
        background-color: white !important;
        color: #cc0000 !important;
        border: 2px solid #cc0000 !important;
        padding: 0.75rem 2rem !important;
        font-weight: 600 !important;
        font-size: 1rem !important;
        border-radius: 8px !important;
        transition: all 0.3s ease !important;
        position: relative !important;
        overflow: hidden !important;
    }
    
    /* Button merah saat input diisi */
    .btn-primary-custom.filled {
        background-color: #cc0000 !important;
        color: white !important;
        border: 2px solid #cc0000 !important;
        box-shadow: 0 4px 12px rgba(204, 0, 0, 0.2) !important;
    }
    
    /* Hover state untuk button kosong */
    .btn-primary-custom:not(.filled):hover {
        background-color: #ffe6e6 !important;
        color: #cc0000 !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 8px rgba(204, 0, 0, 0.1) !important;
    }
    
    /* Hover state untuk button terisi */
    .btn-primary-custom.filled:hover {
        background-color: #b30000 !important;
        border-color: #b30000 !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 16px rgba(204, 0, 0, 0.3) !important;
    }
    
    /* Loading state */
    .btn-primary-custom.loading {
        background-color: #cc0000 !important;
        color: white !important;
        cursor: wait !important;
    }
    
    /* Ripple effect */
    .btn-primary-custom::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 5px;
        height: 5px;
        background: rgba(255, 255, 255, 0.5);
        opacity: 0;
        border-radius: 100%;
        transform: scale(1, 1) translate(-50%);
        transform-origin: 50% 50%;
    }
    
    .btn-primary-custom.filled::after {
        background: rgba(255, 255, 255, 0.3);
    }
    
    /* Animation for approved status */
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.02); }
        100% { transform: scale(1); }
    }
    
    .pulse {
        animation: pulse 2s infinite;
    }
    
    /* Animation for showing results */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .slide-in-up {
        animation: slideInUp 0.5s ease forwards;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .status-badge {
            flex-direction: column;
            text-align: center;
            padding: 1rem;
        }
        
        .status-icon {
            margin-right: 0;
            margin-bottom: 0.75rem;
        }
        
        .detail-card {
            padding: 0.75rem;
        }
        
        .detail-value {
            font-size: 0.95rem;
        }
        
        .notice-card {
            padding: 1rem;
        }
        
        .btn-primary-custom {
            padding: 0.65rem 1.5rem !important;
            font-size: 0.95rem !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Ambil elemen input dan button
        const searchInput = $('#search_whatsapp');
        const searchButton = $('#searchBtn');
        
        // Fungsi untuk cek apakah input sudah diisi
        function checkInput() {
            const value = searchInput.val().trim();
            
            // Jika input diisi (minimal 9 digit nomor WA)
            if (value.length >= 9) {
                // Tambah class 'filled' untuk warna merah
                searchButton.addClass('filled');
            } else {
                // Hapus class 'filled' untuk warna putih
                searchButton.removeClass('filled');
            }
        }
        
        // Cek saat halaman pertama kali load
        checkInput();
        
        // Cek setiap kali input berubah
        searchInput.on('input', function() {
            checkInput();
            
            // Format nomor (hapus non-digit dan leading 0)
            let value = $(this).val().replace(/\D/g, '');
            if (value.startsWith('0')) {
                value = value.substring(1);
            }
            $(this).val(value);
        });
        
        // Auto focus search field jika tidak ada data
        @if(!isset($registration) && !session('not_found'))
            searchInput.focus();
        @endif
        
        // Auto-dismiss alerts
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
        
        // Add animation to status badges
        $('.status-badge').each(function(index) {
            $(this).css('opacity', '0').animate({opacity: 1}, 600 + (index * 200));
        });
        
        // Add pulse animation to approved status
        @if(isset($registration) && $registration->status == 'approved')
            $('.status-approved').addClass('pulse');
        @endif
        
        // Add smooth hover to detail cards
        $('.detail-card').on('mouseenter', function() {
            $(this).css('transform', 'translateY(-2px)');
        }).on('mouseleave', function() {
            $(this).css('transform', 'translateY(0)');
        });
        
        // Scroll to results after form submit
        @if(isset($registration) || session('not_found'))
            setTimeout(function() {
                const resultsDiv = document.getElementById('searchResults');
                if (resultsDiv) {
                    resultsDiv.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    
                    const resultCard = document.getElementById('resultCard');
                    if (resultCard) {
                        resultCard.classList.add('slide-in-up');
                    }
                }
            }, 300);
        @endif
        
        // Form submission handling
        $('#searchForm').on('submit', function(e) {
            // Validasi dulu
            const value = searchInput.val().trim();
            if (value.length < 9) {
                e.preventDefault();
                
                // Tambah efek shake untuk error
                searchInput.addClass('shake');
                setTimeout(() => {
                    searchInput.removeClass('shake');
                }, 500);
                
                // Tampilkan pesan error
                searchInput.focus();
                return false;
            }
            
            // Ubah button menjadi loading
            searchButton.prop('disabled', true);
            searchButton.addClass('loading');
            searchButton.html('<i class="fas fa-spinner fa-spin me-2"></i>Mencari...');
            
            // Scroll ke form dulu
            const form = document.getElementById('searchForm');
            form.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        });
        
        // Add animation to result card when page loads with results
        @if(isset($registration) || session('not_found'))
            setTimeout(function() {
                $('#resultCard').addClass('slide-in-up');
            }, 100);
        @endif
        
        // Copy registration ID on click
        $('.detail-value.text-primary').on('click', function() {
            const text = $(this).text().trim();
            if (text) {
                navigator.clipboard.writeText(text).then(function() {
                    const originalText = $(this).text();
                    $(this).html('<i class="fas fa-check me-2"></i>Copied!');
                    
                    setTimeout(() => {
                        $(this).text(originalText);
                    }, 2000);
                }.bind(this));
            }
        });
    });
</script>
@endpush

@endsection