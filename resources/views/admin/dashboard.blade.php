@extends('admin.layouts.app')

@section('title', 'Dashboard Admin - Global Intermedia')

@section('content')
    <!-- Dashboard Header -->
    <div class="dashboard-header fade-in">
        <div>
            <h1 class="hero-title">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard Admin
            </h1>
            <p class="hero-subtitle">Selamat datang di sistem administrasi PKL Global Intermedia</p>
        </div>
        
        <div class="d-flex align-items-center">
            <div class="btn-group">
                <span class="badge bg-primary-custom rounded-pill p-2">
                    <i class="fas fa-users me-1"></i> Total: {{ $totalRegistrations ?? 0 }}
                </span>
                @if(isset($totalActiveParticipants) && $totalActiveParticipants > 0)
                <span class="badge bg-success rounded-pill p-2 ms-2">
                    <i class="fas fa-user-check me-1"></i> Peserta Aktif: {{ $totalActiveParticipants }}
                </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4 fade-in">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card-custom stat-card">
                <div class="card-body">
                    <div class="stat-icon-container bg-warning-light">
                        <i class="fas fa-clock stat-icon icon-warning"></i>
                    </div>
                    <div class="stat-value">{{ $pendingCount ?? 0 }}</div>
                    <div class="stat-label">Pending</div>
                    <small class="text-muted">Menunggu persetujuan</small>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card-custom stat-card">
                <div class="card-body">
                    <div class="stat-icon-container bg-success-light">
                        <i class="fas fa-check-circle stat-icon icon-success"></i>
                    </div>
                    <div class="stat-value">{{ $approvedCount ?? 0 }}</div>
                    <div class="stat-label">Disetujui</div>
                    <small class="text-muted">Sudah diverifikasi</small>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card-custom stat-card">
                <div class="card-body">
                    <div class="stat-icon-container bg-danger-light">
                        <i class="fas fa-times-circle stat-icon icon-danger"></i>
                    </div>
                    <div class="stat-value">{{ $rejectedCount ?? 0 }}</div>
                    <div class="stat-label">Ditolak</div>
                    <small class="text-muted">Tidak memenuhi syarat</small>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card-custom stat-card">
                <div class="card-body">
                    <div class="stat-icon-container bg-info-light">
                        <i class="fas fa-calendar-day stat-icon icon-info"></i>
                    </div>
                    <div class="stat-value">{{ $todayCount ?? 0 }}</div>
                    <div class="stat-label">Hari Ini</div>
                    <small class="text-muted">{{ date('d M Y') }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Peserta PKL Aktif -->
    <div class="row mb-4 fade-in">
        <div class="col-12">
            <div class="card-custom">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-graduate me-2 text-success"></i>
                        Peserta PKL Sedang Berjalan
                    </h5>
                    <a href="{{ route('admin.peserta.pkl') }}" class="btn btn-sm btn-primary-custom">
                        <i class="fas fa-eye me-1"></i> Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    @php
                        $today = now();
                        $activeParticipants = isset($recentRegistrations) ? 
                            $recentRegistrations->where('status', 'approved')
                                                ->where('tanggal_mulai', '<=', $today)
                                                ->where('tanggal_selesai', '>=', $today)
                                                ->take(6) : collect();
                    @endphp
                    
                    @if($activeParticipants->count() > 0)
                    <div class="row">
                        @foreach($activeParticipants as $participant)
                        @php
                            $start = \Carbon\Carbon::parse($participant->tanggal_mulai);
                            $end = \Carbon\Carbon::parse($participant->tanggal_selesai);
                            $totalDays = $start->diffInDays($end) + 1;
                            $daysPassed = $start->diffInDays(min($today, $end)) + 1;
                            $progress = $totalDays > 0 ? round(($daysPassed / $totalDays) * 100) : 0;
                            $daysLeft = $today->diffInDays($end);
                        @endphp
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="quick-stat-item border-start border-4 border-success">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="quick-stat-icon text-success me-2">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="quick-stat-value text-success mb-0">{{ $progress }}%</div>
                                        <div class="quick-stat-label">{{ $participant->nama_lengkap }}</div>
                                    </div>
                                </div>
                                <div class="progress mb-2" style="height: 6px;">
                                    <div class="progress-bar bg-success" style="width: {{ $progress }}%"></div>
                                </div>
                                <div class="small text-muted d-flex justify-content-between">
                                    <span>{{ $start->format('d/m') }} - {{ $end->format('d/m') }}</span>
                                    <span>{{ $daysLeft }} hari lagi</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada peserta PKL yang sedang berjalan</h5>
                        <p class="text-muted mb-3">Belum ada peserta yang sedang melaksanakan PKL</p>
                        <a href="{{ route('admin.registrations.index') }}?status=approved" class="btn btn-primary-custom">
                            <i class="fas fa-check-circle me-1"></i> Lihat Pendaftaran Disetujui
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Pendidikan & Bulanan -->
    <div class="row mb-4 fade-in">
        <div class="col-lg-6 mb-4">
            <div class="card-custom h-100">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="fas fa-graduation-cap me-2 text-primary"></i>
                        Statistik Pendidikan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-4">
                            <div class="quick-stat-item">
                                <div class="quick-stat-icon text-primary">
                                    <i class="fas fa-school"></i>
                                </div>
                                <div class="quick-stat-value text-primary">{{ $smkCount ?? 0 }}</div>
                                <div class="quick-stat-label">SMK/SMA</div>
                            </div>
                        </div>
                        <div class="col-6 mb-4">
                            <div class="quick-stat-item">
                                <div class="quick-stat-icon text-success">
                                    <i class="fas fa-university"></i>
                                </div>
                                <div class="quick-stat-value text-success">{{ $univCount ?? 0 }}</div>
                                <div class="quick-stat-label">UNIVERSITAS</div>
                            </div>
                        </div>
                    </div>
                    <div class="progress" style="height: 10px;">
                        @php
                            $total = ($smkCount ?? 0) + ($univCount ?? 0);
                            $smkPercentage = $total > 0 ? round(($smkCount ?? 0) / $total * 100) : 0;
                            $univPercentage = $total > 0 ? round(($univCount ?? 0) / $total * 100) : 0;
                        @endphp
                        <div class="progress-bar bg-primary" style="width: {{ $smkPercentage }}%"></div>
                        <div class="progress-bar bg-success" style="width: {{ $univPercentage }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card-custom h-100">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2 text-warning"></i>
                        Statistik Bulanan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-4">
                            <div class="quick-stat-item">
                                <div class="quick-stat-icon text-warning">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <div class="quick-stat-value text-warning">{{ $thisMonthCount ?? 0 }}</div>
                                <div class="quick-stat-label">BULAN INI</div>
                                <small class="text-muted">{{ date('F Y') }}</small>
                            </div>
                        </div>
                        <div class="col-6 mb-4">
                            <div class="quick-stat-item">
                                <div class="quick-stat-icon text-info">
                                    <i class="fas fa-database"></i>
                                </div>
                                <div class="quick-stat-value text-info">{{ $totalRegistrations ?? 0 }}</div>
                                <div class="quick-stat-label">TOTAL</div>
                                <small class="text-muted">Semua waktu</small>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small text-muted">Target Bulanan</span>
                            <span class="small text-muted">
                                @php
                                    $target = 50;
                                    $achievement = $thisMonthCount ?? 0;
                                    $percentage = $target > 0 ? min(100, round($achievement / $target * 100)) : 0;
                                @endphp
                                {{ $percentage }}%
                            </span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent Activity -->
    <div class="row fade-in">
        <div class="col-lg-6 mb-4">
            <div class="card-custom h-100">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2 text-primary"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('admin.registrations.index') }}" class="btn btn-outline-custom w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <span>Data Pendaftaran</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.peserta.pkl') }}" class="btn btn-outline-custom w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-user-graduate fa-2x mb-2"></i>
                                <span>Peserta PKL</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.export') }}" class="btn btn-outline-custom w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-download fa-2x mb-2"></i>
                                <span>Export Data</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="h-100">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                    <i class="fas fa-sign-out-alt fa-2x mb-2"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card-custom h-100">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2 text-info"></i>
                        Aktivitas Terbaru
                    </h5>
                    <a href="{{ route('admin.registrations.index') }}" class="btn btn-sm btn-outline-custom">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($recentRegistrations) && $recentRegistrations->count() > 0)
                        @foreach($recentRegistrations->take(5) as $activity)
                        <div class="activity-item">
                            <div class="activity-icon @if($activity->status == 'approved') bg-success @elseif($activity->status == 'rejected') bg-danger @else bg-warning @endif">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">
                                    {{ $activity->nama_lengkap }}
                                </div>
                                <div class="activity-time">
                                    {{ $activity->created_at->diffForHumans() }}
                                    <span class="badge ms-2 @if($activity->status == 'approved') bg-success @elseif($activity->status == 'rejected') bg-danger @else bg-warning @endif">
                                        {{ $activity->status == 'pending' ? 'Pending' : ($activity->status == 'approved' ? 'Disetujui' : 'Ditolak') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Belum ada aktivitas terbaru</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection