@extends('layouts.admin')

@section('title', 'Detail Pendaftaran - Admin')

@section('content')

<style>
@media print {
    .app-header, .sidebar, .navbar, .mobile-nav, 
    #sidebar, .main-sidebar, header nav, .hamburger-menu,
    [class*="hamburger"], [class*="menu-toggle"], .nav-toggle {
        display: none !important;
    }
    
    body, html {
        margin: 0 !important;
        padding: 0 !important;
        background: white !important;
    }
    
    .detail-wrapper, .mobile-bar, .bottom-sheet, .modal, 
    .surat-pengantar-section, .surat-card, .surat-actions { 
        display: none !important; 
    }
    
    .print-surat { 
        display: block !important; 
        font-family: 'Times New Roman', Times, serif; 
        padding: 0.5cm; 
        max-width: 210mm; 
        margin: 0 auto; 
        background: white;
    }
    
    @page { 
        size: A4; 
        margin: 1cm; 
    }
}

.print-surat { 
    display: none; 
    font-size: 11pt;
    line-height: 1.3;
}

.watermark { 
    position: fixed; 
    top: 50%; 
    left: 50%; 
    transform: translate(-50%, -50%) rotate(-30deg); 
    opacity: 0.1; 
    text-align: center; 
    z-index: -1;
}

