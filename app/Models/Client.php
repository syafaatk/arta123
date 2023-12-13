<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \App\Models\Kpp;

class Client extends Model
{
    use SoftDeletes;

    protected $table = 'client_master';
        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'status',
    ];

    public function NamaKantorPajakPratama()
    {
        return $this->belongsTo(Kpp::class, 'lokasi_kpp');
    }

}
