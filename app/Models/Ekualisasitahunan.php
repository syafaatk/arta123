<?php
// app/Models/Ekualisasitahunan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \App\Models\Ekualisasitahunandetail;

class Ekualisasitahunan extends Model
{
    protected $table = 'tahunan';
    protected $guarded = [];
    public $timestamps = false;

    public function ekualisasiDetails()
    {
        return $this->hasMany(Ekualisasitahunandetail::class, 'id');
    }

}