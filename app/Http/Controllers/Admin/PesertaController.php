<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Kelompok;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class PesertaController extends Controller
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
            $individu = Pendaftaran::where('status', 'diterima')->get();
            $kelompok = Kelompok::with('anggota')->where('status', 'diterima')->get();
            
            $allParticipants = collect();
            $jumlahIndividu = 0;
            $jumlahKelompok = 0;
            
            // PROSES INDIVIDU (tiap individu 1 baris)
            foreach ($individu as $p) {
                $p->tipe_peserta = 'individu';
                $p->kelompok_id = null;
                $p->nama_kelompok = null;
                $p->registration_id = 'IND-' . str_pad($p->id, 5, '0', STR_PAD_LEFT);
                $p->bidang = $p->bidang ?? '-';
                $p->jumlah_anggota = 1;
                $p->anggota_list = null;
                
                $allParticipants->push($p);
                $jumlahIndividu++;
            }
            
            // PROSES KELOMPOK (1 kelompok = 1 baris)
            foreach ($kelompok as $k) {
                $peserta = new \stdClass();
                $peserta->id = $k->id;
                $peserta->tipe_peserta = 'kelompok';
                $peserta->kelompok_id = $k->id;
                $peserta->nama_kelompok = $k->nama_kelompok;
                $peserta->nama_lengkap = $k->perwakilan_nama;
                $peserta->email = $k->perwakilan_email;
                $peserta->no_whatsapp = $k->perwakilan_wa;
                $peserta->jenis_pendidikan = $k->perwakilan_jenis_pendidikan;
                $peserta->jumlah_anggota = $k->anggota->count();
                $peserta->anggota_list = $k->anggota;
                
                if ($k->perwakilan_jenis_pendidikan == 'smk') {
                    $peserta->sekolah = $k->institusi;
                    $peserta->kuliah = null;
                    $peserta->jurusan = $k->perwakilan_jurusan_smk ?? '-';
                } else {
                    $peserta->sekolah = null;
                    $peserta->kuliah = $k->institusi;
                    $peserta->jurusan = $k->perwakilan_jurusan_univ ?? '-';
                }
                
                $peserta->tanggal_mulai = $k->tanggal_mulai;
                $peserta->tanggal_selesai = $k->tanggal_selesai;
                $peserta->bidang = $k->bidang ?? '-';
                $peserta->status = $k->status;
                $peserta->registration_id = 'KLP-' . str_pad($k->id, 5, '0', STR_PAD_LEFT);
                
                $allParticipants->push($peserta);
                $jumlahKelompok++;
            }
            
            $today = Carbon::now();
            $stats = [
                'active' => 0,
                'upcoming' => 0,
                'ending_soon' => 0,
                'completed' => 0,
                'total_individu' => $jumlahIndividu,
                'total_kelompok' => $jumlahKelompok
            ];
            
            foreach ($allParticipants as $participant) {
                $startDate = Carbon::parse($participant->tanggal_mulai);
                $endDate = Carbon::parse($participant->tanggal_selesai);
                
                if ($today->greaterThan($endDate)) {
                    $stats['completed']++;
                } elseif ($today->lessThan($startDate)) {
                    $stats['upcoming']++;
                } else {
                    $daysUntilEnd = $today->diffInDays($endDate);
                    if ($daysUntilEnd <= 7) {
                        $stats['ending_soon']++;
                    } else {
                        $stats['active']++;
                    }
                }
            }
            
            $tipe = $request->get('tipe', 'all');
            $status = $request->get('status', 'all');
            $search = $request->get('search', '');
            
            $filteredParticipants = $allParticipants->filter(function($participant) use ($tipe, $status, $search, $today) {
                if ($tipe != 'all' && $tipe != $participant->tipe_peserta) {
                    return false;
                }
                
                if ($status != 'all') {
                    $startDate = Carbon::parse($participant->tanggal_mulai);
                    $endDate = Carbon::parse($participant->tanggal_selesai);
                    
                    if ($status == 'completed') {
                        if (!$today->greaterThan($endDate)) return false;
                    } elseif ($status == 'upcoming') {
                        if (!$today->lessThan($startDate)) return false;
                    } elseif ($status == 'active') {
                        if ($today->lessThan($startDate) || $today->greaterThan($endDate)) return false;
                        $daysUntilEnd = $today->diffInDays($endDate);
                        if ($daysUntilEnd <= 7) return false;
                    } elseif ($status == 'ending-soon') {
                        if ($today->lessThan($startDate) || $today->greaterThan($endDate)) return false;
                        $daysUntilEnd = $today->diffInDays($endDate);
                        if ($daysUntilEnd > 7) return false;
                    }
                }
                
                if (!empty($search)) {
                    $institution = '';
                    if (isset($participant->sekolah) && $participant->sekolah) {
                        $institution = $participant->sekolah;
                    } elseif (isset($participant->kuliah) && $participant->kuliah) {
                        $institution = $participant->kuliah;
                    }
                    
                    $namaKelompok = $participant->nama_kelompok ?? '';
                    
                    $match = stripos($participant->nama_lengkap ?? '', $search) !== false || 
                             stripos($institution, $search) !== false ||
                             stripos($namaKelompok, $search) !== false;
                             
                    if (!$match) return false;
                }
                
                return true;
            });
            
            $perPage = 10;
            $currentPage = $request->get('page', 1);
            $offset = ($currentPage - 1) * $perPage;
            
            $participants = new LengthAwarePaginator(
                $filteredParticipants->slice($offset, $perPage)->values(),
                $filteredParticipants->count(),
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );
            
            $totalParticipants = $allParticipants->count();
            
            return view('admin.peserta.index', compact(
                'participants',
                'totalParticipants',
                'stats'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error peserta: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat data peserta: ' . $e->getMessage());
        }
    }
}