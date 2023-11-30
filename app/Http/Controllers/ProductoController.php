<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    // Método para obtener todos los productos
    public function index()
    {
        $productos = Producto::all();
        return response()->json($productos)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }

    // Método para obtener un producto por ID
    public function show($id)
    {
        $producto = Producto::findOrFail($id);
        return response()->json($producto)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }

    // Método para crear un nuevo producto
    public function store(Request $request)
    {
        $producto = Producto::create($request->all());
        return response()->json($producto, 201)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }

    // Método para actualizar un producto existente
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        $producto->update($request->all());
        return response()->json($producto, 200)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }

    // Método para eliminar un producto
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();
        return response()->json(null, 204)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }

    // En app/Http/Controllers/ProductoController.php

    public function mostrarDetalles($id)
    {
        $producto = Producto::with('marca')->findOrFail($id);

        return response()->json([
            'producto' => $producto,
        ])
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }
}
