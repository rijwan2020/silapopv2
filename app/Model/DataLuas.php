<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class DataLuas extends Model
{
    protected $table = 'data_luas';

    protected $guarded = ['id'];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function komoditas()
    {
        return $this->belongsTo(Komoditas::class, 'komoditi_id', 'id');
    }
    
    public function penyuluh()
    {
        return $this->belongsTo(Penyuluh::class, 'penyuluh_id', 'id');
    }
}
