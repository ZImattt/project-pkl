@extends('layouts.app')

@section('title', isset($kelompok) ? 'Edit Data Kelompok' : 'Pendaftaran Kelompok PKL/Magang - Global Intermedia')

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

    .section-wrapper {
        background: #fff;
        border-radius: var(--radius-md);
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        margin-bottom: 1.5rem;
        border: 1px solid var(--border-color);
    }

    .section-header { margin-bottom: 1rem; }

    .section-title {
        color: var(--primary-red);
        font-weight: 600;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
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
    }

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

    .education-type-selector { margin-bottom: 0.5rem; }

    .education-option {
        border: 2px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: 1rem;
        cursor: pointer;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        transition: all 0.2s ease;
        height: 100%;
    }

    .education-option:hover { border-color: var(--primary-red); background: var(--primary-red-light); }
    .education-option.selected { border-color: var(--primary-red); background: var(--primary-red-light); }
    .education-icon { font-size: 1.5rem; color: var(--primary-red); }
    .education-content h6 { margin: 0; font-size: 0.9rem; }

    .pendidikan-fields {
        background: #f8f9fa;
        border-radius: var(--radius-md);
        padding: 1.2rem;
        margin-top: 1rem;
        border: 1px solid var(--border-color);
    }

    .duration-card {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        background: linear-gradient(135deg, #e3f2fd, #bbdefb);
        border-radius: var(--radius-md);
        border: 1px solid #bbdefb;
    }

    .duration-icon { font-size: 1.3rem; color: #2196f3; margin-right: 0.75rem; }
    .duration-content h6 { margin: 0; font-size: 0.8rem; }
    .duration-content p { margin: 0; font-size: 0.75rem; }

    .terms-card {
        border: 2px solid var(--primary-red);
        background: var(--primary-red-light);
        border-radius: var(--radius-md);
    }

    .terms-card .form-check-label {
        font-size: 0.8rem;
        line-height: 1.5;
    }

    .document-info-box {
        background: #e8f4fd;
        border-radius: var(--radius-md);
        padding: 0.75rem 1rem;
        border-left: 4px solid var(--primary-red);
    }

    .document-info-box .info-icon {
        width: 36px;
        height: 36px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-red);
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .info-text strong { color: var(--primary-red); display: block; margin-bottom: 2px; font-size: 0.8rem; }
    .info-text p { font-size: 0.7rem; }

    .modern-upload-wrapper { position: relative; width: 100%; }
    .modern-file-input { position: absolute; opacity: 0; width: 0.1px; height: 0.1px; overflow: hidden; z-index: -1; }

    .modern-upload-label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
        border: 2px dashed var(--border-color);
        border-radius: var(--radius-lg);
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }

    .modern-upload-label:hover {
        border-color: var(--primary-red);
        background: var(--primary-red-light);
    }

    .upload-icon {
        width: 44px;
        height: 44px;
        background: var(--primary-red-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-red);
        font-size: 1.3rem;
    }

    .upload-text h6 { margin: 0; font-weight: 600; color: #495057; font-size: 0.85rem; }
    .upload-text p { font-size: 0.7rem; }

    .file-info-modern { margin-top: 0.75rem; }

    .file-card {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
    }

    .file-card-icon {
        width: 42px;
        height: 42px;
        background: var(--primary-red-light);
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: var(--primary-red);
    }

    .file-card-details { flex: 1; min-width: 0; }
    .file-name { font-weight: 600; color: #212529; font-size: 0.8rem; word-break: break-all; }
    .file-size { font-size: 0.65rem; color: #6c757d; margin-top: 2px; }

    .file-card-actions { display: flex; gap: 0.4rem; }

    .file-action-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        background: transparent;
        font-size: 0.75rem;
    }

    .preview-btn { background: #e3f2fd; color: #1976d2; }
    .preview-btn:hover { background: #bbdefb; }
    .delete-btn { background: #ffebee; color: #dc3545; }
    .delete-btn:hover { background: #ffcdd2; }

    /* ========== FORM ACTIONS - NORMAL FLOW (TIDAK FIXED) ========== */
    .form-actions {
        background: white;
        border-radius: var(--radius-md);
        padding: 1rem 1.5rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        margin-top: 1.5rem;
        margin-bottom: 2rem;
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
        background: var(--primary-red);
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
        background: var(--primary-red-dark);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    .btn-submit:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .alert-custom {
        border-radius: var(--radius-md);
        padding: 1rem;
        margin-bottom: 1rem;
    }

    @media (max-width: 768px) {
        .dashboard-header { margin-top: 50px; }
        .page-title { font-size: 1rem; }
        .section-wrapper { padding: 1rem; }
        .form-actions { padding: 0.75rem 1rem; }
        .btn-back, .btn-submit {
            padding: 0.55rem 1.2rem;
            font-size: 0.75rem;
        }
        .education-option { padding: 0.75rem; }
        .modern-upload-label { flex-direction: column; padding: 1.2rem; }
        .file-card { flex-wrap: wrap; }
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
                    <h1 class="page-title">
                        {{ isset($kelompok) ? 'Edit Data Kelompok' : 'Pendaftaran Kelompok PKL/Magang' }}
                    </h1>
                    <p class="page-subtitle">
                        {{ isset($kelompok) ? 'Edit data kelompok dengan lengkap dan benar.' : 'Isi data kelompok dengan lengkap dan benar.' }}
                    </p>
                </div>
                @if(isset($kelompok))
                <div>
                    <span class="badge-total">
                        <i class="fas fa-key"></i> Kode: <strong>{{ $kelompok->kode_pendaftaran }}</strong>
                    </span>
                </div>
                @endif
            </div>

            <!-- ALUR (Hanya untuk create) -->
            @if(!isset($kelompok))
            <div class="alur-pendaftaran">
                <div class="alur-steps">
                    <div class="alur-step active" id="step-1">
                        <div class="step-number">1</div>
                        <div class="step-content"><h6>Data Kelompok</h6><small>Lengkapi data</small></div>
                    </div>
                    <div class="alur-step" id="step-2">
                        <div class="step-number">2</div>
                        <div class="step-content"><h6>Tambah Anggota</h6><small>Isi data anggota</small></div>
                    </div>
                    <div class="alur-step" id="step-3">
                        <div class="step-number">3</div>
                        <div class="step-content"><h6>Konfirmasi</h6><small>Periksa kembali</small></div>
                    </div>
                    <div class="alur-step" id="step-4">
                        <div class="step-number">4</div>
                        <div class="step-content"><h6>Cek Status</h6><small>Lacak status</small></div>
                    </div>
                </div>
                <div class="alur-progress">
                    <div class="progress-bar" id="alurProgress" style="width: 25%;"></div>
                </div>
            </div>
            @endif

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

            @php
                $pendidikanValue = old('perwakilan_jenis_pendidikan', $kelompok->perwakilan_jenis_pendidikan ?? 'smk');
                $isEdit = isset($kelompok);
            @endphp

            <!-- FORM -->
            <form action="{{ $isEdit ? route('user.kelompok.update', $kelompok->id) : route('user.kelompok.store') }}" 
                  method="POST" enctype="multipart/form-data" id="registrationForm" novalidate>
                @csrf
                @if($isEdit)
                    @method('PUT')
                    <input type="hidden" name="kode_pendaftaran" value="{{ $kelompok->kode_pendaftaran }}">
                @else
                    <input type="hidden" name="kode_pendaftaran" id="kode_pendaftaran" value="">
                @endif

                <!-- SECTION 1: Data Kelompok -->
                <div class="section-wrapper">
                    <div class="section-header">
                        <h4 class="section-title">
                            <span class="section-number">1</span>
                            <i class="fas fa-users me-2"></i>Data Kelompok
                        </h4>
                        <div class="section-divider"></div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Nama Kelompok <span class="text-danger">*</span></label>
                            <input type="text" class="form-control-custom" name="nama_kelompok" 
                                   value="{{ old('nama_kelompok', $kelompok->nama_kelompok ?? '') }}" 
                                   placeholder="Contoh: Tim Inovasi Digital" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">Jumlah Anggota <span class="text-danger">*</span></label>
                            <select class="form-control-custom" name="jumlah_anggota" required>
                                <option value="">Pilih</option>
                                @foreach([2,3,4,5] as $jml)
                                <option value="{{ $jml }}" {{ old('jumlah_anggota', $kelompok->jumlah_anggota ?? '') == $jml ? 'selected' : '' }}>{{ $jml }} Orang</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">Asal Institusi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control-custom" name="institusi" 
                                   value="{{ old('institusi', $kelompok->institusi ?? '') }}" 
                                   placeholder="Nama Sekolah/Kampus" required>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: Data Perwakilan -->
                <div class="section-wrapper">
                    <div class="section-header">
                        <h4 class="section-title">
                            <span class="section-number">2</span>
                            <i class="fas fa-user-tie me-2"></i>Data Perwakilan
                        </h4>
                        <div class="section-divider"></div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control-custom" name="perwakilan_nama" 
                                   value="{{ old('perwakilan_nama', $kelompok->perwakilan_nama ?? '') }}" 
                                   placeholder="Nama lengkap perwakilan" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-control-custom" name="perwakilan_jenis_kelamin" required>
                                <option value="">Pilih</option>
                                <option value="L" {{ old('perwakilan_jenis_kelamin', $kelompok->perwakilan_jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('perwakilan_jenis_kelamin', $kelompok->perwakilan_jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Tempat Lahir <span class="text-danger">*</span></label>
                            <input type="text" class="form-control-custom" name="perwakilan_tempat_lahir" 
                                   value="{{ old('perwakilan_tempat_lahir', $kelompok->perwakilan_tempat_lahir ?? '') }}" 
                                   placeholder="Kota kelahiran" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control-custom" name="perwakilan_tanggal_lahir" 
                                   value="{{ old('perwakilan_tanggal_lahir', $kelompok->perwakilan_tanggal_lahir ?? '') }}" 
                                   max="{{ date('Y-m-d', strtotime('-15 years')) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control-custom" name="perwakilan_email" 
                                   value="{{ old('perwakilan_email', $kelompok->perwakilan_email ?? '') }}" 
                                   placeholder="contoh@email.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">WhatsApp <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">+62</span>
                                <input type="tel" class="form-control-custom" name="perwakilan_wa" 
                                       value="{{ old('perwakilan_wa', $kelompok->perwakilan_wa ?? '') }}" 
                                       placeholder="81234567890" oninput="formatWA(this)" maxlength="13" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control-custom" name="perwakilan_alamat" rows="2" 
                                      placeholder="Alamat lengkap" required>{{ old('perwakilan_alamat', $kelompok->perwakilan_alamat ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: Pendidikan -->
                <div class="section-wrapper">
                    <div class="section-header">
                        <h4 class="section-title">
                            <span class="section-number">3</span>
                            <i class="fas fa-user-graduate me-2"></i>Data Pendidikan
                        </h4>
                        <div class="section-divider"></div>
                    </div>

                    <label class="form-label-custom mb-3">Jenis Institusi <span class="text-danger">*</span></label>
                    <div class="education-type-selector">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="education-option {{ $pendidikanValue == 'smk' ? 'selected' : '' }}" 
                                     onclick="selectEducationType('smk')" data-type="smk">
                                    <div class="education-icon"><i class="fas fa-school"></i></div>
                                    <div class="education-content"><h6>SMK</h6></div>
                                    <input type="radio" name="perwakilan_jenis_pendidikan" value="smk" 
                                        {{ $pendidikanValue == 'smk' ? 'checked' : '' }} hidden>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="education-option {{ $pendidikanValue == 'kuliah' ? 'selected' : '' }}" 
                                     onclick="selectEducationType('kuliah')" data-type="kuliah">
                                    <div class="education-icon"><i class="fas fa-university"></i></div>
                                    <div class="education-content"><h6>Perguruan Tinggi</h6></div>
                                    <input type="radio" name="perwakilan_jenis_pendidikan" value="kuliah" 
                                        {{ $pendidikanValue == 'kuliah' ? 'checked' : '' }} hidden>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="smk-fields" class="pendidikan-fields" style="display: {{ $pendidikanValue == 'kuliah' ? 'none' : 'block' }};">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-custom">Nama SMK <span class="text-danger">*</span></label>
                                <input type="text" class="form-control-custom" name="perwakilan_sekolah" 
                                       value="{{ old('perwakilan_sekolah', $kelompok->perwakilan_sekolah ?? '') }}" placeholder="Nama SMK">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Jurusan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control-custom" name="perwakilan_jurusan_smk" 
                                       value="{{ old('perwakilan_jurusan_smk', $kelompok->perwakilan_jurusan_smk ?? '') }}" placeholder="Jurusan">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Kelas</label>
                                <input type="text" class="form-control-custom" name="perwakilan_kelas" 
                                       value="{{ old('perwakilan_kelas', $kelompok->perwakilan_kelas ?? '') }}" placeholder="Kelas">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">NIS</label>
                                <input type="text" class="form-control-custom" name="perwakilan_nis" 
                                       value="{{ old('perwakilan_nis', $kelompok->perwakilan_nis ?? '') }}" placeholder="NIS">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Guru Pembimbing</label>
                                <input type="text" class="form-control-custom" name="perwakilan_guru_pembimbing" 
                                       value="{{ old('perwakilan_guru_pembimbing', $kelompok->perwakilan_guru_pembimbing ?? '') }}" placeholder="Nama guru">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">No. HP Guru</label>
                                <div class="input-group">
                                    <span class="input-group-text">+62</span>
                                    <input type="tel" class="form-control-custom" name="perwakilan_no_hp_guru" 
                                           value="{{ old('perwakilan_no_hp_guru', $kelompok->perwakilan_no_hp_guru ?? '') }}" 
                                           placeholder="81234567890" oninput="formatWA(this)" maxlength="13">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="univ-fields" class="pendidikan-fields" style="display: {{ $pendidikanValue == 'kuliah' ? 'block' : 'none' }};">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-custom">Nama Kampus <span class="text-danger">*</span></label>
                                <input type="text" class="form-control-custom" name="perwakilan_kampus" 
                                       value="{{ old('perwakilan_kampus', $kelompok->perwakilan_kampus ?? '') }}" placeholder="Nama kampus">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Program Studi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control-custom" name="perwakilan_jurusan_univ" 
                                       value="{{ old('perwakilan_jurusan_univ', $kelompok->perwakilan_jurusan_univ ?? '') }}" placeholder="Prodi">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Semester</label>
                                <input type="text" class="form-control-custom" name="perwakilan_semester" 
                                       value="{{ old('perwakilan_semester', $kelompok->perwakilan_semester ?? '') }}" placeholder="Semester">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">NIM</label>
                                <input type="text" class="form-control-custom" name="perwakilan_nim" 
                                       value="{{ old('perwakilan_nim', $kelompok->perwakilan_nim ?? '') }}" placeholder="NIM">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Dosen Pembimbing</label>
                                <input type="text" class="form-control-custom" name="perwakilan_dosen_pembimbing" 
                                       value="{{ old('perwakilan_dosen_pembimbing', $kelompok->perwakilan_dosen_pembimbing ?? '') }}" placeholder="Nama dosen">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">No. HP Dosen</label>
                                <div class="input-group">
                                    <span class="input-group-text">+62</span>
                                    <input type="tel" class="form-control-custom" name="perwakilan_no_hp_dosen" 
                                       value="{{ old('perwakilan_no_hp_dosen', $kelompok->perwakilan_no_hp_dosen ?? '') }}" 
                                       placeholder="81234567890" oninput="formatWA(this)" maxlength="13">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: Motivasi -->
                <div class="section-wrapper">
                    <div class="section-header">
                        <h4 class="section-title">
                            <span class="section-number">4</span>
                            <i class="fas fa-bullseye me-2"></i>Motivasi PKL
                        </h4>
                        <div class="section-divider"></div>
                    </div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label-custom">Alasan PKL di Global Intermedia <span class="text-danger">*</span></label>
                            <textarea class="form-control-custom" name="alasan_pkl_gi" rows="2" 
                                      placeholder="Alasan memilih GI" required>{{ old('alasan_pkl_gi', $kelompok->alasan_pkl_gi ?? '') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Skill ingin dipelajari <span class="text-danger">*</span></label>
                            <textarea class="form-control-custom" name="skill_ingin_dipelajari" rows="2" 
                                      placeholder="Web Dev, Mobile, UI/UX" required>{{ old('skill_ingin_dipelajari', $kelompok->skill_ingin_dipelajari ?? '') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Harapan setelah PKL <span class="text-danger">*</span></label>
                            <textarea class="form-control-custom" name="harapan_setelah_pkl" rows="2" 
                                      placeholder="Harapan kelompok" required>{{ old('harapan_setelah_pkl', $kelompok->harapan_setelah_pkl ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- SECTION 5: Periode -->
                <div class="section-wrapper">
                    <div class="section-header">
                        <h4 class="section-title">
                            <span class="section-number">5</span>
                            <i class="fas fa-calendar-alt me-2"></i>Periode PKL/Magang
                        </h4>
                        <div class="section-divider"></div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control-custom" name="tanggal_mulai" id="tanggal_mulai" 
                                   value="{{ old('tanggal_mulai', $kelompok->tanggal_mulai ?? '') }}" min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control-custom" name="tanggal_selesai" id="tanggal_selesai" 
                                   value="{{ old('tanggal_selesai', $kelompok->tanggal_selesai ?? '') }}" required>
                        </div>
                        <div class="col-12">
                            <div class="duration-card" id="durasiCard" style="display: none;">
                                <div class="duration-icon"><i class="fas fa-clock"></i></div>
                                <div class="duration-content">
                                    <h6>Durasi PKL/Magang</h6>
                                    <p id="durasi_info"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 6: Upload Surat -->
                <div class="section-wrapper">
                    <div class="section-header">
                        <h4 class="section-title">
                            <span class="section-number">6</span>
                            <i class="fas fa-paperclip me-2"></i>Upload Dokumen
                        </h4>
                        <div class="section-divider"></div>
                    </div>

                    <div class="document-info-box mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="info-icon"><i class="fas fa-info-circle"></i></div>
                            <div class="info-text">
                                <strong>Surat Pengantar</strong>
                                <p>PDF, JPG, PNG (Max 10MB)</p>
                            </div>
                        </div>
                    </div>

                    <div class="modern-upload-wrapper">
                        <input type="file" class="modern-file-input" name="upload_surat_pengantar" 
                               id="upload_surat_pengantar" accept=".jpg,.jpeg,.png,.pdf" {{ !$isEdit ? 'required' : '' }}>
                        <label for="upload_surat_pengantar" class="modern-upload-label" id="uploadLabelSurat">
                            <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                            <div class="upload-text">
                                <h6>Klik atau Tarik file ke sini</h6>
                                <p class="text-muted">PDF, JPG, PNG (Max 10MB)</p>
                            </div>
                        </label>
                        <div class="file-info-modern d-none" id="fileInfoSurat">
                            <div class="file-card">
                                <div class="file-card-icon" id="fileCardIconSurat"><i class="fas fa-file-pdf"></i></div>
                                <div class="file-card-details">
                                    <div class="file-name" id="fileNameSurat">-</div>
                                    <div class="file-size" id="fileSizeSurat">0 KB</div>
                                </div>
                                <div class="file-card-actions">
                                    <button type="button" class="file-action-btn preview-btn" onclick="previewFileSurat()"><i class="fas fa-eye"></i></button>
                                    <button type="button" class="file-action-btn delete-btn" onclick="removeFileSurat()"><i class="fas fa-trash-alt"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 7: CV -->
                <div class="section-wrapper" id="section-cv" style="display: {{ $pendidikanValue == 'kuliah' ? 'block' : 'none' }};">
                    <div class="section-header">
                        <h4 class="section-title">
                            <span class="section-number">7</span>
                            <i class="fas fa-file-pdf me-2"></i>Upload CV (Khusus Mahasiswa)
                        </h4>
                        <div class="section-divider"></div>
                    </div>

                    <div class="document-info-box mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="info-icon"><i class="fas fa-info-circle"></i></div>
                            <div class="info-text">
                                <strong>CV / Portofolio</strong>
                                <p>Wajib untuk Perguruan Tinggi. PDF, JPG, PNG (Max 10MB)</p>
                            </div>
                        </div>
                    </div>

                    <div class="modern-upload-wrapper">
                        <input type="file" class="modern-file-input" name="perwakilan_cv" 
                               id="upload_cv" accept=".jpg,.jpeg,.png,.pdf" 
                               {{ $pendidikanValue == 'kuliah' && !$isEdit ? 'required' : '' }}>
                        <label for="upload_cv" class="modern-upload-label" id="uploadLabelCv">
                            <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                            <div class="upload-text">
                                <h6>Klik atau Tarik file CV ke sini</h6>
                                <p class="text-muted">PDF, JPG, PNG (Max 10MB)</p>
                            </div>
                        </label>
                        <div class="file-info-modern d-none" id="fileInfoCv">
                            <div class="file-card">
                                <div class="file-card-icon" id="fileCardIconCv"><i class="fas fa-file-pdf"></i></div>
                                <div class="file-card-details">
                                    <div class="file-name" id="fileNameCv">-</div>
                                    <div class="file-size" id="fileSizeCv">0 KB</div>
                                </div>
                                <div class="file-card-actions">
                                    <button type="button" class="file-action-btn preview-btn" onclick="previewFileCv()"><i class="fas fa-eye"></i></button>
                                    <button type="button" class="file-action-btn delete-btn" onclick="removeFileCv()"><i class="fas fa-trash-alt"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(!$isEdit)
                <div class="section-wrapper">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="terms" name="terms" value="1" required>
                        <label class="form-check-label" for="terms" style="font-size: 0.8rem;">
                            <strong>Saya menyatakan bahwa:</strong><br>
                            1. Data yang diberikan benar dan dapat dipertanggungjawabkan<br>
                            2. Saya perwakilan sah dari kelompok yang terdaftar<br>
                            3. Seluruh anggota siap mengikuti proses PKL/Magang<br>
                            4. Kami akan mematuhi peraturan yang berlaku
                        </label>
                    </div>
                </div>
                @endif

                <!-- BUTTONS - NORMAL FLOW (DI DALAM FORM, DI AKHIR) -->
                <div class="form-actions">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ $isEdit ? route('user.kelompok.tambah-peserta', $kelompok->id) : route('user.pilih-tipe') }}" class="btn-back">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn-submit" id="btnSubmit">
                            <i class="fas {{ $isEdit ? 'fa-save' : 'fa-paper-plane' }}"></i>
                            {{ $isEdit ? 'Simpan Perubahan' : 'Simpan & Lanjut' }}
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

@push('scripts')
<script>
let isSubmitting = false;
let selectedFileSurat = null;
let selectedFileCv = null;

function formatWA(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.startsWith('0')) value = value.substring(1);
    if (value.startsWith('62')) value = value.substring(2);
    input.value = value.substring(0, 13);
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024, sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function selectEducationType(type) {
    document.querySelectorAll('.education-option').forEach(opt => opt.classList.remove('selected'));
    const opt = document.querySelector(`.education-option[data-type="${type}"]`);
    if (opt) opt.classList.add('selected');
    const radio = document.querySelector(`input[name="perwakilan_jenis_pendidikan"][value="${type}"]`);
    if (radio) radio.checked = true;
    document.getElementById('smk-fields').style.display = type === 'smk' ? 'block' : 'none';
    document.getElementById('univ-fields').style.display = type === 'kuliah' ? 'block' : 'none';
    const cvSection = document.getElementById('section-cv');
    const cvInput = document.getElementById('upload_cv');
    if (type === 'smk') {
        if (cvSection) cvSection.style.display = 'none';
        if (cvInput) cvInput.removeAttribute('required');
    } else {
        if (cvSection) cvSection.style.display = 'block';
        @if(!$isEdit)
        if (cvInput) cvInput.setAttribute('required', 'required');
        @endif
    }
}

function hitungDurasi() {
    const m = document.getElementById('tanggal_mulai'), s = document.getElementById('tanggal_selesai');
    const c = document.getElementById('durasiCard'), i = document.getElementById('durasi_info');
    if (!m || !s || !c || !i) return;
    if (m.value && s.value) {
        const start = new Date(m.value), end = new Date(s.value);
        if (end < start) { c.style.display = 'flex'; i.innerHTML = '<span class="text-danger">Tanggal selesai tidak boleh sebelum mulai</span>'; return; }
        const days = Math.ceil((end - start) / 86400000) + 1;
        i.textContent = days + ' hari';
        c.style.display = 'flex';
    } else { c.style.display = 'none'; }
}

function handleFile(input, labelId, infoId, nameId, sizeId, iconId, fileVar) {
    const file = input.files[0];
    if (!file) return;
    if (fileVar === 'surat') selectedFileSurat = file;
    else selectedFileCv = file;
    document.getElementById(labelId).classList.add('d-none');
    document.getElementById(infoId).classList.remove('d-none');
    document.getElementById(nameId).textContent = file.name;
    document.getElementById(sizeId).textContent = formatFileSize(file.size);
    const icon = document.getElementById(iconId);
    if (file.type.startsWith('image/')) icon.innerHTML = '<i class="fas fa-file-image"></i>';
    else if (file.type === 'application/pdf') icon.innerHTML = '<i class="fas fa-file-pdf"></i>';
    else icon.innerHTML = '<i class="fas fa-file-alt"></i>';
}

function removeFile(type) {
    const input = document.getElementById('upload_' + (type === 'cv' ? 'cv' : 'surat_pengantar'));
    if (input) input.value = '';
    document.getElementById('uploadLabel' + (type === 'cv' ? 'Cv' : 'Surat')).classList.remove('d-none');
    document.getElementById('fileInfo' + (type === 'cv' ? 'Cv' : 'Surat')).classList.add('d-none');
    if (type === 'surat') selectedFileSurat = null;
    else selectedFileCv = null;
}

document.addEventListener('DOMContentLoaded', function() {
    const pv = '{{ $pendidikanValue }}';
    if (pv) selectEducationType(pv);

    @if(!$isEdit)
    fetch('{{ route("user.generate.kode.kelompok") }}', {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json'}
    })
    .then(r => r.json())
    .then(d => { if (d.success) document.getElementById('kode_pendaftaran').value = d.kode; })
    .catch(() => {
        const dt = new Date().toISOString().slice(0,10).replace(/-/g,'');
        const rd = Math.random().toString(36).substring(2,7).toUpperCase();
        document.getElementById('kode_pendaftaran').value = 'KLP-'+dt+'-'+rd;
    });
    @endif

    const tm = document.getElementById('tanggal_mulai'), ts = document.getElementById('tanggal_selesai');
    if (tm) { tm.addEventListener('change', () => { if (ts) ts.min = tm.value; hitungDurasi(); }); }
    if (ts) ts.addEventListener('change', hitungDurasi);
    if (tm?.value && ts?.value) hitungDurasi();

    document.getElementById('upload_surat_pengantar').addEventListener('change', function() {
        if (this.files[0]) handleFile(this, 'uploadLabelSurat', 'fileInfoSurat', 'fileNameSurat', 'fileSizeSurat', 'fileCardIconSurat', 'surat');
    });
    document.getElementById('upload_cv').addEventListener('change', function() {
        if (this.files[0]) handleFile(this, 'uploadLabelCv', 'fileInfoCv', 'fileNameCv', 'fileSizeCv', 'fileCardIconCv', 'cv');
    });

    document.getElementById('registrationForm').addEventListener('submit', function(e) {
        const jenis = document.querySelector('input[name="perwakilan_jenis_pendidikan"]:checked')?.value;
        @if(!$isEdit)
        if (!jenis) { e.preventDefault(); alert('Pilih jenis institusi!'); return; }
        if (jenis === 'kuliah' && !document.getElementById('upload_cv').files[0]) { e.preventDefault(); alert('CV wajib untuk Perguruan Tinggi!'); return; }
        if (!document.getElementById('upload_surat_pengantar').files[0]) { e.preventDefault(); alert('Upload surat pengantar!'); return; }
        if (!document.getElementById('terms').checked) { e.preventDefault(); alert('Centang pernyataan persetujuan!'); return; }
        @endif
        if (isSubmitting) { e.preventDefault(); return; }
        isSubmitting = true;
        const btn = document.getElementById('btnSubmit');
        if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...'; }
    });
});

window.selectEducationType = selectEducationType;
window.formatWA = formatWA;
window.removeFileSurat = () => removeFile('surat');
window.previewFileSurat = () => { if (selectedFileSurat) window.open(URL.createObjectURL(selectedFileSurat)); };
window.removeFileCv = () => removeFile('cv');
window.previewFileCv = () => { if (selectedFileCv) window.open(URL.createObjectURL(selectedFileCv)); };
</script>
@endpush
@endsection