<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'thn_ajaran';

    protected $primaryKey = 'id_thn_ajaran';

    // public function getRouteKeyName()
    // {
    //     return 'id_thn_ajaran';
    // }

    protected $fillable = [
        'thn_ajaran',
        'semester'
    ];

    public function transaksi()
    {
        return $this->hasMany(Pembayaran::class, 'id_thn_ajaran', 'id');
    }
}
