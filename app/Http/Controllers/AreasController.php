<?php

namespace App\Http\Controllers;

use App\Models\areas;
use Illuminate\Http\Request;
use App\Services\NumeraService;

class AreasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(areas::latest()->get());
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
        $numera = $numeraService->getCorrelativo(4);
        $area = new areas;
        $area->codigo = (string)$numera;
        $area->descripcion = strtoupper($request->input("descripcion"));
        $area->save();
        $numeraService->updateCorrelativo(4);
        
        return response()->json('Area almacenada exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(areas $areas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(areas $areas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $area = areas::find($id);
        $area->descripcion=strtoupper($request->descripcion);
        $area->save();

        return response()->json('Area actualizada exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(areas $areas)
    {
        //
    }

    public function cambiarEstado($id)
    {
        $area = areas::find($id);
        $area->estado=$area->estado=='A'?'I':'A';
        $area->save();
        return response()->json('Area actualizada exitosamente el estado!');
    }
}
