<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class BakulahanDetail extends Model
{
    protected $table = 'baku_lahan_detail';

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
}
