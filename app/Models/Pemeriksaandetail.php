<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \App\Models\Pemeriksaan;
use \App\Models\Pemeriksaanitem;

class Pemeriksaandetail extends Model
{
    protected $table = 'detail_pemeriksaan';
    protected $fillable = [
        'pemeriksaan_id',
        'item_pemeriksaan_id',
        'quantity',
        'jumlah',
        // Add other attributes as needed
    ];

    public function pemeriksaans()
    {
        return $this->belongsTo(Pemeriksaan::class, 'pemeriksaan_id');
    }

    public function item_pemeriksaan()
    {
        return $this->belongsTo(Pemeriksaanitem::class, 'item_pemeriksaan_id');
    }

}