@extends('layouts.admin')

@section('title', 'Statistik Pendaftaran - Admin Global Intermedia')

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
    
    .section-title {
        font-size: 0.7rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--gray8);
        border-left: 3px solid var(--red);
        padding-left: 8px;
        margin-top: 3px;
    }
    
    .filter-card {
        background: white;
        border-radius: 8px;
        border: 1px solid var(--gray2);
        margin-bottom: 12px;
        padding: 10px;
    }
    
    .filter-label {
        font-size: 0.65rem;
        font-weight: 600;
        margin-bottom: 3px;
        color: var(--gray7);
        display: block;
    }
    
    .form-control-custom {
        border: 1px solid var(--gray3);
        border-radius: 5px;
        padding: 5px 8px;
        width: 100%;
        font-size: 0.7rem;
        transition: all 0.2s ease;
    }
    
    .form-control-custom:focus {
        border-color: var(--red);
        outline: none;
        box-shadow: 0 0 0 2px rgba(220,53,69,0.1);
    }
    
    .btn-primary-custom {
        background: var(--red);
        border: none;
        color: white;
        padding: 5px 12px;
        border-radius: 5px;
        font-size: 0.7rem;
        cursor: pointer;
        transition: all 0.2s ease;
        width: 100%;
    }
    
    .btn-primary-custom:hover {
        background: #b30000;
    }
    
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
    .summary-card.diterima .value { color: var(--green); }
    .summary-card.ditolak .value { color: var(--red); }
    
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
    
    .chart-card {
        background: white;
        border-radius: 8px;
        padding: 16px;
        border: 1px solid var(--gray2);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
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
    .legend-dot.diterima { background: var(--green); }
    .legend-dot.ditolak { background: var(--red); }
    .legend-dot.individu { background: var(--blue); }
    .legend-dot.kelompok { background: var(--purple); }
    .legend-dot.smk { background: var(--cyan); }
    .legend-dot.kuliah { background: var(--orange); }
    
    .legend-value {
        margin-left: auto;
        font-weight: 700;
        color: var(--gray8);
        font-size: 0.65rem;
    }
    
    .legend-percent {
        font-size: 0.5rem;
        color: var(--gray6);
        margin-left: 3px;
    }
    
    .bar-chart-container {
        width: 100%;
        height: 220px;
    }
    
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
    
    .horizontal-bar-fill.individu { background: linear-gradient(90deg, #6f42c1, #8b6cd6); }
    .horizontal-bar-fill.kelompok { background: linear-gradient(90deg, #fd7e14, #ff9a4d); }
    .horizontal-bar-fill.smk { background: linear-gradient(90deg, #17a2b8, #3db5c9); }
    .horizontal-bar-fill.kuliah { background: linear-gradient(90deg, #fd7e14, #ff9a4d); }
    
    .recent-table {
        background: white;
        border-radius: 8px;
        padding: 12px;
        border: 1px solid var(--gray2);
        margin-bottom: 12px;
    }
    
    .recent-table h6 {
        font-size: 0.7rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--gray8);
        border-left: 3px solid var(--red);
        padding-left: 8px;
    }
    
    .recent-table table {
        width: 100%;
        font-size: 0.65rem;
    }
    
    .recent-table th {
        padding: 8px 10px;
        color: var(--gray6);
        font-weight: 600;
        border-bottom: 2px solid var(--gray2);
        font-size: 0.6rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .recent-table td {
        padding: 8px 10px;
        border-bottom: 1px solid var(--gray2);
        vertical-align: middle;
    }
    
    .recent-table tbody tr {
        transition: all 0.15s ease;
    }
    
    .recent-table tbody tr:hover {
        background: var(--gray1);
    }
    
    .recent-table tr:last-child td {
        border-bottom: none;
    }
    
    .type-badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 0.55rem;
        font-weight: 600;
    }
    
    .type-individu { background: rgba(13,110,253,0.1); color: var(--blue); }
    .type-kelompok { background: rgba(111,66,193,0.1); color: var(--purple); }
    
    .status-badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 10px;
        font-size: 0.55rem;
        font-weight: 600;
    }
    
    .badge-yellow { background: rgba(255,193,7,0.15); color: #856404; }
    .badge-green { background: rgba(40,167,69,0.15); color: #155724; }
    .badge-red { background: rgba(220,53,69,0.15); color: #721c24; }
    
    .text-muted {
        color: var(--gray6) !important;
        font-size: 0.55rem;
    }
    
    .btn-refresh, .btn-export {
        background: var(--red);
        color: white;
        border: none;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.65rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .btn-refresh:hover, .btn-export:hover {
        background: #b30000;
    }
    
    .btn-outline-custom {
        border: 1px solid var(--gray3);
        color: var(--gray7);
        padding: 4px 8px;
        border-radius: 4px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 3px;
        background: white;
        font-size: 0.65rem;
        transition: all 0.2s ease;
    }
    
    .btn-outline-custom:hover {
        background: var(--gray1);
        color: var(--red);
        border-color: var(--red);
    }
    
    .alert-warning {
        background: #fff3cd;
        border: 1px solid #ffecb5;
        color: #856404;
        border-radius: 6px;
        padding: 6px 10px;
        font-size: 0.65rem;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .btn-fix {
        background: var(--yellow);
        color: #333;
        border: none;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 0.55rem;
        cursor: pointer;
    }
    
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    
    .loading-spinner {
        width: 30px;
        height: 30px;
        border: 3px solid var(--gray2);
        border-top: 3px solid var(--red);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .toast-notification {
        position: fixed;
        bottom: 15px;
        right: 15px;
        background: white;
        border-radius: 5px;
        padding: 6px 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        gap: 6px;
        z-index: 10000;
        min-width: 200px;
        border-left: 3px solid;
        animation: slideInRight 0.3s ease;
    }
    
    .toast-success { border-left-color: var(--green); }
    .toast-error { border-left-color: var(--red); }
    
    .toast-icon { font-size: 0.8rem; }
    .toast-success .toast-icon { color: var(--green); }
    .toast-error .toast-icon { color: var(--red); }
    
    .toast-content { flex: 1; }
    .toast-title { font-weight: 700; font-size: 0.7rem; }
    .toast-message { font-size: 0.6rem; color: var(--gray6); }
    .toast-close { cursor: pointer; font-size: 0.65rem; color: var(--gray6); }
    
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
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
<div class="dashboard-header">
    <div>
        <h1 class="page-title">Statistik Pendaftaran</h1>
        <p class="page-subtitle">Data pendaftaran individu & kelompok</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn-refresh" onclick="refreshData()">
            <i class="fas fa-sync-alt"></i>
        </button>
        <a href="{{ route('admin.export', request()->all()) }}" class="btn-outline-custom">
            <i class="fas fa-download"></i>
        </a>
    </div>
</div>

@if(isset($individuNullCount) && ($individuNullCount > 0 || $kelompokNullCount > 0))
<div class="alert-warning">
    <div>
        <i class="fas fa-exclamation-triangle"></i>
        {{ $individuNullCount + $kelompokNullCount }} data tanpa tanggal
    </div>
    <button class="btn-fix" onclick="fixCreatedAt()">
        <i class="fas fa-tools"></i> Fix
    </button>
</div>
@endif

<div class="filter-card">
    <div class="row g-2 align-items-end">
        <div class="col-5">
            <label class="filter-label">Dari</label>
            <input type="date" id="start_date" class="form-control-custom" value="{{ $startDate ?? Carbon\Carbon::now()->subDays(30)->format('Y-m-d') }}">
        </div>
        <div class="col-5">
            <label class="filter-label">Sampai</label>
            <input type="date" id="end_date" class="form-control-custom" value="{{ $endDate ?? Carbon\Carbon::now()->format('Y-m-d') }}">
        </div>
        <div class="col-2">
            <button class="btn-primary-custom" onclick="applyFilter()" style="padding: 5px 8px;">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
</div>

<div class="summary-row">
    <div class="summary-card total">
        <div class="value" id="totalPendaftar">{{ ($totalIndividu ?? 0) + ($totalKelompok ?? 0) }}</div>
        <div class="label">Total</div>
    </div>
    <div class="summary-card pending">
        <div class="value" id="totalPending">{{ ($statusIndividu['pending'] ?? 0) + ($statusKelompok['pending'] ?? 0) }}</div>
        <div class="label">Pending</div>
    </div>
    <div class="summary-card diterima">
        <div class="value" id="totalDiterima">{{ ($statusIndividu['diterima'] ?? 0) + ($statusKelompok['diterima'] ?? 0) }}</div>
        <div class="label">Diterima</div>
    </div>
    <div class="summary-card ditolak">
        <div class="value" id="totalDitolak">{{ ($statusIndividu['ditolak'] ?? 0) + ($statusKelompok['ditolak'] ?? 0) }}</div>
        <div class="label">Ditolak</div>
    </div>
</div>

<h6 class="section-title">Status Pendaftaran</h6>
<div class="charts-grid cols-2">
    <div class="chart-card">
        <div class="chart-card-header">
            <h6>Status Individu</h6>
            <span class="chart-card-badge">Donut</span>
        </div>
        <div class="donut-container">
            <div class="donut-chart">
                <canvas id="individuDonutChart"></canvas>
                <div class="donut-center">
                    <div class="donut-total" id="individuDonutTotal">{{ $totalIndividu ?? 0 }}</div>
                    <div class="donut-label">Total</div>
                </div>
            </div>
            <div class="donut-legend">
                <div class="legend-item">
                    <span class="legend-dot pending"></span>
                    <span>Pending</span>
                    <span class="legend-value" id="legendIndPending">{{ $statusIndividu['pending'] ?? 0 }}</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot diterima"></span>
                    <span>Diterima</span>
                    <span class="legend-value" id="legendIndDiterima">{{ $statusIndividu['diterima'] ?? 0 }}</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot ditolak"></span>
                    <span>Ditolak</span>
                    <span class="legend-value" id="legendIndDitolak">{{ $statusIndividu['ditolak'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-card-header">
            <h6>Status Kelompok</h6>
            <span class="chart-card-badge">Donut</span>
        </div>
        <div class="donut-container">
            <div class="donut-chart">
                <canvas id="kelompokDonutChart"></canvas>
                <div class="donut-center">
                    <div class="donut-total" id="kelompokDonutTotal">{{ $totalKelompok ?? 0 }}</div>
                    <div class="donut-label">Total</div>
                </div>
            </div>
            <div class="donut-legend">
                <div class="legend-item">
                    <span class="legend-dot pending"></span>
                    <span>Pending</span>
                    <span class="legend-value" id="legendKelPending">{{ $statusKelompok['pending'] ?? 0 }}</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot diterima"></span>
                    <span>Diterima</span>
                    <span class="legend-value" id="legendKelDiterima">{{ $statusKelompok['diterima'] ?? 0 }}</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot ditolak"></span>
                    <span>Ditolak</span>
                    <span class="legend-value" id="legendKelDitolak">{{ $statusKelompok['ditolak'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<h6 class="section-title">Perbandingan Data</h6>
<div class="charts-grid cols-3">
    <div class="chart-card">
        <div class="chart-card-header">
            <h6>Tipe Pendaftaran</h6>
            <span class="chart-card-badge">Bar</span>
        </div>
        <div class="bar-chart-container">
            <canvas id="tipeBarChart"></canvas>
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-card-header">
            <h6>Jenjang Pendidikan</h6>
            <span class="chart-card-badge">Bar</span>
        </div>
        <div class="bar-chart-container">
            <canvas id="pendidikanBarChart"></canvas>
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-card-header">
            <h6>Status Keseluruhan</h6>
            <span class="chart-card-badge">Polar</span>
        </div>
        <div class="polar-chart-container">
            <canvas id="polarChart"></canvas>
        </div>
    </div>
</div>

<h6 class="section-title">Detail Perbandingan</h6>
<div class="charts-grid cols-2">
    <div class="chart-card">
        <div class="chart-card-header">
            <h6>Tipe Pendaftaran</h6>
            <span class="chart-card-badge">Progress</span>
        </div>
        <div class="horizontal-bars">
            @php
                $totalAll = ($totalIndividu ?? 0) + ($totalKelompok ?? 0);
                $individuPercent = $totalAll > 0 ? round(($totalIndividu ?? 0) / $totalAll * 100) : 0;
                $kelompokPercent = $totalAll > 0 ? round(($totalKelompok ?? 0) / $totalAll * 100) : 0;
            @endphp
            <div class="horizontal-bar-item">
                <span class="horizontal-bar-label">Individu</span>
                <div class="horizontal-bar-track">
                    <div class="horizontal-bar-fill individu" style="width: {{ $individuPercent }}%;" id="hbarIndividu">
                        <span class="horizontal-bar-value">{{ $totalIndividu ?? 0 }}</span>
                    </div>
                </div>
                <span style="font-size:0.6rem;font-weight:600;color:var(--gray7);width:35px;text-align:right;">{{ $individuPercent }}%</span>
            </div>
            <div class="horizontal-bar-item">
                <span class="horizontal-bar-label">Kelompok</span>
                <div class="horizontal-bar-track">
                    <div class="horizontal-bar-fill kelompok" style="width: {{ $kelompokPercent }}%;" id="hbarKelompok">
                        <span class="horizontal-bar-value">{{ $totalKelompok ?? 0 }}</span>
                    </div>
                </div>
                <span style="font-size:0.6rem;font-weight:600;color:var(--gray7);width:35px;text-align:right;" id="hbarKelompokPct">{{ $kelompokPercent }}%</span>
            </div>
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-card-header">
            <h6>Jenjang Pendidikan</h6>
            <span class="chart-card-badge">Progress</span>
        </div>
        <div class="horizontal-bars">
            @php
                $totalPendidikan = ($institusiData['smk'] ?? 0) + ($institusiData['kuliah'] ?? 0);
                $smkPercent = $totalPendidikan > 0 ? round(($institusiData['smk'] ?? 0) / $totalPendidikan * 100) : 0;
                $kuliahPercent = $totalPendidikan > 0 ? round(($institusiData['kuliah'] ?? 0) / $totalPendidikan * 100) : 0;
            @endphp
            <div class="horizontal-bar-item">
                <span class="horizontal-bar-label">SMK</span>
                <div class="horizontal-bar-track">
                    <div class="horizontal-bar-fill smk" style="width: {{ $smkPercent }}%;" id="hbarSmk">
                        <span class="horizontal-bar-value">{{ $institusiData['smk'] ?? 0 }}</span>
                    </div>
                </div>
                <span style="font-size:0.6rem;font-weight:600;color:var(--gray7);width:35px;text-align:right;" id="hbarSmkPct">{{ $smkPercent }}%</span>
            </div>
            <div class="horizontal-bar-item">
                <span class="horizontal-bar-label">Kuliah</span>
                <div class="horizontal-bar-track">
                    <div class="horizontal-bar-fill kuliah" style="width: {{ $kuliahPercent }}%;" id="hbarKuliah">
                        <span class="horizontal-bar-value">{{ $institusiData['kuliah'] ?? 0 }}</span>
                    </div>
                </div>
                <span style="font-size:0.6rem;font-weight:600;color:var(--gray7);width:35px;text-align:right;" id="hbarKuliahPct">{{ $kuliahPercent }}%</span>
            </div>
        </div>
    </div>
</div>

<div class="recent-table">
    <h6>Pendaftar Terbaru</h6>
    <div style="max-height: 300px; overflow-y: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Tipe</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody id="recentTableBody">
                @forelse($recentRegistrations as $reg)
                <tr>
                    <td>
                        <strong>{{ $reg->nama_lengkap ?? $reg->nama_kelompok }}</strong>
                        <br><small class="text-muted">{{ $reg->jenis_pendidikan == 'smk' ? 'SMK' : 'PT' }}</small>
                    </td>
                    <td>
                        <span class="type-badge {{ $reg->tipe == 'kelompok' ? 'type-kelompok' : 'type-individu' }}">
                            {{ $reg->tipe == 'kelompok' ? 'Kel' : 'Ind' }}
                        </span>
                    </td>
                    <td>
                        @if($reg->status == 'pending')
                            <span class="status-badge badge-yellow">Pending</span>
                        @elseif($reg->status == 'diterima')
                            <span class="status-badge badge-green">Diterima</span>
                        @else
                            <span class="status-badge badge-red">Ditolak</span>
                        @endif
                    </td>
                    <td>{{ $reg->created_at ? $reg->created_at->format('d/m') : '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="loading-overlay" id="loadingOverlay" style="display: none;">
    <div class="loading-spinner"></div>
</div>

<div class="toast-notification" id="toastNotification" style="display: none;">
    <div class="toast-icon" id="toastIcon"></div>
    <div class="toast-content">
        <div class="toast-title" id="toastTitle"></div>
        <div class="toast-message" id="toastMessage"></div>
    </div>
    <div class="toast-close" onclick="hideToast()"><i class="fas fa-times"></i></div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let individuDonutChart = null;
let kelompokDonutChart = null;
let tipeBarChart = null;
let pendidikanBarChart = null;
let polarChart = null;

document.addEventListener('DOMContentLoaded', function() {
    const individuCtx = document.getElementById('individuDonutChart').getContext('2d');
    individuDonutChart = new Chart(individuCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Diterima', 'Ditolak'],
            datasets: [{
                data: [
                    {{ $statusIndividu['pending'] ?? 0 }}, 
                    {{ $statusIndividu['diterima'] ?? 0 }}, 
                    {{ $statusIndividu['ditolak'] ?? 0 }}
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
    
    const kelompokCtx = document.getElementById('kelompokDonutChart').getContext('2d');
    kelompokDonutChart = new Chart(kelompokCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Diterima', 'Ditolak'],
            datasets: [{
                data: [
                    {{ $statusKelompok['pending'] ?? 0 }}, 
                    {{ $statusKelompok['diterima'] ?? 0 }}, 
                    {{ $statusKelompok['ditolak'] ?? 0 }}
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
    
    const tipeCtx = document.getElementById('tipeBarChart').getContext('2d');
    tipeBarChart = new Chart(tipeCtx, {
        type: 'bar',
        data: {
            labels: ['Individu', 'Kelompok'],
            datasets: [{
                label: 'Jumlah',
                data: [{{ $totalIndividu ?? 0 }}, {{ $totalKelompok ?? 0 }}],
                backgroundColor: ['#6f42c1', '#fd7e14'],
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
                x: { grid: { display: false }, ticks: { font: { size: 10 }, color: '#6c757d' } },
                y: { beginAtZero: true, grid: { color: '#f1f3f5' }, ticks: { font: { size: 10 }, color: '#6c757d', stepSize: 1 } }
            }
        }
    });
    
    const pendidikanCtx = document.getElementById('pendidikanBarChart').getContext('2d');
    pendidikanBarChart = new Chart(pendidikanCtx, {
        type: 'bar',
        data: {
            labels: ['SMK', 'Kuliah'],
            datasets: [{
                label: 'Jumlah',
                data: [{{ $institusiData['smk'] ?? 0 }}, {{ $institusiData['kuliah'] ?? 0 }}],
                backgroundColor: ['#17a2b8', '#fd7e14'],
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
                x: { grid: { display: false }, ticks: { font: { size: 10 }, color: '#6c757d' } },
                y: { beginAtZero: true, grid: { color: '#f1f3f5' }, ticks: { font: { size: 10 }, color: '#6c757d', stepSize: 1 } }
            }
        }
    });
    
    const polarCtx = document.getElementById('polarChart').getContext('2d');
    polarChart = new Chart(polarCtx, {
        type: 'polarArea',
        data: {
            labels: ['Pending', 'Diterima', 'Ditolak'],
            datasets: [{
                data: [
                    {{ ($statusIndividu['pending'] ?? 0) + ($statusKelompok['pending'] ?? 0) }},
                    {{ ($statusIndividu['diterima'] ?? 0) + ($statusKelompok['diterima'] ?? 0) }},
                    {{ ($statusIndividu['ditolak'] ?? 0) + ($statusKelompok['ditolak'] ?? 0) }}
                ],
                backgroundColor: ['rgba(255,193,7,0.6)', 'rgba(40,167,69,0.6)', 'rgba(220,53,69,0.6)'],
                borderColor: ['#ffc107', '#28a745', '#dc3545'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 10 }, padding: 12, usePointStyle: true, pointStyleWidth: 8 } }
            },
            scales: { r: { ticks: { display: false }, grid: { color: '#f1f3f5' } } }
        }
    });
});

function applyFilter() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    
    if (!startDate || !endDate) {
        showToast('Peringatan', 'Pilih tanggal mulai dan akhir', 'error');
        return;
    }
    
    const loading = document.getElementById('loadingOverlay');
    loading.style.display = 'flex';
    
    fetch(`{{ route('admin.statistik.data') }}?start_date=${startDate}&end_date=${endDate}`)
        .then(response => response.json())
        .then(data => {
            loading.style.display = 'none';
            
            if (data.success && data.data) {
                const d = data.data;
                
                document.getElementById('totalPendaftar').innerText = d.totalPendaftar;
                document.getElementById('totalPending').innerText = d.pending;
                document.getElementById('totalDiterima').innerText = d.diterima;
                document.getElementById('totalDitolak').innerText = d.ditolak;
                
                if (individuDonutChart) {
                    individuDonutChart.data.datasets[0].data = [d.individu.pending, d.individu.diterima, d.individu.ditolak];
                    individuDonutChart.update();
                    document.getElementById('individuDonutTotal').innerText = d.totalIndividu;
                    document.getElementById('legendIndPending').innerText = d.individu.pending;
                    document.getElementById('legendIndDiterima').innerText = d.individu.diterima;
                    document.getElementById('legendIndDitolak').innerText = d.individu.ditolak;
                }
                
                if (kelompokDonutChart) {
                    kelompokDonutChart.data.datasets[0].data = [d.kelompok.pending, d.kelompok.diterima, d.kelompok.ditolak];
                    kelompokDonutChart.update();
                    document.getElementById('kelompokDonutTotal').innerText = d.totalKelompok;
                    document.getElementById('legendKelPending').innerText = d.kelompok.pending;
                    document.getElementById('legendKelDiterima').innerText = d.kelompok.diterima;
                    document.getElementById('legendKelDitolak').innerText = d.kelompok.ditolak;
                }
                
                if (polarChart) {
                    polarChart.data.datasets[0].data = [d.pending, d.diterima, d.ditolak];
                    polarChart.update();
                }
                
                if (tipeBarChart) {
                    tipeBarChart.data.datasets[0].data = [d.totalIndividu, d.totalKelompok];
                    tipeBarChart.update();
                }
                
                if (pendidikanBarChart) {
                    pendidikanBarChart.data.datasets[0].data = [d.smk, d.kuliah];
                    pendidikanBarChart.update();
                }
                
                const totalTipe = d.totalIndividu + d.totalKelompok;
                const individuPct = totalTipe > 0 ? Math.round(d.totalIndividu / totalTipe * 100) : 0;
                const kelompokPct = totalTipe > 0 ? Math.round(d.totalKelompok / totalTipe * 100) : 0;
                
                const hbarIndividu = document.getElementById('hbarIndividu');
                const hbarKelompok = document.getElementById('hbarKelompok');
                if (hbarIndividu) {
                    hbarIndividu.style.width = individuPct + '%';
                    hbarIndividu.querySelector('.horizontal-bar-value').innerText = d.totalIndividu;
                }
                if (hbarKelompok) {
                    hbarKelompok.style.width = kelompokPct + '%';
                    hbarKelompok.querySelector('.horizontal-bar-value').innerText = d.totalKelompok;
                }
                const kelompokPctEl = document.getElementById('hbarKelompokPct');
                if (kelompokPctEl) kelompokPctEl.innerText = kelompokPct + '%';
                
                const totalPend = d.smk + d.kuliah;
                const smkPct = totalPend > 0 ? Math.round(d.smk / totalPend * 100) : 0;
                const kuliahPct = totalPend > 0 ? Math.round(d.kuliah / totalPend * 100) : 0;
                
                const hbarSmk = document.getElementById('hbarSmk');
                const hbarKuliah = document.getElementById('hbarKuliah');
                if (hbarSmk) {
                    hbarSmk.style.width = smkPct + '%';
                    hbarSmk.querySelector('.horizontal-bar-value').innerText = d.smk;
                }
                if (hbarKuliah) {
                    hbarKuliah.style.width = kuliahPct + '%';
                    hbarKuliah.querySelector('.horizontal-bar-value').innerText = d.kuliah;
                }
                const smkPctEl = document.getElementById('hbarSmkPct');
                const kuliahPctEl = document.getElementById('hbarKuliahPct');
                if (smkPctEl) smkPctEl.innerText = smkPct + '%';
                if (kuliahPctEl) kuliahPctEl.innerText = kuliahPct + '%';
                
                if (d.recent && d.recent.length > 0) {
                    const tbody = document.getElementById('recentTableBody');
                    tbody.innerHTML = d.recent.map(reg => `
                        <tr>
                            <td>
                                <strong>${reg.nama_lengkap || reg.nama_kelompok}</strong>
                                <br><small class="text-muted">${reg.jenis_pendidikan == 'smk' ? 'SMK' : 'PT'}</small>
                            </td>
                            <td>
                                <span class="type-badge ${reg.tipe == 'kelompok' ? 'type-kelompok' : 'type-individu'}">
                                    ${reg.tipe == 'kelompok' ? 'Kel' : 'Ind'}
                                </span>
                            </td>
                            <td>
                                ${reg.status == 'pending' ? '<span class="status-badge badge-yellow">Pending</span>' : 
                                  reg.status == 'diterima' ? '<span class="status-badge badge-green">Diterima</span>' : 
                                  '<span class="status-badge badge-red">Ditolak</span>'}
                            </td>
                            <td>${reg.created_at ? new Date(reg.created_at).toLocaleDateString('id-ID', {day:'2-digit', month:'short'}) : '-'}</td>
                        </tr>
                    `).join('');
                }
                
                showToast('Berhasil', 'Data diperbarui', 'success');
            } else {
                showToast('Gagal', data.error || 'Data tidak ditemukan', 'error');
            }
        })
        .catch(error => {
            loading.style.display = 'none';
            console.error('Error:', error);
            showToast('Error', 'Gagal memuat data', 'error');
        });
}

function refreshData() {
    location.reload();
}

function fixCreatedAt() {
    if (confirm('Perbaiki data tanpa tanggal?')) {
        const loading = document.getElementById('loadingOverlay');
        loading.style.display = 'flex';
        
        fetch('{{ route("admin.statistik.fix-created-at") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            loading.style.display = 'none';
            if (data.success) {
                showToast('Berhasil', data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast('Gagal', data.error || 'Gagal memperbaiki data', 'error');
            }
        })
        .catch(error => {
            loading.style.display = 'none';
            showToast('Error', 'Terjadi kesalahan', 'error');
        });
    }
}

function showToast(title, message, type) {
    const toast = document.getElementById('toastNotification');
    const icon = document.getElementById('toastIcon');
    const titleEl = document.getElementById('toastTitle');
    const msgEl = document.getElementById('toastMessage');
    
    toast.className = 'toast-notification';
    if (type === 'success') {
        toast.classList.add('toast-success');
        icon.innerHTML = '<i class="fas fa-check-circle"></i>';
    } else {
        toast.classList.add('toast-error');
        icon.innerHTML = '<i class="fas fa-exclamation-circle"></i>';
    }
    
    titleEl.textContent = title;
    msgEl.textContent = message;
    toast.style.display = 'flex';
    
    setTimeout(() => { toast.style.display = 'none'; }, 3000);
}

function hideToast() {
    document.getElementById('toastNotification').style.display = 'none';
}
</script>
@endpush