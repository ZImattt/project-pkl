@extends('layouts.app')

@section('title', 'Tambah Anggota Kelompok - Global Intermedia')

@push('styles')
<style>
    :root {
        --primary-red: #dc3545;
        --primary-red-light: #fff5f5;
        --primary-red-dark: #c82333;
        --border-color: #dee2e6;
        --shadow-sm: 0 2px 4px rgba(0,0,0,0.1);
        --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--primary-red);
        flex-wrap: wrap;
        gap: 8px;
    }

    .page-title {
        color: var(--primary-red);
        font-weight: 700;
        font-size: 1.1rem;
        margin: 0;
    }

    .page-subtitle {
        color: #6c757d;
        font-size: 0.65rem;
        margin-top: 2px;
    }

    .badge-total {
        background: linear-gradient(135deg, var(--primary-red) 0%, #e11d48 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 4px 12px rgba(220,53,69,0.25);
    }

    /* Progress Steps */
    .alur-pendaftaran {
        background: white;
        border-radius: var(--radius-lg);
        padding: 20px;
        box-shadow: var(--shadow-sm);
        margin-bottom: 20px;
        border: 1px solid var(--border-color);
    }

    .alur-steps {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }

    .alur-step {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
        opacity: 0.5;
        min-width: 100px;
    }

    .alur-step.active { opacity: 1; }
    .alur-step.completed { opacity: 1; }

    .alur-step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 20px;
        right: -30%;
        width: 60%;
        height: 2px;
        background: #e9ecef;
        z-index: 1;
    }

    .alur-step.active::after { background: var(--primary-red); }
    .alur-step.completed::after { background: #28a745; }

    .step-number {
        width: 40px;
        height: 40px;
        background: #e9ecef;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: #6c757d;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .alur-step.active .step-number {
        background: var(--primary-red);
        color: white;
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
    }

    .alur-step.completed .step-number {
        background: #28a745;
        color: white;
    }

    .step-content { text-align: left; }
    .step-content h6 { margin: 0; font-size: 0.9rem; font-weight: 600; }
    .step-content small { font-size: 0.7rem; color: #6c757d; }

    .alur-progress {
        height: 4px;
        background: #e9ecef;
        border-radius: 2px;
        overflow: hidden;
    }

    .alur-progress .progress-bar {
        background: var(--primary-red);
        transition: width 0.3s ease;
    }

    /* Section Wrapper */
    .section-wrapper {
        background: #fff;
        border-radius: var(--radius-md);
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        margin-bottom: 1.5rem;
        border: 1px solid var(--border-color);
    }

    .section-header {
        margin-bottom: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .section-title {
        color: var(--primary-red);
        font-weight: 600;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        margin-bottom: 0;
    }

    .section-number {
        width: 2rem;
        height: 2rem;
        background: var(--primary-red);
        color: white;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .section-divider {
        height: 3px;
        background: linear-gradient(90deg, var(--primary-red) 0%, rgba(220, 53, 69, 0.2) 100%);
        border-radius: 3px;
        margin-top: 5px;
    }

    /* Form Elements */
    .form-label-custom {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.4rem;
        display: block;
        font-size: 0.8rem;
    }

    .form-control-custom {
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        padding: 0.55rem 0.75rem;
        width: 100%;
        background: #fff;
        font-size: 0.8rem;
        transition: all 0.2s ease;
    }

    .form-control-custom:focus {
        border-color: var(--primary-red);
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.1);
    }

    .input-group { display: flex; }
    .input-group-text {
        background: #e9ecef;
        border: 1px solid var(--border-color);
        border-right: none;
        border-radius: var(--radius-sm) 0 0 var(--radius-sm);
        padding: 0.55rem 0.75rem;
        font-size: 0.8rem;
    }
    .input-group .form-control-custom {
        border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
        border-left: none;
    }

    /* Info Card */
    .info-card {
        background: #f8f9fa;
        border-radius: var(--radius-md);
        padding: 1rem;
        border: 1px solid var(--border-color);
        margin-bottom: 1.5rem;
    }

    .info-row {
        display: flex;
        padding: 6px 0;
        font-size: 0.8rem;
        border-bottom: 1px solid var(--border-color);
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        width: 130px;
        color: #6c757d;
        font-weight: 500;
    }

    .info-value {
        flex: 1;
        font-weight: 500;
        color: #212529;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .copy-btn {
        background: #28a745;
        color: white;
        border: none;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        cursor: pointer;
    }

    .copy-btn:hover {
        background: #218838;
    }

    .btn-edit-small {
        background: #007bff;
        color: white;
        border: none;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }

    .btn-edit-small:hover {
        background: #0069d9;
        color: white;
    }

    /* Progress Bar */
    .progress-card {
        background: white;
        border-radius: var(--radius-md);
        padding: 1rem;
        border: 1px solid var(--border-color);
        margin-bottom: 1.5rem;
    }

    .progress-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .progress-bar-container {
        height: 8px;
        background: #e9ecef;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 8px;
    }

    .progress-fill {
        height: 100%;
        background: var(--primary-red);
        border-radius: 4px;
        transition: width 0.3s ease;
    }

    .progress-message {
        font-size: 0.7rem;
        color: #6c757d;
    }

    .text-success {
        color: #28a745;
    }

    /* Table Desktop */
    .table-responsive {
        overflow-x: auto;
    }

    .member-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.75rem;
    }

    .member-table th,
    .member-table td {
        padding: 10px 8px;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
    }

    .member-table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #495057;
    }

    .badge-warning {
        background: #ffc107;
        color: #212529;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.65rem;
        font-weight: 500;
        display: inline-block;
        margin-left: 6px;
    }

    .badge-secondary {
        background: #e9ecef;
        color: #495057;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.65rem;
        font-weight: 500;
    }

    .action-btns {
        display: flex;
        gap: 6px;
    }

    .btn-delete-small {
        background: #dc3545;
        color: white;
        border: none;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        cursor: pointer;
    }

    .btn-delete-small:hover {
        background: #c82333;
    }

    /* Mobile Drawer */
    .member-drawer {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .member-card {
        background: #f8f9fa;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }

    .member-card-header {
        padding: 12px;
        background: white;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .member-name {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .member-name strong {
        font-size: 0.85rem;
    }

    .member-actions {
        display: flex;
        gap: 6px;
    }

    .member-card-body {
        padding: 12px;
    }

    .member-info-row {
        display: flex;
        padding: 6px 0;
        font-size: 0.75rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .member-info-row:last-child {
        border-bottom: none;
    }

    .member-info-row .info-label {
        width: 100px;
        color: #6c757d;
        font-weight: 500;
    }

    .member-info-row .info-value {
        flex: 1;
        color: #212529;
        word-break: break-word;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }

    .empty-icon {
        font-size: 48px;
        margin-bottom: 16px;
    }

    .empty-state h4 {
        margin-bottom: 8px;
        color: #495057;
        font-size: 1rem;
    }

    .empty-state p {
        color: #6c757d;
        font-size: 0.8rem;
    }

    /* Form Actions */
    .form-actions {
        background: white;
        border-radius: var(--radius-md);
        padding: 1rem 1.5rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        margin-top: 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-back {
        border: 2px solid var(--border-color);
        color: #495057;
        padding: 0.65rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8rem;
        transition: all 0.2s ease;
        background: white;
    }

    .btn-back:hover {
        background: #f8f9fa;
        color: var(--primary-red);
        border-color: var(--primary-red);
    }

    .btn-submit {
        background: #28a745;
        border: none;
        color: white;
        padding: 0.65rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        cursor: pointer;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    .btn-submit:hover {
        background: #218838;
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    .btn-disabled {
        background: #e9ecef;
        color: #adb5bd;
        border: none;
        padding: 0.65rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        cursor: not-allowed;
        font-size: 0.8rem;
    }

    .btn-save {
        background: var(--primary-red);
        border: none;
        color: white;
        padding: 0.65rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        cursor: pointer;
        font-size: 0.8rem;
        transition: all 0.2s ease;
    }

    .btn-save:hover {
        background: var(--primary-red-dark);
    }

    .btn-cancel {
        background: #6c757d;
        border: none;
        color: white;
        padding: 0.65rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        cursor: pointer;
        font-size: 0.8rem;
    }

    /* Alert */
    .alert-custom {
        border-radius: var(--radius-md);
        padding: 1rem;
        margin-bottom: 1rem;
    }

    /* Modal */
    .modal-content-custom {
        border-radius: var(--radius-md);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .dashboard-header { margin-top: 50px; }
        .page-title { font-size: 1rem; }
        .section-wrapper { padding: 1rem; }
        .alur-step { flex-direction: column; text-align: center; gap: 5px; }
        .step-content { text-align: center; }
        .alur-step:not(:last-child)::after { top: 20px; right: -20%; width: 40%; }
        .info-row { flex-direction: column; gap: 4px; }
        .info-label { width: 100%; }
        .form-actions { flex-direction: column; }
        .btn-back, .btn-submit, .btn-disabled { width: 100%; justify-content: center; }
        .member-name { flex-direction: column; align-items: flex-start; }
        .member-actions { width: 100%; }
        .member-actions .btn-edit-small, .member-actions .btn-delete-small { flex: 1; text-align: center; }
        .member-info-row { flex-direction: column; gap: 4px; }
        .member-info-row .info-label { width: 100%; }
    }

    @media (min-width: 769px) {
        .desktop-only { display: block; }
        .mobile-only { display: none; }
    }

    @media (max-width: 768px) {
        .desktop-only { display: none; }
        .mobile-only { display: block; }
    }
</style>
@endpush

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <!-- HEADER -->
            <div class="dashboard-header">
                <div>
                    <h1 class="page-title">Kelola Anggota Kelompok</h1>
                    <p class="page-subtitle">{{ $kelompok->nama_kelompok }} - {{ $kelompok->institusi }}</p>
                </div>
                <div>
                    <span class="badge-total">
                        <i class="fas fa-users"></i> Total: <strong id="badgeCount">{{ $kelompok->anggota()->count() }}</strong> / <strong>{{ $kelompok->jumlah_anggota }}</strong>
                    </span>
                </div>
            </div>

            <!-- ALUR PROGRESS -->
            <div class="alur-pendaftaran">
                <div class="alur-steps">
                    <div class="alur-step completed">
                        <div class="step-number">1</div>
                        <div class="step-content"><h6>Data Kelompok</h6><small>Selesai</small></div>
                    </div>
                    <div class="alur-step active">
                        <div class="step-number">2</div>
                        <div class="step-content"><h6>Tambah Anggota</h6><small>Isi data anggota</small></div>
                    </div>
                    <div class="alur-step">
                        <div class="step-number">3</div>
                        <div class="step-content"><h6>Konfirmasi</h6><small>Periksa kembali</small></div>
                    </div>
                    <div class="alur-step">
                        <div class="step-number">4</div>
                        <div class="step-content"><h6>Cek Status</h6><small>Lacak status</small></div>
                    </div>
                </div>
                <div class="alur-progress">
                    <div class="progress-bar" style="width: 50%;"></div>
                </div>
            </div>

            @php
                $anggotaTerdaftarCount = $kelompok->anggota()->count();
                $maxAnggota = (int)$kelompok->jumlah_anggota;
                $sisaAnggotaCount = max(0, $maxAnggota - $anggotaTerdaftarCount);
                $persentase = $maxAnggota > 0 ? min(100, ($anggotaTerdaftarCount / $maxAnggota) * 100) : 0;
                $isFull = $anggotaTerdaftarCount >= $maxAnggota;
                $perwakilanAnggota = $kelompok->anggota()->where('is_perwakilan', true)->first();
                $anggotaList = $kelompok->anggota()->orderByDesc('is_perwakilan')->orderBy('id')->get();
            @endphp

            <!-- ALERTS -->
            @if(session('success'))
            <div class="alert alert-success alert-custom alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-custom alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-custom alert-dismissible fade show">
                <strong><i class="fas fa-exclamation-triangle me-2"></i>Error Validasi:</strong>
                <ul class="mb-0 ps-3 mt-1 small">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- INFO CARD -->
            <div class="section-wrapper">
                <div class="section-header">
                    <h4 class="section-title">
                        <span class="section-number">1</span>
                        <i class="fas fa-info-circle me-2"></i>Informasi Kelompok
                    </h4>
                    <a href="{{ url('pendaftaran/kelompok/'.$kelompok->id.'/edit') }}" class="btn-edit-small">
                        <i class="fas fa-edit me-1"></i>Edit Kelompok
                    </a>
                </div>
                <div class="section-divider"></div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="info-row">
                            <span class="info-label">Nama Kelompok</span>
                            <span class="info-value">{{ $kelompok->nama_kelompok }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Kode Pendaftaran</span>
                            <span class="info-value">
                                <span id="kodePendaftaran">{{ $kelompok->kode_pendaftaran }}</span>
                                <button class="copy-btn" onclick="copyKode()"><i class="fas fa-copy me-1"></i>Salin</button>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Asal Institusi</span>
                            <span class="info-value">{{ $kelompok->institusi }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <span class="info-label">Perwakilan</span>
                            <span class="info-value">
                                @if($perwakilanAnggota)
                                    {{ $perwakilanAnggota->nama }}
                                @else
                                    {{ $kelompok->perwakilan_nama ?? '-' }}
                                @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Total Anggota</span>
                            <span class="info-value" id="infoTotalAnggota">{{ $anggotaTerdaftarCount }} / {{ $maxAnggota }} orang</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Status</span>
                            <span class="info-value">
                                @if($isFull)
                                    <span class="text-success"><i class="fas fa-check-circle"></i> Lengkap</span>
                                @else
                                    <span class="text-warning"><i class="fas fa-clock"></i> Butuh {{ $sisaAnggotaCount }} anggota lagi</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PROGRESS BAR -->
            <div class="progress-card">
                <div class="progress-header">
                    <span>Progress Pengisian Anggota</span>
                    <span id="progressCount">{{ $anggotaTerdaftarCount }} / {{ $maxAnggota }}</span>
                </div>
                <div class="progress-bar-container">
                    <div class="progress-fill" id="progressFill" style="width: {{ $persentase }}%;"></div>
                </div>
                <div class="progress-message" id="progressMessage">
                    @if(!$isFull)
                        <i class="fas fa-info-circle me-1"></i> Masih butuh <strong id="sisaCount">{{ $sisaAnggotaCount }}</strong> anggota lagi
                    @else
                        <span class="text-success"><i class="fas fa-check-circle me-1"></i> Anggota sudah lengkap! Silakan lanjut ke tahap berikutnya.</span>
                    @endif
                </div>
            </div>

            <!-- FORM TAMBAH ANGGOTA -->
            @if(!$isFull)
            <div class="section-wrapper" id="formSection">
                <div class="section-header">
                    <h4 class="section-title">
                        <span class="section-number">2</span>
                        <i class="fas fa-user-plus me-2"></i>Tambah Anggota Baru
                    </h4>
                </div>
                <div class="section-divider"></div>

                <form method="POST" action="{{ url('pendaftaran/kelompok/simpan-peserta') }}" id="formAnggota" novalidate>
                    @csrf
                    <div id="methodContainer"></div>
                    <input type="hidden" name="kelompok_id" value="{{ $kelompok->id }}">
                    <input type="hidden" name="anggota_id" id="anggota_id">
                    <input type="hidden" name="tanggal_lahir" id="tgl_hidden">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control-custom" name="nama" id="nama" placeholder="Masukkan nama lengkap" required>
                            <div class="text-danger small" id="err-nama" style="display:none;"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-control-custom" name="jenis_kelamin" id="jk" required>
                                <option value="">Pilih</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                            <div class="text-danger small" id="err-jk" style="display:none;"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">NIM/NIS <span class="text-danger">*</span></label>
                            <input type="text" class="form-control-custom" name="nim_nis" id="nim_nis" placeholder="Masukkan NIM atau NIS" required>
                            <div class="text-danger small" id="err-nim_nis" style="display:none;"></div>
                            <div class="text-warning small d-none" id="warn-nim">⚠ NIM/NIS sudah digunakan</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control-custom" name="email" id="email" placeholder="contoh@email.com" required>
                            <div class="text-danger small" id="err-email" style="display:none;"></div>
                            <div class="text-warning small d-none" id="warn-email">⚠ Email sudah digunakan</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">No. Telepon <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">+62</span>
                                <input type="tel" class="form-control-custom" name="telepon" id="telepon" placeholder="81234567890" maxlength="13" required>
                            </div>
                            <div class="text-danger small" id="err-telepon" style="display:none;"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Tempat Lahir</label>
                            <input type="text" class="form-control-custom" name="tempat_lahir" id="tempat_lahir" placeholder="Kota kelahiran">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Tanggal Lahir</label>
                            <div class="row g-2">
                                <div class="col-4">
                                    <select class="form-control-custom" id="tgl_h">
                                        <option value="">Tanggal</option>
                                        @for($i=1; $i<=31; $i++)
                                        <option value="{{ str_pad($i,2,'0',STR_PAD_LEFT) }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-4">
                                    <select class="form-control-custom" id="tgl_b">
                                        <option value="">Bulan</option>
                                        @php
                                        $bulanNama = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                                        @endphp
                                        @foreach($bulanNama as $i => $nama)
                                        <option value="{{ str_pad($i+1,2,'0',STR_PAD_LEFT) }}">{{ $nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4">
                                    <select class="form-control-custom" id="tgl_t">
                                        <option value="">Tahun</option>
                                        @for($i=date('Y'); $i>=date('Y')-100; $i--)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label-custom">Alamat</label>
                            <textarea class="form-control-custom" name="alamat" id="alamat" rows="2" placeholder="Alamat lengkap"></textarea>
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2 justify-content-end">
                        <button type="button" class="btn-cancel d-none" id="btnBatal" onclick="batalEdit()">Batal Edit</button>
                        <button type="submit" class="btn-save" id="btnSubmit">
                            <i class="fas fa-save me-1"></i> Simpan Anggota
                        </button>
                    </div>
                </form>
            </div>
            @endif

            <!-- DAFTAR ANGGOTA -->
            <div class="section-wrapper">
                <div class="section-header">
                    <h4 class="section-title">
                        <span class="section-number">3</span>
                        <i class="fas fa-users me-2"></i>Daftar Anggota
                    </h4>
                    <span class="badge-total" style="background: #6c757d;">
                        <i class="fas fa-user-friends"></i> {{ $anggotaTerdaftarCount }} / {{ $maxAnggota }}
                    </span>
                </div>
                <div class="section-divider"></div>

                @if($anggotaTerdaftarCount > 0)
                    <!-- DESKTOP TABLE -->
                    <div class="table-responsive desktop-only">
                        <table class="member-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>JK</th>
                                    <th>NIM/NIS</th>
                                    <th>Email</th>
                                    <th>No. Telepon</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyAnggota">
                                @foreach($anggotaList as $i => $a)
                                <tr id="row-{{ $a->id }}">
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        {{ $a->nama }}
                                        @if($a->is_perwakilan)
                                            <span class="badge-warning"><i class="fas fa-star"></i> Perwakilan</span>
                                        @endif
                                    </td>
                                    <td>{{ $a->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    <td>{{ $a->nim_nis }}</td>
                                    <td class="email-cell">{{ $a->email }}</td>
                                    <td>+62{{ $a->telepon }}</td>
                                    <td>
                                        @if($a->is_perwakilan)
                                            <span class="badge-warning">Perwakilan</span>
                                        @else
                                            <span class="badge-secondary">Anggota</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-btns">
                                            @if($a->is_perwakilan)
                                                <a href="{{ url('pendaftaran/kelompok/'.$kelompok->id.'/edit') }}" class="btn-edit-small">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                            @else
                                                <button class="btn-edit-small" onclick="editAnggota({{ $a->id }})">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <button class="btn-delete-small" onclick="hapusAnggota({{ $a->id }})">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- MOBILE DRAWER -->
                    <div class="member-drawer mobile-only">
                        @foreach($anggotaList as $i => $a)
                        <div class="member-card" id="mobile-row-{{ $a->id }}">
                            <div class="member-card-header">
                                <div class="member-name">
                                    <strong>{{ $i + 1 }}. {{ $a->nama }}</strong>
                                    @if($a->is_perwakilan)
                                        <span class="badge-warning"><i class="fas fa-star"></i> Perwakilan</span>
                                    @else
                                        <span class="badge-secondary">Anggota</span>
                                    @endif
                                </div>
                                <div class="member-actions">
                                    @if($a->is_perwakilan)
                                        <a href="{{ url('pendaftaran/kelompok/'.$kelompok->id.'/edit') }}" class="btn-edit-small">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    @else
                                        <button class="btn-edit-small" onclick="editAnggota({{ $a->id }})">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="btn-delete-small" onclick="hapusAnggota({{ $a->id }})">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div class="member-card-body">
                                <div class="member-info-row">
                                    <span class="info-label">Jenis Kelamin</span>
                                    <span class="info-value">{{ $a->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                </div>
                                <div class="member-info-row">
                                    <span class="info-label">NIM/NIS</span>
                                    <span class="info-value">{{ $a->nim_nis }}</span>
                                </div>
                                <div class="member-info-row">
                                    <span class="info-label">Email</span>
                                    <span class="info-value">{{ $a->email }}</span>
                                </div>
                                <div class="member-info-row">
                                    <span class="info-label">No. Telepon</span>
                                    <span class="info-value">+62{{ $a->telepon }}</span>
                                </div>
                                @if($a->tempat_lahir)
                                <div class="member-info-row">
                                    <span class="info-label">Tempat Lahir</span>
                                    <span class="info-value">{{ $a->tempat_lahir }}</span>
                                </div>
                                @endif
                                @if($a->tanggal_lahir)
                                <div class="member-info-row">
                                    <span class="info-label">Tanggal Lahir</span>
                                    <span class="info-value">{{ \Carbon\Carbon::parse($a->tanggal_lahir)->translatedFormat('d F Y') }}</span>
                                </div>
                                @endif
                                @if($a->alamat)
                                <div class="member-info-row">
                                    <span class="info-label">Alamat</span>
                                    <span class="info-value">{{ $a->alamat }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">👥</div>
                        <h4>Belum ada anggota</h4>
                        <p>Silakan tambahkan anggota kelompok menggunakan form di atas</p>
                    </div>
                @endif
            </div>

            <!-- BUTTON ACTIONS -->
            <div class="form-actions">
                <a href="{{ url('pendaftaran/kelompok') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                @if($isFull)
                <form action="{{ url('pendaftaran/kelompok/'.$kelompok->id.'/final-submit') }}" method="POST" onsubmit="return confirm('Yakin ingin menyelesaikan pendaftaran? Data tidak dapat diubah lagi.')">
                    @csrf
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-check-circle"></i> Selesai & Lanjutkan
                    </button>
                </form>
                @else
                <button class="btn-disabled" disabled>
                    <i class="fas fa-lock"></i> Lengkapi Anggota Dulu
                </button>
                @endif
            </div>

        </div>
    </div>
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-trash-alt me-2"></i>Hapus Anggota</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p>Yakin ingin menghapus anggota:</p>
                <p class="fw-bold text-danger" id="namaHapus"></p>
                <p class="text-muted small">Data yang dihapus tidak dapat dikembalikan!</p>
            </div>
            <div class="modal-footer justify-content-center gap-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="formHapus" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const anggotaTerdaftar = @json($anggotaList->keyBy('id'));
const maxAnggota = {{ $maxAnggota }};
let currentAnggotaCount = {{ $anggotaTerdaftarCount }};
let editingId = null;

const UPDATE_ANGGOTA_URL = '{{ url("pendaftaran/kelompok/update-anggota") }}';
const SIMPAN_PESERTA_URL = '{{ url("pendaftaran/kelompok/simpan-peserta") }}';
const HAPUS_ANGGOTA_BASE_URL = '{{ url("pendaftaran/kelompok/anggota") }}';

document.addEventListener('DOMContentLoaded', function() {
    const teleponField = document.getElementById('telepon');
    if (teleponField) {
        teleponField.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').substring(0, 13);
        });
    }
    
    const nimNisField = document.getElementById('nim_nis');
    if (nimNisField) {
        nimNisField.addEventListener('blur', cekDuplikatNIM);
        nimNisField.addEventListener('input', () => hideWarning('warn-nim'));
    }
    
    const emailField = document.getElementById('email');
    if (emailField) {
        emailField.addEventListener('blur', cekDuplikatEmail);
        emailField.addEventListener('input', () => hideWarning('warn-email'));
    }
    
    const form = document.getElementById('formAnggota');
    if (form) {
        form.addEventListener('submit', handleSubmit);
    }
});

function getTanggalLahir() {
    const h = document.getElementById('tgl_h').value;
    const b = document.getElementById('tgl_b').value;
    const t = document.getElementById('tgl_t').value;
    return (h && b && t) ? `${t}-${b}-${h}` : '';
}

function setTanggalLahir(tanggal) {
    if (tanggal && tanggal.includes('-')) {
        const parts = tanggal.split('-');
        const tglT = document.getElementById('tgl_t');
        const tglB = document.getElementById('tgl_b');
        const tglH = document.getElementById('tgl_h');
        if (tglT) tglT.value = parts[0];
        if (tglB) tglB.value = parts[1];
        if (tglH) tglH.value = parts[2];
    }
}

function cekDuplikatNIM() {
    const element = document.getElementById('nim_nis');
    const warningEl = document.getElementById('warn-nim');
    if (!element || !warningEl) return false;
    
    const value = element.value.trim().toUpperCase();
    if (!value) {
        warningEl.classList.add('d-none');
        return false;
    }
    
    let duplikat = false;
    for (const [id, anggota] of Object.entries(anggotaTerdaftar)) {
        if (editingId && parseInt(id) === parseInt(editingId)) continue;
        if (anggota.nim_nis && anggota.nim_nis.toUpperCase() === value) {
            duplikat = true;
            break;
        }
    }
    
    warningEl.classList.toggle('d-none', !duplikat);
    if (duplikat) {
        element.style.borderColor = '#ffc107';
    }
    return duplikat;
}

function cekDuplikatEmail() {
    const element = document.getElementById('email');
    const warningEl = document.getElementById('warn-email');
    if (!element || !warningEl) return false;
    
    const value = element.value.trim().toLowerCase();
    if (!value) {
        warningEl.classList.add('d-none');
        return false;
    }
    
    let duplikat = false;
    for (const [id, anggota] of Object.entries(anggotaTerdaftar)) {
        if (editingId && parseInt(id) === parseInt(editingId)) continue;
        if (anggota.email && anggota.email.toLowerCase() === value) {
            duplikat = true;
            break;
        }
    }
    
    warningEl.classList.toggle('d-none', !duplikat);
    if (duplikat) {
        element.style.borderColor = '#ffc107';
    }
    return duplikat;
}

function hideWarning(id) {
    const el = document.getElementById(id);
    if (el) el.classList.add('d-none');
}

function resetForm() {
    const form = document.getElementById('formAnggota');
    if (!form) return;
    
    form.reset();
    form.action = SIMPAN_PESERTA_URL;
    
    const methodContainer = document.getElementById('methodContainer');
    if (methodContainer) methodContainer.innerHTML = '';
    
    const anggotaId = document.getElementById('anggota_id');
    if (anggotaId) anggotaId.value = '';
    
    const tglHidden = document.getElementById('tgl_hidden');
    if (tglHidden) tglHidden.value = '';
    
    setTanggalLahir('');
    editingId = null;
    
    const btnBatal = document.getElementById('btnBatal');
    const btnSubmit = document.getElementById('btnSubmit');
    
    if (btnBatal) btnBatal.classList.add('d-none');
    if (btnSubmit) btnSubmit.innerHTML = '<i class="fas fa-save me-1"></i> Simpan Anggota';
    
    document.querySelectorAll('.text-danger.small').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.form-control-custom').forEach(el => el.style.borderColor = '');
    hideWarning('warn-nim');
    hideWarning('warn-email');
}

function editAnggota(id) {
    const anggota = anggotaTerdaftar[id];
    if (!anggota) {
        alert('Data anggota tidak ditemukan!');
        return;
    }
    
    const form = document.getElementById('formAnggota');
    if (!form) return;
    
    form.action = UPDATE_ANGGOTA_URL;
    
    const methodContainer = document.getElementById('methodContainer');
    if (methodContainer) methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';
    
    editingId = parseInt(id);
    
    document.getElementById('anggota_id').value = anggota.id;
    document.getElementById('nama').value = anggota.nama;
    document.getElementById('jk').value = anggota.jenis_kelamin;
    document.getElementById('nim_nis').value = anggota.nim_nis;
    document.getElementById('email').value = anggota.email;
    document.getElementById('telepon').value = anggota.telepon;
    document.getElementById('tempat_lahir').value = anggota.tempat_lahir || '';
    document.getElementById('alamat').value = anggota.alamat || '';
    
    setTanggalLahir(anggota.tanggal_lahir);
    
    const btnBatal = document.getElementById('btnBatal');
    const btnSubmit = document.getElementById('btnSubmit');
    
    if (btnBatal) btnBatal.classList.remove('d-none');
    if (btnSubmit) btnSubmit.innerHTML = '<i class="fas fa-save me-1"></i> Update Anggota';
    
    document.querySelectorAll('.text-danger.small').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.form-control-custom').forEach(el => el.style.borderColor = '');
    hideWarning('warn-nim');
    hideWarning('warn-email');
    
    const formSection = document.getElementById('formSection');
    if (formSection) {
        formSection.style.display = 'block';
        formSection.scrollIntoView({ behavior: 'smooth' });
    }
}

function batalEdit() {
    resetForm();
    if (currentAnggotaCount >= maxAnggota) {
        const formSection = document.getElementById('formSection');
        if (formSection) formSection.style.display = 'none';
    }
}

function hapusAnggota(id) {
    const anggota = anggotaTerdaftar[id];
    if (!anggota) return;
    
    const namaHapus = document.getElementById('namaHapus');
    const formHapus = document.getElementById('formHapus');
    
    if (namaHapus) namaHapus.textContent = anggota.nama;
    if (formHapus) formHapus.action = HAPUS_ANGGOTA_BASE_URL + '/' + id;
    
    const modalEl = document.getElementById('modalHapus');
    if (modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
}

function handleSubmit(e) {
    e.preventDefault();
    
    const tglHidden = document.getElementById('tgl_hidden');
    if (tglHidden) tglHidden.value = getTanggalLahir();
    
    let isValid = true;
    
    const nama = document.getElementById('nama')?.value.trim();
    if (!nama) {
        document.getElementById('err-nama').textContent = 'Nama lengkap wajib diisi';
        document.getElementById('err-nama').style.display = 'block';
        isValid = false;
    } else {
        document.getElementById('err-nama').style.display = 'none';
    }
    
    const jk = document.getElementById('jk')?.value;
    if (!jk) {
        document.getElementById('err-jk').textContent = 'Pilih jenis kelamin';
        document.getElementById('err-jk').style.display = 'block';
        isValid = false;
    } else {
        document.getElementById('err-jk').style.display = 'none';
    }
    
    const nimNis = document.getElementById('nim_nis')?.value.trim();
    if (!nimNis) {
        document.getElementById('err-nim_nis').textContent = 'NIM/NIS wajib diisi';
        document.getElementById('err-nim_nis').style.display = 'block';
        isValid = false;
    } else {
        document.getElementById('err-nim_nis').style.display = 'none';
    }
    
    const email = document.getElementById('email')?.value.trim();
    if (!email) {
        document.getElementById('err-email').textContent = 'Email wajib diisi';
        document.getElementById('err-email').style.display = 'block';
        isValid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        document.getElementById('err-email').textContent = 'Format email tidak valid';
        document.getElementById('err-email').style.display = 'block';
        isValid = false;
    } else {
        document.getElementById('err-email').style.display = 'none';
    }
    
    const telepon = document.getElementById('telepon')?.value.trim();
    if (!telepon) {
        document.getElementById('err-telepon').textContent = 'Nomor telepon wajib diisi';
        document.getElementById('err-telepon').style.display = 'block';
        isValid = false;
    } else if (!/^\d{9,13}$/.test(telepon)) {
        document.getElementById('err-telepon').textContent = 'Nomor telepon harus 9-13 digit';
        document.getElementById('err-telepon').style.display = 'block';
        isValid = false;
    } else {
        document.getElementById('err-telepon').style.display = 'none';
    }
    
    if (cekDuplikatNIM()) {
        alert('NIM/NIS sudah digunakan!');
        isValid = false;
    }
    
    if (cekDuplikatEmail()) {
        alert('Email sudah digunakan!');
        isValid = false;
    }
    
    if (!editingId && currentAnggotaCount >= maxAnggota) {
        alert('Jumlah anggota maksimal ' + maxAnggota + ' orang!');
        isValid = false;
    }
    
    if (!isValid) return;
    
    const btnSubmit = document.getElementById('btnSubmit');
    if (btnSubmit) {
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
    }
    
    this.submit();
}

function copyKode() {
    const kodeSpan = document.getElementById('kodePendaftaran');
    if (!kodeSpan) return;
    
    const kode = kodeSpan.textContent.trim();
    if (!kode) return;
    
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(kode).then(() => {
            const btn = document.querySelector('.copy-btn');
            if (btn) {
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check me-1"></i> Tersalin!';
                setTimeout(() => {
                    btn.innerHTML = originalText;
                }, 2000);
            }
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
    textArea.style.opacity = '0';
    document.body.appendChild(textArea);
    textArea.select();
    
    try {
        document.execCommand('copy');
        alert('Kode berhasil disalin: ' + text);
    } catch (err) {
        alert('Kode pendaftaran: ' + text);
    }
    
    document.body.removeChild(textArea);
}

window.editAnggota = editAnggota;
window.batalEdit = batalEdit;
window.hapusAnggota = hapusAnggota;
window.resetForm = resetForm;
window.copyKode = copyKode;
</script>
@endpush
@endsection