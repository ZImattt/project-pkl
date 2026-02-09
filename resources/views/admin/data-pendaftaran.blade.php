@extends('admin.layouts.app')

@section('title', 'Data Pendaftaran - Admin Global Intermedia')

@push('styles')
<style>
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--primary-red);
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .page-title {
        color: var(--primary-red);
        font-weight: 700;
        font-size: 1.8rem;
        margin-bottom: 0;
    }
    
    .page-subtitle {
        color: var(--dark-gray);
        font-size: 0.9rem;
        margin-top: 5px;
    }
    
    .filter-card {
        background: white;
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
    }
    
    .form-control-custom {
        border: 1px solid #ced4da;
        border-radius: 6px;
        padding: 8px 12px;
        transition: all 0.3s;
        width: 100%;
    }
    
    .form-control-custom:focus {
        border-color: var(--primary-red);
        box-shadow: 0 0 0 0.25rem rgba(204, 0, 0, 0.25);
    }
    
    .table-container {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    
    .table th, .table td {
        border: 1px solid #dee2e6 !important;
        padding: 12px;
        vertical-align: middle;
    }
    
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #495057;
        text-align: center;
        border-bottom: 2px solid #adb5bd !important;
    }
    
    .table th.border-right {
        border-right: 2px solid #adb5bd !important;
    }
    
    .table td.border-right {
        border-right: 1px solid #dee2e6 !important;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(204, 0, 0, 0.03);
    }
    
    .whatsapp-icon {
        opacity: 0.7;
        transition: opacity 0.2s;
        font-size: 0.9rem;
    }
    
    .whatsapp-icon:hover {
        opacity: 1;
        transform: scale(1.1);
    }
    
    .accordion-item {
        border: 1px solid #dee2e6;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    
    .accordion-item:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .accordion-header {
        cursor: pointer;
        transition: all 0.3s ease;
        background-color: #f8f9fa;
        border-bottom: 1px solid transparent;
        position: relative;
        padding: 15px !important;
    }
    
    .accordion-header:hover {
        background-color: #e9ecef;
    }
    
    .accordion-header[aria-expanded="true"] {
        background: linear-gradient(135deg, var(--primary-red) 0%, #cc0000 100%);
        color: white;
    }
    
    .accordion-number {
        width: 30px;
        height: 30px;
        background-color: var(--primary-red);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: bold;
        margin-right: 10px;
    }
    
    .accordion-header[aria-expanded="true"] .accordion-number {
        background-color: white;
        color: var(--primary-red);
    }
    
    .accordion-header[aria-expanded="true"] .status-badge {
        background-color: white !important;
        color: var(--primary-red) !important;
    }
    
    .accordion-header[aria-expanded="true"] .text-muted {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    .accordion-header[aria-expanded="true"] strong {
        color: white !important;
    }
    
    .page-link {
        padding: 6px 12px;
        font-size: 0.85rem;
        color: var(--primary-red);
        border: 1px solid var(--medium-gray);
        margin: 0 2px;
        border-radius: 4px !important;
        transition: all 0.2s ease;
    }
    
    .page-link:hover {
        background-color: var(--primary-red);
        color: white;
        border-color: var(--primary-red);
    }
    
    .page-item.active .page-link {
        background-color: var(--primary-red);
        border-color: var(--primary-red);
        color: white;
    }
    
    @media (max-width: 992px) {
        .dashboard-header {
            margin-top: 60px;
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .page-title {
            font-size: 1.5rem;
        }
    }
    
    @media (max-width: 768px) {
        .page-title {
            font-size: 1.4rem;
        }
        
        .table th, .table td {
            padding: 8px 6px !important;
            font-size: 0.85rem;
        }
    }
    
    @media (max-width: 576px) {
        .page-title {
            font-size: 1.3rem;
        }
    }
</style>
@endpush

@section('content')
    <div class="dashboard-header fade-in">
        <div>
            <h1 class="page-title">
                <i class="fas fa-users me-2"></i>Data Pendaftaran PKL & Magang
            </h1>
            <p class="page-subtitle">Kelola dan pantau semua data pendaftaran PKL</p>
        </div>
        
        <div class="d-flex align-items-center">
            <div class="btn-group me-2">
                <span class="badge bg-primary-custom rounded-pill p-2">
                    <i class="fas fa-database me-1"></i> Total: {{ $registrations->total() }} data
                </span>
            </div>
        </div>
    </div>

    <div class="filter-card no-print fade-in">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('admin.registrations.index') }}" id="filterForm">
                <div class="row g-2">
                    <div class="col-md-3">
                        <input type="text" class="form-control-custom" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari nama, email, atau WA..." id="searchInput">
                    </div>
                    <div class="col-md-2">
                        <select class="form-control-custom" name="status" id="statusFilter">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control-custom" name="jenis_pendidikan" id="educationFilter">
                            <option value="all" {{ request('jenis_pendidikan') == 'all' ? 'selected' : '' }}>Semua Jenis</option>
                            <option value="smk" {{ request('jenis_pendidikan') == 'smk' ? 'selected' : '' }}>SMK/SMA</option>
                            <option value="universitas" {{ request('jenis_pendidikan') == 'universitas' ? 'selected' : '' }}>Universitas</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control-custom" name="start_date" 
                               value="{{ request('start_date') }}" placeholder="Dari tanggal" id="startDate">
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control-custom" name="end_date" 
                               value="{{ request('end_date') }}" placeholder="Sampai tanggal" id="endDate">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary-custom w-100" id="filterButton">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4 no-print fade-in">
        <div class="col-12">
            <div class="card-custom">
                <div class="card-body p-3">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-custom">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
                        </a>
                        <a href="{{ route('admin.peserta.pkl') }}" class="btn btn-outline-custom">
                            <i class="fas fa-user-graduate me-1"></i> Peserta PKL 
                        </a>
                        <a href="{{ route('admin.export') }}?{{ http_build_query(request()->except('page')) }}" 
                           class="btn btn-outline-custom export-btn">
                            <i class="fas fa-download me-1"></i>Export Excel
                        </a>
                        <button type="button" class="btn btn-outline-custom print-data-btn">
                            <i class="fas fa-print me-1"></i>Print Data
                        </button>
                        <button type="button" class="btn btn-outline-custom" id="refreshBtn">
                            <i class="fas fa-sync-alt me-1"></i>Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-container d-none d-lg-block printable-section fade-in">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="50" class="border-right">No</th>
                        <th class="border-right">Nama Lengkap</th>
                        <th class="border-right">Pendidikan</th>
                        <th class="border-right">Institusi</th>
                        <th class="border-right">WhatsApp</th>
                        <th class="border-right">Tanggal Daftar</th>
                        <th class="border-right">Status</th>
                        <th width="120" class="text-center no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $registration)
                    <tr>
                        <td class="fw-bold border-right">{{ ($registrations->currentPage() - 1) * $registrations->perPage() + $loop->iteration }}</td>
                        <td class="border-right">
                            <strong>{{ $registration->nama_lengkap }}</strong>
                            @if($registration->registration_id)
                            <br>
                            <small class="badge bg-info">{{ $registration->registration_id }}</small>
                            @endif
                            <br>
                            <small class="text-muted">{{ $registration->email }}</small>
                        </td>
                        <td class="border-right">
                            <span class="badge {{ $registration->jenis_pendidikan == 'smk' ? 'bg-primary' : 'bg-success' }}">
                                {{ $registration->jenis_pendidikan == 'smk' ? 'SMK/SMA' : 'UNIVERSITAS' }}
                            </span>
                            <br>
                            <small>
                                @if($registration->jenis_pendidikan == 'smk')
                                    Kelas {{ $registration->kelas }}
                                @else
                                    Semester {{ $registration->semester }}
                                @endif
                            </small>
                        </td>
                        <td class="border-right">
                            <strong>
                                @if($registration->jenis_pendidikan == 'smk')
                                    {{ $registration->sekolah }}
                                @else
                                    {{ $registration->universitas }}
                                @endif
                            </strong>
                            <br>
                            <small class="text-muted">
                                @if($registration->jenis_pendidikan == 'smk')
                                    {{ $registration->jurusan_smk }}
                                @else
                                    {{ $registration->jurusan_univ }}
                                @endif
                            </small>
                        </td>
                        <td class="border-right">
                            <div class="d-flex align-items-center">
                                <span>{{ $registration->no_whatsapp }}</span>
                                <a href="https://wa.me/{{ $registration->no_whatsapp }}" 
                                   target="_blank" 
                                   class="ms-2 text-success whatsapp-icon">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </td>
                        <td class="border-right">
                            {{ $registration->created_at->format('d/m/Y') }}
                            <br>
                            <small class="text-muted">{{ $registration->created_at->format('H:i') }}</small>
                        </td>
                        <td class="border-right">
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
                            <span class="badge bg-{{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                            @if($registration->status != 'pending' && $registration->status_updated_at)
                            <br>
                            <small class="text-muted">{{ $registration->status_updated_at->format('d/m/Y') }}</small>
                            @endif
                        </td>
                        <td class="no-print">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('admin.registrations.show', $registration->id) }}" 
                                   class="btn btn-outline-primary btn-sm" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn btn-outline-success btn-sm whatsapp-btn" 
                                        data-phone="{{ $registration->no_whatsapp }}"
                                        data-name="{{ $registration->nama_lengkap }}"
                                        title="WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-database fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">Belum ada data pendaftaran</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($registrations->hasPages())
        <div class="card-footer no-print">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Menampilkan <strong>{{ $registrations->firstItem() }}</strong> - <strong>{{ $registrations->lastItem() }}</strong> dari <strong>{{ $registrations->total() }}</strong> data
                </div>
                <div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            @if ($registrations->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link" aria-hidden="true">&laquo;</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $registrations->previousPageUrl() }}?{{ http_build_query(request()->except('page')) }}" rel="prev">&laquo;</a>
                                </li>
                            @endif

                            <li class="page-item active">
                                <span class="page-link">{{ $registrations->currentPage() }}</span>
                            </li>

                            @if ($registrations->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $registrations->nextPageUrl() }}?{{ http_build_query(request()->except('page')) }}" rel="next">&raquo;</a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link" aria-hidden="true">&raquo;</span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="d-lg-none printable-section fade-in">
        <div class="accordion no-print" id="mobileAccordion">
            @forelse($registrations as $index => $registration)
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
            
            <div class="card-custom mb-3 accordion-item">
                <div class="accordion-header card-header-custom" 
                     id="heading{{ $index }}"
                     data-bs-toggle="collapse" 
                     data-bs-target="#collapse{{ $index }}" 
                     aria-expanded="false" 
                     aria-controls="collapse{{ $index }}">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div class="d-flex align-items-center flex-grow-1">
                            <div class="accordion-number">
                                #{{ ($registrations->currentPage() - 1) * $registrations->perPage() + $loop->iteration }}
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-1">
                                    <strong class="me-2">{{ Str::limit($registration->nama_lengkap, 20) }}</strong>
                                    <span class="badge bg-{{ $statusClass }} status-badge">
                                        {{ $statusText }}
                                    </span>
                                </div>
                                <div class="text-muted small">
                                    {{ $registration->email }}
                                </div>
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-chevron-down accordion-chevron"></i>
                        </div>
                    </div>
                </div>
                
                <div id="collapse{{ $index }}" class="collapse accordion-content" 
                     aria-labelledby="heading{{ $index }}" 
                     data-bs-parent="#mobileAccordion">
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="section-title text-primary mb-2">
                                <i class="fas fa-user me-2"></i>Data Pribadi
                            </h6>
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <small class="text-muted">Nama Lengkap</small>
                                    <p class="mb-0 fw-bold">{{ $registration->nama_lengkap }}</p>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Email</small>
                                    <p class="mb-0 small">{{ $registration->email }}</p>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-muted">WhatsApp</small>
                                    <div class="d-flex align-items-center">
                                        <span class="me-2">{{ $registration->no_whatsapp }}</span>
                                        <a href="https://wa.me/{{ $registration->no_whatsapp }}" 
                                           target="_blank" class="text-success whatsapp-icon-mobile">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <h6 class="section-title text-success mb-2">
                                <i class="fas fa-graduation-cap me-2"></i>Data Pendidikan
                            </h6>
                            <div class="row">
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Jenis</small>
                                    <p class="mb-0">
                                        <span class="badge {{ $registration->jenis_pendidikan == 'smk' ? 'bg-primary' : 'bg-success' }}">
                                            {{ $registration->jenis_pendidikan == 'smk' ? 'SMK/SMA' : 'Universitas' }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-muted">{{ $registration->jenis_pendidikan == 'smk' ? 'Kelas' : 'Semester' }}</small>
                                    <p class="mb-0">
                                        @if($registration->jenis_pendidikan == 'smk')
                                            {{ $registration->kelas }}
                                        @else
                                            {{ $registration->semester }}
                                        @endif
                                    </p>
                                </div>
                                <div class="col-12 mb-2">
                                    <small class="text-muted">Institusi</small>
                                    <p class="mb-0">
                                        @if($registration->jenis_pendidikan == 'smk')
                                            {{ $registration->sekolah }}
                                        @else
                                            {{ $registration->universitas }}
                                        @endif
                                    </p>
                                </div>
                                <div class="col-12 mb-2">
                                    <small class="text-muted">Jurusan</small>
                                    <p class="mb-0">
                                        @if($registration->jenis_pendidikan == 'smk')
                                            {{ $registration->jurusan_smk }}
                                        @else
                                            {{ $registration->jurusan_univ }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <h6 class="section-title text-info mb-2">
                                <i class="fas fa-calendar-alt me-2"></i>Info Pendaftaran
                            </h6>
                            <div class="row">
                                <div class="col-6 mb-2">
                                    <small class="text-muted">ID Pendaftaran</small>
                                    <p class="mb-0 small">{{ $registration->registration_id ?? 'N/A' }}</p>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Tanggal Daftar</small>
                                    <p class="mb-0 small">
                                        {{ $registration->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <div class="col-12 mb-2">
                                    <small class="text-muted">Status</small>
                                    <div class="mt-1">
                                        <span class="badge bg-{{ $statusClass }}">
                                            {{ $statusText }}
                                        </span>
                                        @if($registration->status != 'pending' && $registration->status_updated_at)
                                        <br>
                                        <small class="text-muted">Update: {{ $registration->status_updated_at->format('d/m/Y') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 pt-3 border-top">
                            <h6 class="mb-3">
                                <i class="fas fa-cogs me-2"></i>Aksi
                            </h6>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('admin.registrations.show', $registration->id) }}" 
                                   class="btn btn-outline-primary btn-sm flex-grow-1">
                                    <i class="fas fa-eye me-1"></i> Detail
                                </a>
                                
                                <a href="https://wa.me/{{ $registration->no_whatsapp }}" 
                                   target="_blank" 
                                   class="btn btn-success btn-sm flex-grow-1 whatsapp-btn">
                                    <i class="fab fa-whatsapp me-1"></i> WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="card-custom">
                <div class="card-body text-center py-5">
                    <i class="fas fa-database fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada data pendaftaran</h5>
                    <p class="text-muted mb-0">Saat ini belum ada yang mendaftar PKL</p>
                </div>
            </div>
            @endforelse
        </div>

        @if($registrations->hasPages())
        <div class="card-custom mt-3 no-print">
            <div class="card-body p-3">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center">
                    <div class="text-muted small mb-2 mb-sm-0">
                        Menampilkan <strong>{{ $registrations->firstItem() }}</strong> - <strong>{{ $registrations->lastItem() }}</strong> dari <strong>{{ $registrations->total() }}</strong> data
                    </div>
                    <div>
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm mb-0">
                                @if ($registrations->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link" aria-hidden="true">&laquo;</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $registrations->previousPageUrl() }}?{{ http_build_query(request()->except('page')) }}" rel="prev">&laquo;</a>
                                    </li>
                                @endif

                                <li class="page-item active">
                                    <span class="page-link">{{ $registrations->currentPage() }}</span>
                                </li>

                                @if ($registrations->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $registrations->nextPageUrl() }}?{{ http_build_query(request()->except('page')) }}" rel="next">&raquo;</a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link" aria-hidden="true">&raquo;</span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('refreshBtn').addEventListener('click', function() {
            location.reload();
        });

        document.querySelectorAll('.whatsapp-icon, .whatsapp-btn, .whatsapp-icon-mobile').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                let href = this.getAttribute('href');
                const phone = this.getAttribute('data-phone') || href?.replace('https://wa.me/', '').split('?')[0];
                const name = this.getAttribute('data-name') || this.closest('tr, .card-body')?.querySelector('strong')?.textContent?.trim() || '';
                
                if (phone) {
                    const message = `Halo, saya dari admin PKL Global Intermedia. Apakah ini ${name}?`;
                    const whatsappUrl = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
                    
                    if (this.tagName === 'A') {
                        this.href = whatsappUrl;
                    } else {
                        window.open(whatsappUrl, '_blank');
                    }
                }
            });
        });

        document.querySelectorAll('.print-data-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const date = new Date();
                const formattedDate = date.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                
                const printWindow = window.open('', '_blank');
                printWindow.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Data Pendaftaran PKL - Global Intermedia</title>
                        <style>
                            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
                            
                            * {
                                margin: 0;
                                padding: 0;
                                box-sizing: border-box;
                            }
                            
                            body {
                                font-family: 'Inter', sans-serif;
                                font-size: 11px;
                                line-height: 1.4;
                                color: #000;
                                padding: 15px;
                                background: white;
                            }
                            
                            .print-container {
                                width: 100%;
                                max-width: 100%;
                            }
                            
                            .print-header {
                                margin-bottom: 20px;
                                padding-bottom: 15px;
                                border-bottom: 3px double #333;
                                text-align: center;
                            }
                            
                            .print-title {
                                color: #cc0000;
                                font-size: 18px;
                                font-weight: 700;
                                margin: 0 0 5px 0;
                                text-transform: uppercase;
                            }
                            
                            .print-subtitle {
                                font-size: 12px;
                                color: #666;
                                margin-bottom: 10px;
                            }
                            
                            .print-meta {
                                display: flex;
                                justify-content: space-between;
                                font-size: 9px;
                                color: #666;
                                margin-top: 10px;
                            }
                            
                            .print-table {
                                width: 100%;
                                border-collapse: collapse;
                                margin: 15px 0;
                                font-size: 9px;
                            }
                            
                            .print-table th {
                                background-color: #f5f5f5 !important;
                                border: 1px solid #333 !important;
                                padding: 8px 5px;
                                text-align: center;
                                font-weight: 700;
                                color: #000;
                                font-size: 9px;
                            }
                            
                            .print-table td {
                                border: 1px solid #ddd !important;
                                padding: 6px 4px;
                                vertical-align: top;
                                font-size: 9px;
                            }
                            
                            .print-table tr:nth-child(even) {
                                background-color: #f9f9f9;
                            }
                            
                            .status-badge {
                                display: inline-block;
                                padding: 2px 6px;
                                font-size: 8px;
                                font-weight: 600;
                                border-radius: 3px;
                                text-transform: uppercase;
                            }
                            
                            .pending { background-color: #ffc107; color: #000; }
                            .approved { background-color: #28a745; color: white; }
                            .rejected { background-color: #dc3545; color: white; }
                            
                            .print-footer {
                                margin-top: 20px;
                                padding-top: 10px;
                                border-top: 1px solid #ddd;
                                font-size: 8px;
                                color: #666;
                                text-align: center;
                            }
                            
                            @media print {
                                @page {
                                    size: A4 landscape;
                                    margin: 0.5cm;
                                }
                                
                                body {
                                    margin: 0;
                                    padding: 10px;
                                }
                            }
                        </style>
                    </head>
                    <body>
                        <div class="print-container">
                            <div class="print-header">
                                <h1 class="print-title">DATA PENDAFTARAN PKL</h1>
                                <div class="print-subtitle">Global Intermedia</div>
                                <div class="print-meta">
                                    <div>
                                        <strong>Tanggal Cetak:</strong> ${formattedDate}
                                    </div>
                                    <div>
                                        <strong>Total Data:</strong> {{ $registrations->total() }}
                                    </div>
                                    <div>
                                        <strong>Filter:</strong> 
                                        {{ request('status') && request('status') != 'all' ? 'Status: ' . request('status') . '; ' : '' }}
                                        {{ request('jenis_pendidikan') && request('jenis_pendidikan') != 'all' ? 'Pendidikan: ' . request('jenis_pendidikan') . '; ' : '' }}
                                        {{ request('start_date') ? 'Dari: ' . request('start_date') . '; ' : '' }}
                                        {{ request('end_date') ? 'Sampai: ' . request('end_date') : '' }}
                                    </div>
                                </div>
                            </div>
                            
                            <table class="print-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Lengkap</th>
                                        <th>Email</th>
                                        <th>WhatsApp</th>
                                        <th>Pendidikan</th>
                                        <th>Institusi</th>
                                        <th>Jurusan</th>
                                        <th>Tanggal Daftar</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($registrations as $registration)
                                    @php
                                        $statusText = match($registration->status) {
                                            'pending' => 'Menunggu',
                                            'approved' => 'Disetujui',
                                            'rejected' => 'Ditolak',
                                            default => $registration->status
                                        };
                                    @endphp
                                    <tr>
                                        <td>{{ ($registrations->currentPage() - 1) * $registrations->perPage() + $loop->iteration }}</td>
                                        <td>{{ $registration->nama_lengkap }}</td>
                                        <td>{{ $registration->email }}</td>
                                        <td>{{ $registration->no_whatsapp }}</td>
                                        <td>{{ $registration->jenis_pendidikan == 'smk' ? 'SMK/SMA' : 'UNIVERSITAS' }}</td>
                                        <td>
                                            @if($registration->jenis_pendidikan == 'smk')
                                                {{ $registration->sekolah }}
                                            @else
                                                {{ $registration->universitas }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($registration->jenis_pendidikan == 'smk')
                                                {{ $registration->jurusan_smk }}
                                            @else
                                                {{ $registration->jurusan_univ }}
                                            @endif
                                        </td>
                                        <td>{{ $registration->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $statusText }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                            <div class="print-footer">
                                <p>Dokumen ini dicetak dari Sistem Admin PKL Global Intermedia</p>
                            </div>
                        </div>
                        
                        <script>
                            window.onload = function() {
                                window.print();
                                setTimeout(function() {
                                    window.close();
                                }, 500);
                            };
                        <\/script>
                    </body>
                    </html>
                `);
                
                printWindow.document.close();
            });
        });

        document.querySelectorAll('.export-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (!confirm('Export data ke Excel? Data akan didownload dalam format Excel dengan filter yang aktif.')) {
                    return;
                }
                
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Memproses...';
                btn.disabled = true;
                
                const exportUrl = '{{ route("admin.export") }}?' + new URLSearchParams({
                    search: '{{ request("search") }}',
                    status: '{{ request("status") }}',
                    jenis_pendidikan: '{{ request("jenis_pendidikan") }}',
                    start_date: '{{ request("start_date") }}',
                    end_date: '{{ request("end_date") }}',
                    export_all: 'true'
                }).toString();
                
                window.location.href = exportUrl;
                
                setTimeout(() => {
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                }, 2000);
            });
        });

        @if($registrations->count() == 1)
        if (window.innerWidth < 992) {
            const firstAccordion = document.getElementById('collapse0');
            if (firstAccordion) {
                firstAccordion.classList.add('show');
                document.getElementById('heading0').setAttribute('aria-expanded', 'true');
            }
        }
        @endif

        document.querySelectorAll('input[name="start_date"], input[name="end_date"]').forEach(input => {
            input.addEventListener('change', function() {
                const startDate = document.querySelector('input[name="start_date"]').value;
                const endDate = document.querySelector('input[name="end_date"]').value;
                
                if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
                    alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir!');
                    this.value = '';
                }
            });
        });

        @if(request('search'))
        setTimeout(() => {
            const searchField = document.getElementById('searchInput');
            if (searchField) {
                searchField.focus();
                searchField.select();
            }
        }, 100);
        @endif

        document.querySelectorAll('.accordion-header').forEach(header => {
            header.addEventListener('click', function() {
                const chevron = this.querySelector('.accordion-chevron');
                if (chevron) {
                    chevron.style.transform = chevron.style.transform === 'rotate(180deg)' ? 'rotate(0deg)' : 'rotate(180deg)';
                }
            });
        });
    });
</script>
@endpush