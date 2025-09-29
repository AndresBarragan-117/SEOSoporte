<?php

namespace App\Http\Controllers\Soporte;

use App\Http\Controllers\CustomController;
use App\Http\Requests\Soporte\TicketValidator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use App\Model\Soporte\MEmpresaClienteUsuario;
use App\Model\Soporte\Ticket;
use App\Model\Soporte\TicketMensaje;
use Exception;
use Faker\Guesser\Name;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Entorno;
use Psy\Command\WhereamiCommand;

class TicketController extends CustomController
{
    protected $tag = '107';

    public function __construct()
    {
        parent::__construct($this->tag);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = DB::table('Soporte.Categoria as c')
        ->get();
        return $this->views("soporte.ticket.ticket", [
                "categoria" => $categorias
        ]);
    }

    public function store(TicketValidator $request)
    {
        $validator = Validator::make(
                    $request->all(), 
                    $request->rules(),
                    $request->messages()
        );

        if($validator->validate())
        {
            try {
                DB::beginTransaction();
                $parametroDefecto = DB::table('Soporte.ParametroDefecto')->first();
                if(!isset($parametroDefecto))
                {
                    $request->session()->flash('alert-danger', 'No se encontró un parámetro por defecto.');
                    return redirect('/ticket')
                            ->withInput();
                }
                
                $empresaClienteusuario = DB::table('Soporte.EmpresaClienteUsuario')
                                        ->where('nombre', Auth::user()->name)->first();
                if(!isset($empresaClienteusuario))
                {
                    $request->session()->flash('alert-danger', 'El usuario con el que intenta generar el Ticket no es un cliente.');
                    return redirect('/ticket')
                            ->withInput();
                }

                $ticket = new Ticket();
                $ticket->guid = bin2hex(openssl_random_pseudo_bytes(16));
                $ticket->idTicketPrioridad = $parametroDefecto->idTicketPrioridad;
                $ticket->idTicketEstado = $parametroDefecto->idTicketEstado;
                $ticket->idFuncionario = $parametroDefecto->idFuncionario;
                $ticket->idEmpresaClienteUsuario = $empresaClienteusuario->idEmpresaClienteUsuario;
                $ticket->idCategoria = $request->categoria;
                $ticket->asunto = $request->asunto;
                $ticket->usuarioClienteRating = -1;
                $ticket->usuarioClienteComentario = '';
                $ticket->ultimoReplique = 'USUARIO';
                $ticket->idUsuarioCreacion = Auth::user()->id;
                $ticket->idUsuarioModificacion = Auth::user()->id;
                $ticket->save();

                $anexo1 = $request->input('image-preview-hidanexo1');
                $anexo2 = $request->input('image-preview-hidanexo2');
                $anexo3 = $request->input('image-preview-hidanexo3');
                if(isset($anexo1) || isset($anexo2) || isset($anexo3))
                {
                    $nombre1 = $request->input('image-nombreanexo1');
                    $nombre2 = $request->input('image-nombreanexo2');
                    $nombre3 = $request->input('image-nombreanexo3');

                    $ticketMensaje = new TicketMensaje();
                    $ticketMensaje->idTicket = $ticket->idTicket;
                    $ticketMensaje->contenido = $request->asunto;
                    $ticketMensaje->archivoNombre1 = !isset($anexo1) ? null :$nombre1;
                    $ticketMensaje->archivoAnexo1 = !isset($anexo1) ? null : $anexo1;
                    $ticketMensaje->archivoNombre2 = !isset($anexo2) ? null :$nombre2;
                    $ticketMensaje->archivoAnexo2 = !isset($anexo2) ? null : $anexo2;
                    $ticketMensaje->archivoNombre3 = !isset($anexo3) ? null :$nombre3;
                    $ticketMensaje->archivoAnexo3 = !isset($anexo3) ? null : $anexo3;
                    $ticketMensaje->idFuncionario = $parametroDefecto->idFuncionario;
                    $ticketMensaje->tipo = 'REQUERIDO';
                    $ticketMensaje->idTicketEstado = $parametroDefecto->idTicketEstado;
                    $ticketMensaje->nota = '';
                    $ticketMensaje->idUsuarioCreacion = Auth::user()->id;
                    $ticketMensaje->idUsuarioModificacion = Auth::user()->id;
                    $ticketMensaje->save();
                }
                DB::commit();
            } catch(Exception $e) {
                //dd($e);
                DB::rollback();
                $request->session()->flash('alert-danger', $e->getMessage());
                return redirect('/ticket')
                            ->withInput();
            }

            $request->session()->flash('alert-success', 'El Ticket se guardó correctamente.');
            return redirect()->route('ticket.index');
        }
    }

