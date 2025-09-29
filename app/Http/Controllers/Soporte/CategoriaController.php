<?php

namespace App\Http\Controllers\Soporte;

use Illuminate\Support\Facades\DB;
use App\Model\Soporte\Categoria;
use App\Http\Controllers\CustomController;
use Illuminate\Http\Request;
use Exception;
use App\Http\Requests\Soporte\CategoriaValidator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends CustomController
{
    protected $tag = '100';
    
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
        return $this->views("soporte.categoria.index");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoriaValidator $request)
    {
        $validator = Validator::make(
                                    $request->all(), 
                                    $request->rules(),
                                    $request->messages()
        );

        if($validator->validate())
        {
            try {
                $categoria = new Categoria();
                $categoria->codigo = $request->codigo;
                $categoria->nombre = $request->nombre;
                $categoria->estado = isset($request->estado)? true: false;
                $categoria->idUsuarioCreacion = Auth::id();
                $categoria->idUsuarioModificacion = Auth::id();
                $categoria->save();
            } catch(Exception $e) {
                return $this->error($request,$e);
            }
            $request->session()->flash('alert-success', 'La categoría se guardó correctamente.');
            return redirect()->route('categoria.index');

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

        $datos = DB::table('Soporte.Categoria as c')
            ->select("c.idCategoria","c.nombre", "c.estado")
            ->get();

        return $this->views("soporte.categoria.index",
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

        $categoria = Categoria::find($id);

        return $this->views("soporte.categoria.edit",[
                                    "edit"=>$categoria
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

        try {
            $categoria = Categoria::find($id);
            $categoria->codigo = $request->codigo;
            $categoria->nombre = $request->nombre;
            $categoria->estado = isset($request->estado)? true: false;
            $categoria->idUsuarioModificacion = Auth::id();
            $categoria->save();
        } catch (Exception $e) {
            return $this->error($request,$e);
        }
        
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
}
