<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \App\Models\Kpp;
use \App\Models\Klu;
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

    public function kpp()
    {
        return $this->belongsTo(Kpp::class, 'lokasi_kpp');
    }    

    public function klu()
    {
        return $this->belongsTo(Klu::class, 'klu_id');
    }

}
