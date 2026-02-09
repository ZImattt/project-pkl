@extends('layouts.app')

@section('title', 'Form Pendaftaran PKL')
@section('content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="text-center mb-4">
                <h1 class="hero-title mb-2">
                    <i class="fas fa-file-alt me-2"></i>Formulir Pendaftaran PKL/MAGANG
                </h1>
                <p class="text-muted mb-3">
                    Isi data diri dengan lengkap dan benar. Pastikan semua informasi valid.
                </p>
                <div class="progress mb-3 d-none d-md-block" style="height: 6px;">
                    <div class="progress-bar bg-primary" style="width: 100%;"></div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="card-custom">
                <div class="card-body p-3 p-md-4">
                    
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

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle me-3 fs-4"></i>
                                <div>
                                    <h5 class="mb-1">Terjadi Kesalahan!</h5>
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

                    @if(session('debug'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-bug me-3 fs-4"></i>
                                <div>
                                    <h5 class="mb-1">Debug Info:</h5>
                                    @foreach(session('debug') as $error)
                                        <p class="mb-1">{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Progress Indicator Mobile -->
                    <div class="d-md-none mb-4">
                        <div class="steps-indicator">
                            <div class="step active" data-step="1" onclick="scrollToSection('section-1')">
                                <div class="step-icon"><i class="fas fa-user"></i></div>
                                <div class="step-label">Data Diri</div>
                            </div>
                            <div class="step" data-step="2" onclick="scrollToSection('section-2')">
                                <div class="step-icon"><i class="fas fa-graduation-cap"></i></div>
                                <div class="step-label">Pendidikan</div>
                            </div>
                            <div class="step" data-step="3" onclick="scrollToSection('section-3')">
                                <div class="step-icon"><i class="fas fa-bullseye"></i></div>
                                <div class="step-label">Motivasi</div>
                            </div>
                            <div class="step" data-step="4" onclick="scrollToSection('section-4')">
                                <div class="step-icon"><i class="fas fa-calendar"></i></div>
                                <div class="step-label">Periode</div>
                            </div>
                            <div class="step" data-step="5" onclick="scrollToSection('section-5')">
                                <div class="step-icon"><i class="fas fa-file"></i></div>
                                <div class="step-label">Dokumen</div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('student.register.store') }}" method="POST" enctype="multipart/form-data" id="registrationForm">
                        @csrf

                        <!-- Section 1: Data Diri -->
                        <div class="section-wrapper mb-4" id="section-1" data-section="1">
                            <div class="section-header mb-3">
                                <h4 class="section-title mb-2">
                                    <span class="section-number">1</span>
                                    <i class="fas fa-user-circle me-2"></i>Data Identitas Diri
                                </h4>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row g-3">
                                <!-- Nama Lengkap -->
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="nama_lengkap" class="form-label-custom">
                                            <i class="fas fa-user me-1"></i> Nama Lengkap <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control-custom @error('nama_lengkap') is-invalid @enderror" 
                                               id="nama_lengkap" name="nama_lengkap" 
                                               value="{{ old('nama_lengkap') }}" 
                                               placeholder="Masukkan nama lengkap sesuai ijazah" required>
                                        @error('nama_lengkap')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Jenis Kelamin -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label-custom">
                                            <i class="fas fa-venus-mars me-1"></i> Jenis Kelamin <span class="text-danger">*</span>
                                        </label>
                                        <div class="gender-selector">
                                            <div class="gender-option">
                                                <input type="radio" name="jenis_kelamin" id="laki" value="L" 
                                                       {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }} required>
                                                <label for="laki">
                                                    <i class="fas fa-male"></i>
                                                    <span>Laki-laki</span>
                                                </label>
                                            </div>
                                            <div class="gender-option">
                                                <input type="radio" name="jenis_kelamin" id="perempuan" value="P" 
                                                       {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }}>
                                                <label for="perempuan">
                                                    <i class="fas fa-female"></i>
                                                    <span>Perempuan</span>
                                                </label>
                                            </div>
                                        </div>
                                        @error('jenis_kelamin')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Tempat Lahir -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tempat_lahir" class="form-label-custom">
                                            <i class="fas fa-map-marker-alt me-1"></i> Tempat Lahir <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control-custom @error('tempat_lahir') is-invalid @enderror" 
                                               id="tempat_lahir" name="tempat_lahir" 
                                               value="{{ old('tempat_lahir') }}" 
                                               placeholder="Kota kelahiran" required>
                                        @error('tempat_lahir')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Tanggal Lahir -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_lahir" class="form-label-custom">
                                            <i class="fas fa-calendar-day me-1"></i> Tanggal Lahir <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control-custom @error('tanggal_lahir') is-invalid @enderror" 
                                               id="tanggal_lahir" name="tanggal_lahir" 
                                               value="{{ old('tanggal_lahir') }}" 
                                               max="{{ date('Y-m-d', strtotime('-15 years')) }}" required>
                                        @error('tanggal_lahir')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text text-muted">
                                            <i class="fas fa-info-circle me-1"></i> Minimal 15 tahun
                                        </div>
                                    </div>
                                </div>

                                <!-- Alamat Lengkap -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="alamat_lengkap" class="form-label-custom">
                                            <i class="fas fa-home me-1"></i> Alamat Lengkap <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control-custom @error('alamat_lengkap') is-invalid @enderror" 
                                                  id="alamat_lengkap" name="alamat_lengkap" 
                                                  rows="3" placeholder="Masukkan alamat lengkap tempat tinggal saat ini (jalan, RT/RW, kelurahan, kecamatan, kota)"
                                                  required>{{ old('alamat_lengkap') }}</textarea>
                                        @error('alamat_lengkap')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-label-custom">
                                            <i class="fas fa-envelope me-1"></i> Email <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" class="form-control-custom @error('email') is-invalid @enderror" 
                                               id="email" name="email" 
                                               value="{{ old('email') }}" 
                                               placeholder="contoh@email.com" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- WhatsApp -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="no_whatsapp" class="form-label-custom">
                                            <i class="fab fa-whatsapp me-1"></i> WhatsApp <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">+62</span>
                                            <input type="text" class="form-control-custom @error('no_whatsapp') is-invalid @enderror" 
                                                   id="no_whatsapp" name="no_whatsapp" 
                                                   value="{{ old('no_whatsapp') }}" 
                                                   placeholder="81234567890" required>
                                        </div>
                                        @error('no_whatsapp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text text-muted">
                                            <i class="fas fa-info-circle me-1"></i> Tanpa 0 di depan (contoh: 81234567890)
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Data Pendidikan -->
                        <div class="section-wrapper mb-4" id="section-2" data-section="2">
                            <div class="section-header mb-3">
                                <h4 class="section-title mb-2">
                                    <span class="section-number">2</span>
                                    <i class="fas fa-user-graduate me-2"></i>Data Pendidikan
                                </h4>
                                <div class="section-divider"></div>
                            </div>

                            <!-- Jenis Pendidikan -->
                            <div class="form-group mb-4">
                                <label class="form-label-custom mb-3">
                                    <i class="fas fa-graduation-cap me-1"></i> Jenis Pendidikan <span class="text-danger">*</span>
                                </label>
                                <div class="education-type-selector">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="education-option {{ old('jenis_pendidikan') == 'smk' || !old('jenis_pendidikan') ? 'selected' : '' }}" 
                                                 onclick="selectEducationType('smk')">
                                                <div class="education-icon">
                                                    <i class="fas fa-school"></i>
                                                </div>
                                                <div class="education-content">
                                                    <h6>SMK/SMA</h6>
                                                    <p class="mb-0">Pendidikan menengah kejuruan</p>
                                                </div>
                                                <input type="radio" name="jenis_pendidikan" value="smk" 
                                                       {{ old('jenis_pendidikan') == 'smk' || !old('jenis_pendidikan') ? 'checked' : '' }} 
                                                       required hidden>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="education-option {{ old('jenis_pendidikan') == 'universitas' ? 'selected' : '' }}" 
                                                 onclick="selectEducationType('universitas')">
                                                <div class="education-icon">
                                                    <i class="fas fa-university"></i>
                                                </div>
                                                <div class="education-content">
                                                    <h6>Universitas</h6>
                                                    <p class="mb-0">Perguruan tinggi</p>
                                                </div>
                                                <input type="radio" name="jenis_pendidikan" value="universitas" 
                                                       {{ old('jenis_pendidikan') == 'universitas' ? 'checked' : '' }} 
                                                       hidden>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @error('jenis_pendidikan')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- SMK Fields -->
                            <div id="smk-fields" class="pendidikan-fields {{ old('jenis_pendidikan') == 'universitas' ? 'd-none' : '' }}">
                                <h5 class="subsection-title mb-3">
                                    <i class="fas fa-school me-2"></i>Data SMK
                                </h5>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sekolah" class="form-label-custom">
                                                Nama SMK <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control-custom @error('sekolah') is-invalid @enderror" 
                                                   id="sekolah" name="sekolah" 
                                                   value="{{ old('sekolah') }}" 
                                                   placeholder="Nama lengkap SMK" 
                                                   data-required-if="jenis_pendidikan:smk">
                                            @error('sekolah')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="jurusan_smk" class="form-label-custom">
                                                Jurusan <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control-custom @error('jurusan_smk') is-invalid @enderror" 
                                                   id="jurusan_smk" name="jurusan_smk" 
                                                   value="{{ old('jurusan_smk') }}" 
                                                   placeholder="Contoh: Rekayasa Perangkat Lunak"
                                                   data-required-if="jenis_pendidikan:smk">
                                            @error('jurusan_smk')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="kelas" class="form-label-custom">
                                                Kelas <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control-custom @error('kelas') is-invalid @enderror" 
                                                   id="kelas" name="kelas" 
                                                   value="{{ old('kelas') }}" 
                                                   placeholder="Contoh: 11,12"
                                                   data-required-if="jenis_pendidikan:smk">
                                            @error('kelas')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nis" class="form-label-custom">
                                                NIS <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control-custom @error('nis') is-invalid @enderror" 
                                                   id="nis" name="nis" 
                                                   value="{{ old('nis') }}" 
                                                   placeholder="Nomor Induk Siswa"
                                                   data-required-if="jenis_pendidikan:smk">
                                            @error('nis')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="guru_pembimbing" class="form-label-custom">
                                                Guru Pembimbing <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control-custom @error('guru_pembimbing') is-invalid @enderror" 
                                                   id="guru_pembimbing" name="guru_pembimbing" 
                                                   value="{{ old('guru_pembimbing') }}" 
                                                   placeholder="Nama lengkap guru"
                                                   data-required-if="jenis_pendidikan:smk">
                                            @error('guru_pembimbing')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="no_hp_guru" class="form-label-custom">
                                                No. HP Guru <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">+62</span>
                                                <input type="text" class="form-control-custom @error('no_hp_guru') is-invalid @enderror" 
                                                       id="no_hp_guru" name="no_hp_guru" 
                                                       value="{{ old('no_hp_guru') }}" 
                                                       placeholder="81234567890"
                                                       data-required-if="jenis_pendidikan:smk">
                                            </div>
                                            @error('no_hp_guru')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Universitas Fields -->
                            <div id="univ-fields" class="pendidikan-fields {{ old('jenis_pendidikan') == 'universitas' ? '' : 'd-none' }}">
                                <h5 class="subsection-title mb-3">
                                    <i class="fas fa-university me-2"></i>Data Universitas
                                </h5>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="universitas" class="form-label-custom">
                                                Nama Universitas <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control-custom @error('universitas') is-invalid @enderror" 
                                                   id="universitas" name="universitas" 
                                                   value="{{ old('universitas') }}" 
                                                   placeholder="Nama lengkap universitas"
                                                   data-required-if="jenis_pendidikan:universitas">
                                            @error('universitas')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="jurusan_univ" class="form-label-custom">
                                                Program Studi <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control-custom @error('jurusan_univ') is-invalid @enderror" 
                                                   id="jurusan_univ" name="jurusan_univ" 
                                                   value="{{ old('jurusan_univ') }}" 
                                                   placeholder="Contoh: Teknik Informatika"
                                                   data-required-if="jenis_pendidikan:universitas">
                                            @error('jurusan_univ')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="semester" class="form-label-custom">
                                                Semester <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control-custom @error('semester') is-invalid @enderror" 
                                                   id="semester" name="semester" 
                                                   value="{{ old('semester') }}" 
                                                   placeholder="Contoh: Semester 5"
                                                   data-required-if="jenis_pendidikan:universitas">
                                            @error('semester')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nim" class="form-label-custom">
                                                NIM <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control-custom @error('nim') is-invalid @enderror" 
                                                   id="nim" name="nim" 
                                                   value="{{ old('nim') }}" 
                                                   placeholder="Nomor Induk Mahasiswa"
                                                   data-required-if="jenis_pendidikan:universitas">
                                            @error('nim')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="dosen_pembimbing" class="form-label-custom">
                                                Dosen Pembimbing <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control-custom @error('dosen_pembimbing') is-invalid @enderror" 
                                                   id="dosen_pembimbing" name="dosen_pembimbing" 
                                                   value="{{ old('dosen_pembimbing') }}" 
                                                   placeholder="Nama lengkap dosen"
                                                   data-required-if="jenis_pendidikan:universitas">
                                            @error('dosen_pembimbing')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="no_hp_dosen" class="form-label-custom">
                                                No. HP Dosen <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">+62</span>
                                                <input type="text" class="form-control-custom @error('no_hp_dosen') is-invalid @enderror" 
                                                       id="no_hp_dosen" name="no_hp_dosen" 
                                                       value="{{ old('no_hp_dosen') }}" 
                                                       placeholder="81234567890"
                                                       data-required-if="jenis_pendidikan:universitas">
                                            </div>
                                            @error('no_hp_dosen')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Motivasi PKL -->
                        <div class="section-wrapper mb-4" id="section-3" data-section="3">
                            <div class="section-header mb-3">
                                <h4 class="section-title mb-2">
                                    <span class="section-number">3</span>
                                    <i class="fas fa-bullseye me-2"></i>Motivasi PKL
                                </h4>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="alasan_pkl_gi" class="form-label-custom">
                                            <i class="fas fa-question-circle me-1"></i> Mengapa ingin PKL/Magang di Global Intermedia? <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control-custom @error('alasan_pkl_gi') is-invalid @enderror" 
                                                  id="alasan_pkl_gi" name="alasan_pkl_gi" 
                                                  rows="4" 
                                                  placeholder="Jelaskan alasan Anda memilih Global Intermedia untuk PKL/Magang. Misal: karena reputasi, bidang kerja, atau kesempatan belajar yang ditawarkan."
                                                  required>{{ old('alasan_pkl_gi') }}</textarea>
                                        @error('alasan_pkl_gi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="char-counter">
                                            <span id="alasan_counter">0</span>/500 karakter
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="skill_ingin_dipelajari" class="form-label-custom">
                                            <i class="fas fa-tools me-1"></i> Skill apa yang ingin dipelajari? <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control-custom @error('skill_ingin_dipelajari') is-invalid @enderror" 
                                                  id="skill_ingin_dipelajari" name="skill_ingin_dipelajari" 
                                                  rows="3" 
                                                  placeholder="Contoh: Web Development, Mobile Apps, UI/UX Design, Digital Marketing, Data Analysis, dll"
                                                  required>{{ old('skill_ingin_dipelajari') }}</textarea>
                                        @error('skill_ingin_dipelajari')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="char-counter">
                                            <span id="skill_counter">0</span>/300 karakter
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="harapan_setelah_pkl" class="form-label-custom">
                                            <i class="fas fa-bullseye me-1"></i> Harapan setelah PKL/Magang <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control-custom @error('harapan_setelah_pkl') is-invalid @enderror" 
                                                  id="harapan_setelah_pkl" name="harapan_setelah_pkl" 
                                                  rows="3" 
                                                  placeholder="Jelaskan harapan dan tujuan Anda setelah menyelesaikan PKL/Magang di Global Intermedia"
                                                  required>{{ old('harapan_setelah_pkl') }}</textarea>
                                        @error('harapan_setelah_pkl')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="char-counter">
                                            <span id="harapan_counter">0</span>/300 karakter
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="pengalaman_sebelumnya" class="form-label-custom">
                                            <i class="fas fa-briefcase me-1"></i> Pengalaman sebelumnya (opsional)
                                        </label>
                                        <textarea class="form-control-custom @error('pengalaman_sebelumnya') is-invalid @enderror" 
                                                  id="pengalaman_sebelumnya" name="pengalaman_sebelumnya" 
                                                  rows="2" 
                                                  placeholder="Deskripsikan pengalaman kerja, project, atau kegiatan sebelumnya yang relevan">{{ old('pengalaman_sebelumnya') }}</textarea>
                                        @error('pengalaman_sebelumnya')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="char-counter">
                                            <span id="pengalaman_counter">0</span>/200 karakter
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 4: Periode PKL -->
                        <div class="section-wrapper mb-4" id="section-4" data-section="4">
                            <div class="section-header mb-3">
                                <h4 class="section-title mb-2">
                                    <span class="section-number">4</span>
                                    <i class="fas fa-calendar-alt me-2"></i>Periode PKL/Magang
                                </h4>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_mulai" class="form-label-custom">
                                            <i class="fas fa-calendar-plus me-1"></i> Tanggal Mulai <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control-custom @error('tanggal_mulai') is-invalid @enderror" 
                                               id="tanggal_mulai" name="tanggal_mulai" 
                                               value="{{ old('tanggal_mulai') }}" 
                                               min="{{ date('Y-m-d') }}" required>
                                        @error('tanggal_mulai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text text-muted">
                                            <i class="fas fa-info-circle me-1"></i> Tidak bisa memilih tanggal yang sudah lewat
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_selesai" class="form-label-custom">
                                            <i class="fas fa-calendar-minus me-1"></i> Tanggal Selesai <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control-custom @error('tanggal_selesai') is-invalid @enderror" 
                                               id="tanggal_selesai" name="tanggal_selesai" 
                                               value="{{ old('tanggal_selesai') }}" required>
                                        @error('tanggal_selesai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text text-muted">
                                            <i class="fas fa-info-circle me-1"></i> Minimal 1 minggu dari tanggal mulai
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="duration-card">
                                        <div class="duration-icon">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div class="duration-content">
                                            <h6 class="mb-1">Durasi PKL/Magang</h6>
                                            <p class="mb-0" id="durasi_info">Pilih tanggal mulai dan selesai untuk melihat durasi</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 5: Dokumen -->
                        <div class="section-wrapper mb-4" id="section-5" data-section="5">
                            <div class="section-header mb-3">
                                <h4 class="section-title mb-2">
                                    <span class="section-number">5</span>
                                    <i class="fas fa-file-upload me-2"></i>Dokumen
                                </h4>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="upload_surat_pengantar" class="form-label-custom">
                                            <i class="fas fa-file-alt me-1"></i> Surat Pengantar <span class="text-danger">*</span>
                                        </label>
                                        <div class="file-upload-area @error('upload_surat_pengantar') is-invalid @enderror" 
                                             id="fileUploadArea">
                                            <input type="file" class="file-input" 
                                                   id="upload_surat_pengantar" name="upload_surat_pengantar" 
                                                   accept=".pdf,.jpg,.jpeg,.png" required>
                                            <div class="upload-content">
                                                <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                                <h6 class="mb-1">Upload Surat Pengantar</h6>
                                                <p class="text-muted mb-2">Format: PDF, JPG, JPEG, PNG (Maks: 2MB)</p>
                                                <button type="button" class="btn btn-outline-custom btn-sm">
                                                    <i class="fas fa-folder-open me-1"></i> Pilih File
                                                </button>
                                            </div>
                                            <div class="file-preview" id="filePreview">
                                                <!-- File preview akan muncul di sini -->
                                            </div>
                                        </div>
                                        @error('upload_surat_pengantar')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Terms & Conditions -->
                        <div class="card terms-card mb-4" id="section-6" data-section="6">
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input @error('terms') is-invalid @enderror" 
                                           type="checkbox" id="terms" name="terms" required>
                                    <label class="form-check-label" for="terms">
                                        <strong>Dengan ini saya menyatakan bahwa:</strong><br>
                                        1. Data yang saya berikan adalah benar dan dapat dipertanggungjawabkan<br>
                                        2. Saya siap mengikuti seluruh proses PKL/Magang di Global Intermedia<br>
                                        3. Saya akan mematuhi peraturan dan tata tertib yang berlaku<br>
                                        4. Saya telah membaca dan menyetujui semua ketentuan
                                    </label>
                                    @error('terms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-actions" id="section-7" data-section="7">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('student.home') }}" class="btn btn-outline-custom">
                                    <i class="fas fa-arrow-left me-1"></i>Kembali
                                </a>
                                <button type="submit" class="btn btn-primary-custom px-4 py-2" id="submitBtn">
                                    <i class="fas fa-paper-plane me-1"></i>Kirim Pendaftaran
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Box -->
            <div class="alert alert-info mt-4">
                <div class="d-flex align-items-start">
                    <i class="fas fa-info-circle me-3 fa-lg mt-1"></i>
                    <div>
                        <h6 class="mb-2"><strong>Informasi Penting</strong></h6>
                        <ul class="mb-0 ps-3 small">
                            <li>Status pendaftaran: <span class="badge bg-warning">PENDING</span></li>
                            <li>Admin akan menghubungi via WhatsApp untuk konfirmasi</li>
                            <li>Pastikan nomor WhatsApp aktif dan dapat dihubungi</li>
                            <li>Simpan nomor pendaftaran untuk tracking status</li>
                            <li>Form akan diverifikasi oleh admin dalam 1-3 hari kerja</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    :root {
        --primary-red: #dc3545;
        --primary-red-light: #fff5f5;
        --success-green: #28a745;
        --warning-yellow: #ffc107;
        --danger-red: #dc3545;
        --light-gray: #f8f9fa;
        --border-color: #dee2e6;
        --shadow-sm: 0 2px 4px rgba(0,0,0,0.1);
        --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
        --shadow-lg: 0 10px 25px rgba(0,0,0,0.1);
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
    }

    /* Progress Bar */
    .progress {
        background-color: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-bar {
        background: linear-gradient(90deg, var(--primary-red), #ff6b6b);
        transition: width 0.6s ease;
    }

    /* Steps Indicator Mobile - Enhanced */
    .steps-indicator {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0.5rem;
        position: relative;
        background: white;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
    }

    .steps-indicator::before {
        content: '';
        position: absolute;
        top: 2.5rem;
        left: 10%;
        right: 10%;
        height: 2px;
        background: #e9ecef;
        z-index: 1;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 2;
        flex: 1;
        padding: 0.25rem;
        cursor: pointer;
        transition: all 0.3s ease;
        border-radius: var(--radius-sm);
    }

    .step:hover {
        background: var(--primary-red-light);
    }

    .step:active {
        transform: scale(0.95);
    }

    .step-icon {
        width: 3rem;
        height: 3rem;
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
    }

    .step.active .step-icon {
        background: var(--primary-red);
        border-color: var(--primary-red);
        color: white;
        transform: scale(1.1);
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
    }

    .step-label {
        font-size: 0.7rem;
        font-weight: 500;
        color: #6c757d;
        text-align: center;
        line-height: 1.2;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .step.active .step-label {
        color: var(--primary-red);
        font-weight: 600;
    }

    /* Section Styling */
    .section-wrapper {
        background: #fff;
        border-radius: var(--radius-md);
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        margin-bottom: 1.5rem;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .section-wrapper:target {
        border-color: var(--primary-red);
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
    }

    @media (max-width: 768px) {
        .section-wrapper {
            padding: 1.25rem;
            margin-bottom: 1.25rem;
        }
    }

    .section-header {
        margin-bottom: 1.5rem;
    }

    .section-title {
        color: var(--primary-red);
        font-weight: 600;
        font-size: 1.25rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
    }

    .section-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        background: var(--primary-red);
        color: white;
        border-radius: 50%;
        font-size: 0.875rem;
        font-weight: 600;
        margin-right: 0.75rem;
    }

    .section-divider {
        height: 3px;
        background: linear-gradient(90deg, var(--primary-red) 0%, rgba(204, 0, 0, 0.2) 100%);
        border-radius: 3px;
        width: 100%;
    }

    /* Form Group Spacing */
    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

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
        padding: 0.75rem;
        font-size: 0.9375rem;
        transition: all 0.2s ease;
        width: 100%;
        background: #fff;
    }

    .form-control-custom:focus {
        border-color: var(--primary-red);
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.1);
        outline: none;
    }

    .form-control-custom.is-invalid {
        border-color: var(--danger-red);
    }

    .form-text.text-muted {
        font-size: 0.75rem;
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
    }

    /* Gender Selector */
    .gender-selector {
        display: flex;
        gap: 1rem;
        margin-top: 0.5rem;
    }

    @media (max-width: 576px) {
        .gender-selector {
            flex-direction: column;
            gap: 0.5rem;
        }
    }

    .gender-option {
        flex: 1;
    }

    .gender-option input {
        display: none;
    }

    .gender-option label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        border: 2px solid var(--border-color);
        border-radius: var(--radius-sm);
        background: white;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
        height: 100%;
    }

    .gender-option label i {
        font-size: 2rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }

    .gender-option label span {
        font-weight: 500;
        color: #495057;
    }

    .gender-option input:checked + label {
        border-color: var(--primary-red);
        background: var(--primary-red-light);
    }

    .gender-option input:checked + label i {
        color: var(--primary-red);
    }

    /* Education Type Selector */
    .education-type-selector {
        margin-top: 0.5rem;
    }

    .education-option {
        border: 2px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
        height: 100%;
        position: relative;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    @media (max-width: 768px) {
        .education-option {
            flex-direction: column;
            text-align: center;
            padding: 1.25rem;
        }
    }

    .education-option:hover {
        border-color: var(--primary-red);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .education-option.selected {
        border-color: var(--primary-red);
        background: var(--primary-red-light);
        box-shadow: var(--shadow-sm);
    }

    .education-icon {
        font-size: 2.5rem;
        color: var(--primary-red);
        flex-shrink: 0;
    }

    .education-content h6 {
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: #333;
    }

    .education-content p {
        font-size: 0.875rem;
        color: #6c757d;
        margin: 0;
        line-height: 1.4;
    }

    /* Pendidikan Fields */
    .pendidikan-fields {
        background: var(--light-gray);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        margin-top: 1.5rem;
        border: 1px solid var(--border-color);
    }

    @media (max-width: 768px) {
        .pendidikan-fields {
            padding: 1.25rem;
        }
    }

    .subsection-title {
        color: #495057;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--primary-red);
    }

    /* Character Counter */
    .char-counter {
        text-align: right;
        font-size: 0.75rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }

    /* Duration Card */
    .duration-card {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: linear-gradient(135deg, #e3f2fd, #bbdefb);
        border-radius: var(--radius-md);
        border: 1px solid #bbdefb;
    }

    @media (max-width: 576px) {
        .duration-card {
            flex-direction: column;
            text-align: center;
        }
        
        .duration-icon {
            margin-right: 0;
            margin-bottom: 0.75rem;
        }
    }

    .duration-icon {
        font-size: 1.5rem;
        color: #2196f3;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .duration-content h6 {
        font-weight: 600;
        color: #0d47a1;
        margin-bottom: 0.25rem;
    }

    .duration-content p {
        font-size: 0.875rem;
        color: #0c5460;
        margin: 0;
    }

    /* File Upload */
    .file-upload-area {
        border: 2px dashed var(--border-color);
        border-radius: var(--radius-md);
        padding: 2rem 1rem;
        text-align: center;
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .file-upload-area:hover {
        border-color: var(--primary-red);
        background: var(--primary-red-light);
    }

    .file-upload-area.is-invalid {
        border-color: var(--danger-red);
        background: #f8d7da;
    }

    .file-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 2;
    }

    .upload-content {
        position: relative;
        z-index: 1;
    }

    .upload-content h6 {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #495057;
    }

    .upload-content p {
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }

    .file-preview {
        display: none;
        margin-top: 1rem;
        padding: 1rem;
        background: white;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border-color);
        text-align: left;
    }

    .file-preview.show {
        display: block;
    }

    .file-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    @media (max-width: 576px) {
        .file-info {
            flex-direction: column;
            text-align: center;
            gap: 0.5rem;
        }
    }

    .file-icon {
        font-size: 2rem;
        color: var(--primary-red);
        flex-shrink: 0;
    }

    .file-details {
        flex-grow: 1;
        min-width: 0;
    }

    .file-name {
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
        word-break: break-all;
        color: #333;
    }

    .file-size {
        font-size: 0.75rem;
        color: #6c757d;
    }

    .file-remove {
        color: var(--danger-red);
        background: none;
        border: none;
        padding: 0.25rem 0.5rem;
        cursor: pointer;
        font-size: 1rem;
    }

    /* Terms Card */
    .terms-card {
        border: 2px solid var(--primary-red);
        background: var(--primary-red-light);
        border-radius: var(--radius-md);
    }

    .terms-card .card-body {
        padding: 1.5rem;
    }

    @media (max-width: 768px) {
        .terms-card .card-body {
            padding: 1.25rem;
        }
    }

    .terms-card .form-check-label {
        line-height: 1.6;
        color: #495057;
        font-size: 0.875rem;
    }

    .terms-card .form-check-input:checked {
        background-color: var(--primary-red);
        border-color: var(--primary-red);
    }

    /* Form Actions */
    .form-actions {
        padding-top: 1.5rem;
        border-top: 2px solid var(--border-color);
        margin-top: 1.5rem;
    }

    /* Buttons */
    .btn {
        padding: 0.625rem 1.25rem;
        border-radius: var(--radius-sm);
        font-weight: 500;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary-red), #c82333);
        color: white;
        border: none;
        font-weight: 600;
        padding: 0.75rem 2rem;
    }

    .btn-primary-custom:hover {
        background: linear-gradient(135deg, #c82333, #bd2130);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
    }

    .btn-outline-custom {
        background: white;
        color: var(--primary-red);
        border: 2px solid var(--primary-red);
        font-weight: 500;
    }

    .btn-outline-custom:hover {
        background: var(--primary-red-light);
        color: var(--primary-red);
        border-color: var(--primary-red);
        transform: translateY(-2px);
    }

    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .card-body {
            padding: 1.25rem !important;
        }
        
        .section-title {
            font-size: 1.1rem;
        }
        
        .section-number {
            width: 1.75rem;
            height: 1.75rem;
            font-size: 0.8125rem;
        }
        
        .btn-primary-custom,
        .btn-outline-custom {
            width: 100%;
            justify-content: center;
        }
        
        .form-actions .d-flex {
            flex-direction: column;
            gap: 1rem;
        }
        
        .form-actions .btn {
            width: 100%;
        }
    }

    @media (max-width: 576px) {
        .hero-title {
            font-size: 1.5rem;
        }
        
        .section-wrapper {
            padding: 1rem;
        }
        
        .row.g-3 {
            margin-left: -0.5rem;
            margin-right: -0.5rem;
        }
        
        .row.g-3 > [class*="col-"] {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        
        .form-control-custom {
            padding: 0.625rem;
            font-size: 0.875rem;
        }
        
        .terms-card .card-body {
            padding: 1rem;
        }
        
        .alert .d-flex {
            flex-direction: column;
            text-align: center;
        }
        
        .alert .d-flex i {
            margin-bottom: 1rem;
            margin-right: 0;
        }
        
        /* Adjust steps indicator for very small screens */
        .steps-indicator {
            padding: 0.75rem 0.25rem;
        }
        
        .step-icon {
            width: 2.5rem;
            height: 2.5rem;
            font-size: 1rem;
        }
        
        .step-label {
            font-size: 0.65rem;
        }
    }

    /* Input group */
    .input-group {
        position: relative;
        display: flex;
        flex-wrap: wrap;
        align-items: stretch;
        width: 100%;
    }

    .input-group-text {
        display: flex;
        align-items: center;
        padding: 0.75rem 0.75rem;
        font-size: 0.9375rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        text-align: center;
        white-space: nowrap;
        background-color: #e9ecef;
        border: 1px solid var(--border-color);
        border-right: none;
        border-radius: var(--radius-sm) 0 0 var(--radius-sm);
    }

    .input-group .form-control-custom {
        border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
        border-left: none;
    }

    /* Animation for alerts */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert {
        animation: fadeInDown 0.3s ease;
    }

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

    /* Focus states for accessibility */
    .form-control-custom:focus,
    .btn:focus,
    .form-check-input:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.25);
    }

    /* Print styles */
    @media print {
        .btn,
        .steps-indicator,
        .form-actions {
            display: none !important;
        }
        
        .section-wrapper {
            border: 1px solid #ddd;
            box-shadow: none;
            page-break-inside: avoid;
        }
    }

    /* Section scroll animation */
    .section-wrapper {
        scroll-margin-top: 100px;
    }

    @media (max-width: 768px) {
        .section-wrapper {
            scroll-margin-top: 130px;
        }
    }

    /* Active step indicator animation */
    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
        }
    }

    .step.active .step-icon {
        animation: pulse 2s infinite;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Scroll to section function
        window.scrollToSection = function(sectionId) {
            const element = document.getElementById(sectionId);
            if (element) {
                const headerOffset = 100; // Offset for mobile header
                const elementPosition = element.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });

                // Highlight the section briefly
                element.style.transition = 'all 0.3s ease';
                element.style.borderColor = 'var(--primary-red)';
                element.style.boxShadow = '0 0 0 3px rgba(220, 53, 69, 0.1)';

                setTimeout(() => {
                    element.style.borderColor = '';
                    element.style.boxShadow = '';
                }, 1500);

                // Update active step
                const sectionNum = sectionId.replace('section-', '');
                updateStepIndicatorMobile(sectionNum);
            }
        };

        // Update step indicator mobile
        function updateStepIndicatorMobile(stepNum) {
            $('.step').removeClass('active');
            $(`.step[data-step="${stepNum}"]`).addClass('active');
        }

        // Toggle SMK/Univ fields
        function togglePendidikanFields() {
            const jenis = $('input[name="jenis_pendidikan"]:checked').val();
            
            $('#smk-fields, #univ-fields').addClass('d-none');
            
            if (jenis === 'smk') {
                $('#smk-fields').removeClass('d-none');
            } else if (jenis === 'universitas') {
                $('#univ-fields').removeClass('d-none');
            }
        }

        // Select education type
        window.selectEducationType = function(type) {
            $(`.education-option`).removeClass('selected');
            $(`.education-option:has(input[value="${type}"])`).addClass('selected');
            $(`input[name="jenis_pendidikan"][value="${type}"]`).prop('checked', true);
            togglePendidikanFields();
        }

        // Initialize education type
        const initialType = $('input[name="jenis_pendidikan"]:checked').val() || 'smk';
        selectEducationType(initialType);

        // Date validation and duration calculation
        $('#tanggal_mulai').on('change', function() {
            const startDate = $(this).val();
            $('#tanggal_selesai').attr('min', startDate);
            
            if ($('#tanggal_selesai').val() && $('#tanggal_selesai').val() < startDate) {
                $('#tanggal_selesai').val(startDate);
            }
            calculateDuration();
        });

        $('#tanggal_selesai').on('change', calculateDuration);

        function calculateDuration() {
            const start = $('#tanggal_mulai').val();
            const end = $('#tanggal_selesai').val();
            
            if (start && end) {
                const startDate = new Date(start);
                const endDate = new Date(end);
                const diffTime = Math.abs(endDate - startDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                
                const months = Math.floor(diffDays / 30);
                const weeks = Math.floor(diffDays / 7);
                const remainingDays = diffDays % 30;
                
                let message = `Durasi: ${diffDays} hari`;
                if (months > 0) {
                    message += ` (${months} bulan${remainingDays > 0 ? ` ${remainingDays} hari` : ''})`;
                } else if (weeks > 0) {
                    const daysRemaining = diffDays % 7;
                    message += ` (${weeks} minggu${daysRemaining > 0 ? ` ${daysRemaining} hari` : ''})`;
                }
                
                $('#durasi_info').html(message);
            }
        }

        // Character counters
        function setupCharacterCounter(textareaId, counterId, maxLength) {
            $(textareaId).on('input', function() {
                const length = $(this).val().length;
                $(counterId).text(length);
                
                if (length > maxLength) {
                    $(this).val($(this).val().substring(0, maxLength));
                    $(counterId).text(maxLength);
                }
            });
            
            // Initial count
            const initialLength = $(textareaId).val().length;
            $(counterId).text(initialLength);
        }

        setupCharacterCounter('#alasan_pkl_gi', '#alasan_counter', 500);
        setupCharacterCounter('#skill_ingin_dipelajari', '#skill_counter', 300);
        setupCharacterCounter('#harapan_setelah_pkl', '#harapan_counter', 300);
        setupCharacterCounter('#pengalaman_sebelumnya', '#pengalaman_counter', 200);

        // Format phone numbers
        $('input[id*="no_hp"], input[id*="whatsapp"]').on('input', function() {
            let value = $(this).val().replace(/\D/g, '');
            
            if (value.startsWith('0')) {
                value = value.substring(1);
            }
            
            $(this).val(value.substring(0, 13));
        });

        // File upload preview
        $('#upload_surat_pengantar').on('change', function(e) {
            const file = e.target.files[0];
            const preview = $('#filePreview');
            const uploadContent = $('.upload-content');
            
            if (file) {
                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file maksimal 2MB');
                    $(this).val('');
                    preview.removeClass('show').empty();
                    uploadContent.show();
                    return;
                }
                
                // Validate file type
                const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Format file harus PDF, JPG, JPEG, atau PNG');
                    $(this).val('');
                    preview.removeClass('show').empty();
                    uploadContent.show();
                    return;
                }
                
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                const fileName = file.name.length > 30 ? file.name.substring(0, 27) + '...' : file.name;
                const fileType = file.type;
                
                let icon = 'fas fa-file';
                if (fileType === 'application/pdf') {
                    icon = 'fas fa-file-pdf text-danger';
                } else if (fileType.includes('image')) {
                    icon = 'fas fa-file-image text-success';
                }
                
                preview.html(`
                    <div class="file-info">
                        <i class="${icon} file-icon"></i>
                        <div class="file-details">
                            <div class="file-name">${fileName}</div>
                            <div class="file-size">${fileSize} MB</div>
                        </div>
                        <button type="button" class="file-remove" onclick="removeFile()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `);
                
                preview.addClass('show');
                uploadContent.hide();
            }
        });

        // Remove file function
        window.removeFile = function() {
            $('#upload_surat_pengantar').val('');
            $('#filePreview').removeClass('show').empty();
            $('.upload-content').show();
        }

        // Form validation
        $('#registrationForm').on('submit', function(e) {
            // Validate age
            const birthDate = new Date($('#tanggal_lahir').val());
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            
            if (age < 15) {
                e.preventDefault();
                showAlert('error', 'Anda harus berusia minimal 15 tahun untuk mendaftar PKL');
                $('#tanggal_lahir').focus();
                return false;
            }
            
            // Validate dates
            const start = $('#tanggal_mulai').val();
            const end = $('#tanggal_selesai').val();
            
            if (start && end) {
                const startDate = new Date(start);
                const endDate = new Date(end);
                
                if (endDate < startDate) {
                    e.preventDefault();
                    showAlert('error', 'Tanggal selesai tidak boleh sebelum tanggal mulai');
                    $('#tanggal_selesai').focus();
                    return false;
                }
                
                const diffTime = Math.abs(endDate - startDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                
                if (diffDays < 7) {
                    e.preventDefault();
                    showAlert('error', 'Durasi PKL minimal 1 minggu (7 hari)');
                    return false;
                }
            }
            
            // Validate required fields based on pendidikan type
            const jenisPendidikan = $('input[name="jenis_pendidikan"]:checked').val();
            if (jenisPendidikan === 'smk') {
                const requiredFields = ['sekolah', 'jurusan_smk', 'kelas', 'nis', 'guru_pembimbing', 'no_hp_guru'];
                for (const field of requiredFields) {
                    if (!$(`#${field}`).val().trim()) {
                        e.preventDefault();
                        showAlert('error', `Harap lengkapi data ${field.replace('_', ' ')}`);
                        $(`#${field}`).focus();
                        return false;
                    }
                }
            } else if (jenisPendidikan === 'universitas') {
                const requiredFields = ['universitas', 'jurusan_univ', 'semester', 'nim', 'dosen_pembimbing', 'no_hp_dosen'];
                for (const field of requiredFields) {
                    if (!$(`#${field}`).val().trim()) {
                        e.preventDefault();
                        showAlert('error', `Harap lengkapi data ${field.replace('_', ' ')}`);
                        $(`#${field}`).focus();
                        return false;
                    }
                }
            }
            
            // Validate file upload
            if (!$('#upload_surat_pengantar').val()) {
                e.preventDefault();
                showAlert('error', 'Harap upload surat pengantar');
                return false;
            }
            
            // Show loading state
            const submitBtn = $('#submitBtn');
            submitBtn.addClass('btn-loading').prop('disabled', true);
            
            return true;
        });

        // Show alert function
        function showAlert(type, message) {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'check-circle'} me-3 fs-4"></i>
                        <div>${message}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            $('.card-body').prepend(alertHtml);
            
            setTimeout(() => {
                $('.alert').alert('close');
            }, 5000);
        }

        // Auto-focus first invalid field
        $('.is-invalid').first().focus();

        // Scroll to error fields on mobile
        if ($(window).width() < 768 && $('.is-invalid').length > 0) {
            $('html, body').animate({
                scrollTop: $('.is-invalid').first().offset().top - 150
            }, 500);
        }

        // Update step indicator on scroll (for mobile)
        if ($(window).width() < 768) {
            $(window).on('scroll', function() {
                const sections = $('[data-section]');
                const scrollTop = $(window).scrollTop();
                const windowHeight = $(window).height();
                
                let currentSection = 1;
                
                sections.each(function() {
                    const sectionTop = $(this).offset().top;
                    const sectionHeight = $(this).outerHeight();
                    
                    if (scrollTop >= sectionTop - (windowHeight / 3)) {
                        currentSection = $(this).data('section');
                    }
                });
                
                updateStepIndicatorMobile(currentSection);
            });
        }

        // Add click handlers for step indicators
        $('.step').on('click', function() {
            const stepNum = $(this).data('step');
            scrollToSection(`section-${stepNum}`);
        });

        // Initialize scroll position for mobile
        if ($(window).width() < 768 && window.location.hash) {
            setTimeout(() => {
                const hash = window.location.hash;
                if (hash.startsWith('#section-')) {
                    scrollToSection(hash.substring(1));
                }
            }, 100);
        }

        // Touch feedback for step indicators
        $('.step').on('touchstart', function() {
            $(this).css('background-color', 'rgba(220, 53, 69, 0.1)');
        });

        $('.step').on('touchend', function() {
            $(this).css('background-color', '');
        });
    });
</script>
@endpush

@endsection