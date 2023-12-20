<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \App\Models\Pemeriksaandetail;
use \App\Models\Masapajak;

class Masapajak extends Model
{
    protected $table = 'masa_pajak';

    public function pemeriksaans()
    {
        return $this->hasMany(Pemeriksaan::class, 'masa_pajak_id');
    }

}
