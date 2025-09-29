<?php

namespace App\Http\Controllers\Soporte;

use Illuminate\Support\Facades\DB;
use App\Model\Soporte\Categoria;
use App\Http\Controllers\CustomController;
use Illuminate\Http\Request;
use Exception;
use App\Http\Requests\Soporte\KBArticuloCategoriaValidator;
use App\Model\Soporte\KBArticuloCategoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KBArticuloCategoriaController extends CustomController
{
    protected $tag = '200';
    
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
        $data = DB::table('Soporte.KBArticuloCategoria')->get();

        return $this->views("soporte.kbArticuloCategoria.index", [
            'padreId' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KBArticuloCategoriaValidator $request)
    {
        $validator = Validator::make(
                                    $request->all(), 
                                    $request->rules(),
                                    $request->messages()
        );

        if($validator->validate())
        {
            try {
                $kbArticuloCategoria = new KBArticuloCategoria();
                $kbArticuloCategoria->nombre = $request->nombre;
                $kbArticuloCategoria->padreId = $request->padreId;
                $kbArticuloCategoria->nombreEtiqueta = $request->nombreEtiqueta;
                $kbArticuloCategoria->orden = $request->orden;
                $kbArticuloCategoria->idUsuarioCreacion = Auth::id();
                $kbArticuloCategoria->idUsuarioModificacion = Auth::id();
                
                $kbArticuloCategoria->save();
            } catch(Exception $e) {
                return $this->error($request,$e);
            }
            $request->session()->flash('alert-success', 'La categoría se guardó correctamente.');
            return redirect()->route('kbArticuloCategoria.index');
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
        $datos = DB::table('Soporte.KBArticuloCategoria as c')
            ->leftJoin('Soporte.KBArticuloCategoria as c2', 'c.padreId', '=','c2.idKBArticuloCategoria')
            ->select("c.idKBArticuloCategoria","c.nombre", "c2.nombre AS carpetaPadre", "c.nombreEtiqueta")
            ->get();

        $padreId = DB::table('Soporte.KBArticuloCategoria')->get();
        return $this->views("soporte.kbArticuloCategoria.index",
                            [ 
                                "data" =>$datos,
                                'padreId' => $padreId
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
        $kbArticuloCategoria = KBArticuloCategoria::find($id);
        $padreId = DB::table('Soporte.KBArticuloCategoria')->get();

        return $this->views("soporte.kbArticuloCategoria.edit",[
                                        "edit"=>$kbArticuloCategoria,
                                        'padreId' => $padreId
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
                'nombreEtiqueta' => 'required|max:300|regex:/^[aA-zZ \-0-9óíúáñé]+$/i',
                'orden' => 'required|max:5|regex:/^[0-9]+$/'
            ],
            [
                'nombre.required' => 'La descripción es requerida',
                'nombre.max' => 'El máximo permitido son 250 caracteres',
                'nombre.regex' => 'Sólo se aceptan letras',
                'nombreEtiqueta.required' => 'El nombre de la etiqueta es requerida',
                'nombreEtiqueta.max' => 'El máximo permitido son 250 caracteres',
                'nombreEtiqueta.regex' => 'Sólo se aceptan letras',
                'orden.required' => 'El orden es requerido',
                'orden.max' => 'El máximo permitido son 5 caracteres',
                'orden.regex' => 'Sólo se aceptan números'
            ]);

        if ($validator->fails()) {
            return redirect('/kbArticuloCategoria/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            $kbArticuloCategoria = KBArticuloCategoria::find($id);
            $kbArticuloCategoria->nombre = $request->nombre;
            $kbArticuloCategoria->padreId = $request->padreId;
            $kbArticuloCategoria->nombreEtiqueta = $request->nombreEtiqueta;
            $kbArticuloCategoria->orden = $request->orden;
            $kbArticuloCategoria->idUsuarioModificacion = Auth::id();
            $kbArticuloCategoria->save();
        } catch (Exception $e) {
            return $this->error($request,$e);
        }
        
        $request->session()->flash('alert-success', 'La categoría se ha modificado correctamente.');
        return redirect()->route('kbArticuloCategoria.index');
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
        KBArticuloCategoria::destroy($id);
        return back();
    }
}
