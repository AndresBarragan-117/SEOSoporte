<?php

namespace App\Model\Soporte;

use Illuminate\Database\Eloquent\Model;

class MEmpresaClienteUsuario extends Model
{
    protected $table = 'Soporte.EmpresaClienteUsuario';
    protected $primaryKey = 'idEmpresaClienteUsuario';
   /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
       'idEmpresaClienteUsuario','nombre', 'email', 'pwd', 'idEmpresaCliente'
   ];

   /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
   protected $hidden = [];
}
