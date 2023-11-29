<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Empresa;
use App\Models\Cliente; 
use App\Models\Admin;

class UsuarioController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'correo' => 'required|email',
            'pass' => 'required',
        ]);
                                                         
        if (Auth::attempt(['correo' => $credentials['correo'], 'password' => $credentials['pass']])) {
            $user = Auth::user();

            if($user->rol == 'empresa'){
                $token = $user->createToken('Personal Access Token',[$user->rol])->plainTextToken;
                $empresa = Empresa::where('usuario_id', $user->id_usuario)->first();
                if($empresa->estado == 'pendiente'){
                    return response()->json(['message' => 'Empresa pendiente de aprobación'], 401);
                }
                return response()->json(['token' => $token, 'usuario'=>$user, 'dato_adicional'=>$empresa, 'rol'=>$user->rol] , 200);
            }

            if($user->rol == 'cliente'){
                $token = $user->createToken('Personal Access Token',[$user->rol])->plainTextToken;
                $cliente = Cliente::where('usuario_id', $user->id_usuario)->first();
                return response()->json(['token' => $token, 'usuario'=>$user, 'dato_adicional'=>$cliente, 'rol'=>$user->rol] , 200);
            }

            if($user->rol == 'admin'){
                $admin = Admin::where('usuario_id', $user->id_usuario)->first();
                $token = $user->createToken('Personal Access Token',[...$admin->permisos])->plainTextToken;
                return response()->json(['token' => $token, 'usuario'=>$user, 'dato_adicional'=>$admin, 'rol'=>$user->rol] , 200);
            }

            return response()->json(['message' => 'Usuario sin rol!!'], 200);
        }

        return response()->json(['message' => 'datos incorrectos'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'logout'], 200);
    }

}
