@extends('layouts.app')

@section('title', 'Form Pendaftaran PKL/Magang - Global Intermedia')
@section('content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-4">
                <h1 class="hero-title mb-2" style="color: #dc3545;">
                    <i class="fas fa-file-alt me-2"></i>Formulir Pendaftaran PKL/MAGANG
                </h1>
                <p class="text-muted mb-3">
                    Isi data diri dengan lengkap dan benar. Pastikan semua informasi valid.
                </p>
                
                <div class="alur-pendaftaran mb-4">
                    <div class="alur-steps">
                        <div class="alur-step active" id="step-1">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h6>Isi Form</h6>
                                <small>Lengkapi data diri</small>
                            </div>
                        </div>
                        <div class="alur-step" id="step-2">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h6>Preview Kode</h6>
                                <small>Lihat & salin kode</small>
                            </div>
                        </div>
                        <div class="alur-step" id="step-3">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h6>Konfirmasi</h6>
                                <small>Periksa kembali</small>
                            </div>
                        </div>
                        <div class="alur-step" id="step-4">
                            <div class="step-number">4</div>
                            <div class="step-content">
                                <h6>Cek Status</h6>
                                <small>Lacak status pendaftaran Anda</small>
                            </div>
                        </div>
                    </div>
                    <div class="alur-progress">
                        <div class="progress-bar" id="alurProgress" style="width: 25%;"></div>
                    </div>
                </div>
            </div>

            @if(session('kode_pendaftaran'))
            <div class="kode-card mb-4">
                <div class="kode-card-header">
                    <div class="d-flex align-items-center gap-2">
                        <div class="success-dot"></div>
                        <span class="fw-semibold">Pendaftaran Berhasil!</span>
                    </div>
                    <span class="badge-permanent">
                        <i class="fas fa-shield-alt me-1"></i>KODE PERMANENT
                    </span>
                </div>
                <div class="kode-card-body">
                    <div class="row align-items-center g-3">
                        <div class="col-md-7">
                            <div class="kode-display">
                                <span class="kode-label">Kode Pendaftaran</span>
                                <div class="kode-value-wrapper">
                                    <i class="fas fa-key text-danger me-2"></i>
                                    <span class="kode-text" id="permanentKode">{{ session('kode_pendaftaran') }}</span>
                                </div>
                                <div class="kode-note">
                                    <i class="fas fa-info-circle text-danger me-1"></i>
                                    <small>Simpan kode ini untuk cek status pendaftaran</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <button class="btn-copy-kode" onclick="copyPermanentKode()" type="button">
                                <i class="fas fa-copy me-2"></i>Salin Kode
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-3 fs-4"></i>
                        <div>
                            <h5 class="mb-1">Pendaftaran Berhasil!</h5>
                            <p class="mb-0">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-3 fs-4"></i>
                        <div>
                            <h5 class="mb-1">Terjadi Kesalahan!</h5>
                            <p class="mb-0">{{ session('error') }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-exclamation-triangle me-3 fs-4"></i>
                        <div>
                            <h5 class="mb-1">Terjadi Kesalahan Validasi:</h5>
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="floating-notification d-none" id="floatingNotification">
                <div class="notification-content">
                    <i class="fas fa-exclamation-circle"></i>
                    <span id="notificationMessage"></span>
                </div>
            </div>

            <div class="card-custom">
                <div class="card-body p-3 p-md-4">
                    <form action="{{ route('user.register.individu.store') }}" method="POST" enctype="multipart/form-data" id="registrationForm">
                        @csrf
                        
                        <input type="hidden" name="kode_pendaftaran" id="kode_pendaftaran" value="">
                        <input type="hidden" name="tanggal_lahir" id="tanggal_lahir_hidden">

                        <!-- SECTION 1: Data Diri -->
                        <div class="section-wrapper mb-4" id="section-1">
                            <div class="section-header mb-3">
                                <h4 class="section-title mb-2">
                                    <span class="section-number">1</span>
                                    <i class="fas fa-user-circle me-2"></i>Data Identitas Diri
                                </h4>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label-custom">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control-custom @error('nama_lengkap') is-invalid @enderror" 
                                            name="nama_lengkap" id="nama_lengkap" 
                                            value="{{ old('nama_lengkap') }}" 
                                            placeholder="Nama lengkap" required>
                                        @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label-custom">Jenis Kelamin <span class="text-danger">*</span></label>
                                        <select class="form-control-custom @error('jenis_kelamin') is-invalid @enderror" name="jenis_kelamin" id="jenis_kelamin" required>
                                            <option value="">Pilih</option>
                                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label-custom">Tempat Lahir <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control-custom @error('tempat_lahir') is-invalid @enderror" 
                                            name="tempat_lahir" id="tempat_lahir" 
                                            value="{{ old('tempat_lahir') }}" 
                                            placeholder="Kota lahir" required>
                                        @error('tempat_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label-custom">Tanggal Lahir <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control-custom desktop-date @error('tanggal_lahir') is-invalid @enderror" 
                                            name="tanggal_lahir_desktop" id="tanggal_lahir_desktop" 
                                            value="{{ old('tanggal_lahir') }}" 
                                            max="{{ date('Y-m-d', strtotime('-15 years')) }}">
                                        <div class="mobile-date-group" style="display: none;">
                                            <div class="row g-2">
                                                <div class="col-4">
                                                    <select class="form-control-custom" id="tgl_lahir_hari">
                                                        <option value="">Tanggal</option>
                                                        @for($i=1; $i<=31; $i++)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="col-4">
                                                    <select class="form-control-custom" id="tgl_lahir_bulan">
                                                        <option value="">Bulan</option>
                                                        @for($i=1; $i<=12; $i++)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="col-4">
                                                    <select class="form-control-custom" id="tgl_lahir_tahun">
                                                        <option value="">Tahun</option>
                                                        @for($i=date('Y'); $i>=date('Y')-50; $i--)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label-custom">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control-custom @error('email') is-invalid @enderror" 
                                            name="email" id="email" 
                                            value="{{ old('email') }}" 
                                            placeholder="email@contoh.com" required>
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label-custom">WhatsApp <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">+62</span>
                                            <input type="text" class="form-control-custom @error('no_whatsapp') is-invalid @enderror" 
                                                name="no_whatsapp" id="no_whatsapp" 
                                                value="{{ old('no_whatsapp') }}" 
                                                placeholder="81234567890"
                                                oninput="formatWA(this)" required>
                                        </div>
                                        @error('no_whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label-custom">Alamat Lengkap <span class="text-danger">*</span></label>
                                        <textarea class="form-control-custom @error('alamat_lengkap') is-invalid @enderror" 
                                                name="alamat_lengkap" id="alamat_lengkap" 
                                                rows="2" placeholder="Alamat lengkap" required>{{ old('alamat_lengkap') }}</textarea>
                                        @error('alamat_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: Data Pendidikan -->
                        <div class="section-wrapper mb-4" id="section-2">
                            <div class="section-header mb-3">
                                <h4 class="section-title mb-2">
                                    <span class="section-number">2</span>
                                    <i class="fas fa-user-graduate me-2"></i>Data Pendidikan
                                </h4>
                                <div class="section-divider"></div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="form-label-custom mb-3">Jenis Institusi <span class="text-danger">*</span></label>
                                <div class="education-type-selector">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="education-option {{ old('jenis_pendidikan', 'smk') == 'smk' ? 'selected' : '' }}" 
                                                onclick="selectEducationType('smk')" data-type="smk">
                                                <div class="education-icon">
                                                    <i class="fas fa-school"></i>
                                                </div>
                                                <div class="education-content">
                                                    <h6>SMK</h6>
                                                </div>
                                                <input type="radio" name="jenis_pendidikan" id="pendidikan_smk" value="smk" 
                                                    {{ old('jenis_pendidikan', 'smk') == 'smk' ? 'checked' : '' }} hidden>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="education-option {{ old('jenis_pendidikan') == 'kuliah' ? 'selected' : '' }}" 
                                                onclick="selectEducationType('kuliah')" data-type="kuliah">
                                                <div class="education-icon">
                                                    <i class="fas fa-university"></i>
                                                </div>
                                                <div class="education-content">
                                                    <h6>Perguruan Tinggi</h6>
                                                </div>
                                                <input type="radio" name="jenis_pendidikan" id="pendidikan_kuliah" value="kuliah" 
                                                    {{ old('jenis_pendidikan') == 'kuliah' ? 'checked' : '' }} hidden>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @error('jenis_pendidikan')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>

                            <div id="smk-fields" class="pendidikan-fields {{ old('jenis_pendidikan') == 'kuliah' ? 'd-none' : '' }}">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Nama SMK <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control-custom @error('sekolah') is-invalid @enderror" 
                                            name="sekolah" id="sekolah" value="{{ old('sekolah') }}" placeholder="Nama SMK">
                                        @error('sekolah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Jurusan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control-custom @error('jurusan_smk') is-invalid @enderror" 
                                            name="jurusan_smk" id="jurusan_smk" value="{{ old('jurusan_smk') }}" placeholder="Jurusan">
                                        @error('jurusan_smk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label-custom">Kelas <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control-custom @error('kelas') is-invalid @enderror" 
                                            name="kelas" id="kelas" value="{{ old('kelas') }}" placeholder="Kelas">
                                        @error('kelas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label-custom">NIS <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control-custom @error('nis') is-invalid @enderror" 
                                            name="nis" id="nis" value="{{ old('nis') }}" placeholder="NIS">
                                        @error('nis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label-custom">Guru Pembimbing <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control-custom @error('guru_pembimbing') is-invalid @enderror" 
                                            name="guru_pembimbing" id="guru_pembimbing" value="{{ old('guru_pembimbing') }}" placeholder="Nama guru">
                                        @error('guru_pembimbing')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-custom">No. HP Guru <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">+62</span>
                                            <input type="text" class="form-control-custom @error('no_hp_guru') is-invalid @enderror" 
                                                name="no_hp_guru" id="no_hp_guru" value="{{ old('no_hp_guru') }}" 
                                                placeholder="81234567890" oninput="formatWA(this)">
                                        </div>
                                        @error('no_hp_guru')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div id="univ-fields" class="pendidikan-fields {{ old('jenis_pendidikan') == 'kuliah' ? '' : 'd-none' }}">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Nama Kampus <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control-custom @error('kuliah') is-invalid @enderror" 
                                            name="kuliah" id="kuliah" value="{{ old('kuliah') }}" placeholder="Nama kampus">
                                        @error('kuliah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Program Studi <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control-custom @error('jurusan_univ') is-invalid @enderror" 
                                            name="jurusan_univ" id="jurusan_univ" value="{{ old('jurusan_univ') }}" placeholder="Prodi">
                                        @error('jurusan_univ')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label-custom">Semester <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control-custom @error('semester') is-invalid @enderror" 
                                            name="semester" id="semester" value="{{ old('semester') }}" placeholder="Semester">
                                        @error('semester')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label-custom">NIM <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control-custom @error('nim') is-invalid @enderror" 
                                            name="nim" id="nim" value="{{ old('nim') }}" placeholder="NIM">
                                        @error('nim')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label-custom">Dosen Pembimbing <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control-custom @error('dosen_pembimbing') is-invalid @enderror" 
                                            name="dosen_pembimbing" id="dosen_pembimbing" value="{{ old('dosen_pembimbing') }}" placeholder="Nama dosen">
                                        @error('dosen_pembimbing')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-custom">No. HP Dosen <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">+62</span>
                                            <input type="text" class="form-control-custom @error('no_hp_dosen') is-invalid @enderror" 
                                                name="no_hp_dosen" id="no_hp_dosen" value="{{ old('no_hp_dosen') }}" 
                                                placeholder="81234567890" oninput="formatWA(this)">
                                        </div>
                                        @error('no_hp_dosen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3: Motivasi PKL -->
                        <div class="section-wrapper mb-4" id="section-3">
                            <div class="section-header mb-3">
                                <h4 class="section-title mb-2">
                                    <span class="section-number">3</span>
                                    <i class="fas fa-bullseye me-2"></i>Motivasi PKL
                                </h4>
                                <div class="section-divider"></div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label-custom">Alasan PKL di Global Intermedia <span class="text-danger">*</span></label>
                                    <textarea class="form-control-custom @error('alasan_pkl_gi') is-invalid @enderror" 
                                        name="alasan_pkl_gi" id="alasan_pkl_gi" 
                                        rows="2" placeholder="Alasan Anda..." required>{{ old('alasan_pkl_gi') }}</textarea>
                                    @error('alasan_pkl_gi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">Skill ingin dipelajari <span class="text-danger">*</span></label>
                                    <textarea class="form-control-custom @error('skill_ingin_dipelajari') is-invalid @enderror" 
                                        name="skill_ingin_dipelajari" id="skill_ingin_dipelajari" 
                                        rows="2" placeholder="Skill yang ingin dipelajari" required>{{ old('skill_ingin_dipelajari') }}</textarea>
                                    @error('skill_ingin_dipelajari')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">Harapan setelah PKL <span class="text-danger">*</span></label>
                                    <textarea class="form-control-custom @error('harapan_setelah_pkl') is-invalid @enderror" 
                                        name="harapan_setelah_pkl" id="harapan_setelah_pkl" 
                                        rows="2" placeholder="Harapan Anda" required>{{ old('harapan_setelah_pkl') }}</textarea>
                                    @error('harapan_setelah_pkl')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 4: Periode PKL -->
                        <div class="section-wrapper mb-4" id="section-4">
                            <div class="section-header mb-3">
                                <h4 class="section-title mb-2">
                                    <span class="section-number">4</span>
                                    <i class="fas fa-calendar-alt me-2"></i>Periode PKL/Magang
                                </h4>
                                <div class="section-divider"></div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label-custom">Tanggal Mulai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control-custom @error('tanggal_mulai') is-invalid @enderror" 
                                        name="tanggal_mulai" id="tanggal_mulai" 
                                        value="{{ old('tanggal_mulai') }}" min="{{ date('Y-m-d') }}" required>
                                    @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">Tanggal Selesai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control-custom @error('tanggal_selesai') is-invalid @enderror" 
                                        name="tanggal_selesai" id="tanggal_selesai" 
                                        value="{{ old('tanggal_selesai') }}" required>
                                    @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <div class="duration-card" id="durasiCard" style="display: none;">
                                        <div class="duration-icon">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div class="duration-content">
                                            <h6 class="mb-1">Durasi PKL/Magang</h6>
                                            <p class="mb-0" id="durasi_info"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 5: Upload Surat Pengantar -->
                        <div class="section-wrapper mb-4" id="section-5">
                            <div class="section-header mb-3">
                                <h4 class="section-title mb-2">
                                    <span class="section-number">5</span>
                                    <i class="fas fa-paperclip me-2"></i>Upload Dokumen
                                </h4>
                                <div class="section-divider"></div>
                            </div>
                            
                            <div class="document-info-box mb-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="info-icon">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <div class="info-text">
                                        <strong>Persyaratan Dokumen</strong>
                                        <p class="mb-0 small">Upload Surat Pengantar dari sekolah/kampus (format PDF, JPG, atau PNG, maksimal 10MB)</p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label-custom mb-2">Surat Pengantar <span class="text-danger">*</span></label>
                                
                                <div class="modern-upload-wrapper">
                                    <input type="file" 
                                           class="modern-file-input @error('upload_surat_pengantar') is-invalid @enderror" 
                                           name="upload_surat_pengantar" 
                                           id="upload_surat_pengantar" 
                                           accept=".jpg,.jpeg,.png,.pdf" 
                                           required>
                                    
                                    <label for="upload_surat_pengantar" class="modern-upload-label" id="uploadLabelSurat">
                                        <div class="upload-icon">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                        </div>
                                        <div class="upload-text">
                                            <h6 class="mb-1">Klik atau Tarik file ke sini</h6>
                                            <p class="mb-0 small text-muted">PDF, JPG, PNG (Max 10MB)</p>
                                        </div>
                                    </label>
                                    
                                    <div class="file-info-modern d-none" id="fileInfoSurat">
                                        <div class="file-card">
                                            <div class="file-card-icon" id="fileCardIconSurat">
                                                <i class="fas fa-file-pdf"></i>
                                            </div>
                                            <div class="file-card-details">
                                                <div class="file-name" id="fileNameSurat">nama_file.pdf</div>
                                                <div class="file-size" id="fileSizeSurat">0 KB</div>
                                            </div>
                                            <div class="file-card-actions">
                                                <button type="button" class="file-action-btn preview-btn" onclick="previewFileSurat()" title="Preview">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="file-action-btn delete-btn" onclick="removeFileSurat()" title="Hapus">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                @error('upload_surat_pengantar')
                                    <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- SECTION 6: Upload CV (KHUSUS PERGURUAN TINGGI) -->
                        <div class="section-wrapper mb-4 d-none" id="section-cv">
                            <div class="section-header mb-3">
                                <h4 class="section-title mb-2">
                                    <span class="section-number">6</span>
                                    <i class="fas fa-file-pdf me-2"></i>Upload CV (Khusus Mahasiswa)
                                </h4>
                                <div class="section-divider"></div>
                            </div>
                            
                            <div class="document-info-box mb-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="info-icon">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <div class="info-text">
                                        <strong>Upload CV</strong>
                                        <p class="mb-0 small">
                                            Khusus untuk pendaftar <strong>Perguruan Tinggi/Kuliah</strong>. 
                                            Upload CV atau Curriculum Vitae Anda (format PDF, JPG, PNG, maksimal 10MB).
                                            <br><span class="text-muted">*Opsional, tapi sangat disarankan.</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label-custom mb-2">CV / Curriculum Vitae <span class="text-muted">(Opsional)</span></label>
                                
                                <div class="modern-upload-wrapper">
                                    <input type="file" 
                                           class="modern-file-input @error('upload_cv') is-invalid @enderror" 
                                           name="upload_cv" 
                                           id="upload_cv" 
                                           accept=".jpg,.jpeg,.png,.pdf">
                                    
                                    <label for="upload_cv" class="modern-upload-label" id="uploadLabelCv">
                                        <div class="upload-icon">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                        </div>
                                        <div class="upload-text">
                                            <h6 class="mb-1">Klik atau Tarik file CV ke sini</h6>
                                            <p class="mb-0 small text-muted">PDF, JPG, PNG (Max 10MB) - Opsional</p>
                                        </div>
                                    </label>
                                    
                                    <div class="file-info-modern d-none" id="fileInfoCv">
                                        <div class="file-card">
                                            <div class="file-card-icon" id="fileCardIconCv">
                                                <i class="fas fa-file-pdf"></i>
                                            </div>
                                            <div class="file-card-details">
                                                <div class="file-name" id="fileNameCv">nama_file.pdf</div>
                                                <div class="file-size" id="fileSizeCv">0 KB</div>
                                            </div>
                                            <div class="file-card-actions">
                                                <button type="button" class="file-action-btn preview-btn" onclick="previewFileCv()" title="Preview">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="file-action-btn delete-btn" onclick="removeFileCv()" title="Hapus">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                @error('upload_cv')
                                    <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Terms -->
                        <div class="card terms-card mb-4" id="section-terms">
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" name="terms" id="terms" value="1" {{ old('terms') ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="terms">
                                        <strong>Dengan ini saya menyatakan bahwa:</strong><br>
                                        1. Data yang saya berikan adalah benar dan dapat dipertanggungjawabkan<br>
                                        2. Saya siap mengikuti seluruh proses PKL/Magang di Global Intermedia<br>
                                        3. Saya akan mematuhi peraturan dan tata tertib yang berlaku
                                    </label>
                                    @error('terms')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- Info Box -->
                        <div class="alert alert-info bg-light border-0 rounded-3 mb-4">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-info-circle me-3 fa-lg mt-1" style="color: #17a2b8;"></i>
                                <div>
                                    <h6 class="fw-bold mb-2">Informasi Penting:</h6>
                                    <ul class="mb-0 small">
                                        <li>Status pendaftaran: <span class="badge bg-warning">PENDING</span></li>
                                        <li>Admin akan menghubungi via WhatsApp untuk konfirmasi</li>
                                        <li>Pastikan nomor WhatsApp aktif dan dapat dihubungi</li>
                                        <li><strong class="text-danger">Kode preview akan muncul SEBELUM kirim, SALIN dulu!</strong></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="form-actions">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('user.pilih-tipe') }}" class="btn btn-outline-custom">
                                    <i class="fas fa-arrow-left me-1"></i>Kembali
                                </a>
                                <button type="button" class="btn btn-primary-custom px-4 py-2" onclick="validateAndShowPreview()" id="btnSubmit">
                                    <i class="fas fa-eye me-2"></i>Preview & Kirim
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview File -->
<div class="modal fade" id="filePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title"><i class="fas fa-file me-2"></i>Preview Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-0" style="min-height: 500px; background: #525659;">
                <div id="modalPreviewContent" class="d-flex align-items-center justify-content-center h-100 w-100"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview Kode -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content preview-modal-content">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="previewModalLabel">
                    <i class="fas fa-key text-danger me-2"></i>Kode Pendaftaran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-2">
                <p class="text-muted small mb-3">Kode ini hanya muncul sekali. <strong class="text-danger">Salin sekarang!</strong></p>
                <div class="preview-kode-box" id="previewKodeDisplay">
                    <i class="fas fa-spinner fa-spin"></i> <span id="generatingText">Generating...</span>
                </div>
                <button class="btn btn-copy-preview mt-3" onclick="copyPreviewKode()" id="btnCopyKode" disabled>
                    <i class="fas fa-copy me-2"></i>Salin Kode
                </button>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger btn-sm px-3" onclick="submitForm()" id="btnConfirmSubmit" disabled>
                    <i class="fas fa-paper-plane me-1"></i>Kirim
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    :root {
        --primary-red: #dc3545;
        --primary-red-light: #fff5f5;
        --primary-red-dark: #c82333;
        --border-color: #dee2e6;
        --shadow-sm: 0 2px 4px rgba(0,0,0,0.1);
        --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
        --shadow-lg: 0 10px 25px rgba(0,0,0,0.1);
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
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
        position: relative;
    }

    .alur-step {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
        position: relative;
        opacity: 0.5;
        transition: all 0.3s ease;
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

    .kode-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 15px rgba(220, 53, 69, 0.1);
        border: 1px solid rgba(220, 53, 69, 0.15);
        animation: fadeInUp 0.5s ease;
    }

    .kode-card-header {
        background: #dc3545;
        padding: 12px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: white;
        font-size: 0.9rem;
    }

    .success-dot {
        width: 10px;
        height: 10px;
        background: #28a745;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 8px rgba(40, 167, 69, 0.6);
    }

    .badge-permanent {
        background: rgba(255, 255, 255, 0.2);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .kode-card-body { padding: 20px; }

    .kode-display {
        background: #fdf2f2;
        border-radius: 10px;
        padding: 16px;
        border: 1px dashed #f5c6cb;
    }

    .kode-label {
        display: block;
        font-size: 0.7rem;
        font-weight: 600;
        color: #dc3545;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 8px;
    }

    .kode-value-wrapper {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .kode-text {
        font-size: 1.5rem;
        font-weight: 700;
        color: #212529;
        letter-spacing: 2px;
        font-family: 'Courier New', monospace;
        user-select: all;
    }

    .btn-copy-kode {
        width: 100%;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 12px 20px;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-copy-kode:hover {
        background: #c82333;
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
    }

    .preview-modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .preview-kode-box {
        background: #f8f9fa;
        border: 2px dashed #dc3545;
        border-radius: 10px;
        padding: 20px;
        font-size: 1.8rem;
        font-weight: 700;
        color: #212529;
        letter-spacing: 3px;
        font-family: 'Courier New', monospace;
        user-select: all;
        position: relative;
        word-break: break-all;
    }

    .btn-copy-preview {
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 25px;
        padding: 10px 30px;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s ease;
        width: 100%;
    }

    .btn-copy-preview:hover {
        background: #c82333;
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.2);
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .floating-notification {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999;
        background: white;
        border-radius: 60px;
        box-shadow: var(--shadow-lg);
        border-left: 4px solid var(--primary-red);
        animation: slideIn 0.3s ease;
        max-width: 90%;
        min-width: 280px;
    }

    .notification-content {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 24px;
        background: white;
        border-radius: 60px;
    }

    .notification-content i { color: var(--primary-red); font-size: 1.5rem; }

    @keyframes slideIn {
        from { opacity: 0; transform: translate(-50%, -40%); }
        to { opacity: 1; transform: translate(-50%, -50%); }
    }

    .section-wrapper {
        background: #fff;
        border-radius: var(--radius-md);
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        margin-bottom: 1.5rem;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .section-wrapper.error-highlight {
        border-color: var(--primary-red);
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        animation: shake 0.5s ease;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }

    .section-title {
        color: var(--primary-red);
        font-weight: 600;
        font-size: 1.25rem;
        display: flex;
        align-items: center;
    }

    .section-number {
        width: 2rem;
        height: 2rem;
        background: var(--primary-red);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
        font-size: 1rem;
    }

    .section-divider {
        height: 3px;
        background: linear-gradient(90deg, var(--primary-red) 0%, rgba(220, 53, 69, 0.2) 100%);
        border-radius: 3px;
        margin-top: 5px;
    }

    .form-group { margin-bottom: 1rem; }

    .form-label-custom {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        display: block;
        font-size: 0.875rem;
    }

    .form-control-custom {
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        padding: 0.6rem 0.75rem;
        width: 100%;
        background: #fff;
        transition: all 0.2s ease;
    }

    .form-control-custom:focus {
        border-color: var(--primary-red);
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.1);
    }

    .form-control-custom.error-field {
        border-color: var(--primary-red);
        background-color: var(--primary-red-light);
    }

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
    }

    .education-option:hover { border-color: var(--primary-red); background: var(--primary-red-light); }
    .education-option.selected { border-color: var(--primary-red); background: var(--primary-red-light); }
    .education-icon { font-size: 1.8rem; color: var(--primary-red); margin: 0; }
    .education-content h6 { margin: 0; font-size: 1rem; }

    .pendidikan-fields {
        background: #f8f9fa;
        border-radius: var(--radius-md);
        padding: 1.5rem;
        margin-top: 1.5rem;
        border: 1px solid var(--border-color);
    }

    .duration-card {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: linear-gradient(135deg, #e3f2fd, #bbdefb);
        border-radius: var(--radius-md);
        border: 1px solid #bbdefb;
    }

    .duration-icon { font-size: 1.5rem; color: #2196f3; margin-right: 1rem; }

    .terms-card {
        border: 2px solid var(--primary-red);
        background: var(--primary-red-light);
        border-radius: var(--radius-md);
    }

    .form-actions {
        background: white;
        padding: 1rem;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border-color);
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .btn-primary-custom {
        background: var(--primary-red);
        border: none;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-primary-custom:hover {
        background: #c82333;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-outline-custom {
        border: 2px solid var(--border-color);
        color: #495057;
        padding: 0.75rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-outline-custom:hover {
        background: #f8f9fa;
        color: var(--primary-red);
        border-color: var(--primary-red);
    }

    .document-info-box {
        background: #e8f4fd;
        border-radius: var(--radius-md);
        padding: 1rem;
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
        font-size: 1.2rem;
    }

    .document-info-box .info-text { flex: 1; }
    .document-info-box .info-text strong { color: var(--primary-red); display: block; margin-bottom: 4px; }

    .modern-upload-wrapper { position: relative; width: 100%; }
    .modern-file-input { position: absolute; opacity: 0; width: 0.1px; height: 0.1px; overflow: hidden; z-index: -1; }

    .modern-upload-label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        padding: 2rem;
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
        transform: translateY(-2px);
    }

    .upload-icon {
        width: 50px;
        height: 50px;
        background: var(--primary-red-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-red);
        font-size: 1.5rem;
        transition: all 0.3s ease;
    }

    .modern-upload-label:hover .upload-icon {
        transform: scale(1.1);
        background: var(--primary-red);
        color: white;
    }

    .upload-text h6 { margin: 0; font-weight: 600; color: #495057; }

    .file-info-modern { margin-top: 1rem; animation: fadeInUp 0.3s ease; }

    .file-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        transition: all 0.2s ease;
    }

    .file-card:hover { box-shadow: var(--shadow-md); border-color: var(--primary-red); }

    .file-card-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--primary-red-light) 0%, #fff 100%);
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: var(--primary-red);
    }

    .file-card-details { flex: 1; }
    .file-name { font-weight: 600; color: #212529; font-size: 0.9rem; word-break: break-all; }
    .file-size { font-size: 0.7rem; color: #6c757d; margin-top: 4px; }

    .file-card-actions { display: flex; gap: 0.5rem; }

    .file-action-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background: transparent;
    }

    .preview-btn { background: #e3f2fd; color: #1976d2; }
    .preview-btn:hover { background: #bbdefb; transform: scale(1.05); }
    .delete-btn { background: #ffebee; color: #dc3545; }
    .delete-btn:hover { background: #ffcdd2; transform: scale(1.05); }

    .preview-image { max-width: 100%; max-height: 70vh; object-fit: contain; }
    .preview-pdf { width: 100%; height: 70vh; border: none; }

    @media (max-width: 768px) {
        .alur-step { flex-direction: column; text-align: center; gap: 5px; }
        .step-content { text-align: center; }
        .alur-step:not(:last-child)::after { top: 20px; right: -20%; width: 40%; }
        .form-actions .d-flex { flex-direction: row; gap: 0.5rem; }
        .form-actions .btn { flex: 1; text-align: center; justify-content: center; padding: 0.75rem 1rem; font-size: 0.9rem; }
        .hero-title { font-size: 1.5rem; }
        .kode-card-header { flex-direction: column; gap: 8px; text-align: center; }
        .kode-text { font-size: 1.3rem; }
        .desktop-date { display: none; }
        .mobile-date-group { display: block !important; }
        .modern-upload-label { flex-direction: column; padding: 1.5rem; }
        .file-card { flex-wrap: wrap; justify-content: center; text-align: center; }
        .file-card-details { width: 100%; text-align: center; }
        .file-card-actions { width: 100%; justify-content: center; margin-top: 0.5rem; }
        .file-action-btn { width: 44px; height: 44px; }
        .document-info-box .d-flex { flex-direction: column; text-align: center; }
    }

    @media (min-width: 769px) {
        .desktop-date { display: block; }
        .mobile-date-group { display: none !important; }
    }
</style>
@endpush

@push('scripts')
<script>
    // === GLOBAL VARIABLES ===
    let isSubmitting = false;
    let generatedKode = '';
    let selectedFileSurat = null;
    let fileObjectUrlSurat = null;
    let selectedFileCv = null;
    let fileObjectUrlCv = null;

    // === UTILITY FUNCTIONS ===
    function formatWA(input) {
        let value = input.value.replace(/\D/g, '');
        if (value.startsWith('0')) value = value.substring(1);
        if (value.startsWith('62')) value = value.substring(2);
        input.value = value.substring(0, 13);
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function showNotification(message, type = 'error') {
        let notification = document.getElementById('floatingNotification');
        if (!notification) {
            notification = document.createElement('div');
            notification.id = 'floatingNotification';
            notification.className = 'floating-notification d-none';
            notification.innerHTML = `
                <div class="notification-content">
                    <i class="fas fa-exclamation-circle"></i>
                    <span id="notificationMessage"></span>
                </div>
            `;
            document.body.appendChild(notification);
        }
        
        const notificationMessage = document.getElementById('notificationMessage');
        if (!notificationMessage) return;
        
        notificationMessage.textContent = message;
        notification.classList.remove('d-none');
        
        const icon = notification.querySelector('i');
        if (icon) {
            if (type === 'success') {
                icon.style.color = '#28a745';
                icon.className = 'fas fa-check-circle';
            } else {
                icon.style.color = '#dc3545';
                icon.className = 'fas fa-exclamation-circle';
            }
        }
        
        setTimeout(() => { notification.classList.add('d-none'); }, 3000);
    }

    // === TANGGAL LAHIR HANDLING ===
    function combineTanggalLahir() {
        const hari = document.getElementById('tgl_lahir_hari');
        const bulan = document.getElementById('tgl_lahir_bulan');
        const tahun = document.getElementById('tgl_lahir_tahun');
        
        if (hari && bulan && tahun && hari.value && bulan.value && tahun.value) {
            const tanggal = `${tahun.value}-${String(bulan.value).padStart(2,'0')}-${String(hari.value).padStart(2,'0')}`;
            const hiddenInput = document.getElementById('tanggal_lahir_hidden');
            const desktopInput = document.getElementById('tanggal_lahir_desktop');
            
            if (hiddenInput) hiddenInput.value = tanggal;
            if (desktopInput) desktopInput.value = tanggal;
        }
    }

    function setMobileDateFromOldValue() {
        const oldValue = "{{ old('tanggal_lahir') }}";
        if (oldValue && oldValue.includes('-')) {
            const parts = oldValue.split('-');
            if (parts.length === 3) {
                const tahunSelect = document.getElementById('tgl_lahir_tahun');
                const bulanSelect = document.getElementById('tgl_lahir_bulan');
                const hariSelect = document.getElementById('tgl_lahir_hari');
                
                if (tahunSelect) tahunSelect.value = parts[0];
                if (bulanSelect) bulanSelect.value = parseInt(parts[1]);
                if (hariSelect) hariSelect.value = parseInt(parts[2]);
                combineTanggalLahir();
            }
        }
    }

    // === PENDIDIKAN HANDLING ===
    function selectEducationType(type) {
        document.querySelectorAll('.education-option').forEach(opt => opt.classList.remove('selected'));
        const selectedOption = document.querySelector(`.education-option[data-type="${type}"]`);
        if (selectedOption) selectedOption.classList.add('selected');
        
        const radioBtn = document.querySelector(`input[name="jenis_pendidikan"][value="${type}"]`);
        if (radioBtn) radioBtn.checked = true;
        
        const smkFields = document.getElementById('smk-fields');
        const univFields = document.getElementById('univ-fields');
        const sectionCv = document.getElementById('section-cv');
        
        if (smkFields) smkFields.classList.add('d-none');
        if (univFields) univFields.classList.add('d-none');
        
        if (type === 'smk') {
            if (smkFields) smkFields.classList.remove('d-none');
            if (sectionCv) sectionCv.classList.add('d-none');
        } else {
            if (univFields) univFields.classList.remove('d-none');
            if (sectionCv) sectionCv.classList.remove('d-none');
        }
    }

    // === DURASI HANDLING ===
    function hitungDurasi() {
        const tglMulai = document.getElementById('tanggal_mulai');
        const tglSelesai = document.getElementById('tanggal_selesai');
        const durasiCard = document.getElementById('durasiCard');
        const durasiInfo = document.getElementById('durasi_info');
        
        if (!tglMulai || !tglSelesai || !durasiCard || !durasiInfo) return;
        
        if (tglMulai.value && tglSelesai.value) {
            const start = new Date(tglMulai.value);
            const end = new Date(tglSelesai.value);
            
            if (end < start) {
                durasiCard.style.display = 'flex';
                durasiInfo.innerHTML = '<span class="text-danger">Tanggal selesai tidak boleh sebelum tanggal mulai</span>';
                return;
            }
            
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            let text = `Durasi: ${diffDays} hari`;
            
            if (diffDays >= 30) {
                const months = Math.floor(diffDays / 30);
                const days = diffDays % 30;
                text += ` (${months} bulan${days > 0 ? ' ' + days + ' hari' : ''})`;
            } else if (diffDays >= 7) {
                const weeks = Math.floor(diffDays / 7);
                const days = diffDays % 7;
                text += ` (${weeks} minggu${days > 0 ? ' ' + days + ' hari' : ''})`;
            }
            
            durasiInfo.innerHTML = text;
            durasiCard.style.display = 'flex';
        } else {
            durasiCard.style.display = 'none';
        }
    }

    // === FILE HANDLING SURAT ===
    function handleFileSelectSurat(file) {
        if (!file) return false;
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
        const isValidType = validTypes.includes(file.type) || file.name.match(/\.(pdf|jpg|jpeg|png)$/i);
        if (!isValidType) { showNotification('Format file harus JPG, JPEG, PNG, atau PDF'); return false; }
        if (file.size > 10 * 1024 * 1024) { showNotification('Ukuran file maksimal 10MB'); return false; }

        selectedFileSurat = file;
        if (fileObjectUrlSurat) URL.revokeObjectURL(fileObjectUrlSurat);
        fileObjectUrlSurat = URL.createObjectURL(file);

        document.getElementById('uploadLabelSurat').classList.add('d-none');
        document.getElementById('fileInfoSurat').classList.remove('d-none');
        
        let fileName = file.name;
        if (window.innerWidth <= 768 && fileName.length > 30) {
            const ext = fileName.split('.').pop();
            fileName = `${fileName.substring(0, 26)}...${ext}`;
        }
        
        document.getElementById('fileNameSurat').textContent = fileName;
        document.getElementById('fileSizeSurat').textContent = formatFileSize(file.size);
        
        const icon = document.getElementById('fileCardIconSurat');
        if (file.type.startsWith('image/')) icon.innerHTML = '<i class="fas fa-file-image"></i>';
        else if (file.type === 'application/pdf') icon.innerHTML = '<i class="fas fa-file-pdf"></i>';
        else icon.innerHTML = '<i class="fas fa-file-alt"></i>';

        return true;
    }

    function removeFileSurat() {
        document.getElementById('upload_surat_pengantar').value = '';
        document.getElementById('uploadLabelSurat').classList.remove('d-none');
        document.getElementById('fileInfoSurat').classList.add('d-none');
        if (fileObjectUrlSurat) { URL.revokeObjectURL(fileObjectUrlSurat); fileObjectUrlSurat = null; }
        selectedFileSurat = null;
        document.getElementById('section-5').classList.remove('error-highlight');
    }

    function previewFileSurat() {
        if (!selectedFileSurat) { showNotification('Tidak ada file untuk dipreview'); return; }
        previewFileInModal(selectedFileSurat, fileObjectUrlSurat);
    }

    // === FILE HANDLING CV ===
    function handleFileSelectCv(file) {
        if (!file) return false;
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
        const isValidType = validTypes.includes(file.type) || file.name.match(/\.(pdf|jpg|jpeg|png)$/i);
        if (!isValidType) { showNotification('Format file harus JPG, JPEG, PNG, atau PDF'); return false; }
        if (file.size > 10 * 1024 * 1024) { showNotification('Ukuran file maksimal 10MB'); return false; }

        selectedFileCv = file;
        if (fileObjectUrlCv) URL.revokeObjectURL(fileObjectUrlCv);
        fileObjectUrlCv = URL.createObjectURL(file);

        document.getElementById('uploadLabelCv').classList.add('d-none');
        document.getElementById('fileInfoCv').classList.remove('d-none');
        
        let fileName = file.name;
        if (window.innerWidth <= 768 && fileName.length > 30) {
            const ext = fileName.split('.').pop();
            fileName = `${fileName.substring(0, 26)}...${ext}`;
        }
        
        document.getElementById('fileNameCv').textContent = fileName;
        document.getElementById('fileSizeCv').textContent = formatFileSize(file.size);
        
        const icon = document.getElementById('fileCardIconCv');
        if (file.type.startsWith('image/')) icon.innerHTML = '<i class="fas fa-file-image"></i>';
        else if (file.type === 'application/pdf') icon.innerHTML = '<i class="fas fa-file-pdf"></i>';
        else icon.innerHTML = '<i class="fas fa-file-alt"></i>';

        return true;
    }

    function removeFileCv() {
        document.getElementById('upload_cv').value = '';
        document.getElementById('uploadLabelCv').classList.remove('d-none');
        document.getElementById('fileInfoCv').classList.add('d-none');
        if (fileObjectUrlCv) { URL.revokeObjectURL(fileObjectUrlCv); fileObjectUrlCv = null; }
        selectedFileCv = null;
    }

    function previewFileCv() {
        if (!selectedFileCv) { showNotification('Tidak ada file CV untuk dipreview'); return; }
        previewFileInModal(selectedFileCv, fileObjectUrlCv);
    }

    // === MODAL PREVIEW ===
    function previewFileInModal(file, objectUrl) {
        const modalElement = document.getElementById('filePreviewModal');
        if (!modalElement) return;
        const modal = new bootstrap.Modal(modalElement);
        const modalContent = document.getElementById('modalPreviewContent');
        if (!modalContent) return;
        
        modalContent.innerHTML = '<div class="p-5 text-white"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Memuat file...</p></div>';
        modal.show();
        
        if (file.type.startsWith('image/')) {
            const img = new Image();
            img.onload = () => modalContent.innerHTML = `<img src="${objectUrl}" class="preview-image" alt="Preview">`;
            img.onerror = () => modalContent.innerHTML = '<div class="p-5 text-danger"><i class="fas fa-exclamation-triangle fa-2x"></i><p>Gagal memuat gambar.</p></div>';
            img.src = objectUrl;
        } else if (file.type === 'application/pdf') {
            modalContent.innerHTML = `<iframe src="${objectUrl}" class="preview-pdf" frameborder="0"></iframe>`;
        } else {
            modalContent.innerHTML = `<div class="p-5 text-white"><i class="fas fa-file fa-3x mb-3"></i><h5>${file.name}</h5><a href="${objectUrl}" target="_blank" class="btn btn-primary mt-2"><i class="fas fa-download me-2"></i>Download</a></div>`;
        }
    }

    // === KODE HANDLING ===
    async function fetchKodeFromServer() {
        try {
            const response = await fetch('{{ route("user.generate.kode.individu") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            const data = await response.json();
            
            if (data.success && data.kode) {
                generatedKode = data.kode;
                const previewElement = document.getElementById('previewKodeDisplay');
                if (previewElement) {
                    previewElement.innerHTML = generatedKode;
                    previewElement.style.fontSize = '1.3rem';
                    previewElement.style.fontWeight = 'bold';
                    previewElement.style.letterSpacing = '2px';
                }
                const kodeInput = document.getElementById('kode_pendaftaran');
                if (kodeInput) kodeInput.value = generatedKode;
                
                const btnCopy = document.getElementById('btnCopyKode');
                const btnSubmit = document.getElementById('btnConfirmSubmit');
                if (btnCopy) { btnCopy.disabled = false; btnCopy.innerHTML = '<i class="fas fa-copy me-2"></i>Salin Kode'; }
                if (btnSubmit) { btnSubmit.disabled = false; btnSubmit.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Kirim'; }
                return true;
            }
            return false;
        } catch (error) {
            showNotification('Gagal generate kode. Silakan coba lagi!');
            return false;
        }
    }

    function copyPreviewKode() {
        let kodeToCopy = generatedKode;
        if (!kodeToCopy || kodeToCopy === '') {
            const previewElement = document.getElementById('previewKodeDisplay');
            if (previewElement) kodeToCopy = previewElement.textContent || previewElement.innerText;
            if (!kodeToCopy || kodeToCopy.includes('Generating') || kodeToCopy.includes('spinner')) {
                showNotification('Kode masih dalam proses generate. Tunggu sebentar!', 'error');
                return;
            }
        }
        if (!kodeToCopy || kodeToCopy.length < 5) { showNotification('Kode tidak valid.', 'error'); return; }
        
        // Gunakan fungsi copy universal
        copyTextToClipboard(kodeToCopy);
    }
    
    function copyPermanentKode() {
        const kodeElement = document.getElementById('permanentKode');
        if (!kodeElement) return;
        const kode = kodeElement.textContent || kodeElement.innerText;
        if (!kode) return;
        
        // Gunakan fungsi copy universal
        copyTextToClipboard(kode);
    }

    // Fungsi copy universal yang kompatibel dengan semua perangkat
    function copyTextToClipboard(text) {
        // Method 1: Navigator Clipboard API (Modern)
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(() => {
                showNotification('✓ Kode berhasil disalin!', 'success');
            }).catch(err => {
                console.log('Clipboard API gagal, mencoba fallback:', err);
                fallbackCopyText(text);
            });
        } else {
            // Method 2: Fallback menggunakan textarea
            fallbackCopyText(text);
        }
    }
    
    function fallbackCopyText(text) {
        // Buat elemen textarea yang akan digunakan untuk copy
        const textArea = document.createElement('textarea');
        textArea.value = text;
        
        // Styling untuk memastikan elemen tidak terlihat tapi bisa di-select
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
        textArea.style.zIndex = '999999';
        
        document.body.appendChild(textArea);
        
        // Untuk iOS, kita perlu mengatur contentEditable
        textArea.contentEditable = true;
        textArea.readOnly = false;
        
        // Set selection range
        const range = document.createRange();
        range.selectNodeContents(textArea);
        const selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);
        textArea.setSelectionRange(0, 999999);
        
        // Fokus ke textarea (penting untuk mobile)
        textArea.focus();
        
        try {
            const successful = document.execCommand('copy');
            if (successful) {
                showNotification('✓ Kode berhasil disalin!', 'success');
            } else {
                showNotification('Gagal menyalin otomatis. Silakan salin manual: ' + text, 'error');
            }
        } catch (err) {
            console.error('Fallback copy gagal:', err);
            showNotification('Gagal menyalin. Silakan salin manual: ' + text, 'error');
        }
        
        // Cleanup
        selection.removeAllRanges();
        document.body.removeChild(textArea);
    }

    // === VALIDATION ===
    function validateForm() {
        document.querySelectorAll('.error-field, .is-invalid').forEach(el => el.classList.remove('error-field', 'is-invalid'));
        document.querySelectorAll('.section-wrapper').forEach(el => el.classList.remove('error-highlight'));
        
        if (!document.getElementById('nama_lengkap')?.value.trim()) { showNotification('Nama lengkap harus diisi!'); document.getElementById('nama_lengkap')?.classList.add('error-field'); return false; }
        if (!document.getElementById('jenis_kelamin')?.value) { showNotification('Jenis kelamin harus diisi!'); return false; }
        if (!document.getElementById('tempat_lahir')?.value.trim()) { showNotification('Tempat lahir harus diisi!'); return false; }
        
        if (window.innerWidth <= 768) {
            if (!document.getElementById('tgl_lahir_hari')?.value || !document.getElementById('tgl_lahir_bulan')?.value || !document.getElementById('tgl_lahir_tahun')?.value) {
                showNotification('Tanggal lahir harus diisi lengkap!'); return false;
            }
            combineTanggalLahir();
        } else {
            if (!document.getElementById('tanggal_lahir_desktop')?.value) { showNotification('Tanggal lahir harus diisi!'); return false; }
            document.getElementById('tanggal_lahir_hidden').value = document.getElementById('tanggal_lahir_desktop').value;
        }
        
        const email = document.getElementById('email');
        if (!email?.value.trim()) { showNotification('Email harus diisi!'); email?.classList.add('error-field'); return false; }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) { showNotification('Format email tidak valid!'); email?.classList.add('error-field'); return false; }
        
        const wa = document.getElementById('no_whatsapp');
        if (!wa?.value.trim()) { showNotification('No WhatsApp harus diisi!'); return false; }
        if (wa.value.length < 10) { showNotification('No WhatsApp minimal 10 digit!'); return false; }
        
        if (!document.getElementById('alamat_lengkap')?.value.trim()) { showNotification('Alamat lengkap harus diisi!'); return false; }
        
        const pendidikanSmk = document.getElementById('pendidikan_smk');
        const pendidikanKuliah = document.getElementById('pendidikan_kuliah');
        if ((!pendidikanSmk || !pendidikanSmk.checked) && (!pendidikanKuliah || !pendidikanKuliah.checked)) { showNotification('Pilih jenis institusi!'); return false; }
        
        if (pendidikanSmk?.checked) {
            const fields = ['sekolah', 'jurusan_smk', 'kelas', 'nis', 'guru_pembimbing', 'no_hp_guru'];
            const labels = ['Nama SMK', 'Jurusan', 'Kelas', 'NIS', 'Guru Pembimbing', 'No HP Guru'];
            for (let i = 0; i < fields.length; i++) {
                if (!document.getElementById(fields[i])?.value.trim()) { showNotification(`${labels[i]} harus diisi!`); return false; }
            }
        } else {
            const fields = ['kuliah', 'jurusan_univ', 'semester', 'nim', 'dosen_pembimbing', 'no_hp_dosen'];
            const labels = ['Nama Kampus', 'Program Studi', 'Semester', 'NIM', 'Dosen Pembimbing', 'No HP Dosen'];
            for (let i = 0; i < fields.length; i++) {
                if (!document.getElementById(fields[i])?.value.trim()) { showNotification(`${labels[i]} harus diisi!`); return false; }
            }
        }
        
        ['alasan_pkl_gi', 'skill_ingin_dipelajari', 'harapan_setelah_pkl'].forEach(id => {
            if (!document.getElementById(id)?.value.trim()) { showNotification('Semua field motivasi harus diisi!'); return false; }
        });
        
        if (!document.getElementById('tanggal_mulai')?.value) { showNotification('Tanggal mulai harus diisi!'); return false; }
        if (!document.getElementById('tanggal_selesai')?.value) { showNotification('Tanggal selesai harus diisi!'); return false; }
        if (new Date(document.getElementById('tanggal_selesai').value) < new Date(document.getElementById('tanggal_mulai').value)) {
            showNotification('Tanggal selesai tidak boleh sebelum tanggal mulai!'); return false;
        }
        
        if (!selectedFileSurat) {
            showNotification('Upload surat pengantar!');
            const s5 = document.getElementById('section-5');
            s5?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            s5?.classList.add('error-highlight');
            return false;
        }
        
        if (!document.getElementById('terms')?.checked) { showNotification('Anda harus menyetujui syarat dan ketentuan!'); return false; }
        
        return true;
    }

    async function validateAndShowPreview() {
        if (isSubmitting) return;
        if (!validateForm()) return;
        
        const btn = document.getElementById('btnSubmit');
        const originalHtml = btn?.innerHTML || '';
        if (btn) { btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...'; btn.disabled = true; }
        
        document.getElementById('step-2')?.classList.add('active');
        document.getElementById('alurProgress').style.width = '50%';
        
        document.getElementById('previewKodeDisplay').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
        document.getElementById('btnCopyKode').disabled = true;
        document.getElementById('btnConfirmSubmit').disabled = true;
        
        new bootstrap.Modal(document.getElementById('previewModal')).show();
        
        const success = await fetchKodeFromServer();
        if (btn) { btn.innerHTML = originalHtml; btn.disabled = false; }
        if (!success) {
            showNotification('Gagal generate kode. Silakan coba lagi.');
            document.getElementById('previewKodeDisplay').innerHTML = '<span class="text-danger">Gagal generate kode</span>';
        }
    }

    function submitForm() {
        if (isSubmitting) return;
        if (!generatedKode || generatedKode === '') { showNotification('Kode belum siap!', 'error'); return; }
        
        isSubmitting = true;
        bootstrap.Modal.getInstance(document.getElementById('previewModal'))?.hide();
        
        document.getElementById('step-3')?.classList.add('active');
        document.getElementById('alurProgress').style.width = '75%';
        
        document.getElementById('registrationForm')?.submit();
    }

    // === INITIALIZATION ===
    document.addEventListener('DOMContentLoaded', function() {
        const selectedType = document.querySelector('input[name="jenis_pendidikan"]:checked');
        if (selectedType) selectEducationType(selectedType.value);
        else selectEducationType('smk');
        
        document.querySelectorAll('input[id*="no_hp"], input[id*="whatsapp"]').forEach(i => i.addEventListener('input', function() { formatWA(this); }));
        
        document.querySelectorAll('input, select, textarea').forEach(el => {
            el.addEventListener('input', function() { this.classList.remove('error-field'); });
            el.addEventListener('change', function() { this.classList.remove('error-field'); });
        });
        
        const tglMulai = document.getElementById('tanggal_mulai');
        const tglSelesai = document.getElementById('tanggal_selesai');
        if (tglMulai) tglMulai.addEventListener('change', function() { if (tglSelesai) tglSelesai.min = this.value; hitungDurasi(); });
        if (tglSelesai) tglSelesai.addEventListener('change', hitungDurasi);
        if (tglMulai?.value && tglSelesai?.value) hitungDurasi();
        
        setMobileDateFromOldValue();
        ['tgl_lahir_hari', 'tgl_lahir_bulan', 'tgl_lahir_tahun'].forEach(id => document.getElementById(id)?.addEventListener('change', combineTanggalLahir));
        document.getElementById('tanggal_lahir_desktop')?.addEventListener('change', function() {
            document.getElementById('tanggal_lahir_hidden').value = this.value;
        });
        
        // File surat
        const fileSurat = document.getElementById('upload_surat_pengantar');
        if (fileSurat) fileSurat.addEventListener('change', function(e) { if (this.files?.length) handleFileSelectSurat(this.files[0]); });
        document.getElementById('uploadLabelSurat')?.addEventListener('click', function(e) { e.preventDefault(); document.getElementById('upload_surat_pengantar').click(); });
        
        // File CV
        const fileCv = document.getElementById('upload_cv');
        if (fileCv) fileCv.addEventListener('change', function(e) { if (this.files?.length) handleFileSelectCv(this.files[0]); });
        document.getElementById('uploadLabelCv')?.addEventListener('click', function(e) { e.preventDefault(); document.getElementById('upload_cv').click(); });
    });

    window.selectEducationType = selectEducationType;
    window.formatWA = formatWA;
    window.hitungDurasi = hitungDurasi;
    window.removeFileSurat = removeFileSurat;
    window.previewFileSurat = previewFileSurat;
    window.removeFileCv = removeFileCv;
    window.previewFileCv = previewFileCv;
    window.validateAndShowPreview = validateAndShowPreview;
    window.copyPreviewKode = copyPreviewKode;
    window.copyPermanentKode = copyPermanentKode;
    window.submitForm = submitForm;
</script>
@endpush

@endsection