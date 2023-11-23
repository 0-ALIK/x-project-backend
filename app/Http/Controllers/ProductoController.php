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
        return response()->json($productos);
    }

    // Método para obtener un producto por ID
    public function show($id)
    {
        $producto = Producto::findOrFail($id);
        return response()->json($producto);
    }

    // Método para crear un nuevo producto
    public function store(Request $request)
    {
        $producto = Producto::create($request->all());
        return response()->json($producto, 201);
    }

    // Método para actualizar un producto existente
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        $producto->update($request->all());
        return response()->json($producto, 200);
    }

    // Método para eliminar un producto
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();
        return response()->json(null, 204);
    }
    // En app/Http/Controllers/ProductoController.php

    public function mostrarDetalles($id)
    {
        $producto = Producto::with('marca')->findOrFail($id);

        return response()->json([
            'producto' => $producto,
        ]);
    }

}
