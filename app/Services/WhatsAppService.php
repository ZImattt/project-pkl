<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $token;
    protected $endpoint;

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
        $this->endpoint = config('services.fonnte.endpoint');
    }

    public function kirim(string $nomor, string $pesan): bool
    {
        try {
            $nomorTujuan = $this->formatUntukFonnte($nomor);

            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])
            ->withOptions(['verify' => false])
            ->post($this->endpoint, [
                'target' => $nomorTujuan,
                'message' => $pesan,
            ]);

            $body = $response->json();

            if ($response->successful() && isset($body['status']) && $body['status'] === true) {
                Log::info('WhatsApp terkirim', ['nomor' => $nomorTujuan]);
                return true;
            }

            Log::error('WhatsApp gagal', ['nomor' => $nomorTujuan, 'response' => $body]);
            return false;

        } catch (\Exception $e) {
            Log::error('WhatsApp error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    private function formatUntukFonnte(string $nomor): string
    {
        $nomor = preg_replace('/[^0-9+]/', '', $nomor);
        $nomor = ltrim($nomor, '+');
        if (substr($nomor, 0, 1) === '0') $nomor = '62' . substr($nomor, 1);
        elseif (substr($nomor, 0, 1) === '8') $nomor = '62' . $nomor;
        return $nomor;
    }

    public function buatPesanPendaftaranIndividu(string $nama, string $kode): string
    {
        return "*PENDAFTARAN PKL/MAGANG*\n\n"
            . "Halo *{$nama}*,\n\n"
            . "Pendaftaran kamu sudah kami terima.\n\n"
            . "Kode Pendaftaran: *{$kode}*\n\n"
            . "Simpan kode ini untuk mengecek status pendaftaran.\n\n"
            . "_Global Intermedia Nusantara_";
    }

    public function buatPesanPendaftaranKelompok(string $nama, string $kode, string $namaKelompok): string
    {
        return "*PENDAFTARAN PKL/MAGANG KELOMPOK*\n\n"
            . "Halo *{$nama}*,\n\n"
            . "Pendaftaran kelompok *{$namaKelompok}* sudah kami terima.\n\n"
            . "Kode Pendaftaran: *{$kode}*\n\n"
            . "Simpan kode ini untuk mengecek status pendaftaran.\n\n"
            . "_Global Intermedia Nusantara_";
    }

    public function buatPesanStatusDiterima(string $nama, string $kode, string $catatan = ''): string
    {
        $pesan = "*UPDATE STATUS PENDAFTARAN*\n\n"
            . "Halo *{$nama}*,\n\n"
            . "Pendaftaran kamu *DITERIMA*.\n\n"
            . "Kode: *{$kode}*\n\n"
            . "Silakan cek informasi selanjutnya melalui kode di atas.\n\n"
            . "Terima kasih.\n\n"
            . "_Global Intermedia Nusantara_";

        if ($catatan) {
            $pesan .= "\n\n*Catatan:*\n{$catatan}";
        }

        return $pesan;
    }

    public function buatPesanStatusTidakDiterima(string $nama, string $kode, string $catatan = ''): string
    {
        $pesan = "*UPDATE STATUS PENDAFTARAN*\n\n"
            . "Halo *{$nama}*,\n\n"
            . "Pendaftaran kamu *TIDAK DITERIMA*.\n\n"
            . "Kode: *{$kode}*\n\n"
            . "Terima kasih sudah mendaftar.\n\n"
            . "_Global Intermedia Nusantara_";

        if ($catatan) {
            $pesan .= "\n\n*Alasan:*\n{$catatan}";
        }

        return $pesan;
    }
}