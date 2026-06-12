<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelompok;
use App\Models\Anggota;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Services\WhatsAppService;

class PendaftaranController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function generateKodeIndividu()
    {
        $kode = $this->generateKodePendaftaran('IND');
        return response()->json(['success' => true, 'kode' => $kode]);
    }
    
    public function generateKodeKelompok()
    {
        $kode = $this->generateKodePendaftaran('KLP');
        return response()->json(['success' => true, 'kode' => $kode]);
    }
    
    private function generateKodePendaftaran($prefix)
    {
        do {
            $date = date('Ymd');
            $random = strtoupper(Str::random(5));
            $kode = $prefix . '-' . $date . '-' . $random;
            
            if ($prefix === 'IND') {
                $exists = Pendaftaran::where('kode_pendaftaran', $kode)->exists();
            } else {
                $exists = Kelompok::where('kode_pendaftaran', $kode)->exists();
            }
        } while ($exists);
        
        return $kode;
    }
    
    private function formatPhoneNumber($phone)
    {
        if (empty($phone)) return null;
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (substr($phone, 0, 2) == '62') {
            $phone = substr($phone, 2);
        } elseif (substr($phone, 0, 1) == '0') {
            $phone = substr($phone, 1);
        }
        return $phone;
    }

    // ==================== INDIVIDU ====================
    
    public function showRegistrationForm()
    {
        $kodePendaftaran = $this->generateKodePendaftaran('IND');
        return view('user.individu.register', compact('kodePendaftaran'));
    }
    
    public function storeRegistration(Request $request)
    {
        $request->validate([
            'kode_pendaftaran' => 'required|string|unique:pendaftarans,kode_pendaftaran',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:-15 years',
            'alamat_lengkap' => 'required|string',
            'email' => 'required|email|unique:pendaftarans,email',
            'no_whatsapp' => 'required|string|max:20',
            'jenis_pendidikan' => 'required|in:smk,kuliah',
            'alasan_pkl_gi' => 'required|string',
            'skill_ingin_dipelajari' => 'required|string',
            'harapan_setelah_pkl' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'upload_surat_pengantar' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'upload_cv' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'terms' => 'nullable|accepted'
        ]);
        
        try {
            DB::beginTransaction();
            
            $kodePendaftaran = Pendaftaran::where('kode_pendaftaran', $request->kode_pendaftaran)->exists() 
                ? $this->generateKodePendaftaran('IND') 
                : $request->kode_pendaftaran;
            
            $filePath = null;
            if ($request->hasFile('upload_surat_pengantar')) {
                $file = $request->file('upload_surat_pengantar');
                $fileName = $kodePendaftaran . '_surat_' . time() . '.' . $file->getClientOriginalExtension();
                $uploadPath = public_path('uploads/surat_pengantar');
                if (!file_exists($uploadPath)) mkdir($uploadPath, 0777, true);
                $file->move($uploadPath, $fileName);
                $filePath = 'uploads/surat_pengantar/' . $fileName;
            }
            
            $cvPath = null;
            if ($request->hasFile('upload_cv')) {
                $file = $request->file('upload_cv');
                $fileName = $kodePendaftaran . '_cv_ind_' . time() . '.' . $file->getClientOriginalExtension();
                $uploadPath = public_path('uploads/cv');
                if (!file_exists($uploadPath)) mkdir($uploadPath, 0777, true);
                $file->move($uploadPath, $fileName);
                $cvPath = 'uploads/cv/' . $fileName;
            }
            
            Pendaftaran::create([
                'kode_pendaftaran' => $kodePendaftaran,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat_lengkap' => $request->alamat_lengkap,
                'email' => $request->email,
                'no_whatsapp' => $this->formatPhoneNumber($request->no_whatsapp),
                'jenis_pendidikan' => $request->jenis_pendidikan,
                'sekolah' => $request->sekolah,
                'jurusan_smk' => $request->jurusan_smk,
                'kelas' => $request->kelas,
                'nis' => $request->nis,
                'guru_pembimbing' => $request->guru_pembimbing,
                'no_hp_guru' => $request->no_hp_guru ? $this->formatPhoneNumber($request->no_hp_guru) : null,
                'kuliah' => $request->kuliah,
                'jurusan_kuliah' => $request->jurusan_univ,
                'semester' => $request->semester,
                'nim' => $request->nim,
                'dosen_pembimbing' => $request->dosen_pembimbing,
                'no_hp_dosen' => $request->no_hp_dosen ? $this->formatPhoneNumber($request->no_hp_dosen) : null,
                'alasan_pkl_gi' => $request->alasan_pkl_gi,
                'skill_ingin_dipelajari' => $request->skill_ingin_dipelajari,
                'harapan_setelah_pkl' => $request->harapan_setelah_pkl,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'file_surat_pengantar' => $filePath,
                'cv_ind' => $cvPath,
                'status' => 'pending',
                'bidang' => $request->bidang ?? null
            ]);
            
            DB::commit();

            Log::info('Pendaftaran individu berhasil', [
                'kode' => $kodePendaftaran,
                'nama' => $request->nama_lengkap,
                'wa' => $request->no_whatsapp
            ]);

            // KIRIM WA - menggunakan method yang sudah ada di WhatsAppService
            $pesanWA = $this->whatsappService->buatPesanPendaftaranIndividu(
                $request->nama_lengkap,
                $kodePendaftaran
            );
            
            $hasil = $this->whatsappService->kirim($request->no_whatsapp, $pesanWA);
            
            Log::info('Hasil kirim WA individu', ['hasil' => $hasil, 'nomor' => $request->no_whatsapp]);
            
            return redirect()->route('user.status')
                ->with('success', 'Pendaftaran berhasil! Kode: ' . $kodePendaftaran)
                ->with('kode_pendaftaran', $kodePendaftaran);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error store individu: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan data. ' . $e->getMessage())->withInput();
        }
    }

    // ==================== KELOMPOK ====================
    
    public function formKelompok()
    {
        $kodePendaftaran = $this->generateKodePendaftaran('KLP');
        return view('user.kelompok.data-kelompok', compact('kodePendaftaran'));
    }
    
    public function editKelompok($id)
    {
        $kelompok = Kelompok::with('anggota')->findOrFail($id);
        return view('user.kelompok.data-kelompok', compact('kelompok'));
    }
    
    public function storeKelompok(Request $request)
    {
        $request->validate([
            'kode_pendaftaran' => 'required|string|unique:kelompoks,kode_pendaftaran',
            'nama_kelompok' => 'required|string|max:255|unique:kelompoks,nama_kelompok',
            'jumlah_anggota' => 'required|in:2,3,4,5',
            'institusi' => 'required|string|max:255',
            'perwakilan_nama' => 'required|string|max:255',
            'perwakilan_jenis_kelamin' => 'required|in:L,P',
            'perwakilan_tempat_lahir' => 'required|string|max:255',
            'perwakilan_tanggal_lahir' => 'required|date|before:-15 years',
            'perwakilan_email' => 'required|email|unique:kelompoks,perwakilan_email',
            'perwakilan_wa' => 'required|string|max:20',
            'perwakilan_alamat' => 'required|string',
            'perwakilan_jenis_pendidikan' => 'required|in:smk,kuliah',
            'alasan_pkl_gi' => 'required|string',
            'skill_ingin_dipelajari' => 'required|string',
            'harapan_setelah_pkl' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'upload_surat_pengantar' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'perwakilan_cv' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'terms' => 'nullable|accepted'
        ]);
        
        try {
            DB::beginTransaction();
            
            $kodePendaftaran = $this->generateKodePendaftaran('KLP');
            
            $suratPath = null;
            if ($request->hasFile('upload_surat_pengantar')) {
                $file = $request->file('upload_surat_pengantar');
                $fileName = $kodePendaftaran . '_surat_' . time() . '.' . $file->getClientOriginalExtension();
                $uploadPath = public_path('uploads/surat_pengantar');
                if (!file_exists($uploadPath)) mkdir($uploadPath, 0777, true);
                $file->move($uploadPath, $fileName);
                $suratPath = 'uploads/surat_pengantar/' . $fileName;
            }
            
            $cvPath = null;
            if ($request->hasFile('perwakilan_cv')) {
                $file = $request->file('perwakilan_cv');
                $fileName = $kodePendaftaran . '_cv_' . time() . '.' . $file->getClientOriginalExtension();
                $uploadPath = public_path('uploads/cv');
                if (!file_exists($uploadPath)) mkdir($uploadPath, 0777, true);
                $file->move($uploadPath, $fileName);
                $cvPath = 'uploads/cv/' . $fileName;
            }
            
            $wa = $this->formatPhoneNumber($request->perwakilan_wa);
            
            $kelompok = Kelompok::create([
                'kode_pendaftaran' => $kodePendaftaran,
                'nama_kelompok' => $request->nama_kelompok,
                'jumlah_anggota' => $request->jumlah_anggota,
                'institusi' => $request->institusi,
                'perwakilan_nama' => $request->perwakilan_nama,
                'perwakilan_jenis_kelamin' => $request->perwakilan_jenis_kelamin,
                'perwakilan_tempat_lahir' => $request->perwakilan_tempat_lahir,
                'perwakilan_tanggal_lahir' => $request->perwakilan_tanggal_lahir,
                'perwakilan_email' => $request->perwakilan_email,
                'perwakilan_wa' => $wa,
                'perwakilan_alamat' => $request->perwakilan_alamat,
                'perwakilan_jenis_pendidikan' => $request->perwakilan_jenis_pendidikan,
                'perwakilan_sekolah' => $request->perwakilan_sekolah,
                'perwakilan_jurusan_smk' => $request->perwakilan_jurusan_smk,
                'perwakilan_kelas' => $request->perwakilan_kelas,
                'perwakilan_nis' => $request->perwakilan_nis,
                'perwakilan_guru_pembimbing' => $request->perwakilan_guru_pembimbing,
                'perwakilan_no_hp_guru' => $request->perwakilan_no_hp_guru ? $this->formatPhoneNumber($request->perwakilan_no_hp_guru) : null,
                'perwakilan_kampus' => $request->perwakilan_kampus,
                'perwakilan_jurusan_univ' => $request->perwakilan_jurusan_univ,
                'perwakilan_semester' => $request->perwakilan_semester,
                'perwakilan_nim' => $request->perwakilan_nim,
                'perwakilan_dosen_pembimbing' => $request->perwakilan_dosen_pembimbing,
                'perwakilan_no_hp_dosen' => $request->perwakilan_no_hp_dosen ? $this->formatPhoneNumber($request->perwakilan_no_hp_dosen) : null,
                'perwakilan_cv' => $cvPath,
                'alasan_pkl_gi' => $request->alasan_pkl_gi,
                'skill_ingin_dipelajari' => $request->skill_ingin_dipelajari,
                'harapan_setelah_pkl' => $request->harapan_setelah_pkl,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'upload_surat_pengantar' => $suratPath,
                'status' => 'pending'
            ]);
            
            Anggota::create([
                'kelompok_id' => $kelompok->id,
                'nama' => $request->perwakilan_nama,
                'jenis_kelamin' => $request->perwakilan_jenis_kelamin,
                'nim_nis' => $request->perwakilan_jenis_pendidikan == 'smk' ? ($request->perwakilan_nis ?? '') : ($request->perwakilan_nim ?? ''),
                'email' => $request->perwakilan_email,
                'telepon' => $wa,
                'tempat_lahir' => $request->perwakilan_tempat_lahir,
                'tanggal_lahir' => $request->perwakilan_tanggal_lahir,
                'alamat' => $request->perwakilan_alamat,
                'is_perwakilan' => true
            ]);
            
            DB::commit();

            Log::info('Pendaftaran kelompok berhasil', [
                'kode' => $kodePendaftaran,
                'nama' => $request->perwakilan_nama,
                'kelompok' => $request->nama_kelompok,
                'wa' => $request->perwakilan_wa
            ]);

            // KIRIM WA - menggunakan method yang sudah ada di WhatsAppService
            $pesanWA = $this->whatsappService->buatPesanPendaftaranKelompok(
                $request->perwakilan_nama,
                $kodePendaftaran,
                $request->nama_kelompok
            );
            
            $hasil = $this->whatsappService->kirim($request->perwakilan_wa, $pesanWA);
            
            Log::info('Hasil kirim WA kelompok', ['hasil' => $hasil, 'nomor' => $request->perwakilan_wa]);
            
            return redirect()->route('user.kelompok.tambah-peserta', $kelompok->id)
                ->with('success', 'Data kelompok berhasil disimpan! Kode: ' . $kodePendaftaran);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error store kelompok: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan data. ' . $e->getMessage())->withInput();
        }
    }
    
    public function updateKelompok(Request $request, $id)
    {
        $kelompok = Kelompok::findOrFail($id);
        
        $request->validate([
            'nama_kelompok' => 'required|string|max:255|unique:kelompoks,nama_kelompok,' . $id,
            'jumlah_anggota' => 'required|in:2,3,4,5',
            'institusi' => 'required|string|max:255',
            'perwakilan_nama' => 'required|string|max:255',
            'perwakilan_jenis_kelamin' => 'required|in:L,P',
            'perwakilan_tempat_lahir' => 'required|string|max:255',
            'perwakilan_tanggal_lahir' => 'required|date|before:-15 years',
            'perwakilan_email' => 'required|email|unique:kelompoks,perwakilan_email,' . $id,
            'perwakilan_wa' => 'required|string|max:20',
            'perwakilan_alamat' => 'required|string',
            'perwakilan_jenis_pendidikan' => 'required|in:smk,kuliah',
            'alasan_pkl_gi' => 'required|string',
            'skill_ingin_dipelajari' => 'required|string',
            'harapan_setelah_pkl' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'upload_surat_pengantar' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'perwakilan_cv' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);
        
        try {
            DB::beginTransaction();
            
            $wa = $this->formatPhoneNumber($request->perwakilan_wa);
            
            $updateData = [
                'nama_kelompok' => $request->nama_kelompok,
                'jumlah_anggota' => $request->jumlah_anggota,
                'institusi' => $request->institusi,
                'perwakilan_nama' => $request->perwakilan_nama,
                'perwakilan_jenis_kelamin' => $request->perwakilan_jenis_kelamin,
                'perwakilan_tempat_lahir' => $request->perwakilan_tempat_lahir,
                'perwakilan_tanggal_lahir' => $request->perwakilan_tanggal_lahir,
                'perwakilan_email' => $request->perwakilan_email,
                'perwakilan_wa' => $wa,
                'perwakilan_alamat' => $request->perwakilan_alamat,
                'perwakilan_jenis_pendidikan' => $request->perwakilan_jenis_pendidikan,
                'perwakilan_sekolah' => $request->perwakilan_sekolah,
                'perwakilan_jurusan_smk' => $request->perwakilan_jurusan_smk,
                'perwakilan_kelas' => $request->perwakilan_kelas,
                'perwakilan_nis' => $request->perwakilan_nis,
                'perwakilan_guru_pembimbing' => $request->perwakilan_guru_pembimbing,
                'perwakilan_no_hp_guru' => $request->perwakilan_no_hp_guru ? $this->formatPhoneNumber($request->perwakilan_no_hp_guru) : null,
                'perwakilan_kampus' => $request->perwakilan_kampus,
                'perwakilan_jurusan_univ' => $request->perwakilan_jurusan_univ,
                'perwakilan_semester' => $request->perwakilan_semester,
                'perwakilan_nim' => $request->perwakilan_nim,
                'perwakilan_dosen_pembimbing' => $request->perwakilan_dosen_pembimbing,
                'perwakilan_no_hp_dosen' => $request->perwakilan_no_hp_dosen ? $this->formatPhoneNumber($request->perwakilan_no_hp_dosen) : null,
                'alasan_pkl_gi' => $request->alasan_pkl_gi,
                'skill_ingin_dipelajari' => $request->skill_ingin_dipelajari,
                'harapan_setelah_pkl' => $request->harapan_setelah_pkl,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
            ];
            
            if ($request->hasFile('upload_surat_pengantar')) {
                if ($kelompok->upload_surat_pengantar && file_exists(public_path($kelompok->upload_surat_pengantar))) {
                    unlink(public_path($kelompok->upload_surat_pengantar));
                }
                $file = $request->file('upload_surat_pengantar');
                $fileName = $kelompok->kode_pendaftaran . '_surat_' . time() . '.' . $file->getClientOriginalExtension();
                $uploadPath = public_path('uploads/surat_pengantar');
                if (!file_exists($uploadPath)) mkdir($uploadPath, 0777, true);
                $file->move($uploadPath, $fileName);
                $updateData['upload_surat_pengantar'] = 'uploads/surat_pengantar/' . $fileName;
            }
            
            if ($request->hasFile('perwakilan_cv')) {
                if ($kelompok->perwakilan_cv && file_exists(public_path($kelompok->perwakilan_cv))) {
                    unlink(public_path($kelompok->perwakilan_cv));
                }
                $file = $request->file('perwakilan_cv');
                $fileName = $kelompok->kode_pendaftaran . '_cv_' . time() . '.' . $file->getClientOriginalExtension();
                $uploadPath = public_path('uploads/cv');
                if (!file_exists($uploadPath)) mkdir($uploadPath, 0777, true);
                $file->move($uploadPath, $fileName);
                $updateData['perwakilan_cv'] = 'uploads/cv/' . $fileName;
            }
            
            $oldJumlahAnggota = $kelompok->jumlah_anggota;
            $kelompok->update($updateData);
            
            if ($request->jumlah_anggota < $oldJumlahAnggota) {
                $kelebihan = $kelompok->anggota()
                    ->where('is_perwakilan', false)
                    ->orderBy('id', 'desc')
                    ->limit($oldJumlahAnggota - $request->jumlah_anggota)
                    ->get();
                    
                foreach ($kelebihan as $anggota) {
                    $anggota->delete();
                }
            }
            
            $perwakilan = Anggota::where('kelompok_id', $kelompok->id)->where('is_perwakilan', true)->first();
            if ($perwakilan) {
                $perwakilan->update([
                    'nama' => $request->perwakilan_nama,
                    'jenis_kelamin' => $request->perwakilan_jenis_kelamin,
                    'nim_nis' => $request->perwakilan_jenis_pendidikan == 'smk' ? ($request->perwakilan_nis ?? $perwakilan->nim_nis) : ($request->perwakilan_nim ?? $perwakilan->nim_nis),
                    'email' => $request->perwakilan_email,
                    'telepon' => $wa,
                    'tempat_lahir' => $request->perwakilan_tempat_lahir,
                    'tanggal_lahir' => $request->perwakilan_tanggal_lahir,
                    'alamat' => $request->perwakilan_alamat,
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('user.kelompok.tambah-peserta', $kelompok->id)
                ->with('success', 'Data kelompok berhasil diupdate!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error update kelompok: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengupdate data. ' . $e->getMessage())->withInput();
        }
    }
    
    public function tambahPesertaKelompok($kelompokId)
    {
        $kelompok = Kelompok::with('anggota')->findOrFail($kelompokId);
        return view('user.kelompok.tambah-peserta', compact('kelompok'));
    }
    
    public function simpanPesertaKelompok(Request $request)
    {
        $request->validate([
            'kelompok_id' => 'required|exists:kelompoks,id',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'nim_nis' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'telepon' => 'required|string|max:15',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string'
        ]);
        
        try {
            DB::beginTransaction();
            
            $kelompok = Kelompok::findOrFail($request->kelompok_id);
            
            $currentCount = $kelompok->anggota->count();
            if ($currentCount >= $kelompok->jumlah_anggota) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Jumlah anggota sudah maksimal (' . $kelompok->jumlah_anggota . ' orang).')->withInput();
            }
            
            $existingNim = Anggota::where('kelompok_id', $kelompok->id)
                ->where('nim_nis', $request->nim_nis)
                ->exists();
            if ($existingNim) {
                DB::rollBack();
                return redirect()->back()->with('error', 'NIM/NIS sudah digunakan oleh anggota lain dalam kelompok ini.')->withInput();
            }
            
            $existingEmail = Anggota::where('kelompok_id', $kelompok->id)
                ->where('email', $request->email)
                ->exists();
            if ($existingEmail) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Email sudah digunakan oleh anggota lain dalam kelompok ini.')->withInput();
            }
            
            Anggota::create([
                'kelompok_id' => $request->kelompok_id,
                'nama' => $request->nama,
                'jenis_kelamin' => $request->jenis_kelamin,
                'nim_nis' => $request->nim_nis,
                'email' => $request->email,
                'telepon' => $this->formatPhoneNumber($request->telepon),
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'is_perwakilan' => false
            ]);
            
            DB::commit();
            
            $sisa = $kelompok->jumlah_anggota - $kelompok->anggota()->count();
            $message = 'Anggota berhasil ditambahkan!';
            if ($sisa > 0) {
                $message .= ' Masih perlu ' . $sisa . ' anggota lagi.';
            } else {
                $message .= ' Semua anggota sudah lengkap. Silakan klik "Selesai & Lanjut" untuk menyelesaikan pendaftaran.';
            }
            
            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error simpan peserta: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan data. ' . $e->getMessage())->withInput();
        }
    }
    
    public function updateAnggota(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|exists:anggotas,id',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'nim_nis' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'telepon' => 'required|string|max:15',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string'
        ]);
        
        try {
            DB::beginTransaction();
            
            $anggota = Anggota::findOrFail($request->anggota_id);
            
            if ($anggota->is_perwakilan) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Data perwakilan tidak dapat diedit di halaman ini. Silakan gunakan menu Edit Kelompok.');
            }
            
            $existingNim = Anggota::where('kelompok_id', $anggota->kelompok_id)
                ->where('nim_nis', $request->nim_nis)
                ->where('id', '!=', $anggota->id)
                ->exists();
                
            if ($existingNim) {
                DB::rollBack();
                return redirect()->back()->with('error', 'NIM/NIS sudah digunakan oleh anggota lain dalam kelompok ini.')->withInput();
            }
            
            $existingEmail = Anggota::where('kelompok_id', $anggota->kelompok_id)
                ->where('email', $request->email)
                ->where('id', '!=', $anggota->id)
                ->exists();
                
            if ($existingEmail) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Email sudah digunakan oleh anggota lain dalam kelompok ini.')->withInput();
            }
            
            $anggota->update([
                'nama' => $request->nama,
                'jenis_kelamin' => $request->jenis_kelamin,
                'nim_nis' => $request->nim_nis,
                'email' => $request->email,
                'telepon' => $this->formatPhoneNumber($request->telepon),
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
            ]);
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Data anggota berhasil diupdate!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error update anggota: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengupdate data: ' . $e->getMessage())->withInput();
        }
    }
    
    public function hapusAnggota($id)
    {
        try {
            DB::beginTransaction();
            
            $anggota = Anggota::findOrFail($id);
            
            if ($anggota->is_perwakilan) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Perwakilan kelompok tidak dapat dihapus. Jika ingin mengganti perwakilan, silakan edit data kelompok.');
            }
            
            $kelompokId = $anggota->kelompok_id;
            $namaAnggota = $anggota->nama;
            $anggota->delete();
            
            DB::commit();
            
            return redirect()->route('user.kelompok.tambah-peserta', $kelompokId)
                ->with('success', 'Anggota "' . $namaAnggota . '" berhasil dihapus.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error hapus anggota: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus anggota.');
        }
    }
    
    public function finalSubmitKelompok($id)
    {
        try {
            DB::beginTransaction();
            
            $kelompok = Kelompok::with('anggota')->findOrFail($id);
            
            $anggotaCount = $kelompok->anggota->count();
            
            if ($anggotaCount < $kelompok->jumlah_anggota) {
                $sisa = $kelompok->jumlah_anggota - $anggotaCount;
                DB::rollBack();
                return redirect()->back()->with('error', 'Jumlah anggota belum lengkap. Masih perlu ' . $sisa . ' anggota lagi.');
            }
            
            $kelompok->update([
                'status' => 'pending',
                'submitted_at' => now()
            ]);
            
            DB::commit();
            
            return redirect()->route('user.status')
                ->with('success', 'Pendaftaran kelompok berhasil! Kode pendaftaran Anda: ' . $kelompok->kode_pendaftaran)
                ->with('kode_pendaftaran', $kelompok->kode_pendaftaran);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error final submit kelompok: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyelesaikan pendaftaran. Silakan coba lagi.');
        }
    }

    public function indexKelompok()
    {
        $kelompoks = Kelompok::with('anggota')->orderBy('created_at', 'desc')->paginate(10);
        return view('user.kelompok.index', compact('kelompoks'));
    }
    
    public function showKelompok($id)
    {
        $kelompok = Kelompok::with('anggota')->findOrFail($id);
        return view('user.kelompok.show', compact('kelompok'));
    }
    
    public function destroyKelompok($id)
    {
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
                $anggota->delete();
            }
            
            $kelompok->delete();
            
            DB::commit();
            
            return redirect()->route('user.kelompok.index')
                ->with('success', 'Kelompok berhasil dihapus!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error destroy kelompok: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus kelompok: ' . $e->getMessage());
        }
    }
    
    // ==================== STATUS ====================
    
    public function statusPage()
    {
        return view('user.status');
    }
    
    public function cekStatus(Request $request)
    {
        $request->validate([
            'kode_pendaftaran' => 'required|string'
        ]);
        
        $kode = $request->kode_pendaftaran;
        
        // Cek di tabel pendaftaran individu
        $individu = Pendaftaran::where('kode_pendaftaran', $kode)->first();
        if ($individu) {
            return view('user.status-detail', [
                'data' => $individu,
                'type' => 'individu',
                'kode' => $kode
            ]);
        }
        
        // Cek di tabel kelompok
        $kelompok = Kelompok::with('anggota')->where('kode_pendaftaran', $kode)->first();
        if ($kelompok) {
            return view('user.status-detail', [
                'data' => $kelompok,
                'type' => 'kelompok',
                'kode' => $kode
            ]);
        }
        
        return redirect()->back()->with('error', 'Kode pendaftaran tidak ditemukan.');
    }
    
    // ==================== PAGES ====================
    
    public function pilihTipe()
    {
        return view('user.pilih-tipe');
    }
    
    public function syaratKetentuan()
    {
        return view('user.syarat-ketentuan');
    }
}