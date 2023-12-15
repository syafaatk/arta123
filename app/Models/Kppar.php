<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kppar extends Model
{
    use SoftDeletes;

    protected $table = 'Kppar';

    public function kpp()
    {
        return $this->belongsTo(Kpp::class, 'kpp_id');
    }    
}
