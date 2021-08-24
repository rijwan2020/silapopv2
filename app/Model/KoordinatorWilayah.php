<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class KoordinatorWilayah extends Model
{
    protected $table = 'koordinator_wilayah';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
