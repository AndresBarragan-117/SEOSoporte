<?php

namespace App\Model\Soporte;

use Illuminate\Database\Eloquent\Model;

class Votacion extends Model
{
    protected $table = 'Soporte.Votacion';
    protected $primaryKey = 'idVotacion';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'idVotacion','idUsuario', 'idKBArticulo', 'cantidadVotos'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}