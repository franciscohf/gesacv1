<?php

namespace App\Http\Controllers;

use App\Models\unidades;
use Illuminate\Http\Request;
use App\Services\NumeraService;
class UnidadesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(unidades::latest()->get());
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
        $numera = $numeraService->getCorrelativo(1);
        $unidades = new unidades;
        $unidades->codigo = (string)$numera;
        $unidades->descripcion = strtoupper($request->input("descripcion"));
        $unidades->save();
        $numeraService->updateCorrelativo(1);

        return response()->json('Unidad almacenada exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(unidades $unidades)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(unidades $unidades)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $unidad = unidades::find($id);
        $unidad->descripcion=strtoupper($request->descripcion);
        $unidad->save();

        return response()->json('Unidad actualizada exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(unidades $unidades)
    {
        //
    }

    
    public function cambiarEstado($id)
    {
        $unidad = unidades::find($id);
        $unidad->estado=$unidad->estado=='A'?'I':'A';
        $unidad->save();
        return response()->json('Unidad actualizada exitosamente el estado!');
    }
}
