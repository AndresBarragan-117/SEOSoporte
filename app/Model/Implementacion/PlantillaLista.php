<?php

namespace App\Model\Implementacion;

use Illuminate\Database\Eloquent\Model;

class PlantillaLista extends Model
{
    protected $table = 'Implementacion.PlantillaLista';
    protected $primaryKey = 'idPlantillaLista';

    protected $fillable = [
       'idPlantillaLista','nombre','descripcion','idPlantilla','numeroOrdenLista','idTipoCaptura','opcionTipoCaptura','estado','idUsuarioCreacion','idUsuarioModificacion',
    ];
}