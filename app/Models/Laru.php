<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \App\Models\Larudetail;

class Laru extends Model
{

    public function larudetails()
    {
        return $this->hasMany(Larudetail::class,'laru_id');
    }
}