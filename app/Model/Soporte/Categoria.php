<?php

namespace App\Model\Soporte;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'Soporte.Categoria';
    protected $primaryKey = 'idCategoria';
   /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
       'idCategoria','codigo', 'nombre', 'estado',
   ];

   /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
   protected $hidden = [];
}
