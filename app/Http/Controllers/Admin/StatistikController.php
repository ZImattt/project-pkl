<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Kelompok;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StatistikController extends Controller
{
    private function checkAdminAuth()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        return null;
    }
    
    public function index(Request $request)
    {
        if ($redirect = $this->checkAdminAuth()) {
            return $redirect;
        }
        
        try {
            // Get date range from request
            $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
            $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
            
            $startDateTime = Carbon::parse($startDate)->startOfDay();
            $endDateTime = Carbon::parse($endDate)->endOfDay();
            
            // Total counts
            $totalIndividu = Pendaftaran::count();
            $totalKelompok = Kelompok::count();
            $totalAnggota = Anggota::count();
            $totalPendaftar = $totalIndividu + $totalKelompok;
            
            // Status individu
            $statusIndividu = [
                'pending' => Pendaftaran::where('status', 'pending')->count(),
                'diterima' => Pendaftaran::where('status', 'diterima')->count(),
                'ditolak' => Pendaftaran::where('status', 'ditolak')->count(),
            ];
            
            // Status kelompok
            $statusKelompok = [
                'pending' => Kelompok::where('status', 'pending')->count(),
                'diterima' => Kelompok::where('status', 'diterima')->count(),
                'ditolak' => Kelompok::where('status', 'ditolak')->count(),
            ];
            
            $totalPending = $statusIndividu['pending'] + $statusKelompok['pending'];
            $totalDiterima = $statusIndividu['diterima'] + $statusKelompok['diterima'];
            $totalDitolak = $statusIndividu['ditolak'] + $statusKelompok['ditolak'];
            $totalSemua = $totalIndividu + $totalKelompok;
            
            // Tingkat keberhasilan
            $tingkatKeberhasilan = $totalSemua > 0 ? round(($totalDiterima / $totalSemua) * 100) : 0;
            
            // ========== TREND CHART DATA ==========
            // Ambil data individu
            $individuData = Pendaftaran::select(
                    DB::raw('DATE(created_at) as tanggal'), 
                    DB::raw('COUNT(*) as total')
                )
                ->whereNotNull('created_at')
                ->whereBetween('created_at', [$startDateTime, $endDateTime])
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('tanggal', 'asc')
                ->get()
                ->keyBy('tanggal');
            
            // Ambil data kelompok
            $kelompokData = Kelompok::select(
                    DB::raw('DATE(created_at) as tanggal'), 
                    DB::raw('COUNT(*) as total')
                )
                ->whereNotNull('created_at')
                ->whereBetween('created_at', [$startDateTime, $endDateTime])
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('tanggal', 'asc')
                ->get()
                ->keyBy('tanggal');
            
            // Gabungkan semua tanggal yang ada
            $allDates = [];
            foreach ($individuData as $date => $data) {
                $allDates[] = $date;
            }
            foreach ($kelompokData as $date => $data) {
                if (!in_array($date, $allDates)) {
                    $allDates[] = $date;
                }
            }
            
            // Urutkan tanggal
            sort($allDates);
            
            // Batasi maksimal 30 data terbaru
            $maxData = 30;
            if (count($allDates) > $maxData) {
                $allDates = array_slice($allDates, -$maxData);
            }
            
            // Buat data trend
            $trendLabels = [];
            $trendDataIndividu = [];
            $trendDataKelompok = [];
            
            foreach ($allDates as $date) {
                $trendLabels[] = Carbon::parse($date)->format('d M Y');
                $trendDataIndividu[] = isset($individuData[$date]) ? $individuData[$date]->total : 0;
                $trendDataKelompok[] = isset($kelompokData[$date]) ? $kelompokData[$date]->total : 0;
            }
            
            // Data berdasarkan institusi
            $institusiData = [
                'smk' => Pendaftaran::where('jenis_pendidikan', 'smk')->count(),
                'kuliah' => Pendaftaran::where('jenis_pendidikan', 'kuliah')->count(),
            ];
            
            // ========== DATA TERBARU (DIGABUNGKAN) ==========
            $recentIndividu = Pendaftaran::orderBy('created_at', 'desc')->limit(5)->get();
            $recentKelompok = Kelompok::orderBy('created_at', 'desc')->limit(5)->get();
            
            // Gabungkan data individu dan kelompok menjadi satu collection
            $recentRegistrations = collect();
            
            // Tambahkan data individu
            foreach ($recentIndividu as $item) {
                $recentRegistrations->push((object)[
                    'id' => $item->id,
                    'nama_lengkap' => $item->nama_lengkap,
                    'nama_kelompok' => null,
                    'tipe' => 'individu',
                    'status' => $item->status,
                    'created_at' => $item->created_at,
                    'jenis_pendidikan' => $item->jenis_pendidikan,
                    'sekolah' => $item->sekolah,
                    'kuliah' => $item->kuliah,
                ]);
            }
            
            // Tambahkan data kelompok
            foreach ($recentKelompok as $item) {
                $recentRegistrations->push((object)[
                    'id' => $item->id,
                    'nama_lengkap' => null,
                    'nama_kelompok' => $item->nama_kelompok,
                    'tipe' => 'kelompok',
                    'status' => $item->status,
                    'created_at' => $item->created_at,
                    'jenis_pendidikan' => $item->perwakilan_jenis_pendidikan ?? 'smk',
                    'sekolah' => $item->institusi,
                    'kuliah' => $item->institusi,
                ]);
            }
            
            // Urutkan berdasarkan created_at descending
            $recentRegistrations = $recentRegistrations->sortByDesc('created_at')->take(10);
            
            // Cek data yang created_at-nya NULL
            $individuNullCount = Pendaftaran::whereNull('created_at')->count();
            $kelompokNullCount = Kelompok::whereNull('created_at')->count();
            
            // Log untuk debug
            Log::info('Statistik Index - Total Pendaftar: ' . ($totalIndividu + $totalKelompok));
            Log::info('Statistik Index - Jumlah Data Trend: ' . count($trendLabels));
            
            return view('admin.statistik.index', compact(
                'totalIndividu',
                'totalKelompok',
                'totalAnggota',
                'totalPendaftar',
                'statusIndividu',
                'statusKelompok',
                'totalPending',
                'totalDiterima',
                'totalDitolak',
                'tingkatKeberhasilan',
                'trendLabels',
                'trendDataIndividu',
                'trendDataKelompok',
                'institusiData',
                'recentIndividu',
                'recentKelompok',
                'recentRegistrations',
                'individuNullCount',
                'kelompokNullCount',
                'startDate',
                'endDate'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error statistics: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat statistik: ' . $e->getMessage());
        }
    }
    
    public function getStatistics(Request $request)
    {
        if (!session('admin_logged_in')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        try {
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            
            if (!$startDate || !$endDate) {
                $startDate = Carbon::now()->subDays(30)->format('Y-m-d');
                $endDate = Carbon::now()->format('Y-m-d');
            }
            
            $startDateTime = Carbon::parse($startDate)->startOfDay();
            $endDateTime = Carbon::parse($endDate)->endOfDay();
            
            // Total data dalam range
            $totalIndividu = Pendaftaran::whereBetween('created_at', [$startDateTime, $endDateTime])->count();
            $totalKelompok = Kelompok::whereBetween('created_at', [$startDateTime, $endDateTime])->count();
            
            // Total anggota dari kelompok dalam range
            $kelompokIds = Kelompok::whereBetween('created_at', [$startDateTime, $endDateTime])->pluck('id');
            $totalAnggota = Anggota::whereIn('kelompok_id', $kelompokIds)->count();
            
            $totalPendaftar = $totalIndividu + $totalKelompok;
            
            // Status dalam range
            $pendingIndividu = Pendaftaran::whereBetween('created_at', [$startDateTime, $endDateTime])
                ->where('status', 'pending')->count();
            $pendingKelompok = Kelompok::whereBetween('created_at', [$startDateTime, $endDateTime])
                ->where('status', 'pending')->count();
            
            $diterimaIndividu = Pendaftaran::whereBetween('created_at', [$startDateTime, $endDateTime])
                ->where('status', 'diterima')->count();
            $diterimaKelompok = Kelompok::whereBetween('created_at', [$startDateTime, $endDateTime])
                ->where('status', 'diterima')->count();
            
            $ditolakIndividu = Pendaftaran::whereBetween('created_at', [$startDateTime, $endDateTime])
                ->where('status', 'ditolak')->count();
            $ditolakKelompok = Kelompok::whereBetween('created_at', [$startDateTime, $endDateTime])
                ->where('status', 'ditolak')->count();
            
            $totalDiterima = $diterimaIndividu + $diterimaKelompok;
            $tingkatKeberhasilan = $totalPendaftar > 0 ? round(($totalDiterima / $totalPendaftar) * 100) : 0;
            
            // ========== TREND CHART UNTUK FILTER ==========
            // Ambil data individu dalam range
            $individuTrend = Pendaftaran::select(
                    DB::raw('DATE(created_at) as tanggal'), 
                    DB::raw('COUNT(*) as total')
                )
                ->whereBetween('created_at', [$startDateTime, $endDateTime])
                ->whereNotNull('created_at')
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('tanggal', 'asc')
                ->get()
                ->keyBy('tanggal');
            
            // Ambil data kelompok dalam range
            $kelompokTrend = Kelompok::select(
                    DB::raw('DATE(created_at) as tanggal'), 
                    DB::raw('COUNT(*) as total')
                )
                ->whereBetween('created_at', [$startDateTime, $endDateTime])
                ->whereNotNull('created_at')
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('tanggal', 'asc')
                ->get()
                ->keyBy('tanggal');
            
            // Gabungkan semua tanggal yang ada dalam range
            $allDates = [];
            foreach ($individuTrend as $date => $data) {
                $allDates[] = $date;
            }
            foreach ($kelompokTrend as $date => $data) {
                if (!in_array($date, $allDates)) {
                    $allDates[] = $date;
                }
            }
            
            // Urutkan tanggal
            sort($allDates);
            
            // Batasi maksimal 30 data
            if (count($allDates) > 30) {
                $allDates = array_slice($allDates, -30);
            }
            
            // Buat data trend
            $trendLabels = [];
            $trendDataIndividu = [];
            $trendDataKelompok = [];
            
            foreach ($allDates as $date) {
                $trendLabels[] = Carbon::parse($date)->format('d M Y');
                $trendDataIndividu[] = isset($individuTrend[$date]) ? $individuTrend[$date]->total : 0;
                $trendDataKelompok[] = isset($kelompokTrend[$date]) ? $kelompokTrend[$date]->total : 0;
            }
            
            // Data SMK dan Kuliah dalam range
            $smkCount = Pendaftaran::whereBetween('created_at', [$startDateTime, $endDateTime])
                ->where('jenis_pendidikan', 'smk')->count();
            $kuliahCount = Pendaftaran::whereBetween('created_at', [$startDateTime, $endDateTime])
                ->where('jenis_pendidikan', 'kuliah')->count();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'totalIndividu' => $totalIndividu,
                    'totalKelompok' => $totalKelompok,
                    'totalAnggota' => $totalAnggota,
                    'totalPendaftar' => $totalPendaftar,
                    'pending' => $pendingIndividu + $pendingKelompok,
                    'diterima' => $totalDiterima,
                    'ditolak' => $ditolakIndividu + $ditolakKelompok,
                    'tingkatKeberhasilan' => $tingkatKeberhasilan,
                    'individu' => [
                        'pending' => $pendingIndividu,
                        'diterima' => $diterimaIndividu,
                        'ditolak' => $ditolakIndividu,
                    ],
                    'kelompok' => [
                        'pending' => $pendingKelompok,
                        'diterima' => $diterimaKelompok,
                        'ditolak' => $ditolakKelompok,
                    ],
                    'smk' => $smkCount,
                    'kuliah' => $kuliahCount,
                    'trendLabels' => $trendLabels,
                    'trendDataIndividu' => $trendDataIndividu,
                    'trendDataKelompok' => $trendDataKelompok,
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error get statistics: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Method untuk memperbaiki data yang created_at-nya NULL
    public function fixCreatedAt()
    {
        if ($redirect = $this->checkAdminAuth()) {
            return $redirect;
        }
        
        try {
            DB::beginTransaction();
            
            $updatedIndividu = 0;
            $updatedKelompok = 0;
            
            // Perbaiki data individu
            $individuNull = Pendaftaran::whereNull('created_at')->get();
            foreach ($individuNull as $item) {
                $date = Carbon::now();
                
                // Coba ambil tanggal dari kode_pendaftaran
                if ($item->kode_pendaftaran && preg_match('/(\d{8})/', $item->kode_pendaftaran, $matches)) {
                    try {
                        $date = Carbon::createFromFormat('Ymd', $matches[1]);
                    } catch (\Exception $e) {
                        $date = Carbon::now();
                    }
                }
                
                $item->created_at = $date;
                $item->updated_at = $date;
                $item->save();
                $updatedIndividu++;
            }
            
            // Perbaiki data kelompok
            $kelompokNull = Kelompok::whereNull('created_at')->get();
            foreach ($kelompokNull as $item) {
                $date = Carbon::now();
                
                if ($item->kode_pendaftaran && preg_match('/(\d{8})/', $item->kode_pendaftaran, $matches)) {
                    try {
                        $date = Carbon::createFromFormat('Ymd', $matches[1]);
                    } catch (\Exception $e) {
                        $date = Carbon::now();
                    }
                }
                
                $item->created_at = $date;
                $item->updated_at = $date;
                $item->save();
                $updatedKelompok++;
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "Berhasil memperbaiki {$updatedIndividu} data individu dan {$updatedKelompok} data kelompok",
                'updated' => [
                    'individu' => $updatedIndividu,
                    'kelompok' => $updatedKelompok
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error fix created_at: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    // Method untuk mengecek data yang NULL
    public function checkNullData()
    {
        if ($redirect = $this->checkAdminAuth()) {
            return $redirect;
        }
        
        $individuNull = Pendaftaran::whereNull('created_at')->count();
        $kelompokNull = Kelompok::whereNull('created_at')->count();
        
        // Ambil contoh data yang NULL
        $individuSamples = Pendaftaran::whereNull('created_at')->limit(5)->get();
        $kelompokSamples = Kelompok::whereNull('created_at')->limit(5)->get();
        
        return response()->json([
            'individu_with_null_created_at' => $individuNull,
            'kelompok_with_null_created_at' => $kelompokNull,
            'total_individu' => Pendaftaran::count(),
            'total_kelompok' => Kelompok::count(),
            'individu_samples' => $individuSamples->map(function($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->nama_lengkap,
                    'kode' => $item->kode_pendaftaran
                ];
            }),
            'kelompok_samples' => $kelompokSamples->map(function($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->nama_kelompok,
                    'kode' => $item->kode_pendaftaran
                ];
            })
        ]);
    }
    
    public function exportExcel()
    {
        if ($redirect = $this->checkAdminAuth()) {
            return $redirect;
        }
        
        try {
            $individu = Pendaftaran::orderBy('created_at', 'desc')->get();
            $kelompok = Kelompok::with('anggota')->orderBy('created_at', 'desc')->get();
            
            $filename = 'statistik_pendaftaran_' . date('Y-m-d_H-i-s') . '.csv';
            $handle = fopen('php://temp', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            
            fputcsv($handle, [
                'JENIS DATA', 'KODE', 'NAMA', 'EMAIL', 'NO WHATSAPP', 'INSTITUSI', 'STATUS', 'TANGGAL DAFTAR'
            ]);
            
            foreach ($individu as $item) {
                fputcsv($handle, [
                    'Individu',
                    $item->kode_pendaftaran ?? '-',
                    $item->nama_lengkap,
                    $item->email,
                    $item->no_whatsapp,
                    $item->jenis_pendidikan == 'smk' ? $item->sekolah : $item->kuliah,
                    $this->getStatusText($item->status),
                    $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-'
                ]);
            }
            
            foreach ($kelompok as $item) {
                fputcsv($handle, [
                    'Kelompok (Ketua)',
                    $item->kode_pendaftaran ?? '-',
                    $item->nama_kelompok . ' (' . $item->nama_ketua . ')',
                    $item->email_ketua,
                    $item->no_whatsapp_ketua,
                    $item->institusi,
                    $this->getStatusText($item->status),
                    $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-'
                ]);
            }
            
            rewind($handle);
            $csv = stream_get_contents($handle);
            fclose($handle);
            
            return response($csv, 200)
                ->header('Content-Type', 'application/csv; charset=UTF-8')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
                
        } catch (\Exception $e) {
            Log::error('Error export excel: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export data: ' . $e->getMessage());
        }
    }
    
    private function getStatusText($status)
    {
        return match($status) {
            'pending' => 'Pending (Menunggu)',
            'diterima' => 'Diterima',
            'ditolak' => 'Ditolak',
            default => $status
        };
    }
    
    public function exportCsv()
    {
        return $this->exportExcel();
    }
    
    public function export()
    {
        return $this->exportExcel();
    }
}