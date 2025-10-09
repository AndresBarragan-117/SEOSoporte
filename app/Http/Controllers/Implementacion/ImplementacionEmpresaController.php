<?php

namespace App\Http\Controllers\Implementacion;
            
use Illuminate\Support\Facades\DB;
use App\Model\Implementacion\ImplementacionEmpresa;
use App\Model\Implementacion\PlantillaLista;
use App\Model\Implementacion\Plantilla;
use App\Model\Implementacion\TipoCaptura;
use App\Http\Controllers\CustomController;
use Illuminate\Http\Request;
use App\Http\Requests\Implementacion\PlantillaListaValidator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Entorno;

class ImplementacionEmpresaController extends CustomController
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
    public function index()
    {
        $con2 = Entorno::getConex2();
        $resultado = DB::select("select empresa.\"idEmpresa\", \"razonSocial\"  from dblink('$con2',
                                    'SELECT \"razonSocial\", \"idEmpresa\", implementado FROM \"Empresa\".\"Empresa\"')
                                    AS empresa(\"razonSocial\" text, \"idEmpresa\" integer, implementado boolean)
                                    WHERE implementado = FALSE ORDER BY \"razonSocial\";");
        
        $resultado2 = DB::select("Select \"idTipoPlantilla\",nombre from \"Implementacion\".\"TipoPlantilla\"");
        return $this->views("implementacion.implementacionEmpresa.index", [
            'listaEmpresa' => $resultado,
            "listadoTipoPlantilla"=> $resultado2
        ]);
    }

    public function guardar(Request $request) 
    {
        if(isset($request->idEmpresa) && isset($request->listadoResult)) {
            $json = json_decode($request->listadoResult);
            if(count($json) > 0) {
                DB::beginTransaction();

                $sql = 'DELETE FROM "Implementacion"."ImplementacionEmpresa" WHERE "idEmpresa" = ' . $request->idEmpresa .' AND "idPlantilla" = '.$json[0]->idPlantilla;
                DB::statement($sql);
                try {
                    $values = array();
                    foreach ($json as $row) {
                        $values[] = array('idEmpresa' => $request->idEmpresa,'idPlantilla' => $row->idPlantilla, 'idPlantillaLista' => $row->idPlantillaLista, 
                         'valor' => $row->valor, 'fechaRealiza' => $row->fecha, 'observacion' => $row->comentario,
                        'idUsuarioCreacion' => Auth::id(), 'idUsuarioModificacion' => Auth::id());
                        //dd($row->idPlantilla);
                    }
                    DB::table('Implementacion.ImplementacionEmpresa')->insert($values);
                    DB::commit();
                    
                    return response()->json(["exito" => true]);
                } catch(Exception $e) {
                    DB::rollback();
                    return response()->json(["exito" => false, "mensaje" => "ERROR: ".$e->getMessage()]);
                }
            } else {
                return response()->json(["exito" => false, "mensaje" => "No hay datos para guardar"]);
            }
        } else {
            return response()->json(["exito" => false, "mensaje" => "Error desconocido"]);
        }
    }
}