<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            error_log($user);
            $token = $user->createToken('Personal Access Token',[$user->rol])->plainTextToken;

            return response()->json(['token' => $token, 'data'=>$user], 200);
        }

        return response()->json(['message' => 'datos incorrectos'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'logout'], 200);
    }

}
