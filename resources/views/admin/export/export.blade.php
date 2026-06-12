@extends('layouts.admin')

@section('title', 'Export Data - Admin Global Intermedia')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold" style="color: #dc3545;">
            <i class="fas fa-download me-2"></i>Export Data
        </h4>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Export Data Pendaftaran</h5>
                </div>
                <div class="card-body">
                    <p>Export data pendaftaran individu dan kelompok ke format Excel/CSV.</p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('admin.export.excel', request()->all()) }}" class="btn btn-success">
                            <i class="fas fa-file-excel me-2"></i>Export Excel
                        </a>
                        <a href="{{ route('admin.export.csv', request()->all()) }}" class="btn btn-info">
                            <i class="fas fa-file-csv me-2"></i>Export CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Export Data Kelompok</h5>
                </div>
                <div class="card-body">
                    <p>Export data kelompok beserta anggota ke format Excel.</p>
                    <a href="{{ route('admin.kelompok.export', request()->all()) }}" class="btn btn-success">
                        <i class="fas fa-file-excel me-2"></i>Export Kelompok
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Filter Data (Opsional)</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.export') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="all">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Disetujui</option>
                        <option value="rejected">Ditolak</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis Pendidikan</label>
                    <select name="jenis_pendidikan" class="form-select">
                        <option value="all">Semua</option>
                        <option value="smk">SMK/SMA</option>
                        <option value="kuliah">Kuliah</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-2"></i>Apply Filter
                    </button>
                    <a href="{{ route('admin.export') }}" class="btn btn-secondary">
                        <i class="fas fa-undo me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection