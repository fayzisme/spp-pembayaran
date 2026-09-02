<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPembayaran extends Model
{
    use HasFactory;

    protected $table = 'jenis_pembayaran';

    protected $fillable = [
        'id_thn_ajaran',
        'tipe_bayar',
        'nama_pembayaran'
    ];

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_jenis_pembayaran';

    public function tahunAjaran()
    {
        // return $this->belongsTo(TahunAjaran::class, 'id_thnajaran', 'id_thn_ajaran');
        return $this->belongsTo(TahunAjaran::class, 'id_thn_ajaran', 'id_thn_ajaran');
    }

    public function transaksi()
    {
        return $this->hasMany(Pembayaran::class, 'id_jenis_pembayaran', 'id_jenis_pembayaran');
    }

    public function tarif()
    {
        return $this->hasMany(Tarif::class, 'id_jenis_pembayaran', 'id_jenis_pembayaran');
    }


}
