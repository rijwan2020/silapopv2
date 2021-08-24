<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Htp extends Model
{
    protected $table = 'htp';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'insert_by', 'id');
    }

    public function penyuluh()
    {
        return $this->belongsTo(Penyuluh::class, 'penyuluh_id', 'id');
    }
    
    public function komoditas()
    {
        return $this->belongsTo(KomoditasHtp::class, 'komoditi_id', 'id');
    }
}
