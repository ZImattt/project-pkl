<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Kelompok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\WhatsAppService;

class IndividuController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    private function checkAdminAuth()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        return null;
    }

    public function getPendingCounts()
    {
        try {
            $pendingIndividu = Pendaftaran::where('status', 'pending')->count();
            $pendingKelompok = Kelompok::where('status', 'pending')->count();

            return response()->json([
                'success' => true,
                'pending_individu' => $pendingIndividu,
                'pending_kelompok' => $pendingKelompok,
                'total_pending' => $pendingIndividu + $pendingKelompok,
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting pending counts: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal'], 500);
        }
    }

    public function index(Request $request)
    {
        if ($redirect = $this->checkAdminAuth()) return $redirect;

        $query = Pendaftaran::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('no_whatsapp', 'like', "%{$search}%")
                    ->orWhere('kode_pendaftaran', 'like', "%{$search}%")
                    ->orWhere('sekolah', 'like', "%{$search}%")
                    ->orWhere('kuliah', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis_pendidikan') && $request->jenis_pendidikan != 'all') {
            $query->where('jenis_pendidikan', $request->jenis_pendidikan);
        }

        $registrations = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $totalPending = Pendaftaran::where('status', 'pending')->count();

        return view('admin.individu.index', compact('registrations', 'totalPending'));
    }

    public function show($id)
    {
        if ($redirect = $this->checkAdminAuth()) return $redirect;

        $registration = Pendaftaran::findOrFail($id);
        $totalPending = Pendaftaran::where('status', 'pending')->count();

        return view('admin.individu.show', compact('registration', 'totalPending'));
    }

    public function updateStatus(Request $request, $id)
    {
        if ($redirect = $this->checkAdminAuth()) return $redirect;

        try {
            DB::beginTransaction();

            $registration = Pendaftaran::findOrFail($id);

            $request->validate([
                'status' => 'required|in:pending,diterima,ditolak',
                'catatan_admin' => 'nullable|string|max:1000'
            ]);

            $oldStatus = $registration->status;
            $newStatus = $request->status;
            
            $registration->update([
                'status' => $newStatus,
                'catatan_admin' => $request->catatan_admin
            ]);

            DB::commit();

            // Kirim WA otomatis jika status berubah dan bukan pending
            if ($newStatus !== 'pending' && $newStatus !== $oldStatus) {
                try {
                    $this->kirimWaStatusUpdate($registration, $newStatus, $request->catatan_admin);
                } catch (\Exception $waError) {
                    Log::error('Gagal kirim WA update status individu: ' . $waError->getMessage());
                }
            }

            if ($request->ajax() || $request->wantsJson()) {
                $pendingIndividu = Pendaftaran::where('status', 'pending')->count();
                $pendingKelompok = Kelompok::where('status', 'pending')->count();

                return response()->json([
                    'success' => true,
                    'message' => 'Status berhasil diupdate!',
                    'pending_individu' => $pendingIndividu,
                    'pending_kelompok' => $pendingKelompok,
                    'data' => [
                        'id' => $registration->id,
                        'status' => $newStatus,
                        'status_text' => $this->getStatusLabel($newStatus),
                        'catatan' => $registration->catatan_admin
                    ]
                ]);
            }

            return redirect()->route('admin.individu.show', $id)
                ->with('success', 'Status berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error update status individu: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal update status: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal update status: ' . $e->getMessage());
        }
    }

    private function kirimWaStatusUpdate($registration, $status, $catatan)
    {
        $nomorWA = $registration->no_whatsapp;
        $kode = $registration->kode_pendaftaran;
        $nama = $registration->nama_lengkap;
        $institusi = $registration->jenis_pendidikan == 'smk' ? $registration->sekolah : $registration->kuliah;
        $jenis = $registration->jenis_pendidikan == 'smk' ? 'SMK/SMA' : 'Perguruan Tinggi';
        $catatan = $catatan ?? '';

        if ($status === 'diterima') {
            $pesan = "🎉 *SELAMAT! PENDAFTARAN DITERIMA*\n\n"
                . "Halo, *{$nama}*!\n\n"
                . "Pendaftaran PKL/Magang kamu di *Global Intermedia Nusantara* telah *DITERIMA*.\n\n"
                . "📋 *Detail Pendaftaran:*\n"
                . "• Kode: *{$kode}*\n"
                . "• Nama: *{$nama}*\n"
                . "• Institusi: *{$institusi}*\n"
                . "• Jenjang: *{$jenis}*\n\n"
                . "📅 *Periode PKL:*\n"
                . "• Mulai: *" . date('d M Y', strtotime($registration->tanggal_mulai)) . "*\n"
                . "• Selesai: *" . date('d M Y', strtotime($registration->tanggal_selesai)) . "*\n\n"
                . "📝 *Informasi Penting:*\n"
                . "• Hadir tepat waktu sesuai jadwal\n"
                . "• Membawa surat pengantar dari institusi\n"
                . "• Berpakaian rapi dan sopan\n"
                . "• Membawa laptop sendiri (jika ada)\n\n"
                . ($catatan ? "📌 *Catatan Admin:*\n{$catatan}\n\n" : "")
                . "Jika ada pertanyaan, silakan hubungi kami.\n\n"
                . "Terima kasih dan selamat bergabung! 🚀\n\n"
                . "*Global Intermedia Nusantara*";
        } else {
            $pesan = "📋 *UPDATE STATUS PENDAFTARAN*\n\n"
                . "Halo, *{$nama}*!\n\n"
                . "Pendaftaran PKL/Magang kamu di *Global Intermedia Nusantara* dinyatakan *TIDAK DITERIMA*.\n\n"
                . "📋 *Detail Pendaftaran:*\n"
                . "• Kode: *{$kode}*\n"
                . "• Nama: *{$nama}*\n"
                . "• Institusi: *{$institusi}*\n"
                . "• Jenjang: *{$jenis}*\n\n"
                . ($catatan ? "📌 *Alasan:*\n{$catatan}\n\n" : "")
                . "Kami mengucapkan terima kasih atas minat dan waktu yang telah diberikan. "
                . "Jangan berkecil hati, tetap semangat dan terus kembangkan diri! 💪\n\n"
                . "Jika ada pertanyaan, silakan hubungi kami.\n\n"
                . "*Global Intermedia Nusantara*";
        }

        $this->whatsappService->kirim($nomorWA, $pesan);
        
        Log::info('WA status update individu terkirim', [
            'kode' => $kode,
            'status' => $status,
            'nomor' => $nomorWA
        ]);
    }

    private function getStatusLabel($status)
    {
        return match ($status) {
            'pending' => 'Pending',
            'diterima' => 'Diterima',
            'ditolak' => 'Tidak Diterima',
            default => $status
        };
    }

    public function destroy($id)
    {
        if ($redirect = $this->checkAdminAuth()) return $redirect;

        try {
            DB::beginTransaction();

            $registration = Pendaftaran::findOrFail($id);

            if ($registration->file_surat_pengantar && file_exists(public_path('uploads/' . $registration->file_surat_pengantar))) {
                unlink(public_path('uploads/' . $registration->file_surat_pengantar));
            }

            if ($registration->cv_ind && file_exists(public_path('uploads/' . $registration->cv_ind))) {
                unlink(public_path('uploads/' . $registration->cv_ind));
            }

            $registration->delete();

            DB::commit();

            if (request()->ajax() || request()->wantsJson()) {
                $pendingIndividu = Pendaftaran::where('status', 'pending')->count();
                $pendingKelompok = Kelompok::where('status', 'pending')->count();

                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil dihapus!',
                    'pending_individu' => $pendingIndividu,
                    'pending_kelompok' => $pendingKelompok
                ]);
            }

            return redirect()->route('admin.individu.index')->with('success', 'Data berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error delete individu: ' . $e->getMessage());

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal menghapus data'], 500);
            }

            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function massDestroy(Request $request)
    {
        if ($redirect = $this->checkAdminAuth()) return $redirect;

        try {
            $ids = $request->ids;
            if (!is_array($ids) || empty($ids)) {
                return response()->json(['success' => false, 'message' => 'Tidak ada data'], 400);
            }

            DB::beginTransaction();

            $registrations = Pendaftaran::whereIn('id', $ids)->get();
            foreach ($registrations as $reg) {
                if ($reg->file_surat_pengantar && file_exists(public_path('uploads/' . $reg->file_surat_pengantar))) {
                    unlink(public_path('uploads/' . $reg->file_surat_pengantar));
                }
                if ($reg->cv_ind && file_exists(public_path('uploads/' . $reg->cv_ind))) {
                    unlink(public_path('uploads/' . $reg->cv_ind));
                }
            }

            Pendaftaran::whereIn('id', $ids)->delete();
            DB::commit();

            $pendingIndividu = Pendaftaran::where('status', 'pending')->count();
            $pendingKelompok = Kelompok::where('status', 'pending')->count();

            return response()->json([
                'success' => true,
                'message' => count($ids) . ' data berhasil dihapus',
                'pending_individu' => $pendingIndividu,
                'pending_kelompok' => $pendingKelompok,
                'redirect' => route('admin.individu.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error mass delete individu: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal'], 500);
        }
    }

    public function downloadSurat($id)
    {
        if ($redirect = $this->checkAdminAuth()) return $redirect;

        try {
            $registration = Pendaftaran::findOrFail($id);
            if (!$registration->file_surat_pengantar) {
                return redirect()->back()->with('error', 'File tidak ditemukan.');
            }
            $path = public_path('uploads/' . $registration->file_surat_pengantar);
            if (!file_exists($path)) {
                return redirect()->back()->with('error', 'File tidak ada di server.');
            }
            return response()->download($path, basename($registration->file_surat_pengantar));
        } catch (\Exception $e) {
            Log::error('Error download surat: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal download');
        }
    }

    public function viewSurat($id)
    {
        if ($redirect = $this->checkAdminAuth()) return $redirect;

        try {
            $registration = Pendaftaran::findOrFail($id);
            if (!$registration->file_surat_pengantar) {
                return redirect()->back()->with('error', 'File tidak ditemukan.');
            }
            $path = public_path('uploads/' . $registration->file_surat_pengantar);
            if (!file_exists($path)) {
                return redirect()->back()->with('error', 'File tidak ada.');
            }
            return response()->file($path, ['Content-Type' => mime_content_type($path)]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuka file');
        }
    }

    public function downloadCv($id)
    {
        if ($redirect = $this->checkAdminAuth()) return $redirect;

        try {
            $registration = Pendaftaran::findOrFail($id);
            if (!$registration->cv_ind) {
                return redirect()->back()->with('error', 'CV tidak ditemukan.');
            }
            $path = public_path('uploads/' . $registration->cv_ind);
            if (!file_exists($path)) {
                return redirect()->back()->with('error', 'File tidak ada.');
            }
            return response()->download($path, basename($registration->cv_ind));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal download');
        }
    }

    public function export(Request $request)
    {
        if ($redirect = $this->checkAdminAuth()) return $redirect;

        try {
            $query = Pendaftaran::query();
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('no_whatsapp', 'like', "%{$search}%")
                        ->orWhere('kode_pendaftaran', 'like', "%{$search}%");
                });
            }
            if ($request->filled('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            $data = $query->orderBy('created_at', 'desc')->get();
            $filename = 'data-pendaftaran-individu-' . date('Y-m-d-His') . '.xls';

            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=\"$filename\"");
            echo '<html><head><meta charset="UTF-8"><style>body{font-family:Arial}table{border-collapse:collapse}th{background:#cc0000;color:#fff;padding:8px}td{padding:5px;border:1px solid #ddd}</style></head><body>';
            echo '<h2>DATA PENDAFTARAN INDIVIDU</h2><table><tr><th>No</th><th>Kode</th><th>Nama</th><th>Email</th><th>WA</th><th>Institusi</th><th>Status</th><th>Tanggal</th></tr>';

            $no = 1;
            foreach ($data as $row) {
                $institusi = $row->jenis_pendidikan == 'smk' ? $row->sekolah : $row->kuliah;
                $statusText = $this->getStatusLabel($row->status);
                echo '<tr><td>' . $no++ . '</td><td>' . $row->kode_pendaftaran . '</td><td>' . htmlspecialchars($row->nama_lengkap) . '</td><td>' . $row->email . '</td><td>\'' . $row->no_whatsapp . '</td><td>' . htmlspecialchars($institusi) . '</td><td>' . $statusText . '</td><td>' . $row->created_at->format('d/m/Y') . '</td></tr>';
            }

            echo '</table></body></html>';
            exit;
        } catch (\Exception $e) {
            Log::error('Error export individu: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export');
        }
    }
}