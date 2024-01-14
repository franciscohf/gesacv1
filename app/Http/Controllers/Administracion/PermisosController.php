<?php

namespace App\Http\Controllers\Administracion;

use App\Models\permisos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class PermisosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(permisos::latest()->get());
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
       
        $permiso= new permisos;
        $permiso->nombre =$request->nombre ;
        $permiso->codigo=$request->codigo;
        $permiso->save();
      
        
        return response()->json('El Permiso almacenada exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(permisos $permisos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(permisos $permisos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $permiso = permisos::find($id);
        $permiso->nombre =$request->nombre ;
        $permiso->codigo=$request->codigo;
        $permiso->save();

        return response()->json('El Permiso actualizada exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(permisos $permisos)
    {
        //
    }
}
