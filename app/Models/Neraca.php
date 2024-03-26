<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \App\Models\Neracadetail;

class Neraca extends Model
{
    protected $fillable = [
        'id',
        'keterangan',
        'client_id',
        'tahun',
        'judul_parent',
    ];

    public function neracadetails()
    {
        return $this->hasMany(Neracadetail::class,'neraca_id');
    }

    public function getPreviousYearNeracadetails($nid)
    {
        // Assuming $this->neraca_id represents the current year
        $previousYearNeracadetails = Neracadetail::where('neraca_id', $nid - 1)->get();
        return $previousYearNeracadetails;
    }

}