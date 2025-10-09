<?php

namespace App\Http\Controllers\Seguridad;

use Illuminate\Support\Facades\DB;
use App\Model\Seguridad\Carpeta;
use App\Http\Controllers\CustomController;
use Illuminate\Http\Request;
use Exception;
use App\Http\Requests\Seguridad\CarpetaValidator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CarpetaController extends CustomController
{

    protected $tag = '2';
    
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
        return $this->views("seguridad.carpeta.index",
                            [
                                "carpeta"=>$this->cargarCarpeta(), 
                            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CarpetaValidator $request)
    {
        $validator = Validator::make(
                                    $request->all(), 
                                    $request->rules(),
                                    $request->messages()
        );

        if($validator->validate())
        {
            try {
                $carpeta = new Carpeta();
                $carpeta->descripcion = $request->descripcion;
                $carpeta->idUsuarioCreacion = Auth::id();
                $carpeta->idUsuarioModificacion = Auth::id();
                $carpeta->idPadre = $request->carpetaPadre != ""?$request->carpetaPadre:null;
                $carpeta->save();
            } catch(Exception $e) {
                return $this->error($request,$e);
            }
            $request->session()->flash('alert-success', 'La carpeta se guardó correctamente.');
            return redirect()->route('carpeta.index');

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

        $datos = DB::table('Seguridad.Carpeta as c')
            ->leftJoin("Seguridad.Carpeta as cp", "c.idPadre",'=', "cp.idCarpeta")
            ->select("c.idCarpeta","c.descripcion", "cp.descripcion as carpetaPadre")
            ->get();
        //Carpeta::leftJoin('Seguridad.Carpeta as c', '')

        return $this->views("seguridad.carpeta.index",
                            [
                                "carpeta"=>$this->cargarCarpeta(), 
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

        $carpeta = Carpeta::find($id);

        return $this->views("seguridad.carpeta.edit",[
                                    "edit"=>$carpeta,
                                    "carpeta"=>$this->cargarCarpeta(),
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
        //$this->validate($request);
        $validator = Validator::make($request->all(), 
            [
                'descripcion' => 'required|max:250|regex:/^[aA-zZ -]+$/i',
            ],
            [
                'descripcion.required' => 'La descripción es requerido',
                'descripcion.max' => 'El máximo permitido son 250 caracteres',
                'descripcion.regex' => 'Sólo se aceptan letras',
            ]);
        
        if ($validator->fails()) {
            return redirect('/carpeta/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            $carpeta = Carpeta::find($id);
            
            if (!$carpeta) {
                return back()->with('alert-danger', 'No se encontró la carpeta a modificar.');
            }
            
            $carpeta->descripcion = $request->descripcion;
            $carpeta->idUsuarioModificacion = Auth::id();
            $carpeta->idPadre = $request->carpetaPadre != ""?$request->carpetaPadre:null;
            $carpeta->save();
        } catch (Exception $e) {
            return $this->error($request,$e);
        }
        $request->session()->flash('alert-success', 'La carpeta se ha modificado correctamente.');
        return redirect()->route('carpeta.index');
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
        try {
            $carpeta = Carpeta::find($id);

            if (!$carpeta) {
                return back()->with('alert-danger', 'No se encontró la carpeta al eliminar.');
            }

            $nombre = $carpeta->descripcion;
            $carpeta->delete();

            return back()->with('alert-success', "La carpeta '$nombre' se ha eliminado correctamente.");
        } catch (Exception $e) {
            return back()->with('alert-danger', 'Error al eliminar la carpeta. ' . $e->getMessage());
        }
    }

    private function cargarCarpeta()
    {
        return Carpeta::all();
    }
}
