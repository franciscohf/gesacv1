<?php

namespace App\Http\Controllers;

use App\Models\marcas;
use Illuminate\Http\Request;
use App\Services\NumeraService;
use Illuminate\Support\Facades\DB;

class MarcasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $marcas = DB::table('marcas')
            ->select(
                'marcas.*',
                DB::raw("(CASE WHEN marcas.estado = 'A' THEN 'Activo' ELSE 'Inactivo' END) as estado_dsc")
            )
            ->get();
        return response()->json($marcas);
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
        $numera = $numeraService->getCorrelativo(8);
        $marca = new marcas;
        $marca->codigo = (string)$numera;
        $marca->descripcion = strtoupper($request->input("descripcion"));
        $marca->save();
        $numeraService->updateCorrelativo(8);

        return response()->json('Marca almacenada exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(marcas $marcas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(marcas $marcas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $marca = marcas::find($id);
        $marca->descripcion=strtoupper($request->descripcion);
        $marca->save();

        return response()->json('Marca actualizada exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(marcas $marcas)
    {
        //
    }

    public function cambiarEstado($id)
    {
        $marca = marcas::find($id);
        $marca->estado=$marca->estado=='A'?'I':'A';
        $marca->save();
        return response()->json('Marca actualizada exitosamente el estado!');
    }
}
