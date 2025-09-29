<?php

namespace App\Model\Seguridad;

use Illuminate\Database\Eloquent\Model;

class RolFormulario extends Model
{
    use Notifiable;

    protected $table = 'Seguridad.RolFormulario';
    protected $primaryKey = 'idRolFormulario';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idRol', 'idFormulario',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
