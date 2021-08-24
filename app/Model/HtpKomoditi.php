<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class HtpKomoditi extends Model
{
    protected $table = 'htp_komoditi';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'insert_by', 'id');
    }
}
