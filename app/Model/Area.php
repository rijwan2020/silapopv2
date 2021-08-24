<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Awobaz\Compoships\Compoships;

class Area extends Model
{
    use Compoships;
    protected $table = 'area';
    protected $guarded = ['id'];
    public $timestamps = false;
}
