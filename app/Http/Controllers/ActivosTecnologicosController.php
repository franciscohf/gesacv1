<?php

namespace App\Http\Controllers;

use App\Models\historico_activos;
use App\Models\activos_tecnologicos;
use App\Models\estados;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\NumeraService;


class ActivosTecnologicosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activosTecnologicos = DB::table('activos_tecnologicos')
            ->Join('tipos_activos', 'activos_tecnologicos.tipo_activo_id', '=', 'tipos_activos.id')
            ->Join('categorias', 'tipos_activos.categoria_id', '=', 'categorias.id')
            ->Join('estados_activos', 'activos_tecnologicos.estado_activo_id', '=', 'estados_activos.id')
            ->Join('marcas', 'activos_tecnologicos.marca_id', '=', 'marcas.id')
            ->Join('tipo_ingresos', 'activos_tecnologicos.tipo_ingreso_id', '=', 'tipo_ingresos.id')
            ->Join('areas', 'activos_tecnologicos.area_id', '=', 'areas.id')
            ->Join('estados', 'activos_tecnologicos.estado_id', '=', 'estados.id')
            ->leftJoin('archivos', 'activos_tecnologicos.archivo_img_id', '=', 'archivos.id')
            ->select(
                'activos_tecnologicos.*',
                'categorias.id as categoria_id',
                'estados_activos.descripcion as estado_dsc',
                'tipos_activos.descripcion as tipo_activo_dsc',
                'areas.descripcion as area_dsc',
                'estados_activos.codigo as estado_codigo',
                'estados.codigo as estado_codigo_reg',
                'estados.descripcion as estado_dsc_reg',
                'archivos.path as imagen',
            )

            ->get();
        return response()->json($activosTecnologicos);
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

        $fotografia    =   $request->imagen;
        $fotografia    =   ($fotografia   ==  0) ? ($fotografia   =   NULL) :   $fotografia;
        try {
            DB::beginTransaction();
            $numeraService = new NumeraService();
            $numera = $numeraService->getCorrelativo(10);
            $activoTecnologico = new activos_tecnologicos;
            $activoTecnologico->codigo = (string)$numera;
            $activoTecnologico->nombre = strtoupper($request->nombre);
            $activoTecnologico->marca_id = $request->marca_id;
            $activoTecnologico->modelo = strtoupper($request->modelo);
            $activoTecnologico->serie = strtoupper($request->serie);
            $activoTecnologico->caracteristicas = strtoupper($request->caracteristicas);
            $activoTecnologico->estado_activo_id = $request->estado_activo_id;
            $activoTecnologico->tipo_activo_id = $request->tipo_activo_id;
            $activoTecnologico->tipo_ingreso_id = $request->tipo_ingreso_id;
            $activoTecnologico->area_id = $request->area_id;
            $activoTecnologico->usuario_registro_id = Auth::id();
            $activoTecnologico->usuario_actualizacion_id = Auth::id();
            $activoTecnologico->estado_id = 1;
            $activoTecnologico->archivo_img_id = $fotografia;
            $activoTecnologico->save();
            $numeraService->updateCorrelativo(10);

            $hitoricoActivos = new historico_activos();
            $hitoricoActivos->activo_tecnologico_id = $activoTecnologico->id;
            $hitoricoActivos->codigo == $activoTecnologico->codigo;
            $hitoricoActivos->nombre =  $activoTecnologico->nombre;
            $hitoricoActivos->marca_id = $activoTecnologico->marca_id;
            $hitoricoActivos->modelo =  $activoTecnologico->modelo;
            $hitoricoActivos->serie =  $activoTecnologico->serie;
            $hitoricoActivos->caracteristicas =  $activoTecnologico->caracteristicas;
            $hitoricoActivos->estado_activo_id =  $activoTecnologico->estado_activo_id;
            $hitoricoActivos->tipo_activo_id =  $activoTecnologico->tipo_activo_id;
            $hitoricoActivos->tipo_ingreso_id =  $activoTecnologico->tipo_ingreso_id;
            $hitoricoActivos->area_id = $activoTecnologico->area_id;
            $hitoricoActivos->usuario_registro_id = $activoTecnologico->usuario_registro_id;
            $hitoricoActivos->usuario_actualizacion_id =  $activoTecnologico->usuario_actualizacion_id;
            $hitoricoActivos->estado_id =  $activoTecnologico->estado_id;
            $hitoricoActivos->archivo_img_id =  $activoTecnologico->archivo_img_id;

            $hitoricoActivos->save();
            DB::commit();
            return response()->json(["success" => "true", "mensaje" => "Activos Tecnológicos almacenada exitosamente"]);
        } catch (\Illuminate\Database\QueryException $ex) {
            // capturara algun error ocurrido en el "try"
            DB::rollBack();
            return response()->json(["success" => "false", "mensaje" => $ex]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(activos_tecnologicos $activos_tecnologicos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(activos_tecnologicos $activos_tecnologicos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $fotografia    =   $request->imagen;
        $fotografia    =   ($fotografia   ==  0) ? ($fotografia   =   NULL) :   $fotografia;
        try {
            $activoTecnologico = activos_tecnologicos::find($id);

            if ($fotografia != NULL) {
                $activoTecnologico->archivo_img_id = $fotografia;
            }

            $activoTecnologico->nombre = strtoupper($request->nombre);
            $activoTecnologico->marca_id = $request->marca_id;
            $activoTecnologico->modelo = strtoupper($request->modelo);
            $activoTecnologico->serie = strtoupper($request->serie);
            $activoTecnologico->caracteristicas = strtoupper($request->caracteristicas);
            $activoTecnologico->estado_activo_id = $request->estado_activo_id;
            $activoTecnologico->tipo_activo_id = $request->tipo_activo_id;
            $activoTecnologico->tipo_ingreso_id = $request->tipo_ingreso_id;
            $activoTecnologico->area_id = $request->area_id;
            $activoTecnologico->usuario_actualizacion_id = Auth::id();
            $activoTecnologico->save();

            $hitoricoActivos = new historico_activos();
            $hitoricoActivos->activo_tecnologico_id = $activoTecnologico->id;
            $hitoricoActivos->codigo == $activoTecnologico->codigo;
            $hitoricoActivos->nombre =  $activoTecnologico->nombre;
            $hitoricoActivos->marca_id = $activoTecnologico->marca_id;
            $hitoricoActivos->modelo =  $activoTecnologico->modelo;
            $hitoricoActivos->serie =  $activoTecnologico->serie;
            $hitoricoActivos->caracteristicas =  $activoTecnologico->caracteristicas;
            $hitoricoActivos->estado_activo_id =  $activoTecnologico->estado_activo_id;
            $hitoricoActivos->tipo_activo_id =  $activoTecnologico->tipo_activo_id;
            $hitoricoActivos->tipo_ingreso_id =  $activoTecnologico->tipo_ingreso_id;
            $hitoricoActivos->area_id = $activoTecnologico->area_id;
            $hitoricoActivos->usuario_registro_id = $activoTecnologico->usuario_registro_id;
            $hitoricoActivos->usuario_actualizacion_id =  $activoTecnologico->usuario_actualizacion_id;
            $hitoricoActivos->estado_id =  $activoTecnologico->estado_id;
            $hitoricoActivos->archivo_img_id =  $activoTecnologico->archivo_img_id;


            $hitoricoActivos->save();
            DB::commit();
            return response()->json(["success" => "true", "mensaje" => "Activos Tecnológicos modificado exitosamente"]);
        } catch (\Illuminate\Database\QueryException $ex) {
            // capturara algun error ocurrido en el "try"
            DB::rollBack();
            return response()->json(["success" => "false", "mensaje" => $ex]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(activos_tecnologicos $activos_tecnologicos)
    {
        //
    }

    public function bajaActivo($id)
    {
        try {
            $activoTecnologico = activos_tecnologicos::find($id);
            $estado = estados::where('codigo', 99)->get()->first();;
            $activoTecnologico->estado_id = $estado->id;
            $activoTecnologico->save();

            $hitoricoActivos = new historico_activos();
            $hitoricoActivos->activo_tecnologico_id = $activoTecnologico->id;
            $hitoricoActivos->codigo == $activoTecnologico->codigo;
            $hitoricoActivos->nombre =  $activoTecnologico->nombre;
            $hitoricoActivos->marca_id = $activoTecnologico->marca_id;
            $hitoricoActivos->modelo =  $activoTecnologico->modelo;
            $hitoricoActivos->serie =  $activoTecnologico->serie;
            $hitoricoActivos->caracteristicas =  $activoTecnologico->caracteristicas;
            $hitoricoActivos->estado_activo_id =  $activoTecnologico->estado_activo_id;
            $hitoricoActivos->tipo_activo_id =  $activoTecnologico->tipo_activo_id;
            $hitoricoActivos->tipo_ingreso_id =  $activoTecnologico->tipo_ingreso_id;
            $hitoricoActivos->area_id = $activoTecnologico->area_id;
            $hitoricoActivos->usuario_registro_id = $activoTecnologico->usuario_registro_id;
            $hitoricoActivos->usuario_actualizacion_id =  $activoTecnologico->usuario_actualizacion_id;
            $hitoricoActivos->estado_id =  $activoTecnologico->estado_id;
            $hitoricoActivos->archivo_img_id =  $activoTecnologico->archivo_img_id;
            $hitoricoActivos->save();

            DB::commit();
            return response()->json(["success" => "true", "mensaje" => "Activos Tecnológicos modificado estado"]);
        } catch (\Illuminate\Database\QueryException $ex) {
            // capturara algun error ocurrido en el "try"
            DB::rollBack();
            return response()->json(["success" => "false", "mensaje" => $ex]);
        }
    }

    public function activosTecnologicosActivos()
    {
        $activosTecnologicos = DB::table('activos_tecnologicos')
            ->Join('tipos_activos', 'activos_tecnologicos.tipo_activo_id', '=', 'tipos_activos.id')
            ->Join('categorias', 'tipos_activos.categoria_id', '=', 'categorias.id')
            ->Join('estados_activos', 'activos_tecnologicos.estado_activo_id', '=', 'estados_activos.id')
            ->Join('marcas', 'activos_tecnologicos.marca_id', '=', 'marcas.id')
            ->Join('tipo_ingresos', 'activos_tecnologicos.tipo_ingreso_id', '=', 'tipo_ingresos.id')
            ->Join('areas', 'activos_tecnologicos.area_id', '=', 'areas.id')
            ->Join('estados', 'activos_tecnologicos.estado_id', '=', 'estados.id')
            ->leftJoin('archivos', 'activos_tecnologicos.archivo_img_id', '=', 'archivos.id')
            ->select(
                'activos_tecnologicos.*',
                'categorias.id as categoria_id',
                'estados_activos.descripcion as estado_dsc',
                'tipos_activos.descripcion as tipo_activo_dsc',
                'areas.descripcion as area_dsc',
                'estados_activos.codigo as estado_codigo',
                'estados.codigo as estado_codigo_reg',
                'estados.descripcion as estado_dsc_reg',
                'archivos.path as imagen',
            )
            ->where('activos_tecnologicos.estado_id', '=', 1)
            ->get();
        return response()->json($activosTecnologicos);
    }
}
