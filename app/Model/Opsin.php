<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Opsin extends Model
{
    protected $table = 'opsin';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'insert_by', 'id');
    }
    
    public function jenis()
    {
        return $this->belongsTo(JenisAlsin::class, 'jenis_id', 'id');
    }
    
    public function penyuluh()
    {
        return $this->belongsTo(Penyuluh::class, 'penyuluh_id', 'id');
    }
}
