<?php

namespace App\Http\Controllers\Seguridad;

use Illuminate\Support\Facades\DB;
use App\Model\Seguridad\Formulario;
use App\Model\Seguridad\Carpeta;
use App\Http\Controllers\CustomController;
use Illuminate\Http\Request;
use App\Http\Requests\Seguridad\FormularioValidator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Exception;

class FormularioController extends CustomController
{
    protected $tag = '4';
    
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
        return $this->views("seguridad.formulario.index",["carpeta"=>$this->cargarCarpeta()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FormularioValidator $request)
    {
        //$this->validate($request);


            $validator = Validator::make(
                                        $request->all(), 
                                        $request->rules(),
                                        $request->messages()
            );

            if($validator->validate())
            {
                try {
                    $formulario = new Formulario();
                    $formulario->nombre = $request->nombre;
                    $formulario->path = $request->path;
                    $formulario->tag = $request->tag;
                    $formulario->widget = false;
                    $formulario->idUsuarioCreacion = Auth::id();
                    $formulario->idUsuarioModificacion = Auth::id();
                    $formulario->idCarpeta = $request->carpeta;
                    $formulario->estado = isset($request->estado)? true: false;
                    $formulario->save();
                } catch(Exception $e) {
                    return $this->error($request,$e);
                }
                $request->session()->flash('alert-success', 'El formulario se guardó correctamente.');
                return redirect()->route('formulario.index');

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

        $datos = DB::table('Seguridad.Formulario as f')
            ->leftJoin("Seguridad.Carpeta as cp", "f.idCarpeta",'=', "cp.idCarpeta")
            ->select("f.idFormulario","f.nombre","f.path", "f.tag","f.estado", "cp.descripcion as carpeta")
            ->get();

        return $this->views("seguridad.formulario.index",
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

        $formulario = Formulario::find($id);

        return $this->views("seguridad.formulario.edit",[
										"edit"=>$formulario,
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
    public function update(FormularioValidator $request, $id)
    {
		       
         $validator = Validator::make($request->all(), 
									  $request->rules(),
									  $request->messages());
        
 
        if ($validator->validate()) {
			try {
				$formulario = Formulario::find($id);
				$formulario->nombre = $request->nombre;
				$formulario->path = $request->path;
                $formulario->idCarpeta = $request->carpeta;
                $formulario->idUsuarioModificacion = Auth::id();
				$formulario->estado = isset($request->estado)? true: false;
				$formulario->save();
				
				$request->session()->flash('alert-success', 'El formulario se ha modificado correctamente.');
				return redirect()->route('formulario.index');
			} catch (Exception $e) {
				return $this->error($request,$e);
			}
        }
        
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
        Carpeta::destroy($id);
        return back();
    }

    private function cargarCarpeta()
    {
        return Carpeta::all();

    }
}