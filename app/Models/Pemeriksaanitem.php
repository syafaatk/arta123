<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \App\Models\Pemeriksaandetail;

class Pemeriksaanitem extends Model
{
    protected $table = 'item_pemeriksaan';

    public function detailpemeriksaans()
    {
        return $this->hasMany(Pemeriksaandetail::class, 'item_pemeriksaan_id');
    }

}
