@extends('layouts.app')

@section('title', 'Cek Status Pendaftaran')
@section('content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-4">
                <h1 class="hero-title mb-2">
                    <i class="fas fa-search me-2"></i>Cek Status Pendaftaran
                </h1>
                <p class="text-muted">Masukkan kode pendaftaran untuk mengecek status.</p>
            </div>

            <div class="info-card mb-4">
                <div class="info-card-header">
                    <i class="fas fa-info-circle me-2"></i>
                    <span class="fw-bold">Informasi Penting</span>
                </div>
                <div class="info-card-body">
                    <div class="row g-3">
                        <div class="col-4">
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-clock"></i></div>
                                <div class="info-content">
                                    <h6 class="fw-bold mb-1">Verifikasi</h6>
                                    <p class="mb-0 small">1-3 hari kerja</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="info-item">
                                <div class="info-icon"><i class="fab fa-whatsapp"></i></div>
                                <div class="info-content">
                                    <h6 class="fw-bold mb-1">Notifikasi</h6>
                                    <p class="mb-0 small">Via WhatsApp</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-lock"></i></div>
                                <div class="info-content">
                                    <h6 class="fw-bold mb-1">Keamanan</h6>
                                    <p class="mb-0 small">Kode pendaftaran</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-custom mb-3">
                <div class="card-header-custom">
                    <h4 class="mb-0"><i class="fas fa-qrcode me-2"></i>Cari Pendaftaran</h4>
                </div>
                <div class="card-body p-3">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div id="modeKode">
                        <form action="{{ route('user.status.check') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label-custom">Kode Pendaftaran <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                    <input type="text" class="form-control-custom" name="kode_pendaftaran" 
                                           value="{{ old('kode_pendaftaran') }}" 
                                           placeholder="Contoh: IND-20260312-ABC12" required>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <button type="button" class="btn btn-link text-decoration-none p-0" onclick="switchMode('wa')">
                                    <i class="fas fa-question-circle me-1"></i>Lupa kode?
                                </button>
                                <button type="submit" class="btn btn-primary-custom">
                                    <i class="fas fa-search me-2"></i>Cari Status
                                </button>
                            </div>
                        </form>
                    </div>

                    <div id="modeWA" style="display: none;">
                        <div class="alert alert-info bg-light border-0 rounded-3 mb-3" id="infoLupaKode">
                            <div class="d-flex align-items-start gap-2">
                                <i class="fas fa-info-circle mt-1" style="color: #17a2b8;"></i>
                                <div>
                                    <strong class="d-block mb-1">Lupa Kode Pendaftaran?</strong>
                                    <p class="mb-0 small">Masukkan nomor WhatsApp yang digunakan saat mendaftar. Kode pendaftaran akan dikirimkan langsung ke WhatsApp Anda.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label-custom">Nomor WhatsApp <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">+62</span>
                                <input type="tel" class="form-control-custom" id="waInput" 
                                       placeholder="81234567890" 
                                       oninput="this.value=this.value.replace(/\D/g,'').substring(0,13)">
                            </div>
                        </div>
                        <div id="waResult" class="mb-3" style="display: none;"></div>
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-link text-decoration-none p-0" onclick="switchMode('kode')">
                                <i class="fas fa-arrow-left me-1"></i>Gunakan kode
                            </button>
                            <button type="button" class="btn btn-primary-custom" id="btnCariWA" onclick="kirimKodeViaWA()">
                                <i class="fab fa-whatsapp me-2"></i>Kirim via WhatsApp
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="searchResults">
                @if(isset($data) && isset($tipe))
                    @php
                        $statusText = match($data->status ?? '') {
                            'pending' => 'Menunggu',
                            'diterima' => 'Disetujui',
                            'ditolak' => 'Ditolak',
                            default => $data->status ?? 'Tidak Diketahui'
                        };
                        $statusClass = $data->status ?? '';
                    @endphp

                    <div class="card-custom mb-3">
                        <div class="card-header-custom">
                            <h4 class="mb-0">
                                <i class="fas {{ $tipe == 'individu' ? 'fa-user' : 'fa-users' }} me-2"></i>
                                Data Pendaftaran {{ $tipe == 'individu' ? 'Individu' : 'Kelompok' }}
                            </h4>
                        </div>
                        <div class="card-body p-3">

                            <div class="row mb-3">
                                <div class="col-md-6 mb-2">
                                    <div class="p-2 bg-light rounded">
                                        <small class="text-muted">Kode Pendaftaran</small>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <strong class="fs-5">{{ $data->kode_pendaftaran }}</strong>
                                            <button class="btn btn-sm btn-success" onclick="copyKode('{{ $data->kode_pendaftaran }}')">
                                                <i class="fas fa-copy"></i> Salin
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="p-2 bg-light rounded">
                                        <small class="text-muted">Status</small>
                                        <div>
                                            @if($statusClass == 'pending')
                                                <span class="badge bg-warning text-dark">⏳ {{ $statusText }}</span>
                                            @elseif($statusClass == 'diterima')
                                                <span class="badge bg-success">✅ {{ $statusText }}</span>
                                            @elseif($statusClass == 'ditolak')
                                                <span class="badge bg-danger">❌ {{ $statusText }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $statusText }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($tipe == 'individu')
                                <div class="data-list">
                                    <div class="data-item">
                                        <span class="data-label"><i class="fas fa-user me-1"></i>Nama Lengkap</span>
                                        <span class="data-value">{{ $data->nama_lengkap }}</span>
                                    </div>
                                    <div class="data-item">
                                        <span class="data-label"><i class="fas fa-venus-mars me-1"></i>Jenis Kelamin</span>
                                        <span class="data-value">{{ $data->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                    </div>
                                    <div class="data-item">
                                        <span class="data-label"><i class="fas fa-envelope me-1"></i>Email</span>
                                        <span class="data-value">{{ $data->email }}</span>
                                    </div>
                                    <div class="data-item">
                                        <span class="data-label"><i class="fab fa-whatsapp me-1"></i>WhatsApp</span>
                                        <span class="data-value">{{ $data->no_whatsapp }}</span>
                                    </div>
                                    <div class="data-item">
                                        <span class="data-label"><i class="fas fa-graduation-cap me-1"></i>Pendidikan</span>
                                        <span class="data-value">{{ $data->jenis_pendidikan == 'smk' ? 'SMK' : 'Perguruan Tinggi' }}</span>
                                    </div>
                                    @if($data->jenis_pendidikan == 'smk')
                                    <div class="data-item">
                                        <span class="data-label"><i class="fas fa-layer-group me-1"></i>Kelas</span>
                                        <span class="data-value">{{ $data->kelas ?? '-' }}</span>
                                    </div>
                                    <div class="data-item">
                                        <span class="data-label"><i class="fas fa-school me-1"></i>Sekolah</span>
                                        <span class="data-value">{{ $data->sekolah ?? '-' }}</span>
                                    </div>
                                    <div class="data-item">
                                        <span class="data-label"><i class="fas fa-book me-1"></i>Jurusan</span>
                                        <span class="data-value">{{ $data->jurusan_smk ?? '-' }}</span>
                                    </div>
                                    @else
                                    <div class="data-item">
                                        <span class="data-label"><i class="fas fa-university me-1"></i>Kampus</span>
                                        <span class="data-value">{{ $data->kuliah ?? '-' }}</span>
                                    </div>
                                    <div class="data-item">
                                        <span class="data-label"><i class="fas fa-book me-1"></i>Program Studi</span>
                                        <span class="data-value">{{ $data->jurusan_univ ?? '-' }}</span>
                                    </div>
                                    <div class="data-item">
                                        <span class="data-label"><i class="fas fa-layer-group me-1"></i>Semester</span>
                                        <span class="data-value">{{ $data->semester ?? '-' }}</span>
                                    </div>
                                    @endif
                                    <div class="data-item">
                                        <span class="data-label"><i class="fas fa-calendar-alt me-1"></i>Tanggal Mulai</span>
                                        <span class="data-value">{{ \Carbon\Carbon::parse($data->tanggal_mulai)->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="data-item">
                                        <span class="data-label"><i class="fas fa-calendar-check me-1"></i>Tanggal Selesai</span>
                                        <span class="data-value">{{ \Carbon\Carbon::parse($data->tanggal_selesai)->format('d/m/Y') }}</span>
                                    </div>
                                </div>

                                @if($data->file_surat_pengantar)
                                <div class="mt-3 p-3 bg-light rounded">
                                    <small class="text-muted d-block mb-2"><i class="fas fa-paperclip me-1"></i>Surat Pengantar</small>
                                    @php
                                        $file = $data->file_surat_pengantar;
                                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                        $fileUrl = asset($file);
                                    @endphp
                                    @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                                        <a href="{{ $fileUrl }}" target="_blank" class="d-block">
                                            <img src="{{ $fileUrl }}" class="img-fluid rounded border w-100" style="max-height: 200px; object-fit: cover;">
                                        </a>
                                        <div class="d-flex gap-2 mt-2">
                                            <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-outline-primary flex-grow-1">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                            <a href="{{ $fileUrl }}" download class="btn btn-sm btn-outline-secondary flex-grow-1">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    @elseif($ext == 'pdf')
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <i class="fas fa-file-pdf text-danger fs-3"></i>
                                            <span class="small text-break">{{ basename($file) }}</span>
                                            <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-outline-danger ms-auto">
                                                <i class="fas fa-eye"></i> Lihat PDF
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                @endif

                                @if($data->upload_cv)
                                <div class="mt-3 p-3 bg-light rounded">
                                    <small class="text-muted d-block mb-2"><i class="fas fa-file-alt me-1"></i>CV</small>
                                    @php
                                        $cvFile = $data->upload_cv;
                                        $cvExt = strtolower(pathinfo($cvFile, PATHINFO_EXTENSION));
                                        $cvUrl = asset($cvFile);
                                    @endphp
                                    @if(in_array($cvExt, ['jpg', 'jpeg', 'png']))
                                        <a href="{{ $cvUrl }}" target="_blank" class="d-block">
                                            <img src="{{ $cvUrl }}" class="img-fluid rounded border w-100" style="max-height: 200px; object-fit: cover;">
                                        </a>
                                        <div class="d-flex gap-2 mt-2">
                                            <a href="{{ $cvUrl }}" target="_blank" class="btn btn-sm btn-outline-primary flex-grow-1">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                            <a href="{{ $cvUrl }}" download class="btn btn-sm btn-outline-secondary flex-grow-1">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    @elseif($cvExt == 'pdf')
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <i class="fas fa-file-pdf text-danger fs-3"></i>
                                            <span class="small text-break">{{ basename($cvFile) }}</span>
                                            <a href="{{ $cvUrl }}" target="_blank" class="btn btn-sm btn-outline-danger ms-auto">
                                                <i class="fas fa-eye"></i> Lihat PDF
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                @endif

                            @else
                                <div class="data-list">
                                    <div class="data-item">
                                        <span class="data-label"><i class="fas fa-users me-1"></i>Nama Kelompok</span>
                                        <span class="data-value fw-bold">{{ $data->nama_kelompok }}</span>
                                    </div>
                                    <div class="data-item">
                                        <span class="data-label"><i class="fas fa-building me-1"></i>Institusi</span>
                                        <span class="data-value">{{ $data->institusi }}</span>
                                    </div>
                                    <div class="data-item">
                                        <span class="data-label"><i class="fas fa-user-friends me-1"></i>Jumlah Anggota</span>
                                        <span class="data-value">{{ $data->anggota_count }}/{{ $data->jumlah_anggota }}</span>
                                    </div>
                                    <div class="data-item">
                                        <span class="data-label"><i class="fas fa-user-tie me-1"></i>Perwakilan</span>
                                        <span class="data-value">{{ $data->perwakilan_nama }}</span>
                                    </div>
                                    <div class="data-item">
                                        <span class="data-label"><i class="fas fa-envelope me-1"></i>Email Perwakilan</span>
                                        <span class="data-value">{{ $data->perwakilan_email }}</span>
                                    </div>
                                    <div class="data-item">
                                        <span class="data-label"><i class="fab fa-whatsapp me-1"></i>WA Perwakilan</span>
                                        <span class="data-value">{{ $data->perwakilan_wa }}</span>
                                    </div>
                                    <div class="data-item">
                                        <span class="data-label"><i class="fas fa-calendar-alt me-1"></i>Tanggal Mulai</span>
                                        <span class="data-value">{{ \Carbon\Carbon::parse($data->tanggal_mulai)->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="data-item">
                                        <span class="data-label"><i class="fas fa-calendar-check me-1"></i>Tanggal Selesai</span>
                                        <span class="data-value">{{ \Carbon\Carbon::parse($data->tanggal_selesai)->format('d/m/Y') }}</span>
                                    </div>
                                </div>

                                @if(isset($data->anggota) && $data->anggota->count() > 0)
                                <div class="mt-3">
                                    <h6 class="fw-bold mb-2"><i class="fas fa-list me-2"></i>Daftar Anggota</h6>
                                    <div class="anggota-list">
                                        @foreach($data->anggota as $anggota)
                                        <div class="anggota-item">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <div class="fw-bold">{{ $anggota->nama }}</div>
                                                    <div class="small text-muted">{{ $anggota->nim_nis }}</div>
                                                    <div class="small">{{ $anggota->email }}</div>
                                                    <div class="small">+62 {{ $anggota->telepon }}</div>
                                                </div>
                                                @if($anggota->is_perwakilan)
                                                    <span class="badge bg-primary ms-2">Perwakilan</span>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @if($data->upload_surat_pengantar)
                                <div class="mt-3 p-3 bg-light rounded">
                                    <small class="text-muted d-block mb-2"><i class="fas fa-paperclip me-1"></i>Surat Pengantar Kelompok</small>
                                    @php
                                        $file = $data->upload_surat_pengantar;
                                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                        $fileUrl = asset($file);
                                    @endphp
                                    @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                                        <a href="{{ $fileUrl }}" target="_blank" class="d-block">
                                            <img src="{{ $fileUrl }}" class="img-fluid rounded border w-100" style="max-height: 200px; object-fit: cover;">
                                        </a>
                                        <div class="d-flex gap-2 mt-2">
                                            <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-outline-primary flex-grow-1">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                            <a href="{{ $fileUrl }}" download class="btn btn-sm btn-outline-secondary flex-grow-1">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    @elseif($ext == 'pdf')
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <i class="fas fa-file-pdf text-danger fs-3"></i>
                                            <span class="small text-break">{{ basename($file) }}</span>
                                            <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-outline-danger ms-auto">
                                                <i class="fas fa-eye"></i> Lihat PDF
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                @endif

                                @if($data->perwakilan_cv)
                                <div class="mt-3 p-3 bg-light rounded">
                                    <small class="text-muted d-block mb-2"><i class="fas fa-file-alt me-1"></i>CV Perwakilan</small>
                                    @php
                                        $cvFile = $data->perwakilan_cv;
                                        $cvExt = strtolower(pathinfo($cvFile, PATHINFO_EXTENSION));
                                        $cvUrl = asset($cvFile);
                                    @endphp
                                    @if(in_array($cvExt, ['jpg', 'jpeg', 'png']))
                                        <a href="{{ $cvUrl }}" target="_blank" class="d-block">
                                            <img src="{{ $cvUrl }}" class="img-fluid rounded border w-100" style="max-height: 200px; object-fit: cover;">
                                        </a>
                                        <div class="d-flex gap-2 mt-2">
                                            <a href="{{ $cvUrl }}" target="_blank" class="btn btn-sm btn-outline-primary flex-grow-1">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                            <a href="{{ $cvUrl }}" download class="btn btn-sm btn-outline-secondary flex-grow-1">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    @elseif($cvExt == 'pdf')
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <i class="fas fa-file-pdf text-danger fs-3"></i>
                                            <span class="small text-break">{{ basename($cvFile) }}</span>
                                            <a href="{{ $cvUrl }}" target="_blank" class="btn btn-sm btn-outline-danger ms-auto">
                                                <i class="fas fa-eye"></i> Lihat PDF
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                @endif

                            @endif

                            @php
                                $adminNotes = $data->catatan_admin ?? null;
                            @endphp
                            @if($adminNotes)
                            <div class="mt-3 p-3 bg-warning bg-opacity-10 rounded border-start border-warning border-3">
                                <small class="text-muted"><i class="fas fa-sticky-note me-1"></i> Catatan Admin</small>
                                <div class="mt-1">{{ $adminNotes }}</div>
                            </div>
                            @endif

                            <div class="d-flex gap-2 mt-4">
                                <a href="{{ route('user.pilih-tipe') }}" class="btn btn-outline-danger flex-grow-1">
                                    <i class="fas fa-file-alt me-1"></i> Daftar Lagi
                                </a>
                                <a href="{{ route('user.home') }}" class="btn btn-outline-secondary flex-grow-1">
                                    <i class="fas fa-home me-1"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                @elseif(session('not_found'))
                    <div class="card-custom text-center p-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5>Data Tidak Ditemukan</h5>
                        <p>Kode pendaftaran <strong>{{ session('search_kode') }}</strong> tidak terdaftar.</p>
                        <a href="{{ route('user.pilih-tipe') }}" class="btn btn-danger">Daftar Sekarang</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card-custom {
        background: white;
        border-radius: 12px;
        border: 1px solid #dee2e6;
        overflow: hidden;
    }
    .card-header-custom {
        background: #f8f9fa;
        padding: 1rem;
        border-bottom: 2px solid #dc3545;
    }
    .info-card {
        background: #fff5f5;
        border-radius: 12px;
        border: 1px solid #ffcccc;
    }
    .info-card-header {
        background: #dc3545;
        color: white;
        padding: 0.75rem 1rem;
        border-radius: 12px 12px 0 0;
    }
    .info-card-body {
        padding: 1rem;
    }
    .info-item {
        display: flex;
        gap: 8px;
        padding: 0.5rem;
        background: white;
        border-radius: 8px;
        height: 100%;
    }
    .info-icon {
        width: 32px;
        height: 32px;
        background: #ffe6e6;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #dc3545;
        flex-shrink: 0;
    }
    .info-content h6 {
        font-size: 0.8rem;
    }
    .info-content p {
        font-size: 0.7rem;
    }
    .form-control-custom {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 0.75rem;
    }
    .form-control-custom:focus {
        border-color: #dc3545;
        outline: none;
        box-shadow: 0 0 0 2px rgba(220,53,69,0.1);
    }
    .form-label-custom {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        display: block;
        font-size: 0.875rem;
    }
    .btn-primary-custom {
        background: #dc3545;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .btn-primary-custom:hover {
        background: #b30000;
    }
    .btn-outline-danger {
        border: 1px solid #dc3545;
        color: #dc3545;
        background: white;
    }
    .btn-outline-danger:hover {
        background: #dc3545;
        color: white;
    }
    .btn-link {
        color: #dc3545;
        font-size: 0.85rem;
        font-weight: 500;
    }
    .btn-link:hover {
        color: #a30000;
    }
    .data-list {
        display: flex;
        flex-direction: column;
        gap: 1px;
        background: #dee2e6;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #dee2e6;
    }
    .data-item {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        background: white;
        padding: 0.75rem 1rem;
    }
    .data-label {
        width: 40%;
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 500;
    }
    .data-value {
        width: 60%;
        font-weight: 500;
        color: #212529;
        word-break: break-word;
    }
    .anggota-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .anggota-item {
        background: #f8f9fa;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        border-left: 3px solid #dc3545;
    }
    .toast-custom {
        position: fixed;
        bottom: 25px;
        right: 25px;
        z-index: 9999;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        border-left: 5px solid #28a745;
        animation: slideIn 0.3s ease;
        padding: 0;
        overflow: hidden;
    }
    .toast-custom.error {
        border-left-color: #dc3545;
    }
    .toast-body-custom {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        font-size: 0.9rem;
    }
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    .hero-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #dc3545;
    }
    @media (max-width: 576px) {
        .info-item {
            flex-direction: column;
            text-align: center;
            padding: 0.75rem 0.25rem;
        }
        .info-icon {
            margin: 0 auto;
        }
        .info-content h6 {
            font-size: 0.75rem;
        }
        .info-content p {
            font-size: 0.65rem;
        }
        .data-item {
            flex-direction: column;
            gap: 4px;
            padding: 0.75rem;
        }
        .data-label {
            width: 100%;
            font-size: 0.8rem;
        }
        .data-value {
            width: 100%;
            font-size: 0.95rem;
        }
        .container {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }
        .card-body {
            padding: 0.75rem !important;
        }
        .btn {
            font-size: 0.9rem;
        }
        .fs-5 {
            font-size: 1rem !important;
        }
        .badge {
            font-size: 0.8rem;
        }
        .hero-title {
            font-size: 1.5rem;
        }
    }
    @media (min-width: 577px) and (max-width: 768px) {
        .data-label {
            width: 35%;
        }
        .data-value {
            width: 65%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
function switchMode(mode) {
    const modeKode = document.getElementById('modeKode');
    const modeWA = document.getElementById('modeWA');
    const waResult = document.getElementById('waResult');
    const infoLupaKode = document.getElementById('infoLupaKode');
    
    if (mode === 'wa') {
        modeKode.style.display = 'none';
        modeWA.style.display = 'block';
        if (waResult) {
            waResult.style.display = 'none';
            waResult.innerHTML = '';
        }
        if (infoLupaKode) {
            infoLupaKode.style.display = 'block';
        }
        document.getElementById('waInput').value = '';
    } else {
        modeWA.style.display = 'none';
        modeKode.style.display = 'block';
        if (waResult) {
            waResult.style.display = 'none';
            waResult.innerHTML = '';
        }
        document.getElementById('waInput').value = '';
    }
}

function kirimKodeViaWA() {
    const waInput = document.getElementById('waInput');
    const wa = waInput.value.trim();
    const waResult = document.getElementById('waResult');
    const btnKirim = document.getElementById('btnCariWA');
    const infoLupaKode = document.getElementById('infoLupaKode');
    
    if (!wa || wa.length < 10) {
        if (waResult) {
            waResult.style.display = 'block';
            waResult.innerHTML = '<div class="alert alert-warning py-2 mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Masukkan nomor WhatsApp yang valid (minimal 10 digit)</div>';
        }
        return;
    }
    
    if (waResult) {
        waResult.style.display = 'block';
        waResult.innerHTML = '<div class="alert alert-info py-2 mb-0"><i class="fas fa-spinner fa-spin me-2"></i>Mengirim kode via WhatsApp...</div>';
    }
    btnKirim.disabled = true;
    btnKirim.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...';
    
    fetch('/get-kode-by-wa', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ no_whatsapp: wa })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let extraInfo = data.kode_count > 1 ? 
                '<br><small class="text-muted">Ditemukan ' + data.kode_count + ' pendaftaran dengan nomor ini. Semua kode telah dikirim.</small>' : '';
            
            waResult.innerHTML = '<div class="alert alert-success py-2 mb-0">' +
                '<i class="fab fa-whatsapp me-2"></i>' +
                '<strong>Kode terkirim!</strong> ' + data.message + extraInfo +
                '</div>';
            showToast('Kode pendaftaran dikirim via WhatsApp!', 'success');
        } else {
            waResult.innerHTML = '<div class="alert alert-warning py-2 mb-0">' +
                '<i class="fas fa-exclamation-circle me-2"></i>' +
                (data.message || 'Nomor WhatsApp tidak ditemukan.') +
                '</div>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        waResult.innerHTML = '<div class="alert alert-danger py-2 mb-0"><i class="fas fa-times-circle me-2"></i>Terjadi kesalahan. Silakan coba lagi.</div>';
    })
    .finally(() => {
        btnKirim.disabled = false;
        btnKirim.innerHTML = '<i class="fab fa-whatsapp me-2"></i>Kirim via WhatsApp';
    });
}

function copyKode(kode) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(kode).then(() => {
            showToast('Kode berhasil disalin!', 'success');
        }).catch(() => {
            fallbackCopy(kode);
        });
    } else {
        fallbackCopy(kode);
    }
}

function fallbackCopy(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.top = '0';
    textArea.style.left = '0';
    textArea.style.width = '2em';
    textArea.style.height = '2em';
    textArea.style.padding = '0';
    textArea.style.border = 'none';
    textArea.style.outline = 'none';
    textArea.style.boxShadow = 'none';
    textArea.style.background = 'transparent';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    try {
        document.execCommand('copy');
        showToast('Kode berhasil disalin!', 'success');
    } catch (err) {
        showToast('Gagal menyalin kode', 'error');
    }
    document.body.removeChild(textArea);
}

function showToast(message, type = 'success') {
    const existingToast = document.querySelector('.toast-custom');
    if (existingToast) existingToast.remove();
    
    const toast = document.createElement('div');
    toast.className = 'toast-custom' + (type === 'error' ? ' error' : '');
    toast.innerHTML = `
        <div class="toast-body-custom">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}" style="color: ${type === 'success' ? '#28a745' : '#dc3545'}; font-size: 1.5rem;"></i>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

document.addEventListener('DOMContentLoaded', function() {
    const waInput = document.getElementById('waInput');
    if (waInput) {
        waInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                kirimKodeViaWA();
            }
        });
    }
});
</script>
@endpush

@endsection