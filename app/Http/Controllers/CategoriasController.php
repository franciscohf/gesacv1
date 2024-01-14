<?php

namespace App\Http\Controllers;

use App\Models\categorias;
use Illuminate\Http\Request;
use App\Services\NumeraService;
use Illuminate\Support\Facades\DB;

class CategoriasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = DB::table('categorias')
       ->select('categorias.*',  
       DB::raw("(CASE WHEN categorias.estado = 'A' THEN 'Activo' ELSE 'Inactivo' END) as estado_dsc"))
        ->get();
    return response()->json($categorias);
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
        $numera = $numeraService->getCorrelativo(7);
        $categoria = new categorias;
        $categoria->codigo = (string)$numera;
        $categoria->descripcion = strtoupper($request->input("descripcion"));
        $categoria->save();
        $numeraService->updateCorrelativo(7);
        
        return response()->json('Categoria almacenada exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(categorias $categorias)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(categorias $categorias)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $categoria = categorias::find($id);
        $categoria->descripcion=strtoupper($request->descripcion);
        $categoria->save();

        return response()->json('Categoria actualizada exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(categorias $categorias)
    {
        //
    }
    public function cambiarEstado($id)
    {
        $categoria = categorias::find($id);
        $categoria->estado=$categoria->estado=='A'?'I':'A';
        $categoria->save();
        return response()->json('Categoria actualizada exitosamente el estado!');
    }
}
