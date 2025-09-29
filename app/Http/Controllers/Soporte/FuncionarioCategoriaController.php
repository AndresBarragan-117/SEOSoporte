<?php

namespace App\Http\Controllers\Soporte;

use App\FuncionarioCategoria as AppFuncionarioCategoria;
use Illuminate\Support\Facades\DB;
use App\Model\Soporte\FuncionarioCategoria;
use App\Http\Controllers\CustomController;
use Illuminate\Http\Request;
use Exception;
use App\Http\Requests\Soporte\FuncionarioCategoriaValidator;
use App\Model\Soporte\Categoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FuncionarioCategoriaController extends CustomController
{
    protected $tag = '101';
    
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
         $usuarios = DB::table('users as c')
                ->get();

        return $this->views("soporte.funcionarioCategoria.index",[
                                "usuarios"=>$usuarios,
                                "categorias"=>$this->cargarCategoria()
                        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $categorias = $request->input("form");
        /*$validator = Validator::make(
                                    $request->all(), 
                                    $request->rules(),
                                    $request->messages()
        );*/
        //if($validator->validate())
        {
            try {
                if(isset($categorias)) {
                    foreach ($categorias as $value) {
                        $values[] = array('idFuncionario'=> $request->funcionario, 'idCategoria' => $value, 'idUsuarioCreacion' => Auth::id(), 'idUsuarioModificacion' => Auth::id());
                    }
                    DB::table('Soporte.FuncionarioCategoria')->insert($values);
                }
            } catch(Exception $e) {
                return $this->error($request,$e);
            }
            $request->session()->flash('alert-success', 'La asignación se guardó correctamente.');
            return redirect()->route('funcionarioCategoria.index');
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
        $funcionarios = DB::table('users as c')
            ->get();

        return $this->views("soporte.funcionarioCategoria.index",
                            [ 
                                "funcionarios" =>$funcionarios
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
        $funcionarioCategoria = AppFuncionarioCategoria::find($id);
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
        return $this->views("soporte.funcionarioCategoria.edit",[
                                    "edit"=>$funcionarioCategoria,
                                    "categorias"=>$this->cargarCategoria()()
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
                'codigo' => 'required|max:20',
                'nombre' => 'required|max:250|regex:/^[aA-zZ -]+$/i',
            ],
            [
                'codigo.required' => 'El código es requerido',
                'codigo.max' => 'El máximo permitido son 20 caracteres',
                'nombre.required' => 'La descripción es requerida',
                'nombre.max' => 'El máximo permitido son 250 caracteres',
                'nombre.regex' => 'Sólo se aceptan letras',
            ]);

        if ($validator->fails()) {
            return redirect('/categoria/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }

        /*try {
            $categoria = Categoria::find($id);
            $categoria->codigo = $request->codigo;
            $categoria->nombre = $request->nombre;
            $categoria->estado = isset($request->estado)? true: false;
            $categoria->idUsuarioModificacion = Auth::id();
            $categoria->save();
        } catch (Exception $e) {
            return $this->error($request,$e);
        }*/
        
        $request->session()->flash('alert-success', 'La categoría se ha modificado correctamente.');
        return redirect()->route('categoria.index');
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
        Categoria::destroy($id);
        return back();
    }

    private function cargarCategoria()
    {
        return Categoria::all();
    }
}
