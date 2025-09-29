<?php

namespace App\Http\Controllers\Soporte;

use Illuminate\Support\Facades\DB;
use App\Model\Soporte\MensajePlantilla;
use App\Http\Controllers\CustomController;
use Illuminate\Http\Request;
use Exception;
use App\Http\Requests\Soporte\MensajePlantillaValidator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MensajePlantillaController extends CustomController
{
    protected $tag = '102';
    
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
        $categoria = DB::table('Soporte.Categoria')->get();
        
        return $this->views("soporte.mensajePlantilla.index",
            [
                'categoria'=> $categoria
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MensajePlantillaValidator $request)
    {
        $validator = Validator::make(
                                    $request->all(), 
                                    $request->rules(),
                                    $request->messages()
        );

        if($validator->validate())
        {
            try {
                $mensajePlantilla = new MensajePlantilla();
                $mensajePlantilla->idCategoria = $request->categoria;
                $mensajePlantilla->pregunta = $request->pregunta;
                $mensajePlantilla->respuesta = $request->respuesta;
                $mensajePlantilla->idUsuarioCreacion = Auth::id();
                $mensajePlantilla->idUsuarioModificacion = Auth::id();
                $mensajePlantilla->save();
            } catch(Exception $e) {
                return $this->error($request,$e);
            }
            $request->session()->flash('alert-success', 'La plantilla se guardó correctamente.');
            return redirect()->route('mensajePlantilla.index');

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
        $datos = DB::table('Soporte.MensajePlantilla as c')
            ->get();
        $categoria = DB::table('Soporte.Categoria')->get();
        return $this->views("soporte.mensajePlantilla.index",
                            [ 
                                "data" =>$datos,
                                "categoria"=>$categoria
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
        $mensajePlantilla = MensajePlantilla::find($id);
        $categoria = DB::table('Soporte.Categoria')->get();
        
        return $this->views("soporte.mensajePlantilla.edit",[
                                    "edit"=>$mensajePlantilla,
                                    "categoria" => $categoria
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
                'pregunta' => 'required|max:300',
                'respuesta' => 'required|max:300',
            ],
            [
                'pregunta.required' => 'La pregunta es requerida',
                'pregunta.max' => 'El máximo permitido son 20 caracteres',
                'respuesta.required' => 'La respuesta es requerida',
                'respuesta.max' => 'El máximo permitido son 20 caracteres',
            ]);

        if ($validator->fails()) {
            return redirect('/mensajePlantilla/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            $mensajePlantilla = MensajePlantilla::find($id);
            $mensajePlantilla->idCategoria = $request->categoria;
            $mensajePlantilla->pregunta = $request->pregunta;
            $mensajePlantilla->respuesta = $request->respuesta;
            $mensajePlantilla->idUsuarioModificacion = Auth::id();
            $mensajePlantilla->save();
        } catch (Exception $e) {
            return $this->error($request,$e);
        }
        
        $request->session()->flash('alert-success', 'La plantilla se ha modificado correctamente.');
        return redirect()->route('mensajePlantilla.index');
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
        MensajePlantilla::destroy($id);
        return back();
    }

    public function consultarMensajePlantilla()
    {
        $mensajePlantilla = DB::select('select mp.*, c.nombre as categoria from "Soporte"."MensajePlantilla" mp
                                        inner join "Soporte"."Categoria" c on mp."idCategoria" = c."idCategoria"', []);
        return $this->views("soporte.consultas.listadoMensajePlantilla",["mensajePlantilla"=>$mensajePlantilla]);
    }
}
