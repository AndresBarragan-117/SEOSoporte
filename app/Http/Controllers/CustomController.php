<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CustomController extends Controller
{
    public function __construct()
    {
		// Valida la sesión cuando se abre una ruta
        $this->middleware('auth');
    }

	public function views($ruta,$data = [])
	{
		$data["menu"] = null;
		if(\Illuminate\Support\Facades\Session::get("formulario") != null)
		{
			$data["menu"] = \Illuminate\Support\Facades\Session::get("formulario");
		}
		//dd($data["menu"]);
		return view($ruta, $data);
	}

	/*public function views($ruta,$data = [])
	{
		$data["menu"] = null;
		if(Auth::user() != null && Auth::user()->id > 0)
		{
			if(isset($_SESSION["formulario"]))
			{
				$data["menu"] = $_SESSION["formulario"];
			}else
			{
				$datos = DB::table('Seguridad.Formulario as f')
						->join("Seguridad.Carpeta AS c", "f.idCarpeta",'=', "c.idCarpeta")
						->join("Seguridad.RolFormulario AS rf", "f.idFormulario",'=', "rf.idFormulario")
						->join("Seguridad.RolUsuario AS ru", "rf.idRol",'=', "ru.idRol")
						->select("f.nombre as formulario","f.path",  "c.descripcion as carpeta")
						->where("ru.idUsuario","=", Auth::user()->id)
						->orderBy("c.descripcion")
						->orderBy("f.nombre")
						->get();		
				
				foreach($datos as $d)
				{
					
					if(!isset($data["menu"][$d->carpeta]))
					{
						$data["menu"][$d->carpeta] = array();
					}
					array_push($data["menu"][$d->carpeta], ["formulario"=> $d->formulario, "path"=> $d->path]);
					
				}
				
			}
			$_SESSION["formulario"] = $data["menu"];	
		}
		
		return view($ruta, $data);
	}*/


}