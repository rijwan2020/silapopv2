<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    protected $table = 'user_session';

    protected $guarded = ['id'];

    public $timestamps = false;
}
