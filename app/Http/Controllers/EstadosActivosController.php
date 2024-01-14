<?php

namespace App\Http\Controllers;

use App\Models\estados_activos;
use Illuminate\Http\Request;
use App\Services\NumeraService;
use Illuminate\Support\Facades\DB;

class EstadosActivosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estadosActivos = DB::table('estados_activos')
            ->select(
                'estados_activos.*',
                DB::raw("(CASE WHEN estados_activos.estado = 'A' THEN 'Activo' ELSE 'Inactivo' END) as estado_dsc")
            )
            ->get();
        return response()->json($estadosActivos);
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
        $numeraService = new NumeraService();
        $numera = $numeraService->getCorrelativo(9);
        $estadosActivos = new estados_activos;
        $estadosActivos->codigo = (string)$numera;
        $estadosActivos->descripcion = strtoupper($request->input("descripcion"));
        $estadosActivos->save();
        $numeraService->updateCorrelativo(9);
        
        return response()->json('Estado Activos almacenada exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(estados_activos $estados_activos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(estados_activos $estados_activos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $estadosActivos = estados_activos::find($id);
        $estadosActivos->descripcion=strtoupper($request->descripcion);
        $estadosActivos->save();

        return response()->json('Estado Activos actualizada exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(estados_activos $estados_activos)
    {
        //
    }

    public function cambiarEstado($id)
    {
        $estadosActivos = estados_activos::find($id);
        $estadosActivos->estado=$estadosActivos->estado=='A'?'I':'A';
        $estadosActivos->save();
        return response()->json('Estado Activos actualizada exitosamente el estado!');
    }
}
