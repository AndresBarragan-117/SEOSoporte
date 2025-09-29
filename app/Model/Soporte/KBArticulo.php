<?php

namespace App\Model\Soporte;

use Illuminate\Database\Eloquent\Model;

class KBArticulo extends Model
{
    protected $table = 'Soporte.KBArticulo';
    protected $primaryKey = 'idKBArticulo';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idKBArticulo', 'idKBArticuloCategoria', 'asunto', 'contenido', 'tipo','cantidadVistos','cantidadVotos','cantidadVotacion', 'idUsuarioCreacion', 'idUsuarioModificacion',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
