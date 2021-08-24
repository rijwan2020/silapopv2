<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WilayahKerja extends Model
{
    protected $table = 'wilayah_kerja';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function penyuluh()
    {
        return $this->belongsTo(Penyuluh::class, 'penyuluh_id', 'id');
    }
}
