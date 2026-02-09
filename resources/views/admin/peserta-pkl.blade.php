@extends('admin.layouts.app')

@section('title', 'Daftar Peserta PKL - Admin Global Intermedia')

@push('styles')
<style>
    .page-header {
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
    
    .count-card {
        text-align: center;
        padding: 20px;
        border-radius: 10px;
        background: white;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        height: 100%;
        transition: all 0.3s ease;
    }
    
    .count-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .count-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .count-label {
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .badge-pending {
        background-color: #ffc107;
        color: #000;
    }
    
    .badge-active {
        background-color: #28a745;
        color: white;
    }
    
    .badge-completed {
        background-color: #17a2b8;
        color: white;
    }
    
    .badge-upcoming {
        background-color: #6f42c1;
        color: white;
    }
    
    .status-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }
    
    .filter-btn {
        border-radius: 25px;
        padding: 8px 20px;
        font-weight: 500;
        transition: all 0.3s;
        margin: 2px;
    }
    
    .filter-btn.active {
        background-color: var(--primary-red);
        color: white;
        border-color: var(--primary-red);
    }
    
    .calendar-badge {
        background-color: #e3f2fd;
        color: #1976d2;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.3;
    }
    
    .mobile-card {
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .mobile-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 10px;
    }
    
    .mobile-card-title {
        font-weight: 600;
        color: var(--primary-dark);
        margin-bottom: 5px;
    }
    
    .mobile-card-subtitle {
        color: #6c757d;
        font-size: 0.85rem;
    }
    
    .mobile-card-body {
        border-top: 1px solid #f0f0f0;
        padding-top: 10px;
        margin-top: 10px;
    }
    
    .mobile-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
    }
    
    .mobile-label {
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    .mobile-value {
        font-weight: 500;
        text-align: right;
    }
    
    .participant-card {
        border-left: 4px solid var(--primary-red);
    }
    
    @media (max-width: 992px) {
        .page-header {
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
            font-size: 1.5rem;
        }
        
        .count-number {
            font-size: 2rem;
        }
        
        .row .col-xl-3 {
            flex: 0 0 50%;
            max-width: 50%;
        }
        
        .table-desktop {
            display: none;
        }
        
        .participant-mobile-view {
            display: block;
        }
        
        .card-header-custom {
            flex-direction: column;
            align-items: flex-start;
        }
    }
    
    @media (max-width: 576px) {
        .row .col-xl-3 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
</style>
@endpush

@section('content')
    <div class="page-header fade-in">
        <div>
            <h1 class="page-title">
                <i class="fas fa-user-graduate me-2"></i>Daftar Peserta PKL
            </h1>
            <p class="page-subtitle">Monitoring peserta PKL yang sudah disetujui dan sedang berjalan</p>
        </div>
        
        <div class="d-flex align-items-center">
            <div class="btn-group">
                <span class="badge bg-primary-custom rounded-pill p-2">
                    <i class="fas fa-users me-1"></i> Total: 
                    <span id="totalParticipants">
                        {{ isset($participants) && $participants->total() ? $participants->total() : 0 }}
                    </span>
                </span>
            </div>
        </div>
    </div>

    <div class="row mb-4 fade-in" id="statsSection">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="count-card">
                <div class="count-number text-primary" id="activeCount">
                    {{ isset($totalActive) ? $totalActive : 0 }}
                </div>
                <div class="count-label">Sedang PKL</div>
                <small class="text-muted">Peserta aktif</small>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="count-card">
                <div class="count-number text-success" id="willStartCount">
                    {{ isset($willStart) ? $willStart : 0 }}
                </div>
                <div class="count-label">Akan Mulai</div>
                <small class="text-muted">Dalam 7 hari ke depan</small>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="count-card">
                <div class="count-number text-info" id="completedCount">
                    {{ isset($totalCompleted) ? $totalCompleted : 0 }}
                </div>
                <div class="count-label">Selesai</div>
                <small class="text-muted">PKL telah berakhir</small>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="count-card">
                <div class="count-number text-secondary" id="totalCount">
                    {{ isset($totalParticipants) ? $totalParticipants : 0 }}
                </div>
                <div class="count-label">Total Peserta</div>
                <small class="text-muted">Semua yang pernah PKL</small>
            </div>
        </div>
    </div>

    <div class="card-custom mb-4 fade-in">
        <div class="card-header-custom">
            <i class="fas fa-filter me-2"></i>Filter Status
        </div>
        <div class="card-body">
            <div class="btn-group flex-wrap" role="group" id="filterGroup">
                <a href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}" 
                   class="btn filter-btn {{ request('status', 'all') == 'all' ? 'active' : 'btn-outline-secondary' }}">
                    Semua
                </a>
                <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}" 
                   class="btn filter-btn {{ request('status') == 'active' ? 'active' : 'btn-outline-secondary' }}">
                    <span class="status-indicator bg-success"></span> Sedang PKL
                </a>
                <a href="{{ request()->fullUrlWithQuery(['status' => 'upcoming']) }}" 
                   class="btn filter-btn {{ request('status') == 'upcoming' ? 'active' : 'btn-outline-secondary' }}">
                    <span class="status-indicator bg-primary"></span> Akan Mulai
                </a>
                <a href="{{ request()->fullUrlWithQuery(['status' => 'completed']) }}" 
                   class="btn filter-btn {{ request('status') == 'completed' ? 'active' : 'btn-outline-secondary' }}">
                    <span class="status-indicator bg-info"></span> Selesai
                </a>
                <a href="{{ request()->fullUrlWithQuery(['status' => 'past']) }}" 
                   class="btn filter-btn {{ request('status') == 'past' ? 'active' : 'btn-outline-secondary' }}">
                    <span class="status-indicator bg-secondary"></span> Sudah Lewat
                </a>
            </div>
        </div>
    </div>

    <div class="card-custom fade-in" id="participantsSection">
        <div class="card-header-custom">
            <div>
                <i class="fas fa-list-check me-2"></i>Daftar Peserta PKL
                <span class="badge bg-primary ms-2" id="tableCount">
                    {{ isset($participants) ? $participants->total() : 0 }}
                </span>
            </div>
            <div class="input-group input-group-sm" style="width: 250px;">
                <form method="GET" class="d-flex w-100">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari nama..." value="{{ request('search') }}">
                    <button class="btn btn-outline-custom" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request('search'))
                    <a href="{{ request()->url() }}" class="btn btn-outline-secondary ms-1">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </form>
            </div>
        </div>
        
        <div class="card-body p-0" id="participantsContainer">
            <!-- Desktop View -->
            <div class="table-desktop">
                @if(isset($participants) && $participants->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Peserta</th>
                                <th>Pendidikan</th>
                                <th>Institusi</th>
                                <th>Periode PKL</th>
                                <th>Progress</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($participants as $participant)
                            @php
                                $today = \Carbon\Carbon::now();
                                try {
                                    $startDate = \Carbon\Carbon::parse($participant->tanggal_mulai);
                                    $endDate = \Carbon\Carbon::parse($participant->tanggal_selesai);
                                    
                                    $totalDays = $startDate->diffInDays($endDate) + 1;
                                    $daysPassed = $today->greaterThan($startDate) ? 
                                        $startDate->diffInDays(min($today, $endDate)) + 1 : 0;
                                    $progress = $totalDays > 0 ? round(($daysPassed / $totalDays) * 100) : 0;
                                    
                                    if ($today->lessThan($startDate)) {
                                        $status = 'upcoming';
                                        $statusText = 'Akan Mulai';
                                        $statusClass = 'badge-upcoming';
                                        $daysLeft = $today->diffInDays($startDate);
                                    } elseif ($today->greaterThan($endDate)) {
                                        $status = 'completed';
                                        $statusText = 'Selesai';
                                        $statusClass = 'badge-completed';
                                        $daysLeft = 0;
                                    } else {
                                        $status = 'active';
                                        $statusText = 'Sedang PKL';
                                        $statusClass = 'badge-active';
                                        $daysLeft = $today->diffInDays($endDate);
                                    }
                                } catch (Exception $e) {
                                    $totalDays = 0;
                                    $daysPassed = 0;
                                    $progress = 0;
                                    $status = 'unknown';
                                    $statusText = 'Error';
                                    $statusClass = 'badge-secondary';
                                    $daysLeft = 0;
                                }
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration + (($participants->currentPage() - 1) * $participants->perPage()) }}</td>
                                <td>
                                    <strong>{{ $participant->nama_lengkap ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">{{ $participant->registration_id ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    @if(isset($participant->jenis_pendidikan))
                                    <span class="badge {{ $participant->jenis_pendidikan == 'smk' ? 'bg-info' : 'bg-primary' }}">
                                        {{ $participant->jenis_pendidikan == 'smk' ? 'SMK' : 'Universitas' }}
                                    </span>
                                    @else
                                    <span class="badge bg-secondary">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($participant->jenis_pendidikan == 'smk')
                                        {{ $participant->sekolah ?? 'N/A' }}<br>
                                        <small>{{ $participant->jurusan_smk ?? '' }}</small>
                                    @else
                                        {{ $participant->universitas ?? 'N/A' }}<br>
                                        <small>{{ $participant->jurusan_univ ?? '' }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($participant->tanggal_mulai) && isset($participant->tanggal_selesai))
                                    <div class="calendar-badge">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ \Carbon\Carbon::parse($participant->tanggal_mulai)->format('d M Y') }}
                                    </div>
                                    <div class="small text-muted mt-1">s/d</div>
                                    <div class="calendar-badge">
                                        <i class="fas fa-calendar-check me-1"></i>
                                        {{ \Carbon\Carbon::parse($participant->tanggal_selesai)->format('d M Y') }}
                                    </div>
                                    <div class="small text-muted mt-1">
                                        {{ $totalDays }} hari
                                    </div>
                                    @else
                                    <span class="text-muted">Tidak ada data</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2">
                                            <div class="progress-bar bg-{{ $status == 'active' ? 'success' : ($status == 'completed' ? 'info' : ($status == 'upcoming' ? 'warning' : 'secondary')) }}" 
                                                 role="progressbar" style="width: {{ $progress }}%" 
                                                 aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                        <span class="small">{{ $progress }}%</span>
                                    </div>
                                    <small class="text-muted">
                                        @if($status == 'active')
                                            {{ $daysPassed }}/{{ $totalDays }} hari ({{ $daysLeft }} hari lagi)
                                        @elseif($status == 'upcoming')
                                            Mulai dalam {{ $daysLeft }} hari
                                        @elseif($status == 'completed')
                                            Telah selesai
                                        @else
                                            Data tidak valid
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.registrations.show', $participant->id) }}" 
                                       class="btn btn-sm btn-outline-custom" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <i class="fas fa-users-slash"></i>
                    <h4>Tidak ada data peserta PKL</h4>
                    <p class="text-muted">
                        @if(request('search'))
                            Tidak ditemukan peserta dengan kata kunci "{{ request('search') }}"
                        @elseif(request('status') && request('status') != 'all')
                            Tidak ada peserta dengan status "{{ request('status') }}"
                        @else
                            Belum ada peserta yang telah disetujui untuk melaksanakan PKL.
                        @endif
                    </p>
                    <a href="{{ route('admin.registrations.index') }}" class="btn btn-primary-custom mt-3">
                        <i class="fas fa-list me-1"></i> Lihat Data Pendaftaran
                    </a>
                    @if(request('search') || (request('status') && request('status') != 'all'))
                    <a href="{{ route('admin.peserta.pkl') }}" class="btn btn-outline-custom mt-3 ms-2">
                        <i class="fas fa-times me-1"></i> Hapus Filter
                    </a>
                    @endif
                </div>
                @endif
            </div>
            
            <!-- Mobile View -->
            <div class="participant-mobile-view">
                @if(isset($participants) && $participants->count() > 0)
                    @foreach($participants as $participant)
                    @php
                        $today = \Carbon\Carbon::now();
                        try {
                            $startDate = \Carbon\Carbon::parse($participant->tanggal_mulai);
                            $endDate = \Carbon\Carbon::parse($participant->tanggal_selesai);
                            
                            $totalDays = $startDate->diffInDays($endDate) + 1;
                            $daysPassed = $today->greaterThan($startDate) ? 
                                $startDate->diffInDays(min($today, $endDate)) + 1 : 0;
                            $progress = $totalDays > 0 ? round(($daysPassed / $totalDays) * 100) : 0;
                            
                            if ($today->lessThan($startDate)) {
                                $status = 'upcoming';
                                $statusText = 'Akan Mulai';
                                $statusClass = 'badge-upcoming';
                                $daysLeft = $today->diffInDays($startDate);
                            } elseif ($today->greaterThan($endDate)) {
                                $status = 'completed';
                                $statusText = 'Selesai';
                                $statusClass = 'badge-completed';
                                $daysLeft = 0;
                            } else {
                                $status = 'active';
                                $statusText = 'Sedang PKL';
                                $statusClass = 'badge-active';
                                $daysLeft = $today->diffInDays($endDate);
                            }
                        } catch (Exception $e) {
                            $totalDays = 0;
                            $daysPassed = 0;
                            $progress = 0;
                            $status = 'unknown';
                            $statusText = 'Error';
                            $statusClass = 'badge-secondary';
                            $daysLeft = 0;
                        }
                    @endphp
                    <div class="mobile-card">
                        <div class="mobile-card-header">
                            <div>
                                <div class="mobile-card-title">{{ $participant->nama_lengkap ?? 'N/A' }}</div>
                                <div class="mobile-card-subtitle">{{ $participant->registration_id ?? 'N/A' }}</div>
                            </div>
                            @if(isset($participant->jenis_pendidikan))
                            <span class="badge {{ $participant->jenis_pendidikan == 'smk' ? 'bg-info' : 'bg-primary' }}">
                                {{ $participant->jenis_pendidikan == 'smk' ? 'SMK' : 'UNIV' }}
                            </span>
                            @else
                            <span class="badge bg-secondary">N/A</span>
                            @endif
                        </div>
                        <div class="mobile-card-body">
                            <div class="mobile-row">
                                <span class="mobile-label">Institusi:</span>
                                <span class="mobile-value">
                                    @if($participant->jenis_pendidikan == 'smk')
                                        {{ $participant->sekolah ?? 'N/A' }}
                                    @else
                                        {{ $participant->universitas ?? 'N/A' }}
                                    @endif
                                </span>
                            </div>
                            <div class="mobile-row">
                                <span class="mobile-label">Jurusan:</span>
                                <span class="mobile-value">
                                    {{ $participant->jurusan_smk ?? $participant->jurusan_univ ?? 'N/A' }}
                                </span>
                            </div>
                            <div class="mobile-row">
                                <span class="mobile-label">Periode:</span>
                                <span class="mobile-value">
                                    @if(isset($participant->tanggal_mulai) && isset($participant->tanggal_selesai))
                                        {{ \Carbon\Carbon::parse($participant->tanggal_mulai)->format('d M Y') }} - 
                                        {{ \Carbon\Carbon::parse($participant->tanggal_selesai)->format('d M Y') }}
                                    @else
                                        N/A
                                    @endif
                                </span>
                            </div>
                            <div class="mobile-row">
                                <span class="mobile-label">Progress:</span>
                                <span class="mobile-value">{{ $progress }}%</span>
                            </div>
                            <div class="mobile-row">
                                <span class="mobile-label">Status:</span>
                                <span class="mobile-value">
                                    <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                </span>
                            </div>
                            <div class="progress mt-2 mb-2">
                                <div class="progress-bar bg-{{ $status == 'active' ? 'success' : ($status == 'completed' ? 'info' : ($status == 'upcoming' ? 'warning' : 'secondary')) }}" 
                                     style="width: {{ $progress }}%">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <span class="text-muted small">
                                    @if($status == 'active')
                                        {{ $daysPassed }}/{{ $totalDays }} hari ({{ $daysLeft }} hari lagi)
                                    @elseif($status == 'upcoming')
                                        Mulai dalam {{ $daysLeft }} hari
                                    @elseif($status == 'completed')
                                        Telah selesai
                                    @else
                                        Data tidak valid
                                    @endif
                                </span>
                                <a href="{{ route('admin.registrations.show', $participant->id) }}" 
                                   class="btn btn-sm btn-outline-custom">
                                    <i class="fas fa-eye me-1"></i> Detail
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                <div class="empty-state">
                    <i class="fas fa-users-slash"></i>
                    <h4>Tidak ada data peserta PKL</h4>
                    <p class="text-muted">
                        @if(request('search'))
                            Tidak ditemukan peserta dengan kata kunci "{{ request('search') }}"
                        @elseif(request('status') && request('status') != 'all')
                            Tidak ada peserta dengan status "{{ request('status') }}"
                        @else
                            Belum ada peserta yang telah disetujui untuk melaksanakan PKL.
                        @endif
                    </p>
                    <a href="{{ route('admin.registrations.index') }}" class="btn btn-primary-custom mt-3">
                        <i class="fas fa-list me-1"></i> Lihat Data Pendaftaran
                    </a>
                    @if(request('search') || (request('status') && request('status') != 'all'))
                    <a href="{{ route('admin.peserta.pkl') }}" class="btn btn-outline-custom mt-3 ms-2">
                        <i class="fas fa-times me-1"></i> Hapus Filter
                    </a>
                    @endif
                </div>
                @endif
            </div>
            
            <!-- Pagination -->
            @if(isset($participants) && $participants->count() > 0)
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <div class="text-muted">
                    Menampilkan {{ $participants->firstItem() }} - {{ $participants->lastItem() }} dari {{ $participants->total() }} peserta
                </div>
                <div>
                    {{ $participants->appends(request()->query())->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="card-custom mt-4 fade-in" id="calendarSection">
        <div class="card-header-custom">
            <i class="fas fa-calendar-alt me-2"></i>Kalender PKL (Bulan Ini)
        </div>
        <div class="card-body">
            <div class="row" id="calendarData">
                @php
                    $currentMonth = \Carbon\Carbon::now()->month;
                    $currentYear = \Carbon\Carbon::now()->year;
                    
                    if(isset($participants) && $participants->count() > 0) {
                        $monthParticipants = $participants->filter(function($p) use ($currentMonth, $currentYear) {
                            if(!$p) return false;
                            try {
                                $start = \Carbon\Carbon::parse($p->tanggal_mulai);
                                $end = \Carbon\Carbon::parse($p->tanggal_selesai);
                                return ($start->month == $currentMonth && $start->year == $currentYear) ||
                                       ($end->month == $currentMonth && $end->year == $currentYear) ||
                                       ($start->month <= $currentMonth && $end->month >= $currentMonth);
                            } catch (Exception $e) {
                                return false;
                            }
                        });
                    } else {
                        $monthParticipants = collect();
                    }
                @endphp
                
                @if($monthParticipants->count() > 0)
                    @foreach($monthParticipants as $participant)
                    <div class="col-md-6 mb-3">
                        <div class="participant-card p-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">{{ $participant->nama_lengkap }}</h6>
                                    <small class="text-muted">
                                        {{ $participant->jenis_pendidikan == 'smk' ? $participant->sekolah : $participant->universitas }}
                                    </small>
                                </div>
                                <span class="badge {{ $participant->jenis_pendidikan == 'smk' ? 'bg-info' : 'bg-primary' }}">
                                    {{ $participant->jenis_pendidikan == 'smk' ? 'SMK' : 'UNIV' }}
                                </span>
                            </div>
                            
                            <div class="mt-2">
                                <div class="d-flex justify-content-between small">
                                    <span>
                                        <i class="fas fa-play text-success me-1"></i>
                                        {{ \Carbon\Carbon::parse($participant->tanggal_mulai)->format('d M') }}
                                    </span>
                                    <span>
                                        <i class="fas fa-flag-checkered text-primary me-1"></i>
                                        {{ \Carbon\Carbon::parse($participant->tanggal_selesai)->format('d M') }}
                                    </span>
                                </div>
                                <div class="progress mt-1" style="height: 6px;">
                                    @php
                                        $today = \Carbon\Carbon::now();
                                        $start = \Carbon\Carbon::parse($participant->tanggal_mulai);
                                        $end = \Carbon\Carbon::parse($participant->tanggal_selesai);
                                        $total = $start->diffInDays($end);
                                        $current = $today->greaterThan($start) ? $start->diffInDays(min($today, $end)) : 0;
                                        $pct = $total > 0 ? ($current / $total) * 100 : 0;
                                    @endphp
                                    <div class="progress-bar bg-success" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Tidak ada peserta PKL pada bulan ini.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scroll untuk filter links
        document.querySelectorAll('#filterGroup a').forEach(link => {
            link.addEventListener('click', function(e) {
                setTimeout(() => {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }, 100);
            });
        });
        
        // Auto refresh setiap 5 menit (optional)
        setInterval(() => {
            console.log('Auto-refresh data...');
        }, 300000);
        
        // Animasi fade-in
        const cards = document.querySelectorAll('.fade-in');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
        
        console.log('Participants data:', {
            count: {{ isset($participants) ? $participants->count() : 0 }},
            total: {{ isset($participants) ? $participants->total() : 0 }},
            currentPage: {{ isset($participants) ? $participants->currentPage() : 1 }},
            status: '{{ request('status', 'all') }}',
            search: '{{ request('search', '') }}'
        });
    });
</script>
@endpush