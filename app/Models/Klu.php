<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Klu extends Model
{
    use SoftDeletes;

    protected $table = 'klu_masters';
}
