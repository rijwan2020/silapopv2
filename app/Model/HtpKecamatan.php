<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class HtpKecamatan extends Model
{
    protected $table = 'htp_kecamatan';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'insert_by', 'id');
    }
}
