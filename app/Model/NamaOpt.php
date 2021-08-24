<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class NamaOpt extends Model
{
    protected $table = 'nama_opt';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'insert_by', 'id');
    }
}
