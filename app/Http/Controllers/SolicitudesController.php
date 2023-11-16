<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Usuario;

class SolicitudesController extends Controller
{
    //
    public function getAllSolicitudes(Request $request){
        $empresa = $request->input('empresa');

        $query = Empresa::select('empresa.id_empresa as Id', 'usuario.nombre as nombre',  'empresa.ruc', 'usuario.telefono', 'empresa.documento', 'usuario.foto')
        ->join('usuario', 'empresa.usuario_id', '=', 'usuario.id_usuario')
        ->where('empresa.estado', '=', 'pendiente');

        $empresa ? $query->where('usuario.nombre', 'like', '%'.$empresa.'%') : null;

        $solitudes = $query->simplePaginate(10);

        return $solitudes;
    }

    public function actualizarSolicitud($id){
        $empresa = Empresa::find($id);
        $empresa->estado = 'aprobado';
        $empresa->save();
        return $empresa;
    }

    public function rechazarSolicitud($id){
        $empresa = Empresa::find($id);
        $empresa->delete();
        return response()->json([
            'message' => 'Empresa rechazada'
        ], 200);
    }
}
