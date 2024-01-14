<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*Route::get('/{optional?}', function () {
    return view('app');
})->name('basepath');*/

//->where('optional', '.*');
//Auth::routes();
Route::post('/authenticate/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);

Route::group(['middleware' => ['auth']], function () {

    Route::post('/authenticate/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);
    Route::get('/administracion/usuario/getListarRolPermisosByUsuario', [App\Http\Controllers\Administracion\UsersController::class, 'getListarRolPermisosByUsuario']);



    Route::get('/parametros/undiad/getListarUnidades', [App\Http\Controllers\UnidadesController::class, 'index']);
    Route::post('/parametros/undiad/crear', [App\Http\Controllers\UnidadesController::class, 'store']);
    Route::post('/parametros/undiad/update/{id}', [App\Http\Controllers\UnidadesController::class, 'update']);
    Route::post('/parametros/undiad/cambiarEstado/{id}', [App\Http\Controllers\UnidadesController::class, 'cambiarEstado']);


    Route::get('/parametros/institucion/getListarInstituciones', [App\Http\Controllers\InstitucionesController::class, 'index']);
    Route::post('/parametros/institucion/crear', [App\Http\Controllers\InstitucionesController::class, 'store']);
    Route::post('/parametros/institucion/update/{id}', [App\Http\Controllers\InstitucionesController::class, 'update']);
    Route::post('/parametros/institucion/cambiarEstado/{id}', [App\Http\Controllers\InstitucionesController::class, 'cambiarEstado']);

    Route::get('/parametros/tipoEmpleado/getListarTipoEmpleados', [App\Http\Controllers\TiposEmpleadosController::class, 'index']);
    Route::post('/parametros/tipoEmpleado/crear', [App\Http\Controllers\TiposEmpleadosController::class, 'store']);
    Route::post('/parametros/tipoEmpleado/update/{id}', [App\Http\Controllers\TiposEmpleadosController::class, 'update']);
    Route::post('/parametros/tipoEmpleado/cambiarEstado/{id}', [App\Http\Controllers\TiposEmpleadosController::class, 'cambiarEstado']);


    Route::get('/parametros/area/getListarAreas', [App\Http\Controllers\AreasController::class, 'index']);
    Route::post('/parametros/area/crear', [App\Http\Controllers\AreasController::class, 'store']);
    Route::post('/parametros/area/update/{id}', [App\Http\Controllers\AreasController::class, 'update']);
    Route::post('/parametros/area/cambiarEstado/{id}', [App\Http\Controllers\AreasController::class, 'cambiarEstado']);

    Route::get('/parametros/municipio/getListarMunicipios', [App\Http\Controllers\MunicipiosController::class, 'index']);
    Route::post('/parametros/municipio/crear', [App\Http\Controllers\MunicipiosController::class, 'store']);
    Route::post('/parametros/municipio/update/{id}', [App\Http\Controllers\MunicipiosController::class, 'update']);
    Route::post('/parametros/municipio/cambiarEstado/{id}', [App\Http\Controllers\MunicipiosController::class, 'cambiarEstado']);
    Route::get('/parametros/municipio/getListarMunicipiosPorArea/{id}', [App\Http\Controllers\MunicipiosController::class, 'getListarMunicipiosPorArea']);

    Route::post('/archivo/registrarArchivoUsuario', [App\Http\Controllers\ArchivosController::class, 'registrarArchivoUsuario']);
    Route::post('/archivo/registrarArchivoEmpleado', [App\Http\Controllers\ArchivosController::class, 'registrarArchivoEmpleado']);
    Route::post('/archivo/registrarArchivoActivoTecnologico', [App\Http\Controllers\ArchivosController::class, 'registrarArchivoActivoTecnologico']);

    Route::get('/procesos/empleado/getListarEmpleados', [App\Http\Controllers\EmpleadosController::class, 'index']);
    Route::post('/procesos/empleado/crear', [App\Http\Controllers\EmpleadosController::class, 'store']);
    Route::post('/procesos/empleado/update/{id}', [App\Http\Controllers\EmpleadosController::class, 'update']);
    Route::post('/procesos/empleado/cambiarEstado/{id}', [App\Http\Controllers\EmpleadosController::class, 'cambiarEstado']);
    Route::get('/procesos/empleado/getEmpleado/{id}', [App\Http\Controllers\EmpleadosController::class, 'getEmpleado']);



    Route::get('/administracion/usuario/getListarUsuarios', [App\Http\Controllers\Administracion\UsersController::class, 'index']);
    Route::post('/administracion/usuario/crear', [App\Http\Controllers\Administracion\UsersController::class, 'store']);
    Route::post('/administracion/usuario/update/{id}', [App\Http\Controllers\Administracion\UsersController::class, 'update']);
    Route::post('/administracion/usuario/cambiarEstado/{id}', [App\Http\Controllers\Administracion\UsersController::class, 'cambiarEstado']);
    Route::get('/administracion/usuario/getRolByUsuario',  [App\Http\Controllers\Administracion\UsersController::class, 'getRolByUsuario']);

    Route::get('/administracion/usuario/getListarPermisosByRolAsignado', [App\Http\Controllers\Administracion\UsersController::class, 'getListarPermisosByRolAsignado']);
    Route::get('/administracion/usuario/getListarPermisosByUsuario', [App\Http\Controllers\Administracion\UsersController::class, 'getListarPermisosByUsuario']);
    Route::post('/administracion/usuario/setRegistrarPermisosByUsuario',  [App\Http\Controllers\Administracion\UsersController::class, 'setRegistrarPermisosByUsuario']);


    Route::get('/administracion/rol/getListarRoles', [App\Http\Controllers\Administracion\RolesController::class, 'index']);

    Route::get('/administracion/rol/getListarPermisosByRol', [App\Http\Controllers\Administracion\RolesController::class, 'getListarPermisosByRol']);
    Route::post('/administracion/rol/setRegistrarRolPermisos', [App\Http\Controllers\Administracion\RolesController::class, 'setRegistrarRolPermisos']);
    Route::post('/administracion/rol/setEditarRolPermisos', [App\Http\Controllers\Administracion\RolesController::class, 'setEditarRolPermisos']);


    Route::get('/administracion/permiso/getListarPermisos', [App\Http\Controllers\Administracion\PermisosController::class, 'index']);
    Route::post('/administracion/permiso/setRegistrarPermiso', [App\Http\Controllers\Administracion\PermisosController::class, 'store']);
    Route::post('/administracion/permiso/update/{id}', [App\Http\Controllers\Administracion\PermisosController::class, 'update']);

    Route::get('/parametros/categoria/getListarCategorias', [App\Http\Controllers\CategoriasController::class, 'index']);
    Route::post('/parametros/categoria/crear', [App\Http\Controllers\CategoriasController::class, 'store']);
    Route::post('/parametros/categoria/update/{id}', [App\Http\Controllers\CategoriasController::class, 'update']);
    Route::post('/parametros/categoria/cambiarEstado/{id}', [App\Http\Controllers\CategoriasController::class, 'cambiarEstado']);


    Route::get('/parametros/tipos_activos/getListarTiposActivos', [App\Http\Controllers\TiposActivosController::class, 'index']);
    Route::post('/parametros/tipos_activos/crear', [App\Http\Controllers\TiposActivosController::class, 'store']);
    Route::post('/parametros/tipos_activos/update/{id}', [App\Http\Controllers\TiposActivosController::class, 'update']);
    Route::post('/parametros/tipos_activos/cambiarEstado/{id}', [App\Http\Controllers\TiposActivosController::class, 'cambiarEstado']);
    Route::get('/parametros/tipos_activos/getListarTiposActivosPorCategoria/{id}', [App\Http\Controllers\TiposActivosController::class, 'getListarTiposActivosPorCategoria']);

    Route::get('/parametros/marca/getListarMarcas', [App\Http\Controllers\MarcasController::class, 'index']);
    Route::post('/parametros/marca/crear', [App\Http\Controllers\MarcasController::class, 'store']);
    Route::post('/parametros/marca/update/{id}', [App\Http\Controllers\MarcasController::class, 'update']);
    Route::post('/parametros/marca/cambiarEstado/{id}', [App\Http\Controllers\MarcasController::class, 'cambiarEstado']);

    Route::get('/parametros/estado_activo/getListarEstadosActivos', [App\Http\Controllers\EstadosActivosController::class, 'index']);
    Route::post('/parametros/estado_activo/crear', [App\Http\Controllers\EstadosActivosController::class, 'store']);
    Route::post('/parametros/estado_activo/update/{id}', [App\Http\Controllers\EstadosActivosController::class, 'update']);
    Route::post('/parametros/estado_activo/cambiarEstado/{id}', [App\Http\Controllers\EstadosActivosController::class, 'cambiarEstado']);


    Route::get('/parametros/tipo_ingreso/getListarTiposIngresos', [App\Http\Controllers\TipoIngresoController::class, 'index']);
    Route::post('/parametros/tipo_ingreso/crear', [App\Http\Controllers\TipoIngresoController::class, 'store']);
    Route::post('/parametros/tipo_ingreso/update/{id}', [App\Http\Controllers\TipoIngresoController::class, 'update']);
    Route::post('/parametros/tipo_ingreso/cambiarEstado/{id}', [App\Http\Controllers\TipoIngresoController::class, 'cambiarEstado']);

    Route::get('/procesos/activos_tecnologicos/getListarActivosTenologicos', [App\Http\Controllers\ActivosTecnologicosController::class, 'index']);
    Route::post('/procesos/activos_tecnologicos/crear', [App\Http\Controllers\ActivosTecnologicosController::class, 'store']);
    Route::post('/procesos/activos_tecnologicos/update/{id}', [App\Http\Controllers\ActivosTecnologicosController::class, 'update']);
    Route::post('/procesos/activos_tecnologicos/cambiarEstado/{id}', [App\Http\Controllers\ActivosTecnologicosController::class, 'bajaActivo']);

    Route::get('/procesos/activos_tecnologicos/activosTecnologicosActivos', [App\Http\Controllers\ActivosTecnologicosController::class, 'activosTecnologicosActivos']);

    Route::post('/procesos/activos_tecnologicos/setRegistrarActivosAsignados', [App\Http\Controllers\ActivosTecnologicosController::class, 'setRegistrarActivosAsignados']);
    //Route::post('/administracion/rol/setEditarRolPermisos', [App\Http\Controllers\Administracion\RolesController::class, 'setEditarRolPermisos']);
});
Route::get('/{optional?}', function () {
    return view('app');
})->name('basepath');
