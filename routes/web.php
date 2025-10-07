<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//use Illuminate\Routing\Route;

use App\Http\Controllers\Soporte\KBArticuloController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//Auth::routes();
// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/home', 'HomeController@index')->name('home');

/** Seguridad **/
// resource es la forma rápida y automática en Laravel para registrar todas las rutas CRUD 
// (Crear, Leer, Actualizar, Eliminar) para un controlador
Route::resource('/usuario', 'Seguridad\UsuarioController'); //->name('index', 'usuario');
Route::resource('/carpeta', 'Seguridad\CarpetaController'); //->name('index', 'usuario');
Route::resource('/formulario', 'Seguridad\FormularioController'); //->name('index', 'usuario');
Route::resource('/rol', 'Seguridad\RolController'); //->name('index', 'usuario');

/** Soporte **/
Route::resource('/categoria', 'Soporte\CategoriaController');
Route::resource('/kbArticuloCategoria', 'Soporte\KBArticuloCategoriaController');

Route::post('/kbArticulo/calificarArticulo', 'Soporte\KBArticuloController@calificarArticulo');
Route::get('/kbArticulo/listadoBaseConocimiento/{busqueda?}/{json?}', 'Soporte\KBArticuloController@listadoBaseConocimiento');
Route::resource('/kbArticulo', 'Soporte\KBArticuloController');
Route::get('/kbArticulo/consultarContenidoArticulo/{idKbArticulo}', 'Soporte\KBArticuloController@consultarContenidoArticulo');

Route::resource('/implementacionEmpresa', 'Implementacion\ImplementacionEmpresaController');
Route::post('/implementacionEmpresa/guardar', 'Implementacion\ImplementacionEmpresaController@guardar');

Route::get('/plantilla/obtenerPlantilla/{idTipoPlantilla}', 'Implementacion\PlantillaController@obtenerPlantilla');
Route::resource('/plantilla', 'Implementacion\PlantillaController');
Route::resource('/tipoCaptura', 'Implementacion\TipoCapturaController');

Route::get('/mensajePlantilla/consultarMensajePlantilla', 'Soporte\MensajePlantillaController@consultarMensajePlantilla');
Route::resource('/mensajePlantilla', 'Soporte\MensajePlantillaController');

Route::resource('/ticketPrioridad', 'Soporte\TicketPrioridadController');
Route::resource('/ticketEstado', 'Soporte\TicketEstadoController');
Route::resource('/ticketEmailPlantilla', 'Soporte\TicketEmailPlantillaController');
Route::resource('/parametroDefecto', 'Soporte\ParametroDefectoController');

Route::resource('/registro', 'Seguridad\RegistroController',['only'=> ['index','store']]);

Route::post('/ticket/calificarTicket', 'Soporte\TicketController@calificarTicket');
Route::get('/ticket/listadoTicket', 'Soporte\TicketController@listadoTicket');
Route::get('/ticket/movimientoTareaTicket/{idTicket}', 'Soporte\TicketController@movimientoTareaTicket');
Route::resource('/ticket', 'Soporte\TicketController',['only'=> ['index','store']]);

Route::resource('/ticketSoporte', 'Soporte\TicketSoporteController',['only'=> ['index','store']]);

Route::get('/ticketSoporte/rptClienteTicket/{json?}/{fechaInicial?}/{fechaFinal?}/{cliente?}', 'Soporte\TicketSoporteController@rptClienteTicket');
Route::get('/ticketSoporte/rptClienteCategoriaTicket/{json?}/{fechaInicial?}/{fechaFinal?}/{cliente?}/{categoria?}', 'Soporte\TicketSoporteController@rptClienteCategoriaTicket');
Route::get('/ticketSoporte/rptClienteTicketTiempo/{json?}/{fechaInicial?}/{fechaFinal?}/{cliente?}', 'Soporte\TicketSoporteController@rptClienteTicketTiempo');
Route::get('/ticketSoporte/rptClienteCategoriaTicketTiempo/{json?}/{fechaInicial?}/{fechaFinal?}/{cliente?}/{categoria?}', 'Soporte\TicketSoporteController@rptClienteCategoriaTicketTiempo');
Route::get('/ticketSoporte/rptUsuarioCliente/{json?}/{fechaInicial?}/{fechaFinal?}/{cliente?}', 'Soporte\TicketSoporteController@rptUsuarioCliente');
Route::get('/ticketSoporte/rptUsuarioCategoriaTicket/{json?}/{fechaInicial?}/{fechaFinal?}/{cliente?}/{categoria?}', 'Soporte\TicketSoporteController@rptUsuarioCategoriaTicket');

Route::get('/ticketSoporte/listadoTicketSoporte', 'Soporte\TicketSoporteController@listadoTicketSoporte');
Route::get('/ticketSoporte/listadoTicketSoporte/{fechaInicio}/{fechaFin}/{idEmpresa}/{idCliente}/{idTicketEstado}/{idFuncionario}/{idCategoria}/{idTicketPrioridad}',
         'Soporte\TicketSoporteController@listadoTicketSoporte2');
Route::get('/ticketSoporte/listadoTicketTarea/{id}', 'Soporte\TicketSoporteController@listadoTicketTarea');

Route::post('/usuario/busqueda', 'Seguridad\UsuarioController@busqueda');
Route::post('/usuario/busquedaEmpresa', 'Seguridad\UsuarioController@busquedaEmpresa');
Route::post('/usuario/busquedaFuncionario', 'Seguridad\UsuarioController@busquedaFuncionario');
Route::post('/usuario/cambiarAtencion', 'Seguridad\UsuarioController@cambiarAtencion');
Route::post('/ticketSoporte/nuevaTarea', 'Soporte\TicketSoporteController@nuevaTarea');
Route::post('/ticketSoporte/cambiarPrioridadTicket', 'Soporte\TicketSoporteController@cambiarPrioridadTicket');

Route::post('/ticketSoporte/notificacionTicket', 'Soporte\TicketSoporteController@notificacionTicket');

Route::post('/ticketSoporte/consultarAnexoTicket', 'Soporte\TicketSoporteController@consultarAnexoTicket');
Route::get('/ticketSoporte/ticketEstadoTarea', 'Soporte\TicketSoporteController@ticketEstadoTarea');

Route::get('/plantillaLista/cargarPlantillaLista/{idEmpresa}/{idPlantilla}', 'Implementacion\PlantillaListaController@cargarPlantillaLista');
Route::resource('/plantillaLista', 'Implementacion\PlantillaListaController');


// Registration Routes...
//$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
//$this->post('register', 'Auth\RegisterController@register');