<?php

namespace App\Http\Controllers\Implementacion;
            
use Illuminate\Support\Facades\DB;
use App\Model\Implementacion\PlantillaLista;
use App\Model\Implementacion\Plantilla;
use App\Model\Implementacion\TipoCaptura;
use App\Http\Controllers\CustomController;
use Illuminate\Http\Request;
use App\Http\Requests\Implementacion\PlantillaListaValidator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class PlantillaListaController extends CustomController
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
        $listaPlantilla = DB::table("Implementacion.Plantilla")->orderBy('idPlantilla', 'asc')->get();
        $listaTipoCaptura = DB::table("Implementacion.TipoCaptura")->orderBy('idTipoCaptura', 'asc')->get();
        return $this->views("Implementacion.PlantillaLista.index",
        [
            "listaPlantilla"=> $listaPlantilla,
            "listaTipoCaptura"=>$listaTipoCaptura
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // funcion para guarda un nuevo registro de lista de plantilla
    public function store(PlantillaListaValidator $request)
    {
        
        // validar los datos enviados por el formulario 
        $validator = Validator::make($request->all(), 
            [
                'nombre' => 'required|max:250',
                'descripcion' => 'required|max:250',
                'listaPlantilla' => 'required',
                'numeroOrdenLista' => 'required|numeric',
                'listaTipoCaptura' => 'required',
                'opcionTipoCaptura' => 'required',
            ],
            [
                'nombre.required' => 'El nombre es requerido',
                'nombre.max' => 'El máximo permitido son 250 caracteres',
                'nombre.regex' => 'Sólo se aceptan letras',
                'descripcion.required' => 'La descripción es requerido',
                'descripcion.max' => 'El máximo permitido son 250 caracteres',
                'descripcion.regex' => 'Sólo se aceptan letras',
                'listaPlantilla.required' => 'Seleccione la Plantilla',
                'numeroOrdenLista.required' => 'El número de orden es requerido',
                'numeroOrdenLista.numeric' => 'El número de orden debe ser numérico',
                'listaTipoCaptura.required' => 'Seleccione el tipo de captura',
                'opcionTipoCaptura.required' => 'Seleccione la opción de tipo de captura',
            ]);

        if($validator->validate())
        {
            try {
                $plantillaLista = new PlantillaLista();
                $plantillaLista->nombre = $request->nombre;
                $plantillaLista->descripcion = $request->descripcion;
                $plantillaLista->idPlantilla = $request->listaPlantilla;
                $plantillaLista->numeroOrdenLista = $request->numeroOrdenLista;
                $plantillaLista->idTipoCaptura = $request->listaTipoCaptura;
                $plantillaLista->opcionTipoCaptura = $request->opcionTipoCaptura;
                $plantillaLista->estado = isset($request->estado) ? $request->estado : false;
                $plantillaLista->idUsuarioCreacion = Auth::id();
                $plantillaLista->idUsuarioModificacion = Auth::id();
                $plantillaLista->save();
              
            } catch(Exception $e) {
                return back()->with('alert-danger', $e->getMessage())->withInput();
            }

            // enviar mensaje de exito
            $request->session()->flash('alert-success', 'La Lista de la Plantilla se guardó correctamente.');
            return redirect()->route('plantillaLista.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // funcion para consultar todas las listas de plantilla
    public function show()
    {
        
        $datos = DB::table('Implementacion.PlantillaLista as pl')
            ->leftJoin("Implementacion.Plantilla as p", "pl.idPlantilla",'=', "p.idPlantilla")
            ->leftJoin("Implementacion.TipoCaptura as tc", "pl.idTipoCaptura",'=', "tc.idTipoCaptura")
            ->select("pl.idPlantillaLista","pl.nombre","pl.descripcion", "pl.idPlantilla", "pl.numeroOrdenLista", "pl.idTipoCaptura", "pl.opcionTipoCaptura", "pl.estado", "p.nombre as dPlantilla", "tc.nombre as dTipoCaptura")
            ->orderBy('pl.nombre', 'asc')->get();
            
        return $this->views("Implementacion.PlantillaLista.index",
                            [
                                "listaPlantilla"=> $datos,
                                "listaTipoCaptura"=>$datos,
                                "data"=> $datos
                            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // funcion para buscar un registro específico de lista de plantilla
    public function edit($id)
    {
        $plantillaLista = PlantillaLista::find($id);
        $listaPlantilla = DB::table("Implementacion.Plantilla")->orderBy('idPlantilla', 'asc')->get();
        $listaTipoCaptura = DB::table("Implementacion.TipoCaptura")->orderBy('idTipoCaptura', 'asc')->get();
        return $this->views("Implementacion.PlantillaLista.edit",[
                                    "edit"=>$plantillaLista,
                                    "listaPlantilla"=> $listaPlantilla,
                                    "listaTipoCaptura"=>$listaTipoCaptura
                                     ]);                                               
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // funcion para actualizar un registro existente de lista de plantilla
    public function update(Request $request, $id)
    {
        
        $validator = Validator::make($request->all(), 
            [
                'nombre' => 'required|max:250',
                'descripcion' => 'required|max:250',
                'listaPlantilla' => 'required',
                'numeroOrdenLista' => 'required|numeric',
                'listaTipoCaptura' => 'required',
                'opcionTipoCaptura' => 'required',
            ],
            [
                'nombre.required' => 'El nombre es requerido',
                'nombre.max' => 'El máximo permitido son 250 caracteres',
                'nombre.regex' => 'Sólo se aceptan letras',
                'descripcion.required' => 'La descripción es requerido',
                'descripcion.max' => 'El máximo permitido son 250 caracteres',
                'descripcion.regex' => 'Sólo se aceptan letras',
                'listaPlantilla.required' => 'Seleccione la Plantilla',
                'numeroOrdenLista.required' => 'El número de orden es requerido',
                'numeroOrdenLista.numeric' => 'El número de orden debe ser numérico',
                'listaTipoCaptura.required' => 'Seleccione el tipo de captura',
                'opcionTipoCaptura.required' => 'Seleccione la opción de tipo de captura',
            ]);
        
        if ($validator->fails()) {
            return redirect('/plantillaLista/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }
        
        try {
            $plantillaLista = PlantillaLista::find($id);
            $plantillaLista->nombre = $request->nombre;
            $plantillaLista->descripcion = $request->descripcion;
            $plantillaLista->idPlantilla = $request->listaPlantilla;
            $plantillaLista->numeroOrdenLista = $request->numeroOrdenLista;
            $plantillaLista->idTipoCaptura = $request->listaTipoCaptura;
            $plantillaLista->opcionTipoCaptura = $request->opcionTipoCaptura;
            $plantillaLista->estado = $request->estado;
            $plantillaLista->idUsuarioModificacion = Auth::id();
            $plantillaLista->save();

            $request->session()->flash('alert-success', 'La Lista de la Plantilla se ha modificado correctamente.');
            return redirect()->route('plantillaLista.index');

        } catch (Exception $e) {
            return back()->with('alert-danger', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // funcion para eliminar una lista de plantilla
    public function destroy($id)
    {

        $plantilla = PlantillaLista::find($id);

        if ($plantilla) {
            $nombre = $plantilla->nombre; // Guardamos el nombre antes de borrarlo
            $plantilla->delete();

            return redirect()->back()->with('alert-success', "La Lista de la Plantilla '$nombre' fue eliminada correctamente.");
        }

        return redirect()->back()->with('alert-danger', "No se encontró el registro a eliminar.");
    }
    
    // funcion para cargar una lista de plantilla
    public function cargarPlantillaLista($idEmpresa, $idPlantilla) 
    {
        
        $resultado = DB::select('SELECT
                                        COALESCE(ie.valor, null) as valor, COALESCE(TO_CHAR(ie."fechaRealiza":: DATE, \'dd/mm/yyyy\'), null) as "fechaRealiza", COALESCE(ie.observacion, null) as observacion,tc.nombre as "codigoTipoCaptura",
                                        pl.*
                                    FROM "Implementacion"."Plantilla" p
                                    INNER JOIN "Implementacion"."PlantillaLista" pl ON p."idPlantilla" = pl."idPlantilla"
                                    INNER JOIN "Implementacion"."TipoCaptura" tc ON pl."idTipoCaptura" = tc."idTipoCaptura"
                                    LEFT JOIN "Implementacion"."ImplementacionEmpresa" ie ON pl."idPlantillaLista" = ie."idPlantillaLista" 
                                                                                            AND ie."idEmpresa" = ? AND ie."idPlantilla" = p."idPlantilla"
                                    WHERE p.estado = TRUE AND pl."idPlantilla" = ?
                                    ORDER BY CAST(pl."numeroOrdenLista" as INTEGER)', [$idEmpresa, $idPlantilla]);
        return response()->json(["plantillaLista" => $resultado]);
    }
}