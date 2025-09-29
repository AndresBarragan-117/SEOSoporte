<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    protected $table = 'Soporte.Funcionario';
    protected $primaryKey = 'idFuncionario';
   /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
       'idFuncionario','idUser', 'nombre', 'email', 'pwd', 'firma', 'estado'
   ];

   /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
   protected $hidden = [];
}
