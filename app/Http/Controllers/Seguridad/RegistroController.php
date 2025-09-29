<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use App\Model\Soporte\MEmpresaClienteUsuario;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Entorno;

class RegistroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nit = $_GET["nit"];
        //$usuario = $_GET["usuario"];

        if(!isset($_GET["nit"])) //|| !isset($_GET["usuario"]) si no tiene los parámetros en la URL lo retorna al Login
        {
            return redirect()->route('login');
        }
        
        $empresaClienteusuario = DB::table('Soporte.EmpresaClienteUsuario')
                                ->where('nombre', $nit)->first();
        if(isset($empresaClienteusuario)) { // verificar si esta registrado el cliente
            $user = User::where('name',$nit)->first();
            if($user){
                Auth::login($user); // login user automatically
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
                foreach($datos as $d)
                {
                    \Illuminate\Support\Facades\Session::push("formulario.$d->carpeta",["formulario"=> $d->formulario, "path"=> $d->path, "carpetaPadre"=>$d->carpetaPadre]);
                }
                return redirect('/ticket');
            }
        }
        return redirect()->route('login');
        /*return view("seguridad.registro.registro", [
            "nit" => $nit,
            "usuario" => $usuario
        ]);*/
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), 
        [
            'email'=> 'required|max:300',
            'password' => ['required', 'min:8', 'confirmed']
        ],
        [
            'email.required' => 'El email es requerido',
            'email.max' => 'El máximo permitido son 250 caracteres',
            'password.confirmed' => 'La confirmación de contraseña no coincide',
            'password.min' => 'La constraseña debe contener mínimo 8 caracteres'
        ]);
        
        if ($validator->fails()) {
            return redirect('/registro?nit='.$request->nit.'&usuario='.$request->usuario)
                        ->withErrors($validator)
                        ->withInput();
        }

        $con2 = Entorno::getConex2();
        $resultado = DB::select("select
                                    empresa.\"idEmpresa\"
                                from dblink('$con2',
                                    'SELECT nit,\"razonSocial\", \"idEmpresa\", ciudad FROM \"Empresa\".\"Empresa\"')
                                    AS empresa(nit text,\"razonSocial\" text, \"idEmpresa\" integer, ciudad text)
                                WHERE empresa.nit = :nit LIMIT 1", ["nit" => $request->nit]);

        // Este es el webservice que vamos a consumir
        /*$wsdl = 'http://192.168.1.13:84/SisfecAdministracion/WebServices/WSSisfec.asmx?WSDL';
        $cliente = new \SoapClient($wsdl);
        $parametros = array(
            "nit" => $request->nit
        );*/
        // llamada al método del servicio que trae todas las empresas registradas en el SEO Web
       /* $resultado = $cliente->__soapCall("ConsultarEmpresaSimplePorNit", array($parametros));
        if(!isset($resultado->ConsultarEmpresaSimplePorNitResult)) {
            $request->session()->flash('alert-danger', 'La empresa no se encuentra registrada.');
            return redirect('/registro?nit='.$request->nit.'&usuario='.$request->usuario)
                    ->withInput();
        }*/
        
        DB::beginTransaction();
        try {
            $verificarUsuario = DB::table('users')
                    ->where('name', $request->usuario)->first();
            if(isset($verificarUsuario))
            {
                $request->session()->flash('alert-danger', 'El nombre de usuario ingresado ya exíste, ingrese otro nombre de usuario.');
                return redirect('/registro?nit='.$request->nit.'&usuario='.$request->usuario)
                        ->withInput();
            }

            $verificarUsuario = DB::table('users')
                    ->where('email', $request->email)->first();
            if(isset($verificarUsuario))
            {
                $request->session()->flash('alert-danger', 'Ya exíste un usuario con el email '.$request->email.', ingrese otro email.');
                return redirect('/registro?nit='.$request->nit.'&usuario='.$request->usuario)
                        ->withInput();
            }

            $usuario = new User();
            $usuario->name = $request->usuario;
            $usuario->email = $request->email;
            $usuario->password = bcrypt($request->password);
            $usuario->save();
            
            $rolCliente = DB::table('Seguridad.Rol')
                     ->where('nombre', 'Cliente')->first();

            $values[] = array('idUsuario' => $usuario->id,'idRol' => $rolCliente->idRol, 'idUsuarioCreacion' => -1, 'idUsuarioModificacion' => -1);
            DB::table('Seguridad.RolUsuario')->insert($values);

            $empresaClienteusuario = new MEmpresaClienteUsuario();
            $empresaClienteusuario->nombre = $request->usuario;
            $empresaClienteusuario->email = $request->email;
            $empresaClienteusuario->pwd = bcrypt($request->password);
            $empresaClienteusuario->idEmpresaCliente = $resultado[0]->idEmpresa;
            $empresaClienteusuario->idUsuarioCreacion = -1;
            $empresaClienteusuario->idUsuarioModificacion = -1;
            $empresaClienteusuario->save();

            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            $request->session()->flash('alert-danger', $e->getMessage());
            return redirect('/registro?nit='.$request->nit.'&usuario='.$request->usuario)
                        ->withInput();
        }
       
        $request->session()->flash('alert-success', 'El usuario se guardó correctamente. debe iniciar sesión para continuar.');
        return redirect()->route('login');
    }
}
