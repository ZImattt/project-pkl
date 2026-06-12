@extends('layouts.admin')

@section('title', 'Daftar Peserta - Admin Global Intermedia')

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
        --gray3: #dee2e6;
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
    
    .summary-row {
        display: flex;
        gap: 8px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }
    
    .summary-card {
        flex: 1;
        min-width: 60px;
        background: white;
        border-radius: 8px;
        padding: 10px;
        text-align: center;
        border: 1px solid var(--gray2);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .summary-card .value {
        font-size: 1.3rem;
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
    
    .summary-card.all .value { color: var(--gray6); }
    .summary-card.active .value { color: var(--green); }
    .summary-card.upcoming .value { color: var(--purple); }
    .summary-card.ending .value { color: var(--orange); }
    .summary-card.completed .value { color: var(--cyan); }
    
    .filter-section {
        background: white;
        border-radius: 8px;
        padding: 10px 12px;
        border: 1px solid var(--gray2);
        margin-bottom: 12px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .filter-row {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    
    .filter-label-text {
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--gray7);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }
    
    .filter-pills {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
    }
    
    .filter-pill {
        padding: 4px 12px;
        border-radius: 20px;
        background: var(--gray1);
        border: 1px solid var(--gray3);
        font-size: 0.65rem;
        font-weight: 500;
        color: var(--gray7);
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none;
        white-space: nowrap;
    }
    
    .filter-pill:hover {
        background: var(--gray2);
    }
    
    .filter-pill.active {
        background: var(--red);
        color: white;
        border-color: var(--red);
    }
    
    .search-box {
        position: relative;
        width: 100%;
    }
    
    .search-box input {
        width: 100%;
        padding: 7px 12px 7px 32px;
        border-radius: 20px;
        border: 1px solid var(--gray3);
        font-size: 0.7rem;
        background: white;
    }
    
    .search-box input:focus {
        border-color: var(--red);
        outline: none;
        box-shadow: 0 0 0 2px rgba(220,53,69,0.1);
    }
    
    .search-box i {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray6);
        font-size: 0.7rem;
    }
    
    .data-container {
        background: white;
        border-radius: 8px;
        border: 1px solid var(--gray2);
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .table {
        margin: 0;
        width: 100%;
        font-size: 0.7rem;
        table-layout: fixed;
    }
    
    .table th {
        padding: 10px 10px;
        background: #fafbfc;
        font-weight: 700;
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        color: var(--gray7);
        border-bottom: 2px solid var(--gray2);
        text-align: left;
        white-space: nowrap;
    }
    
    .table td {
        padding: 10px 10px;
        border-bottom: 1px solid var(--gray2);
        vertical-align: middle;
        color: var(--gray7);
    }
    
    .table tbody tr {
        cursor: pointer;
        transition: background 0.15s ease;
    }
    
    .table tbody tr:hover {
        background: #fafbfc;
    }
    
    .table tbody tr.row-active {
        background: rgba(220,53,69,0.03);
    }
    
    .badge-tipe {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 0.6rem;
        font-weight: 600;
    }
    
    .badge-tipe.individu {
        background: rgba(13,110,253,0.1);
        color: var(--blue);
    }
    
    .badge-tipe.kelompok {
        background: rgba(111,66,193,0.1);
        color: var(--purple);
    }
    
    .badge-status {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 10px;
        font-size: 0.6rem;
        font-weight: 600;
        white-space: nowrap;
    }
    
    .badge-status.active { background: rgba(40,167,69,0.1); color: #155724; }
    .badge-status.upcoming { background: rgba(111,66,193,0.1); color: #4b2e8a; }
    .badge-status.ending { background: rgba(253,126,20,0.1); color: #b85c00; }
    .badge-status.completed { background: rgba(23,162,184,0.1); color: #005c8a; }
    
    .progress-mini {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .progress-bar {
        width: 40px;
        height: 4px;
        background: var(--gray2);
        border-radius: 2px;
        overflow: hidden;
        flex-shrink: 0;
    }
    
    .progress-fill {
        height: 100%;
        border-radius: 2px;
    }
    
    .progress-fill.active { background: var(--green); }
    .progress-fill.upcoming { background: var(--purple); }
    .progress-fill.ending { background: var(--orange); }
    .progress-fill.completed { background: var(--cyan); }
    
    .progress-text {
        font-size: 0.6rem;
        color: var(--gray6);
    }
    
    .expand-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        transition: all 0.2s ease;
        font-size: 0.55rem;
        color: var(--gray6);
        background: var(--gray1);
    }
    
    .expand-icon.open {
        transform: rotate(180deg);
        background: var(--red);
        color: white;
    }
    
    .drawer-row {
        display: none;
    }
    
    .drawer-row.open {
        display: table-row;
    }
    
    .drawer-row td {
        padding: 0;
        background: #fafbfc;
    }
    
    .drawer-content {
        padding: 14px 16px;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px 16px;
        border-top: 1px solid var(--gray2);
    }
    
    .drawer-item {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .drawer-item.full {
        grid-column: 1 / -1;
    }
    
    .drawer-item-label {
        font-size: 0.55rem;
        color: var(--gray6);
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.3px;
    }
    
    .drawer-item-value {
        font-size: 0.7rem;
        color: var(--gray8);
        font-weight: 500;
        word-break: break-word;
    }
    
    .drawer-actions {
        grid-column: 1 / -1;
        display: flex;
        gap: 6px;
        padding-top: 8px;
        border-top: 1px solid var(--gray2);
        flex-wrap: wrap;
    }
    
    .drawer-btn {
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 0.65rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s ease;
    }
    
    .drawer-btn-detail {
        background: var(--red);
        color: white;
        border: none;
    }
    
    .drawer-btn-detail:hover {
        background: #b30000;
        color: white;
    }
    
    .drawer-btn-wa {
        background: #25D366;
        color: white;
        border: none;
    }
    
    .drawer-btn-wa:hover {
        background: #1da851;
        color: white;
    }
    
    .text-muted {
        color: var(--gray6) !important;
        font-size: 0.6rem;
    }
    
    .text-center { text-align: center; }
    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .pagination-wrapper {
        padding: 12px 16px;
        border-top: 1px solid var(--gray2);
        background: #fafbfc;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
    }
    
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 4px;
        margin: 0;
        padding: 0;
        list-style: none;
        flex-wrap: wrap;
    }
    
    .pagination .page-item {
        list-style: none;
    }
    
    .pagination .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 30px;
        height: 30px;
        padding: 0 8px;
        border: 1px solid var(--gray3);
        border-radius: 6px;
        color: var(--gray7);
        text-decoration: none;
        font-size: 0.7rem;
        font-weight: 500;
        background: white;
        transition: all 0.2s ease;
    }
    
    .pagination .page-link:hover {
        background: var(--red);
        color: white;
        border-color: var(--red);
    }
    
    .pagination .active .page-link {
        background: var(--red);
        color: white;
        border-color: var(--red);
    }
    
    .pagination .disabled .page-link {
        color: var(--gray6);
        background: var(--gray1);
        cursor: not-allowed;
        opacity: 0.6;
    }
    
    .pagination-info {
        text-align: center;
        font-size: 0.65rem;
        color: var(--gray6);
    }
    
    .empty-state {
        padding: 50px 20px;
        text-align: center;
        color: var(--gray6);
    }
    
    .empty-state i {
        font-size: 2.5rem;
        margin-bottom: 12px;
        opacity: 0.4;
    }
    
    .empty-state h5 {
        font-size: 0.85rem;
        margin-bottom: 6px;
        color: var(--gray7);
        font-weight: 600;
    }
    
    .empty-state p {
        font-size: 0.7rem;
    }
    
    @media (max-width: 768px) {
        .dashboard-header {
            margin-top: 50px;
        }
        
        .summary-card {
            min-width: 50px;
            flex: 1 1 28%;
            padding: 8px 6px;
        }
        
        .summary-card .value {
            font-size: 1.1rem;
        }
        
        .drawer-content {
            grid-template-columns: repeat(2, 1fr);
            gap: 8px 12px;
        }
        
        .drawer-actions {
            flex-direction: column;
        }
        
        .drawer-btn {
            justify-content: center;
            text-align: center;
        }
    }
    
    @media (max-width: 480px) {
        .summary-card {
            min-width: 40px;
            flex: 1 1 25%;
            padding: 6px 4px;
        }
        
        .summary-card .value {
            font-size: 1rem;
        }
        
        .filter-pill {
            padding: 3px 8px;
            font-size: 0.6rem;
        }
        
        .drawer-content {
            grid-template-columns: 1fr;
            gap: 6px;
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-header">
    <div>
        <h1 class="page-title">Daftar Peserta</h1>
        <p class="page-subtitle">Monitoring peserta yang sudah disetujui</p>
    </div>
    <div>
        <span class="badge-total">
            <i class="fas fa-users"></i>
            Total: <strong>{{ $participants->total() }} peserta</strong>
        </span>
    </div>
</div>

<div class="summary-row">
    <div class="summary-card all" onclick="filterByStatus('all')">
        <div class="value">{{ $totalParticipants ?? 0 }}</div>
        <div class="label">Semua</div>
    </div>
    <div class="summary-card active" onclick="filterByStatus('active')">
        <div class="value">{{ $stats['active'] ?? 0 }}</div>
        <div class="label">Aktif</div>
    </div>
    <div class="summary-card upcoming" onclick="filterByStatus('upcoming')">
        <div class="value">{{ $stats['upcoming'] ?? 0 }}</div>
        <div class="label">Mulai</div>
    </div>
    <div class="summary-card ending" onclick="filterByStatus('ending-soon')">
        <div class="value">{{ $stats['ending_soon'] ?? 0 }}</div>
        <div class="label">Selesai</div>
    </div>
    <div class="summary-card completed" onclick="filterByStatus('completed')">
        <div class="value">{{ $stats['completed'] ?? 0 }}</div>
        <div class="label">Done</div>
    </div>
</div>

<div class="filter-section">
    <div class="filter-row">
        <span class="filter-label-text">Tipe</span>
        <div class="filter-pills">
            <a href="#" class="filter-pill {{ !request('tipe') || request('tipe') == 'all' ? 'active' : '' }}" onclick="event.preventDefault(); filterByTipe('all')">Semua</a>
            <a href="#" class="filter-pill {{ request('tipe') == 'individu' ? 'active' : '' }}" onclick="event.preventDefault(); filterByTipe('individu')">Individu</a>
            <a href="#" class="filter-pill {{ request('tipe') == 'kelompok' ? 'active' : '' }}" onclick="event.preventDefault(); filterByTipe('kelompok')">Kelompok</a>
        </div>
    </div>
    
    <div class="filter-row">
        <span class="filter-label-text">Status</span>
        <div class="filter-pills">
            <a href="#" class="filter-pill {{ !request('status') || request('status') == 'all' ? 'active' : '' }}" onclick="event.preventDefault(); filterByStatus('all')">Semua</a>
            <a href="#" class="filter-pill {{ request('status') == 'active' ? 'active' : '' }}" onclick="event.preventDefault(); filterByStatus('active')">Aktif</a>
            <a href="#" class="filter-pill {{ request('status') == 'upcoming' ? 'active' : '' }}" onclick="event.preventDefault(); filterByStatus('upcoming')">Akan Mulai</a>
            <a href="#" class="filter-pill {{ request('status') == 'ending-soon' ? 'active' : '' }}" onclick="event.preventDefault(); filterByStatus('ending-soon')">Akan Selesai</a>
            <a href="#" class="filter-pill {{ request('status') == 'completed' ? 'active' : '' }}" onclick="event.preventDefault(); filterByStatus('completed')">Selesai</a>
        </div>
    </div>
    
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" id="searchInput" placeholder="Cari nama, kelompok, atau institusi..." value="{{ request('search') }}" onkeyup="debouncedSearch()">
    </div>
</div>

<div class="data-container">
    @if($participants->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th width="35">No</th>
                    <th width="55">Tipe</th>
                    <th>Nama / Institusi</th>
                    <th width="80">Status</th>
                    <th width="35"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($participants as $index => $p)
                    @php
                        $start = \Carbon\Carbon::parse($p->tanggal_mulai);
                        $end = \Carbon\Carbon::parse($p->tanggal_selesai);
                        $today = \Carbon\Carbon::now();
                        $totalDays = $start->diffInDays($end) + 1;
                        $passed = $today->greaterThan($start) ? $start->diffInDays(min($today, $end)) + 1 : 0;
                        $progress = $totalDays > 0 ? round(($passed / $totalDays) * 100) : 0;
                        
                        if ($today->greaterThan($end)) {
                            $status = 'completed';
                            $statusText = 'Selesai';
                        } elseif ($today->lessThan($start)) {
                            $status = 'upcoming';
                            $statusText = 'Akan Mulai';
                        } else {
                            $daysLeft = $today->diffInDays($end);
                            if ($daysLeft <= 7) {
                                $status = 'ending';
                                $statusText = 'Akan Selesai';
                            } else {
                                $status = 'active';
                                $statusText = 'Aktif';
                            }
                        }
                        
                        $tipe = $p->tipe_peserta ?? 'individu';
                        $institusi = $p->jenis_pendidikan == 'smk' ? $p->sekolah : $p->kuliah;
                        $jurusan = $p->jenis_pendidikan == 'smk' ? ($p->jurusan_smk ?? '-') : ($p->jurusan_kuliah ?? '-');
                        $noWa = preg_replace('/[^0-9]/', '', $p->no_whatsapp);
                        $itemNumber = $index + 1 + (($participants->currentPage() - 1) * $participants->perPage());
                        $rowId = 'row-' . $p->id;
                    @endphp
                    <tr id="{{ $rowId }}" class="main-row" onclick="toggleDrawer('{{ $rowId }}')">
                        <td class="text-center">{{ $itemNumber }}</td>
                        <td class="text-center">
                            <span class="badge-tipe {{ $tipe }}">
                                {{ $tipe == 'kelompok' ? 'Kel' : 'Ind' }}
                            </span>
                        </td>
                        <td>
                            <div class="text-truncate"><strong>{{ $p->nama_lengkap }}</strong></div>
                            <div class="text-truncate text-muted">{{ $institusi ?? '-' }}</div>
                        </td>
                        <td class="text-center">
                            <span class="badge-status {{ $status }}">{{ $statusText }}</span>
                        </td>
                        <td class="text-center">
                            <span class="expand-icon" id="icon-{{ $rowId }}">
                                <i class="fas fa-chevron-down"></i>
                            </span>
                        </td>
                    </tr>
                    <tr class="drawer-row" id="drawer-{{ $rowId }}">
                        <td colspan="5">
                            <div class="drawer-content">
                                <div class="drawer-item">
                                    <span class="drawer-item-label">Kode</span>
                                    <span class="drawer-item-value">{{ $p->registration_id ?? '-' }}</span>
                                </div>
                                <div class="drawer-item">
                                    <span class="drawer-item-label">Tipe</span>
                                    <span class="drawer-item-value">{{ $tipe == 'kelompok' ? 'Kelompok' : 'Individu' }}</span>
                                </div>
                                <div class="drawer-item">
                                    <span class="drawer-item-label">Nama Kelompok</span>
                                    <span class="drawer-item-value">{{ $p->nama_kelompok ?? '-' }}</span>
                                </div>
                                <div class="drawer-item">
                                    <span class="drawer-item-label">No. WA</span>
                                    <span class="drawer-item-value">{{ $p->no_whatsapp ?? '-' }}</span>
                                </div>
                                <div class="drawer-item">
                                    <span class="drawer-item-label">Institusi</span>
                                    <span class="drawer-item-value">{{ $institusi ?? '-' }}</span>
                                </div>
                                <div class="drawer-item">
                                    <span class="drawer-item-label">Jenjang</span>
                                    <span class="drawer-item-value">{{ $p->jenis_pendidikan == 'smk' ? 'SMK/SMA' : 'Kuliah' }}</span>
                                </div>
                                <div class="drawer-item">
                                    <span class="drawer-item-label">Jurusan</span>
                                    <span class="drawer-item-value">{{ $jurusan }}</span>
                                </div>
                                <div class="drawer-item">
                                    <span class="drawer-item-label">Periode</span>
                                    <span class="drawer-item-value">{{ $start->format('d M Y') }} - {{ $end->format('d M Y') }}</span>
                                </div>
                                <div class="drawer-item">
                                    <span class="drawer-item-label">Progress</span>
                                    <span class="drawer-item-value">
                                        <div class="progress-mini" style="margin-top: 3px;">
                                            <div class="progress-bar" style="width: 60px;">
                                                <div class="progress-fill {{ $status }}" style="width: {{ $progress }}%"></div>
                                            </div>
                                            <span class="progress-text">{{ $progress }}% ({{ $passed }}/{{ $totalDays }} hari)</span>
                                        </div>
                                    </span>
                                </div>
                                <div class="drawer-item">
                                    <span class="drawer-item-label">Status</span>
                                    <span class="drawer-item-value">
                                        <span class="badge-status {{ $status }}">{{ $statusText }}</span>
                                    </span>
                                </div>
                                <div class="drawer-actions">
                                    <a href="{{ $tipe == 'kelompok' ? route('admin.kelompok.show', $p->kelompok_id ?? $p->id) : route('admin.individu.show', $p->id) }}" class="drawer-btn drawer-btn-detail">
                                        <i class="fas fa-eye"></i> Detail Lengkap
                                    </a>
                                    @if(!empty($noWa))
                                    <a href="https://wa.me/{{ $noWa }}" target="_blank" class="drawer-btn drawer-btn-wa">
                                        <i class="fab fa-whatsapp"></i> Chat WA
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($participants->hasPages())
        <div class="pagination-wrapper">
            <ul class="pagination">
                @if ($participants->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $participants->previousPageUrl() }}" rel="prev">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                @endif

                @php
                    $currentPage = $participants->currentPage();
                    $lastPage = $participants->lastPage();
                    $start = max(1, $currentPage - 2);
                    $end = min($lastPage, $currentPage + 2);
                    
                    if ($start > 1) {
                        echo '<li class="page-item"><a class="page-link" href="' . $participants->url(1) . '">1</a></li>';
                        if ($start > 2) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                    }
                    
                    for ($i = $start; $i <= $end; $i++) {
                        if ($i == $currentPage) {
                            echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
                        } else {
                            echo '<li class="page-item"><a class="page-link" href="' . $participants->url($i) . '">' . $i . '</a></li>';
                        }
                    }
                    
                    if ($end < $lastPage) {
                        if ($end < $lastPage - 1) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                        echo '<li class="page-item"><a class="page-link" href="' . $participants->url($lastPage) . '">' . $lastPage . '</a></li>';
                    }
                @endphp

                @if ($participants->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $participants->nextPageUrl() }}" rel="next">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link"><i class="fas fa-chevron-right"></i></span>
                    </li>
                @endif
            </ul>
            
            <div class="pagination-info">
                {{ $participants->firstItem() ?? 0 }} - {{ $participants->lastItem() ?? 0 }} dari {{ $participants->total() }} peserta
            </div>
        </div>
        @endif
    @else
        <div class="empty-state">
            <i class="fas fa-users-slash"></i>
            <h5>Tidak Ada Data Peserta</h5>
            <p class="text-muted">Belum ada peserta yang disetujui</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    let searchTimeout;
    
    function debouncedSearch() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const search = document.getElementById('searchInput').value;
            const tipe = getActiveTipe();
            const status = getActiveStatus();
            window.location.href = `{{ route('admin.peserta.index') }}?tipe=${tipe}&status=${status}&search=${encodeURIComponent(search)}`;
        }, 500);
    }
    
    function filterByTipe(tipe) {
        const status = getActiveStatus();
        const search = document.getElementById('searchInput').value;
        window.location.href = `{{ route('admin.peserta.index') }}?tipe=${tipe}&status=${status}&search=${encodeURIComponent(search)}`;
    }
    
    function filterByStatus(status) {
        const tipe = getActiveTipe();
        const search = document.getElementById('searchInput').value;
        window.location.href = `{{ route('admin.peserta.index') }}?tipe=${tipe}&status=${status}&search=${encodeURIComponent(search)}`;
    }
    
    function getActiveTipe() {
        const firstRow = document.querySelector('.filter-row:first-of-type');
        if (firstRow) {
            const pills = firstRow.querySelectorAll('.filter-pill');
            for (let pill of pills) {
                if (pill.classList.contains('active')) {
                    const text = pill.textContent.trim();
                    if (text === 'Individu') return 'individu';
                    if (text === 'Kelompok') return 'kelompok';
                    return 'all';
                }
            }
        }
        return 'all';
    }
    
    function getActiveStatus() {
        const rows = document.querySelectorAll('.filter-row');
        if (rows.length >= 2) {
            const pills = rows[1].querySelectorAll('.filter-pill');
            for (let pill of pills) {
                if (pill.classList.contains('active')) {
                    const text = pill.textContent.trim();
                    if (text === 'Aktif') return 'active';
                    if (text === 'Akan Mulai') return 'upcoming';
                    if (text === 'Akan Selesai') return 'ending-soon';
                    if (text === 'Selesai') return 'completed';
                    return 'all';
                }
            }
        }
        return 'all';
    }
    
    function toggleDrawer(rowId) {
        const drawer = document.getElementById('drawer-' + rowId);
        const icon = document.getElementById('icon-' + rowId);
        const mainRow = document.getElementById(rowId);
        
        if (drawer.classList.contains('open')) {
            drawer.classList.remove('open');
            icon.classList.remove('open');
            mainRow.classList.remove('row-active');
        } else {
            document.querySelectorAll('.drawer-row.open').forEach(d => d.classList.remove('open'));
            document.querySelectorAll('.expand-icon.open').forEach(i => i.classList.remove('open'));
            document.querySelectorAll('.main-row.row-active').forEach(r => r.classList.remove('row-active'));
            
            drawer.classList.add('open');
            icon.classList.add('open');
            mainRow.classList.add('row-active');
        }
    }
</script>
@endpush