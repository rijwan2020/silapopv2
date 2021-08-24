<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
