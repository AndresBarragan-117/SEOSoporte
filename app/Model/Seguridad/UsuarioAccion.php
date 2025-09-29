<?php

namespace App\Model\Seguridad;

use Illuminate\Database\Eloquent\Model;

class UsuarioAccion extends Model
{
    protected $table = 'Seguridad.UsuarioAccion';
    protected $primaryKey = 'idUsuarioAccion';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idUsuarioAccion', 'idUsuario', 'accion', 'idUsuarioCreacion', 'idUsuarioModificacion'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
