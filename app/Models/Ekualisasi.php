<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \App\Models\Client;
use \App\Models\Ekualisasidetail;
use \App\Models\Ekualisasigroup;
use \App\Models\Masapajak;

class Ekualisasi extends Model
{
    protected $table = 'pemeriksaan';
    protected $fillable = [
        'client_id',
        'masa_pajak_id',
        'tanggal_masa_pajak',
        'diperiksa_oleh',
        'mengetahui',
        'status',
        // Add other attributes as needed
    ];

    public function clients()
    {
        return $this->hasMany(Client::class, 'client_id');
    }

    public function ekualisasiDetails()
    {
        return $this->hasMany(Ekualisasidetail::class, 'pemeriksaan_id');
    }

    public function ekualisasiitems()
    {
        return $this->hasMany(Ekualisasiitem::class, 'item_pemeriksaan_id');
    }

    public function clientDataSummary()
    {
        return $this->belongsTo(Ekualisasigroup::class, 'client_id', 'client_id');
    }

    public function masaPajak()
    {
        return $this->belongsTo(Masapajak::class, 'masa_pajak_id');
    }

    public static function getPemeriksaanByClientAndYear($clientId, $year)
    {
        return self::where('client_id', $clientId)
            ->whereYear('tanggal_masa_pajak', $year)
            ->get();
    }
}