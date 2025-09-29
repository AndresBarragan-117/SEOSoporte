<?php

namespace App\Http\Controllers\Soporte;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\CustomController;
use Illuminate\Http\Request;
use Exception;
use App\Http\Requests\Soporte\TicketemailPlantillaValidator;
use App\Model\Soporte\TicketEmailPlantilla;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TicketEmailPlantillaController extends CustomController
{
    protected $tag = '105';
    
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
        $data = DB::table('Soporte.TicketEstado')->get();

        return $this->views("soporte.ticketEmailPlantilla.index", [
            'ticketEstado' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TicketEmailPlantillaValidator $request)
    {
        $validator = Validator::make(
                                    $request->all(), 
                                    $request->rules(),
                                    $request->messages()
        );

        if($validator->validate())
        {
            try {
                $ticketEmailPlantilla = new TicketEmailPlantilla();
                $ticketEmailPlantilla->idTicketEstado = $request->ticketEstado;
                $ticketEmailPlantilla->asunto = $request->asunto;
                $ticketEmailPlantilla->contenido = $request->contenido;
                $ticketEmailPlantilla->idUsuarioCreacion = Auth::id();
                $ticketEmailPlantilla->idUsuarioModificacion = Auth::id();
                
                $ticketEmailPlantilla->save();
            } catch(Exception $e) {
                return $this->error($request,$e);
            }
            $request->session()->flash('alert-success', 'La plantilla se guardó correctamente.');
            return redirect()->route('ticketEmailPlantilla.index');
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
        $datos = DB::table('Soporte.TicketEmailPlantilla as c')
            ->leftJoin('Soporte.TicketEstado as c2', 'c.idTicketEstado', '=','c2.idTicketEstado')
            ->select("c.idTicketEmailPlantilla","c.asunto", "c2.nombre AS ticketEstado")
            ->get();

        $ticketEstado = DB::table('Soporte.TicketEstado')->get();
        return $this->views("soporte.ticketEmailPlantilla.index",
                            [ 
                                "data" =>$datos,
                                'ticketEstado' => $ticketEstado
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
        $ticketEmailPlantilla = TicketEmailPlantilla::find($id);
        $ticketEstado = DB::table('Soporte.TicketEstado')->get();

        return $this->views("soporte.ticketEmailPlantilla.edit",[
                                        "edit"=>$ticketEmailPlantilla,
                                        'ticketEstado' => $ticketEstado
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
                            'asunto' => 'required|max:150',
                            'contenido' => 'required|max:300'
                        ],
                        [
                            'asunto.required' => 'El asunto es requerido',
                            'asunto.max' => 'El máximo permitido son 300 caracteres',
                            'contenido.required' => 'El contenido es requerido',
                            'contenido.max' => 'El máximo permitido son 5 caracteres'
                        ]);

        if ($validator->fails()) {
            return redirect('/ticketEmailPlantilla/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            $ticketEmailPlantilla = TicketEmailPlantilla::find($id);
            $ticketEmailPlantilla->idTicketEstado = $request->ticketEstado;
            $ticketEmailPlantilla->asunto = $request->asunto;
            $ticketEmailPlantilla->contenido = $request->contenido;
            $ticketEmailPlantilla->idUsuarioModificacion = Auth::id();
            $ticketEmailPlantilla->save();
        } catch (Exception $e) {
            return $this->error($request,$e);
        }
        
        $request->session()->flash('alert-success', 'La plantilla se ha modificado correctamente.');
        return redirect()->route('ticketEmailPlantilla.index');
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
        TicketEmailPlantilla::destroy($id);
        return back();
    }
}
