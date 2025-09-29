<?php

namespace App\Http\Controllers\Seguridad;

use Illuminate\Support\Facades\DB;
use App\Model\Seguridad\Rol;
use App\Http\Controllers\CustomController;
use Illuminate\Http\Request;
use App\Http\Requests\Seguridad\RolValidator;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Auth;

class RolController extends CustomController
{
    protected $tag = '3';
    
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
        $datos = DB::table('Seguridad.Formulario as f')
                    ->get();
        return $this->views("seguridad.rol.index",
                            [
                                "formularios" =>$datos
                            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RolValidator $request)
    {
        //dd($request->estado);
        $formularios = $request->input("form");
        $validator = Validator::make(
                                    $request->all(), 
                                    $request->rules(),
                                    $request->messages()
        );

        $rol = new Rol();
        if($validator->validate())
        {
            DB::beginTransaction();
            try {
                $rol->nombre = $request->nombre;
                $rol->descripcion = $request->descripcion;
                $rol->idUsuarioCreacion = Auth::id();
                $rol->idUsuarioModificacion = Auth::id();
                if($request->estado == "1")
                {
                    $rol->estado = true;   
                } else {
                    $rol->estado = false;
                }
                $rol->save();
                $values = array();
                if(isset($formularios)) {
                    foreach ($formularios as $value) {
                        $values[] = array('idRol' => $rol->idRol,'idFormulario' => $value, 'idUsuarioCreacion' => Auth::id(), 'idUsuarioModificacion' => Auth::id());
                    }
                    DB::table('Seguridad.RolFormulario')->insert($values);
                }
                DB::commit();
            } catch(Exception $e) {
                DB::rollback();
                return $this->error($request,$e);
            }
            $request->session()->flash('alert-success', 'El rol se guardó correctamente.');
            return $this->views("seguridad.rol.index",["rol"=>$this->cargarRol()]);
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
        return $this->views("seguridad.rol.index",
                            [
                                "data"=>$this->cargarRol()
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
        $rol = Rol::find($id);

        // Formularios Por Rol
        $formulariosRol = DB::select('select
                                        f."idFormulario",
                                        f.nombre,
                                        CASE WHEN (SELECT count(*) from "Seguridad"."RolFormulario" rf where rf."idFormulario" = f."idFormulario" AND rf."idRol" = ?) > 0 THEN 
                                                1
                                            ELSE 
                                                0
                                       END as seleccionar
                                    from "Seguridad"."Formulario" f', array($id));

        //$datos = DB::table('Seguridad.Formulario as f')
                            //->get();
        return $this->views("seguridad.rol.edit",[
                                    "edit"=>$rol,
                                    "rol"=>$this->cargarRol(),
                                    "formularios"=>$formulariosRol
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
            	'nombre' => 'required|max:250|regex:/^[aA-zZ -]+$/i',
                'descripcion' => 'required|max:250|regex:/^[aA-zZ -]+$/i',
            ],
            [
            	'nombre.required' => 'La descripción es requerido',
                'nombre.max' => 'El máximo permitido son 250 caracteres',
                'nombre.regex' => 'Sólo se aceptan letras',
                'descripcion.required' => 'La descripción es requerido',
                'descripcion.max' => 'El máximo permitido son 250 caracteres',
                'descripcion.regex' => 'Sólo se aceptan letras',
            ]);
 
        if ($validator->fails()) {
            return redirect('/rol/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }

        DB::beginTransaction();
        $formularios = $request->input("form");
        try {
            DB::table('Seguridad.RolFormulario')->where('idRol', '=', $id)->delete();

            $values = array();
            if(isset($formularios)) {
                foreach ($formularios as $value) {
                    $values[] = array('idRol' => $id,'idFormulario' => $value, 'idUsuarioCreacion' => Auth::id(), 'idUsuarioModificacion' => Auth::id());
                }
                DB::table('Seguridad.RolFormulario')->insert($values);
            }

            $rol = Rol::find($id);
            $rol->nombre = $request->nombre;
            $rol->idUsuarioModificacion = Auth::id();
            $rol->descripcion = $request->descripcion;
            if(isset($request->estado))
            {
                $rol->estado = true;   
            } else {
                $rol->estado = false;
            }
            $rol->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return $this->error($request,$e);
        }
        $request->session()->flash('alert-success', 'El Rol se ha modificado correctamente.');
        return redirect()->route('rol.index');
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
        DB::table('Seguridad.RolFormulario')->where('idRol', '=', $id)->delete();
        Rol::destroy($id);
        return back();
    }

    private function cargarRol()
    {
        return Rol::all();
    }
}
