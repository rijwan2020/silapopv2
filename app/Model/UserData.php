<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserData extends Model
{
    protected $table = 'user_data';

    protected $guarded = ['id'];

    public $timestamps = false;

}
