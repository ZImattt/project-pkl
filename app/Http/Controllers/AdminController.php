<?php

namespace App\Http\Controllers;

use App\Models\PklRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Check if admin is logged in
     */
    private function checkAdminAuth()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        return null;
    }
    
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        // Jika sudah login, redirect ke dashboard
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.login');
    }
    
    /**
     * Handle login submission
     */
    public function login(Request $request)
    {
        // Jika sudah login, redirect ke dashboard
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        
        if ($request->username === 'admin' && $request->password === 'admin123') {
            session(['admin_logged_in' => true]);
            return redirect()->route('admin.dashboard')->with('success', 'Login berhasil!');
        }
        
        return back()->withErrors(['error' => 'Username atau password salah.']);
    }
    
    /**
     * Logout admin
     */
    public function logout()
    {
        session()->forget('admin_logged_in');
        return redirect()->route('admin.login')->with('success', 'Logout berhasil!');
    }
    
    /**
     * Display admin dashboard (hanya statistik)
     */
    public function dashboard()
    {
        // Cek apakah admin sudah login
        if ($redirect = $this->checkAdminAuth()) {
            return $redirect;
        }
        
        // Statistics
        $totalRegistrations = PklRegistration::count();
        $totalPending = PklRegistration::where('status', 'pending')->count(); // Untuk sidebar
        $approvedCount = PklRegistration::where('status', 'approved')->count();
        $rejectedCount = PklRegistration::where('status', 'rejected')->count();
        
        // Today's registrations
        $todayCount = PklRegistration::whereDate('created_at', today())->count();
        
        // Education statistics
        $smkCount = PklRegistration::where('jenis_pendidikan', 'smk')->count();
        $univCount = PklRegistration::where('jenis_pendidikan', 'universitas')->count();
        
        // This month's registrations
        $thisMonthCount = PklRegistration::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();
        
        // Recent activities (5 terbaru)
        $recentRegistrations = PklRegistration::latest()->take(5)->get();

        // Peserta PKL aktif
        $today = now();
        $totalActiveParticipants = PklRegistration::where('status', 'approved')
            ->where('tanggal_mulai', '<=', $today)
            ->where('tanggal_selesai', '>=', $today)
            ->count();

        return view('admin.dashboard', compact(
            'totalRegistrations',
            'totalPending', // Kirim ke sidebar
            'approvedCount',
            'rejectedCount',
            'todayCount',
            'smkCount',
            'univCount',
            'thisMonthCount',
            'recentRegistrations',
            'totalActiveParticipants'
        ));
    }
    
    /**
     * Display a listing of registrations (data pendaftaran lengkap dengan filter)
     */
    public function registrations(Request $request)
    {
        // Cek apakah admin sudah login
        if ($redirect = $this->checkAdminAuth()) {
            return $redirect;
        }
        
        $query = PklRegistration::query();
        
        // Apply filters
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('no_whatsapp', 'like', "%$search%")
                  ->orWhere('registration_id', 'like', "%$search%");
            });
        }
        
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('jenis_pendidikan') && $request->jenis_pendidikan != 'all') {
            $query->where('jenis_pendidikan', $request->jenis_pendidikan);
        }
        
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        // Paginate results
        $registrations = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // TOTAL PENDING UNTUK BADGE SIDEBAR
        $totalPending = PklRegistration::where('status', 'pending')->count();

        // Gunakan view yang ada
        if (view()->exists('admin.data-pendaftaran')) {
            return view('admin.data-pendaftaran', compact('registrations', 'totalPending'));
        } elseif (view()->exists('admin.registrations.index')) {
            return view('admin.registrations.index', compact('registrations', 'totalPending'));
        } else {
            return view('admin.dashboard')->with('error', 'View data pendaftaran tidak ditemukan');
        }
    }
    
    /**
     * Display the specified registration
     */
    public function showRegistration($id)
    {
        // Cek apakah admin sudah login
        if ($redirect = $this->checkAdminAuth()) {
            return $redirect;
        }
        
        $registration = PklRegistration::findOrFail($id);
        
        // TOTAL PENDING UNTUK BADGE SIDEBAR
        $totalPending = PklRegistration::where('status', 'pending')->count();
        
        // Cek view mana yang ada
        if (view()->exists('admin.registration-show')) {
            return view('admin.registration-show', compact('registration', 'totalPending'));
        } elseif (view()->exists('admin.registrations.show')) {
            return view('admin.registrations.show', compact('registration', 'totalPending'));
        } elseif (view()->exists('admin.detail-pendaftaran')) {
            return view('admin.detail-pendaftaran', compact('registration', 'totalPending'));
        } else {
            // Fallback ke view sederhana
            return view('admin.registration-detail', compact('registration', 'totalPending'));
        }
    }
    
    /**
     * Show method alias (untuk compatibility dengan route lama)
     */
    public function show($id)
    {
        return $this->showRegistration($id);
    }
    
    /**
     * Approve registration
     */
    public function approve($id)
    {
        if ($redirect = $this->checkAdminAuth()) {
            return $redirect;
        }
        
        try {
            $registration = PklRegistration::findOrFail($id);
            
            $registration->update([
                'status' => 'approved',
                'status_updated_at' => now()
            ]);
            
            // TOTAL PENDING TERBARU UNTUK BADGE SIDEBAR
            $totalPending = PklRegistration::where('status', 'pending')->count();
            
            return redirect()->route('admin.registrations.show', $id)
                ->with('success', 'Pendaftaran berhasil disetujui!')
                ->with('totalPending', $totalPending);
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menyetujui pendaftaran: ' . $e->getMessage());
        }
    }
    
    /**
     * Reject registration
     */
    public function reject($id)
    {
        if ($redirect = $this->checkAdminAuth()) {
            return $redirect;
        }
        
        try {
            $registration = PklRegistration::findOrFail($id);
            
            $registration->update([
                'status' => 'rejected',
                'status_updated_at' => now()
            ]);
            
            // TOTAL PENDING TERBARU UNTUK BADGE SIDEBAR
            $totalPending = PklRegistration::where('status', 'pending')->count();
            
            return redirect()->route('admin.registrations.show', $id)
                ->with('success', 'Pendaftaran berhasil ditolak!')
                ->with('totalPending', $totalPending);
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menolak pendaftaran: ' . $e->getMessage());
        }
    }
    
    /**
     * Update the specified registration status
     */
    public function updateStatus(Request $request, $id)
    {
        // Cek apakah admin sudah login
        if ($redirect = $this->checkAdminAuth()) {
            return $redirect;
        }
        
        try {
            $registration = PklRegistration::findOrFail($id);
            
            $request->validate([
                'status' => 'required|in:pending,approved,rejected',
                'admin_notes' => 'nullable|string|max:1000'
            ]);
            
            $registration->update([
                'status' => $request->status,
                'admin_notes' => $request->admin_notes,
                'status_updated_at' => now()
            ]);
            
            // TOTAL PENDING TERBARU UNTUK BADGE SIDEBAR
            $totalPending = PklRegistration::where('status', 'pending')->count();
            
            return redirect()->route('admin.registrations.show', $id)
                ->with('success', 'Status pendaftaran berhasil diperbarui!')
                ->with('totalPending', $totalPending);
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }
    
    /**
     * Update registration (alias for updateStatus)
     */
    public function update(Request $request, $id)
    {
        return $this->updateStatus($request, $id);
    }
    
    /**
     * Remove the specified registration
     */
    public function destroy($id)
    {
        // Cek apakah admin sudah login
        if ($redirect = $this->checkAdminAuth()) {
            return $redirect;
        }
        
        try {
            $registration = PklRegistration::findOrFail($id);
            
            // Hapus file surat pengantar jika ada
            if ($registration->upload_surat_pengantar) {
                $filePath = public_path('uploads/surat_pengantar/' . $registration->upload_surat_pengantar);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            
            $registration->delete();
            
            // TOTAL PENDING TERBARU UNTUK BADGE SIDEBAR
            $totalPending = PklRegistration::where('status', 'pending')->count();
            
            return redirect()->route('admin.registrations.index')
                ->with('success', 'Pendaftaran berhasil dihapus!')
                ->with('totalPending', $totalPending);
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus pendaftaran: ' . $e->getMessage());
        }
    }
    
    /**
     * Download surat pengantar
     */
    public function downloadSurat($id)
    {
        // Cek apakah admin sudah login
        if ($redirect = $this->checkAdminAuth()) {
            return $redirect;
        }
        
        $registration = PklRegistration::findOrFail($id);
        
        if (!$registration->upload_surat_pengantar) {
            return redirect()->back()->with('error', 'File surat pengantar tidak ditemukan');
        }
        
        $filePath = public_path('uploads/surat_pengantar/' . $registration->upload_surat_pengantar);
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan');
        }
        
        return response()->download($filePath, $registration->upload_surat_pengantar);
    }
    
    /**
     * Export data to Excel
     */
    public function export(Request $request)
    {
        // Cek apakah admin sudah login
        if ($redirect = $this->checkAdminAuth()) {
            return $redirect;
        }
        
        $query = PklRegistration::query();
        
        // Apply same filters as registrations page
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('no_whatsapp', 'like', "%$search%")
                  ->orWhere('registration_id', 'like', "%$search%");
            });
        }
        
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('jenis_pendidikan') && $request->jenis_pendidikan != 'all') {
            $query->where('jenis_pendidikan', $request->jenis_pendidikan);
        }
        
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $registrations = $query->orderBy('created_at', 'desc')->get();
        
        // Jika request export_all, export semua data tanpa filter
        if ($request->has('export_all') && $request->export_all == 'true') {
            $registrations = PklRegistration::orderBy('created_at', 'desc')->get();
        }
        
        // Format untuk view export
        $exportData = $registrations->map(function ($item) {
            $jenisPendidikan = $item->jenis_pendidikan == 'smk' ? 'SMK/SMA' : 'Universitas';
            $institusi = $item->jenis_pendidikan == 'smk' ? $item->sekolah : $item->universitas;
            $jurusan = $item->jenis_pendidikan == 'smk' ? $item->jurusan_smk : $item->jurusan_univ;
            $kelasSemester = $item->jenis_pendidikan == 'smk' ? 'Kelas ' . $item->kelas : 'Semester ' . $item->semester;
            $statusText = match($item->status) {
                'pending' => 'Menunggu',
                'approved' => 'Disetujui',
                'rejected' => 'Ditolak',
                default => $item->status
            };
            
            return [
                'ID Pendaftaran' => $item->registration_id,
                'Nama Lengkap' => $item->nama_lengkap,
                'Email' => $item->email,
                'No WhatsApp' => $item->no_whatsapp,
                'Jenis Pendidikan' => $jenisPendidikan,
                'Sekolah/Universitas' => $institusi,
                'Jurusan' => $jurusan,
                'Kelas/Semester' => $kelasSemester,
                'Tanggal Mulai PKL' => $item->tanggal_mulai ? $item->tanggal_mulai->format('d/m/Y') : '-',
                'Tanggal Selesai PKL' => $item->tanggal_selesai ? $item->tanggal_selesai->format('d/m/Y') : '-',
                'Status' => $statusText,
                'Tanggal Daftar' => $item->created_at->format('d/m/Y H:i'),
                'Catatan Admin' => $item->admin_notes ?? '-'
            ];
        });
        
        // TOTAL PENDING UNTUK BADGE SIDEBAR
        $totalPending = PklRegistration::where('status', 'pending')->count();
        
        // Cek view mana yang ada
        if (view()->exists('admin.export')) {
            return view('admin.export', compact('exportData', 'totalPending'));
        } elseif (view()->exists('admin.export-simple')) {
            return view('admin.export-simple', compact('exportData', 'totalPending'));
        } else {
            // Fallback ke CSV download
            return $this->exportCsv($request);
        }
    }
    
    /**
     * Simple export view (CSV download)
     */
    public function exportCsv(Request $request)
    {
        // Cek apakah admin sudah login
        if ($redirect = $this->checkAdminAuth()) {
            return $redirect;
        }
        
        $query = PklRegistration::query();
        
        // Apply filters
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('no_whatsapp', 'like', "%$search%")
                  ->orWhere('registration_id', 'like', "%$search%");
            });
        }
        
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('jenis_pendidikan') && $request->jenis_pendidikan != 'all') {
            $query->where('jenis_pendidikan', $request->jenis_pendidikan);
        }
        
        $registrations = $query->orderBy('created_at', 'desc')->get();
        
        $fileName = 'data-pendaftaran-pkl-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];
        
        $callback = function() use ($registrations) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Header CSV
            fputcsv($file, [
                'ID Pendaftaran',
                'Nama Lengkap',
                'Email',
                'No WhatsApp',
                'Jenis Pendidikan',
                'Sekolah/Universitas',
                'Jurusan',
                'Kelas/Semester',
                'Tanggal Mulai PKL',
                'Tanggal Selesai PKL',
                'Status',
                'Tanggal Daftar',
                'Catatan Admin'
            ]);
            
            // Data
            foreach ($registrations as $row) {
                $jenisPendidikan = $row->jenis_pendidikan == 'smk' ? 'SMK/SMA' : 'Universitas';
                $institusi = $row->jenis_pendidikan == 'smk' ? $row->sekolah : $row->universitas;
                $jurusan = $row->jenis_pendidikan == 'smk' ? $row->jurusan_smk : $row->jurusan_univ;
                $kelasSemester = $row->jenis_pendidikan == 'smk' ? 'Kelas ' . $row->kelas : 'Semester ' . $row->semester;
                $statusText = match($row->status) {
                    'pending' => 'Menunggu',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    default => $row->status
                };
                
                fputcsv($file, [
                    $row->registration_id,
                    $row->nama_lengkap,
                    $row->email,
                    $row->no_whatsapp,
                    $jenisPendidikan,
                    $institusi,
                    $jurusan,
                    $kelasSemester,
                    $row->tanggal_mulai ? $row->tanggal_mulai->format('d/m/Y') : '',
                    $row->tanggal_selesai ? $row->tanggal_selesai->format('d/m/Y') : '',
                    $statusText,
                    $row->created_at->format('d/m/Y H:i'),
                    $row->admin_notes ?? ''
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Display list of PKL participants
     */
    public function pesertaPkl(Request $request)
    {
        // Cek apakah admin sudah login
        if ($redirect = $this->checkAdminAuth()) {
            return $redirect;
        }
        
        // Hanya ambil pendaftaran yang sudah disetujui (approved)
        $query = PklRegistration::where('status', 'approved');
        
        // Filter berdasarkan status
        $today = now();
        
        if ($request->has('status')) {
            switch ($request->status) {
                case 'active':
                    $query->where('tanggal_mulai', '<=', $today)
                          ->where('tanggal_selesai', '>=', $today);
                    break;
                case 'upcoming':
                    $query->where('tanggal_mulai', '>', $today);
                    break;
                case 'completed':
                    $query->where('tanggal_selesai', '<', $today);
                    break;
                case 'past':
                    $query->where('tanggal_mulai', '<', $today);
                    break;
            }
        }
        
        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('registration_id', 'like', "%$search%")
                  ->orWhere('sekolah', 'like', "%$search%")
                  ->orWhere('universitas', 'like', "%$search%");
            });
        }
        
        // Order by tanggal mulai
        $participants = $query->orderBy('tanggal_mulai', 'asc')->paginate(15);
        
        // Hitung statistik
        $totalActive = PklRegistration::where('status', 'approved')
            ->where('tanggal_mulai', '<=', $today)
            ->where('tanggal_selesai', '>=', $today)
            ->count();
        
        $willStart = PklRegistration::where('status', 'approved')
            ->where('tanggal_mulai', '>', $today)
            ->where('tanggal_mulai', '<=', $today->copy()->addDays(7))
            ->count();
        
        $totalCompleted = PklRegistration::where('status', 'approved')
            ->where('tanggal_selesai', '<', $today)
            ->count();
        
        $totalParticipants = PklRegistration::where('status', 'approved')->count();
        
        // TOTAL PENDING UNTUK BADGE SIDEBAR
        $totalPending = PklRegistration::where('status', 'pending')->count();
        
        return view('admin.peserta-pkl', compact(
            'participants',
            'totalActive',
            'willStart',
            'totalCompleted',
            'totalParticipants',
            'totalPending'
        ));
    }
    
    /**
     * Get statistics for dashboard widgets (API endpoint)
     */
    public function getStatistics()
    {
        // Cek apakah admin sudah login
        if (!session('admin_logged_in')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $today = now();
        
        $statistics = [
            'total' => PklRegistration::count(),
            'pending' => PklRegistration::where('status', 'pending')->count(),
            'approved' => PklRegistration::where('status', 'approved')->count(),
            'rejected' => PklRegistration::where('status', 'rejected')->count(),
            'today' => PklRegistration::whereDate('created_at', today())->count(),
            'smk' => PklRegistration::where('jenis_pendidikan', 'smk')->count(),
            'universitas' => PklRegistration::where('jenis_pendidikan', 'universitas')->count(),
            'this_month' => PklRegistration::whereMonth('created_at', date('m'))
                ->whereYear('created_at', date('Y'))
                ->count(),
            'active_participants' => PklRegistration::where('status', 'approved')
                ->where('tanggal_mulai', '<=', $today)
                ->where('tanggal_selesai', '>=', $today)
                ->count(),
        ];
        
        return response()->json($statistics);
    }
    
    /**
     * For backward compatibility with old routes
     */
    public function index(Request $request)
    {
        // Redirect ke method registrations
        return $this->registrations($request);
    }
}