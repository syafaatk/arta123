<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \App\Models\Ekualisasidetail;

class Ekualisasiitem extends Model
{
    protected $table = 'item_pemeriksaan';

    public function detailekualiasis()
    {
        return $this->hasMany(Ekualisasidetail::class, 'item_pemeriksaan_id');
    }

}
