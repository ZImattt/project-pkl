<?php

namespace App\Http\Controllers;

use App\Models\PklRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class StudentController extends Controller
{
    /**
     * Show landing/home page
     */
    public function home()
    {
        return view('student.home');
    }

    /**
     * Show registration form
     */
    public function showRegistrationForm()
    {
        return view('student.register');
    }

    /**
     * Store registration data
     */
    public function storeRegistration(Request $request)
    {
        // Validasi utama
        $validator = Validator::make($request->all(), [
            // Data Pribadi
            'nama_lengkap' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date|before_or_equal:-15 years',
            'alamat_lengkap' => 'required|string',
            'email' => 'required|email|max:100',
            'no_whatsapp' => 'required|string|max:15|regex:/^[0-9]+$/',
            
            // Data Pendidikan
            'jenis_pendidikan' => 'required|in:smk,universitas',
            
            // Data SMK (jika dipilih)
            'nis' => 'required_if:jenis_pendidikan,smk|nullable|string|max:50',
            'kelas' => 'required_if:jenis_pendidikan,smk|nullable|string|max:10',
            'sekolah' => 'required_if:jenis_pendidikan,smk|nullable|string|max:100',
            'jurusan_smk' => 'required_if:jenis_pendidikan,smk|nullable|string|max:100',
            'guru_pembimbing' => 'required_if:jenis_pendidikan,smk|nullable|string|max:100',
            'no_hp_guru' => 'required_if:jenis_pendidikan,smk|nullable|string|max:15',
            
            // Data Universitas (jika dipilih)
            'nim' => 'required_if:jenis_pendidikan,universitas|nullable|string|max:50',
            'semester' => 'required_if:jenis_pendidikan,universitas|nullable|string|max:10',
            'universitas' => 'required_if:jenis_pendidikan,universitas|nullable|string|max:100',
            'jurusan_univ' => 'required_if:jenis_pendidikan,universitas|nullable|string|max:100',
            'dosen_pembimbing' => 'required_if:jenis_pendidikan,universitas|nullable|string|max:100',
            'no_hp_dosen' => 'required_if:jenis_pendidikan,universitas|nullable|string|max:15',
            
            // Motivasi
            'alasan_pkl_gi' => 'required|string',
            'skill_ingin_dipelajari' => 'required|string',
            'harapan_setelah_pkl' => 'required|string',
            'pengalaman_sebelumnya' => 'nullable|string',
            
            // Periode PKL
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            
            // Dokumen
            'upload_surat_pengantar' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            
            // Terms
            'terms' => 'required|accepted',
        ], [
            'tanggal_lahir.before_or_equal' => 'Minimal usia 15 tahun untuk mendaftar PKL',
            'no_whatsapp.regex' => 'Nomor WhatsApp hanya boleh berisi angka',
            'upload_surat_pengantar.mimes' => 'Format file harus PDF, JPG, JPEG, atau PNG',
            'upload_surat_pengantar.max' => 'Ukuran file maksimal 2MB',
            'terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan',
            'tanggal_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Handle file upload
            if ($request->hasFile('upload_surat_pengantar')) {
                $file = $request->file('upload_surat_pengantar');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $suratFileName = time() . '_surat_' . Str::slug($originalName) . '.' . $extension;
                
                // Simpan ke public folder
                $uploadPath = public_path('uploads/surat_pengantar');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $file->move($uploadPath, $suratFileName);
            } else {
                $suratFileName = null;
            }

            // Generate registration ID
            $registrationId = 'PKL-' . date('Ymd') . '-' . Str::random(6);

            // Prepare data
            $data = [
                // Data Pribadi
                'registration_id' => $registrationId,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat_lengkap' => $request->alamat_lengkap,
                'email' => $request->email,
                'no_whatsapp' => $request->no_whatsapp,
                
                // Data Pendidikan
                'jenis_pendidikan' => $request->jenis_pendidikan,
                
                // Motivasi
                'alasan_pkl_gi' => $request->alasan_pkl_gi,
                'skill_ingin_dipelajari' => $request->skill_ingin_dipelajari,
                'harapan_setelah_pkl' => $request->harapan_setelah_pkl,
                'pengalaman_sebelumnya' => $request->pengalaman_sebelumnya,
                
                // Periode PKL
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                
                // Dokumen
                'upload_surat_pengantar' => $suratFileName,
                
                // Status
                'status' => 'pending',
            ];

            // Add education data based on type
            if ($request->jenis_pendidikan == 'smk') {
                $data['nis'] = $request->nis;
                $data['kelas'] = $request->kelas;
                $data['sekolah'] = $request->sekolah;
                $data['jurusan_smk'] = $request->jurusan_smk;
                $data['guru_pembimbing'] = $request->guru_pembimbing;
                $data['no_hp_guru'] = $request->no_hp_guru;
            } else {
                $data['nim'] = $request->nim;
                $data['semester'] = $request->semester;
                $data['universitas'] = $request->universitas;
                $data['jurusan_univ'] = $request->jurusan_univ;
                $data['dosen_pembimbing'] = $request->dosen_pembimbing;
                $data['no_hp_dosen'] = $request->no_hp_dosen;
            }

            // Create registration
            $registration = PklRegistration::create($data);

            // Redirect ke status page dengan data
            return redirect()->route('student.status')
                ->with([
                    'success' => 'Pendaftaran berhasil dikirim! ID Pendaftaran Anda: ' . $registrationId,
                    'registration_id' => $registrationId,
                    'whatsapp' => $request->no_whatsapp,
                    'auto_search' => true // Flag untuk auto search
                ]);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show status check form
     */
    public function showStatusForm()
    {
        // Check if there's auto_search flag from registration
        $autoSearch = session('auto_search', false);
        $whatsapp = session('whatsapp', '');
        $registrationId = session('registration_id', '');
        
        return view('student.status', compact('autoSearch', 'whatsapp', 'registrationId'));
    }

    /**
     * Check registration status - FIXED VERSION
     */
    public function checkStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'whatsapp' => 'required|string|regex:/^[0-9]+$/',
        ], [
            'whatsapp.regex' => 'Nomor WhatsApp hanya boleh berisi angka',
        ]);

        if ($validator->fails()) {
            return redirect()->route('student.status')
                ->withErrors($validator)
                ->withInput();
        }

        // Clean WhatsApp number (remove +62, 62, or 0)
        $whatsapp = $request->whatsapp;
        $whatsapp = ltrim($whatsapp, '+');
        if (str_starts_with($whatsapp, '62')) {
            $whatsapp = substr($whatsapp, 2);
        }
        $whatsapp = ltrim($whatsapp, '0');

        // Search registrations
        $registration = PklRegistration::where('no_whatsapp', $whatsapp)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$registration) {
            return redirect()->route('student.status')
                ->with('error', 'Tidak ditemukan pendaftaran dengan nomor WhatsApp: ' . $request->whatsapp)
                ->with('not_found', true)
                ->with('search_number', $request->whatsapp)
                ->withInput();
        }

        // Return single registration to view
        return view('student.status', compact('registration'));
    }

    /**
     * Show registration detail
     */
    public function showRegistrationDetail($id)
    {
        // Find by ID or registration_id
        $registration = PklRegistration::where('id', $id)
            ->orWhere('registration_id', $id)
            ->first();

        if (!$registration) {
            return redirect()->route('student.status')
                ->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        return view('student.registration-detail', compact('registration'));
    }
}