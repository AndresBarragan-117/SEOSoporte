<?php

namespace App\Http\Controllers\Seguridad;

use App\Entorno;
use App\Funcionario;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\CustomController;
use App\Http\Requests\Seguridad\UsuarioValidator;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends CustomController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datos = DB::table('Seguridad.Rol as r')
                    ->get();
        $categorias = DB::table('Soporte.Categoria as c')
                    ->get();
        return $this->views("seguridad.usuario.index",
                            [
                                "roles" =>$datos,
                                "categorias" => $categorias
                            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsuarioValidator $request)
    {
        $roles = $request->input("form");
        $categorias = $request->input("cats");
        $acciones = $request->input("acciones");

        $validator = Validator::make(
                                    $request->all(), 
                                    $request->rules(),
                                    $request->messages()
        );

        $usuario = new User();
        if($validator->validate())
        {
            DB::beginTransaction();
            try {
                $usuario->name = $request->name;
                $usuario->email = $request->email;
                $usuario->password = bcrypt($request->password);
                $usuario->save();
                
                $funcionario = new Funcionario();
                $funcionario->idUser = $usuario->id;
                $funcionario->nombre = $request->name;
                $funcionario->email = $request->email;
                $funcionario->pwd = bcrypt($request->password);
                $funcionario->firma = $request->firma;
                $funcionario->estado = true;
                $funcionario->idUsuarioCreacion = Auth::id();
                $funcionario->idUsuarioModificacion = Auth::id();
                $funcionario->save();

                $func = DB::table('Soporte.Funcionario')
                     ->where('idUser', $usuario->id)->first();

                $values = array();
                if(isset($roles)) {
	                foreach ($roles as $value) {
	                    $values[] = array('idUsuario' => $usuario->id,'idRol' => $value, 'idUsuarioCreacion' => Auth::id(), 'idUsuarioModificacion' => Auth::id());
	                }
	                DB::table('Seguridad.RolUsuario')->insert($values);
                }
                
                $valuesCategoria = array();
                if(isset($categorias)) {
	                foreach ($categorias as $value) {
	                    $valuesCategoria[] = array('idFuncionario' => $func->idFuncionario, 'idCategoria' => $value, 'fechaCreacion' => date('Y-m-d'), 'idUsuarioCreacion' => Auth::id());
	                }
	                DB::table('Soporte.FuncionarioCategoria')->insert($valuesCategoria);
                }
                
                $valuesAcciones = array();
                if(isset($acciones)) {
	                foreach ($acciones as $value) {
	                    $valuesAcciones[] = array('idUsuario' => $usuario->id, 'accion' => $value, 'idUsuarioCreacion' => Auth::id(), 'idUsuarioModificacion' => Auth::id());
	                }
	                DB::table('Seguridad.UsuarioAccion')->insert($valuesAcciones);
	            }

                DB::commit();
            } catch(Exception $e) {
                DB::rollback();
                return $this->error($request,$e);
            }
            $request->session()->flash('alert-success', 'El Usuario se guardó correctamente.');
            return $this->views("seguridad.usuario.index",["usuario"=>$this->cargarUsuario()]);
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
        return $this->views("seguridad.usuario.index",
                            [
                                "data"=>$this->cargarUsuario()
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
        $usuario = User::find($id);

        // Formularios Por Rol
        $rolesUsuario = DB::select('select
                                        r."idRol",
                                        r.nombre,
                                        CASE WHEN (SELECT count(*) from "Seguridad"."RolUsuario" ru where ru."idRol" = r."idRol" AND ru."idUsuario" = ?) > 0 THEN 
                                                1
                                            ELSE 
                                                0
                                       END as seleccionar
                                    from "Seguridad"."Rol" r', array($id));

         $categoriasUsuario = DB::select('select
                                            r."idCategoria",
                                            r.nombre,
                                            CASE WHEN (SELECT count(*) from "Soporte"."FuncionarioCategoria" ru
                                                        inner join "Soporte"."Funcionario" fun ON ru."idFuncionario" = fun."idFuncionario"
                                                        inner join users u ON fun."idUser" = u.id
                                                        where ru."idCategoria" = r."idCategoria" AND u.id = ?) > 0 THEN
                                                    1
                                                ELSE 
                                                    0
                                        END as seleccionar
                                        from "Soporte"."Categoria" r', array($id));

        $func = DB::table('Soporte.Funcionario')
                                        ->where('idUser', $id)->first();
        $acciones = DB::select('select
                                    distinct
                                    accion
                                from "Seguridad"."UsuarioAccion" where "idUsuario" = ?', array($id));
        
        $data2 = collect($acciones)->map(function($x){ return $x->accion; })->toArray();
        
        return $this->views("seguridad.usuario.edit",[
                                    "edit"=>$usuario,
                                    "usuario"=>$this->cargarUsuario(),
                                    "roles"=>$rolesUsuario,
                                    "categorias" =>$categoriasUsuario,
                                    "firma" => $func->firma,
                                    "acciones" => $data2
                                     ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UsuarioValidator $request, $id)
    {
        $validator = Validator::make(
                $request->all(), 
                $request->rules(),
                $request->messages()
        );
        
		if($validator->validate())
        {
	        DB::beginTransaction();
            $roles = $request->input("form");
            $categorias = $request->input("cats");
            $acciones = $request->input("acciones");

	        try {
                $func = DB::table('Soporte.Funcionario')->where('idUser', $id)->first();
                $funcionarioModificar = Funcionario::find($func->idFuncionario);

                DB::table('Soporte.FuncionarioCategoria')->where('idFuncionario', '=', $func->idFuncionario)->delete();
                DB::table('Seguridad.RolUsuario')->where('idUsuario', '=', $id)->delete();
                DB::table('Seguridad.UsuarioAccion')->where('idUsuario', '=', $id)->delete();

                $funcionarioModificar->firma = $request->firma;
                $funcionarioModificar->idUsuarioModificacion = Auth::id();
                $funcionarioModificar->save();
                
	            $values = array();
	            if(isset($roles)) {
	                foreach ($roles as $value) {
                        $values[] = array('idUsuario' => $id,'idRol' => $value, 'idUsuarioCreacion' => Auth::id(), 'idUsuarioModificacion' => Auth::id());
	                }
	                DB::table('Seguridad.RolUsuario')->insert($values);
	            }

                $valuesCategoria = array();
                if(isset($categorias)) {
	                foreach ($categorias as $value) {
	                    $valuesCategoria[] = array('idFuncionario' => $func->idFuncionario, 'idCategoria' => $value, 'fechaCreacion'=>Carbon::now(), 'idUsuarioCreacion' => Auth::id());
	                }
	                DB::table('Soporte.FuncionarioCategoria')->insert($valuesCategoria);
                }
                
                $valuesAcciones = array();
                if(isset($acciones)) {
	                foreach ($acciones as $value) {
	                    $valuesAcciones[] = array('idUsuario' => $id, 'accion' => $value, 'idUsuarioCreacion' => Auth::id(), 'idUsuarioModificacion' => Auth::id());
	                }
	                DB::table('Seguridad.UsuarioAccion')->insert($valuesAcciones);
	            }

	            $usuario = User::find($id);
	            $usuario->name = $request->name;
	            $usuario->email = $request->email;
	            if($usuario->password != $request->password)
	            {
	            	$usuario->password = bcrypt($request->password);
	            }
	            $usuario->save();
	            DB::commit();
	        } catch (Exception $e) {
                DB::rollback();
                return $this->error($request,$e);
	        }
	        $request->session()->flash('alert-success', 'El Usuario se ha modificado correctamente.');
	        return redirect()->route('usuario.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $func = DB::table('Soporte.Funcionario')
                        ->where('idUser', $id)->first();

        DB::table('Soporte.FuncionarioCategoria')->where('idFuncionario', '=', $func->idFuncionario)->delete();

        DB::table('Seguridad.RolUsuario')->where('idRolUsuario', '=', $id)->delete();
        User::destroy($id);
        return back();
    }

    private function cargarUsuario()
    {
        //return User::all();
        return $listTicket = DB::select('select
                                                *
                                            from users
                                            where name not in (
                                                select 
                                                    nombre
                                                from "Soporte"."EmpresaClienteUsuario"
                                            )', []);
    }

    public function busqueda(Request $request)
    {
        $data = null;
        $con2 = Entorno::getConex2();
        if(!isset($request->nombre) || empty($request->nombre))
        {
            
            $query = "SELECT ecu.nombre, empresa.\"razonSocial\", ecu.\"idEmpresaClienteUsuario\" FROM \"Soporte\".\"EmpresaClienteUsuario\" ecu
                        INNER JOIN dblink('$con2',
                                    'SELECT nit,\"razonSocial\", \"idEmpresa\" FROM \"Empresa\".\"Empresa\" ')
                                    AS empresa(nit text,\"razonSocial\" text, \"idEmpresa\" integer) ON ecu.\"idEmpresaCliente\" = empresa.\"idEmpresa\" LIMIT 20";

            $data = DB::select($query);
        } else {
            $query = "SELECT ecu.nombre, empresa.\"razonSocial\", ecu.\"idEmpresaClienteUsuario\" FROM \"Soporte\".\"EmpresaClienteUsuario\" ecu
                            INNER JOIN dblink('$con2',
                                        'SELECT nit,\"razonSocial\", \"idEmpresa\" FROM \"Empresa\".\"Empresa\"')
                                        AS empresa(nit text,\"razonSocial\" text, \"idEmpresa\" integer) ON ecu.\"idEmpresaCliente\" = empresa.\"idEmpresa\"
                        WHERE UPPER(ecu.nombre) like :nombre OR empresa.\"razonSocial\" like :nombre LIMIT 20";

            $data = DB::select($query, ['nombre' => '%'.strtoupper($request->nombre).'%']);
        }
        $html = View("controles.usuario.tablaBusquedaCliente",["data"=>$data]);  
        return response()->json([ "estado"=> true, "html"=> $html->render()]);
    }

    public function busquedaEmpresa(Request $request)
    {
        $data = null;
        $con2 = Entorno::getConex2();
        if(!isset($request->nombre) || empty($request->nombre))
        {
            
            $query = "SELECT empresa.\"razonSocial\", empresa.nit, empresa.\"idEmpresa\" from
                         dblink('$con2',
                                    'SELECT nit,\"razonSocial\", \"idEmpresa\" FROM \"Empresa\".\"Empresa\" ')
                                    AS empresa(nit text,\"razonSocial\" text, \"idEmpresa\" integer) LIMIT 10";

            $data = DB::select($query);
        } else {
            $query = "SELECT empresa.\"razonSocial\", empresa.nit, empresa.\"idEmpresa\" from
                            dblink('$con2',
                                        'SELECT nit,\"razonSocial\", \"idEmpresa\" FROM \"Empresa\".\"Empresa\"')
                                        AS empresa(nit text,\"razonSocial\" text, \"idEmpresa\" integer)
                        WHERE (UPPER(empresa.\"razonSocial\") like :nombre) or (empresa.nit like :nombre) LIMIT 10";

            $data = DB::select($query, ['nombre' => '%'.strtoupper($request->nombre).'%']);
        }
        $html = View("controles.empresa.tablaBusquedaEmpresa",["data"=>$data]);  
        return response()->json([ "estado"=> true, "html"=> $html->render()]);
    }

    public function busquedaFuncionario(Request $request)
    {
        $data = null;
        $con2 = Entorno::getConex2();
        if(!isset($request->nombre) || empty($request->nombre))
        {
            $query = "SELECT \"idFuncionario\", email, nombre FROM \"Soporte\".\"Funcionario\" LIMIT 30";

            $data = DB::select($query);
        } else {
            $query = "SELECT \"idFuncionario\", email, nombre FROM \"Soporte\".\"Funcionario\"
                        WHERE UPPER(nombre) like :nombre LIMIT 10";

            $data = DB::select($query, ['nombre' => '%'.strtoupper($request->nombre).'%']);
        }
        $html = View("controles.funcionario.tablaBusquedaFuncionario",["data"=>$data, "control"=>$request->control]);  
        return response()->json([ "estado"=> true, "html"=> $html->render()]);
    }

    public function cambiarAtencion(Request $request)
    {
        $data = 0;
        if(isset($request->idFuncionario) && $request->idFuncionario > 0)
        {
            $query = 'UPDATE "Soporte"."Ticket" SET "idFuncionario" = :idFuncionario, "idUsuarioModificacion" = :id WHERE guid = :guid';
            $data = DB::update($query, ['idFuncionario' => $request->idFuncionario,
                                        'guid' => $request->guid,
                                        'id' => Auth::user()->id
                                       ]);
            if($data > 0) {
                return response()->json([ "estado"=> true]);        
            }
        }
        return response()->json([ "estado"=> false]);
    }
}