<?php

namespace App\Model\Seguridad;

use Illuminate\Database\Eloquent\Model;

class Carpeta extends Model
{
   
     protected $table = 'Seguridad.Carpeta';
     protected $primaryKey = 'idCarpeta';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idCapeta','idPadre', 'descripcion',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
