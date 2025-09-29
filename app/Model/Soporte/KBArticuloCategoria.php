<?php

namespace App\Model\Soporte;

use Illuminate\Database\Eloquent\Model;

class KBArticuloCategoria extends Model
{
    protected $table = 'Soporte.KBArticuloCategoria';
    protected $primaryKey = 'idKBArticuloCategoria';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idKBArticuloCategoria', 'nombre', 'padreId', 'nombreEtiqueta', 'orden', 'idUsuarioCreacion', 'idUsuarioModificacion',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
