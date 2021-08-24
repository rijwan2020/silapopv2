<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LuasTambahTanam extends Model
{
    protected $table = 'luas_tambah_tanam';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'insert_by', 'id');
    }
}
