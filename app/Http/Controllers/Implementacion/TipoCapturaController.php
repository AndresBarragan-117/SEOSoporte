<?php

namespace App\Http\Controllers\Implementacion;

use Illuminate\Support\Facades\DB;
use App\Model\Implementacion\TipoCaptura;
use App\Http\Controllers\CustomController;
use Illuminate\Http\Request;
use Exception;
use App\Http\Requests\Implementacion\TipoCapturaValidator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TipoCapturaController extends CustomController
{
    protected $tag = '300';
    
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
        return $this->views("implementacion.tipoCaptura.index");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TipoCapturaValidator $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nombre' => 'required|max:250',
                'descripcion' => 'required|max:250',
                'estado' => 'required',
            ],
            [
                'nombre.required' => 'El nombre es requerido',
                'nombre.max' => 'El máximo permitido son 250 caracteres',
                'nombre.regex' => 'Sólo se aceptan letras',
                'descripcion.required' => 'La descripción es requerido',
                'descripcion.max' => 'El máximo permitido son 250 caracteres',
                'descripcion.regex' => 'Sólo se aceptan letras',
                'estado.required' => 'El estado es requerido',
            ], 
            $request->rules(),
            $request->messages());

        if($validator->validate())
        {
            try {
                $tipoCaptura = new TipoCaptura();
                $tipoCaptura->nombre = $request->nombre;
                $tipoCaptura->descripcion = $request->descripcion;
                $tipoCaptura->estado = isset($request->estado) ? $request->estado : false;
                $tipoCaptura->idUsuarioCreacion = Auth::id();
                $tipoCaptura->idUsuarioModificacion = Auth::id();
                $tipoCaptura->save();
            } catch(Exception $e) {
                return $this->error($request,$e);
            }
            $request->session()->flash('alert-success', 'El Tipo Captura se guardó correctamente.');
            return redirect()->route('tipoCaptura.index');

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
        $datos = DB::table('Implementacion.TipoCaptura as c')
                        ->get();
        return $this->views("Implementacion.TipoCaptura.index",
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
        $tipoCaptura = TipoCaptura::find($id);

        return $this->views("Implementacion.TipoCaptura.edit",[
                                    "edit"=>$tipoCaptura
                                     ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // funcion para actualizar un registro existente de tipo de captura
    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nombre' => 'required|max:250',
                'descripcion' => 'required|max:250',
                'estado' => 'required',
            ],
            [
                'nombre.required' => 'El nombre es requerido',
                'nombre.max' => 'El máximo permitido son 250 caracteres',
                'nombre.regex' => 'Sólo se aceptan letras',
                'descripcion.required' => 'La descripción es requerido',
                'descripcion.max' => 'El máximo permitido son 250 caracteres',
                'descripcion.regex' => 'Sólo se aceptan letras',
                'estado.required' => 'El estado es requerido',
            ]);
        
        if ($validator->fails()) {
            return redirect('/tipoCaptura/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            $tipoCaptura = TipoCaptura::find($id);
            $tipoCaptura->nombre = $request->nombre;
            $tipoCaptura->descripcion = $request->descripcion;
            $tipoCaptura->estado = $request->estado;
            $tipoCaptura->idUsuarioModificacion = Auth::id();
            $tipoCaptura->save();
        } catch (Exception $e) {
            return $this->error($request,$e);
        }

        $request->session()->flash('alert-success', 'El Tipo Captura se ha modificado correctamente.');
        return redirect()->route('tipoCaptura.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $tipoCaptura = TipoCaptura::find($id);

            if (!$tipoCaptura) {
                return back()->with('alert-danger', 'No se encontro el Tipo Captura al eliminar.');
            }

            $nombre = $tipoCaptura->nombre;
            $tipoCaptura->delete();

            return back()->with('alert-success', "El Tipo Captura '$nombre' se ha eliminado correctamente.");
        } catch (Exception $e) {
            return back()->with('alert-danger', 'Error al eliminar el Tipo Captura. ' . $e->getMessage());
        }
    }
}