<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LuasPanen extends Model
{
    protected $table = 'luas_panen';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'insert_by', 'id');
    }
}
