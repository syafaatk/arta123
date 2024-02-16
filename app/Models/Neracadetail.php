<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \App\Models\Neraca;

class Neracadetail extends Model
{

    protected $fillable = [
        'neraca_id',
        'parent_id',
        'item_no',
        'item_name',
        'total',
        'column_order',
    ];

    public function neraca()
    {
        return $this->belongsTo(Neraca::class,'neraca_id');
    }
}