.watermark img { width: 100px; height: auto; }
.watermark div { font-size: 24pt; font-weight: bold; color: #dc3545; }

.kop { display: flex; align-items: center; gap: 15px; margin-bottom: 5px; }
.kop .logo { width: 70px; height: auto; }
.kop-text h2 { margin: 0; color: #dc3545; font-size: 18pt; }
.kop-text p { margin: 1px 0; font-size: 9pt; }
.line { height: 2px; background: #dc3545; margin: 5px 0; }
.ref { margin-bottom: 5px; font-size: 10pt; }
.tgl { text-align: right; font-size: 10pt; margin-bottom: 10px; font-style: italic; }
.data-print { margin-bottom: 10px; }
.status-print { width: 100%; border: 1px solid #ddd; padding: 5px; background: #f9f9f9; margin-bottom: 10px; font-size: 10pt; }
.subtitle { font-size: 12pt; font-weight: bold; margin: 8px 0 3px; color: #dc3545; border-bottom: 1px solid #dc3545; }
.data-table { width: 100%; font-size: 10pt; border-collapse: collapse; }
.data-table td { padding: 2px 0; vertical-align: top; }

.sts-pending { color: #b45309; font-weight: bold; background: #fffbeb; padding: 2px 8px; border-radius: 4px; }
.sts-diterima { color: #065f46; font-weight: bold; background: #ecfdf5; padding: 2px 8px; border-radius: 4px; }
.sts-ditolak { color: #991b1b; font-weight: bold; background: #fef2f2; padding: 2px 8px; border-radius: 4px; }

.ttd { margin-top: 20px; }
.ttd table { width: 100%; }
.ttd td { text-align: center; vertical-align: bottom; padding: 0 5px; }
.ttd small { font-size: 8pt; color: #666; }
.footer-print { margin-top: 10px; text-align: center; }
.footer-print hr { border: none; border-top: 1px solid #ccc; margin: 5px 0; }
.footer-print small { font-size: 7pt; color: #999; }

/* Toast Notification */
.toast-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    min-width: 300px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    z-index: 10000;
    border-left: 4px solid;
    animation: slideIn 0.3s ease;
}

.toast-success { 
    border-left-color: #28a745; 
    background: linear-gradient(135deg, #ffffff 0%, #f0fff4 100%); 
}

.toast-error { 
    border-left-color: #dc3545; 
    background: linear-gradient(135deg, #ffffff 0%, #fff5f5 100%); 
}

.toast-warning { 
    border-left-color: #ffc107; 
    background: linear-gradient(135deg, #ffffff 0%, #fffbeb 100%); 
}

.toast-icon { 
    font-size: 1.2rem; 
    width: 24px; 
    text-align: center; 
}

.toast-content { 
    flex: 1; 
}

.toast-title { 
    font-weight: 700; 
    font-size: 0.85rem; 
    margin-bottom: 2px; 
}

.toast-message { 
    font-size: 0.75rem; 
    color: #6c757d; 
    word-break: break-word; 
}

.toast-close { 
    cursor: pointer; 
    font-size: 0.8rem; 
    color: #6c757d; 
    padding: 4px; 
    background: none;
    border: none;
    transition: all 0.2s;
}

.toast-close:hover { 
    color: #dc3545; 
    transform: scale(1.1); 
}

@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

@keyframes slideOut {
    from { transform: translateX(0); opacity: 1; }
    to { transform: translateX(100%); opacity: 0; }
}

.toast-hiding {
    animation: slideOut 0.3s ease forwards;
}

.detail-wrapper { padding: 20px; max-width: 1400px; margin: 0 auto; }
.detail-header { display: flex; justify-content: space-between; align-items: center; background: white; padding: 15px 20px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
.header-left { display: flex; align-items: center; gap: 15px; }
.nav-btn { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: #f1f5f9; border-radius: 10px; color: #64748b; text-decoration: none; transition: all 0.2s; }
.nav-btn:hover { background: #dc3545; color: white; }
.header-title h1 { font-size: 1.3rem; margin: 0; color: #0f172a; }
.header-title span { font-size: 0.8rem; color: #64748b; }
.header-right { display: flex; gap: 10px; }
.status-badge { display: flex; align-items: center; gap: 8px; padding: 6px 15px; border-radius: 30px; font-weight: 600; font-size: 0.85rem; }
.status-badge.pending { background: #fffbeb; color: #b45309; border: 1px solid #fcd34d; }
.status-badge.diterima { background: #ecfdf5; color: #065f46; border: 1px solid #6ee7b7; }
.status-badge.ditolak { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
.print-btn { width: 40px; height: 40px; border-radius: 10px; background: #2563eb; color: white; border: none; cursor: pointer; transition: all 0.2s; }
.print-btn:hover { background: #1d4ed8; }

.detail-content { display: grid; grid-template-columns: 1fr 350px; gap: 20px; }
.left-col { display: flex; flex-direction: column; gap: 15px; }
.right-col { display: flex; flex-direction: column; gap: 15px; }

.card { background: white; border-radius: 16px; padding: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
.card-title { display: flex; align-items: center; gap: 10px; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0; }
.card-title i { color: #dc3545; width: 22px; }
.card-title h3 { margin: 0; font-size: 1rem; font-weight: 600; color: #0f172a; }

.profile-box { display: flex; gap: 20px; align-items: center; }
.avatar { width: 60px; height: 60px; background: #dc3545; border-radius: 16px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; font-weight: 600; }
.profile-info h2 { margin: 0 0 5px; font-size: 1.2rem; }
.contact { display: flex; gap: 8px; font-size: 0.85rem; color: #475569; margin-bottom: 8px; }
.school { display: inline-flex; align-items: center; gap: 6px; background: #f1f5f9; padding: 4px 12px; border-radius: 30px; font-size: 0.8rem; }

.info-row { display: flex; padding: 6px 0; border-bottom: 1px solid #f1f5f9; }
.info-row:last-child { border-bottom: none; }
.info-row .label { width: 130px; font-size: 0.8rem; color: #64748b; }
.info-row .value { flex: 1; font-size: 0.9rem; color: #0f172a; font-weight: 500; }

.period { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
.period-item { display: flex; align-items: center; gap: 10px; flex: 1; }
.period-icon { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; }
.period-icon.start { background: #3b82f6; }
.period-icon.end { background: #dc3545; }
.arrow { color: #94a3b8; }
.duration { display: inline-flex; align-items: center; gap: 8px; padding: 4px 15px; background: #f1f5f9; border-radius: 30px; font-size: 0.8rem; color: #2563eb; }

.edu-badge { display: inline-block; padding: 3px 12px; border-radius: 30px; font-size: 0.7rem; font-weight: 600; }
.edu-badge.smk { background: #fef3c7; color: #92400e; }
.edu-badge.kuliah { background: #dbeafe; color: #1e40af; }

.motivation-item { display: flex; gap: 12px; margin-bottom: 12px; }
.motiv-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.9rem; }
.motiv-icon.alasan { background: #f59e0b; }
.motiv-icon.skill { background: #10b981; }
.motiv-icon.harapan { background: #8b5cf6; }
.motivation-item small { font-size: 0.7rem; color: #64748b; display: block; }
.motivation-item p { margin: 2px 0 0; font-size: 0.85rem; }

.status-card { background: white; border-radius: 16px; padding: 18px; position: sticky; top: 20px; }
.status-title { display: flex; align-items: center; gap: 10px; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0; }
.status-title i { color: #dc3545; }
.status-title h3 { margin: 0; font-size: 1rem; }
.status-options { display: flex; flex-direction: column; gap: 10px; margin-bottom: 15px; }
.status-opt { cursor: pointer; }
.status-opt input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; pointer-events: none; }
.status-opt.active .status-item { border-color: #dc3545; background: #fee2e2; }
.status-item { display: flex; align-items: center; gap: 15px; padding: 12px; border: 2px solid #e2e8f0; border-radius: 12px; transition: all 0.2s; cursor: pointer; }
.status-item:hover { border-color: #94a3b8; background: #f8fafc; }
.status-item i { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; }
.status-item.pending i { background: #f59e0b; }
.status-item.approved i { background: #10b981; }
.status-item.rejected i { background: #ef4444; }
.status-item strong { display: block; font-size: 0.95rem; }
.status-item small { font-size: 0.7rem; color: #64748b; }

.realtime-clock { display: flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 10px 15px; border-radius: 12px; margin-bottom: 15px; font-size: 0.95rem; }
.realtime-clock i { font-size: 1rem; }
.realtime-clock .timezone { background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 30px; font-size: 0.7rem; margin-left: auto; }

.form-group { margin-bottom: 15px; }
.form-group label { display: flex; align-items: center; gap: 6px; font-size: 0.85rem; margin-bottom: 5px; color: #475569; }
.form-group textarea { width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.85rem; resize: vertical; transition: all 0.2s; }
.form-group textarea:focus { outline: none; border-color: #dc3545; box-shadow: 0 0 0 3px rgba(220,53,69,0.1); }

.btn-update { width: 100%; padding: 12px; background: #dc3545; color: white; border: none; border-radius: 12px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 10px; cursor: pointer; transition: all 0.2s; }
.btn-update:hover { background: #c82333; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(220,53,69,0.2); }
.btn-update:disabled { background: #94a3b8; cursor: not-allowed; transform: none; box-shadow: none; }

.action-group { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
.btn-wa { background: #25d366; color: white; padding: 10px; border-radius: 10px; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 5px; font-size: 0.85rem; transition: all 0.2s; }
.btn-wa:hover { background: #20bd59; transform: translateY(-1px); }
.btn-delete { background: white; color: #dc3545; border: 2px solid #fee2e2; padding: 10px; border-radius: 10px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 5px; font-size: 0.85rem; transition: all 0.2s; }
.btn-delete:hover { background: #fee2e2; }

.info-card { background: white; border-radius: 16px; padding: 18px; }
.info-title { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
.info-title i { color: #3b82f6; }
.info-title h3 { margin: 0; font-size: 1rem; }

.mobile-bar { display: none; position: fixed; bottom: 0; left: 0; right: 0; background: white; padding: 12px; box-shadow: 0 -4px 12px rgba(0,0,0,0.1); z-index: 100; align-items: center; gap: 12px; }
.mobile-bar-left { flex-shrink: 0; }
.mobile-btn { flex: 1; padding: 14px; background: #dc3545; color: white; border: none; border-radius: 12px; display: flex; align-items: center; justify-content: center; gap: 8px; font-weight: 600; position: relative; cursor: pointer; transition: all 0.2s; }
.mobile-btn i:last-child { position: absolute; right: 16px; }

.bottom-sheet { position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 1000; display: none; }
.bottom-sheet.show { display: block; }
.sheet-overlay { position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); }
.sheet-container { position: absolute; bottom: 0; left: 0; right: 0; background: white; border-radius: 20px 20px 0 0; padding: 15px; animation: slideUp 0.3s ease; max-height: 90vh; overflow-y: auto; }
.sheet-handle { width: 40px; height: 4px; background: #cbd5e1; border-radius: 2px; margin: 0 auto 15px; }
.sheet-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
.sheet-close { width: 36px; height: 36px; border-radius: 10px; background: #f1f5f9; border: none; cursor: pointer; transition: all 0.2s; }
.sheet-close:hover { background: #e2e8f0; }

.mobile-realtime { display: flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 8px 12px; border-radius: 10px; margin-bottom: 15px; font-size: 0.9rem; }
.mobile-realtime .timezone { background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 30px; font-size: 0.7rem; margin-left: auto; }

.mobile-status-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin-bottom: 15px; }
.mobile-opt { cursor: pointer; }
.mobile-opt.active .mobile-status { border-color: #dc3545; background: #fee2e2; }
.mobile-status { display: flex; flex-direction: column; align-items: center; gap: 5px; padding: 10px; border: 2px solid #e2e8f0; border-radius: 12px; transition: all 0.2s; }
.mobile-status:hover { border-color: #94a3b8; background: #f8fafc; }
.mobile-status i { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; }
.mobile-status.pending i { background: #f59e0b; }
.mobile-status.approved i { background: #10b981; }
.mobile-status.rejected i { background: #ef4444; }
.mobile-status span { font-size: 0.8rem; font-weight: 600; }

.mobile-form { margin-bottom: 15px; }
.mobile-form label { display: flex; align-items: center; gap: 5px; font-size: 0.85rem; margin-bottom: 5px; color: #475569; }
.mobile-form textarea { width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 10px; resize: vertical; }
.mobile-form textarea:focus { outline: none; border-color: #dc3545; box-shadow: 0 0 0 3px rgba(220,53,69,0.1); }

.mobile-update { width: 100%; padding: 14px; background: #dc3545; color: white; border: none; border-radius: 12px; font-weight: 600; margin-bottom: 8px; cursor: pointer; transition: all 0.2s; }
.mobile-update:hover { background: #c82333; }
.mobile-update:disabled { background: #94a3b8; cursor: not-allowed; }
.mobile-action { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
.mobile-wa { background: #25d366; color: white; padding: 12px; border-radius: 10px; text-align: center; text-decoration: none; transition: all 0.2s; }
.mobile-wa:hover { background: #20bd59; }
.mobile-del { background: white; color: #dc3545; border: 2px solid #fee2e2; padding: 12px; border-radius: 10px; cursor: pointer; transition: all 0.2s; }
.mobile-del:hover { background: #fee2e2; }
.mobile-footer-info { margin-top: 15px; text-align: center; color: #64748b; font-size: 0.75rem; padding-top: 10px; border-top: 1px dashed #e2e8f0; }

@keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }

.modal { position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 1100; display: none; align-items: center; justify-content: center; padding: 16px; }
.modal.show { display: flex; }
.modal-overlay { position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); }
.modal-container { position: relative; background: white; width: 100%; max-width: 320px; border-radius: 20px; padding: 20px; text-align: center; animation: modalPop 0.3s ease; }
@keyframes modalPop {
    from { transform: scale(0.9); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
.modal-container i { font-size: 3rem; color: #dc3545; margin-bottom: 10px; }
.modal-container h3 { margin: 0 0 5px; }
.modal-container p { margin: 0 0 5px; color: #475569; }
.warning { color: #dc3545; font-size: 0.8rem; }
.modal-actions { display: flex; gap: 10px; margin-top: 20px; }
.modal-actions button, .modal-actions form button { flex: 1; padding: 12px; border-radius: 10px; border: none; font-weight: 600; cursor: pointer; transition: all 0.2s; }
.btn-cancel { background: #f1f5f9; color: #334155; }
.btn-cancel:hover { background: #e2e8f0; }
.btn-confirm { background: #dc3545; color: white; display: flex; align-items: center; justify-content: center; gap: 5px; }
.btn-confirm:hover { background: #c82333; }

@media (max-width: 992px) {
    .detail-content { grid-template-columns: 1fr; }
    .right-col { display: none; }
    .mobile-bar { display: flex; }
    .toast-notification { 
        min-width: 280px; 
        top: 10px; 
        right: 10px; 
        left: 10px; 
    }
}
</style>

<div class="detail-wrapper">
    <div class="detail-header">
        <div class="header-left">
            <a href="{{ route('admin.dashboard') }}" class="nav-btn" title="Dashboard">
                <i class="fas fa-home"></i>
            </a>
            <a href="{{ route('admin.individu.index') }}" class="nav-btn" title="Kembali">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="header-title">
                <h1>Detail Pendaftaran</h1>
                <span>{{ $registration->kode_pendaftaran ?? 'IND-' . str_pad($registration->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>
        <div class="header-right">
            <span class="status-badge {{ $registration->status }}" id="headerStatusBadge">
                @php
                    $icon = match($registration->status) {
                        'pending' => 'clock',
                        'diterima' => 'check-circle',
                        'ditolak' => 'times-circle',
                        default => 'circle'
                    };
                    $statusText = match($registration->status) {
                        'pending' => 'Menunggu',
                        'diterima' => 'Diterima',
                        'ditolak' => 'Tidak Diterima',
                        default => 'Unknown'
                    };
                @endphp
                <i class="fas fa-{{ $icon }}"></i>
                {{ $statusText }}
            </span>
            <button onclick="window.print()" class="print-btn">
                <i class="fas fa-print"></i>
            </button>
        </div>
    </div>

    <div class="detail-content">
        <div class="left-col">
            <div class="card">
                <div class="profile-box">
                    <div class="avatar" style="background: #dc3545;">{{ strtoupper(substr($registration->nama_lengkap, 0, 1)) }}</div>
                    <div class="profile-info">
                        <h2>{{ $registration->nama_lengkap }}</h2>
                        <div class="contact">
                            <span><i class="fab fa-whatsapp"></i> {{ $registration->no_whatsapp }}</span>
                            <span>|</span>
                            <span><i class="fas fa-envelope"></i> {{ $registration->email }}</span>
                        </div>
                        <div class="school">
                            @if($registration->jenis_pendidikan == 'smk')
                                <i class="fas fa-school"></i> {{ $registration->sekolah }}
                            @else
                                <i class="fas fa-university"></i> {{ $registration->kuliah }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-title">
                    <i class="fas fa-user"></i>
                    <h3>Data Pribadi</h3>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="label">Jenis Kelamin</span>
                        <span class="value">{{ $registration->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Tempat, Tgl Lahir</span>
                        <span class="value">{{ $registration->tempat_lahir }}, {{ \Carbon\Carbon::parse($registration->tanggal_lahir)->format('d/m/Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Alamat</span>
                        <span class="value">{{ $registration->alamat_lengkap }}</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-title">
                    <i class="fas fa-calendar"></i>
                    <h3>Periode PKL</h3>
                </div>
                <div class="card-body">
                    @php
                        $start = \Carbon\Carbon::parse($registration->tanggal_mulai);
                        $end = \Carbon\Carbon::parse($registration->tanggal_selesai);
                        $duration = $start->diffInDays($end) + 1;
                    @endphp
                    <div class="period">
                        <div class="period-item">
                            <div class="period-icon start"><i class="fas fa-play"></i></div>
                            <div>
                                <small>Mulai</small>
                                <strong>{{ $start->format('d M Y') }}</strong>
                            </div>
                        </div>
                        <i class="fas fa-arrow-right arrow"></i>
                        <div class="period-item">
                            <div class="period-icon end"><i class="fas fa-flag"></i></div>
                            <div>
                                <small>Selesai</small>
                                <strong>{{ $end->format('d M Y') }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="duration">
                        <i class="fas fa-clock"></i> {{ $duration }} Hari
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-title">
                    <i class="fas fa-graduation-cap"></i>
                    <h3>Data Pendidikan</h3>
                </div>
                <div class="card-body">
                    <div class="edu-badge {{ $registration->jenis_pendidikan == 'kuliah' ? 'kuliah' : 'smk' }}" style="margin-bottom: 15px;">
                        {{ $registration->jenis_pendidikan == 'kuliah' ? 'PERGURUAN TINGGI' : 'SMK' }}
                    </div>
                    
                    @if($registration->jenis_pendidikan == 'smk')
                        <div class="info-row"><span class="label">Nama SMK</span><span class="value">{{ $registration->sekolah }}</span></div>
                        <div class="info-row"><span class="label">Jurusan</span><span class="value">{{ $registration->jurusan_smk }}</span></div>
                        <div class="info-row"><span class="label">Kelas / NIS</span><span class="value">{{ $registration->kelas }} / {{ $registration->nis }}</span></div>
                        <div class="info-row">
                            <span class="label">Guru Pembimbing</span>
                            <span class="value">
                                {{ $registration->guru_pembimbing }}<br>
                                <small class="text-muted">{{ $registration->no_hp_guru }}</small>
                            </span>
                        </div>
                    @else
                        @php
                            $waNumberDosen = preg_replace('/[^0-9]/', '', $registration->no_hp_dosen ?? '');
                        @endphp
                        <div class="info-row"><span class="label">Nama Kampus</span><span class="value">{{ $registration->kuliah }}</span></div>
                        <div class="info-row"><span class="label">Program Studi</span><span class="value">{{ $registration->jurusan_kuliah }}</span></div>
                        <div class="info-row"><span class="label">Semester / NIM</span><span class="value">{{ $registration->semester }} / {{ $registration->nim }}</span></div>
                        <div class="info-row">
                            <span class="label">Dosen Pembimbing</span>
                            <span class="value">
                                {{ $registration->dosen_pembimbing }}<br>
                                <small class="text-muted">{{ $registration->no_hp_dosen }}</small>
                                @if($waNumberDosen)
                                    <a href="https://wa.me/{{ $waNumberDosen }}" target="_blank" class="text-success ms-1">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                @endif
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-title">
                    <i class="fas fa-bullseye"></i>
                    <h3>Motivasi</h3>
                </div>
                <div class="card-body">
                    <div class="motivation-item">
                        <div class="motiv-icon alasan"><i class="fas fa-question"></i></div>
                        <div>
                            <small>Alasan PKL di GI</small>
                            <p>{{ $registration->alasan_pkl_gi }}</p>
                        </div>
                    </div>
                    <div class="motivation-item">
                        <div class="motiv-icon skill"><i class="fas fa-code"></i></div>
                        <div>
                            <small>Skill yang ingin dipelajari</small>
                            <p>{{ $registration->skill_ingin_dipelajari }}</p>
                        </div>
                    </div>
                    <div class="motivation-item">
                        <div class="motiv-icon harapan"><i class="fas fa-star"></i></div>
                        <div>
                            <small>Harapan setelah PKL</small>
                            <p>{{ $registration->harapan_setelah_pkl }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-title">
                    <i class="fas fa-envelope"></i>
                    <h3>Surat Pengantar</h3>
                </div>
                <div class="card-body">
                    @if($registration->file_surat_pengantar)
                        @php
                            $fileName = $registration->file_surat_pengantar;
                            $fullPath = public_path($fileName);
                            $fileExists = file_exists($fullPath);
                            $fileUrl = asset($fileName);
                            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                            $isPdf = $extension === 'pdf';
                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            $fileSize = $fileExists ? number_format(filesize($fullPath) / 1024, 1) : 0;
                        @endphp

                        @if($fileExists)
                            <div style="background: #f8fafc; border-radius: 12px; padding: 16px; border: 1px solid #e2e8f0;">
                                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                                    <div style="width: 48px; height: 48px; background: #ffffff; border-radius: 10px; display: flex; align-items: center; justify-content: center; border: 1px solid #e2e8f0;">
                                        @if($isPdf)
                                            <i class="fas fa-file-pdf" style="color: #dc2626; font-size: 24px;"></i>
                                        @elseif($isImage)
                                            <i class="fas fa-file-image" style="color: #059669; font-size: 24px;"></i>
                                        @else
                                            <i class="fas fa-file-alt" style="color: #2563eb; font-size: 24px;"></i>
                                        @endif
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; color: #0f172a; margin-bottom: 4px; word-break: break-all;">{{ basename($fileName) }}</div>
                                        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                                            <span style="background: #e2e8f0; padding: 2px 10px; border-radius: 30px; font-size: 11px; color: #475569;">
                                                @if($isPdf) PDF @elseif($isImage) Gambar @else File @endif
                                            </span>
                                            <span style="color: #64748b; font-size: 11px;">
                                                <i class="fas fa-database"></i> {{ $fileSize }} KB
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                    <a href="{{ route('admin.individu.download-surat', $registration->id) }}" 
                                       style="flex: 1; min-width: 120px; padding: 10px; background: white; border: 2px solid #2563eb; border-radius: 10px; color: #2563eb; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 6px; font-size: 13px; font-weight: 600;">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                    <a href="{{ $fileUrl }}" target="_blank"
                                       style="flex: 1; min-width: 120px; padding: 10px; background: #2563eb; border: none; border-radius: 10px; color: white; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 6px; font-size: 13px; font-weight: 600;">
                                        <i class="fas fa-eye"></i> Lihat File
                                    </a>
                                </div>

                                @if($isImage)
                                    <div style="margin-top: 16px; text-align: center; border-top: 1px solid #e2e8f0; padding-top: 16px;">
                                        <a href="{{ $fileUrl }}" target="_blank">
                                            <img src="{{ $fileUrl }}" alt="Preview" style="max-width: 100%; max-height: 200px; border-radius: 8px; border: 1px solid #e2e8f0;">
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div style="background: #fee2e2; border: 1px solid #fecaca; border-radius: 12px; padding: 20px; text-align: center; color: #991b1b;">
                                <i class="fas fa-exclamation-triangle" style="font-size: 24px; margin-bottom: 8px;"></i>
                                <p style="margin: 0; font-weight: 600;">File tidak ditemukan</p>
                            </div>
                        @endif
                    @else
                        <div style="background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; text-align: center; color: #64748b;">
                            <i class="fas fa-exclamation-circle" style="font-size: 24px; margin-bottom: 8px;"></i>
                            <p style="margin: 0;">Tidak ada file surat pengantar</p>
                        </div>
                    @endif
                </div>
            </div>

            @if($registration->cv_ind)
            <div class="card">
                <div class="card-title">
                    <i class="fas fa-file-alt"></i>
                    <h3>CV / Portofolio</h3>
                </div>
                <div class="card-body">
                    @php
                        $cvFile = $registration->cv_ind;
                        $cvFullPath = public_path($cvFile);
                        $cvExists = file_exists($cvFullPath);
                        $cvUrl = asset($cvFile);
                        $cvSize = $cvExists ? number_format(filesize($cvFullPath) / 1024, 1) : 0;
                        $cvExt = strtolower(pathinfo($cvFile, PATHINFO_EXTENSION));
                    @endphp

                    @if($cvExists)
                        <div style="background: #f8fafc; border-radius: 12px; padding: 16px; border: 1px solid #e2e8f0;">
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                                <div style="width: 48px; height: 48px; background: #ffffff; border-radius: 10px; display: flex; align-items: center; justify-content: center; border: 1px solid #e2e8f0;">
                                    <i class="fas fa-file-{{ $cvExt == 'pdf' ? 'pdf' : 'alt' }}" style="color: {{ $cvExt == 'pdf' ? '#dc2626' : '#2563eb' }}; font-size: 24px;"></i>
                                </div>
                                <div style="flex: 1;">
                                    <div style="font-weight: 600; color: #0f172a; margin-bottom: 4px;">{{ basename($cvFile) }}</div>
                                    <span style="color: #64748b; font-size: 11px;">
                                        <i class="fas fa-database"></i> {{ $cvSize }} KB
                                    </span>
                                </div>
                            </div>
                            <div style="display: flex; gap: 8px;">
                                <a href="{{ route('admin.individu.download-cv', $registration->id) }}" 
                                   class="btn-download" style="flex: 1; text-align: center; padding: 12px; background: #10b981; color: white; text-decoration: none; border-radius: 10px;">
                                    <i class="fas fa-download me-2"></i> Download CV
                                </a>
                                <a href="{{ $cvUrl }}" target="_blank"
                                   style="flex: 1; text-align: center; padding: 12px; background: #2563eb; color: white; text-decoration: none; border-radius: 10px;">
                                    <i class="fas fa-eye me-2"></i> Lihat
                                </a>
                            </div>
                        </div>
                    @else
                        <div style="background: #fee2e2; border: 1px solid #fecaca; border-radius: 12px; padding: 20px; text-align: center; color: #991b1b;">
                            <i class="fas fa-exclamation-triangle"></i> File CV tidak ditemukan
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="right-col">
            <div class="status-card">
                <div class="status-title">
                    <i class="fas fa-edit"></i>
                    <h3>Update Status</h3>
                </div>
                
                <div class="realtime-clock">
                    <i class="fas fa-clock"></i>
                    <span id="realtimeClock"></span>
                    <span class="timezone">WIB</span>
                </div>
                
                <form id="statusForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="status-options">
                        <label class="status-opt {{ $registration->status == 'pending' ? 'active' : '' }}">
                            <input type="radio" name="status" value="pending" {{ $registration->status == 'pending' ? 'checked' : '' }}>
                            <div class="status-item pending">
                                <i class="fas fa-clock"></i>
                                <div>
                                    <strong>Pending</strong>
                                    <small>Menunggu verifikasi</small>
                                </div>
                            </div>
                        </label>
                        
                        <label class="status-opt {{ $registration->status == 'diterima' ? 'active' : '' }}">
                            <input type="radio" name="status" value="diterima" {{ $registration->status == 'diterima' ? 'checked' : '' }}>
                            <div class="status-item approved">
                                <i class="fas fa-check-circle"></i>
                                <div>
                                    <strong>Diterima</strong>
                                    <small>Pendaftaran disetujui</small>
                                </div>
                            </div>
                        </label>
                        
                        <label class="status-opt {{ $registration->status == 'ditolak' ? 'active' : '' }}">
                            <input type="radio" name="status" value="ditolak" {{ $registration->status == 'ditolak' ? 'checked' : '' }}>
                            <div class="status-item rejected">
                                <i class="fas fa-times-circle"></i>
                                <div>
                                    <strong>Tidak Diterima</strong>
                                    <small>Pendaftaran tidak disetujui</small>
                                </div>
                            </div>
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-sticky-note"></i> Catatan Admin</label>
                        <textarea name="catatan_admin" id="adminNotes" rows="3" placeholder="Tambahkan catatan...">{{ $registration->catatan_admin }}</textarea>
                    </div>
                    
                    <button type="submit" class="btn-update" id="submitBtn">
                        <i class="fas fa-save"></i> Update Status
                    </button>
                    
                    <div class="info-waktu" style="background: #f8f9fa; padding: 10px; border-radius: 10px; margin-bottom: 10px; font-size: 0.8rem;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 5px; color: #2563eb;">
                            <i class="fas fa-calendar-alt"></i>
                            <span><strong>Tanggal Daftar:</strong> {{ $registration->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i:s') }} WIB</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px; color: #10b981;">
                            <i class="fas fa-history"></i>
                            <span><strong>Update Terakhir:</strong> <span id="lastUpdateStatus">{{ $registration->updated_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i:s') }} WIB</span></span>
                        </div>
                    </div>
                    
                    @php
                        $waNumber = preg_replace('/[^0-9]/', '', $registration->no_whatsapp);
                    @endphp
                    <div class="action-group">
                        <a href="https://wa.me/{{ $waNumber }}?text=Halo%20{{ urlencode($registration->nama_lengkap) }}%2C%20kami%20dari%20Global%20Intermedia%20Nusantara" target="_blank" class="btn-wa">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                        <button type="button" onclick="confirmDelete()" class="btn-delete">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </form>
            </div>

            <div class="info-card">
                <div class="info-title">
                    <i class="fas fa-info-circle"></i>
                    <h3>Informasi</h3>
                </div>
                <div class="info-body">
                    <div class="info-row">
                        <span class="label">ID Registrasi</span>
                        <span class="value">#{{ $registration->id }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Kode Pendaftaran</span>
                        <span class="value">{{ $registration->kode_pendaftaran ?? 'IND-' . str_pad($registration->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Jenis Pendidikan</span>
                        <span class="value">{{ $registration->jenis_pendidikan == 'smk' ? 'SMK' : 'Kuliah' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Tanggal Daftar</span>
                        <span class="value">{{ $registration->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Terakhir Update</span>
                        <span class="value" id="lastUpdate">{{ $registration->updated_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Diupdate oleh</span>
                        <span class="value">{{ auth()->user()->name ?? 'Admin' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mobile-bar">
        <div class="mobile-bar-left">
            <a href="{{ route('admin.individu.index') }}" class="nav-btn" title="Kembali">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
        <button onclick="openBottomSheet()" class="mobile-btn">
            <i class="fas fa-edit"></i>
            <span>Update Status</span>
            <i class="fas fa-chevron-up"></i>
        </button>
    </div>

    <div class="bottom-sheet" id="mobileBottomSheet">
        <div class="sheet-overlay" onclick="closeBottomSheet()"></div>
        <div class="sheet-container">
            <div class="sheet-handle"></div>
            <div class="sheet-header">
                <h4>Update Status</h4>
                <button class="sheet-close" onclick="closeBottomSheet()"><i class="fas fa-times"></i></button>
            </div>
            <div class="sheet-body">
                <div class="mobile-realtime">
                    <i class="fas fa-clock"></i>
                    <span id="mobileRealtimeClock"></span>
                    <span class="timezone">WIB</span>
                </div>
                
                <form id="mobileStatusForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="mobile-status-grid">
                        <label class="mobile-opt {{ $registration->status == 'pending' ? 'active' : '' }}">
                            <input type="radio" name="status" value="pending" {{ $registration->status == 'pending' ? 'checked' : '' }} hidden>
                            <div class="mobile-status pending">
                                <i class="fas fa-clock"></i>
                                <span>Pending</span>
                            </div>
                        </label>
                        <label class="mobile-opt {{ $registration->status == 'diterima' ? 'active' : '' }}">
                            <input type="radio" name="status" value="diterima" {{ $registration->status == 'diterima' ? 'checked' : '' }} hidden>
                            <div class="mobile-status approved">
                                <i class="fas fa-check-circle"></i>
                                <span>Diterima</span>
                            </div>
                        </label>
                        <label class="mobile-opt {{ $registration->status == 'ditolak' ? 'active' : '' }}">
                            <input type="radio" name="status" value="ditolak" {{ $registration->status == 'ditolak' ? 'checked' : '' }} hidden>
                            <div class="mobile-status rejected">
                                <i class="fas fa-times-circle"></i>
                                <span>Tidak Diterima</span>
                            </div>
                        </label>
                    </div>
                    
                    <div class="mobile-form">
                        <label><i class="fas fa-sticky-note"></i> Catatan Admin</label>
                        <textarea name="catatan_admin" id="mobileAdminNotes" rows="2" placeholder="Tambahkan catatan...">{{ $registration->catatan_admin }}</textarea>
                    </div>
                    
                    <button type="submit" class="mobile-update" id="mobileSubmitBtn">
                        <i class="fas fa-save"></i> Update Status
                    </button>
                    
                    @php
                        $waNumber = preg_replace('/[^0-9]/', '', $registration->no_whatsapp);
                    @endphp
                    <div class="mobile-action">
                        <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="mobile-wa">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                        <button type="button" onclick="confirmDelete()" class="mobile-del">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="deleteModal">
        <div class="modal-overlay" onclick="closeModal('deleteModal')"></div>
        <div class="modal-container">
            <i class="fas fa-exclamation-triangle"></i>
            <h3>Hapus Data?</h3>
            <p>Yakin ingin menghapus pendaftaran <strong>{{ $registration->nama_lengkap }}</strong>?</p>
            <p class="warning">Data akan hilang permanent!</p>
            <div class="modal-actions">
                <button onclick="closeModal('deleteModal')" class="btn-cancel">Batal</button>
                <button type="button" class="btn-confirm" onclick="executeDelete()"><i class="fas fa-trash"></i> Hapus</button>
            </div>
        </div>
    </div>
</div>

<div class="print-surat">
    <div class="watermark">
        <img src="{{ asset('images/logo_gi.png') }}" alt="logo">
        <div>GLOBAL INTERMEDIA NUSANTARA</div>
    </div>

    <div class="kop">
        <img src="{{ asset('images/logo_gi.png') }}" alt="Logo GI" class="logo">
        <div class="kop-text">
            <h2>GLOBAL INTERMEDIA NUSANTARA</h2>
            <p>Jl. Raya ITS No. 56, Keputih, Sukolilo, Surabaya • Jawa Timur 60111</p>
            <p>(031) 12345678 • info@gi.co.id • www.globalintermedia.com</p>
        </div>
    </div>
    <div class="line"></div>

    <div class="ref">
        <table style="width:100%;">
            <tr><td width="70">Nomor</td><td width="10">:</td><td>GI/HRD/{{ date('Ymd') }}/IND-{{ str_pad($registration->id, 4, '0', STR_PAD_LEFT) }}</td></tr>
            <tr><td width="70">Lampiran</td><td width="10">:</td><td>1 (satu) berkas</td></tr>
            <tr><td width="70">Perihal</td><td width="10">:</td><td>Data Pendaftaran Praktik Kerja Lapangan (PKL)</td></tr>
        </table>
    </div>

    <div class="tgl">Surabaya, {{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('d F Y') }}</div>

    <div class="data-print">
        <table class="status-print" style="width:100%;">
            <tr>
                <td width="130"><strong>Status Pendaftaran</strong></td>
                <td width="10">:</td>
                <td>
                    @if($registration->status == 'pending')
                        <span class="sts-pending">⏳ MENUNGGU VERIFIKASI</span>
                    @elseif($registration->status == 'diterima')
                        <span class="sts-diterima">DITERIMA</span>
                    @else
                        <span class="sts-ditolak">❌ TIDAK DITERIMA</span>
                    @endif
                </td>
            </tr>
            @if($registration->catatan_admin)
            <tr><td><strong>Catatan Admin</strong></td><td>:</td><td>{{ $registration->catatan_admin }}</td></tr>
            @endif
        </table>

        <div class="subtitle">A. DATA PRIBADI</div>
        <table class="data-table" style="width:100%;">
            <tr><td width="130">Nama Lengkap</td><td width="10">:</td><td>{{ $registration->nama_lengkap }}</td></tr>
            <tr><td>Jenis Kelamin</td><td>:</td><td>{{ $registration->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
            <tr><td>Tempat, Tanggal Lahir</td><td>:</td><td>{{ $registration->tempat_lahir }}, {{ \Carbon\Carbon::parse($registration->tanggal_lahir)->format('d F Y') }}</td></tr>
            <tr><td>Alamat Lengkap</td><td>:</td><td>{{ $registration->alamat_lengkap }}</td></tr>
            <tr><td>Nomor WhatsApp</td><td>:</td><td>{{ $registration->no_whatsapp }}</td></tr>
            <tr><td>Email</td><td>:</td><td>{{ $registration->email }}</td></tr>
        </table>

        <div class="subtitle">B. DATA PENDIDIKAN</div>
        <table class="data-table" style="width:100%;">
            <tr><td width="130">Jenis Pendidikan</td><td width="10">:</td><td>{{ $registration->jenis_pendidikan == 'smk' ? 'SMK' : 'PERGURUAN TINGGI' }}</td></tr>
            @if($registration->jenis_pendidikan == 'smk')
                <tr><td>Nama Sekolah</td><td>:</td><td>{{ $registration->sekolah }}</td></tr>
                <tr><td>Jurusan</td><td>:</td><td>{{ $registration->jurusan_smk }}</td></tr>
                <tr><td>Kelas / NIS</td><td>:</td><td>{{ $registration->kelas }} / {{ $registration->nis }}</td></tr>
                <tr><td>Guru Pembimbing</td><td>:</td><td>{{ $registration->guru_pembimbing }} ({{ $registration->no_hp_guru }})</td></tr>
            @else
                <tr><td>Nama Kampus</td><td>:</td><td>{{ $registration->kuliah }}</td></tr>
                <tr><td>Program Studi</td><td>:</td><td>{{ $registration->jurusan_kuliah }}</td></tr>
                <tr><td>Semester / NIM</td><td>:</td><td>{{ $registration->semester }} / {{ $registration->nim }}</td></tr>
                <tr><td>Dosen Pembimbing</td><td>:</td><td>{{ $registration->dosen_pembimbing }} ({{ $registration->no_hp_dosen }})</td></tr>
            @endif
        </table>

        <div class="subtitle">C. PERIODE PKL</div>
        <table class="data-table" style="width:100%;">
            <tr><td width="130">Tanggal Mulai</td><td width="10">:</td><td>{{ \Carbon\Carbon::parse($registration->tanggal_mulai)->format('d F Y') }}</td></tr>
            <tr><td>Tanggal Selesai</td><td>:</td><td>{{ \Carbon\Carbon::parse($registration->tanggal_selesai)->format('d F Y') }}</td></tr>
            <tr><td>Durasi</td><td>:</td><td>{{ $duration }} Hari</td></tr>
        </table>

        <div class="subtitle">D. MOTIVASI</div>
        <table class="data-table" style="width:100%;">
            <tr><td width="130">Alasan PKL di GI</td><td width="10">:</td><td>{{ $registration->alasan_pkl_gi }}</td></tr>
            <tr><td>Skill yang Ingin Dipelajari</td><td>:</td><td>{{ $registration->skill_ingin_dipelajari }}</td></tr>
            <tr><td>Harapan Setelah PKL</td><td>:</td><td>{{ $registration->harapan_setelah_pkl }}</td></tr>
        </table>
    </div>

    <div class="ttd">
        <table style="width:100%;">
            <tr>
                <td style="text-align:center;">Mengetahui,<br><small>Pembimbing</small><br><br><br><br>(____________________)</td>
                <td style="text-align:center;">Menyetujui,<br><small>Pembimbing Lapangan</small><br><br><br><br>(____________________)</td>
                <td style="text-align:center;">Hormat Kami,<br><small>Manajer HRD</small><br><br><br><br>(____________________)</td>
            </tr>
        </table>
    </div>

    <div class="footer-print">
        <hr>
        <small>Dicetak: {{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('d/m/Y H:i:s') }} WIB</small>
    </div>
</div>

@push('scripts')
<script>
let toastTimeout;

function updateClock() {
    const now = new Date();
    const wibTime = new Date(now.getTime() + (7 * 60 * 60 * 1000));
    
    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    
    const dayName = days[wibTime.getUTCDay()];
    const date = wibTime.getUTCDate();
    const month = months[wibTime.getUTCMonth()];
    const year = wibTime.getUTCFullYear();
    const hours = wibTime.getUTCHours().toString().padStart(2, '0');
    const minutes = wibTime.getUTCMinutes().toString().padStart(2, '0');
    const seconds = wibTime.getUTCSeconds().toString().padStart(2, '0');
    
    const timeString = `${dayName}, ${date} ${month} ${year} ${hours}:${minutes}:${seconds}`;
    
    const clockElement = document.getElementById('realtimeClock');
    if (clockElement) clockElement.textContent = timeString;
    
    const mobileClockElement = document.getElementById('mobileRealtimeClock');
    if (mobileClockElement) mobileClockElement.textContent = timeString;
}

function showToast(title, message, type) {
    const existingToast = document.querySelector('.toast-notification');
    if (existingToast) {
        existingToast.classList.add('toast-hiding');
        setTimeout(() => {
            if (existingToast.parentNode) existingToast.remove();
        }, 300);
    }
    
    if (toastTimeout) clearTimeout(toastTimeout);
    
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    
    let iconHtml = '';
    let borderColor = '';
    let iconColor = '';
    let bgGradient = '';
    
    if (type === 'success') {
        borderColor = '#10b981';
        iconColor = '#059669';
        bgGradient = 'linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%)';
        iconHtml = '<i class="fas fa-check-circle"></i>';
    } else if (type === 'error') {
        borderColor = '#ef4444';
        iconColor = '#dc2626';
        bgGradient = 'linear-gradient(135deg, #fee2e2 0%, #fecaca 100%)';
        iconHtml = '<i class="fas fa-times-circle"></i>';
    } else if (type === 'warning') {
        borderColor = '#f59e0b';
        iconColor = '#d97706';
        bgGradient = 'linear-gradient(135deg, #fef3c7 0%, #fde68a 100%)';
        iconHtml = '<i class="fas fa-exclamation-triangle"></i>';
    } else if (type === 'info') {
        borderColor = '#3b82f6';
        iconColor = '#2563eb';
        bgGradient = 'linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%)';
        iconHtml = '<i class="fas fa-info-circle"></i>';
    }
    
    toast.style.borderLeftColor = borderColor;
    toast.style.background = bgGradient;
    toast.style.boxShadow = '0 10px 25px rgba(0,0,0,0.1), 0 4px 10px rgba(0,0,0,0.05)';
    
    toast.innerHTML = `
        <div class="toast-icon" style="color: ${iconColor}; font-size: 1.5rem;">
            ${iconHtml}
        </div>
        <div class="toast-content">
            <div class="toast-title" style="color: #1e293b;">${title}</div>
            <div class="toast-message" style="color: #475569;">${message}</div>
        </div>
        <button class="toast-close" onclick="this.parentElement.classList.add('toast-hiding'); setTimeout(() => this.parentElement.remove(), 300);">
            <i class="fas fa-times" style="color: #64748b;"></i>
        </button>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
        toast.style.opacity = '1';
    }, 10);
    
    toastTimeout = setTimeout(() => {
        if (toast.parentNode) {
            toast.classList.add('toast-hiding');
            setTimeout(() => {
                if (toast.parentNode) toast.remove();
            }, 300);
        }
    }, 5000);
}

function updateStatusInUI(newStatus) {
    const statusText = { 'pending': 'Menunggu', 'diterima': 'Diterima', 'ditolak': 'Tidak Diterima' }[newStatus] || newStatus;
    const iconMap = { 'pending': 'clock', 'diterima': 'check-circle', 'ditolak': 'times-circle' };
    const icon = iconMap[newStatus] || 'circle';
    
    const headerBadge = document.getElementById('headerStatusBadge');
    if (headerBadge) {
        headerBadge.className = `status-badge ${newStatus}`;
        headerBadge.innerHTML = `<i class="fas fa-${icon}"></i> ${statusText}`;
    }
    
    const now = new Date();
    const wibTime = new Date(now.getTime() + (7 * 60 * 60 * 1000));
    const formattedTime = `${wibTime.getUTCDate().toString().padStart(2, '0')}/${(wibTime.getUTCMonth()+1).toString().padStart(2, '0')}/${wibTime.getUTCFullYear()} ${wibTime.getUTCHours().toString().padStart(2, '0')}:${wibTime.getUTCMinutes().toString().padStart(2, '0')}:${wibTime.getUTCSeconds().toString().padStart(2, '0')}`;
    
    const lastUpdateEl = document.getElementById('lastUpdateStatus');
    if (lastUpdateEl) lastUpdateEl.textContent = formattedTime + ' WIB';
    
    const lastUpdateInfo = document.getElementById('lastUpdate');
    if (lastUpdateInfo) lastUpdateInfo.textContent = formattedTime.substring(0, 16) + ' WIB';
    
    const mobileLastUpdate = document.getElementById('mobileLastUpdateStatus');
    if (mobileLastUpdate) mobileLastUpdate.textContent = formattedTime + ' WIB';
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.status-opt input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.status-opt').forEach(opt => opt.classList.remove('active'));
            this.closest('.status-opt').classList.add('active');
        });
    });
    
    document.querySelectorAll('.mobile-opt input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.mobile-opt').forEach(opt => opt.classList.remove('active'));
            this.closest('.mobile-opt').classList.add('active');
        });
    });
    
    updateClock();
    setInterval(updateClock, 1000);
    
    const statusForm = document.getElementById('statusForm');
    if (statusForm) {
        statusForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const formData = new FormData(this);
            const newStatus = formData.get('status');
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            
            $.ajax({
                url: '{{ route("admin.individu.status.update", $registration->id) }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'PUT',
                    status: newStatus,
                    catatan_admin: formData.get('catatan_admin')
                },
                success: function(response) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Status';
                    
                    if (response.success) {
                        updateStatusInUI(response.data.status);
                        
                        let notifTitle = 'Status Diperbarui';
                        let notifMessage = '';
                        let notifType = '';
                        
                        if (newStatus === 'diterima') {
                            notifMessage = 'Pendaftaran <strong>{{ $registration->nama_lengkap }}</strong> telah <span style="color: #059669; font-weight: 600;">DITERIMA</span>.';
                            notifType = 'success';
                        } else if (newStatus === 'ditolak') {
                            notifMessage = 'Pendaftaran <strong>{{ $registration->nama_lengkap }}</strong> <span style="color: #dc2626; font-weight: 600;">TIDAK DITERIMA</span>.';
                            notifType = 'error';
                        } else if (newStatus === 'pending') {
                            notifMessage = 'Status diubah menjadi <span style="color: #d97706; font-weight: 600;">PENDING</span>. Menunggu verifikasi selanjutnya.';
                            notifType = 'warning';
                        }
                        
                        showToast(notifTitle, notifMessage, notifType);
                        
                        if (typeof window.updateSidebarBadge === 'function' && response.pending_individu !== undefined) {
                            window.updateSidebarBadge(response.pending_individu, response.pending_kelompok);
                        }
                        
                        closeBottomSheet();
                    } else {
                        showToast('Gagal', response.message || 'Gagal update status', 'error');
                    }
                },
                error: function(xhr) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Status';
                    
                    let errorMessage = 'Terjadi kesalahan pada server';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showToast('Kesalahan Sistem', errorMessage, 'error');
                }
            });
        });
    }
    
    const mobileStatusForm = document.getElementById('mobileStatusForm');
    if (mobileStatusForm) {
        mobileStatusForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('mobileSubmitBtn');
            const formData = new FormData(this);
            const newStatus = formData.get('status');
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            
            $.ajax({
                url: '{{ route("admin.individu.status.update", $registration->id) }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'PUT',
                    status: newStatus,
                    catatan_admin: formData.get('catatan_admin')
                },
                success: function(response) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Status';
                    
                    if (response.success) {
                        document.getElementById('adminNotes').value = formData.get('catatan_admin');
                        updateStatusInUI(response.data.status);
                        
                        let notifTitle = 'Status Diperbarui';
                        let notifMessage = '';
                        let notifType = '';
                        
                        if (newStatus === 'diterima') {
                            notifMessage = 'Pendaftaran <strong>{{ $registration->nama_lengkap }}</strong> telah <span style="color: #059669; font-weight: 600;">DITERIMA</span>.';
                            notifType = 'success';
                        } else if (newStatus === 'ditolak') {
                            notifMessage = 'Pendaftaran <strong>{{ $registration->nama_lengkap }}</strong> <span style="color: #dc2626; font-weight: 600;">TIDAK DITERIMA</span>.';
                            notifType = 'error';
                        } else if (newStatus === 'pending') {
                            notifMessage = 'Status diubah menjadi <span style="color: #d97706; font-weight: 600;">PENDING</span>. Menunggu verifikasi selanjutnya.';
                            notifType = 'warning';
                        }
                        
                        showToast(notifTitle, notifMessage, notifType);
                        
                        if (typeof window.updateSidebarBadge === 'function' && response.pending_individu !== undefined) {
                            window.updateSidebarBadge(response.pending_individu, response.pending_kelompok);
                        }
                        
                        closeBottomSheet();
                    } else {
                        showToast('Gagal', response.message || 'Gagal update status', 'error');
                    }
                },
                error: function(xhr) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Status';
                    
                    let errorMessage = 'Terjadi kesalahan pada server';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showToast('Kesalahan Sistem', errorMessage, 'error');
                }
            });
        });
    }
    
    @if(session('success'))
        showToast('Berhasil', '{{ session("success") }}', 'success');
    @endif
    
    @if(session('error'))
        showToast('Gagal', '{{ session("error") }}', 'error');
    @endif
    
    @if(session('warning'))
        showToast('Perhatian', '{{ session("warning") }}', 'warning');
    @endif
    
    @if(session('info'))
        showToast('Informasi', '{{ session("info") }}', 'info');
    @endif
});

function confirmDelete() { 
    document.getElementById('deleteModal').classList.add('show'); 
    document.body.style.overflow = 'hidden'; 
}

function closeModal(id) { 
    document.getElementById(id).classList.remove('show'); 
    document.body.style.overflow = ''; 
}

function openBottomSheet() { 
    document.getElementById('mobileBottomSheet').classList.add('show'); 
    document.body.style.overflow = 'hidden'; 
}

function closeBottomSheet() { 
    document.getElementById('mobileBottomSheet').classList.remove('show'); 
    document.body.style.overflow = ''; 
}

function executeDelete() {
    closeModal('deleteModal');
    
    const loading = document.createElement('div');
    loading.style.cssText = 'position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.95); display: flex; justify-content: center; align-items: center; z-index: 9999;';
    loading.innerHTML = '<div style="width: 40px; height: 40px; border: 4px solid #e9ecef; border-top: 4px solid #dc3545; border-radius: 50%; animation: spin 0.8s linear infinite;"></div>';
    document.body.appendChild(loading);
    
    $.ajax({
        url: '{{ route("admin.individu.destroy", $registration->id) }}',
        method: 'POST',
        data: { 
            _token: '{{ csrf_token() }}', 
            _method: 'DELETE' 
        },
        success: function(response) {
            loading.remove();
            if (response.success) {
                showToast('Data Terhapus', 'Pendaftaran <strong>{{ $registration->nama_lengkap }}</strong> berhasil dihapus dari sistem.', 'error');
                if (typeof window.updateSidebarBadge === 'function' && response.pending_individu !== undefined) {
                    window.updateSidebarBadge(response.pending_individu, response.pending_kelompok);
                }
                setTimeout(() => { 
                    window.location.href = '{{ route("admin.individu.index") }}'; 
                }, 1500);
            } else {
                showToast('Gagal', response.message || 'Gagal menghapus data', 'error');
            }
        },
        error: function(xhr) {
            loading.remove();
            let errorMessage = 'Terjadi kesalahan pada server';
            if (xhr.responseJSON && xhr.responseJSON.message) errorMessage = xhr.responseJSON.message;
            showToast('Kesalahan Sistem', errorMessage, 'error');
        }
    });
}

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('sheet-overlay')) closeBottomSheet();
    if (e.target.classList.contains('modal-overlay') && e.target.closest('#deleteModal')) closeModal('deleteModal');
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') { 
        closeBottomSheet(); 
        closeModal('deleteModal'); 
    }
});
</script>
@endpush
@endsection