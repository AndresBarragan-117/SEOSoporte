<?php

namespace App\Model\Implementacion;

use Illuminate\Database\Eloquent\Model;

class ImplementacionEmpresa extends Model
{
    protected $table = 'Implementacion.ImplementacionEmpresa';
     protected $primaryKey = 'idImplementacionEmpresa';

     protected $fillable = [
        'idImplementacionEmpresa','idEmpresa','idPlantilla','idPlantillaLista','valor','fechaRealiza','observacion','idUsuarioCreacion','idUsuarioModificacion',
    ];
    
        
}
