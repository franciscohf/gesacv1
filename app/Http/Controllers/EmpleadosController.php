<?php

namespace App\Http\Controllers;

use App\Models\empleados;
use Illuminate\Http\Request;
use App\Services\NumeraService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EmpleadosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = DB::table('empleados')
            ->Join('tipos_empleados', 'empleados.tipo_empleado_id', '=', 'tipos_empleados.id')
            ->Join('unidades', 'empleados.unidad_id', '=', 'unidades.id')
            ->Join('instituciones', 'empleados.institucion_id', '=', 'instituciones.id')
            ->Join('municipios', 'empleados.municipio_id', '=', 'municipios.id')
            ->Join('areas', 'municipios.area_id', '=', 'areas.id')
            ->leftJoin('archivos', 'empleados.archivo_img_id', '=', 'archivos.id')
            ->select(
                'empleados.*',
                'areas.id as area_id',
                'archivos.path',
                DB::raw("CONCAT(apellido_paterno,' ',apellido_materno,' ',primer_nombre,' ',segundo_nombre) as razon_social"),
                DB::raw("(CASE WHEN empleados.estado = 'A' THEN 'Activo' ELSE 'Inactivo' END) as estado_dsc"),
            )
            // ->orderBy('id', 'desc')
            ->get();
        return response()->json($usuarios);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fotografia    =   $request->fotografia;
        $fotografia    =   ($fotografia   ==  0) ? ($fotografia   =   NULL) :   $fotografia;
        $numeraService = new NumeraService();
        $numera = $numeraService->getCorrelativo(6);

        $empleado = new empleados;
        $empleado->codigo = (string)$numera;
        $empleado->primer_nombre = strtoupper($request->primer_nombre);
        $empleado->segundo_nombre = strtoupper($request->segundo_nombre);
        $empleado->apellido_paterno = strtoupper($request->apellido_paterno);
        $empleado->apellido_materno = strtoupper($request->apellido_materno);
        $empleado->fecha_nacimiento = $request->fecha_nacimiento;
        $empleado->tarjeta_identificacion = strtoupper($request->tarjeta_identificacion);
        $empleado->ci = strtoupper($request->ci);
        $empleado->direccion = strtoupper($request->direccion);
        $empleado->telefono = strtoupper($request->telefono);
        $empleado->tipo_empleado_id = $request->tipo_empleado_id;
        $empleado->unidad_id = $request->unidad_id;
        $empleado->institucion_id = $request->institucion_id;
        $empleado->municipio_id = $request->municipio_id;
        $empleado->archivo_img_id = $fotografia;
        $empleado->user_id = Auth::id();
        $empleado->save();
        $numeraService->updateCorrelativo(6);

        return response()->json('Empleado almacenada exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(empleados $empleados)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(empleados $empleados)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $fotografia    =   $request->fotografia;
        $fotografia    =   ($fotografia   ==  0) ? ($fotografia   =   NULL) :   $fotografia;
        $empleado = empleados::find($id);

        if ($fotografia != NULL) {
            $empleado->archivo_img_id = $fotografia;
        }

        $empleado->primer_nombre = strtoupper($request->primer_nombre);
        $empleado->segundo_nombre = strtoupper($request->segundo_nombre);
        $empleado->apellido_paterno = strtoupper($request->apellido_paterno);
        $empleado->apellido_materno = strtoupper($request->apellido_materno);
        $empleado->fecha_nacimiento = $request->fecha_nacimiento;
        $empleado->tarjeta_identificacion = strtoupper($request->tarjeta_identificacion);
        $empleado->ci = strtoupper($request->ci);
        $empleado->direccion = strtoupper($request->direccion);
        $empleado->telefono = strtoupper($request->telefono);
        $empleado->tipo_empleado_id = $request->tipo_empleado_id;
        $empleado->unidad_id = $request->unidad_id;
        $empleado->institucion_id = $request->institucion_id;
        $empleado->municipio_id = $request->municipio_id;
        $empleado->user_id = Auth::id();
        $empleado->save();


        return response()->json('Empleado Modificado exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(empleados $empleados)
    {
        //
    }
    public function cambiarEstado($id)
    {
        $empleado = empleados::find($id);
        $empleado->estado = $empleado->estado == 'A' ? 'I' : 'A';
        $empleado->save();
        return response()->json('Empleado actualizada exitosamente el estado!');
    }

    public function getEmpleado($id)
    {
        $empleado = DB::table('empleados')
            ->Join('tipos_empleados', 'empleados.tipo_empleado_id', '=', 'tipos_empleados.id')
            ->Join('unidades', 'empleados.unidad_id', '=', 'unidades.id')
            ->Join('instituciones', 'empleados.institucion_id', '=', 'instituciones.id')
            ->Join('municipios', 'empleados.municipio_id', '=', 'municipios.id')
            ->Join('areas', 'municipios.area_id', '=', 'areas.id')
            ->leftJoin('archivos', 'empleados.archivo_img_id', '=', 'archivos.id')
            ->select(
                'empleados.*',
                'areas.id as area_id',
                'unidades.descripcion as unidad_dsc',
                'instituciones.descripcion as institucion_dsc',
                'archivos.path',
                DB::raw("CONCAT(apellido_paterno,' ',apellido_materno,' ',primer_nombre,' ',segundo_nombre) as razon_social"),
                DB::raw("(CASE WHEN empleados.estado = 'A' THEN 'Activo' ELSE 'Inactivo' END) as estado_dsc"),
            )
            ->where('empleados.id', $id)
            ->get()->first();

  
        return $empleado;
    }
}
