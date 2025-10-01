<?php

namespace App\Http\Controllers\Implementacion;

use Illuminate\Support\Facades\DB;
use App\Model\Implementacion\Plantilla;
use App\Http\Controllers\CustomController;
use Illuminate\Http\Request;
use Exception;
use App\Http\Requests\Implementacion\PlantillaValidator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PlantillaController extends CustomController
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
    // funcion para mostrar la lista de plantillas
    public function index()
    {
        $resultado = DB::select("Select \"idTipoPlantilla\",nombre from \"Implementacion\".\"TipoPlantilla\"");
        return $this->views("Implementacion.Plantilla.index",
        [
            "listadoTipoPlantilla"=> $resultado
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // funcion para guarda un nuevo registro de plantilla
    public function store(PlantillaValidator $request)
    {
        $validator = Validator::make(
            $request->all(), 
            $request->rules(),
            $request->messages());

        if($validator->validate())
        {
            try {
                $plantilla = new Plantilla();
                $plantilla->nombre = $request->nombre;
                $plantilla->descripcion = $request->descripcion;
                $plantilla->estado = isset($request->estado) ? $request->estado : false;
                $plantilla->idUsuarioCreacion = Auth::id();
                $plantilla->idUsuarioModificacion = Auth::id();
                $plantilla->idTipoPlantilla = $request->listadoTipoPlantilla;
                $plantilla->save();
            } catch(Exception $e) {
                return $this->error($request,$e);
            }
            $request->session()->flash('alert-success', 'La plantilla se guardó correctamente.');
            return redirect()->route('plantilla.index');

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // funcion para consultar todas las plantillas
    public function show()
    {
        $datos = DB::table('Implementacion.Plantilla as c')
                        ->orderBy('idPlantilla', 'asc')
                        ->get();
        $resultado = DB::select("Select \"idTipoPlantilla\",nombre from \"Implementacion\".\"TipoPlantilla\"");
        return $this->views("implementacion.plantilla.index",
                            [
                                "data" =>$datos,
                                "listadoTipoPlantilla" =>$resultado
                            ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // funcion para buscar un registro específico de plantilla
    public function edit($id)
    {
        $plantilla = Plantilla::find($id);
        $resultado = DB::select("Select \"idTipoPlantilla\",nombre from \"Implementacion\".\"TipoPlantilla\"");
        return $this->views("implementacion.plantilla.edit",[
                                    "edit"=>$plantilla,
                                    "listadoTipoPlantilla"=>$resultado
                                     ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // funcion para actualizar un registro existente de plantilla
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), 
            [
                'nombre' => 'required|max:250|regex:/^[aA-zZ \-0-9óíúáñé]+$/i',
                'descripcion' => 'required|max:250|regex:/^[aA-zZ \-0-9óíúáñé \.]+$/i',
            ],
            [
                'nombre.required' => 'El nombre es requerido',
                'nombre.max' => 'El máximo permitido son 250 caracteres',
                'nombre.regex' => 'Sólo se aceptan letras',
                'descripcion.required' => 'La descripción es requerido',
                'descripcion.max' => 'El máximo permitido son 250 caracteres',
                'descripcion.regex' => 'Sólo se aceptan letras',
            ]);
        
        if ($validator->fails()) {
            return redirect('/plantilla/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            $plantilla = Plantilla::find($id);
            $plantilla->nombre = $request->nombre;
            $plantilla->descripcion = $request->descripcion;
            $plantilla->estado = $request->estado;
            $plantilla->idUsuarioModificacion = Auth::id();
            $plantilla->idTipoPlantilla = $request->listadoTipoPlantilla;
            $plantilla->save();
        } catch (Exception $e) {
            return $this->error($request,$e);
        }
        $request->session()->flash('alert-success', 'La plantilla se ha modificado correctamente.');
        return redirect()->route('plantilla.index');
        /**/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
    //     Plantilla::destroy($id);
    //     return back();
    // }
    // funcion para eliminar una plantilla
    public function destroy($id)
    {
        $plantilla = Plantilla::find($id);
        if ($plantilla) {
            $nombre = $plantilla->nombre;
            $plantilla->delete();
            return back()->with('alert-success', "La plantilla '$nombre' fue eliminada correctamente.");
        }
        return back()->with('alert-danger', "No se encontró la plantilla a eliminar.");
    }

    // funcion para obtener las plantillas por tipo de plantilla
    public function obtenerPlantilla($idTipoPlantilla) {
        $resultado = DB::select("SELECT 
                                    \"idPlantilla\",
                                    nombre,
                                    descripcion
                                FROM \"Implementacion\".\"Plantilla\"
                                WHERE estado = TRUE AND \"idTipoPlantilla\" = ?
                                order by \"idPlantilla\"", [$idTipoPlantilla]);
        return response()->json(["plantillas"=>$resultado]);
    }
}