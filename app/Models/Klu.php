<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \App\Models\Client;

class Klu extends Model
{
    use SoftDeletes;

    protected $table = 'klu_masters';

    public function getFullNameAttribute()
    {
        return $this->id . ' - ' . $this->name_klu;
    }

    public function clients()
    {
        return $this->hasMany(Client::class, 'klu_id');
    }
    
}
