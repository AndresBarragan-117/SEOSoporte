<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Model\Soporte\MEmpresaClienteUsuario;

class RegistroController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function registrarUsuarioWeb(Request $request) {
        if(!isset($request->name)){
            return "Ingrese la cédula";
        }
        try 
        {
            DB::beginTransaction();
            
            //-- Verificar si exíste el rol del cliente
            $rolCliente = DB::table('Seguridad.Rol')->where('nombre', 'Cliente')->first();
            if(!isset($rolCliente)) {
                return "ERROR: No está definida el rol del cliente.";
            }
            
            $u = DB::select('select * from users where name = ?', [$request->name]);
            if(count($u) > 0) {
                return "ERROR: Ya exíste el usuario.";
            }

            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $usuario = User::create($input);

            $values[] = array('idUsuario' => $usuario->id,'idRol' => $rolCliente->idRol, 'idUsuarioCreacion' => -1, 'idUsuarioModificacion' => -1);
            DB::table('Seguridad.RolUsuario')->insert($values);
            
            $empresaClienteusuario = new MEmpresaClienteUsuario();
            $empresaClienteusuario->nombre = $request->name;
            $empresaClienteusuario->email = $request->email;
            $empresaClienteusuario->pwd = bcrypt($request->password);
            $empresaClienteusuario->idEmpresaCliente = $request->idEmpresa;
            $empresaClienteusuario->idUsuarioCreacion = -1;
            $empresaClienteusuario->idUsuarioModificacion = -1;
            $empresaClienteusuario->save();

            DB::commit();
            return "OK";
        } catch(Exception $ex) {
            DB::rollback();
            return "ERROR: ".$ex->getMessage();
        }
    }

    public function existeUsuario(Request $request) {
        try {
            $user = DB::select('select * from users where name = ?', [$request->nombreUsuario]);
            if(isset($user) && count($user) > 0) {
                return "ERROR: Ya exíste el usuario.";
            }
            return "Ok";
        } catch(Exception $e) {
            return "ERROR: ".$e->getMessage();
        }
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['name' => $request->name, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  "tocken"; //$user->createToken('MyApp')-> accessToken; 
            $success['name'] =  $user->name;
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }
}