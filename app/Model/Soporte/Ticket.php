<?php

namespace App\Model\Soporte;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'Soporte.Ticket';
    protected $primaryKey = 'idTicket';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
        'idTicket','guid','idTicketPrioridad','idTicketEstado', 'idFuncionario','idEmpresaClienteUsuario','idCategoria','asunto','fechaLeido','usuarioClienteRating','usuarioClienteComentario','ultimoReplique','idUsuarioCreacion','idUsuarioModificacion'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
