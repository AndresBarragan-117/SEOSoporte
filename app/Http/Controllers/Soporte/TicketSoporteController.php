<?php

namespace App\Http\Controllers\Soporte;

use App\Http\Controllers\CustomController;
use App\Http\Requests\Soporte\TicketValidator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use App\Entorno;
use App\Model\Soporte\MEmpresaClienteUsuario;
use App\Model\Soporte\TicketTarea;
use App\Model\Soporte\Ticket;
use App\Model\Soporte\TicketMensaje;
use EmpresaClienteUsuario;
use Exception;
use Faker\Guesser\Name;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TicketSoporteController extends CustomController
{
    protected $tag = '108';

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
        return $this->views("soporte.ticketsoporte.ticketsoporte", [
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
                    return redirect('/ticketSoporte')
                            ->withInput();
                }
                
                $funcionarioLogueado = DB::select('select func."idFuncionario" from "Soporte"."Funcionario" as func
                                    INNER JOIN users u ON func."idUser" = u.id
                                    where u.id = ? limit 1', [Auth::user()->id]);
                if(!isset($funcionarioLogueado))
                {
                    $request->session()->flash('alert-danger', 'No se encontró el usuario soporte.');
                    return redirect('/ticketSoporte')
                            ->withInput();
                }
                
                if(!isset($request->cliente) || $request->cliente == "") {
                    $request->session()->flash('alert-danger', 'Debe seleccionar un cliente.');
                    return redirect('/ticketSoporte')
                            ->withInput();
                }
                $empresaClienteusuario = MEmpresaClienteUsuario::where('idEmpresaClienteUsuario',$request->cliente)->first();
                if(!isset($empresaClienteusuario))
                {
                    $request->session()->flash('alert-danger', 'No se encontró el cliente.');
                    return redirect('/ticketSoporte')
                            ->withInput();
                }
                $fechaInicio = date('Y-m-d H:i:s');
                $ticket = new Ticket();
                $ticket->guid = bin2hex(openssl_random_pseudo_bytes(16));
                $ticket->idTicketPrioridad = $parametroDefecto->idTicketPrioridad;
                $ticket->idTicketEstado = isset($request->tareaRapida)?$parametroDefecto->idTicketEstadoFinalizar : $parametroDefecto->idTicketEstado;
                $ticket->idFuncionario = $funcionarioLogueado[0]->idFuncionario;
                $ticket->idEmpresaClienteUsuario = $empresaClienteusuario->idEmpresaClienteUsuario;
                $ticket->idCategoria = $request->categoria;
                $ticket->asunto = $request->asunto;
                $ticket->fechaLeido = date('Y-m-d H:i:s');
                $ticket->usuarioClienteRating = -1;
                $ticket->usuarioClienteComentario = '';
                $ticket->ultimoReplique = 'SOPORTE';
                $ticket->idUsuarioCreacion = Auth::user()->id;
                $ticket->idUsuarioModificacion = Auth::user()->id;
                $ticket->save();
                
                if(!isset($request->tareaRapida)) {
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
                        $ticketMensaje->idFuncionario = $funcionarioLogueado[0]->idFuncionario;
                        $ticketMensaje->tipo = 'REQUERIDO';
                        $ticketMensaje->idTicketEstado = $parametroDefecto->idTicketEstado;
                        $ticketMensaje->nota = '';
                        $ticketMensaje->idUsuarioCreacion = Auth::user()->id;
                        $ticketMensaje->idUsuarioModificacion = Auth::user()->id;
                        $ticketMensaje->save();
                    }
                } else {
                    $ticketTarea = new TicketTarea();
                    $ticketTarea->idTicket = $ticket->idTicket;
                    $ticketTarea->idFuncionario = $funcionarioLogueado[0]->idFuncionario;
                    $ticketTarea->fechaInicio = $fechaInicio;
                    $ticketTarea->contenidoInicio = 'SOLUCION RAPIDA';
                    $ticketTarea->fechaFin = date('Y-m-d H:i:s');
                    $ticketTarea->contenidoFin = $request->solucion;
                    $ticketTarea->idUsuarioCreacion = Auth::user()->id;
                    $ticketTarea->idUsuarioModificacion = Auth::user()->id;
                    $ticketTarea->save();
                }
                DB::commit();
            } catch(Exception $e) {
                DB::rollback();
                $request->session()->flash('alert-danger', $e->getLine()."-".$e->getMessage());
                return redirect('/ticketSoporte')
                            ->withInput();
            }

            $request->session()->flash('alert-success', 'El Ticket se guardó correctamente.');
            return redirect()->route('ticketSoporte.index');
        }
    }

    // Consulta al cargar el formulario
    public function listadoTicketSoporte()
    {
        $ticketEstado = DB::table('Soporte.TicketEstado')->get();
        $ticketPrioridad = DB::table('Soporte.TicketPrioridad')->get();
        $categoria = DB::table('Soporte.Categoria')->get();
        $parametroDefectoFinalizar = DB::select('select
                                        distinct
                                            te.nombre
                                        from "Soporte"."ParametroDefecto" pd
                                        inner join "Soporte"."TicketEstado" te on pd."idTicketEstadoFinalizar" = te."idTicketEstado"', []);
        $parametroDefectoArchivado = DB::select('select
                                        distinct
                                            te.nombre
                                        from "Soporte"."ParametroDefecto" pd
                                        inner join "Soporte"."TicketEstado" te on pd."idTicketEstadoArchivar" = te."idTicketEstado"', []);

        $acciones = DB::select('select
                                distinct
                                accion
                            from "Seguridad"."UsuarioAccion" where "idUsuario" = ?', array(Auth::user()->id));
        $data2 = collect($acciones)->map(function($x){ return $x->accion; })->toArray();

        $func = DB::table('Soporte.Funcionario')
                    ->where('idUser', Auth::user()->id)->first();

        $idFuncionario = $func->idFuncionario;
        $nombreFuncionario = $func->nombre;
        if(in_array("MOSTRARTODOSOPORTE", $data2)) {
            $idFuncionario = 0;
            $nombreFuncionario = '';
        }

        $con2 = Entorno::getConex2();
        $listTicket = DB::select("select 
                                            t.guid,t.created_at as fechasolicitud,
                                            ec.nombre as contacto, 
                                            t.asunto,
                                            te.nombre as estado,
                                            empresa.\"razonSocial\",
                                            empresa.ciudad,
                                            func.nombre as \"usuarioSoporte\", t.\"idTicket\",
                                            cat.nombre categoria, te.color
                                    from \"Soporte\".\"Ticket\" as t
                                    inner join \"Soporte\".\"EmpresaClienteUsuario\" as ec on t.\"idEmpresaClienteUsuario\" = ec.\"idEmpresaClienteUsuario\"
                                    INNER JOIN dblink('$con2',
                                        'SELECT nit,\"razonSocial\", \"idEmpresa\", ciudad FROM \"Empresa\".\"Empresa\"')
                                        AS empresa(nit text,\"razonSocial\" text, \"idEmpresa\" integer, ciudad text) ON ec.\"idEmpresaCliente\" = empresa.\"idEmpresa\"
                                    inner join \"Soporte\".\"TicketEstado\" te on t.\"idTicketEstado\" = te.\"idTicketEstado\"
                                    INNER JOIN \"Soporte\".\"Funcionario\" func on t.\"idFuncionario\" = func.\"idFuncionario\"
                                    INNER JOIN \"Soporte\".\"Categoria\" cat on t.\"idCategoria\" = cat.\"idCategoria\"
                                    WHERE (:idFuncionario = 0 OR t.\"idFuncionario\" = :idFuncionario) ORDER BY t.created_at DESC", ["idFuncionario" => $idFuncionario]);

        
        return $this->views("soporte.consultas.listadoTicketSoporte", [
                "data" => $listTicket,
                'ticketEstado' => $ticketEstado,
                'acciones' => $data2,
                'idFuncionario' => $idFuncionario,
                'nombreFuncionario' => $nombreFuncionario,
                'categoria' => $categoria,
                'ticketPrioridad'=>$ticketPrioridad,
                'parametroDefectoFinalizar' => $parametroDefectoFinalizar[0],
                'parametroDefectoArchivado' => $parametroDefectoArchivado[0]
        ]);
    }

    // Consulta al dar clic en Consultar(Ajax)
    public function listadoTicketSoporte2($fechaInicio, $fechaFin, $idEmpresa, $idCliente, $idTicketEstado, $idFuncionario, $idCategoria, $idTicketPrioridad)
    {
        $con2 = Entorno::getConex2();

        $acciones = DB::select('select
                                distinct
                                accion
                            from "Seguridad"."UsuarioAccion" where "idUsuario" = ?', array(Auth::user()->id));
        $data2 = collect($acciones)->map(function($x){ return $x->accion; })->toArray();

        $func = DB::table('Soporte.Funcionario')
                ->where('idUser', Auth::user()->id)->first();
        
        if(!in_array("MOSTRARTODOSOPORTE", $data2)) {
            // No tiene el permiso, solo busca los tickets de ese funcionario
            $idFuncionario = $func->idFuncionario;
        }
        
        $listTicket = DB::select("select 
                                            t.guid,t.created_at as fechasolicitud,
                                            ec.nombre as contacto, 
                                            t.asunto,
                                            te.nombre as estado,
                                            empresa.\"razonSocial\",
                                            empresa.ciudad,
                                            func.nombre as \"usuarioSoporte\", t.\"idTicket\",
                                            cat.nombre as categoria, te.color
                                    from \"Soporte\".\"Ticket\" as t
                                    inner join \"Soporte\".\"EmpresaClienteUsuario\" as ec on t.\"idEmpresaClienteUsuario\" = ec.\"idEmpresaClienteUsuario\"
                                    INNER JOIN dblink('$con2',
                                        'SELECT nit,\"razonSocial\", \"idEmpresa\", ciudad FROM \"Empresa\".\"Empresa\"')
                                        AS empresa(nit text,\"razonSocial\" text, \"idEmpresa\" integer, ciudad text) ON ec.\"idEmpresaCliente\" = empresa.\"idEmpresa\"
                                    inner join \"Soporte\".\"TicketEstado\" te on t.\"idTicketEstado\" = te.\"idTicketEstado\"
                                    INNER JOIN \"Soporte\".\"Funcionario\" func on t.\"idFuncionario\" = func.\"idFuncionario\"
                                    INNER JOIN \"Soporte\".\"Categoria\" cat on t.\"idCategoria\" = cat.\"idCategoria\"
                                    WHERE date_trunc('day', t.created_at) >= :fechaInicio AND date_trunc('day', t.created_at) <= :fechaFin
                                            AND (:idEmpresa = 0 OR empresa.\"idEmpresa\" = :idEmpresa)
                                            AND (:idCliente = 0 OR ec.\"idEmpresaClienteUsuario\" = :idCliente)
                                            AND (:idTicketEstado = 0 OR te.\"idTicketEstado\" = :idTicketEstado)
                                            AND (:idFuncionario = 0 OR t.\"idFuncionario\" = :idFuncionario)
                                            AND (:idTicketPrioridad = 0 OR t.\"idTicketPrioridad\" = :idTicketPrioridad)
                                            AND (:idCategoria = 0 OR t.\"idCategoria\" = :idCategoria)  ORDER BY t.created_at DESC
                                    ", [
                                        'fechaInicio' => $fechaInicio,
                                        'fechaFin' => $fechaFin,
                                        'idEmpresa' => $idEmpresa,
                                        'idCliente' => $idCliente,
                                        'idTicketEstado' => $idTicketEstado,
                                        'idFuncionario' => $idFuncionario,
                                        'idCategoria' => $idCategoria,
                                        'idTicketPrioridad' => $idTicketPrioridad
                                    ]);
        return response()->json($listTicket);
    }

    public function listadoTicketTarea($id)
    {
        $data = DB::select("SELECT
                                fun.nombre as funcionario,
                                t.asunto,
                                tt.*
                            FROM \"Soporte\".\"Ticket\" AS t
                            INNER JOIN \"Soporte\".\"TicketTarea\" AS tt ON t.\"idTicket\" = tt.\"idTicket\"
                            INNER JOIN \"Soporte\".\"Funcionario\" AS fun ON tt.\"idFuncionario\" = fun.\"idFuncionario\"
                            WHERE t.\"idTicket\" = :idTicket", [
                                                                    'idTicket' => $id
                                                                ]);
        return response()->json($data);
    }

    public function ticketEstadoTarea() 
    {
        $parametroPorDefecto = DB::table('Soporte.ParametroDefecto')->first();
        $data = DB::select('select * from "Soporte"."TicketEstado" where "idTicketEstado" NOT IN(?, ?)', [$parametroPorDefecto->idTicketEstado, $parametroPorDefecto->idTicketEstadoArchivar]);
        return response()->json($data);
    }

    public function nuevaTarea(Request $request)
    {
        if(isset($request->guid))
        {
            try {
                $funcionarioLogueado = DB::select('select func."idFuncionario" from "Soporte"."Funcionario" as func
                                        INNER JOIN users u ON func."idUser" = u.id
                                        where u.id = ? limit 1', [Auth::user()->id])[0];
                $ticket = DB::table('Soporte.Ticket')->where('guid', $request->guid)->first();
                $parametroDefecto = DB::select('select
                                                    distinct
                                                    te.nombre as finalizar,
                                                    tea.nombre as archivar
                                                from "Soporte"."ParametroDefecto" pd
                                                inner join "Soporte"."TicketEstado" te on pd."idTicketEstadoFinalizar" = te."idTicketEstado"
                                                inner join "Soporte"."TicketEstado" tea on pd."idTicketEstadoArchivar" = tea."idTicketEstado"', [])[0];
                //validar estado del Ticket
                $estado = DB::table('Soporte.TicketEstado')->where('idTicketEstado', $ticket->idTicketEstado)->first();
                if($estado->nombre == $parametroDefecto->finalizar || $estado->nombre == $parametroDefecto->archivar) {
                    return response()->json([ "estado"=> false, "mensaje"=>"No se puede realizar la operación, el estado del ticket es ".$estado->nombre]);
                }
                
                // VALIDAR QUE EL TICKET NO TENGA TAREAS ABIERTAS
                $tarea = DB::select('select * from "Soporte"."TicketTarea" where "idTicket" = ? and "contenidoFin" IS NULL', [$ticket->idTicket]);
                if((isset($request->contenido) && $request->hiddenContenidoInicio == "1") && count($tarea) > 0) {
                    return response()->json([ "estado"=> false, "mensaje"=>"No se puede realizar la operación, El ticket ya tiene una tarea abierta"]);
                }

                if(isset($request->contenido) && $request->hiddenContenidoInicio == "1")
                {
                    $ticketTarea = new TicketTarea();
                    $ticketTarea->idTicket = $ticket->idTicket;
                    $ticketTarea->idFuncionario = $funcionarioLogueado->idFuncionario;
                    $ticketTarea->fechaInicio = date('Y-m-d H:i:s');
                    $ticketTarea->contenidoInicio = $request->contenido;
                    $ticketTarea->fechaFin = null;
                    $ticketTarea->contenidoFin = null;
                    $ticketTarea->idUsuarioCreacion = Auth::user()->id;
                    $ticketTarea->idUsuarioModificacion = Auth::user()->id;
                    $ticketTarea->save();

                    // actualizar replique del Ticket SOPORTE
                    DB::table('Soporte.Ticket')
                        ->where('idTicket', $ticket->idTicket)
                        ->update(['updated_at' => date('Y-m-d H:i:s'), 'ultimoReplique'=>'SOPORTE']);

                    return response()->json([ "estado"=> true, "idTicket"=>$ticket->idTicket]);
                } else if(isset($request->contenido) && $request->hiddenContenidoInicio == "0") {
                    /// Finalización de la tarea
                    if(!isset($request->idTicketEstado) || $request->idTicketEstado == 0) {
                        return response()->json([ "estado"=> false, "mensaje"=>"Seleccione el estado próximo del Ticket"]);
                    }
                    DB::table('Soporte.Ticket')
                        ->where('idTicket', $ticket->idTicket)
                        ->update(['updated_at' => date('Y-m-d H:i:s'), 'idTicketEstado'=>$request->idTicketEstado, 'ultimoReplique' =>'USUARIO']);

                    $affected = DB::table('Soporte.TicketTarea')
                        ->where('idTicketTarea', intval($request->hiddenTicketTarea))
                        ->update(['fechaFin' => date('Y-m-d H:i:s'), 'contenidoFin'=>$request->contenido]);


                    if($affected > 0) {
                        return response()->json([ "estado"=> true, "idTicket"=>$ticket->idTicket]);
                    }
                }
            } catch(Exception $e) {
                return response()->json([ "estado"=> false, "mensaje"=>$e->getMessage()]);
            }
        }
        return response()->json([ "estado"=> false, "mensaje"=>"Se ha presentado un error al intentar realizar la operación."]);
    }

    public function cambiarPrioridadTicket(Request $request)
    {
        $data = 0;
        if(isset($request->idTicketPrioridad) && $request->idTicketPrioridad > 0)
        {
            $query = 'UPDATE "Soporte"."Ticket" SET "idTicketPrioridad" = :idTicketPrioridad, "idUsuarioModificacion" = :id WHERE guid = :guid';
            $data = DB::update($query, ['idTicketPrioridad' => $request->idTicketPrioridad,
                                        'guid' => $request->guid,
                                        'id' => Auth::user()->id
                                       ]);
            if($data > 0) {
                return response()->json([ "estado"=> true]);        
            }
        }
        return response()->json([ "estado"=> false]);
    }

    public function notificacionTicket(Request $request)
    {
        try {
            $con2 = Entorno::getConex2();
            $roles = DB::select('select distinct r.nombre from "Seguridad"."RolUsuario" ru
                                    inner join "Seguridad"."Rol" r on ru."idRol" = r."idRol"
                                    inner join users u on ru."idUsuario" = u.id
                                    where u.id = :id
                                ', ['id'=> Auth::user()->id]);
            if(count($roles) > 0) {
                $admin = false;
                foreach ($roles as $rol) { 
                    if($rol->nombre == "Administradores") {
                        $admin = true;
                    }
                }

                $data = null;
                if($admin) {
                    $data = DB::select("select
                                            t.created_at as fecha,
                                            ecu.nombre as usuario,
                                            empresa.\"razonSocial\" as empresa
                                        from \"Soporte\".\"Ticket\" t
                                        inner join \"Soporte\".\"EmpresaClienteUsuario\" ecu on t.\"idEmpresaClienteUsuario\" = ecu.\"idEmpresaClienteUsuario\"
                                        INNER JOIN dblink('$con2',
                                            'SELECT nit,\"razonSocial\", \"idEmpresa\", ciudad FROM \"Empresa\".\"Empresa\"')
                                            AS empresa(nit text,\"razonSocial\" text, \"idEmpresa\" integer, ciudad text) ON ecu.\"idEmpresaCliente\" = empresa.\"idEmpresa\"
                                        inner join \"Soporte\".\"ParametroDefecto\" pd on 
                                            t.\"idTicketEstado\" = pd.\"idTicketEstado\" and
                                            t.\"idFuncionario\" = pd.\"idFuncionario\"
                                        where (select count(*) from \"Soporte\".\"TicketTarea\" tt where t.\"idTicket\" = tt.\"idTicket\") = 0", []);
                } else {
                    $data = DB::select("select
                                            t.created_at as fecha,
                                            ecu.nombre as usuario,
                                            empresa.\"razonSocial\" as empresa
                                        from \"Soporte\".\"Ticket\" t
                                        inner join \"Soporte\".\"Funcionario\" fun on t.\"idFuncionario\" = fun.\"idFuncionario\"
                                        inner join \"Soporte\".\"EmpresaClienteUsuario\" ecu on t.\"idEmpresaClienteUsuario\" = ecu.\"idEmpresaClienteUsuario\"
                                        INNER JOIN dblink('$con2',
                                                'SELECT nit,\"razonSocial\", \"idEmpresa\", ciudad FROM \"Empresa\".\"Empresa\"')
                                                AS empresa(nit text,\"razonSocial\" text, \"idEmpresa\" integer, ciudad text) ON ecu.\"idEmpresaCliente\" = empresa.\"idEmpresa\"
                                        where fun.\"idUser\" = :id
                                        and (select count(*) from \"Soporte\".\"TicketTarea\" tt where t.\"idTicket\" = tt.\"idTicket\") = 0", ["id"=>Auth::user()->id]);
                }
                return response()->json([ "estado"=> true, "cantidad" => count($data), "data"=>$data]);
            } else {
                return response()->json([ "estado"=> false, "mensaje"=>"El usuario no tiene roles asignados"]);
            }
        } catch (\Exception $ex) {
            return response()->json([ "estado"=> false, "mensaje"=>$ex->getMessage()]);
        }
    }

    public function consultarAnexoTicket(Request $request){
        try {
            if(isset($request->idTicket)) {
                $ticket = DB::select('select * from "Soporte"."TicketMensaje" where "idTicket" = ?', [$request->idTicket]);
                if(count($ticket) > 0) {
                    for($i = 0; $i < count($ticket); $i++)
                    {
                        if($ticket[$i]->archivoAnexo1 != null)
                        {
                            $ticket[$i]->archivoAnexo1 = stream_get_contents($ticket[$i]->archivoAnexo1);
                        }
                        if($ticket[$i]->archivoAnexo2 != null)
                        {
                            $ticket[$i]->archivoAnexo2 = stream_get_contents($ticket[$i]->archivoAnexo2);
                        }
                        if($ticket[$i]->archivoAnexo3 != null)
                        {
                            $ticket[$i]->archivoAnexo3 = stream_get_contents($ticket[$i]->archivoAnexo3);
                        }
                    }

                    return response()->json(['estado'=> true, 'mensaje'=>'Ok', 'data'=>$ticket]);
                } else {
                    return response()->json(['estado'=> false, 'mensaje'=>'EL ticket no tiene anexos', 'data'=>null]);
                }
            } else {
                return response()->json(['estado'=> false, 'mensaje'=>'Seleccione un ticket', 'data'=>null]);
            }
        } catch(\Exception $ex) {
            return response()->json(['estado'=> false,'mensaje'=>$ex->getMessage(), 'data'=>null]);
        }
    }

    public function rptClienteTicket($json = 0,$fechaInicial = '', $fechaFinal = '', $cliente = 0)
    {
        if($json == 0)
        {
            //$categoria = DB::table('Soporte.Categoria')->get();
            return $this->views("soporte.consultas.rptClienteTicket", [
                'categoria' => null
            ]);
        } else {
            $con2 = Entorno::getConex2();
            $listTicket = DB::select("select
                                            empresa.\"razonSocial\" || ' - ' || empresa.\"razonSocial\" as empresa,
                                            (select count(t.*) from \"Soporte\".\"Ticket\" t 
                                                    inner join \"Soporte\".\"EmpresaClienteUsuario\" ecu on t.\"idEmpresaClienteUsuario\" = ecu.\"idEmpresaClienteUsuario\"
                                                    where ecu.\"idEmpresaCliente\" = empresa.\"idEmpresa\") as cantidad
                                        from  dblink('$con2',
                                                        'SELECT nit,\"razonSocial\", \"idEmpresa\", ciudad FROM \"Empresa\".\"Empresa\"')
                                                        AS empresa(nit text,\"razonSocial\" text, \"idEmpresa\" integer, ciudad text)
                                        WHERE :idEmpresa = 0 OR empresa.\"idEmpresa\" = :idEmpresa", ["idEmpresa" => $cliente]);
            $array[] = ['Empresa', 'Cantidades Tickets'];
            foreach($listTicket as $key => $value)
            {
                $array[++$key] = [$value->empresa, $value->cantidad];
            }
            return response()->json(["reporte"=>$listTicket, "grafica"=>$array]);
        }
    }

    public function rptClienteCategoriaTicket($json = 0,$fechaInicial = '', $fechaFinal = '', $cliente = 0, $categoria = 0){
        if($json == 0)
        {
            $categoria = DB::table('Soporte.Categoria')->get();
            return $this->views("soporte.consultas.rptClienteCategoriaTicket", [
                'categoria' => $categoria
            ]);
        } else {
            $con2 = Entorno::getConex2();
            $listTicket = DB::select("select
                                            empresa.\"idEmpresa\",
                                            empresa.\"nit\" || ' - ' || empresa.\"razonSocial\" as empresa,
                                            c.nombre as categoria,
                                            count(t.*) cantidad
                                        from  \"Soporte\".\"Ticket\" t
                                        inner join \"Soporte\".\"EmpresaClienteUsuario\" ecu on t.\"idEmpresaClienteUsuario\" = ecu.\"idEmpresaClienteUsuario\"
                                        inner join \"Soporte\".\"Categoria\" c on t.\"idCategoria\" = c.\"idCategoria\"
                                        inner join dblink('$con2',
                                                        'SELECT nit,\"razonSocial\", \"idEmpresa\", ciudad FROM \"Empresa\".\"Empresa\"')
                                                        AS empresa(nit text,\"razonSocial\" text, \"idEmpresa\" integer, ciudad text) ON ecu.\"idEmpresaCliente\" = empresa.\"idEmpresa\"
                                        WHERE :idEmpresa = 0 OR empresa.\"idEmpresa\" = :idEmpresa
                                         GROUP BY empresa.\"idEmpresa\", empresa.\"nit\",empresa.\"razonSocial\", c.nombre ", ["idEmpresa" => $cliente]);
            
            $categorias = DB::select("select
                                            DISTINCT c.nombre as categoria
                                        from  \"Soporte\".\"Ticket\" t
                                        inner join \"Soporte\".\"EmpresaClienteUsuario\" ecu on t.\"idEmpresaClienteUsuario\" = ecu.\"idEmpresaClienteUsuario\"
                                        inner join \"Soporte\".\"Categoria\" c on t.\"idCategoria\" = c.\"idCategoria\"
                                        inner join dblink('$con2',
                                                        'SELECT nit,\"razonSocial\", \"idEmpresa\", ciudad FROM \"Empresa\".\"Empresa\"')
                                                        AS empresa(nit text,\"razonSocial\" text, \"idEmpresa\" integer, ciudad text) ON ecu.\"idEmpresaCliente\" = empresa.\"idEmpresa\"
                                        WHERE :idEmpresa = 0 OR empresa.\"idEmpresa\" = :idEmpresa
                                        GROUP BY empresa.\"nit\",empresa.\"razonSocial\", c.nombre ", ["idEmpresa" => $cliente]);
            $empresas = DB::select("select
                                            DISTINCT empresa.\"nit\" || ' - ' || empresa.\"razonSocial\" as empresa, empresa.\"idEmpresa\"
                                        from  \"Soporte\".\"Ticket\" t
                                        inner join \"Soporte\".\"EmpresaClienteUsuario\" ecu on t.\"idEmpresaClienteUsuario\" = ecu.\"idEmpresaClienteUsuario\"
                                        inner join \"Soporte\".\"Categoria\" c on t.\"idCategoria\" = c.\"idCategoria\"
                                        inner join dblink('$con2',
                                                        'SELECT nit,\"razonSocial\", \"idEmpresa\", ciudad FROM \"Empresa\".\"Empresa\"')
                                                        AS empresa(nit text,\"razonSocial\" text, \"idEmpresa\" integer, ciudad text) ON ecu.\"idEmpresaCliente\" = empresa.\"idEmpresa\"
                                        WHERE :idEmpresa = 0 OR empresa.\"idEmpresa\" = :idEmpresa
                                        ", ["idEmpresa" => $cliente]);

            $array = [];
            foreach($categorias as $key => $cat)
            {
                if($key == 0) {
                    $array[] = 'Empresa';
                }
                $array[] = $cat->categoria;
            }
            $array = [$array];
            $cabecera = $array[0];
            foreach($empresas as $emp) { //Listado de empresa
                $arrRow = [];
                $arrRow[] = $emp->empresa;
                foreach($cabecera as $cat) // Categoria Cabecera
                {
                    if($cat != 'Empresa') {
                        $cantidad = DB::select("select
                                        count(t.*) cantidad
                                    from  \"Soporte\".\"Ticket\" t
                                    inner join \"Soporte\".\"EmpresaClienteUsuario\" ecu on t.\"idEmpresaClienteUsuario\" = ecu.\"idEmpresaClienteUsuario\"
                                    inner join \"Soporte\".\"Categoria\" c on t.\"idCategoria\" = c.\"idCategoria\"
                                    inner join dblink('$con2',
                                                    'SELECT nit,\"razonSocial\", \"idEmpresa\", ciudad FROM \"Empresa\".\"Empresa\"')
                                                    AS empresa(nit text,\"razonSocial\" text, \"idEmpresa\" integer, ciudad text) ON ecu.\"idEmpresaCliente\" = empresa.\"idEmpresa\"
                                    WHERE (empresa.\"idEmpresa\" = :idEmpresa) AND (c.nombre = :categoria)
                                    ", ["idEmpresa" => $emp->idEmpresa, "categoria"=>$cat]);
                        $arrRow[] = $cantidad[0]->cantidad;
                    }
                }
                $array[] = $arrRow;
                $arrRow =[];
            }
            return response()->json(["reporte"=>$listTicket, "grafica"=>$array]);
        }
    }

    public function rptClienteTicketTiempo($json = 0,$fechaInicial = '', $fechaFinal = '', $cliente = 0)
    {
        if($json == 0)
        {
            return $this->views("soporte.consultas.rptClienteTicketTiempo", [
                'categoria' => null
            ]);
        } else {
            $con2 = Entorno::getConex2();
            $listTicket = DB::select("select * from \"Soporte\".\"fnRpteClienteTicketTiempo\"(:idEmpresa, :fechaInicio, :fechaFinal) ORDER BY tiempo desc", ["idEmpresa" => $cliente, "fechaInicio" => $fechaInicial, "fechaFinal"=>$fechaFinal]);
            $array[] = ['Empresa', 'Tiempo Tickets(Horas)'];
            foreach($listTicket as $key => $value)
            {
                $array[++$key] = [$value->empresa, intval($value->tiempo)];
            }

            return response()->json(["reporte"=>$listTicket, "grafica"=>$array]);
        }
    }

    public function rptClienteCategoriaTicketTiempo($json = 0,$fechaInicial = '', $fechaFinal = '', $cliente = 0, $categoria = 0){
        if($json == 0)
        {
            $categoria = DB::table('Soporte.Categoria')->get();
            return $this->views("soporte.consultas.rptClienteCategoriaTicketTiempo", [
                'categoria' => $categoria
            ]);
        } else {
            $con2 = Entorno::getConex2();
            $listTicket = DB::select("select * from \"Soporte\".\"fnRpteClienteCategoriaTicketTiempo\"(:idEmpresa, :fechaInicio, :fechaFinal, :categoria)", ["idEmpresa" => $cliente, "fechaInicio" => $fechaInicial, "fechaFinal"=>$fechaFinal, "categoria"=>$categoria]);

            $categorias = DB::select("select
                                            DISTINCT c.nombre as categoria
                                        from  \"Soporte\".\"Ticket\" t
                                        inner join \"Soporte\".\"EmpresaClienteUsuario\" ecu on t.\"idEmpresaClienteUsuario\" = ecu.\"idEmpresaClienteUsuario\"
                                        inner join \"Soporte\".\"Categoria\" c on t.\"idCategoria\" = c.\"idCategoria\"
                                        inner join dblink('$con2',
                                                        'SELECT nit,\"razonSocial\", \"idEmpresa\", ciudad FROM \"Empresa\".\"Empresa\"')
                                                        AS empresa(nit text,\"razonSocial\" text, \"idEmpresa\" integer, ciudad text) ON ecu.\"idEmpresaCliente\" = empresa.\"idEmpresa\"
                                        WHERE :idEmpresa = 0 OR empresa.\"idEmpresa\" = :idEmpresa
                                        GROUP BY empresa.\"nit\",empresa.\"razonSocial\", c.nombre ", ["idEmpresa" => $cliente]);
            $empresas = DB::select("select
                                            DISTINCT empresa.\"nit\" || ' - ' || empresa.\"razonSocial\" as empresa, empresa.\"idEmpresa\"
                                        from  \"Soporte\".\"Ticket\" t
                                        inner join \"Soporte\".\"EmpresaClienteUsuario\" ecu on t.\"idEmpresaClienteUsuario\" = ecu.\"idEmpresaClienteUsuario\"
                                        inner join \"Soporte\".\"Categoria\" c on t.\"idCategoria\" = c.\"idCategoria\"
                                        inner join dblink('$con2',
                                                        'SELECT nit,\"razonSocial\", \"idEmpresa\", ciudad FROM \"Empresa\".\"Empresa\"')
                                                        AS empresa(nit text,\"razonSocial\" text, \"idEmpresa\" integer, ciudad text) ON ecu.\"idEmpresaCliente\" = empresa.\"idEmpresa\"
                                        WHERE :idEmpresa = 0 OR empresa.\"idEmpresa\" = :idEmpresa
                                        ", ["idEmpresa" => $cliente]);

            $array = [];
            foreach($categorias as $key => $cat)
            {
                if($key == 0) {
                    $array[] = 'Empresa';
                }
                $array[] = $cat->categoria;
            }
            $array = [$array];
            $cabecera = $array[0];
            foreach($empresas as $emp) { //Listado de empresa
                $arrRow = [];
                $arrRow[] = $emp->empresa;
                foreach($cabecera as $cat) // Categoria Cabecera
                {
                    if($cat != 'Empresa') {
                        $cantidad = DB::select("select * from \"Soporte\".\"fnGetTiempoHoras\"(:idEmpresa, :fechaInicio, :fechaFinal, :categoria)"
                                        , ["idEmpresa" => $emp->idEmpresa, "fechaInicio" => $fechaInicial, "fechaFinal"=>$fechaFinal, "categoria"=>$cat]);
                        if(isset($cantidad) && count($cantidad) > 0) {
                            $arrRow[] = doubleval($cantidad[0]->tiempo);
                        } else {
                            $arrRow[] = 0;
                        }
                    }
                }
                $array[] = $arrRow;
                $arrRow = [];
            }
            return response()->json(["reporte"=>$listTicket, "grafica"=>$array]);
        }
    }

    public function rptUsuarioCliente($json = 0,$fechaInicial = '', $fechaFinal = '', $cliente = 0)
    {
        if($json == 0)
        {
            return $this->views("soporte.consultas.rptUsuarioCLiente", [
                'categoria' => null
            ]);
        } else {
            $con2 = Entorno::getConex2();
            $listTicket = DB::select("select
                                            f.nombre as usuario
                                            , empresa.\"nit\" || ' - ' || empresa.\"razonSocial\" as empresa
                                            ,count(t.*) as cantidad
                                        from \"Soporte\".\"Ticket\" t
                                        inner join \"Soporte\".\"Funcionario\" f on t.\"idFuncionario\" = f.\"idFuncionario\"
                                        inner join \"Soporte\".\"EmpresaClienteUsuario\" ecu on t.\"idEmpresaClienteUsuario\" = ecu.\"idEmpresaClienteUsuario\"
                                        inner join dblink('$con2',
                                                'SELECT nit,\"razonSocial\", \"idEmpresa\", ciudad FROM \"Empresa\".\"Empresa\"')
                                                AS empresa(nit text,\"razonSocial\" text, \"idEmpresa\" integer, ciudad text) ON ecu.\"idEmpresaCliente\" = empresa.\"idEmpresa\"
                                        WHERE :idEmpresa = 0 OR empresa.\"idEmpresa\" = :idEmpresa group by f.nombre, empresa.\"nit\", empresa.\"razonSocial\"", ["idEmpresa" => $cliente]);
            $array[] = ['Empresa', 'Cantidades Tickets'];
            foreach($listTicket as $key => $value)
            {
                $array[++$key] = [$value->usuario." - ".$value->empresa, $value->cantidad];
            }
            return response()->json(["reporte"=>$listTicket, "grafica"=>$array]);
        }
    }

    public function rptUsuarioCategoriaTicket($json = 0,$fechaInicial = '', $fechaFinal = '', $cliente = 0, $categoria = 0){
        if($json == 0)
        {
            $categoria = DB::table('Soporte.Categoria')->get();
            return $this->views("soporte.consultas.rptUsuarioCategoriaTicket", [
                'categoria' => $categoria
            ]);
        } else {
            $con2 = Entorno::getConex2();
            $listTicket = DB::select("select
                                            f.nombre as usuario,
                                            c.nombre as categoria,
                                            count(t.*) cantidad
                                        from  \"Soporte\".\"Ticket\" t
                                        inner join \"Soporte\".\"Funcionario\" f on t.\"idFuncionario\" = f.\"idFuncionario\"
                                        inner join \"Soporte\".\"EmpresaClienteUsuario\" ecu on t.\"idEmpresaClienteUsuario\" = ecu.\"idEmpresaClienteUsuario\"
                                        inner join \"Soporte\".\"Categoria\" c on t.\"idCategoria\" = c.\"idCategoria\"
                                        inner join dblink('$con2',
                                                        'SELECT nit,\"razonSocial\", \"idEmpresa\", ciudad FROM \"Empresa\".\"Empresa\"')
                                                        AS empresa(nit text,\"razonSocial\" text, \"idEmpresa\" integer, ciudad text) ON ecu.\"idEmpresaCliente\" = empresa.\"idEmpresa\"
                                        WHERE :idEmpresa = 0 OR empresa.\"idEmpresa\" = :idEmpresa
                                         GROUP BY f.nombre, c.nombre ", ["idEmpresa" => $cliente]);
            
            $categorias = DB::select("select
                                            DISTINCT c.nombre as categoria
                                        from  \"Soporte\".\"Ticket\" t
                                        inner join \"Soporte\".\"EmpresaClienteUsuario\" ecu on t.\"idEmpresaClienteUsuario\" = ecu.\"idEmpresaClienteUsuario\"
                                        inner join \"Soporte\".\"Categoria\" c on t.\"idCategoria\" = c.\"idCategoria\"
                                        inner join dblink('$con2',
                                                        'SELECT nit,\"razonSocial\", \"idEmpresa\", ciudad FROM \"Empresa\".\"Empresa\"')
                                                        AS empresa(nit text,\"razonSocial\" text, \"idEmpresa\" integer, ciudad text) ON ecu.\"idEmpresaCliente\" = empresa.\"idEmpresa\"
                                        WHERE :idEmpresa = 0 OR empresa.\"idEmpresa\" = :idEmpresa
                                        GROUP BY empresa.\"nit\",empresa.\"razonSocial\", c.nombre ", ["idEmpresa" => $cliente]);
            $usuarios = DB::select("select
                                            DISTINCT f.nombre as usuario, f.\"idFuncionario\"
                                        from  \"Soporte\".\"Ticket\" t
                                        inner join \"Soporte\".\"Funcionario\" f on t.\"idFuncionario\" = f.\"idFuncionario\"
                                        inner join \"Soporte\".\"EmpresaClienteUsuario\" ecu on t.\"idEmpresaClienteUsuario\" = ecu.\"idEmpresaClienteUsuario\"
                                        inner join \"Soporte\".\"Categoria\" c on t.\"idCategoria\" = c.\"idCategoria\"
                                        inner join dblink('$con2',
                                                        'SELECT nit,\"razonSocial\", \"idEmpresa\", ciudad FROM \"Empresa\".\"Empresa\"')
                                                        AS empresa(nit text,\"razonSocial\" text, \"idEmpresa\" integer, ciudad text) ON ecu.\"idEmpresaCliente\" = empresa.\"idEmpresa\"
                                        WHERE :idEmpresa = 0 OR empresa.\"idEmpresa\" = :idEmpresa
                                        ", ["idEmpresa" => $cliente]);

            $array = [];
            foreach($categorias as $key => $cat)
            {
                if($key == 0) {
                    $array[] = 'Usuario';
                }
                $array[] = $cat->categoria;
            }
            $array = [$array];
            $cabecera = $array[0];
            foreach($usuarios as $usu) { //Listado de empresa
                $arrRow = [];
                $arrRow[] = $usu->usuario;
                foreach($cabecera as $cat) // Categoria Cabecera
                {
                    if($cat != 'Usuario') {
                        $cantidad = DB::select("select
                                        count(t.*) cantidad
                                    from  \"Soporte\".\"Ticket\" t
                                    inner join \"Soporte\".\"EmpresaClienteUsuario\" ecu on t.\"idEmpresaClienteUsuario\" = ecu.\"idEmpresaClienteUsuario\"
                                    inner join \"Soporte\".\"Categoria\" c on t.\"idCategoria\" = c.\"idCategoria\"
                                    inner join dblink('$con2',
                                                    'SELECT nit,\"razonSocial\", \"idEmpresa\", ciudad FROM \"Empresa\".\"Empresa\"')
                                                    AS empresa(nit text,\"razonSocial\" text, \"idEmpresa\" integer, ciudad text) ON ecu.\"idEmpresaCliente\" = empresa.\"idEmpresa\"
                                    WHERE (t.\"idFuncionario\" = :idFuncionario) AND (c.nombre = :categoria)
                                    ", ["idFuncionario" => $usu->idFuncionario, "categoria"=>$cat]);
                        $arrRow[] = $cantidad[0]->cantidad;
                    }
                }
                $array[] = $arrRow;
                $arrRow =[];
            }
            return response()->json(["reporte"=>$listTicket, "grafica"=>$array]);
        }
    }
}