<?php

namespace App\Http\Controllers\Soporte;

use Illuminate\Support\Facades\DB;
use App\Model\Soporte\TicketEstado;
use App\Http\Controllers\CustomController;
use Illuminate\Http\Request;
use Exception;
use App\Http\Requests\Soporte\TicketEstadoValidator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TicketEstadoController extends CustomController
{
    protected $tag = '104';
    
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
        return $this->views("soporte.ticketEstado.index");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TicketEstadoValidator $request)
    {
        $validator = Validator::make(
                                    $request->all(), 
                                    $request->rules(),
                                    $request->messages()
        );

        if($validator->validate())
        {
            try {
                $ticketEstado = new TicketEstado();
                $ticketEstado->nombre = $request->nombre;
                $ticketEstado->orden = $request->orden;
                $ticketEstado->color = $request->color;
                $ticketEstado->idUsuarioCreacion = Auth::id();
                $ticketEstado->idUsuarioModificacion = Auth::id();
                $ticketEstado->save();
            } catch(Exception $e) {
                return $this->error($request,$e);
            }
            $request->session()->flash('alert-success', 'El registro se guardó correctamente.');
            return redirect()->route('ticketEstado.index');
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
        $datos = DB::table('Soporte.TicketEstado as c')->get();
        return $this->views("soporte.ticketEstado.index",
                            [ 
                                "data" =>$datos
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
        $ticketestado = TicketEstado::find($id);
        
        return $this->views("soporte.ticketEstado.edit",[
                                    "edit"=>$ticketestado
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
                'nombre' => 'required|max:300',
                'orden' => 'required|max:5|regex:/^[0-9]+$/'
            ],
            [
                'nombre.required' => 'El nombre es requerido',
                'nombre.max' => 'El máximo permitido son 300 caracteres',
                'orden.required' => 'El orden es requerido',
                'orden.max' => 'El máximo permitido son 5 caracteres',
            ]);

        if ($validator->fails()) {
            return redirect('/ticketEstado/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            $ticketEstado = TicketEstado::find($id);
            $ticketEstado->nombre = $request->nombre;
            $ticketEstado->orden = $request->orden;
            $ticketEstado->color = $request->color;
            $ticketEstado->idUsuarioModificacion = Auth::id();
            $ticketEstado->save();
        } catch (Exception $e) {
            return $this->error($request,$e);
        }
        
        $request->session()->flash('alert-success', 'El registro se ha modificado correctamente.');
        return redirect()->route('ticketEstado.index');
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
        TicketEstado::destroy($id);
        return back();
    }
}
