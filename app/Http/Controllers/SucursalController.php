<?php
//SucursalController corresponde a la tabla Empresa_direcciones
namespace App\Http\Controllers;

use App\Models\Direccion;
use Exception;
use Illuminate\Http\Request;
use App\Models\Empresa_direcciones;
use Illuminate\Support\Facades\DB;
class SucursalController extends Controller
{
        public function getSucursales($empresa_id) //Ruta: /api/sucursales/{id}
        // el id corresponde al id de empresa, se retornan todas las sucursales de esa empresa
        {
            $sucursales = Empresa_direcciones::select(
            'empresa_direcciones.nombre AS nombre_sucursal',
            'empresa_direcciones.empresa_id AS id_empresa',
            'empresa_direcciones.direccion_id AS id_direccion',
            'provincia.nombre AS provincia',
            'direccion.detalles AS detalles',
            'direccion.codigo_postal AS codigo_postal',
            'direccion.telefono AS telefono')

        ->join('empresa', 'empresa_direcciones.empresa_id', '=', 'empresa.id_empresa')
        ->join('direccion', 'empresa_direcciones.direccion_id', '=', 'direccion.id_direccion')
        ->join('provincia', 'direccion.provincia_id', '=', 'provincia.id_provincia')
        ->where('empresa_id', '=', $empresa_id)
        ->get();

        // Verificar si no existen sucursales de esa empresa
        if ($sucursales->count() === 0) {
            return response()->json(['mensaje' => 'No se encontraron sucursales para la empresa con ID ' . $empresa_id, 'status' => 404], 404);
        }
        return $sucursales;
        }

        public function guardarSucursal(Request $request){
            $camposValidados = $request->validate([
                //para tabla Sucursal (Empresa_direcciones)
                'empresa_id' => 'required',
                'nombre' => ['required','min:3'],
                //para tabla Direccion
                'provincia_id' => 'required',
                'codigo_postal' => ['required','min:3'],
                'telefono' => ['required','min:3'],
            ]);
            $detalles = $request->input('detalles');
            try{
                DB::beginTransaction();

                $Direccion = Direccion::create([
                    'provincia_id' => $camposValidados['provincia_id'],
                    'codigo_postal' => $camposValidados['codigo_postal'],
                    'detalles' => ' ',
                    'telefono' => $camposValidados['telefono']
                ]);
                $detalles ? $Direccion->detalles = $detalles : null;
                $Direccion->save();

                $Sucursal = Empresa_direcciones::create([
                    'empresa_id' => $camposValidados['empresa_id'],
                    'direccion_id' => $Direccion->id_direccion,
                    'nombre' => $camposValidados['nombre'],
                ]);
                $Sucursal->save();

                DB::commit();
                return response()->json(['estado' => 'Sucursal creada con éxito',$Sucursal], 200);
            }catch (Exception $e) {
                DB::rollBack();
                return response()->json(['error' => $e->getMessage(), 'status' => 500], 500);
            }
        }

        public function actualizarSucursal(Request $request, $empresa_id, $direccion_id)
            // permite actualizar la sucursal que coincida con empresa_id y direccion_id
        {   // ruta: /api/sucursales/{empresa_id}/{direccion_id}
            // Actualizar: nombre, codigo_postal, telefono, detalles
            // Validar la solicitud
                        /*JSON de prueba:
            {
                "nombre": "NombreSucursal nuevo", // de tabla Sucursal (Empresa_direcciones)
                "detalles": "Texto Nuevo para Detalles de la sucursal", // de tabla Direccion
                "telefono": "98765" // de tabla Direccion
            }
            */
            $camposValidados = $request->validate([
                'nombre' => ['required','min:3'],
                'provincia_id' => 'required',
                'codigo_postal' => ['required','min:3'],
                'telefono' => 'required'
            ]);
            $detalles = $request->input('detalles');
            try{
                DB::beginTransaction();
                $Direccion = Direccion::find($direccion_id);
                $Direccion->provincia_id = $camposValidados['provincia_id'];
                $Direccion->codigo_postal = $camposValidados['codigo_postal'];
                $Direccion->telefono = $camposValidados['telefono'];
                $detalles ? $Direccion->detalles = $detalles : null;
                $Direccion->save();
                
                $Sucursal = Empresa_direcciones::find($direccion_id);
                $Sucursal->nombre = $camposValidados['nombre'];
                $Sucursal->save();
                DB::commit();
                return $this->getSucursales($empresa_id);
            }catch(Exception $exc){
                DB::rollBack();
                return response()->json( ["mensaje" => "Ocurrió un error", "status" => 500],500 );
            }
        }

        public function eliminarSucursal($empresa_id, $direccion_id){
            try{
            // Sucursal es null si no encuentra la sucursal con los id's enviados (tabla Empresa_direcciones)
            $sucursal = Empresa_direcciones::where('empresa_id', $empresa_id)
            ->where('direccion_id', $direccion_id)
            ->delete();
            return response()->json( ["mensaje" => "Sucursal eliminada", "status" => 200],200 );
            }catch(exception $exc){
                return $exc;
            }
        }
}
