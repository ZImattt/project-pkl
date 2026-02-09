@extends('layouts.app')

@section('title', 'Detail Pendaftaran - Admin')
@section('content')

<div class="container-fluid py-3">
    <!-- Header Mobile Friendly -->
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-custom btn-sm me-2">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="hero-title mb-0 fs-5">
                    <i class="fas fa-file-alt me-2"></i>Detail Pendaftaran
                </h1>
                <small class="text-muted d-block d-md-none">
                    ID: {{ $registration->registration_id ?? 'N/A' }}
                </small>
            </div>
        </div>
        <div class="d-none d-md-block">
            <span class="badge bg-primary-custom fs-6 px-3 py-2">
                <i class="fas fa-hashtag me-1"></i>{{ $registration->registration_id ?? 'N/A' }}
            </span>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle me-3 fs-4"></i>
            <div>
                <h5 class="mb-1">Status Berhasil Diperbarui!</h5>
                <p class="mb-0">{{ session('success') }}</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show animate__animated animate__shakeX" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-circle me-3 fs-4"></i>
            <div>
                <h5 class="mb-1">Gagal Memperbarui Status</h5>
                <p class="mb-0">{{ session('error') }}</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Main Content -->
    <div class="row">
        <!-- Left Column - Main Info -->
        <div class="col-lg-8 mb-4 mb-lg-0">
            <!-- Student Profile Card -->
            <div class="card-custom shadow-sm mb-3">
                <div class="card-header-custom">
                    <!-- Student Profile -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-circle me-3 flex-shrink-0">
                            {{ strtoupper(substr($registration->nama_lengkap, 0, 1)) }}
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="mb-0">{{ $registration->nama_lengkap }}</h5>
                                    <small class="text-muted">
                                        @if($registration->jenis_pendidikan == 'smk')
                                            {{ $registration->sekolah }}
                                        @else
                                            {{ $registration->universitas }}
                                        @endif
                                    </small>
                                </div>
                                
                                <!-- Status Badge Desktop -->
                                <div class="d-none d-md-block">
                                    @php
                                        $statusText = match($registration->status) {
                                            'pending' => 'Menunggu',
                                            'approved' => 'Disetujui',
                                            'rejected' => 'Ditolak',
                                            default => $registration->status
                                        };
                                        $statusClass = match($registration->status) {
                                            'pending' => 'warning',
                                            'approved' => 'success',
                                            'rejected' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }} px-3 py-2">
                                        <i class="fas fa-{{ $registration->status == 'pending' ? 'clock' : ($registration->status == 'approved' ? 'check' : 'times') }} me-1"></i>
                                        {{ $statusText }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center mt-2">
                                <i class="fab fa-whatsapp text-success me-1 fs-6"></i>
                                <small>{{ $registration->no_whatsapp }}</small>
                                <span class="mx-2">•</span>
                                <i class="fas fa-envelope text-primary me-1 fs-6"></i>
                                <small>{{ $registration->email }}</small>
                            </div>
                        </div>
                        
                        <!-- Status Badge Mobile -->
                        <div class="status-badge-mobile ms-2 d-block d-md-none">
                            @php
                                $statusText = match($registration->status) {
                                    'pending' => 'Menunggu',
                                    'approved' => 'Disetujui',
                                    'rejected' => 'Ditolak',
                                    default => $registration->status
                                };
                                $statusClass = match($registration->status) {
                                    'pending' => 'warning',
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusClass }} px-2 py-1">
                                <i class="fas fa-{{ $registration->status == 'pending' ? 'clock' : ($registration->status == 'approved' ? 'check' : 'times') }} me-1"></i>
                                {{ $statusText }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Mobile Action Buttons -->
                    <div class="mobile-quick-actions d-block d-lg-none mt-3 pt-3 border-top">
                        <div class="d-grid gap-2">
                            <!-- WhatsApp Button -->
                            <a href="https://wa.me/{{ $registration->no_whatsapp }}" 
                               target="_blank" class="btn btn-success w-100 py-3">
                                <i class="fab fa-whatsapp me-2"></i>WhatsApp
                            </a>
                        </div>
                    </div>                
                </div>
                
                <div class="card-body">
                    <!-- Personal Information -->
                    <div class="section-block mb-4">
                        <div class="section-header">
                            <i class="fas fa-user-circle me-2"></i>
                            <h6 class="mb-0">Data Pribadi</h6>
                        </div>
                        <div class="section-divider"></div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-grid">
                                    <div class="info-item">
                                        <label><i class="fas fa-venus-mars me-1"></i>Jenis Kelamin</label>
                                        <p>{{ $registration->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                                    </div>
                                    <div class="info-item">
                                        <label><i class="fas fa-birthday-cake me-1"></i>Tempat, Tgl Lahir</label>
                                        <p>{{ $registration->tempat_lahir }}, {{ \Carbon\Carbon::parse($registration->tanggal_lahir)->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label><i class="fas fa-map-marker-alt me-1"></i>Alamat Lengkap</label>
                                    <p class="address-text">{{ $registration->alamat_lengkap }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Periode PKL -->
                    <div class="section-block mb-4">
                        <div class="section-header">
                            <i class="fas fa-calendar-alt me-2"></i>
                            <h6 class="mb-0">Periode PKL</h6>
                        </div>
                        <div class="section-divider"></div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="period-card start">
                                    <div class="period-icon">
                                        <i class="fas fa-play-circle"></i>
                                    </div>
                                    <div class="period-content">
                                        <label>Mulai</label>
                                        <p class="period-date">{{ \Carbon\Carbon::parse($registration->tanggal_mulai)->format('d F Y') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="period-card end">
                                    <div class="period-icon">
                                        <i class="fas fa-flag-checkered"></i>
                                    </div>
                                    <div class="period-content">
                                        <label>Selesai</label>
                                        <p class="period-date">{{ \Carbon\Carbon::parse($registration->tanggal_selesai)->format('d F Y') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="period-card duration">
                                    <div class="period-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="period-content">
                                        <label>Durasi</label>
                                        <p class="period-duration">
                                            @php
                                                $start = \Carbon\Carbon::parse($registration->tanggal_mulai);
                                                $end = \Carbon\Carbon::parse($registration->tanggal_selesai);
                                                $duration = $start->diffInDays($end);
                                            @endphp
                                            {{ $duration }} hari
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Education Information -->
                    <div class="section-block mb-4">
                        <div class="section-header">
                            <i class="fas fa-graduation-cap me-2"></i>
                            <h6 class="mb-0">Data Pendidikan</h6>
                        </div>
                        <div class="section-divider"></div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label><i class="fas fa-school me-1"></i>Jenis Pendidikan</label>
                                    <p>
                                        <span class="badge {{ $registration->jenis_pendidikan == 'smk' ? 'bg-primary' : 'bg-success' }}">
                                            {{ $registration->jenis_pendidikan == 'smk' ? 'SMK/SMA' : 'UNIVERSITAS' }}
                                        </span>
                                    </p>
                                </div>
                                
                                <div class="info-item">
                                    <label><i class="fas fa-university me-1"></i>
                                        {{ $registration->jenis_pendidikan == 'smk' ? 'Nama Sekolah' : 'Nama Universitas' }}
                                    </label>
                                    <p class="fw-bold">
                                        @if($registration->jenis_pendidikan == 'smk')
                                            {{ $registration->sekolah ?? '-' }}
                                        @else
                                            {{ $registration->universitas ?? '-' }}
                                        @endif
                                    </p>
                                </div>
                                
                                <div class="info-item">
                                    <label><i class="fas fa-book me-1"></i>
                                        {{ $registration->jenis_pendidikan == 'smk' ? 'Jurusan' : 'Program Studi' }}
                                    </label>
                                    <p class="fw-bold">
                                        @if($registration->jenis_pendidikan == 'smk')
                                            {{ $registration->jurusan_smk ?? '-' }}
                                        @else
                                            {{ $registration->jurusan_univ ?? '-' }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                @if($registration->jenis_pendidikan == 'smk')
                                <div class="info-item">
                                    <label><i class="fas fa-id-card me-1"></i>NIS</label>
                                    <p>{{ $registration->nis ?? '-' }}</p>
                                </div>
                                <div class="info-item">
                                    <label><i class="fas fa-users me-1"></i>Kelas</label>
                                    <p>{{ $registration->kelas ?? '-' }}</p>
                                </div>
                                
                                <!-- Guru Pembimbing -->
                                <div class="info-item with-divider">
                                    <label><i class="fas fa-chalkboard-teacher me-1"></i>Guru Pembimbing</label>
                                    <p class="fw-semibold">{{ $registration->guru_pembimbing ?? '-' }}</p>
                                </div>
                                
                                @if($registration->no_hp_guru)
                                <div class="info-item">
                                    <label><i class="fas fa-phone me-1"></i>No. HP Guru</label>
                                    <p>{{ $registration->no_hp_guru }}</p>
                                </div>
                                @endif
                                
                                @else
                                <div class="info-item">
                                    <label><i class="fas fa-id-card me-1"></i>NIM</label>
                                    <p>{{ $registration->nim ?? '-' }}</p>
                                </div>
                                <div class="info-item">
                                    <label><i class="fas fa-layer-group me-1"></i>Semester</label>
                                    <p>{{ $registration->semester ?? '-' }}</p>
                                </div>
                                
                                <!-- Dosen Pembimbing -->
                                <div class="info-item with-divider">
                                    <label><i class="fas fa-user-tie me-1"></i>Dosen Pembimbing</label>
                                    <p class="fw-semibold">{{ $registration->dosen_pembimbing ?? '-' }}</p>
                                </div>
                                
                                @if($registration->no_hp_dosen)
                                <div class="info-item">
                                    <label><i class="fas fa-phone me-1"></i>No. HP Dosen</label>
                                    <p>{{ $registration->no_hp_dosen }}</p>
                                </div>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Motivation & Skills -->
                    <div class="section-block mb-4">
                        <div class="section-header">
                            <i class="fas fa-bullseye me-2"></i>
                            <h6 class="mb-0">Motivasi & Tujuan</h6>
                        </div>
                        <div class="section-divider"></div>
                        
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="info-item">
                                    <label><i class="fas fa-question-circle me-1"></i>Alasan PKL di GI</label>
                                    <div class="content-box motivation">
                                        {{ $registration->alasan_pkl_gi }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <div class="info-item">
                                    <label><i class="fas fa-code me-1"></i>Skill yang ingin dipelajari</label>
                                    <div class="content-box skills">
                                        {{ $registration->skill_ingin_dipelajari }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <div class="info-item">
                                    <label><i class="fas fa-star me-1"></i>Harapan setelah PKL</label>
                                    <div class="content-box expectation">
                                        {{ $registration->harapan_setelah_pkl }}
                                    </div>
                                </div>
                            </div>
                            
                            @if($registration->pengalaman_sebelumnya)
                            <div class="col-12">
                                <div class="info-item">
                                    <label><i class="fas fa-briefcase me-1"></i>Pengalaman sebelumnya</label>
                                    <div class="content-box experience">
                                        {{ $registration->pengalaman_sebelumnya }}
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Documents Section -->
                    <div class="section-block mb-4">
                        <div class="section-header">
                            <i class="fas fa-file-upload me-2"></i>
                            <h6 class="mb-0">Dokumen</h6>
                        </div>
                        <div class="section-divider"></div>
                        
                        <!-- Surat Pengantar -->
                        <div class="mb-3">
                            <label class="form-label fw-bold mb-2">
                                <i class="fas fa-envelope me-1"></i>Surat Pengantar
                            </label>
                            @if($registration->upload_surat_pengantar)
                                @php
                                    $extension = pathinfo($registration->upload_surat_pengantar, PATHINFO_EXTENSION);
                                    $isPdf = in_array(strtolower($extension), ['pdf']);
                                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                                @endphp
                                @if($isPdf)
                                    <div class="document-card">
                                        <div class="document-icon">
                                            <i class="fas fa-file-pdf text-danger fa-2x"></i>
                                        </div>
                                        <div class="document-info">
                                            <div class="document-name">{{ $registration->upload_surat_pengantar }}</div>
                                            <div class="document-meta">
                                                <span class="document-type">PDF Document</span>
                                            </div>
                                        </div>
                                        <div class="document-actions">
                                            <a href="{{ route('admin.download.surat', $registration->id) }}" 
                                               class="btn btn-sm btn-outline-custom px-3" title="Download">
                                                <i class="fas fa-download me-1"></i>Download
                                            </a>
                                            <a href="{{ route('view.surat', ['filename' => $registration->upload_surat_pengantar]) }}" 
                                               target="_blank" class="btn btn-sm btn-primary-custom px-3" title="Preview">
                                                <i class="fas fa-eye me-1"></i>Preview
                                            </a>
                                        </div>
                                    </div>
                                @elseif($isImage)
                                    <div class="document-card">
                                        <div class="document-icon">
                                            <i class="fas fa-file-image text-success fa-2x"></i>
                                        </div>
                                        <div class="document-info">
                                            <div class="document-name">{{ $registration->upload_surat_pengantar }}</div>
                                            <div class="document-meta">
                                                <span class="document-type">Image File</span>
                                            </div>
                                        </div>
                                        <div class="document-actions">
                                            <a href="{{ route('admin.download.surat', $registration->id) }}" 
                                               class="btn btn-sm btn-outline-custom px-3" title="Download">
                                                <i class="fas fa-download me-1"></i>Download
                                            </a>
                                            <a href="{{ route('view.surat', ['filename' => $registration->upload_surat_pengantar]) }}" 
                                               target="_blank" class="btn btn-sm btn-primary-custom px-3" title="Preview">
                                                <i class="fas fa-eye me-1"></i>Preview
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="document-card">
                                        <div class="document-icon">
                                            <i class="fas fa-file text-primary fa-2x"></i>
                                        </div>
                                        <div class="document-info">
                                            <div class="document-name">{{ $registration->upload_surat_pengantar }}</div>
                                            <div class="document-meta">
                                                <span class="document-type">Document</span>
                                            </div>
                                        </div>
                                        <div class="document-actions">
                                            <a href="{{ route('admin.download.surat', $registration->id) }}" 
                                               class="btn btn-sm btn-outline-custom px-3" title="Download">
                                                <i class="fas fa-download me-1"></i>Download
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-secondary">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    Tidak ada file surat pengantar yang diunggah
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="section-block">
                        <div class="section-header">
                            <i class="fas fa-history me-2"></i>
                            <h6 class="mb-0">Timeline</h6>
                        </div>
                        <div class="section-divider"></div>
                        
                        <div class="timeline">
                            <div class="timeline-item success">
                                <div class="timeline-marker">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6>Pendaftaran</h6>
                                    <p>{{ $registration->created_at->format('d F Y, H:i') }}</p>
                                </div>
                            </div>
                            
                            @if($registration->status_updated_at)
                            <div class="timeline-item {{ $registration->status == 'approved' ? 'success' : ($registration->status == 'rejected' ? 'danger' : 'warning') }}">
                                <div class="timeline-marker">
                                    <i class="fas fa-sync-alt"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6>Status Diperbarui ke 
                                        @php
                                            $statusText = match($registration->status) {
                                                'pending' => 'Menunggu',
                                                'approved' => 'Disetujui',
                                                'rejected' => 'Ditolak',
                                                default => $registration->status
                                            };
                                        @endphp
                                        {{ $statusText }}
                                    </h6>
                                    <p>{{ $registration->status_updated_at->format('d F Y, H:i') }}</p>
                                </div>
                            </div>
                            @endif
                            
                            <div class="timeline-item info">
                                <div class="timeline-marker">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6>Mulai PKL</h6>
                                    <p>{{ \Carbon\Carbon::parse($registration->tanggal_mulai)->format('d F Y') }}</p>
                                </div>
                            </div>
                            
                            <div class="timeline-item info">
                                <div class="timeline-marker">
                                    <i class="fas fa-calendar-times"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6>Selesai PKL</h6>
                                    <p>{{ \Carbon\Carbon::parse($registration->tanggal_selesai)->format('d F Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Desktop Form (TIDAK BERUBAH) -->
        <div class="col-lg-4 d-none d-lg-block">
            <div class="card-custom shadow-sm sticky-sidebar">
                <div class="card-header-custom">
                    <h6 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Update Status
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.registrations.update', $registration->id) }}" method="POST" id="statusForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold mb-3">Status Baru</label>
                            <div class="status-grid">
                                <label class="status-option">
                                    <input type="radio" name="status" value="pending" 
                                           {{ $registration->status == 'pending' ? 'checked' : '' }} hidden>
                                    <div class="status-card {{ $registration->status == 'pending' ? 'active' : '' }}">
                                        <div class="status-icon bg-warning">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div class="status-label">
                                            <div class="status-title">Pending</div>
                                            <div class="status-desc">Menunggu Verifikasi</div>
                                        </div>
                                    </div>
                                </label>
                                
                                <label class="status-option">
                                    <input type="radio" name="status" value="approved" 
                                           {{ $registration->status == 'approved' ? 'checked' : '' }} hidden>
                                    <div class="status-card {{ $registration->status == 'approved' ? 'active' : '' }}">
                                        <div class="status-icon bg-success">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="status-label">
                                            <div class="status-title">Approved</div>
                                            <div class="status-desc">Diterima</div>
                                        </div>
                                    </div>
                                </label>
                                
                                <label class="status-option">
                                    <input type="radio" name="status" value="rejected" 
                                           {{ $registration->status == 'rejected' ? 'checked' : '' }} hidden>
                                    <div class="status-card {{ $registration->status == 'rejected' ? 'active' : '' }}">
                                        <div class="status-icon bg-danger">
                                            <i class="fas fa-times"></i>
                                        </div>
                                        <div class="status-label">
                                            <div class="status-title">Rejected</div>
                                            <div class="status-desc">Ditolak</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold mb-2">Catatan Admin</label>
                            <textarea name="admin_notes" class="form-control-custom" 
                                      rows="4" 
                                      placeholder="Tambahkan catatan untuk pendaftar...">{{ $registration->admin_notes }}</textarea>
                            <small class="text-muted">Catatan akan terlihat oleh pendaftar</small>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary-custom py-2" id="submitBtn">
                                <i class="fas fa-save me-2"></i>Update Status
                            </button>
                            <a href="https://wa.me/{{ $registration->no_whatsapp }}" 
                               target="_blank" class="btn btn-success py-2">
                                <i class="fab fa-whatsapp me-2"></i>Hubungi via WhatsApp
                            </a>
                            <button type="button" class="btn btn-outline-danger py-2" onclick="confirmDelete()">
                                <i class="fas fa-trash me-2"></i>Hapus Pendaftaran
                            </button>
                        </div>
                    </form>
                    
                    <!-- Admin Notes Display -->
                    @if($registration->admin_notes)
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="fw-bold mb-2">
                            <i class="fas fa-sticky-note me-2"></i>Catatan Terakhir
                        </h6>
                        <div class="notes-preview">
                            {{ $registration->admin_notes }}
                        </div>
                        <small class="text-muted d-block mt-1">
                            Diperbarui: {{ $registration->status_updated_at?->format('d/m/Y H:i') ?? $registration->updated_at->format('d/m/Y H:i') }}
                        </small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div> <!-- END .row -->

    <!-- ============================================
       MOBILE UPDATE STATUS BUTTON 
       Posisi: DI LUAR .row, fixed di bagian bawah
    ============================================ -->
    <div class="mobile-update-status-sticky-wrapper d-block d-lg-none">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <button class="btn btn-update-status-mobile w-100" onclick="openBottomSheet()">
                        <i class="fas fa-edit me-2"></i>UPDATE STATUS
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Bottom Action Sheet (untuk Update Status Detail) -->
    <div class="mobile-bottom-sheet" id="mobileBottomSheet">
        <div class="sheet-overlay" onclick="closeBottomSheet()"></div>
        <div class="sheet-content">
            <div class="sheet-header">
                <h6 class="sheet-title">
                    <i class="fas fa-edit me-2"></i>Update Status
                </h6>
                <button type="button" class="sheet-close" onclick="closeBottomSheet()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.registrations.update', $registration->id) }}" method="POST" id="mobileStatusForm">
                @csrf
                @method('PUT')
                
                <div class="sheet-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold mb-2">Status Baru</label>
                        <div class="row g-2 status-mobile-grid">
                            <div class="col-4">
                                <label class="status-mobile-option">
                                    <input type="radio" name="status" value="pending" 
                                           {{ $registration->status == 'pending' ? 'checked' : '' }} hidden>
                                    <div class="status-mobile-card {{ $registration->status == 'pending' ? 'active' : '' }}">
                                        <div class="status-mobile-icon bg-warning">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div class="status-mobile-label">Pending</div>
                                    </div>
                                </label>
                            </div>
                            <div class="col-4">
                                <label class="status-mobile-option">
                                    <input type="radio" name="status" value="approved" 
                                           {{ $registration->status == 'approved' ? 'checked' : '' }} hidden>
                                    <div class="status-mobile-card {{ $registration->status == 'approved' ? 'active' : '' }}">
                                        <div class="status-mobile-icon bg-success">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="status-mobile-label">Approved</div>
                                    </div>
                                </label>
                            </div>
                            <div class="col-4">
                                <label class="status-mobile-option">
                                    <input type="radio" name="status" value="rejected" 
                                           {{ $registration->status == 'rejected' ? 'checked' : '' }} hidden>
                                    <div class="status-mobile-card {{ $registration->status == 'rejected' ? 'active' : '' }}">
                                        <div class="status-mobile-icon bg-danger">
                                            <i class="fas fa-times"></i>
                                        </div>
                                        <div class="status-mobile-label">Rejected</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold mb-2">Catatan Admin</label>
                        <textarea name="admin_notes" class="form-control-custom" 
                                  rows="3" 
                                  placeholder="Tambahkan catatan...">{{ $registration->admin_notes }}</textarea>
                    </div>
                </div>
                
                <div class="sheet-footer">
                    <button type="submit" class="btn btn-primary-custom w-100 py-2 mb-2">
                        <i class="fas fa-save me-2"></i>Update Status
                    </button>
                    <button type="button" class="btn btn-outline-danger w-100 py-2" onclick="confirmDelete()">
                        <i class="fas fa-trash me-2"></i>Hapus Pendaftaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> <!-- END .container-fluid -->

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                    <h5 class="fw-bold">Hapus Pendaftaran?</h5>
                    <p class="text-muted mb-0">
                        Apakah Anda yakin ingin menghapus pendaftaran 
                        <strong>{{ $registration->nama_lengkap }}</strong>?
                    </p>
                    <p class="text-danger small mt-2">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        Tindakan ini tidak dapat dibatalkan
                    </p>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.registrations.destroy', $registration->id) }}" method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    /* ============================================
       CUSTOM VARIABLES
    ============================================ */
    :root {
        --primary-red: #dc3545;
        --primary-red-dark: #c82333;
        --success-green: #28a745;
        --warning-yellow: #ffc107;
        --danger-red: #dc3545;
        --info-blue: #17a2b8;
        --gray-light: #f8f9fa;
        --gray-medium: #e9ecef;
        --gray-border: #dee2e6;
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
        --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
        --shadow-md: 0 4px 12px rgba(0,0,0,0.1);
        --shadow-lg: 0 8px 24px rgba(0,0,0,0.12);
    }

    /* ============================================
       FIX BODY PADDING
    ============================================ */
    body {
        padding-top: 56px !important;
        padding-bottom: 0 !important;
    }
    
    .container-fluid {
        padding-top: 1rem;
        padding-bottom: 80px; /* Space for mobile button */
    }

    @media (min-width: 768px) {
        body {
            padding-top: 0 !important;
        }
        
        .container-fluid {
            padding-bottom: 1rem; /* Reset for desktop */
        }
    }

    /* ============================================
       MOBILE UPDATE STATUS BUTTON - AMAN
    ============================================ */
    .mobile-update-status-sticky-wrapper {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: white;
        padding: 1rem;
        border-top: 1px solid var(--gray-border);
        box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
        z-index: 990; /* Lower than WhatsApp floating button */
        display: none; /* Hidden by default */
    }

    /* Show only on mobile */
    @media (max-width: 991.98px) {
        .mobile-update-status-sticky-wrapper {
            display: block;
        }
    }

    /* Button styling - safe from WhatsApp */
    .btn-update-status-mobile {
        background: linear-gradient(135deg, var(--primary-red), #e63946);
        color: white !important;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.25);
        transition: all 0.3s ease;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1.5rem;
        width: 100%;
    }

    .btn-update-status-mobile:hover {
        background: linear-gradient(135deg, #c82333, #e63946);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(220, 53, 69, 0.35);
    }

    .btn-update-status-mobile:active {
        transform: translateY(0);
    }

    .btn-update-status-mobile i {
        font-size: 1.2rem;
        margin-right: 10px;
    }

    /* Hide on desktop */
    @media (min-width: 992px) {
        .mobile-update-status-sticky-wrapper {
            display: none !important;
        }
    }

    /* Responsive adjustments */
    @media (max-width: 576px) {
        .mobile-update-status-sticky-wrapper {
            padding: 0.75rem;
        }
        
        .btn-update-status-mobile {
            height: 52px;
            font-size: 0.95rem;
            padding: 0.75rem;
        }
    }

    /* ============================================
       BASE STYLES
    ============================================ */
    .hero-title {
        color: var(--primary-red);
        font-weight: 700;
        display: flex;
        align-items: center;
    }

    /* ============================================
       SECTION BLOCKS
    ============================================ */
    .section-block {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--gray-border);
        padding: 1.25rem;
        margin-bottom: 1.25rem;
        transition: all 0.3s ease;
    }

    .section-block:hover {
        border-color: var(--primary-red);
        box-shadow: var(--shadow-sm);
    }

    .section-header {
        display: flex;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .section-header i {
        color: var(--primary-red);
        font-size: 1.1rem;
    }

    .section-header h6 {
        font-weight: 600;
        color: #333;
        margin: 0;
        font-size: 1rem;
    }

    .section-divider {
        height: 2px;
        background: linear-gradient(90deg, var(--primary-red), transparent);
        margin-bottom: 1.25rem;
        border-radius: 1px;
    }

    /* ============================================
       CARD STYLES
    ============================================ */
    .card-custom {
        background: white;
        border-radius: var(--radius-lg);
        border: none;
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        margin-bottom: 1rem;
    }

    .card-header-custom {
        background: white;
        border-bottom: 1px solid var(--gray-border);
        padding: 1.25rem;
        font-weight: 600;
        color: #333;
    }

    .card-body {
        padding: 1.5rem;
    }

    /* ============================================
       BUTTON STYLES
    ============================================ */
    .btn-custom {
        background: var(--primary-red);
        color: white;
        border: none;
        padding: 0.5rem 1.5rem;
        border-radius: var(--radius-md);
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-custom:hover {
        background: var(--primary-red-dark);
        color: white;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-outline-custom {
        color: var(--primary-red);
        border: 2px solid var(--primary-red);
        background: transparent;
        padding: 0.5rem 1.5rem;
        border-radius: var(--radius-md);
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-outline-custom:hover {
        background: var(--primary-red);
        color: white;
        transform: translateY(-2px);
    }

    .btn-primary-custom {
        background: var(--primary-red);
        border: none;
        color: white;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        border-radius: var(--radius-md);
        transition: all 0.3s ease;
    }

    .btn-primary-custom:hover {
        background: var(--primary-red-dark);
        color: white;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    /* ============================================
       MOBILE QUICK ACTIONS
    ============================================ */
    .mobile-quick-actions {
        background: var(--gray-light);
        border-radius: var(--radius-sm);
        padding: 0.75rem;
        margin-top: 1rem;
    }

    .mobile-quick-actions .btn {
        font-size: 0.875rem;
        font-weight: 500;
    }

    /* ============================================
       AVATAR
    ============================================ */
    .avatar-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-red), #ff6b6b);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1.5rem;
        flex-shrink: 0;
        border: 3px solid white;
        box-shadow: var(--shadow-sm);
    }

    /* ============================================
       STATUS BADGES
    ============================================ */
    .bg-primary-custom {
        background-color: var(--primary-red) !important;
    }

    .badge.bg-warning { 
        background-color: var(--warning-yellow) !important; 
        color: #000 !important; 
    }
    
    .badge.bg-success { 
        background-color: var(--success-green) !important; 
    }
    
    .badge.bg-danger { 
        background-color: var(--danger-red) !important; 
    }

    .status-badge-mobile .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        font-weight: 500;
    }

    /* ============================================
       INFORMATION GRID LAYOUT
    ============================================ */
    .info-grid {
        display: grid;
        gap: 1rem;
    }

    .info-item {
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--gray-light);
    }

    .info-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .info-item.with-divider {
        border-bottom: 2px solid var(--gray-border);
        padding-bottom: 1rem;
        margin-bottom: 1rem;
    }

    .info-item label {
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 500;
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
    }

    .info-item label i {
        width: 16px;
        margin-right: 6px;
    }

    .info-item p {
        font-size: 0.95rem;
        color: #333;
        margin: 0;
        line-height: 1.5;
    }

    .address-text {
        background: var(--gray-light);
        padding: 0.75rem;
        border-radius: var(--radius-sm);
        font-size: 0.9rem;
        line-height: 1.5;
        border-left: 3px solid var(--primary-red);
    }

    /* ============================================
       PERIODE CARDS
    ============================================ */
    .period-card {
        background: white;
        border-radius: var(--radius-md);
        padding: 1rem;
        border: 1px solid var(--gray-border);
        text-align: center;
        height: 100%;
        transition: all 0.3s ease;
    }

    .period-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-sm);
    }

    .period-card.start {
        border-top: 4px solid var(--success-green);
    }

    .period-card.end {
        border-top: 4px solid var(--primary-red);
    }

    .period-card.duration {
        border-top: 4px solid var(--info-blue);
    }

    .period-icon {
        font-size: 2rem;
        margin-bottom: 0.75rem;
    }

    .period-card.start .period-icon {
        color: var(--success-green);
    }

    .period-card.end .period-icon {
        color: var(--primary-red);
    }

    .period-card.duration .period-icon {
        color: var(--info-blue);
    }

    .period-content label {
        font-size: 0.8rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
        display: block;
    }

    .period-date {
        font-size: 0.95rem;
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    .period-duration {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--info-blue);
        margin: 0;
    }

    /* ============================================
       CONTENT BOXES
    ============================================ */
    .content-box {
        background: white;
        border-radius: var(--radius-sm);
        padding: 1rem;
        font-size: 0.9rem;
        line-height: 1.6;
        border: 1px solid var(--gray-border);
        white-space: pre-wrap;
        max-height: 200px;
        overflow-y: auto;
    }

    .content-box.motivation {
        border-left: 4px solid #ffc107;
        background: #fffcf5;
    }

    .content-box.skills {
        border-left: 4px solid #17a2b8;
        background: #f8f9fe;
    }

    .content-box.expectation {
        border-left: 4px solid #28a745;
        background: #f8fff9;
    }

    .content-box.experience {
        border-left: 4px solid #6f42c1;
        background: #f9f7ff;
    }

    /* ============================================
       TIMELINE
    ============================================ */
    .timeline {
        position: relative;
        padding-left: 2.5rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 1.25rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, var(--primary-red), var(--info-blue));
        border-radius: 1px;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-marker {
        position: absolute;
        left: -2.5rem;
        top: 0;
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.75rem;
        z-index: 1;
        border: 3px solid white;
        box-shadow: var(--shadow-sm);
    }

    .timeline-item.success .timeline-marker {
        background: var(--success-green);
    }

    .timeline-item.warning .timeline-marker {
        background: var(--warning-yellow);
    }

    .timeline-item.danger .timeline-marker {
        background: var(--danger-red);
    }

    .timeline-item.info .timeline-marker {
        background: var(--info-blue);
    }

    .timeline-content h6 {
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: #333;
    }

    .timeline-content p {
        font-size: 0.8rem;
        color: #6c757d;
        margin: 0;
    }

    /* ============================================
       DOCUMENT STYLES
    ============================================ */
    .document-card {
        display: flex;
        align-items: center;
        background: white;
        border-radius: var(--radius-md);
        padding: 1rem;
        border: 1px solid var(--gray-border);
        transition: all 0.3s ease;
    }

    .document-card:hover {
        border-color: var(--primary-red);
        box-shadow: var(--shadow-sm);
        transform: translateY(-2px);
    }

    .document-icon {
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .document-info {
        flex-grow: 1;
    }

    .document-name {
        font-weight: 500;
        margin-bottom: 0.25rem;
        font-size: 0.9rem;
        color: #333;
    }

    .document-meta {
        font-size: 0.75rem;
        color: #6c757d;
    }

    .document-actions {
        display: flex;
        gap: 0.5rem;
        flex-shrink: 0;
    }

    /* ============================================
       ADMIN NOTES
    ============================================ */
    .notes-preview {
        background: var(--gray-light);
        border-radius: var(--radius-sm);
        padding: 0.75rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-left: 3px solid var(--primary-red);
        white-space: pre-wrap;
    }

    /* ============================================
       STATUS GRID LAYOUT (Desktop)
    ============================================ */
    .status-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 20px;
    }

    .status-option {
        cursor: pointer;
        display: block;
    }

    .status-option input[type="radio"] {
        display: none;
    }

    .status-card {
        border: 2px solid var(--gray-medium);
        border-radius: var(--radius-md);
        padding: 15px;
        text-align: center;
        transition: all 0.2s ease;
        background: white;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .status-card:hover {
        border-color: var(--primary-red);
        background: #fff5f5;
        transform: translateY(-2px);
    }

    .status-card.active {
        border-color: var(--primary-red);
        background: #fff5f5;
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.1);
    }

    .status-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        color: white;
        font-size: 20px;
    }

    .status-label {
        font-size: 12px;
    }

    .status-title {
        font-weight: 600;
        margin-bottom: 2px;
    }

    .status-desc {
        font-size: 11px;
        color: #6c757d;
    }

    /* ============================================
       MOBILE BOTTOM SHEET
    ============================================ */
    .mobile-bottom-sheet {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1099;
        display: none;
    }

    .mobile-bottom-sheet.show {
        display: block;
    }

    .sheet-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(2px);
    }

    .sheet-content {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: white;
        border-radius: 20px 20px 0 0;
        padding: 25px;
        max-height: 85vh;
        overflow-y: auto;
        animation: slideUp 0.3s ease;
        padding-bottom: max(25px, env(safe-area-inset-bottom));
    }

    @keyframes slideUp {
        from {
            transform: translateY(100%);
        }
        to {
            transform: translateY(0);
        }
    }

    .sheet-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--gray-border);
        position: sticky;
        top: 0;
        background: white;
        z-index: 1;
    }

    .sheet-title {
        font-weight: 600;
        margin: 0;
        color: #333;
        font-size: 1.1rem;
    }

    .sheet-close {
        background: none;
        border: none;
        font-size: 1.25rem;
        color: #6c757d;
        cursor: pointer;
        padding: 0;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: background-color 0.2s;
    }

    .sheet-close:hover {
        background-color: var(--gray-light);
    }

    .sheet-body {
        margin-bottom: 20px;
    }

    .sheet-footer {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid var(--gray-border);
    }

    /* Mobile Status Grid */
    .status-mobile-grid {
        display: flex;
        justify-content: space-between;
    }

    .status-mobile-option {
        cursor: pointer;
        display: block;
        width: 100%;
    }

    .status-mobile-option input[type="radio"] {
        display: none;
    }

    .status-mobile-card {
        border: 2px solid var(--gray-medium);
        border-radius: var(--radius-md);
        padding: 12px 8px;
        text-align: center;
        transition: all 0.2s ease;
        background: white;
        width: 100%;
    }

    .status-mobile-card:hover {
        border-color: var(--primary-red);
        background: #fff5f5;
    }

    .status-mobile-card.active {
        border-color: var(--primary-red);
        background: #fff5f5;
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.1);
    }

    .status-mobile-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px;
        color: white;
        font-size: 14px;
    }

    .status-mobile-label {
        font-size: 10px;
        font-weight: 600;
        color: #333;
    }

    /* ============================================
       FORM CONTROLS
    ============================================ */
    .form-control-custom {
        border: 1px solid var(--gray-border);
        border-radius: var(--radius-md);
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        width: 100%;
    }

    .form-control-custom:focus {
        border-color: var(--primary-red);
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        outline: none;
    }

    /* ============================================
       STICKY SIDEBAR
    ============================================ */
    .sticky-sidebar {
        position: sticky;
        top: 20px;
        max-height: calc(100vh - 100px);
        overflow-y: auto;
    }

    /* ============================================
       ANIMATIONS
    ============================================ */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .timeline-item {
        animation: fadeInUp 0.5s ease forwards;
        opacity: 0;
    }

    .timeline-item:nth-child(1) { animation-delay: 0.1s; }
    .timeline-item:nth-child(2) { animation-delay: 0.2s; }
    .timeline-item:nth-child(3) { animation-delay: 0.3s; }
    .timeline-item:nth-child(4) { animation-delay: 0.4s; }

    /* Loading state for submit button */
    .btn-loading {
        position: relative;
        color: transparent !important;
    }

    .btn-loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        margin-top: -10px;
        margin-left: -10px;
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* ============================================
       RESPONSIVE ADJUSTMENTS
    ============================================ */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 12px;
            padding-right: 12px;
        }
        
        .card-header-custom {
            padding: 1rem;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        .section-block {
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .info-item {
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
        }
        
        .content-box {
            max-height: 150px;
            font-size: 0.85rem;
            padding: 0.75rem;
        }
        
        .timeline {
            padding-left: 2rem;
        }
        
        .timeline::before {
            left: 1rem;
        }
        
        .timeline-marker {
            left: -2rem;
            width: 1.5rem;
            height: 1.5rem;
            font-size: 0.625rem;
        }
        
        .document-card {
            flex-direction: column;
            text-align: center;
            gap: 0.75rem;
            padding: 0.75rem;
        }
        
        .document-icon {
            margin-right: 0;
        }
        
        .document-actions {
            width: 100%;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .document-actions .btn {
            width: 100%;
            margin-bottom: 0.25rem;
        }
        
        /* Avatar smaller on mobile */
        .avatar-circle {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }
        
        /* Period cards stack on mobile */
        .period-card {
            margin-bottom: 1rem;
        }
        
        /* Mobile quick actions */
        .mobile-quick-actions {
            margin-top: 0.75rem;
            padding: 0.5rem;
        }
        
        .mobile-quick-actions .btn {
            padding: 0.5rem;
            font-size: 0.8rem;
        }
        
        .mobile-quick-actions i {
            margin-right: 0.25rem;
        }
    }

    @media (max-width: 576px) {
        .section-header h6 {
            font-size: 0.9rem;
        }
        
        .info-item p {
            font-size: 0.875rem;
        }
        
        .period-date {
            font-size: 0.85rem;
        }
        
        .status-mobile-label {
            font-size: 9px;
        }
        
        .sheet-content {
            padding: 20px;
        }
        
        .mobile-update-status-sticky-wrapper {
            padding: 0.5rem;
        }
        
        .btn-update-status-mobile {
            height: 50px;
            font-size: 0.9rem;
        }
    }

    @media (min-width: 992px) {
        .sticky-sidebar {
            position: sticky;
            top: 20px;
        }
        
        /* Pastikan button mobile tidak tampil di desktop */
        .mobile-update-status-sticky-wrapper {
            display: none !important;
        }
    }

    /* ============================================
       UTILITY CLASSES
    ============================================ */
    .text-primary-custom {
        color: var(--primary-red) !important;
    }

    .border-custom {
        border-color: var(--primary-red) !important;
    }

    .shadow-custom {
        box-shadow: var(--shadow-md) !important;
    }

    /* Toast Notification */
    .toast-custom {
        position: fixed;
        bottom: 80px; /* Above mobile button */
        right: 20px;
        z-index: 1100;
        min-width: 300px;
        max-width: 90%;
    }

    @media (max-width: 768px) {
        .toast-custom {
            bottom: 80px; /* Above mobile button */
            right: 10px;
            left: 10px;
            max-width: calc(100% - 20px);
        }
    }

    /* Safe area adjustments */
    @supports (padding: max(0px)) {
        .sheet-content {
            padding-bottom: max(25px, env(safe-area-inset-bottom));
        }
        
        .mobile-update-status-sticky-wrapper {
            padding-bottom: max(1rem, env(safe-area-inset-bottom));
        }
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize form validation
        $('#statusForm, #mobileStatusForm').on('submit', function(e) {
            const form = $(this);
            const submitBtn = form.find('button[type="submit"]');
            
            // Add loading state
            submitBtn.addClass('btn-loading').prop('disabled', true);
            submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Memproses...');
            
            // Show processing toast
            showToast('Memproses...', 'Sedang memperbarui status pendaftaran', 'info');
            
            // Allow form to submit
            return true;
        });
        
        // Auto-close success alert
        @if(session('success'))
            setTimeout(function() {
                $('.alert-success').fadeOut('slow', function() {
                    $(this).remove();
                });
            }, 5000);
        @endif
        
        // Status card click handler for desktop
        $('.status-option').on('click', function() {
            const radio = $(this).find('input[type="radio"]');
            radio.prop('checked', true);
            
            // Update UI
            $('.status-card').removeClass('active');
            $(this).find('.status-card').addClass('active');
            
            // Update submit button text based on selected status
            updateSubmitButton(radio.val());
        });
        
        // Status card click handler for mobile
        $('.status-mobile-option').on('click', function() {
            const radio = $(this).find('input[type="radio"]');
            radio.prop('checked', true);
            
            // Update UI
            $('.status-mobile-card').removeClass('active');
            $(this).find('.status-mobile-card').addClass('active');
            
            // Update submit button text based on selected status
            updateSubmitButton(radio.val());
        });
        
        // Function to update submit button text
        function updateSubmitButton(status) {
            let buttonText = '';
            let iconClass = '';
            
            switch(status) {
                case 'pending':
                    buttonText = 'Set sebagai Pending';
                    iconClass = 'fa-clock';
                    break;
                case 'approved':
                    buttonText = 'Setujui Pendaftaran';
                    iconClass = 'fa-check';
                    break;
                case 'rejected':
                    buttonText = 'Tolak Pendaftaran';
                    iconClass = 'fa-times';
                    break;
                default:
                    buttonText = 'Update Status';
                    iconClass = 'fa-save';
            }
            
            // Update desktop button
            $('#submitBtn').html(`<i class="fas ${iconClass} me-2"></i>${buttonText}`);
            
            // Update mobile button
            $('.mobile-bottom-sheet button[type="submit"]').html(`<i class="fas ${iconClass} me-2"></i>${buttonText}`);
        }
        
        // Copy text functionality
        $('.info-item p, .content-box').on('click', function() {
            const text = $(this).text().trim();
            if (text) {
                navigator.clipboard.writeText(text).then(() => {
                    showToast('Teks disalin!', 'Teks berhasil disalin ke clipboard', 'success');
                });
            }
        });
        
        // Animate timeline items on scroll
        function animateOnScroll() {
            $('.timeline-item').each(function() {
                const elementTop = $(this).offset().top;
                const elementBottom = elementTop + $(this).outerHeight();
                const viewportTop = $(window).scrollTop();
                const viewportBottom = viewportTop + $(window).height();
                
                if (elementBottom > viewportTop && elementTop < viewportBottom) {
                    $(this).css('animation-play-state', 'running');
                }
            });
        }
        
        // Initial animation check
        animateOnScroll();
        $(window).on('scroll', animateOnScroll);
        
        // Initialize current status on buttons
        updateSubmitButton('{{ $registration->status }}');
        
        // Handle WhatsApp link formatting
        $('a[href^="https://wa.me/"]').each(function() {
            const href = $(this).attr('href');
            // Ensure proper WhatsApp formatting
            if (!href.includes('?text=')) {
                const message = `Halo {{ $registration->nama_lengkap }}, ini dari admin GI Hub tentang pendaftaran PKL Anda.`;
                $(this).attr('href', href + '?text=' + encodeURIComponent(message));
            }
        });
        
        // Add click animation to cards
        $('.section-block').on('click', function() {
            $(this).css({
                'transform': 'scale(0.99)',
                'transition': 'transform 0.2s ease'
            });
            setTimeout(() => {
                $(this).css('transform', 'scale(1)');
            }, 200);
        });
        
        // Mobile update status button click handler
        $('.btn-update-status-mobile').on('click', function() {
            openBottomSheet();
        });
        
        // Adjust mobile button position for WhatsApp floating button
        function adjustForWhatsAppButton() {
            if ($(window).width() < 992) {
                // WhatsApp button biasanya di pojok kanan bawah
                // Kita beri jarak dari kanan untuk menghindari tabrakan
                const whatsappButtonSize = 56; // Approximate size
                const safeMargin = 20;
                
                $('.mobile-update-status-sticky-wrapper').css({
                    'padding-right': (whatsappButtonSize + safeMargin) + 'px'
                });
            }
        }
        
        // Run on load and resize
        $(window).on('load resize', adjustForWhatsAppButton);
        adjustForWhatsAppButton();
    });
    
    function openBottomSheet() {
        $('#mobileBottomSheet').addClass('show');
        $('body').addClass('sheet-open');
        
        // Disable scrolling on body
        $('body').css('overflow', 'hidden');
        
        // Scroll to top of sheet
        setTimeout(() => {
            $('#mobileBottomSheet .sheet-content').scrollTop(0);
        }, 100);
    }
    
    function closeBottomSheet() {
        $('#mobileBottomSheet').removeClass('show');
        $('body').removeClass('sheet-open');
        
        // Enable scrolling on body
        $('body').css('overflow', 'auto');
    }
    
    function confirmDelete() {
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }
    
    function showToast(title, message, type) {
        const toastId = 'toast-' + Date.now();
        const toastHtml = `
            <div id="${toastId}" class="toast-custom toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header bg-${type} text-white">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 
                                     type === 'error' ? 'exclamation-circle' : 
                                     type === 'warning' ? 'exclamation-triangle' : 
                                     'info-circle'} me-2"></i>
                    <strong class="me-auto">${title}</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        `;
        
        $('body').append(toastHtml);
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, {
            animation: true,
            autohide: true,
            delay: 3000
        });
        toast.show();
        
        toastElement.addEventListener('hidden.bs.toast', function () {
            $(this).remove();
        });
    }
    
    // Handle ESC key to close bottom sheet
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('#mobileBottomSheet').hasClass('show')) {
            closeBottomSheet();
        }
    });
    
    // Handle click outside bottom sheet to close
    $(document).on('click', function(e) {
        if ($('#mobileBottomSheet').hasClass('show') && 
            $(e.target).hasClass('sheet-overlay')) {
            closeBottomSheet();
        }
    });
    
    // Handle swipe down to close on mobile
    let startY = 0;
    let isSwiping = false;
    
    $('#mobileBottomSheet .sheet-content').on('touchstart', function(e) {
        startY = e.touches[0].clientY;
        isSwiping = true;
    });
    
    $('#mobileBottomSheet .sheet-content').on('touchmove', function(e) {
        if (!isSwiping) return;
        
        const currentY = e.touches[0].clientY;
        const diff = currentY - startY;
        
        if (diff > 50) { // Swipe down threshold
            closeBottomSheet();
            isSwiping = false;
        }
    }); 
    
    $('#mobileBottomSheet .sheet-content').on('touchend', function() {
        isSwiping = false;
    });
    
    // Adjust for safe areas on mobile
    function adjustForSafeAreas() {
        if ($(window).width() < 992) {
            const safeAreaBottom = 'env(safe-area-inset-bottom, 0px)';
            document.documentElement.style.setProperty('--safe-bottom', safeAreaBottom);
        }
    }
    
    // Run on load and resize
    $(window).on('load resize orientationchange', adjustForSafeAreas);
    adjustForSafeAreas();
</script>
@endpush