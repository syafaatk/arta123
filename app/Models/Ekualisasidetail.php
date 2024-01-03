<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \App\Models\Ekualisasi;
use \App\Models\Ekualisasiitem;

class Ekualisasidetail extends Model
{
    protected $table = 'detail_pemeriksaan';
    protected $fillable = [
        'pemeriksaan_id',
        'item_pemeriksaan_id',
        'quantity',
        'jumlah',
        // Add other attributes as needed
    ];

    public function ekualisasis()
    {
        return $this->belongsTo(Ekualisasi::class, 'pemeriksaan_id');
    }

    public function item_ekualisasi()
    {
        return $this->belongsTo(Ekualisasiitem::class, 'item_pemeriksaan_id');
    }

}