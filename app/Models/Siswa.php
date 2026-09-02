<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\LulusScope;

class Siswa extends Model
{
    use HasFactory;

    protected $table ='siswas';

    protected $primaryKey = 'id_siswa';

    protected $fillable= [
        'id_user',
        'id_kelas',
        // 'id_tahun_ajaran',
        'id_thn_ajaran',
        'nis',
        'nama',
        'tempat_lahir',
        'tgl_lahir',
        'jenis_kelamin',
        'agama',
        'no_hp',
        'alamat',
        'nama_wali',
        'status'
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new LulusScope);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_thn_ajaran', 'id_thn_ajaran');
    }

    public function transaksi()
    {
        return $this->hasMany(Pembayaran::class, 'id_siswa', 'id_siswa');
    }
    // Model Siswa

    public function cek_transaksi()
    {
        return $this->hasMany(DetailPembayaran::class, 'id_siswa', 'id');
    }
}
