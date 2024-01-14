<?php

namespace App\Http\Controllers\Administracion;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\usuarios_roles;
use App\Models\usuarios_permisos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function index()
    {
        $usuarios = DB::table('users')
            ->leftJoin('archivos', 'archivos.id', '=', 'users.archivo_id')
            ->select(
                'users.*',
                'archivos.path',
                DB::raw("(CASE WHEN users.estado = 'A' THEN 'Activo' ELSE 'Inactivo' END) as estado_dsc")
            )
            // ->orderBy('id', 'desc')
            ->get();
        return response()->json($usuarios);
    }

    public function store(Request $request)
    {

        $nombre  =   $request->nombre;
        $nombre_usuario =   $request->nombre_usuario;
        $correo      =   $request->correo;

        $contrasenia    =   Hash::make($request->contrasenia);
        $fotografia    =   $request->fotografia;
        $fotografia    =   ($fotografia   ==  NULL) ? ($fotografia   =   NULL) :   $fotografia;
        try {
            DB::beginTransaction();
            $usuario = new User;

            $usuario->name = strtoupper($nombre);
            $usuario->username = $nombre_usuario;
            $usuario->email = $correo;
            $usuario->password = $contrasenia;
            $usuario->archivo_id = $fotografia;
            $usuario->usuario_registro = Auth::id();
            $usuario->usuario_actualizacion = Auth::id();
            $usuario->save();
            $request->request->add(['nIdUsuario' => $usuario->id]);
            $this->setEditarRolByUsuario($request);
            DB::commit();
            return response()->json('Usuario almacenada exitosamente!');
        } catch (\Exception $e) {
            // capturara algun error ocurrido en el "try"
            DB::rollBack();
            return $e;
        }
    }

    public function update(Request $request, $id)
    {
        $nombre = strtoupper($request->nombre);
        $correo = $request->correo;
        $contrasenia = $request->contrasenia;
        $fotografia = $request->fotografia;
        try {
            $usuario = User::find($id);
            $usuario->name = $nombre;
            $usuario->email = $correo;

            if ($contrasenia != NULL) {
                $contrasenia    =   Hash::make($contrasenia);
                $usuario->password = $contrasenia;
            }
            if ($fotografia != NULL) {
                $usuario->archivo_id = $fotografia;
            }
            $usuario->usuario_actualizacion = Auth::id();

            $usuario->save();
            $request->request->add(['nIdUsuario' => $usuario->id]);
            $this->setEditarRolByUsuario($request);

            return response()->json('Usuario actualizada exitosamente!');
        } catch (\Exception $e) {
            // capturara algun error ocurrido en el "try"
            DB::rollBack();
            return $e;
        }
    }

    public function cambiarEstado($id)
    {
        $usuario = User::find($id);
        $usuario->estado = $usuario->estado == 'A' ? 'I' : 'A';
        $usuario->usuario_actualizacion=Auth::id();
        $usuario->save();
        return response()->json('Usuario actualizada exitosamente el estado!');
    }

    public function getListarRolPermisosByUsuario(Request $request)
    {
        
        $nIdUsuario  =   $request->nIdUsuario;

        if (!$nIdUsuario) {
            $nIdUsuario =   Auth::id();
        }

        $nIdUsuario =   ($nIdUsuario   ==  NULL) ? ($nIdUsuario   =   0) :   $nIdUsuario;

        $permisos1 = DB::table('permisos')
            ->join('usuarios_permisos', 'usuarios_permisos.permiso_id', '=', 'permisos.id')
            ->select(
                'permisos.id',
                'permisos.codigo',
                'permisos.nombre'
            )
            ->where('usuarios_permisos.user_id', '=', $nIdUsuario);
        // ->get();

        $permisos2 = DB::table('usuarios_roles')
            ->join('roles_permisos', 'roles_permisos.role_id', '=', 'usuarios_roles.rol_id')
            ->join('permisos', 'permisos.id', '=', 'roles_permisos.permiso_id')
            ->select(
                'permisos.id',
                'permisos.codigo',
                'permisos.nombre'
            )
            ->where('usuarios_roles.user_id', '=', $nIdUsuario);
        // ->get();
        $permisos = $permisos1->union($permisos2)->get();
        return response()->json($permisos);
    }

    public function setEditarRolByUsuario(Request $request)
    {

        $nIdUsuario  =   $request->nIdUsuario;
        $nIdRol      =   $request->nIdRol;

        $nIdUsuario =   ($nIdUsuario   ==  NULL) ? ($nIdUsuario   =   '') :   $nIdUsuario;
        $nIdRol     =   ($nIdRol   ==  NULL) ? ($nIdRol   =   '') :   $nIdRol;

        $nCantidadRoles = usuarios_roles::where('user_id', '=', $nIdUsuario)->count();

        if ($nCantidadRoles > 0) {


            usuarios_roles::whereIn('user_id', [$nIdUsuario])
                ->update([
                    'rol_id' => $nIdRol,
                ]);
        } else {

            $usuario_rol = new usuarios_roles();
            $usuario_rol->user_id = $nIdUsuario;
            $usuario_rol->rol_id = $nIdRol;
            $usuario_rol->save();
        }
    }

    public function getRolByUsuario(Request $request)
    {


        $nIdUsuario  =   $request->nIdUsuario;

        $nIdUsuario =   ($nIdUsuario   ==  NULL) ? ($nIdUsuario   =   '') :   $nIdUsuario;
        $usuario_rol = DB::table('usuarios_roles')
            ->Join('roles', 'usuarios_roles.rol_id', '=', 'roles.id')
            ->select(
                'usuarios_roles.rol_id as nIdRol','roles.nombre as nombre'
            )
            ->where("user_id", $nIdUsuario)
            ->get();



        return response()->json($usuario_rol);
    }

    public function getListarPermisosByRolAsignado(Request $request)
    {

        $nIdUsuario  =   $request->nIdUsuario;

        $nIdUsuario =   ($nIdUsuario   ==  NULL) ? ($nIdUsuario   =   '') :   $nIdUsuario;
        $permisos = DB::table('usuarios_roles')
            ->Join('roles_permisos', 'usuarios_roles.rol_id', '=', 'roles_permisos.role_id')
            ->Join('permisos', 'roles_permisos.permiso_id', '=', 'permisos.id')
            ->select('permisos.*')
            ->where('usuarios_roles.user_id', $nIdUsuario)
            ->get();


        return  response()->json($permisos);
    }

    public function getListarPermisosByUsuario(Request $request)
    {


        $nIdUsuario  =   $request->nIdUsuario;

        $nIdUsuario =   ($nIdUsuario   ==  NULL) ? ($nIdUsuario   =   0) :   $nIdUsuario;

        $permisos = DB::table('permisos')
            ->leftjoin('usuarios_permisos', function ($leftjoin) use ($nIdUsuario) {
                $leftjoin
                    ->on('permisos.id', '=', 'usuarios_permisos.permiso_id')
                    ->where('usuarios_permisos.user_id', '=', $nIdUsuario);
            })
            ->select(
                'permisos.id',
                'permisos.nombre',
                'permisos.codigo',
                DB::raw("(CASE WHEN usuarios_permisos.user_id is null  THEN 0 ELSE 1 END) as checked")
            )
            ->get();

        return response()->json($permisos);
    }

    public function setRegistrarPermisosByUsuario(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $nIdUsuario  =   $request->nIdUsuario;

        $nIdUsuario =   ($nIdUsuario   ==  NULL) ? ($nIdUsuario   =   0) :   $nIdUsuario;

        try {
            // Ira todo el codigo que deseamos realizar
            DB::beginTransaction();

            $usuario_permiso=usuarios_permisos::where('user_id', $nIdUsuario)->delete();

           

            $listPermisos       =   $request->listPermisosFilter;
            $listPermisosSize   =   sizeof($listPermisos);
            if ($listPermisosSize > 0) {
                foreach ($listPermisos as $key => $value) {
                    if ($value['checked'] == true) {
                        $rol_permiso = usuarios_permisos::create([
                            'user_id' => $nIdUsuario,
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
