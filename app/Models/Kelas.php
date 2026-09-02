<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    // protected $primaryKey = 'id_kelas';

    // public function getRouteKeyName()
    // {
    //     return 'id_kelas';
    // }

    protected $primaryKey = 'id_kelas';

    protected $fillable = [
        'tingkat',
        'nama_kelas'
    ];

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_kelas', 'id_kelas');
    }

    public function transaksi()
    {
        return $this->hasMany(Pembayaran::class, 'id_kelas', 'id_kelas');
    }

    // tambahan

    public function jenisPembayaran()
    {
        return $this->belongsToMany(JenisPembayaran::class, 'kelas_jenis_pembayaran', 'id_kelas', 'id_jenis_pembayaran');
    }
}
