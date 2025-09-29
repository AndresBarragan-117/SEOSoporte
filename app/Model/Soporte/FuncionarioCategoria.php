<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FuncionarioCategoria extends Model
{
    protected $table = 'Soporte.FuncionarioCategoria';
    protected $primaryKey = 'idFuncionarioCategoria';
   /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
       'idFuncionarioCategoria','idCategoria', 'idFuncionario'
   ];

   /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
   protected $hidden = [];
}
