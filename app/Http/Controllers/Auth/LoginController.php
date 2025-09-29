<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;
use Session;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    protected $username = 'name';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'name';
    }

    public function authenticated()
    {

        $datos = DB::table('Seguridad.Formulario as f')
                ->join("Seguridad.Carpeta AS c", "f.idCarpeta",'=', "c.idCarpeta")
                ->leftJoin("Seguridad.Carpeta AS cp", "c.idPadre", "=", "cp.idCarpeta")
                ->join("Seguridad.RolFormulario AS rf", "f.idFormulario",'=', "rf.idFormulario")
                ->join("Seguridad.RolUsuario AS ru", "rf.idRol",'=', "ru.idRol")
                ->select("f.nombre as formulario","f.path", "c.descripcion as carpeta", "cp.descripcion as carpetaPadre")
                ->where("ru.idUsuario","=", Auth::user()->id)
                ->orderBy("c.descripcion")
                ->orderBy("f.nombre")
                ->distinct()
                ->get();
        $roles = DB::select('select distinct r.nombre from "Seguridad"."RolUsuario" ru
                        inner join "Seguridad"."Rol" r on ru."idRol" = r."idRol"
                        inner join users u on ru."idUsuario" = u.id
                        where u.id = :id
                    ', ['id'=> Auth::user()->id]);
        \Illuminate\Support\Facades\Session::push("roles", $roles);
        $roles = \Illuminate\Support\Facades\Session::get('roles', '');
        foreach ($roles[0] as $rol) {
            if($rol->nombre == "Administradores") {
                DB::select('select "Soporte"."fnArchivarTicket"()', []);
                break;
            }
        } 
        foreach($datos as $d)
        {
            \Illuminate\Support\Facades\Session::push("formulario.$d->carpeta",["formulario"=> $d->formulario, "path"=> $d->path, "carpetaPadre"=>$d->carpetaPadre]);
            /*if(!isset($_SESSION["formulario"][$d->carpeta]))
            {
                $_SESSION["formulario"][$d->carpeta] = array();
            }
            array_push($_SESSION["formulario"][$d->carpeta], ["formulario"=> $d->formulario, "path"=> $d->path]);*/
        }
    }
}
