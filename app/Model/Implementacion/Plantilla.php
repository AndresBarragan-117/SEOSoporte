<?php

namespace App\Model\Implementacion;

use Illuminate\Database\Eloquent\Model;

class Plantilla extends Model
{
    protected $table = 'Implementacion.Plantilla';
     protected $primaryKey = 'idPlantilla';

     protected $fillable = [
        'idPlantilla','nombre','descripcion','estado','idUsuarioCreacion','idUsuarioModificacion','idTipoPlantilla'
    ];
    
  /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
