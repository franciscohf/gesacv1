<?php

namespace App\Http\Controllers;

use App\Models\tipos_activos;
use Illuminate\Http\Request;
use App\Services\NumeraService;
use Illuminate\Support\Facades\DB;

class TiposActivosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tiposActivos = DB::table('tipos_activos')
        ->join('categorias', 'tipos_activos.categoria_id', '=', 'categorias.id')
        ->select('tipos_activos.*', 'categorias.descripcion as categoria_descripcion',
        DB::raw("(CASE WHEN tipos_activos.estado = 'A' THEN 'Activo' ELSE 'Inactivo' END) as estado_dsc"))
        ->get();
    return response()->json($tiposActivos);
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
       
        $tipoActivo = new tipos_activos;
        $tipoActivo->codigo = (string)$numera;
        $tipoActivo->descripcion = strtoupper($request->input("descripcion"));
        $tipoActivo->categoria_id= $request->categoria_id;
        $tipoActivo->save();
        $numeraService->updateCorrelativo(8);
        
        return response()->json('Tipos Activos almacenada exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(tipos_activos $tipos_activos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(tipos_activos $tipos_activos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $tipoActivos = tipos_activos::find($id);
        $tipoActivos->descripcion=strtoupper($request->descripcion);
        $tipoActivos->categoria_id=$request->categoria_id;
        $tipoActivos->save();

        return response()->json('Tipos Activos actualizada exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(tipos_activos $tipos_activos)
    {
        //
    }

    public function cambiarEstado($id)
    {
        $tipoActivo = tipos_activos::find($id);
        $tipoActivo->estado=$tipoActivo->estado=='A'?'I':'A';
        $tipoActivo->save();
        return response()->json('Tipos Activos actualizada exitosamente el estado!');
    }

    public function getListarTiposActivosPorCategoria($categoriaId)
    {
        $tiposActivos = tipos_activos::where('categoria_id', $categoriaId)->get();
        return response()->json($tiposActivos);
    }
}
