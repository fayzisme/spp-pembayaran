<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    use HasFactory;

    protected $table = 'tarif';

    protected $fillable = [
        'id_jenis_pembayaran',
        'id_kelas',
        'tarif',
    ];

     /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_tarif';

    public function jenisPembayaran()
    {
        return $this->belongsTo(JenisPembayaran::class, 'id_jenis_pembayaran', 'id_jenis_pembayaran');
    }

    public function kelas()
    {
        // return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function transaksi()
    {
        return $this->hasMany(Pembayaran::class, 'id_tarif', 'id');
    }
}
