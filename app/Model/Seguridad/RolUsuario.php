<?php

namespace App\Model\Seguridad;

use Illuminate\Database\Eloquent\Model;

class RolUsuario extends Model
{
    protected $table = 'Seguridad.RolUsuario';
    protected $primaryKey = 'idRolUsuario';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idRolUsuario', 'idRol', 'idUsuario',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
