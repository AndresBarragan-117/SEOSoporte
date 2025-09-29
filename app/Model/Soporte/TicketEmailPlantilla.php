<?php

namespace App\Model\Soporte;

use Illuminate\Database\Eloquent\Model;

class TicketEmailPlantilla extends Model
{
    protected $table = 'Soporte.TicketEmailPlantilla';
    protected $primaryKey = 'idTicketEmailPlantilla';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
        'idTicketEmailPlantilla','idTicketEstado','asunto', 'contenido','idUsuarioCreacion','idUsuarioModificacion'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
