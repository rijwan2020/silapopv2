<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    
    protected $table = 'user_log';

    protected $guarded = ['id'];

    public $timestamps = false;
}
