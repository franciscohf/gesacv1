<?php

namespace App\Http\Controllers;

use App\Models\archivos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
class ArchivosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(archivos $archivos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(archivos $archivos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, archivos $archivos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(archivos $archivos)
    {
        //
    }
    public function registrarArchivoUsuario(Request $request)
    {
        $file       =   $request->file;
        $bandera    =   Str::random(10);
        $filename   =   $file->getClientOriginalName();
        $fileserver =   $bandera .'_'. $filename;
        //$nIdAuthUser=   Auth::id();

        Storage::putFileAs('public/usuarios', $file, $fileserver);

        $archivo = new Archivos;
        $archivo->path =  asset('storage/usuarios/'. $fileserver);
        $archivo->nombre_archivo =  $filename;
        $archivo->created_by=Auth::id();
        $archivo->updated_by=Auth::id();
        $archivo->save();
      
      
        return  response()->json($archivo->id);
    }

    public function registrarArchivoEmpleado(Request $request)
    {
        $file       =   $request->file;
        $bandera    =   Str::random(10);
        $filename   =   $file->getClientOriginalName();
        $fileserver =   $bandera .'_'. $filename;
        //$nIdAuthUser=   Auth::id();

        Storage::putFileAs('public/empleados', $file, $fileserver);

        $archivo = new Archivos;
        $archivo->path =  asset('storage/empleados/'. $fileserver);
        $archivo->nombre_archivo =  $filename;
        $archivo->created_by=1;
        $archivo->updated_by=1;
        $archivo->save();
      
      
        return  response()->json($archivo->id);
    }

    public function registrarArchivoActivoTecnologico(Request $request)
    {
        $file       =   $request->file;
        $bandera    =   Str::random(10);
        $filename   =   $file->getClientOriginalName();
        $fileserver =   $bandera .'_'. $filename;
        //$nIdAuthUser=   Auth::id();

        Storage::putFileAs('public/activos_tec', $file, $fileserver);

        $archivo = new Archivos;
        $archivo->path =  asset('storage/activos_tec/'. $fileserver);
        $archivo->nombre_archivo =  $filename;
        $archivo->created_by=Auth::id();
        $archivo->updated_by=Auth::id();
        $archivo->save();
      
      
        return  response()->json($archivo->id);
    }
}
