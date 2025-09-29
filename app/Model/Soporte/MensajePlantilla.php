<?php

namespace App\Model\Soporte;

use Illuminate\Database\Eloquent\Model;

class MensajePlantilla extends Model
{
    protected $table = 'Soporte.MensajePlantilla';
    protected $primaryKey = 'idMensajePlantilla';
   /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
       'idMensajePlantilla','idCategoria', 'pregunta', 'respuesta','idUsuarioCreacion','idUsuarioModificacion'
   ];

   /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
   protected $hidden = [];
}
