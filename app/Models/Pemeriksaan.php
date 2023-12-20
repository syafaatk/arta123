<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \App\Models\Client;
use \App\Models\Pemeriksaandetail;

class Pemeriksaan extends Model
{
    protected $table = 'pemeriksaan';
    protected $fillable = [
        'client_id',
        'masa_pajak_id',
        'tanggal_masa_pajak',
        'diperiksa_oleh',
        'mengetahui',
        // Add other attributes as needed
    ];

    public function clients()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function pemeriksaanDetails()
    {
        return $this->hasMany(Pemeriksaandetail::class, 'pemeriksaan_id');
    }

    public function pemeriksaanitems()
    {
        return $this->hasMany(Pemeriksaanitem::class, 'item_pemeriksaan_id');
    }
}