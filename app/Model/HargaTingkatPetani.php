<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class HargaTingkatPetani extends Model
{
    protected $table = 'harga_tingkat_petani';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'insert_by', 'id');
    }
}
