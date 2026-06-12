@extends('layouts.app')

@section('title', 'Detail Pendaftaran')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-4">
                <div class="d-inline-block p-3 bg-white rounded-circle shadow-sm mb-3">
                    <i class="fas fa-file-alt fa-3x" style="color: #dc3545;"></i>
                </div>
                <h1 class="fw-bold mb-2" style="color: #dc3545;">Detail Pendaftaran</h1>
                <p class="text-muted">
                    @if($tipe == 'individu')
                        Data pendaftaran individu atas nama <strong class="text-dark">{{ $data->nama_lengkap }}</strong>
                    @else
                        Data pendaftaran kelompok <strong class="text-dark">{{ $data->nama_kelompok }}</strong>
                    @endif
                </p>
            </div>

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
                <div class="bg-danger text-white p-3" style="background: linear-gradient(135deg, #dc3545, #b30000);">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div>
                            <h5 class="mb-0 fw-bold">
                                <i class="fas {{ $tipe == 'individu' ? 'fa-user' : 'fa-users' }} me-2"></i>
                                {{ $tipe == 'individu' ? 'Pendaftaran Individu' : 'Pendaftaran Kelompok' }}
                            </h5>
                            <small class="opacity-75">Status terkini pendaftaran Anda</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-white text-danger px-3 py-2 rounded-pill">
                                <i class="fas fa-calendar-alt me-1"></i>
                                {{ \Carbon\Carbon::parse($data->created_at)->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-3 p-md-4">
                    @php
                        $statusText = match($data->status ?? '') {
                            'pending' => 'Menunggu Verifikasi',
                            'diterima' => 'Diterima',
                            'ditolak' => 'Tidak Diterima',
                            default => $data->status ?? 'Tidak Diketahui'
                        };
                        $statusClass = $data->status ?? '';
                        
                        $catatanAdmin = $tipe == 'individu' 
                            ? ($data->catatan_admin ?? null) 
                            : ($data->catatan_admin ?? null);
                    @endphp

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 border">
                                <small class="text-muted text-uppercase tracking-wide">Kode Pendaftaran</small>
                                <div class="d-flex flex-wrap justify-content-between align-items-center mt-1 gap-2">
                                    <span class="fs-5 fs-md-4 fw-bold font-monospace text-success" id="kodePendaftaran">{{ $data->kode_pendaftaran }}</span>
                                    <button class="btn btn-sm btn-outline-success" onclick="copyKode()">
                                        <i class="fas fa-copy me-1"></i> Salin
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 border">
                                <small class="text-muted text-uppercase tracking-wide">Status Pendaftaran</small>
                                <div class="mt-1">
                                    @if($statusClass == 'pending')
                                        <div class="d-inline-block px-3 py-2 bg-warning bg-opacity-10 text-warning rounded-3 w-100 w-md-auto text-center text-md-start">
                                            <i class="fas fa-clock me-2"></i>
                                            <span class="fw-semibold">{{ $statusText }}</span>
                                        </div>
                                    @elseif($statusClass == 'diterima')
                                        <div class="d-inline-block px-3 py-2 bg-success bg-opacity-10 text-success rounded-3 w-100 w-md-auto text-center text-md-start">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <span class="fw-semibold">{{ $statusText }}</span>
                                        </div>
                                    @elseif($statusClass == 'ditolak')
                                        <div class="d-inline-block px-3 py-2 bg-danger bg-opacity-10 text-danger rounded-3 w-100 w-md-auto text-center text-md-start">
                                            <i class="fas fa-times-circle me-2"></i>
                                            <span class="fw-semibold">{{ $statusText }}</span>
                                        </div>
                                    @else
                                        <div class="d-inline-block px-3 py-2 bg-secondary bg-opacity-10 text-secondary rounded-3 w-100 w-md-auto text-center text-md-start">
                                            <i class="fas fa-circle me-2"></i>
                                            <span class="fw-semibold">{{ $statusText }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($catatanAdmin)
                    <div class="mb-4 animate__animated animate__fadeIn">
                        @if($statusClass == 'diterima')
                        <div class="p-3 bg-success bg-opacity-10 rounded-3 border-start border-success border-4">
                            <div class="d-flex gap-3">
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-2 text-success">Catatan Admin - Diterima</h6>
                                    <p class="mb-0">{{ nl2br(e($catatanAdmin)) }}</p>
                                </div>
                            </div>
                        </div>
                        @elseif($statusClass == 'ditolak')
                        <div class="p-3 bg-danger bg-opacity-10 rounded-3 border-start border-danger border-4">
                            <div class="d-flex gap-3">
                                <i class="fas fa-times-circle fa-2x text-danger"></i>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-2 text-danger">Catatan Admin - Tidak Diterima</h6>
                                    <p class="mb-0">{{ nl2br(e($catatanAdmin)) }}</p>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="p-3 bg-info bg-opacity-10 rounded-3 border-start border-info border-4">
                            <div class="d-flex gap-3">
                                <i class="fas fa-sticky-note fa-2x text-info"></i>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-2">Catatan dari Admin</h6>
                                    <p class="mb-0">{{ nl2br(e($catatanAdmin)) }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    @if($tipe == 'individu')
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3 pb-2 border-bottom border-danger" style="color: #dc3545;">
                                <i class="fas fa-user-circle me-2"></i>Data Pribadi
                            </h6>
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
                                    <span class="data-label"><i class="fas fa-map-marker-alt me-1"></i>Tempat Lahir</span>
                                    <span class="data-value">{{ $data->tempat_lahir }}</span>
                                </div>
                                <div class="data-item">
                                    <span class="data-label"><i class="fas fa-calendar-day me-1"></i>Tanggal Lahir</span>
                                    <span class="data-value">{{ \Carbon\Carbon::parse($data->tanggal_lahir)->format('d F Y') }}</span>
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
                                    <span class="data-label"><i class="fas fa-home me-1"></i>Alamat Lengkap</span>
                                    <span class="data-value">{{ $data->alamat_lengkap }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold mb-3 pb-2 border-bottom border-danger" style="color: #dc3545;">
                                <i class="fas fa-graduation-cap me-2"></i>Data Pendidikan
                            </h6>
                            <div class="data-list">
                                <div class="data-item">
                                    <span class="data-label">Jenis Pendidikan</span>
                                    <span class="data-value">
                                        <span class="badge bg-primary px-3 py-2">{{ $data->jenis_pendidikan == 'smk' ? 'SMK' : 'Kuliah' }}</span>
                                    </span>
                                </div>
                                @if($data->jenis_pendidikan == 'smk')
                                <div class="data-item">
                                    <span class="data-label">Kelas</span>
                                    <span class="data-value">{{ $data->kelas ?? '-' }}</span>
                                </div>
                                <div class="data-item">
                                    <span class="data-label">Nama Sekolah</span>
                                    <span class="data-value">{{ $data->sekolah ?? '-' }}</span>
                                </div>
                                <div class="data-item">
                                    <span class="data-label">Jurusan</span>
                                    <span class="data-value">{{ $data->jurusan_smk ?? '-' }}</span>
                                </div>
                                <div class="data-item">
                                    <span class="data-label">NIS</span>
                                    <span class="data-value">{{ $data->nis ?? '-' }}</span>
                                </div>
                                <div class="data-item">
                                    <span class="data-label">Guru Pembimbing</span>
                                    <span class="data-value">
                                        {{ $data->guru_pembimbing ?? '-' }}
                                        @if($data->no_hp_guru)
                                            <small class="d-block text-muted mt-1"><i class="fab fa-whatsapp me-1"></i> {{ $data->no_hp_guru }}</small>
                                        @endif
                                    </span>
                                </div>
                                @else
                                <div class="data-item">
                                    <span class="data-label">Semester</span>
                                    <span class="data-value">{{ $data->semester ?? '-' }}</span>
                                </div>
                                <div class="data-item">
                                    <span class="data-label">Nama Kampus</span>
                                    <span class="data-value">{{ $data->kuliah ?? '-' }}</span>
                                </div>
                                <div class="data-item">
                                    <span class="data-label">Jurusan</span>
                                    <span class="data-value">{{ $data->jurusan_kuliah ?? '-' }}</span>
                                </div>
                                <div class="data-item">
                                    <span class="data-label">NIM</span>
                                    <span class="data-value">{{ $data->nim ?? '-' }}</span>
                                </div>
                                <div class="data-item">
                                    <span class="data-label">Dosen Pembimbing</span>
                                    <span class="data-value">
                                        {{ $data->dosen_pembimbing ?? '-' }}
                                        @if($data->no_hp_dosen)
                                            <small class="d-block text-muted mt-1"><i class="fab fa-whatsapp me-1"></i> {{ $data->no_hp_dosen }}</small>
                                        @endif
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3 pb-2 border-bottom border-danger" style="color: #dc3545;">
                                <i class="fas fa-users me-2"></i>Data Kelompok
                            </h6>
                            <div class="data-list">
                                <div class="data-item">
                                    <span class="data-label">Nama Kelompok</span>
                                    <span class="data-value fw-bold">{{ $data->nama_kelompok }}</span>
                                </div>
                                <div class="data-item">
                                    <span class="data-label">Institusi</span>
                                    <span class="data-value">{{ $data->institusi }}</span>
                                </div>
                                <div class="data-item">
                                    <span class="data-label">Jumlah Anggota</span>
                                    <span class="data-value">
                                        @php
                                            $anggotaCount = $data->anggota_count ?? $data->anggota->count() ?? 0;
                                        @endphp
                                        <span class="badge bg-info px-3 py-2">{{ $anggotaCount }} / {{ $data->jumlah_anggota }}</span>
                                    </span>
                                </div>
                                <div class="data-item">
                                    <span class="data-label">Jenis Pendidikan</span>
                                    <span class="data-value">
                                        <span class="badge bg-primary px-3 py-2">{{ $data->perwakilan_jenis_pendidikan == 'smk' ? 'SMK' : 'Kuliah' }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold mb-3 pb-2 border-bottom border-danger" style="color: #dc3545;">
                                <i class="fas fa-user-tie me-2"></i>Data Perwakilan Kelompok
                            </h6>
                            <div class="data-list">
                                <div class="data-item">
                                    <span class="data-label">Nama Perwakilan</span>
                                    <span class="data-value">{{ $data->perwakilan_nama }}</span>
                                </div>
                                <div class="data-item">
                                    <span class="data-label">Jenis Kelamin</span>
                                    <span class="data-value">{{ $data->perwakilan_jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                </div>
                                <div class="data-item">
                                    <span class="data-label">Tempat, Tanggal Lahir</span>
                                    <span class="data-value">
                                        {{ $data->perwakilan_tempat_lahir }}, 
                                        {{ $data->perwakilan_tanggal_lahir ? \Carbon\Carbon::parse($data->perwakilan_tanggal_lahir)->format('d F Y') : '-' }}
                                    </span>
                                </div>
                                <div class="data-item">
                                    <span class="data-label">Email</span>
                                    <span class="data-value">{{ $data->perwakilan_email }}</span>
                                </div>
                                <div class="data-item">
                                    <span class="data-label">WhatsApp</span>
                                    <span class="data-value">{{ $data->perwakilan_wa }}</span>
                                </div>
                                <div class="data-item">
                                    <span class="data-label">NIM / NIS</span>
                                    <span class="data-value">{{ $data->perwakilan_nim ?? $data->perwakilan_nis ?? '-' }}</span>
                                </div>
                                <div class="data-item">
                                    <span class="data-label">Alamat Perwakilan</span>
                                    <span class="data-value">{{ $data->perwakilan_alamat }}</span>
                                </div>
                            </div>
                        </div>

                        @if(isset($data->anggota) && $data->anggota->count() > 0)
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3 pb-2 border-bottom border-danger" style="color: #dc3545;">
                                <i class="fas fa-users me-2"></i>Daftar Anggota Kelompok
                                <span class="badge bg-secondary ms-2">{{ $data->anggota->count() }} Anggota</span>
                            </h6>
                            <div class="anggota-list">
                                @foreach($data->anggota as $index => $anggota)
                                <div class="anggota-item">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                        <div class="fw-bold fs-6">{{ $anggota->nama }}</div>
                                        @if($anggota->is_perwakilan)
                                            <span class="badge bg-danger">Perwakilan</span>
                                        @else
                                            <span class="badge bg-secondary">Anggota</span>
                                        @endif
                                    </div>
                                    <div class="small text-muted mt-1">
                                        <i class="fas fa-id-card me-1"></i> {{ $anggota->nim_nis }}
                                    </div>
                                    <div class="small text-muted">
                                        <i class="fas fa-envelope me-1"></i> {{ $anggota->email }}
                                    </div>
                                    <div class="small text-muted">
                                        <i class="fab fa-whatsapp me-1"></i> {{ $anggota->telepon }}
                                    </div>
                                    @if($anggota->tempat_lahir)
                                    <div class="small text-muted mt-1">
                                        <i class="fas fa-map-marker-alt me-1"></i> {{ $anggota->tempat_lahir }}
                                        @if($anggota->tanggal_lahir)
                                            , {{ \Carbon\Carbon::parse($anggota->tanggal_lahir)->format('d/m/Y') }}
                                        @endif
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endif

                    <div class="mb-4">
                        <h6 class="fw-bold mb-3 pb-2 border-bottom border-danger" style="color: #dc3545;">
                            <i class="fas fa-calendar-alt me-2"></i>Periode
                        </h6>
                        <div class="data-list">
                            <div class="data-item">
                                <span class="data-label"><i class="fas fa-play text-success me-1"></i>Tanggal Mulai</span>
                                <span class="data-value">{{ \Carbon\Carbon::parse($data->tanggal_mulai)->format('d F Y') }}</span>
                            </div>
                            <div class="data-item">
                                <span class="data-label"><i class="fas fa-flag-checkered text-danger me-1"></i>Tanggal Selesai</span>
                                <span class="data-value">{{ \Carbon\Carbon::parse($data->tanggal_selesai)->format('d F Y') }}</span>
                            </div>
                            <div class="data-item">
                                <span class="data-label"><i class="fas fa-hourglass-half text-warning me-1"></i>Durasi</span>
                                <span class="data-value">
                                    @php
                                        $start = \Carbon\Carbon::parse($data->tanggal_mulai);
                                        $end = \Carbon\Carbon::parse($data->tanggal_selesai);
                                        $days = $start->diffInDays($end) + 1;
                                        $weeks = floor($days / 7);
                                        $months = floor($days / 30);
                                    @endphp
                                    {{ $days }} hari
                                    @if($weeks > 0) ({{ $weeks }} minggu) @endif
                                    @if($months > 0) ({{ $months }} bulan) @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold mb-3 pb-2 border-bottom border-danger" style="color: #dc3545;">
                            <i class="fas fa-bullseye me-2"></i>Motivasi & Harapan
                        </h6>
                        <div class="data-list">
                            <div class="data-item">
                                <span class="data-label"><i class="fas fa-question-circle me-1"></i>Alasan memilih Global Intermedia</span>
                                <span class="data-value">{{ $data->alasan_pkl_gi }}</span>
                            </div>
                            <div class="data-item">
                                <span class="data-label"><i class="fas fa-code me-1"></i>Skill yang ingin dipelajari</span>
                                <span class="data-value">{{ $data->skill_ingin_dipelajari }}</span>
                            </div>
                            <div class="data-item">
                                <span class="data-label"><i class="fas fa-star me-1"></i>Harapan setelah selesai</span>
                                <span class="data-value">{{ $data->harapan_setelah_pkl }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- ==================== DOKUMEN PENDUKUNG ==================== --}}
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3 pb-2 border-bottom border-danger" style="color: #dc3545;">
                            <i class="fas fa-file-upload me-2"></i>Dokumen Pendukung
                        </h6>
                        
                        @php
                            $hasSurat = ($tipe == 'individu' && $data->file_surat_pengantar) || ($tipe == 'kelompok' && $data->upload_surat_pengantar);
                            $suratFile = $tipe == 'individu' ? $data->file_surat_pengantar : $data->upload_surat_pengantar;
                            $hasCv = ($tipe == 'individu' && !empty($data->cv_ind)) || ($tipe == 'kelompok' && !empty($data->perwakilan_cv));
                            $hasAnyDoc = $hasSurat || $hasCv;
                        @endphp
                        
                        @if(!$hasAnyDoc)
                            <div class="p-3 bg-light rounded-3 text-center text-muted">
                                <i class="fas fa-file-alt fa-2x mb-2 opacity-50"></i>
                                <p class="mb-0">Tidak ada dokumen yang diunggah</p>
                            </div>
                        @else
                            {{-- SURAT PENGANTAR --}}
                            @if($hasSurat && $suratFile)
                                @php
                                    $ext = strtolower(pathinfo($suratFile, PATHINFO_EXTENSION));
                                    $fileUrl = asset($suratFile);
                                    $fileName = basename($suratFile);
                                @endphp
                                <div class="dokumen-item mb-3">
                                    <div class="d-flex align-items-center gap-3 flex-wrap">
                                        @if($ext == 'pdf')
                                            <i class="fas fa-file-pdf text-danger fa-2x"></i>
                                        @elseif(in_array($ext, ['jpg', 'jpeg', 'png']))
                                            <i class="fas fa-file-image text-primary fa-2x"></i>
                                        @else
                                            <i class="fas fa-file-alt text-secondary fa-2x"></i>
                                        @endif
                                        <div class="flex-grow-1">
                                            <div class="fw-bold text-break">{{ $fileName }}</div>
                                            <small class="text-muted">Surat Pengantar {{ $tipe == 'individu' ? 'Individu' : 'Kelompok' }}</small>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a href="{{ $fileUrl }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                            <a href="{{ $fileUrl }}" download class="btn btn-outline-secondary btn-sm">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                    @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                                        <div class="mt-3 text-center">
                                            <a href="{{ $fileUrl }}" target="_blank">
                                                <img src="{{ $fileUrl }}" class="img-fluid rounded border" style="max-height: 200px;">
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            {{-- CV INDIVIDU --}}
                            @if($tipe == 'individu' && !empty($data->cv_ind))
                                @php
                                    $cvExt = strtolower(pathinfo($data->cv_ind, PATHINFO_EXTENSION));
                                    $cvUrl = asset($data->cv_ind);
                                    $cvName = basename($data->cv_ind);
                                @endphp
                                <div class="dokumen-item mt-3">
                                    <div class="d-flex align-items-center gap-3 flex-wrap">
                                        @if($cvExt == 'pdf')
                                            <i class="fas fa-file-pdf text-danger fa-2x"></i>
                                        @elseif(in_array($cvExt, ['jpg', 'jpeg', 'png']))
                                            <i class="fas fa-file-image text-primary fa-2x"></i>
                                        @else
                                            <i class="fas fa-file-alt text-secondary fa-2x"></i>
                                        @endif
                                        <div class="flex-grow-1">
                                            <div class="fw-bold text-break">{{ $cvName }}</div>
                                            <small class="text-muted">CV / Curriculum Vitae</small>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a href="{{ $cvUrl }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                            <a href="{{ $cvUrl }}" download class="btn btn-outline-secondary btn-sm">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                    @if(in_array($cvExt, ['jpg', 'jpeg', 'png']))
                                        <div class="mt-3 text-center">
                                            <a href="{{ $cvUrl }}" target="_blank">
                                                <img src="{{ $cvUrl }}" class="img-fluid rounded border" style="max-height: 200px;">
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            {{-- CV PERWAKILAN KELOMPOK --}}
                            @if($tipe == 'kelompok' && !empty($data->perwakilan_cv))
                                @php
                                    $cvExt = strtolower(pathinfo($data->perwakilan_cv, PATHINFO_EXTENSION));
                                    $cvUrl = asset($data->perwakilan_cv);
                                    $cvName = basename($data->perwakilan_cv);
                                @endphp
                                <div class="dokumen-item mt-3">
                                    <div class="d-flex align-items-center gap-3 flex-wrap">
                                        @if($cvExt == 'pdf')
                                            <i class="fas fa-file-pdf text-danger fa-2x"></i>
                                        @elseif(in_array($cvExt, ['jpg', 'jpeg', 'png']))
                                            <i class="fas fa-file-image text-primary fa-2x"></i>
                                        @else
                                            <i class="fas fa-file-alt text-secondary fa-2x"></i>
                                        @endif
                                        <div class="flex-grow-1">
                                            <div class="fw-bold text-break">{{ $cvName }}</div>
                                            <small class="text-muted">CV / Resume Perwakilan</small>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a href="{{ $cvUrl }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                            <a href="{{ $cvUrl }}" download class="btn btn-outline-secondary btn-sm">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                    {{-- ==================== END DOKUMEN ==================== --}}

                    <div class="d-flex flex-wrap gap-3 justify-content-center mt-4 pt-3 border-top">
                        <a href="{{ route('user.status') }}" class="btn btn-outline-secondary px-4 py-2 flex-grow-1 flex-md-grow-0">
                            <i class="fas fa-search me-2"></i>Cek Status Lain
                        </a>
                        <a href="{{ route('user.home') }}" class="btn btn-outline-primary px-4 py-2 flex-grow-1 flex-md-grow-0">
                            <i class="fas fa-home me-2"></i>Kembali ke Beranda
                        </a>
                        @if($tipe == 'kelompok' && $data->status == 'pending' && ($anggotaCount ?? 0) < $data->jumlah_anggota)
                        <a href="{{ route('user.kelompok.tambah-peserta', $data->id) }}" class="btn btn-success px-4 py-2 flex-grow-1 flex-md-grow-0">
                            <i class="fas fa-user-plus me-2"></i>Lanjut Tambah Anggota
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <div class="alert alert-light border">
                    <i class="fas fa-info-circle text-danger me-2"></i>
                    <small class="text-muted">
                        Jika ada pertanyaan terkait pendaftaran, silakan hubungi admin melalui WhatsApp yang terdaftar.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .tracking-wide {
        letter-spacing: 0.5px;
    }
    .border-danger {
        border-color: #dc3545 !important;
    }
    .bg-light {
        background-color: #f8f9fa !important;
    }
    .rounded-3 {
        border-radius: 12px !important;
    }
    .rounded-4 {
        border-radius: 16px !important;
    }
    .shadow-lg {
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.02) !important;
    }
    .font-monospace {
        font-family: 'Courier New', monospace;
    }
    
    .data-list {
        display: flex;
        flex-direction: column;
        gap: 1px;
        background: #dee2e6;
        border-radius: 12px;
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
        width: 35%;
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 500;
    }
    .data-value {
        width: 65%;
        font-weight: 500;
        color: #212529;
        word-break: break-word;
    }
    
    .anggota-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .anggota-item {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 12px;
        border-left: 4px solid #dc3545;
        transition: transform 0.2s;
    }
    .anggota-item:hover {
        transform: translateX(5px);
    }
    
    .dokumen-item {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 12px;
        transition: all 0.2s;
    }
    .dokumen-item:hover {
        background: #e9ecef;
    }
    
    .toast-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 280px;
        animation: slideInRight 0.3s ease;
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate__animated {
        animation-duration: 0.5s;
    }
    
    .animate__fadeIn {
        animation-name: fadeIn;
    }
    
    @media (max-width: 576px) {
        .container {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }
        .card-body {
            padding: 0.75rem !important;
        }
        .data-item {
            flex-direction: column;
            gap: 4px;
            padding: 0.75rem;
        }
        .data-label {
            width: 100%;
            font-size: 0.75rem;
        }
        .data-value {
            width: 100%;
            font-size: 0.9rem;
        }
        .fs-5 {
            font-size: 1rem !important;
        }
        .fs-md-4 {
            font-size: 1.25rem !important;
        }
        .btn {
            padding: 8px 12px !important;
            font-size: 0.85rem !important;
        }
        .badge {
            font-size: 0.75rem;
        }
        h1 {
            font-size: 1.5rem;
        }
        h5 {
            font-size: 1.1rem;
        }
        h6 {
            font-size: 1rem;
        }
        .dokumen-item .d-flex {
            flex-direction: column;
            align-items: flex-start !important;
        }
        .dokumen-item .d-flex .d-flex {
            width: 100%;
            justify-content: flex-start;
        }
        .toast-notification {
            left: 10px;
            right: 10px;
            top: 10px;
        }
    }
    
    @media (min-width: 577px) and (max-width: 768px) {
        .data-label {
            width: 30%;
        }
        .data-value {
            width: 70%;
        }
    }
    
    .w-md-auto {
        @media (min-width: 768px) {
            width: auto !important;
        }
    }
    .flex-md-grow-0 {
        @media (min-width: 768px) {
            flex-grow: 0 !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function copyKode() {
        const kodeElement = document.getElementById('kodePendaftaran');
        const kode = kodeElement.innerText;
        
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(kode).then(function() {
                showToast('Kode pendaftaran berhasil disalin!', 'success');
            }).catch(function(err) {
                console.error('Clipboard error:', err);
                fallbackCopy(kode);
            });
        } else {
            fallbackCopy(kode);
        }
    }
    
    function fallbackCopy(text) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.select();
        
        try {
            document.execCommand('copy');
            showToast('Kode pendaftaran berhasil disalin!', 'success');
        } catch (err) {
            console.error('Fallback copy error:', err);
            showToast('Gagal menyalin kode, silakan salin manual', 'error');
        }
        
        document.body.removeChild(textarea);
    }
    
    function showToast(message, type) {
        type = type || 'success';
        var existingToast = document.querySelector('.toast-notification');
        if (existingToast) existingToast.remove();
        
        var bgColor = type === 'success' ? '#28a745' : '#dc3545';
        var icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        var toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.innerHTML = '<div style="background:white;border-left:4px solid '+bgColor+';padding:12px 16px;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.15);display:flex;align-items:center;gap:12px"><i class="fas '+icon+'" style="color:'+bgColor+';font-size:1.2rem"></i><span style="color:#333;font-size:0.9rem">'+message+'</span><button onclick="this.parentElement.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:#999;padding:0;font-size:0.8rem"><i class="fas fa-times"></i></button></div>';
        
        document.body.appendChild(toast);
        
        setTimeout(function() {
            if (toast && toast.parentNode) {
                toast.style.animation = 'slideInRight 0.3s ease reverse';
                setTimeout(function() {
                    if (toast && toast.parentNode) toast.remove();
                }, 300);
            }
        }, 4000);
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                var target = document.querySelector(this.getAttribute('href'));
                if (target) target.scrollIntoView({ behavior: 'smooth' });
            });
        });
        
        var buttons = document.querySelectorAll('.btn');
        buttons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                var href = this.getAttribute('href');
                if (href && href !== '#' && !href.startsWith('javascript:')) {
                    var originalHtml = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Memuat...';
                    this.disabled = true;
                    
                    setTimeout(function(self) {
                        self.innerHTML = originalHtml;
                        self.disabled = false;
                    }, 3000, this);
                }
            });
        });
    });
    
    window.onload = function() {
        if (document.querySelector('.btn-print')) return;
        
        var actionButtons = document.querySelector('.d-flex.flex-wrap.gap-3.justify-content-center');
        if (actionButtons) {
            var printBtn = document.createElement('button');
            printBtn.className = 'btn btn-outline-secondary px-4 py-2 flex-grow-1 flex-md-grow-0 btn-print';
            printBtn.innerHTML = '<i class="fas fa-print me-2"></i>Cetak';
            printBtn.onclick = function() { window.print(); };
            actionButtons.appendChild(printBtn);
        }
    };
</script>
@endpush