<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Marca;

class EliminarMarcaController extends Controller
{
    public function eliminarMarca(Request $request, $id_marca)
    {
        $marca = Marca::find($id_marca);

        if (!$marca) {
            return response()->json(['error' => 'Marca no encontrada']);
        }

        $marca->delete();

        return response()->json(['message' => 'Marca eliminada correctamente']);
    }
}
