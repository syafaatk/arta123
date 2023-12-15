<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \App\Models\Client;
use \App\Models\Kppar;

class Kpp extends Model
{
    protected $table = 'kpp_master';

    public function kppars()
    {
        return $this->hasMany(Kppar::class, 'kpp_id');
    }

}
