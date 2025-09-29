<?php

namespace App\Model\Implementacion;

use Illuminate\Database\Eloquent\Model;

class TipoCaptura extends Model
{
    protected $table = 'Implementacion.TipoCaptura';
    protected $primaryKey = 'idTipoCaptura';

    protected $fillable = [
       'idTipoCaptura','nombre','descripcion','estado','idUsuarioCreacion','idUsuarioModificacion',
   ];
   
}
