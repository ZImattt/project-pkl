<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use App\Models\Anggota;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\WhatsAppService;

class KelompokController extends Controller
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

    private function getStatusLabel($status)
    {
        return match ($status) {
            'pending' => 'Pending',
            'diterima' => 'Diterima',
            'ditolak' => 'Tidak Diterima',
            default => $status
        };
    }

    private function kirimWaStatusUpdate($kelompok, $status, $catatan)
    {
        $nomorWA = $kelompok->perwakilan_wa;
        $kode = $kelompok->kode_pendaftaran;
        $namaKelompok = $kelompok->nama_kelompok;
        $namaPerwakilan = $kelompok->perwakilan_nama;
        $institusi = $kelompok->institusi;
        $jenis = $kelompok->perwakilan_jenis_pendidikan == 'smk' ? 'SMK/SMA' : 'Perguruan Tinggi';
        $jumlahAnggota = $kelompok->anggota->count() . '/' . $kelompok->jumlah_anggota;
        $catatan = $catatan ?? '';

        // Ambil nama anggota
        $anggotaList = $kelompok->anggota->pluck('nama')->implode(', ');

        if ($status === 'diterima') {
            $pesan = "🎉 *SELAMAT! PENDAFTARAN KELOMPOK DITERIMA*\n\n"
                . "Halo, *{$namaPerwakilan}*!\n\n"
                . "Pendaftaran PKL/Magang kelompok *{$namaKelompok}* di *Global Intermedia Nusantara* telah *DITERIMA*.\n\n"
                . "📋 *Detail Pendaftaran Kelompok:*\n"
                . "• Kode: *{$kode}*\n"
                . "• Nama Kelompok: *{$namaKelompok}*\n"
                . "• Perwakilan: *{$namaPerwakilan}*\n"
                . "• Institusi: *{$institusi}*\n"
                . "• Jenjang: *{$jenis}*\n"
                . "• Jumlah Anggota: *{$jumlahAnggota}*\n\n"
                . "👥 *Anggota Kelompok:*\n"
                . "{$anggotaList}\n\n"
                . "📅 *Periode PKL:*\n"
                . "• Mulai: *" . date('d M Y', strtotime($kelompok->tanggal_mulai)) . "*\n"
                . "• Selesai: *" . date('d M Y', strtotime($kelompok->tanggal_selesai)) . "*\n\n"
                . "📝 *Informasi Penting:*\n"
                . "• Hadir tepat waktu sesuai jadwal\n"
                . "• Membawa surat pengantar dari institusi\n"
                . "• Berpakaian rapi dan sopan\n"
                . "• Membawa laptop sendiri (jika ada)\n"
                . "• Koordinasikan dengan anggota kelompok\n\n"
                . ($catatan ? "📌 *Catatan Admin:*\n{$catatan}\n\n" : "")
                . "Jika ada pertanyaan, silakan hubungi kami.\n\n"
                . "Terima kasih dan selamat bergabung! 🚀\n\n"
                . "*Global Intermedia Nusantara*";
        } else {
            $pesan = "📋 *UPDATE STATUS PENDAFTARAN KELOMPOK*\n\n"
                . "Halo, *{$namaPerwakilan}*!\n\n"
                . "Pendaftaran PKL/Magang kelompok *{$namaKelompok}* di *Global Intermedia Nusantara* dinyatakan *TIDAK DITERIMA*.\n\n"
                . "📋 *Detail Pendaftaran Kelompok:*\n"
                . "• Kode: *{$kode}*\n"
                . "• Nama Kelompok: *{$namaKelompok}*\n"
                . "• Perwakilan: *{$namaPerwakilan}*\n"
                . "• Institusi: *{$institusi}*\n"
                . "• Jenjang: *{$jenis}*\n"
                . "• Jumlah Anggota: *{$jumlahAnggota}*\n\n"
                . "👥 *Anggota Kelompok:*\n"
                . "{$anggotaList}\n\n"
                . ($catatan ? "📌 *Alasan:*\n{$catatan}\n\n" : "")
                . "Kami mengucapkan terima kasih atas minat dan waktu yang telah diberikan. "
                . "Jangan berkecil hati, tetap semangat dan terus kembangkan diri! 💪\n\n"
                . "Jika ada pertanyaan, silakan hubungi kami.\n\n"
                . "*Global Intermedia Nusantara*";
        }

        $this->whatsappService->kirim($nomorWA, $pesan);
        
        Log::info('WA status update kelompok terkirim', [
            'kode' => $kode,
            'status' => $status,
            'nomor' => $nomorWA
        ]);
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

        $query = Kelompok::with('anggota');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_kelompok', 'like', "%{$search}%")
                    ->orWhere('institusi', 'like', "%{$search}%")
                    ->orWhere('perwakilan_nama', 'like', "%{$search}%")
                    ->orWhere('perwakilan_email', 'like', "%{$search}%")
                    ->orWhere('kode_pendaftaran', 'like', "%{$search}%")
                    ->orWhere('perwakilan_wa', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis_pendidikan') && $request->jenis_pendidikan != 'all') {
            $query->where('perwakilan_jenis_pendidikan', $request->jenis_pendidikan);
        }

        $kelompoks = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $totalPending = Kelompok::where('status', 'pending')->count();

        return view('admin.kelompok.index', compact('kelompoks', 'totalPending'));
    }

    public function show($id)
    {
        if ($redirect = $this->checkAdminAuth()) return $redirect;

        $kelompok = Kelompok::with('anggota')->findOrFail($id);
        $totalPending = Kelompok::where('status', 'pending')->count();

        return view('admin.kelompok.show', compact('kelompok', 'totalPending'));
    }

    public function updateStatus(Request $request, $id)
    {
        if ($redirect = $this->checkAdminAuth()) return $redirect;

        try {
            DB::beginTransaction();

            $kelompok = Kelompok::with('anggota')->findOrFail($id);

            $request->validate([
                'status' => 'required|in:pending,diterima,ditolak',
                'catatan_admin' => 'nullable|string|max:1000'
            ]);

            $oldStatus = $kelompok->status;
            $newStatus = $request->status;
            
            $kelompok->update([
                'status' => $newStatus,
                'catatan_admin' => $request->catatan_admin
            ]);

            DB::commit();

            // Kirim WA otomatis ke perwakilan jika status berubah
            if ($newStatus !== 'pending' && $newStatus !== $oldStatus) {
                try {
                    $this->kirimWaStatusUpdate($kelompok, $newStatus, $request->catatan_admin);
                } catch (\Exception $waError) {
                    Log::error('Gagal kirim WA update status kelompok: ' . $waError->getMessage());
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
                        'id' => $kelompok->id,
                        'status' => $newStatus,
                        'status_text' => $this->getStatusLabel($newStatus),
                        'catatan' => $kelompok->catatan_admin
                    ]
                ]);
            }

            return redirect()->route('admin.kelompok.show', $id)
                ->with('success', 'Status kelompok berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error update status kelompok: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal update status: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal update status: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        if ($redirect = $this->checkAdminAuth()) return $redirect;

        try {
            DB::beginTransaction();

            $kelompok = Kelompok::with('anggota')->findOrFail($id);

            if ($kelompok->upload_surat_pengantar && file_exists(public_path($kelompok->upload_surat_pengantar))) {
                unlink(public_path($kelompok->upload_surat_pengantar));
            }

            if ($kelompok->perwakilan_cv && file_exists(public_path($kelompok->perwakilan_cv))) {
                unlink(public_path($kelompok->perwakilan_cv));
            }

            foreach ($kelompok->anggota as $anggota) {
                if ($anggota->cv && file_exists(public_path($anggota->cv))) {
                    unlink(public_path($anggota->cv));
                }
                $anggota->delete();
            }

            $kelompok->delete();

            DB::commit();

            if (request()->ajax() || request()->wantsJson()) {
                $pendingIndividu = Pendaftaran::where('status', 'pending')->count();
                $pendingKelompok = Kelompok::where('status', 'pending')->count();

                return response()->json([
                    'success' => true,
                    'message' => 'Data kelompok berhasil dihapus!',
                    'pending_individu' => $pendingIndividu,
                    'pending_kelompok' => $pendingKelompok
                ]);
            }

            return redirect()->route('admin.kelompok.index')
                ->with('success', 'Data kelompok berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error delete kelompok: ' . $e->getMessage());

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus data: ' . $e->getMessage()
                ], 500);
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
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data yang dipilih'
                ], 400);
            }

            DB::beginTransaction();

            $kelompoks = Kelompok::with('anggota')->whereIn('id', $ids)->get();

            foreach ($kelompoks as $kelompok) {
                if ($kelompok->upload_surat_pengantar && file_exists(public_path($kelompok->upload_surat_pengantar))) {
                    unlink(public_path($kelompok->upload_surat_pengantar));
                }

                if ($kelompok->perwakilan_cv && file_exists(public_path($kelompok->perwakilan_cv))) {
                    unlink(public_path($kelompok->perwakilan_cv));
                }

                foreach ($kelompok->anggota as $anggota) {
                    if ($anggota->cv && file_exists(public_path($anggota->cv))) {
                        unlink(public_path($anggota->cv));
                    }
                }
            }

            Anggota::whereIn('kelompok_id', $ids)->delete();
            Kelompok::whereIn('id', $ids)->delete();

            DB::commit();

            $pendingIndividu = Pendaftaran::where('status', 'pending')->count();
            $pendingKelompok = Kelompok::where('status', 'pending')->count();

            return response()->json([
                'success' => true,
                'message' => count($ids) . ' kelompok berhasil dihapus',
                'pending_individu' => $pendingIndividu,
                'pending_kelompok' => $pendingKelompok,
                'redirect' => route('admin.kelompok.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error mass delete kelompok: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadSurat($id)
    {
        if ($redirect = $this->checkAdminAuth()) return $redirect;

        try {
            $kelompok = Kelompok::findOrFail($id);
            if (!$kelompok->upload_surat_pengantar) {
                return redirect()->back()->with('error', 'File surat pengantar tidak ditemukan.');
            }
            $path = public_path($kelompok->upload_surat_pengantar);
            if (!file_exists($path)) {
                return redirect()->back()->with('error', 'File surat pengantar tidak ditemukan di server.');
            }
            return response()->download($path, basename($kelompok->upload_surat_pengantar));
        } catch (\Exception $e) {
            Log::error('Error download surat kelompok: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengunduh file');
        }
    }

    public function viewSurat($id)
    {
        if ($redirect = $this->checkAdminAuth()) return $redirect;

        try {
            $kelompok = Kelompok::findOrFail($id);
            if (!$kelompok->upload_surat_pengantar) {
                return redirect()->back()->with('error', 'File surat pengantar tidak ditemukan.');
            }
            $path = public_path($kelompok->upload_surat_pengantar);
            if (!file_exists($path)) {
                return redirect()->back()->with('error', 'File surat pengantar tidak ditemukan di server.');
            }
            return response()->file($path, ['Content-Type' => mime_content_type($path)]);
        } catch (\Exception $e) {
            Log::error('Error view surat kelompok: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuka file');
        }
    }

    public function downloadCv($id)
    {
        if ($redirect = $this->checkAdminAuth()) return $redirect;

        try {
            $kelompok = Kelompok::findOrFail($id);
            if (!$kelompok->perwakilan_cv) {
                return redirect()->back()->with('error', 'File CV tidak ditemukan.');
            }
            $path = public_path($kelompok->perwakilan_cv);
            if (!file_exists($path)) {
                return redirect()->back()->with('error', 'File CV tidak ditemukan di server.');
            }
            return response()->download($path, basename($kelompok->perwakilan_cv));
        } catch (\Exception $e) {
            Log::error('Error download CV: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengunduh file');
        }
    }

    public function export(Request $request)
    {
        if ($redirect = $this->checkAdminAuth()) return $redirect;

        try {
            $query = Kelompok::with('anggota');

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama_kelompok', 'like', "%{$search}%")
                        ->orWhere('institusi', 'like', "%{$search}%")
                        ->orWhere('perwakilan_nama', 'like', "%{$search}%")
                        ->orWhere('perwakilan_email', 'like', "%{$search}%");
                });
            }

            if ($request->filled('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            $data = $query->orderBy('created_at', 'desc')->get();
            $filename = 'data-pendaftaran-kelompok-' . date('Y-m-d-His') . '.xls';

            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=\"$filename\"");

            echo '<html><head><meta charset="UTF-8"><style>';
            echo 'body{font-family:Arial}table{border-collapse:collapse}th{background:#cc0000;color:#fff;padding:8px}td{padding:5px;border:1px solid #ddd}';
            echo '</style></head><body>';
            echo '<h2>DATA PENDAFTARAN KELOMPOK</h2>';
            echo '<table><tr><th>No</th><th>Kode</th><th>Nama Kelompok</th><th>Institusi</th><th>Perwakilan</th><th>WA</th><th>Anggota</th><th>Tanggal</th><th>Status</th></tr>';

            $no = 1;
            foreach ($data as $row) {
                $statusText = $this->getStatusLabel($row->status);
                echo '<tr><td>' . $no++ . '</td><td>' . ($row->kode_pendaftaran ?? '-') . '</td><td>' . htmlspecialchars($row->nama_kelompok) . '</td><td>' . htmlspecialchars($row->institusi) . '</td><td>' . htmlspecialchars($row->perwakilan_nama) . '</td><td>\'' . $row->perwakilan_wa . '</td><td>' . $row->anggota->count() . '/' . $row->jumlah_anggota . '</td><td>' . date('d/m/Y', strtotime($row->created_at)) . '</td><td>' . $statusText . '</td></tr>';
            }

            echo '</table></body></html>';
            exit;

        } catch (\Exception $e) {
            Log::error('Error export kelompok: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export data');
        }
    }

    public function exportSingle($id)
    {
        if ($redirect = $this->checkAdminAuth()) return $redirect;

        try {
            $kelompok = Kelompok::with('anggota')->findOrFail($id);
            $filename = 'kelompok-' . $kelompok->kode_pendaftaran . '.xls';

            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=\"$filename\"");

            echo '<html><head><meta charset="UTF-8"><style>';
            echo 'body{font-family:Arial}table{border-collapse:collapse}th{background:#cc0000;color:#fff;padding:8px}td{padding:5px;border:1px solid #ddd}';
            echo '</style></head><body>';
            echo '<h2>DETAIL KELOMPOK: ' . htmlspecialchars($kelompok->nama_kelompok) . '</h2>';
            echo '<p>Kode: ' . $kelompok->kode_pendaftaran . ' | Status: ' . $this->getStatusLabel($kelompok->status) . '</p>';
            echo '<h3>Anggota</h3><table><tr><th>No</th><th>Nama</th><th>NIM/NIS</th><th>Email</th><th>Telepon</th></tr>';

            foreach ($kelompok->anggota as $i => $a) {
                echo '<tr><td>' . ($i + 1) . '</td><td>' . htmlspecialchars($a->nama) . '</td><td>' . $a->nim_nis . '</td><td>' . $a->email . '</td><td>\'' . $a->telepon . '</td></tr>';
            }

            echo '</table></body></html>';
            exit;

        } catch (\Exception $e) {
            Log::error('Error export single kelompok: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export data');
        }
    }
}