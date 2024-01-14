<?php

namespace App\Http\Controllers\Administracion;

use App\Models\roles;
use App\Models\roles_permisos;
use App\Http\Controllers\Controller;
use App\Models\permisos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(roles::latest()->get());
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
    public function show(roles $roles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(roles $roles)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, roles $roles)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(roles $roles)
    {
        //
    }

    public function getListarPermisosByRol(Request $request)
    {


        $nIdRol     =   $request->nIdRol;

        $nIdRol     =   ($nIdRol   ==  NULL) ? ($nIdRol   =   0) :   $nIdRol;
        $permisos = DB::table('permisos')
            ->leftjoin('roles_permisos', function ($leftjoin) use ($nIdRol){
                $leftjoin
                ->on('permisos.id', '=', 'roles_permisos.permiso_id')
                ->where('roles_permisos.role_id', '=', $nIdRol );
               
            })
            ->select(
                'permisos.*',
                DB::raw("(CASE WHEN roles_permisos.role_id is null  THEN 0 ELSE 1 END) as checked")
            )
            ->get();
    
        return response()->json($permisos);
    }

    public function setRegistrarRolPermisos(Request $request)
    {

        $cNombre    =   $request->cNombre;
        $cSlug      =   $request->cSlug;

        $cNombre    =   ($cNombre   ==  NULL) ? ($cNombre   =   '') :   $cNombre;
        $cSlug      =   ($cSlug   ==  NULL) ? ($cSlug   =   '') :   $cSlug;

         try {
            DB::beginTransaction();

        $rol = new roles;
        $rol->nombre = $cNombre;
        $rol->url = $cSlug;
        // $rol->created_at = new \DateTime();
        //$rol->updated_at = new \DateTime();
        $rol->save();
        $nIdRol = $rol->id;

        $listPermisos       =   $request->listPermisosFilter;
        $listPermisosSize   =   sizeof($listPermisos);



        if ($listPermisosSize > 0) {
            foreach ($listPermisos as $key => $value) {
                if ($value['checked'] == true) {
                    $rol_permiso = roles_permisos::create([
                        'role_id' => $nIdRol,
                        'permiso_id' => $value['id'],

                    ]);
                }
            }
        }
          DB::commit();
        } catch (\Exception $e) {
            // capturara algun error ocurrido en el "try"
            DB::rollBack();
            return $e;
        }
    }
    public function setEditarRolPermisos(Request $request)
    {


        $nIdRol     =   $request->nIdRol;
        $cNombre    =   $request->cNombre;
        $cSlug      =   $request->cSlug;

        $nIdRol     =   ($nIdRol   ==  NULL) ? ($nIdRol   =   '') :   $nIdRol;
        $cNombre    =   ($cNombre   ==  NULL) ? ($cNombre   =   '') :   $cNombre;
        $cSlug      =   ($cSlug   ==  NULL) ? ($cSlug   =   '') :   $cSlug;

        try {
            // Ira todo el codigo que deseamos realizar
            DB::beginTransaction();

            $rol = roles::find($nIdRol);
            $rol->nombre= $cNombre ;
            $rol->url= $cSlug ;
            $rol->save();

            $rol_permiso=roles_permisos::where('role_id', $nIdRol)->delete();

            $listPermisos       =   $request->listPermisosFilter;
            $listPermisosSize   =   sizeof($listPermisos);
            if ($listPermisosSize > 0) {
                foreach ($listPermisos as $key => $value) {
                    if ($value['checked'] == true) {
                        $rol_permiso = roles_permisos::create([
                            'role_id' => $nIdRol,
                            'permiso_id' => $value['id'],
    
                        ]);
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            // capturara algun error ocurrido en el "try"
            DB::rollBack();
            return $e;
        }
    }
}
