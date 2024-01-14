<?php

namespace App\Http\Controllers;

use App\Models\municipios;
use Illuminate\Http\Request;
use App\Services\NumeraService;
use Illuminate\Support\Facades\DB;

class MunicipiosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $municipios = DB::table('municipios')
        ->join('areas', 'areas.id', '=', 'municipios.area_id')
        ->select('municipios.id', 'municipios.codigo','municipios.descripcion','municipios.estado', 
        'municipios.area_id','areas.descripcion as area_descripcion','areas.created_at','areas.updated_at')
        ->get();
    return response()->json($municipios);
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
        $numera = $numeraService->getCorrelativo(5);
       
        $municipio = new municipios;
        $municipio->codigo = (string)$numera;
        $municipio->descripcion = strtoupper($request->input("descripcion"));
        $municipio->area_id= $request->area_id;
        $municipio->save();
        $numeraService->updateCorrelativo(5);
        
        return response()->json('Municipio almacenada exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(municipios $municipios)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(municipios $municipios)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $municipio = municipios::find($id);
        $municipio->descripcion=strtoupper($request->descripcion);
        $municipio->areas_id=$request->areas_id;
        $municipio->save();

        return response()->json('Municipio actualizada exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(municipios $municipios)
    {
        //
    }

    public function cambiarEstado($id)
    {
        $municipio = municipios::find($id);
        $municipio->estado=$municipio->estado=='A'?'I':'A';
        $municipio->save();
        return response()->json('Municipio actualizada exitosamente el estado!');
    }

    public function getListarMunicipiosPorArea($areaId)
    {
        $municipio = municipios::where('area_id', $areaId)->get();
        return response()->json($municipio);
    }
}
