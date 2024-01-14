<?php

namespace App\Http\Controllers;

use App\Models\tipos_empleados;
use Illuminate\Http\Request;
use App\Services\NumeraService;

class TiposEmpleadosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(tipos_empleados::latest()->get());
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
        $numera = $numeraService->getCorrelativo(3);
        $tipos_empleados = new tipos_empleados;
        $tipos_empleados->codigo = (string)$numera;
        $tipos_empleados->descripcion = strtoupper($request->input("descripcion"));
        $tipos_empleados->save();
        $numeraService->updateCorrelativo(3);

        return response()->json('Tipo de Empleado almacenada exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(tipos_empleados $tipos_empleados)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(tipos_empleados $tipos_empleados)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $tipos_empleados = tipos_empleados::find($id);
        $tipos_empleados->descripcion=strtoupper($request->descripcion);
        $tipos_empleados->save();

        return response()->json('Tipo de Empleado actualizada exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(tipos_empleados $tipos_empleados)
    {
        //
    }

    public function cambiarEstado($id)
    {
        $tipos_empleados = tipos_empleados::find($id);
        $tipos_empleados->estado=$tipos_empleados->estado=='A'?'I':'A';
        $tipos_empleados->save();
        return response()->json('Tipo de Empleado actualizada exitosamente el estado!');
    }
}
