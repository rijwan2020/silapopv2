<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Awobaz\Compoships\Compoships;

class Penyuluh extends Model
{
    use Compoships;
    protected $table = 'penyuluh';
    // protected $appends = ['nama_kota'];
    protected $guarded = ['id'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function wilayahkerja()
    {
        return $this->hasMany(WilayahKerja::class, 'penyuluh_id', 'id');
    }

    public function kota()
    {
        return $this->hasOne(Area::class, ['provinsi_id', 'kota_id'], ['provinsi_id', 'kota_id']);
    }

    public function kecamatan()
    {
        return $this->hasOne(Area::class, ['provinsi_id', 'kota_id', 'kecamatan_id'], ['provinsi_id', 'kota_id', 'kecamatan_id']);
    }

    public function desa()
    {
        return $this->hasOne(Area::class, ['provinsi_id', 'kota_id', 'kecamatan_id', 'kelurahan_id'], ['provinsi_id', 'kota_id', 'kecamatan_id', 'kelurahan_id']);
    }
}
