<?php
namespace App\Utils;

use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

class PermisoUtil
{
    public static function verificarPermisos(Request $request, $usuario)
    {
        if ($request->user()->currentAccessToken()->tokenable_id != $usuario->id_usuario) {
            throw new UnauthorizedException('No tiene permisos para actualizar estos datos');
        }
    }

    public static function verificarAccionCliente(Request $request, $usuario, $empresa)
    {
        if (!($request->user()->currentAccessToken()->tokenable_id != $usuario->id_usuario || $request->user()->currentAccessToken()->tokenable_id != $empresa->usuario_id)) {
            throw new UnauthorizedException('No tiene permisos para actualizar estos datos');
        }
        

    }

    public static function verificarUsuario(Request $request, $usuario)
    {
        if ($request->user()->currentAccessToken()->tokenable_id != $usuario->usuario_id) {
            throw new UnauthorizedException('No tiene permisos para actualizar estos datos');
        }
    }

}