    public function listadoTicket()
    {
        $empresaClienteusuario = DB::table('Soporte.EmpresaClienteUsuario')
                                        ->where('nombre', Auth::user()->name)->first();
        if(isset($empresaClienteusuario))
        {
            $listTicket = DB::select('select t."idTicket", t.guid,t.created_at as fechasolicitud, ec.nombre as contacto, t.asunto, te.nombre as estado  
                                            ,(case WHEN (select "idTicketEstadoFinalizar" from "Soporte"."ParametroDefecto" LIMIT 1) = t."idTicketEstado" then
                                                    1
                                                else
                                                    0
                                                end
                                            ) as finalizado,
                                            t."usuarioClienteRating", te.color
                                        from "Soporte"."Ticket" as t
                                        inner join "Soporte"."EmpresaClienteUsuario" as ec on t."idEmpresaClienteUsuario" = ec."idEmpresaClienteUsuario"
                                        inner join "Soporte"."TicketEstado" te on t."idTicketEstado" = te."idTicketEstado"
                                        where ec."idEmpresaCliente" = ? ORDER BY t.created_at DESC', [$empresaClienteusuario->idEmpresaCliente]);
            return $this->views("soporte.consultas.listadoTicket", ["data" => $listTicket]);
        }
        return redirect('/home');
    }

    //Listado ticket(usuario)
    public function movimientoTareaTicket($idTicket)
    {
        if($idTicket > 0) {
            $tickets = DB::select("select 
                                        t.*,
                                        f.nombre as funcionario
                                    from \"Soporte\".\"TicketTarea\" as t 
                                    inner join \"Soporte\".\"Funcionario\" as f ON t.\"idFuncionario\" = f.\"idFuncionario\"
                                    where t.\"idTicket\" = :idTicket ORDER BY t.\"fechaInicio\"", ["idTicket"=>$idTicket]);
            return response()->json($tickets);
        }
    }

    public function calificarTicket(Request $request) {
        $data = 0;
        if(isset($request->guid))
        {
            $ticket = DB::table('Soporte.Ticket')->where('guid', '=', $request->guid)->first();
            $noSolucionado = $request->noSolucionado;
            if($ticket != null) {
                $parametroDefecto = DB::table('Soporte.ParametroDefecto')->first();
                if($ticket->idTicketEstado == $parametroDefecto->idTicketEstadoFinalizar)
                {
                    if($ticket->usuarioClienteRating == -1) {
                        $data = 0;
                        if($noSolucionado == 0) //Calificado
                        {
                            $query = 'UPDATE "Soporte"."Ticket" SET "idTicketEstado" = (select "idTicketEstadoArchivar" from "Soporte"."ParametroDefecto" LIMIT 1), "usuarioClienteRating" = :usuarioClienteRating, "usuarioClienteComentario" = :usuarioClienteComentario, "ultimoReplique" = :ultimoReplique,  "idUsuarioModificacion" = :id WHERE guid = :guid';
                            $data = DB::update($query, ['usuarioClienteRating' => $request->calificacion,
                                                        'usuarioClienteComentario' => $request->comentario == null ? '' : $request->comentario,
                                                        'guid' => $request->guid,
                                                        'ultimoReplique' => 'USUARIO',
                                                        'id' => Auth::user()->id
                                                    ]);
                        } else { //El cliente devuelve la solución
                            $query = 'UPDATE "Soporte"."Ticket" SET "idTicketEstado" = (select "idTicketEstadoRechazar" from "Soporte"."ParametroDefecto" LIMIT 1), "ultimoReplique" = :ultimoReplique,  "idUsuarioModificacion" = :id WHERE guid = :guid';
                            $data = DB::update($query, ['guid' => $request->guid,
                                                        'ultimoReplique' => 'USUARIO',
                                                        'id' => Auth::user()->id
                                                    ]);
                            //Guardar TicketMensaje
                            $parametroDefecto = DB::table('Soporte.ParametroDefecto')->first();
                            $ticketMensaje = new TicketMensaje();
                            $ticketMensaje->idTicket = $ticket->idTicket;
                            $ticketMensaje->contenido = $request->comentario;
                            $ticketMensaje->archivoNombre1 = null;
                            $ticketMensaje->archivoAnexo1 = null;
                            $ticketMensaje->archivoNombre2 = null;
                            $ticketMensaje->archivoAnexo2 = null;
                            $ticketMensaje->archivoNombre3 = null;
                            $ticketMensaje->archivoAnexo3 = null;
                            $ticketMensaje->idFuncionario = $parametroDefecto->idFuncionario;
                            $ticketMensaje->tipo = 'REQUERIDO';
                            $ticketMensaje->idTicketEstado = $parametroDefecto->idTicketEstadoRechazar;
                            $ticketMensaje->nota = '';
                            $ticketMensaje->idUsuarioCreacion = Auth::user()->id;
                            $ticketMensaje->idUsuarioModificacion = Auth::user()->id;
                            $ticketMensaje->save();
                        }
                        if($data > 0) {
                            return response()->json([ "estado"=> true]);        
                        }
                    } else {
                        return response()->json([ "estado"=> false, "mensaje"=>"El ticket ya se encuentra archivado."]);
                    }
                } else {
                    return response()->json([ "estado"=> false, "mensaje"=>"El ticket no se puede modificar, el estado cambió."]);
                }
            } else {
                return response()->json([ "estado"=> false, "mensaje"=>"No se encontró el Ticket."]);
            }
        } else {
            return response()->json([ "estado"=> false, "mensaje"=>"Se ha presentado un error con los datos."]);
        }
    }
}