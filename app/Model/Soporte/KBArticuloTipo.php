<?php

namespace App\Model\Soporte;

use Illuminate\Database\Eloquent\Model;

class KBArticuloTipo extends Model
{
    protected $table = 'Soporte.KBArticuloTipo'; // Nombre exacto de la tabla

    protected $primaryKey = 'idKBArticuloTipo'; // Llave primaria

    public $timestamps = true; // Si tu tabla tiene created_at / updated_at

    protected $fillable = [
        'idKBArticuloTipo', 'nombre', 'idUsuarioCreacion', 'idUsuarioModificacion'
    ];
}
