@extends('layouts.admin')

@section('title', 'Dashboard Admin - Global Intermedia')

@push('styles')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    :root {
        --red: #dc3545;
        --green: #28a745;
        --yellow: #ffc107;
        --blue: #0d6efd;
        --purple: #6f42c1;
        --orange: #fd7e14;
        --cyan: #17a2b8;
        --gray1: #f8f9fa;
        --gray2: #e9ecef;
        --gray3: #dee2e3;
        --gray6: #6c757d;
        --gray7: #495057;
        --gray8: #212529;
    }
    
    .dashboard-wrapper {
        max-width: 1400px;
        margin: 0 auto;
        animation: fadeInUp 0.5s ease;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--red);
        flex-wrap: wrap;
        gap: 8px;
    }
    
    .page-title {
        color: var(--red);
        font-weight: 700;
        font-size: 1.1rem;
        margin: 0;
    }
    
    .page-subtitle {
        color: var(--gray6);
        font-size: 0.65rem;
        margin-top: 2px;
    }
    
    .badge-total {
        background: linear-gradient(135deg, var(--red) 0%, #e11d48 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(220,53,69,0.25);
    }
    
    /* Section Title */
    .section-title {
        font-size: 0.7rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--gray8);
        border-left: 3px solid var(--red);
        padding-left: 8px;
        margin-top: 3px;
    }
    
    /* Summary Cards - Compact */
    .summary-row {
        display: flex;
        gap: 8px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }
    
    .summary-card {
        flex: 1;
        min-width: 80px;
        background: white;
        border-radius: 8px;
        padding: 10px;
        text-align: center;
        border: 1px solid var(--gray2);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: all 0.2s ease;
    }
    
    .summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .summary-card .value {
        font-size: 1.4rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 2px;
    }
    
    .summary-card .label {
        font-size: 0.6rem;
        color: var(--gray6);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .summary-card.total .value { color: var(--blue); }
    .summary-card.pending .value { color: var(--yellow); }
    .summary-card.approved .value { color: var(--green); }
    .summary-card.rejected .value { color: var(--red); }
    .summary-card.today .value { color: var(--cyan); }
    
    /* Charts Grid */
    .charts-grid {
        display: grid;
        gap: 12px;
        margin-bottom: 12px;
    }
    
    .charts-grid.cols-2 {
        grid-template-columns: 1fr 1fr;
    }
    
    .charts-grid.cols-3 {
        grid-template-columns: repeat(3, 1fr);
    }
    
    /* Chart Card */
    .chart-card {
        background: white;
        border-radius: 8px;
        padding: 16px;
        border: 1px solid var(--gray2);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: all 0.2s ease;
    }
    
    .chart-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .chart-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }
    
    .chart-card h6 {
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--gray8);
        border-left: 3px solid var(--red);
        padding-left: 8px;
        margin: 0;
    }
    
    .chart-card-badge {
        font-size: 0.6rem;
        padding: 3px 10px;
        border-radius: 12px;
        font-weight: 600;
        background: var(--gray1);
        color: var(--gray6);
    }
    
    /* Donut Container */
    .donut-container {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
    }
    
    .donut-chart {
        position: relative;
        width: 130px;
        height: 130px;
        flex-shrink: 0;
    }
    
    .donut-chart canvas {
        width: 100% !important;
        height: 100% !important;
    }
    
    .donut-center {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }
    
    .donut-total {
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--gray8);
        line-height: 1;
    }
    
    .donut-label {
        font-size: 0.55rem;
        color: var(--gray6);
        font-weight: 500;
    }
    
    .donut-legend {
        display: flex;
        flex-direction: column;
        gap: 6px;
        flex: 1;
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.6rem;
        padding: 3px 8px;
        border-radius: 6px;
        background: var(--gray1);
    }
    
    .legend-dot {
        width: 8px;
        height: 8px;
        border-radius: 2px;
        flex-shrink: 0;
    }
    
    .legend-dot.pending { background: var(--yellow); }
    .legend-dot.approved { background: var(--green); }
    .legend-dot.rejected { background: var(--red); }
    .legend-dot.individu { background: var(--blue); }
    .legend-dot.kelompok { background: var(--purple); }
    .legend-dot.smk { background: var(--cyan); }
    .legend-dot.univ { background: var(--orange); }
    
    .legend-value {
        margin-left: auto;
        font-weight: 700;
        color: var(--gray8);
        font-size: 0.65rem;
    }
    
    /* Bar Chart Container */
    .bar-chart-container {
        width: 100%;
        height: 220px;
    }
    
    /* Polar Area Container */
    .polar-chart-container {
        width: 100%;
        height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .polar-chart-container canvas {
        max-width: 250px;
        max-height: 250px;
    }
    
    /* Horizontal Bar */
    .horizontal-bars {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .horizontal-bar-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .horizontal-bar-label {
        width: 80px;
        font-size: 0.65rem;
        font-weight: 600;
        color: var(--gray7);
        flex-shrink: 0;
    }
    
    .horizontal-bar-track {
        flex: 1;
        height: 24px;
        background: var(--gray1);
        border-radius: 6px;
        overflow: hidden;
        position: relative;
    }
    
    .horizontal-bar-fill {
        height: 100%;
        border-radius: 6px;
        transition: width 0.5s ease;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding-right: 8px;
        min-width: 30px;
    }
    
    .horizontal-bar-value {
        font-size: 0.6rem;
        font-weight: 700;
        color: white;
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }
    
    .horizontal-bar-fill.smk { background: linear-gradient(90deg, #17a2b8, #3db5c9); }
    .horizontal-bar-fill.univ { background: linear-gradient(90deg, #fd7e14, #ff9a4d); }
    
    /* Peserta Table */
    .peserta-table-card {
        background: white;
        border-radius: 8px;
        padding: 12px;
        border: 1px solid var(--gray2);
        margin-bottom: 12px;
    }
    
    .peserta-table-card h6 {
        font-size: 0.7rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--gray8);
        border-left: 3px solid var(--red);
        padding-left: 8px;
    }
    
    .peserta-table-card table {
        width: 100%;
        font-size: 0.65rem;
    }
    
    .peserta-table-card th {
        padding: 8px 10px;
        color: var(--gray6);
        font-weight: 600;
        border-bottom: 2px solid var(--gray2);
        font-size: 0.6rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .peserta-table-card td {
        padding: 8px 10px;
        border-bottom: 1px solid var(--gray2);
        vertical-align: middle;
    }
    
    .peserta-table-card tbody tr {
        transition: all 0.15s ease;
    }
    
    .peserta-table-card tbody tr:hover {
        background: var(--gray1);
    }
    
    .peserta-table-card tr:last-child td {
        border-bottom: none;
    }
    
    .peserta-avatar {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--red) 0%, #e11d48 100%);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 0.7rem;
        margin-right: 10px;
    }
    
    .peserta-nama {
        font-weight: 600;
        color: var(--gray8);
        display: block;
        font-size: 0.7rem;
    }
    
    .peserta-institusi {
        font-size: 0.6rem;
        color: var(--gray6);
        display: block;
    }
    
    .status-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 10px;
        font-size: 0.55rem;
        font-weight: 600;
    }
    
    .status-badge.active { background: rgba(40,167,69,0.15); color: #155724; }
    .status-badge.upcoming { background: rgba(255,193,7,0.15); color: #856404; }
    .status-badge.done { background: rgba(13,110,253,0.15); color: #0d3b6e; }
    
    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }
    
    .status-dot.active { background: var(--green); }
    .status-dot.upcoming { background: var(--yellow); }
    .status-dot.done { background: var(--blue); }
    
    .btn-detail-sm {
        background: rgba(220,53,69,0.1);
        color: var(--red);
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.6rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-block;
    }
    
    .btn-detail-sm:hover {
        background: var(--red);
        color: white;
    }
    
    .btn-link-clean {
        font-size: 0.65rem;
        color: var(--red);
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }
    
    .btn-link-clean:hover {
        color: #b30000;
        gap: 8px;
    }
    
    /* Activity List */
    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .activity-item {
        padding: 10px 14px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.2s ease;
    }
    
    .activity-item:hover {
        background: var(--gray1);
    }
    
    .activity-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    
    .activity-dot.approved { background: var(--green); box-shadow: 0 0 0 3px rgba(40,167,69,0.2); }
    .activity-dot.rejected { background: var(--red); box-shadow: 0 0 0 3px rgba(220,53,69,0.2); }
    .activity-dot.pending { background: var(--yellow); box-shadow: 0 0 0 3px rgba(255,193,7,0.2); }
    
    .activity-content {
        flex: 1;
        min-width: 0;
    }
    
    .activity-name {
        font-weight: 600;
        font-size: 0.75rem;
        color: var(--gray8);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .activity-meta {
        font-size: 0.6rem;
        color: var(--gray6);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .activity-badge {
        font-size: 0.55rem;
        padding: 3px 10px;
        border-radius: 50px;
        font-weight: 600;
        flex-shrink: 0;
    }
    
    .activity-badge.approved { background: rgba(40,167,69,0.15); color: #155724; }
    .activity-badge.rejected { background: rgba(220,53,69,0.15); color: #721c24; }
    .activity-badge.pending { background: rgba(255,193,7,0.15); color: #856404; }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 30px 20px;
    }
    
    .empty-state-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: var(--gray1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        color: var(--gray6);
        font-size: 1.2rem;
    }
    
    .empty-state h6 {
        color: var(--gray8);
        margin-bottom: 4px;
        font-weight: 600;
        font-size: 0.7rem;
    }
    
    .empty-state p {
        color: var(--gray6);
        font-size: 0.65rem;
        margin: 0;
    }
    
    .text-muted {
        color: var(--gray6) !important;
        font-size: 0.55rem;
    }
    
    /* Mobile Optimization */
    @media (max-width: 768px) {
        .dashboard-header {
            margin-top: 50px;
        }
        
        .charts-grid.cols-2,
        .charts-grid.cols-3 {
            grid-template-columns: 1fr;
        }
        
        .summary-row {
            flex-wrap: wrap;
        }
        
        .summary-card {
            min-width: 60px;
            flex: 1 1 40%;
        }
        
        .donut-container {
            gap: 12px;
        }
        
        .donut-chart {
            width: 110px;
            height: 110px;
        }
        
        .bar-chart-container {
            height: 180px;
        }
        
        .polar-chart-container {
            height: 200px;
        }
        
        .polar-chart-container canvas {
            max-width: 200px;
            max-height: 200px;
        }
        
        .horizontal-bar-label {
            width: 60px;
            font-size: 0.6rem;
        }
    }
    
    @media (max-width: 480px) {
        .summary-card {
            min-width: 50px;
            flex: 1 1 35%;
            padding: 8px 6px;
        }
        
        .summary-card .value {
            font-size: 1.1rem;
        }
        
        .donut-container {
            flex-direction: column;
            gap: 10px;
        }
        
        .donut-chart {
            width: 100px;
            height: 100px;
        }
        
        .donut-legend {
            width: 100%;
        }
        
        .bar-chart-container {
            height: 150px;
        }
        
        .polar-chart-container {
            height: 180px;
        }
        
        .polar-chart-container canvas {
            max-width: 180px;
            max-height: 180px;
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-wrapper">
    <!-- HEADER -->
    <div class="dashboard-header">
        <div>
            <h1 class="page-title">Dashboard Admin</h1>
            <p class="page-subtitle">Overview pendaftaran PKL Global Intermedia Nusantara</p>
        </div>
        <div>
            <span class="badge-total">
                <i class="fas fa-users"></i>
                Total Pendaftar: <strong>{{ number_format($totalRegistrations ?? 0) }}</strong>
            </span>
        </div>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="summary-row">
        <div class="summary-card pending">
            <div class="value">{{ number_format($totalPending ?? 0) }}</div>
            <div class="label">Pending</div>
        </div>
        <div class="summary-card approved">
            <div class="value">{{ number_format($approvedCount ?? 0) }}</div>
            <div class="label">Disetujui</div>
        </div>
        <div class="summary-card rejected">
            <div class="value">{{ number_format($rejectedCount ?? 0) }}</div>
            <div class="label">Ditolak</div>
        </div>
        <div class="summary-card today">
            <div class="value">{{ number_format($todayCount ?? 0) }}</div>
            <div class="label">Hari Ini</div>
        </div>
        <div class="summary-card total">
            <div class="value">{{ number_format($totalRegistrations ?? 0) }}</div>
            <div class="label">Total</div>
        </div>
    </div>

    <!-- CHARTS SECTION -->
    <h6 class="section-title">Visualisasi Data</h6>
    
    <!-- Baris 1: Donut + Polar -->
    <div class="charts-grid cols-3">
        <!-- Donut Chart - Status Pendaftaran -->
        <div class="chart-card">
            <div class="chart-card-header">
                <h6>Status Pendaftaran</h6>
                <span class="chart-card-badge">Donut</span>
            </div>
            <div class="donut-container">
                <div class="donut-chart">
                    <canvas id="statusDonutChart"></canvas>
                    <div class="donut-center">
                        <div class="donut-total">{{ number_format($totalRegistrations ?? 0) }}</div>
                        <div class="donut-label">Total</div>
                    </div>
                </div>
                <div class="donut-legend">
                    <div class="legend-item">
                        <span class="legend-dot pending"></span>
                        <span>Pending</span>
                        <span class="legend-value">{{ number_format($totalPending ?? 0) }}</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-dot approved"></span>
                        <span>Disetujui</span>
                        <span class="legend-value">{{ number_format($approvedCount ?? 0) }}</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-dot rejected"></span>
                        <span>Ditolak</span>
                        <span class="legend-value">{{ number_format($rejectedCount ?? 0) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bar Chart - Periode Pendaftaran -->
        <div class="chart-card">
            <div class="chart-card-header">
                <h6>Periode Pendaftaran</h6>
                <span class="chart-card-badge">Bar</span>
            </div>
            <div class="bar-chart-container">
                <canvas id="periodeBarChart"></canvas>
            </div>
        </div>

        <!-- Polar Area - Distribusi Pendidikan -->
        <div class="chart-card">
            <div class="chart-card-header">
                <h6>Distribusi Pendidikan</h6>
                <span class="chart-card-badge">Polar</span>
            </div>
            <div class="polar-chart-container">
                <canvas id="pendidikanPolarChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Horizontal Progress Bars -->
    <div class="charts-grid cols-2">
        <div class="chart-card">
            <div class="chart-card-header">
                <h6>Detail Pendidikan</h6>
                <span class="chart-card-badge">Progress</span>
            </div>
            <div class="horizontal-bars">
                @php
                    $totalPendidikan = ($smkCount ?? 0) + ($univCount ?? 0);
                    $smkPercent = $totalPendidikan > 0 ? round(($smkCount ?? 0) / $totalPendidikan * 100) : 0;
                    $univPercent = $totalPendidikan > 0 ? round(($univCount ?? 0) / $totalPendidikan * 100) : 0;
                @endphp
                <div class="horizontal-bar-item">
                    <span class="horizontal-bar-label">SMK/SMA</span>
                    <div class="horizontal-bar-track">
                        <div class="horizontal-bar-fill smk" style="width: {{ $smkPercent }}%;">
                            <span class="horizontal-bar-value">{{ number_format($smkCount ?? 0) }}</span>
                        </div>
                    </div>
                    <span style="font-size:0.6rem;font-weight:600;color:var(--gray7);width:35px;text-align:right;">{{ $smkPercent }}%</span>
                </div>
                <div class="horizontal-bar-item">
                    <span class="horizontal-bar-label">Mahasiswa</span>
                    <div class="horizontal-bar-track">
                        <div class="horizontal-bar-fill univ" style="width: {{ $univPercent }}%;">
                            <span class="horizontal-bar-value">{{ number_format($univCount ?? 0) }}</span>
                        </div>
                    </div>
                    <span style="font-size:0.6rem;font-weight:600;color:var(--gray7);width:35px;text-align:right;">{{ $univPercent }}%</span>
                </div>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-card-header">
                <h6>Detail Periode</h6>
                <span class="chart-card-badge">Progress</span>
            </div>
            <div class="horizontal-bars">
                @php
                    $totalPeriode = ($todayCount ?? 0) + ($thisWeekCount ?? 0) + ($thisMonthCount ?? 0);
                    $todayPercent = $totalPeriode > 0 ? round(($todayCount ?? 0) / $totalPeriode * 100) : 0;
                    $weekPercent = $totalPeriode > 0 ? round(($thisWeekCount ?? 0) / $totalPeriode * 100) : 0;
                    $monthPercent = $totalPeriode > 0 ? round(($thisMonthCount ?? 0) / $totalPeriode * 100) : 0;
                @endphp
                <div class="horizontal-bar-item">
                    <span class="horizontal-bar-label">Hari Ini</span>
                    <div class="horizontal-bar-track">
                        <div class="horizontal-bar-fill" style="width: {{ $todayPercent }}%; background: linear-gradient(90deg, #3b82f6, #60a5fa);">
                            <span class="horizontal-bar-value">{{ number_format($todayCount ?? 0) }}</span>
                        </div>
                    </div>
                    <span style="font-size:0.6rem;font-weight:600;color:var(--gray7);width:35px;text-align:right;">{{ $todayPercent }}%</span>
                </div>
                <div class="horizontal-bar-item">
                    <span class="horizontal-bar-label">Minggu Ini</span>
                    <div class="horizontal-bar-track">
                        <div class="horizontal-bar-fill" style="width: {{ $weekPercent }}%; background: linear-gradient(90deg, #8b5cf6, #a78bfa);">
                            <span class="horizontal-bar-value">{{ number_format($thisWeekCount ?? 0) }}</span>
                        </div>
                    </div>
                    <span style="font-size:0.6rem;font-weight:600;color:var(--gray7);width:35px;text-align:right;">{{ $weekPercent }}%</span>
                </div>
                <div class="horizontal-bar-item">
                    <span class="horizontal-bar-label">Bulan Ini</span>
                    <div class="horizontal-bar-track">
                        <div class="horizontal-bar-fill" style="width: {{ $monthPercent }}%; background: linear-gradient(90deg, #dc3545, #f87171);">
                            <span class="horizontal-bar-value">{{ number_format($thisMonthCount ?? 0) }}</span>
                        </div>
                    </div>
                    <span style="font-size:0.6rem;font-weight:600;color:var(--gray7);width:35px;text-align:right;">{{ $monthPercent }}%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- PESERTA AKTIF -->
    <div class="peserta-table-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
            <h6 style="margin-bottom: 0;">Peserta PKL Aktif</h6>
            <a href="{{ route('admin.peserta.index') }}" class="btn-link-clean">
                Lihat Semua <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        @php
            $latestPeserta = collect();
            if(isset($recentRegistrations) && $recentRegistrations->count() > 0) {
                $latestPeserta = $recentRegistrations
                    ->where('status', 'diterima')
                    ->sortByDesc('created_at')
                    ->take(5);
            }
        @endphp

        @if($latestPeserta->count() > 0)
            <div style="max-height: 300px; overflow-y: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Peserta</th>
                            <th>Institusi</th>
                            <th>Tanggal Mulai</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($latestPeserta as $peserta)
                            @php
                                $today = now();
                                $start = $peserta->tanggal_mulai ? \Carbon\Carbon::parse($peserta->tanggal_mulai) : null;
                                $end = $peserta->tanggal_selesai ? \Carbon\Carbon::parse($peserta->tanggal_selesai) : null;
                                
                                if($end && $today > $end) {
                                    $statusClass = 'done';
                                    $statusText = 'Selesai';
                                } elseif($start && $today < $start) {
                                    $statusClass = 'upcoming';
                                    $statusText = 'Akan Mulai';
                                } else {
                                    $statusClass = 'active';
                                    $statusText = 'Aktif';
                                }
                                
                                $institusi = $peserta->sekolah ?? $peserta->universitas ?? '-';
                            @endphp
                            
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <span class="peserta-avatar">
                                            {{ strtoupper(substr($peserta->nama_lengkap, 0, 2)) }}
                                        </span>
                                        <span>
                                            <span class="peserta-nama">{{ $peserta->nama_lengkap }}</span>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="peserta-institusi">{{ $institusi }}</span>
                                </td>
                                <td>
                                    <span style="font-size: 0.65rem; color: var(--gray6);">
                                        <i class="far fa-calendar me-1"></i>
                                        {{ $start ? $start->format('d M Y') : '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge {{ $statusClass }}">
                                        <span class="status-dot {{ $statusClass }}"></span>
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.individu.show', $peserta->id) }}" class="btn-detail-sm">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-user-slash"></i>
                </div>
                <h6>Belum Ada Peserta</h6>
                <p>Belum ada peserta PKL yang disetujui</p>
            </div>
        @endif
    </div>

    <!-- AKTIVITAS TERBARU -->
    <div class="chart-card">
        <div class="chart-card-header">
            <h6>Aktivitas Pendaftaran</h6>
            <span style="font-size: 0.6rem; color: var(--gray6);">
                <i class="far fa-clock me-1"></i> Real-time
            </span>
        </div>
        @if(isset($recentRegistrations) && $recentRegistrations->count() > 0)
            <div class="activity-list">
                @foreach($recentRegistrations->take(5) as $activity)
                    @php
                        $statusClass = $activity->status;
                        $statusLabel = match($activity->status) {
                            'diterima' => 'Disetujui',
                            'ditolak' => 'Ditolak',
                            default => 'Pending'
                        };
                    @endphp
                    <div class="activity-item">
                        <div class="activity-dot {{ $statusClass }}"></div>
                        <div class="activity-content">
                            <div class="activity-name">{{ $activity->nama_lengkap }}</div>
                            <div class="activity-meta">
                                <i class="far fa-clock me-1"></i>
                                {{ $activity->created_at->diffForHumans() }}
                                @if($activity->sekolah || $activity->universitas)
                                    <span class="mx-1">•</span>
                                    <i class="fas fa-building me-1"></i>
                                    {{ Str::limit($activity->sekolah ?? $activity->universitas, 25) }}
                                @endif
                            </div>
                        </div>
                        <span class="activity-badge {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <h6>Belum Ada Aktivitas</h6>
                <p>Belum ada aktivitas pendaftaran terbaru</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Donut Chart - Status
    const statusCtx = document.getElementById('statusDonutChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Disetujui', 'Ditolak'],
            datasets: [{
                data: [
                    {{ $totalPending ?? 0 }}, 
                    {{ $approvedCount ?? 0 }}, 
                    {{ $rejectedCount ?? 0 }}
                ],
                backgroundColor: ['#ffc107', '#28a745', '#dc3545'],
                borderWidth: 0,
                hoverBorderWidth: 2,
                hoverBorderColor: '#ffffff'
            }]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: true, 
            plugins: { legend: { display: false } },
            cutout: '70%'
        }
    });
    
    // Bar Chart - Periode
    const periodeCtx = document.getElementById('periodeBarChart').getContext('2d');
    new Chart(periodeCtx, {
        type: 'bar',
        data: {
            labels: ['Hari Ini', 'Minggu Ini', 'Bulan Ini'],
            datasets: [{
                label: 'Jumlah',
                data: [
                    {{ $todayCount ?? 0 }}, 
                    {{ $thisWeekCount ?? 0 }}, 
                    {{ $thisMonthCount ?? 0 }}
                ],
                backgroundColor: ['#3b82f6', '#8b5cf6', '#dc3545'],
                borderRadius: 6,
                borderSkipped: false,
                barPercentage: 0.5,
                categoryPercentage: 0.5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10 }, color: '#6c757d' }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f3f5' },
                    ticks: { font: { size: 10 }, color: '#6c757d', stepSize: 1 }
                }
            }
        }
    });
    
    // Polar Area - Pendidikan
    const pendidikanCtx = document.getElementById('pendidikanPolarChart').getContext('2d');
    new Chart(pendidikanCtx, {
        type: 'polarArea',
        data: {
            labels: ['SMK/SMA', 'Mahasiswa'],
            datasets: [{
                data: [{{ $smkCount ?? 0 }}, {{ $univCount ?? 0 }}],
                backgroundColor: [
                    'rgba(23,162,184,0.6)',
                    'rgba(253,126,20,0.6)'
                ],
                borderColor: ['#17a2b8', '#fd7e14'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { size: 10 },
                        padding: 12,
                        usePointStyle: true,
                        pointStyleWidth: 8
                    }
                }
            },
            scales: {
                r: {
                    ticks: { display: false },
                    grid: { color: '#f1f3f5' }
                }
            }
        }
    });
});
</script>
@endpush