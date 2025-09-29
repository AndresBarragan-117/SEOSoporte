<?php

namespace App\Http\Controllers\Soporte;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\CustomController;
use Illuminate\Http\Request;
use Exception;
use App\Http\Requests\Soporte\ParametroDefectoValidator;
use App\Model\Soporte\ParametroDefecto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ParametroDefectoController extends CustomController
{
    protected $tag = '106';
    
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
        $ticketPrioridad = DB::table('Soporte.TicketPrioridad')->get();
        $ticketEstado = DB::table('Soporte.TicketEstado')->get();
        $funcionario = DB::table('Soporte.Funcionario')->get();
        
        $parametroDefecto = DB::table('Soporte.ParametroDefecto')->first();
        if(isset($parametroDefecto)) {
            return $this->views("soporte.parametroDefecto.edit",[
                                            "edit"=>$parametroDefecto,
                                            'ticketPrioridad' => $ticketPrioridad,
                                            'ticketEstado' => $ticketEstado,
                                            'funcionario' => $funcionario
                                        ]);
        } else {
            return $this->views("soporte.parametroDefecto.index", [
                'ticketPrioridad' => $ticketPrioridad,
                'ticketEstado' => $ticketEstado,
                'funcionario' => $funcionario
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ParametroDefectoValidator $request)
    {
        $validator = Validator::make(
                                    $request->all(), 
                                    $request->rules(),
                                    $request->messages()
        );

        if($validator->validate())
        {
            try {
                $parametroDefecto = new ParametroDefecto();
                $parametroDefecto->idTicketPrioridad = $request->ticketPrioridad;
                $parametroDefecto->idTicketEstado = $request->ticketEstado;
                $parametroDefecto->idFuncionario = $request->funcionario;
                $parametroDefecto->idTicketEstadoFinalizar = $request->ticketEstadoFinalizar;
                $parametroDefecto->idTicketEstadoArchivar = $request->ticketEstadoArchivar;
                $parametroDefecto->idTicketEstadoRechazar = $request->ticketEstadoRechazar;
                $parametroDefecto->diasArchivar = $request->diasArchivar;
                $parametroDefecto->idUsuarioCreacion = Auth::id();
                $parametroDefecto->idUsuarioModificacion = Auth::id();
                
                $parametroDefecto->save();
            } catch(Exception $e) {
                return $this->error($request,$e);
            }
            $request->session()->flash('alert-success', 'El parámetro se guardó correctamente.');
            return redirect()->route('parametroDefecto.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $datos = DB::table('Soporte.ParametroDefecto as c')
            ->join('Soporte.TicketPrioridad as tp', 'c.idTicketPrioridad', '=','tp.idTicketPrioridad')
            ->join('Soporte.TicketEstado as te', 'c.idTicketEstado', '=','te.idTicketEstado')
            ->join('Soporte.Funcionario as f', 'c.idFuncionario', '=','f.idFuncionario')
            ->join('Soporte.TicketEstado as tef', 'c.idTicketEstadoFinalizar', '=','tef.idTicketEstado')
            ->join('Soporte.TicketEstado as tea', 'c.idTicketEstadoArchivar', '=','tea.idTicketEstado')
            ->join('Soporte.TicketEstado as ter', 'c.idTicketEstadoRechazar', '=','ter.idTicketEstado')
            ->select("c.idParametroDefecto","tp.nombre as ticketPrioridad", "te.nombre AS ticketEstado", "f.nombre AS funcionario", "tef.nombre AS estadoFinalizar", "tea.nombre AS estadoArchivar", "ter.nombre AS estadoRechazar", "c.diasArchivar")
            ->get();

        $ticketPrioridad = DB::table('Soporte.TicketPrioridad')->get();
        $ticketEstado = DB::table('Soporte.TicketEstado')->get();
        $funcionario = DB::table('Soporte.Funcionario')->get();

        return $this->views("soporte.parametroDefecto.index",
                            [ 
                                "data" =>$datos,
                                'ticketPrioridad' => $ticketPrioridad,
                                'ticketEstado' => $ticketEstado,
                                'funcionario' => $funcionario
                            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $parametroDefecto = ParametroDefecto::find($id);

        $ticketPrioridad = DB::table('Soporte.TicketPrioridad')->get();
        $ticketEstado = DB::table('Soporte.TicketEstado')->get();
        $funcionario = DB::table('Soporte.Funcionario')->get();

        return $this->views("soporte.parametroDefecto.edit",[
                                        "edit"=>$parametroDefecto,
                                        'ticketPrioridad' => $ticketPrioridad,
                                        'ticketEstado' => $ticketEstado,
                                        'funcionario' => $funcionario
                                     ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),
                        [
                            'ticketPrioridad' => 'required',
                            'ticketEstado' => 'required',
                            'funcionario' => 'required',
                            'ticketEstadoFinalizar' => 'required',
                            'ticketEstadoArchivar' => 'required',
                            'ticketEstadoRechazar' => 'required',
                            'diasArchivar' => 'required|max:5|regex:/^[0-9]+$/'
                        ],
                        [
                            'ticketPrioridad.required' => 'La prioridad del ticket es requerida',
                            'ticketEstado.required' => 'El estado del ticket es requerido',
                            'funcionario.required' => 'El funcionario es requerido',
                            'ticketEstadoFinalizar.required' => 'El estado de finalización es requerido',
                            'ticketEstadoArchivar.required' => 'El estado de archivar es requerido',
                            'ticketEstadoRechazar.required' => 'El estado de rechazar es requerido',
                            'diasArchivar.required' => 'Los días a archivar es requerido',
                            'diasArchivar.max' => 'El máximo permitido son 5 caracteres'
                        ]);

        if ($validator->fails()) {
            return redirect('/parametroDefecto/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            $parametroDefecto = ParametroDefecto::find($id);
            $parametroDefecto->idTicketPrioridad = $request->ticketPrioridad;
            $parametroDefecto->idTicketEstado = $request->ticketEstado;
            $parametroDefecto->idFuncionario = $request->funcionario;
            $parametroDefecto->idTicketEstadoFinalizar = $request->ticketEstadoFinalizar;
            $parametroDefecto->idTicketEstadoArchivar = $request->ticketEstadoArchivar;
            $parametroDefecto->idTicketEstadoRechazar = $request->ticketEstadoRechazar;
            $parametroDefecto->diasArchivar = $request->diasArchivar;
            $parametroDefecto->idUsuarioModificacion = Auth::id();
            $parametroDefecto->save();
        } catch (Exception $e) {
            return $this->error($request,$e);
        }
        
        $request->session()->flash('alert-success', 'El parámetro se ha modificado correctamente.');
        return redirect()->route('parametroDefecto.index');
        /**/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ParametroDefecto::destroy($id);
        return back();
    }
}