<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class JenisAlsin extends Model
{
    protected $table = 'jenis_alsin';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'insert_by', 'id');
    }
}
