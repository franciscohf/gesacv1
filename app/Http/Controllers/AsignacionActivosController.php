<?php

namespace App\Http\Controllers;

use App\Models\asignacion_activos;
use App\Models\asignacion_activos_detalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsignacionActivosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(asignacion_activos $asignacion_activos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(asignacion_activos $asignacion_activos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, asignacion_activos $asignacion_activos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(asignacion_activos $asignacion_activos)
    {
        //
    }

    public function setRegistrarActivosAsignados(Request $request)
    {

        $empleado_id    =   $request->empleado_id;
        $descripcion    =   $request->descripcion;
        $observacion    =   $request->observacion;

        try {
            DB::beginTransaction();

            $asignacionActivo = new asignacion_activos;
            $asignacionActivo->empleado_id = $empleado_id;
            $asignacionActivo->descripcion = $descripcion;
            $asignacionActivo->observacion = $observacion;

            $asignacionActivo->save();
            $nIdAsigancion = $asignacionActivo->id;

            $listActivosAsignados       =   $request->listActivosAsignadosFilter;
            $listActivosAsignadosSize   =   sizeof($listActivosAsignados);



            if ($listActivosAsignadosSize > 0) {
                foreach ($listActivosAsignados as $key => $value) {
                    if ($value['checked'] == true) {
                        $asignacionActivosDetalle = asignacion_activos_detalle::create([
                            'asignacion_activo_id' => $nIdAsigancion,
                            'activo_tecnologico_id' => $value['id'],

                        ]);
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            // capturara algun error ocurrido en el "try"
            DB::rollBack();
            return $e;
        }
    }
}
