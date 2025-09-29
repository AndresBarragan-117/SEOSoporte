<?php

namespace App\Http\Controllers\Soporte;

use Illuminate\Support\Facades\DB;
use App\Model\Soporte\Categoria;
use App\Model\Soporte\Votacion;
use App\Http\Controllers\CustomController;
use Illuminate\Http\Request;
use Exception;
use App\Http\Requests\Soporte\KBArticuloValidator;
use App\Model\Soporte\KBArticulo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KBArticuloController extends CustomController
{
    protected $tag = '201';
    
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

        return $this->views("soporte.kbArticulo.index", [
            'categorias' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KBArticuloValidator $request)
    {
        $validator = Validator::make(
                                    $request->all(), 
                                    $request->rules(),
                                    $request->messages()
        );

        if($validator->validate())
        {
            try {
                $kbArticulo = new KBArticulo();
                $kbArticulo->idKBArticuloCategoria = $request->categoria;
                $kbArticulo->asunto = $request->asunto;
                $kbArticulo->contenido = $request->contenido;
                $kbArticulo->tipo = $request->tipo;
                $kbArticulo->idUsuarioCreacion = Auth::id();
                $kbArticulo->idUsuarioModificacion = Auth::id();
                
                $kbArticulo->save();
            } catch(Exception $e) {
                return $this->error($request,$e);
            }
            $request->session()->flash('alert-success', 'El articulo se guardó correctamente.');
            return redirect()->route('kbArticulo.index');
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
        $datos = DB::table('Soporte.KBArticulo as c')
            ->leftJoin('Soporte.KBArticuloCategoria as c2', 'c.idKBArticuloCategoria', '=','c2.idKBArticuloCategoria')
            ->select("c.idKBArticulo","c.asunto", "c2.nombre AS categoria", "c.tipo")
            ->get();

        $data = DB::table('Soporte.KBArticuloCategoria')->get();

        return $this->views("soporte.kbArticulo.index",
                            [ 
                                "data" =>$datos,
                                'categorias' => $data
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
        $kbArticulo = KBArticulo::find($id);
        $data = DB::table('Soporte.KBArticuloCategoria')->get();

        return $this->views("soporte.kbArticulo.edit",[
                                        "edit"=>$kbArticulo,
                                        'categorias' => $data
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
                'asunto' => 'required|max:300'
            ],
            [
                'asunto.required' => 'El asunto es requerido',
                'asunto.max' => 'El máximo permitido son 250 caracteres'
            ]);
        if ($validator->fails()) {
            return redirect('/kbArticulo/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }
        
        try {
            $kbArticulo = KBArticulo::find($id);
            $kbArticulo->idKBArticuloCategoria = $request->categoria;
            $kbArticulo->asunto = $request->asunto;
            $kbArticulo->contenido = $request->contenido;
            $kbArticulo->tipo = $request->tipo;
            $kbArticulo->idUsuarioModificacion = Auth::id();
            $kbArticulo->save();
        } catch (Exception $e) {
            return $this->error($request,$e);
        }
        $request->session()->flash('alert-success', 'El articulo se ha modificado correctamente.');
        return redirect()->route('kbArticulo.index');
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
        //KBArticulo::destroy($id);
        return back();
    }

    public function listadoBaseConocimiento($busqueda = '', $json = 0)
    {
        if($json == 1) {
            $roles = \Illuminate\Support\Facades\Session::get('roles', '');
            $tipoArticulo = '';
            foreach ($roles[0] as $rol) { 
                if($rol->nombre == "Administradores" ||  strtoupper($rol->nombre) == "SOPORTE") {
                    $tipoArticulo = '1,2';
                    break;
                }
                if(strtoupper($rol->nombre) == "CLIENTE") {
                    $tipoArticulo = '1';
                }
            }

            $listArticulo = DB::select("select
                                    a.*,
                                    c.\"nombreEtiqueta\" as categoria,
                                    cp.\"nombreEtiqueta\" as \"categoriaPadre\",
                                    (case when (select count(*) from \"Soporte\".\"Votacion\" as v where v.\"idKBArticulo\" = a.\"idKBArticulo\" and v.\"idUsuario\" = :id) > 0 then 1 else 0 end) as calificado,
                                    COALESCE((select v.\"cantidadVotos\" from \"Soporte\".\"Votacion\" as v where v.\"idKBArticulo\" = a.\"idKBArticulo\" and v.\"idUsuario\" = :id), 0) as calificacion
                                from \"Soporte\".\"KBArticulo\" a
                                inner join \"Soporte\".\"KBArticuloCategoria\" c on a.\"idKBArticuloCategoria\" = c.\"idKBArticuloCategoria\"
                                left join \"Soporte\".\"KBArticuloCategoria\" cp on c.\"padreId\" = cp.\"idKBArticuloCategoria\"
                                WHERE a.tipo IN(".$tipoArticulo.") AND (:busqueda = 'empty' OR UPPER(c.\"nombreEtiqueta\") LIKE :busqueda OR UPPER(a.asunto) LIKE :busqueda) OR UPPER(a.contenido) LIKE :busqueda"
                                , ["busqueda"=>($busqueda != "empty" ? '%'.strtoupper($busqueda).'%' : $busqueda), "id"=> Auth::id()]);
            $array = array();
            foreach($listArticulo as $d)
            {
                $array["$d->categoria"][] = ["idKbArticulo"=>$d->idKBArticulo ,"asunto"=>$d->asunto, "padre"=>$d->categoriaPadre, "calificado"=>$d->calificado, "calificacion"=>$d->calificacion, "cliente"=>($tipoArticulo == '1' ? 1 : 0)];
            }
            
            $listPadre = DB::select("select distinct
                                    cp.\"nombreEtiqueta\" as carpeta
                                    from \"Soporte\".\"KBArticulo\" a
                                    inner join \"Soporte\".\"KBArticuloCategoria\" c on a.\"idKBArticuloCategoria\" = c.\"idKBArticuloCategoria\"
                                    inner join \"Soporte\".\"KBArticuloCategoria\" cp on c.\"padreId\" = cp.\"idKBArticuloCategoria\" ", []);
            return response()->json(["carpetas"=> $listPadre, "baseConocimiento"=>$array]);
        } else {
            return $this->views("soporte.consultas.listadoBaseConocimiento", [
                    "listArticulo" => null
            ]);
        }
    }

    public function consultarContenidoArticulo($idKbArticulo)
    {
        if($idKbArticulo > 0) {
            $articulo = DB::select("select  a.*,
                                        c.\"nombreEtiqueta\" as carpeta
                                    from \"Soporte\".\"KBArticulo\" a
                                    inner join \"Soporte\".\"KBArticuloCategoria\" c on a.\"idKBArticuloCategoria\" = c.\"idKBArticuloCategoria\"
                                    where a.\"idKBArticulo\" = :idKBArticulo", ["idKBArticulo"=>$idKbArticulo]);

            // Actualiza cantidadVistos
            $roles = \Illuminate\Support\Facades\Session::get('roles', '');
            if(count($roles[0]) > 0) {
                foreach ($roles[0] as $rol) {
                    if(strtoupper($rol->nombre) == "CLIENTE" && $articulo[0]->tipo == 1) {
                        $rowArticulo = DB::table('Soporte.KBArticulo')->where("idKBArticulo", "=", $idKbArticulo)->first();
                        if($rowArticulo != null) {
                            $cantidadVistos = $rowArticulo->cantidadVistos != null ? $rowArticulo->cantidadVistos : 0;
                            DB::update('update
                                                "Soporte"."KBArticulo"
                                            set "cantidadVistos" = :cantidadVistos
                                        where "idKBArticulo" = :idKBArticulo', ["idKBArticulo"=>$idKbArticulo, "cantidadVistos"=>($cantidadVistos+1)]);
                        } //end actualizar cantidadVistos
                        break;
                    }
                }
            }
            
            return response()->json($articulo[0]);
        }
    }

    public function calificarArticulo(Request $request) {
        if($request->idKbArticulo > 0 && $request->calificacion > 0)
        {
            $kbArticulo = KBArticulo::find($request->idKbArticulo);
            if($kbArticulo != null && $kbArticulo->tipo == 1) {
                $votacion = new Votacion();
                $votacion->idUsuario = Auth::id();
                $votacion->idKBArticulo = $request->idKbArticulo;
                $votacion->cantidadVotos = $request->calificacion;
                $votacion->save();
                
                $cantidadVotosActual = ($kbArticulo->cantidadVotos == null ? 0 : $kbArticulo->cantidadVotos);

                $kbArticulo->cantidadVotos = ($cantidadVotosActual + 1);
                $kbArticulo->save();
                return response()->json([ "estado"=> true]);
            } else {
                return response()->json([ "estado"=> false, "mensaje"=>"No se encontró el Articulo."]);
            }
        } else {
            return response()->json([ "estado"=> false, "mensaje"=>"Se ha presentado un error con los datos."]);
        }
    }
}
