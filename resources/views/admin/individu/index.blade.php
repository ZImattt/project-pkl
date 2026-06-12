@extends('layouts.admin')

@section('title', 'Data Pendaftaran Individu - Admin Global Intermedia')

@push('styles')
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    
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
    
    .page-title { color: var(--red); font-weight: 700; font-size: 1.1rem; margin: 0; }
    .page-subtitle { color: var(--gray6); font-size: 0.65rem; margin-top: 2px; }
    
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

    .filter-row { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

    .filter-label-text {
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--gray7);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    .filter-pills { display: flex; gap: 4px; flex-wrap: wrap; }

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

    .filter-pill:hover { background: var(--gray2); }
    .filter-pill.active { background: var(--red); color: white; border-color: var(--red); }

    .search-box { position: relative; flex: 1; min-width: 150px; }

    .search-box input {
        width: 100%;
        padding: 6px 10px 6px 30px;
        border-radius: 20px;
        border: 1px solid var(--gray3);
        font-size: 0.7rem;
        background: white;
    }

    .search-box input:focus { border-color: var(--red); outline: none; box-shadow: 0 0 0 2px rgba(220,53,69,0.1); }
    .search-box i { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--gray6); font-size: 0.7rem; }

    .data-container {
        background: white;
        border-radius: 8px;
        border: 1px solid var(--gray2);
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }

    .table { margin: 0; width: 100%; font-size: 0.7rem; min-width: 680px; }

    .table th {
        padding: 10px 8px;
        background: #fafbfc;
        font-weight: 700;
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        color: var(--gray7);
        border-bottom: 2px solid var(--gray2);
        white-space: nowrap;
    }

    .table td { 
        padding: 10px 8px; 
        border-bottom: 1px solid var(--gray2); 
        vertical-align: middle; 
        color: var(--gray7);
    }
    .table tbody tr { transition: background 0.15s ease; }
    .table tbody tr:hover { background: #fafbfc; }

    .kode-badge {
        background: var(--gray7);
        color: white;
        font-family: monospace;
        font-size: 0.65rem;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
        display: inline-block;
    }

    .kode-badge:hover { background: var(--red); }
    .kode-badge.copied { background: var(--green) !important; }

    .badge-status {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 10px;
        font-size: 0.6rem;
        font-weight: 600;
        white-space: nowrap;
        transition: all 0.3s ease;
    }

    .badge-status.pending { background: rgba(255,193,7,0.15); color: #856404; }
    .badge-status.diterima { background: rgba(40,167,69,0.15); color: #155724; }
    .badge-status.ditolak { background: rgba(220,53,69,0.15); color: #721c24; }

    .badge-pendidikan {
        display: inline-block;
        padding: 3px 6px;
        border-radius: 10px;
        font-size: 0.55rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .badge-pendidikan.smk { background: rgba(23,162,184,0.1); color: #0d7a8a; }
    .badge-pendidikan.kuliah { background: rgba(13,110,253,0.1); color: #0a58ca; }

    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        border-radius: 6px;
        border: 1px solid var(--gray3);
        background: white;
        cursor: pointer;
        text-decoration: none;
        color: var(--gray6);
        font-size: 0.6rem;
        transition: all 0.2s ease;
        margin: 0 1px;
    }

    .btn-action:hover { transform: translateY(-1px); box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .btn-edit { color: var(--yellow); border-color: rgba(255,193,7,0.3); }
    .btn-edit:hover { background: var(--yellow); color: #333; border-color: var(--yellow); }
    .btn-detail { color: var(--blue); border-color: rgba(13,110,253,0.3); }
    .btn-detail:hover { background: var(--blue); color: white; border-color: var(--blue); }
    .btn-delete { color: var(--red); border-color: rgba(220,53,69,0.3); }
    .btn-delete:hover { background: var(--red); color: white; border-color: var(--red); }

    .action-group {
        display: flex;
        gap: 4px;
        justify-content: center;
        flex-wrap: nowrap;
    }

    .wa-link { color: #25D366; text-decoration: none; font-size: 0.65rem; font-weight: 500; }
    .wa-link:hover { color: #128C7E; }

    .text-muted { color: var(--gray6) !important; font-size: 0.55rem; }
    .text-center { text-align: center; }
    .text-truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: block; max-width: 120px; }

    /* PAGINATION */
    .pagination-wrapper {
        padding: 16px;
        border-top: 1px solid var(--gray2);
        background: #fafbfc;
    }

    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 6px;
        margin: 0;
        padding: 0;
        flex-wrap: wrap;
    }

    .pagination .page-item {
        list-style: none;
        margin: 0;
    }

    .pagination .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
        padding: 0 12px;
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--gray7);
        background: white;
        border: 1px solid var(--gray3);
        border-radius: 6px;
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
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
        cursor: default;
    }

    .pagination .disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
        background: var(--gray1);
    }

    .empty-state { padding: 50px 20px; text-align: center; color: var(--gray6); }
    .empty-state i { font-size: 2.5rem; margin-bottom: 12px; opacity: 0.4; }
    .empty-state h5 { font-size: 0.85rem; margin-bottom: 6px; color: var(--gray7); font-weight: 600; }
    .empty-state p { font-size: 0.7rem; }

    /* MODAL */
    .update-modal-overlay {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.5); z-index: 1050;
        display: none; align-items: center; justify-content: center;
    }
    .update-modal-overlay.show { display: flex; }

    .update-modal-container {
        background: white; border-radius: 12px;
        width: 90%; max-width: 400px; max-height: 85vh; overflow-y: auto;
    }

    .update-modal-header {
        padding: 14px 16px; border-bottom: 1px solid var(--gray2);
        display: flex; justify-content: space-between; align-items: center;
    }
    .update-modal-header h3 { margin: 0; font-size: 0.9rem; font-weight: 700; }
    .update-modal-close {
        background: var(--gray1); border: none; width: 28px; height: 28px;
        border-radius: 6px; cursor: pointer; font-size: 0.7rem; color: var(--gray6);
    }
    .update-modal-close:hover { background: var(--gray2); color: var(--red); }
    .update-modal-body { padding: 14px 16px; }

    .modal-info-card {
        background: var(--gray1); border-radius: 8px;
        padding: 10px; margin-bottom: 12px; border: 1px solid var(--gray2);
    }
    .modal-info-item { display: flex; gap: 8px; padding: 5px 0; font-size: 0.75rem; }
    .modal-info-item strong { color: var(--gray7); }
    .modal-info-item span { color: var(--gray8); }

    .form-group-modal { margin-bottom: 12px; }
    .form-group-modal label { display: block; font-weight: 600; margin-bottom: 4px; font-size: 0.7rem; color: var(--gray7); }
    .form-group-modal select, .form-group-modal textarea {
        width: 100%; padding: 6px 8px; border: 1px solid var(--gray3); border-radius: 6px; font-size: 0.75rem;
    }
    .form-group-modal select:focus, .form-group-modal textarea:focus { outline: none; border-color: var(--red); }

    .modal-actions { display: flex; gap: 8px; margin-top: 12px; }
    .btn-cancel-modal { flex: 1; padding: 7px; background: white; border: 1px solid var(--gray3); border-radius: 6px; font-size: 0.75rem; cursor: pointer; }
    .btn-update-modal { flex: 1; padding: 7px; background: var(--red); color: white; border: none; border-radius: 6px; font-size: 0.75rem; cursor: pointer; }
    .btn-update-modal:hover { background: #b30000; }

    /* LOADING & TOAST */
    .loading-overlay {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(255,255,255,0.8); display: none;
        justify-content: center; align-items: center; z-index: 9999;
    }
    .loading-spinner {
        width: 35px; height: 35px;
        border: 3px solid var(--gray2); border-top: 3px solid var(--red);
        border-radius: 50%; animation: spin 0.8s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    .toast-notification {
        position: fixed; top: 15px; right: 15px; min-width: 280px;
        background: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        padding: 12px 14px; display: none; align-items: center; gap: 10px;
        z-index: 10000; border-left: 4px solid; animation: slideIn 0.3s ease;
    }
    @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    .toast-success { border-left-color: var(--green); }
    .toast-error { border-left-color: var(--red); }
    .toast-icon { font-size: 1rem; }
    .toast-content { flex: 1; }
    .toast-title { font-weight: 700; font-size: 0.8rem; }
    .toast-message { font-size: 0.7rem; color: var(--gray6); }
    .toast-close { cursor: pointer; font-size: 0.7rem; color: var(--gray6); }

    /* MOBILE */
    @media (max-width: 768px) {
        .desktop-view { display: none; }
        .mobile-view { display: block; }
        .dashboard-header { margin-top: 50px; }
        .toast-notification { min-width: 250px; right: 10px; left: 10px; top: 10px; }
        .modal-actions { flex-direction: column; }
        .pagination { gap: 4px; }
        .pagination .page-link { min-width: 30px; height: 30px; padding: 0 8px; font-size: 0.65rem; }
    }
    @media (min-width: 769px) {
        .desktop-view { display: block; }
        .mobile-view { display: none; }
    }

    /* MOBILE CARDS */
    .mobile-card { background: white; border-radius: 8px; margin-bottom: 8px; border: 1px solid var(--gray2); }
    .mobile-card-header { padding: 10px 12px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; }
    .mobile-name { font-weight: 700; font-size: 0.8rem; color: var(--gray8); margin-bottom: 2px; }
    .mobile-meta { font-size: 0.6rem; color: var(--gray6); display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .mobile-card-body { padding: 10px 12px; border-top: 1px solid var(--gray2); background: #fafbfc; display: none; }
    .mobile-card-body.show { display: block; }
    .mobile-row { display: flex; padding: 5px 0; font-size: 0.7rem; border-bottom: 1px solid var(--gray2); }
    .mobile-row:last-child { border-bottom: none; }
    .mobile-label { width: 80px; color: var(--gray6); font-weight: 600; flex-shrink: 0; font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.3px; }
    .mobile-value { flex: 1; word-break: break-word; }
    .mobile-actions { display: flex; gap: 5px; margin-top: 8px; padding-top: 8px; border-top: 1px solid var(--gray2); flex-wrap: wrap; }
    .mobile-btn { flex: 1 1 45%; min-width: 70px; padding: 6px; text-align: center; border-radius: 6px; font-size: 0.65rem; font-weight: 600; cursor: pointer; border: none; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 4px; transition: all 0.2s ease; }
    .mobile-btn-edit { background: rgba(255,193,7,0.15); color: #856404; }
    .mobile-btn-detail { background: var(--red); color: white; }
    .mobile-btn-wa { background: #25D366; color: white; }
    .mobile-btn-delete { background: rgba(220,53,69,0.1); color: var(--red); }
    .expand-icon { font-size: 0.6rem; color: var(--gray6); transition: transform 0.2s ease; }
    .expand-icon.open { transform: rotate(180deg); }
</style>
@endpush

@section('content')
<div class="loading-overlay" id="loadingOverlay"><div class="loading-spinner"></div></div>

<div class="toast-notification" id="toastNotification">
    <div class="toast-icon" id="toastIcon"></div>
    <div class="toast-content">
        <div class="toast-title" id="toastTitle"></div>
        <div class="toast-message" id="toastMessage"></div>
    </div>
    <div class="toast-close" onclick="hideToast()"><i class="fas fa-times"></i></div>
</div>

<div class="dashboard-header">
    <div>
        <h1 class="page-title">Data Pendaftaran Individu</h1>
        <p class="page-subtitle">Kelola pendaftaran PKL & Magang</p>
    </div>
    <div>
        <span class="badge-total"><i class="fas fa-users"></i> Total: <strong>{{ $registrations->total() }} pendaftar</strong></span>
    </div>
</div>

<div class="filter-section">
    <div class="filter-row">
        <span class="filter-label-text">Status</span>
        <div class="filter-pills">
            <a href="{{ route('admin.individu.index', array_merge(request()->except('status'), ['status' => 'all'])) }}" class="filter-pill {{ request('status', 'all') == 'all' ? 'active' : '' }}">Semua</a>
            <a href="{{ route('admin.individu.index', array_merge(request()->except('status'), ['status' => 'pending'])) }}" class="filter-pill {{ request('status') == 'pending' ? 'active' : '' }}">Pending</a>
            <a href="{{ route('admin.individu.index', array_merge(request()->except('status'), ['status' => 'diterima'])) }}" class="filter-pill {{ request('status') == 'diterima' ? 'active' : '' }}">Diterima</a>
            <a href="{{ route('admin.individu.index', array_merge(request()->except('status'), ['status' => 'ditolak'])) }}" class="filter-pill {{ request('status') == 'ditolak' ? 'active' : '' }}">Tidak Diterima</a>
        </div>
    </div>
    <div class="filter-row">
        <span class="filter-label-text">Pendidikan</span>
        <div class="filter-pills">
            <a href="{{ route('admin.individu.index', array_merge(request()->except('jenis_pendidikan'), ['jenis_pendidikan' => 'all'])) }}" class="filter-pill {{ request('jenis_pendidikan', 'all') == 'all' ? 'active' : '' }}">Semua</a>
            <a href="{{ route('admin.individu.index', array_merge(request()->except('jenis_pendidikan'), ['jenis_pendidikan' => 'smk'])) }}" class="filter-pill {{ request('jenis_pendidikan') == 'smk' ? 'active' : '' }}">SMK</a>
            <a href="{{ route('admin.individu.index', array_merge(request()->except('jenis_pendidikan'), ['jenis_pendidikan' => 'kuliah'])) }}" class="filter-pill {{ request('jenis_pendidikan') == 'kuliah' ? 'active' : '' }}">Perguruan Tinggi</a>
        </div>
    </div>
    <div class="filter-row">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Cari nama, email, atau institusi..." value="{{ request('search') }}">
        </div>
    </div>
</div>

<div class="data-container">
    @if($registrations->count() > 0)
        <!-- DESKTOP TABLE - UKURAN KOLOM DIPERKECIL AGAR BUTTON 3 BERJEJER -->
        <div class="desktop-view">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="25">No</th>
                            <th width="70">Kode</th>
                            <th width="130">Nama</th>
                            <th width="50">Pend</th>
                            <th width="110">Institusi</th>
                            <th width="75">WhatsApp</th>
                            <th width="65">Tanggal</th>
                            <th width="65">Status</th>
                            <th width="70">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($registrations as $index => $reg)
                            @php
                                $statusClass = $reg->status;
                                $statusText = match($reg->status) {
                                    'pending' => 'Pending',
                                    'diterima' => 'Diterima',
                                    'ditolak' => 'Tidak Diterima',
                                    default => $reg->status
                                };
                                $institusi = $reg->jenis_pendidikan == 'smk' ? $reg->sekolah : $reg->kuliah;
                                $waNumber = preg_replace('/[^0-9]/', '', $reg->no_whatsapp);
                                $itemNumber = ($registrations->currentPage() - 1) * $registrations->perPage() + $loop->iteration;
                            @endphp
                            <tr id="row-{{ $reg->id }}">
                                <td class="text-center">{{ $itemNumber }}</td>
                                <td class="text-center">
                                    <span class="kode-badge" onclick="copyKode('{{ $reg->kode_pendaftaran }}', this)">{{ $reg->kode_pendaftaran }}</span>
                                </td>
                                <td>
                                    <div class="text-truncate"><strong>{{ $reg->nama_lengkap }}</strong></div>
                                    <small class="text-muted">{{ $reg->email }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge-pendidikan {{ $reg->jenis_pendidikan }}">{{ $reg->jenis_pendidikan == 'smk' ? 'SMK' : 'PT' }}</span>
                                </td>
                                <td>
                                    <div class="text-truncate">{{ $institusi ?? '-' }}</div>
                                    <small class="text-muted">{{ $reg->jenis_pendidikan == 'smk' ? ($reg->jurusan_smk ?? '') : ($reg->jurusan_kuliah ?? '') }}</small>
                                </td>
                                <td class="text-center">
                                    <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="wa-link"><i class="fab fa-whatsapp"></i> {{ substr($reg->no_whatsapp, 0, 12) }}</a>
                                </td>
                                <td class="text-center">{{ $reg->created_at->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <span class="badge-status {{ $statusClass }}" id="status-{{ $reg->id }}">{{ $statusText }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="action-group">
                                        <button onclick="openUpdateModal({{ $reg->id }}, '{{ $reg->status }}', '{{ addslashes($reg->catatan_admin ?? '') }}', '{{ addslashes($reg->nama_lengkap) }}', '{{ $reg->kode_pendaftaran }}')" class="btn-action btn-edit" title="Update"><i class="fas fa-edit"></i></button>
                                        <a href="{{ route('admin.individu.show', $reg->id) }}" class="btn-action btn-detail" title="Detail"><i class="fas fa-eye"></i></a>
                                        <button onclick="confirmDelete({{ $reg->id }}, '{{ addslashes($reg->nama_lengkap) }}')" class="btn-action btn-delete" title="Hapus"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MOBILE CARDS -->
        <div class="mobile-view">
            @foreach($registrations as $index => $reg)
                @php
                    $statusText = match($reg->status) {
                        'pending' => 'Pending',
                        'diterima' => 'Diterima',
                        'ditolak' => 'Tidak Diterima',
                        default => $reg->status
                    };
                    $institusi = $reg->jenis_pendidikan == 'smk' ? $reg->sekolah : $reg->kuliah;
                    $jurusan = $reg->jenis_pendidikan == 'smk' ? ($reg->jurusan_smk ?? '-') : ($reg->jurusan_kuliah ?? '-');
                    $waNumber = preg_replace('/[^0-9]/', '', $reg->no_whatsapp);
                @endphp
                <div class="mobile-card">
                    <div class="mobile-card-header" onclick="toggleMobileCard(this)">
                        <div style="flex:1;min-width:0;">
                            <div class="mobile-name">{{ $reg->nama_lengkap }}</div>
                            <div class="mobile-meta">
                                <span class="badge-pendidikan {{ $reg->jenis_pendidikan }}" style="font-size:0.55rem;padding:2px 6px;">{{ $reg->jenis_pendidikan == 'smk' ? 'SMK' : 'PT' }}</span>
                                <span>{{ $reg->email }}</span>
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
                            <span class="badge-status {{ $reg->status }}" style="font-size:0.55rem;padding:2px 8px;">{{ $statusText }}</span>
                            <span class="expand-icon"><i class="fas fa-chevron-down"></i></span>
                        </div>
                    </div>
                    <div class="mobile-card-body">
                        <div class="mobile-row">
                            <div class="mobile-label">Kode</div>
                            <div class="mobile-value"><span class="kode-badge" onclick="event.stopPropagation();copyKode('{{ $reg->kode_pendaftaran }}',this)" style="font-size:0.6rem;">{{ $reg->kode_pendaftaran }}</span></div>
                        </div>
                        <div class="mobile-row"><div class="mobile-label">Institusi</div><div class="mobile-value">{{ $institusi ?? '-' }}</div></div>
                        <div class="mobile-row"><div class="mobile-label">Jurusan</div><div class="mobile-value">{{ $jurusan }}</div></div>
                        <div class="mobile-row"><div class="mobile-label">WhatsApp</div><div class="mobile-value"><a href="https://wa.me/{{ $waNumber }}" target="_blank" class="wa-link">{{ $reg->no_whatsapp }}</a></div></div>
                        <div class="mobile-row"><div class="mobile-label">Tanggal</div><div class="mobile-value">{{ $reg->created_at->format('d M Y') }}</div></div>
                        @if($reg->catatan_admin)
                        <div class="mobile-row"><div class="mobile-label">Catatan</div><div class="mobile-value">{{ $reg->catatan_admin }}</div></div>
                        @endif
                        <div class="mobile-actions">
                            <button onclick="event.stopPropagation();openUpdateModal({{ $reg->id }},'{{ $reg->status }}','{{ addslashes($reg->catatan_admin ?? '') }}','{{ addslashes($reg->nama_lengkap) }}','{{ $reg->kode_pendaftaran }}')" class="mobile-btn mobile-btn-edit"><i class="fas fa-edit"></i> Update</button>
                            <a href="{{ route('admin.individu.show', $reg->id) }}" class="mobile-btn mobile-btn-detail" onclick="event.stopPropagation();"><i class="fas fa-eye"></i> Detail</a>
                            @if($waNumber)<a href="https://wa.me/{{ $waNumber }}" target="_blank" class="mobile-btn mobile-btn-wa" onclick="event.stopPropagation();"><i class="fab fa-whatsapp"></i> Chat</a>@endif
                            <button onclick="event.stopPropagation();confirmDelete({{ $reg->id }},'{{ addslashes($reg->nama_lengkap) }}')" class="mobile-btn mobile-btn-delete"><i class="fas fa-trash"></i> Hapus</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- PAGINATION -->
        @if($registrations->hasPages())
        <div class="pagination-wrapper">
            <ul class="pagination">
                @if ($registrations->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">&laquo; Prev</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $registrations->previousPageUrl() }}" rel="prev">&laquo; Prev</a></li>
                @endif

                @foreach ($registrations->getUrlRange(1, $registrations->lastPage()) as $page => $url)
                    @if ($page == $registrations->currentPage())
                        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach

                @if ($registrations->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $registrations->nextPageUrl() }}" rel="next">Next &raquo;</a></li>
                @else
                    <li class="page-item disabled"><span class="page-link">Next &raquo;</span></li>
                @endif
            </ul>
        </div>
        @endif
    @else
        <div class="empty-state">
            <i class="fas fa-folder-open"></i>
            <h5>Tidak Ada Data</h5>
            <p class="text-muted">Belum ada pendaftaran individu</p>
        </div>
    @endif
</div>

<!-- UPDATE MODAL -->
<div class="update-modal-overlay" id="updateModalOverlay">
    <div class="update-modal-container">
        <div class="update-modal-header">
            <h3><i class="fas fa-edit" style="color:var(--red);margin-right:6px;"></i> Update Status</h3>
            <button class="update-modal-close" onclick="closeUpdateModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="update-modal-body">
            <input type="hidden" id="updateId">
            <div class="modal-info-card">
                <div class="modal-info-item"><strong>Nama:</strong><span id="infoNama">-</span></div>
                <div class="modal-info-item"><strong>Kode:</strong><span id="infoKode">-</span></div>
            </div>
            <div class="form-group-modal">
                <label>Status</label>
                <select id="updateStatus">
                    <option value="pending">Pending</option>
                    <option value="diterima">Diterima</option>
                    <option value="ditolak">Tidak Diterima</option>
                </select>
            </div>
            <div class="form-group-modal">
                <label>Catatan Admin</label>
                <textarea id="updateCatatan" rows="2" placeholder="Tambahkan catatan..."></textarea>
            </div>
            <div class="modal-actions">
                <button class="btn-cancel-modal" onclick="closeUpdateModal()">Batal</button>
                <button class="btn-update-modal" onclick="submitUpdate()">Update</button>
            </div>
        </div>
    </div>
</div>

<!-- DELETE MODAL -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header"><h6 class="modal-title">Konfirmasi Hapus</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body text-center">
                <i class="fas fa-trash-alt fa-2x text-danger mb-2"></i>
                <p class="mb-1">Yakin ingin menghapus data?</p>
                <p class="text-muted small" id="deleteItemName"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm btn-danger" onclick="executeDelete()">Hapus</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let deleteModal, toastTimeout, currentDeleteId = null, searchTimeout;

document.addEventListener('DOMContentLoaded', function() {
    deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    document.getElementById('updateModalOverlay')?.addEventListener('click', function(e) { if (e.target === this) closeUpdateModal(); });
    document.getElementById('searchInput')?.addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const url = new URL(window.location.href);
            this.value ? url.searchParams.set('search', this.value) : url.searchParams.delete('search');
            window.location.href = url.toString();
        }, 500);
    });
});

function toggleMobileCard(header) {
    const body = header.nextElementSibling, icon = header.querySelector('.expand-icon');
    if (body.classList.contains('show')) {
        body.classList.remove('show');
        if(icon) icon.classList.remove('open');
    } else {
        document.querySelectorAll('.mobile-card-body.show').forEach(b => b.classList.remove('show'));
        document.querySelectorAll('.expand-icon.open').forEach(i => i.classList.remove('open'));
        body.classList.add('show');
        if(icon) icon.classList.add('open');
    }
}

function copyKode(kode, element) {
    navigator.clipboard?.writeText(kode).then(() => {
        element.classList.add('copied'); setTimeout(() => element.classList.remove('copied'), 1000);
        showToast('Berhasil', 'Kode berhasil disalin', 'success');
    }).catch(() => {
        const ta = document.createElement('textarea'); ta.value = kode; document.body.appendChild(ta); ta.select();
        document.execCommand('copy'); document.body.removeChild(ta);
        element.classList.add('copied'); setTimeout(() => element.classList.remove('copied'), 1000);
        showToast('Berhasil', 'Kode berhasil disalin', 'success');
    });
}

function openUpdateModal(id, status, catatan, nama, kode) {
    document.getElementById('updateId').value = id;
    document.getElementById('updateStatus').value = status;
    document.getElementById('updateCatatan').value = catatan || '';
    document.getElementById('infoNama').textContent = nama || '-';
    document.getElementById('infoKode').textContent = kode || '-';
    document.getElementById('updateModalOverlay').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeUpdateModal() { document.getElementById('updateModalOverlay').classList.remove('show'); document.body.style.overflow = ''; }

function submitUpdate() {
    const id = document.getElementById('updateId').value, status = document.getElementById('updateStatus').value, catatan = document.getElementById('updateCatatan').value;
    if (!id) return showToast('Error', 'ID tidak ditemukan', 'error');
    document.getElementById('loadingOverlay').style.display = 'flex';
    
    $.ajax({
        url: `/admin/individu/${id}/status`, method: 'PUT',
        data: { _token: '{{ csrf_token() }}', status: status, catatan_admin: catatan },
        success: function(r) {
            document.getElementById('loadingOverlay').style.display = 'none';
            if (r.success) {
                const badge = document.getElementById('status-' + id);
                if (badge) {
                    const texts = { 'pending': 'Pending', 'diterima': 'Diterima', 'ditolak': 'Tidak Diterima' };
                    badge.className = 'badge-status ' + status;
                    badge.textContent = texts[status] || status;
                    badge.style.transform = 'scale(1.1)'; setTimeout(() => badge.style.transform = 'scale(1)', 200);
                }
                closeUpdateModal(); showToast('Berhasil', 'Status berhasil diperbarui', 'success');
                if (typeof window.updateSidebarBadge === 'function') window.updateSidebarBadge(r.pending_individu, r.pending_kelompok);
            } else showToast('Gagal', r.message || 'Update gagal', 'error');
        },
        error: function(x) { document.getElementById('loadingOverlay').style.display = 'none'; showToast('Error', x.responseJSON?.message || 'Terjadi kesalahan', 'error'); }
    });
}

function confirmDelete(id, name) { currentDeleteId = id; document.getElementById('deleteItemName').innerHTML = `<strong>${name}</strong>`; deleteModal.show(); }

function executeDelete() {
    if (!currentDeleteId) return;
    deleteModal.hide(); document.getElementById('loadingOverlay').style.display = 'flex';
    
    $.ajax({
        url: `/admin/individu/${currentDeleteId}`, method: 'DELETE', data: { _token: '{{ csrf_token() }}' },
        success: function(r) {
            document.getElementById('loadingOverlay').style.display = 'none';
            if (r.success) {
                const row = document.getElementById('row-' + currentDeleteId);
                if (row) { row.style.opacity = '0'; row.style.transform = 'translateX(-20px)'; row.style.transition = 'all 0.3s ease'; setTimeout(() => row.remove(), 300); }
                showToast('Berhasil', 'Data berhasil dihapus', 'success');
                if (typeof window.updateSidebarBadge === 'function') window.updateSidebarBadge(r.pending_individu, r.pending_kelompok);
            } else showToast('Gagal', r.message || 'Gagal menghapus', 'error');
        },
        error: function(x) { document.getElementById('loadingOverlay').style.display = 'none'; showToast('Error', x.responseJSON?.message || 'Terjadi kesalahan', 'error'); }
    });
}

function showToast(title, message, type) {
    if (toastTimeout) clearTimeout(toastTimeout);
    const toast = document.getElementById('toastNotification'), icon = document.getElementById('toastIcon');
    toast.classList.remove('toast-success', 'toast-error');
    if (type === 'success') { toast.classList.add('toast-success'); icon.innerHTML = '<i class="fas fa-check-circle" style="color:var(--green);"></i>'; }
    else { toast.classList.add('toast-error'); icon.innerHTML = '<i class="fas fa-exclamation-circle" style="color:var(--red);"></i>'; }
    document.getElementById('toastTitle').textContent = title;
    document.getElementById('toastMessage').textContent = message;
    toast.style.display = 'flex';
    toastTimeout = setTimeout(() => hideToast(), 3000);
}

function hideToast() { document.getElementById('toastNotification').style.display = 'none'; if (toastTimeout) clearTimeout(toastTimeout); }
</script>
@endpush