<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \App\Models\Ekualisasigroup;
use \App\Models\Ekualisasi;
use \App\Models\Client;

class Ekualisasigroup extends Model
{
    protected $table = 'client_data_summary';
    protected $guarded = [];
    public $timestamps = false;

    public function pemeriksaan()
    {
        return $this->hasMany(Ekualisasi::class, 'client_id', 'client_id');
    }
    
}