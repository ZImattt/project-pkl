<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Kelompok;
use App\Models\Anggota;
use Carbon\Carbon;

class DashboardController extends Controller
{
    private function checkAdminAuth()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        return null;
    }
    
    public function index()
    {
        if ($redirect = $this->checkAdminAuth()) {
            return $redirect;
        }
        
        $totalIndividu = Pendaftaran::count();
        $pendingIndividu = Pendaftaran::where('status', 'pending')->count();
        $diterimaIndividu = Pendaftaran::where('status', 'diterima')->count();
        $ditolakIndividu = Pendaftaran::where('status', 'ditolak')->count();
        
        $totalKelompok = Kelompok::count();
        $pendingKelompok = Kelompok::where('status', 'pending')->count();
        $diterimaKelompok = Kelompok::where('status', 'diterima')->count();
        $ditolakKelompok = Kelompok::where('status', 'ditolak')->count();
        
        $totalAnggota = Anggota::count();
        
        $totalPendaftar = $totalIndividu + $totalAnggota;
        $totalPending = $pendingIndividu + $pendingKelompok;
        
        $recentIndividu = Pendaftaran::latest()->take(5)->get();
        $recentKelompok = Kelompok::with('anggota')->latest()->take(5)->get();
        
        $recentRegistrations = Pendaftaran::latest()->take(10)->get();
        
        $totalRegistrations = $totalIndividu + $totalKelompok;
        
        $approvedCount = $diterimaIndividu + $diterimaKelompok;
        $rejectedCount = $ditolakIndividu + $ditolakKelompok;
        
        $todayCount = Pendaftaran::whereDate('created_at', Carbon::today())->count() + 
                      Kelompok::whereDate('created_at', Carbon::today())->count();
        
        $smkCount = Pendaftaran::where('jenis_pendidikan', 'smk')->count();
        $univCount = Pendaftaran::where('jenis_pendidikan', 'kuliah')->count();
        
        $thisMonthCount = Pendaftaran::whereMonth('created_at', Carbon::now()->month)
                            ->whereYear('created_at', Carbon::now()->year)
                            ->count() +
                          Kelompok::whereMonth('created_at', Carbon::now()->month)
                            ->whereYear('created_at', Carbon::now()->year)
                            ->count();
        
        return view('admin.dashboard', compact(
            'totalIndividu',
            'pendingIndividu',
            'diterimaIndividu',
            'ditolakIndividu',
            'totalKelompok',
            'pendingKelompok',
            'diterimaKelompok',
            'ditolakKelompok',
            'totalAnggota',
            'totalPendaftar',
            'totalPending',
            'recentIndividu',
            'recentKelompok',
            'recentRegistrations',
            'totalRegistrations',
            'approvedCount',
            'rejectedCount',
            'todayCount',
            'smkCount',
            'univCount',
            'thisMonthCount'
        ));
    }
}