<?php

namespace App\Model\Seguridad;

use Illuminate\Database\Eloquent\Model;

class Formulario extends Model
{
    protected $table = 'Seguridad.Formulario';
     protected $primaryKey = 'idFormulario';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idCarpeta', 'nombre', 'path', 'tag', 'widget', 'estado',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
