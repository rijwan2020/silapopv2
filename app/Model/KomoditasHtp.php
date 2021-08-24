<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class KomoditasHtp extends Model
{
    protected $table = 'komoditas_htp';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'insert_by', 'id');
    }
}
