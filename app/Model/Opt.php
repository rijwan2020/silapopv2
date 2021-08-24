<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Opt extends Model
{
    protected $table = 'opt';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'insert_by', 'id');
    }
}
