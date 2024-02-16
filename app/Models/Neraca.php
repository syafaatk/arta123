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
}