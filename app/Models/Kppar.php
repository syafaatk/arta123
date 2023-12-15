<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \App\Models\Kpp;

class Kppar extends Model
{
    use SoftDeletes;

    protected $table = 'Kppar';

    public function masterKpp()
    {
        return $this->belongsTo(Kpp::class, 'kpp_id');
    }

    public function clients()
    {
        return $this->hasMany(Client::class, 'kppar_id');
    }
}
