<?php

namespace App\Model\Seguridad;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{

    protected $table = 'Seguridad.Rol';
    protected $primaryKey = 'idRol';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idRol', 'nombre', 'descripcion', 'estado',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
