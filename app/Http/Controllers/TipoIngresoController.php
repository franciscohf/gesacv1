<?php

namespace App\Http\Controllers;

use App\Models\tipo_ingreso;
use Illuminate\Http\Request;
use App\Services\NumeraService;
use Illuminate\Support\Facades\DB;

class TipoIngresoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipoIngresos = DB::table('tipo_ingresos')
       ->select('tipo_ingresos.*',  
       DB::raw("(CASE WHEN tipo_ingresos.estado = 'A' THEN 'Activo' ELSE 'Inactivo' END) as estado_dsc"))
        ->get();
    return response()->json($tipoIngresos);
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
        $tipoIngreso = new tipo_ingreso;
        $tipoIngreso->codigo = (string)$numera;
        $tipoIngreso->descripcion = strtoupper($request->input("descripcion"));
        $tipoIngreso->save();
        $numeraService->updateCorrelativo(9);
        
        return response()->json('Tipo de Ingreso almacenada exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(tipo_ingreso $tipo_ingreso)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(tipo_ingreso $tipo_ingreso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $tipoIngreso = tipo_ingreso::find($id);
        $tipoIngreso->descripcion=strtoupper($request->descripcion);
        $tipoIngreso->save();

        return response()->json('Tipos de Ingresos actualizada exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(tipo_ingreso $tipo_ingreso)
    {
        //
    }

    public function cambiarEstado($id)
    {
        $tipoIngreso = tipo_ingreso::find($id);
        $tipoIngreso->estado=$tipoIngreso->estado=='A'?'I':'A';
        $tipoIngreso->save();
        return response()->json('Tipos de Ingresos actualizada exitosamente el estado!');
    }
}
