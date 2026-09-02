<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'id_kelas',
        'id_tarif',
        'id_siswa',
        'id_jenis_pembayaran',
        'id_thn_ajaran',
        'invoice',
        'total_bayar',
        'status',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function tarif()
    {
        return $this->belongsTo(Tarif::class, 'id_tarif', 'id_tarif');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function jenisPembayaran()
    {
        return $this->belongsTo(JenisPembayaran::class, 'id_jenis_pembayaran', 'id_jenis_pembayaran');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_thn_ajaran', 'id_thn_ajaran');
    }

    public function detailPembayaran()
    {
        return $this->hasMany(DetailPembayaran::class, 'id_transaksi', 'id_transaksi');
    }

    // Accessor for jumlah_bayar
    public function getJumlahBayarAttribute()
    {
        return $this->detailPembayaran()
            ->where('status_transaksi', 'Sukses')
            ->sum('jumlah_transaksi');
    }

    public function getIsLunasAttribute()
    {
        // Define the semesters with string values
        $semesters = [
            'Ganjil' => ['Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            'Genap' => ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'],
        ];
    
        // Get the current semester's months based on the associated TahunAjaran
        $currentSemesterMonths = $semesters[$this->tahunAjaran->semester];
    
        // Get the months that have been successfully paid
        $paidMonths = $this->detailPembayaran()
            ->where('status_transaksi', 'Sukses')
            ->pluck('bulan')
            ->toArray();
    
        // Check if all months in the current semester are paid
        return empty(array_diff($currentSemesterMonths, $paidMonths));
    }
    
}
