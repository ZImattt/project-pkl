<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\Kelompok;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;

class StatusController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function showStatusForm()
    {
        return view('user.status.status');
    }
    
    public function checkStatus(Request $request)
    {
        $request->validate([
            'kode_pendaftaran' => 'required|string'
        ]);
        
        $kode = $request->kode_pendaftaran;
        
        $pendaftaran = Pendaftaran::where('kode_pendaftaran', $kode)->first();
        
        if ($pendaftaran) {
            return redirect()->route('user.registration.detail', $pendaftaran->id)
                ->with('tipe', 'individu');
        }
        
        $kelompok = Kelompok::where('kode_pendaftaran', $kode)->first();
        
        if (!$kelompok) {
            $kelompok = Kelompok::whereRaw('LOWER(kode_pendaftaran) = ?', [strtolower($kode)])->first();
        }
        
        if ($kelompok) {
            return redirect()->route('user.registration.detail', $kelompok->id)
                ->with('tipe', 'kelompok');
        }
        
        return redirect()->back()->with('error', 'Kode pendaftaran tidak ditemukan');
    }
    
    public function showRegistrationDetail($id)
    {
        $tipe = session('tipe');
        
        if ($tipe == 'individu') {
            $data = Pendaftaran::find($id);
            if ($data) {
                return view('user.status.detail', [
                    'data' => $data, 
                    'tipe' => 'individu'
                ]);
            }
        }
        
        if ($tipe == 'kelompok') {
            $data = Kelompok::with('anggota')->find($id);
            if ($data) {
                return view('user.status.detail', [
                    'data' => $data, 
                    'tipe' => 'kelompok'
                ]);
            }
        }
        
        $data = Pendaftaran::find($id);
        if ($data) {
            return view('user.status.detail', [
                'data' => $data, 
                'tipe' => 'individu'
            ]);
        }
        
        $data = Kelompok::with('anggota')->find($id);
        if ($data) {
            return view('user.status.detail', [
                'data' => $data, 
                'tipe' => 'kelompok'
            ]);
        }
        
        return redirect()->route('user.status')->with('error', 'Data tidak ditemukan');
    }

    public function getKodeByWa(Request $request)
    {
        $request->validate(['no_whatsapp' => 'required|string|min:10']);

        try {
            $wa = preg_replace('/[^0-9]/', '', $request->no_whatsapp);
            
            if (substr($wa, 0, 2) == '62') $waDb = substr($wa, 2);
            elseif (substr($wa, 0, 1) == '0') $waDb = substr($wa, 1);
            else $waDb = $wa;

            $individus = Pendaftaran::where('no_whatsapp', $waDb)->get();
            $kelompoks = Kelompok::where('perwakilan_wa', $waDb)->get();
            $totalDitemukan = $individus->count() + $kelompoks->count();

            if ($totalDitemukan === 0) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Nomor WhatsApp tidak ditemukan.'
                ]);
            }

            foreach ($individus as $p) {
                $this->whatsappService->kirim($request->no_whatsapp, $p->kode_pendaftaran);
            }

            foreach ($kelompoks as $k) {
                $this->whatsappService->kirim($request->no_whatsapp, $k->kode_pendaftaran);
            }

            Log::info('Kode dikirim via WA', ['nomor' => $request->no_whatsapp, 'jumlah' => $totalDitemukan]);

            return response()->json([
                'success' => true, 
                'message' => 'Kode berhasil dikirim!',
                'kode_count' => $totalDitemukan
            ]);

        } catch (\Exception $e) {
            Log::error('Error getKodeByWa: ' . $e->getMessage());
            
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan.'
            ], 500);
        }
    }
}