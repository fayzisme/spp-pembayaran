<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembayaran extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksi';

    protected $primaryKey = 'id_detail_transaksi';

    protected $fillable = [
        'id_transaksi',
        'id_petugas',
        'id_tarif',
        'id_siswa',
        'id_jenis_pembayaran',
        'id_thn_ajaran',
        'id_namabayar',
        'status_transaksi',
        'jumlah_transaksi',
        'tgl_transaksi',
        'bulan',
        'snap_token',
        'metode_transaksi', // Tambahkan field metode_transaksi ke fillable
    ];

    public function setSuccess()
    {
        $this->status_transaksi = 'Sukses';
        $this->save();
    }

    public function setPending()
    {
        $this->status_transaksi = 'Pending';
        $this->save();
    }

    public function setFailed()
    {
        $this->status_transaksi = 'Gagal';
        $this->save();
    }

    public function transaksi()
    {
        return $this->belongsTo(Pembayaran::class, 'id_transaksi', 'id_transaksi');
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
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas','id_petugas');
    }
}
