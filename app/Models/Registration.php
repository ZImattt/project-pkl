<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lengkap',
        'sekolah',
        'jurusan',
        'no_whatsapp',
        'email',
        'jenjang',
        'tanggal_mulai',
        'tanggal_selesai',
        'upload_surat_pengantar',
        'upload_cv',
        'upload_other',
        'status',
        'admin_notes',
        'interview_date',
        'interview_time',
        'status_updated_at',
        'updated_by',
    ];

    protected $dates = [
        'tanggal_mulai',
        'tanggal_selesai',
        'interview_date',
        'status_updated_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the status badge class
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'interview' => 'info',
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    /**
     * Get the status text
     */
    public function getStatusTextAttribute()
    {
        $texts = [
            'pending' => 'Pending',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'interview' => 'Wawancara',
        ];

        return $texts[$this->status] ?? 'Unknown';
    }

    /**
     * Scope for pending registrations
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved registrations
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for today's registrations
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope for this week's registrations
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    /**
     * Scope for this month's registrations
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }
}