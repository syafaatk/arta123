<?php
// app/Models/Ekualisasitahunan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ekualisasitahunandetail extends Model
{
    protected $table = 'pemeriksaan_tahunan';
    protected $guarded = [];
    public $timestamps = false;

    public function item_ekualisasi()
    {
        return $this->belongsTo(Ekualisasiitem::class, 'item_pemeriksaan_id');
    }
}