<?php

namespace App\Http\Controllers\Soporte;

use Illuminate\Support\Facades\DB;
use App\Model\Soporte\TicketPrioridad;
use App\Http\Controllers\CustomController;
use Illuminate\Http\Request;
use Exception;
use App\Http\Requests\Soporte\TicketPrioridadValidator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TicketPrioridadController extends CustomController
{
    protected $tag = '103';
    
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
        return $this->views("soporte.ticketPrioridad.index");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TicketPrioridadValidator $request)
    {
        $validator = Validator::make(
                                    $request->all(), 
                                    $request->rules(),
                                    $request->messages()
        );

        if($validator->validate())
        {
            try {
                $ticketPrioridad = new TicketPrioridad();
                $ticketPrioridad->nombre = $request->nombre;
                $ticketPrioridad->orden = $request->orden;
                $ticketPrioridad->idUsuarioCreacion = Auth::id();
                $ticketPrioridad->idUsuarioModificacion = Auth::id();
                $ticketPrioridad->save();
            } catch(Exception $e) {
                return $this->error($request,$e);
            }
            $request->session()->flash('alert-success', 'El registro se guardó correctamente.');
            return redirect()->route('ticketPrioridad.index');

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
        $datos = DB::table('Soporte.TicketPrioridad as c')->get();
        return $this->views("soporte.ticketPrioridad.index",
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
        $ticketPrioridad = TicketPrioridad::find($id);
        
        return $this->views("soporte.ticketPrioridad.edit",[
                                    "edit"=>$ticketPrioridad
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
            return redirect('/ticketPrioridad/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            $ticketPrioridad = TicketPrioridad::find($id);
            $ticketPrioridad->nombre = $request->nombre;
            $ticketPrioridad->orden = $request->orden;
            $ticketPrioridad->idUsuarioModificacion = Auth::id();
            $ticketPrioridad->save();
        } catch (Exception $e) {
            return $this->error($request,$e);
        }
        
        $request->session()->flash('alert-success', 'El registro se ha modificado correctamente.');
        return redirect()->route('ticketPrioridad.index');
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
        TicketPrioridad::destroy($id);
        return back();
    }
}
