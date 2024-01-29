<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \App\Models\Laru;

class Larudetail extends Model
{
    protected $fillable = [
        'parent_id',
        'item_no',
        'item_name',
        'final',
        'non_final',
        'total',
        'tax',
    ];

    public function laru()
    {
        return $this->belongsTo(Laru::class,'laru_id');
    }
}