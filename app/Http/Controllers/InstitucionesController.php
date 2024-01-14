<?php

namespace App\Http\Controllers;

use App\Models\instituciones;
use Illuminate\Http\Request;
use App\Services\NumeraService;

class InstitucionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(instituciones::latest()->get());
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
        $numera = $numeraService->getCorrelativo(2);
        $institucion = new instituciones;
        $institucion->codigo = (string)$numera;
        $institucion->descripcion = strtoupper($request->input("descripcion"));
        $institucion->save();
        $numeraService->updateCorrelativo(2);

        return response()->json('Institucion almacenada exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(instituciones $instituciones)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(instituciones $instituciones)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        
        $institucion = instituciones::find($id);
        $institucion->descripcion=strtoupper($request->descripcion);
        $institucion->save();

        return response()->json('Institucion actualizada exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(instituciones $instituciones)
    {
        //
    }
    public function cambiarEstado($id)
    {
        $institucion = instituciones::find($id);
        $institucion->estado=$institucion->estado=='A'?'I':'A';
        $institucion->save();
        return response()->json('Institucion actualizada exitosamente el estado!');
    }
}
