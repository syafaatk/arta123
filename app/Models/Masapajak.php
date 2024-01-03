<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \App\Models\Ekualisasidetail;
use \App\Models\Masapajak;

class Masapajak extends Model
{
    protected $table = 'masa_pajak';

    public function ekualisasis()
    {
        return $this->hasMany(Ekualisasi::class, 'masa_pajak_id');
    }

}
