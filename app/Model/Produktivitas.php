<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Produktivitas extends Model
{
    protected $table = 'produktivitas';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'insert_by', 'id');
    }
}
