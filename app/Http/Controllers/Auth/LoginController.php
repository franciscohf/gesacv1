<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        //$cEmail         = $request->cEmail;
        $cContrasena    =   $request->cContrasena;
        $cUsername         = $request->cUsername;

        $rpta = Auth::attempt(['username' => $cUsername, 'password' => $cContrasena]);

        if ($rpta) {
            return response()->json([
                'authUser'  =>  Auth::user(),
                'code'      =>  200
            ]);
        } else {
            return response()->json([
                'authUser'  =>  $cUsername ,
                'code'      =>  401
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return response()->json([
            'code'      =>  204
        ]);
    }
}
