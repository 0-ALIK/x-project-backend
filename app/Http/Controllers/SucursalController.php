<?php

namespace App\Http\Controllers;

use App\Models\Direccion;
use Exception;
use Illuminate\Http\Request;
use App\Models\Empresa_direcciones;
use Illuminate\Support\Facades\DB;

class SucursalController extends Controller
{        public function getAllSucursales(Request $request)
            //Ruta: /api/sucursales/
        {   //Params: /api/sucursales/?empresaId=
                                  //?direccionId=
                                  //?nombreEmpresa=
                                  //?provincia=
                                  //?codigoPostal=
                                  //?sucursales=
            $empresaId = $request->input('empresa_id');
            $direccionId = $request->input('direccion_id');
            $nombreEmpresa = $request->input('empresa');
            $nombreSucursal = $request->input('sucursal');
            $provincia = $request->input('provincia');
            $codigoPostal = $request->input('codigo_postal');

            $query = Empresa_direcciones::select(
                'empresa_direcciones.empresa_id AS Id_Empresa',
                'empresa_direcciones.direccion_id AS Id_Direccion',
                'empresa_direcciones.nombre AS nombreSucursal',
                'empresa.razon_social AS nombreEmpresa',
                'provincia.nombre AS provincia',
                'direccion.detalles AS detalles',
                'direccion.codigo_postal AS codigo_postal',
                'direccion.telefono AS telefono'
            )
            ->join('empresa', 'empresa_direcciones.empresa_id', '=', 'empresa.id_empresa')
            ->join('direccion', 'empresa_direcciones.direccion_id', '=', 'direccion.id_direccion')
            ->join('provincia', 'direccion.provincia_id', '=', 'provincia.id_provincia');

            $empresaId ? $query->where('sucursal.empresa_id', '=', $empresaId) : null;
            $direccionId ? $query->where('sucursal.direccion_id', '=', $direccionId) : null;
            $nombreSucursal ? $query->where('sucursal.nombre', '=', $nombreSucursal) : null;
            $nombreEmpresa ? $query->where('empresa.razon_social', '=', $nombreEmpresa) : null;
            $provincia ? $query->where('provincia.nombre', '=', $provincia) : null;
            $codigoPostal ? $query->where('direccion.codigo_postal', '=', $codigoPostal) : null;
            $sucursales = $query->simplePaginate(10);
            return $sucursales;
        }

        public function getSucursal($id) //Ruta: /api/sucursales/{id}
        {   $sucursales = Empresa_direcciones::select(
            'empresa_direcciones.nombre AS nombre_sucursal',
            'empresa_direcciones.empresa_id AS id_empresa',
            'empresa_direcciones.direccion_id AS id_direccion',
            'provincia.nombre AS provincia',
            'direccion.detalles AS detalles',
            'direccion.codigo_postal AS codigo_postal',
            'direccion.telefono AS telefono'
        )
        ->join('empresa', 'empresa_direcciones.empresa_id', '=', 'empresa.id_empresa')
        ->join('direccion', 'empresa_direcciones.direccion_id', '=', 'direccion.id_direccion')
        ->join('provincia', 'direccion.provincia_id', '=', 'provincia.id_provincia')
        ->where('empresa.id_empresa', '=', $id)
        ->get();
        return $sucursales;
        }
        public function actualizarSucursal(Request $request, $empresa_id, $direccion_id)
        {// ruta: /api/sucursales/{empresa_id}/{direccion_id}
         // Actualizar: nombre, direccion_id, provincia_id, codigo_postal, telefono, detalles
            // Validar la solicitud
            /*JSON de prueba:
            {
                "nombre": "NombreSucursal nuevo", // de tabla Sucursal (Empresa_direcciones)
                "direccion_id": 3, // de tabla Sucursal (Empresa_direcciones)
                "detalles": "Texto Nuevo para Detalles de la sucursal", // de tabla Direccion
                "provincia_id": 2, // de la tabla Direccion
                "codigo_postal": "5555", //de tabla Direccion
                "telefono": "98765" // de tabla Direccion
            }
            */
            $camposValidados = $request->validate([
                'nombre' => ['min:3'],
                'direccion_id' => ['min:1'],
                'provincia_id' => ['min:1'],
                'codigo_postal' => ['min:3'],
                'telefono' => ['min:3'],
                'detalles' => ['min:3'],
            ]);
            // Sucursal es null si no encuentra la sucursal con los id's enviados (tabla Empresa_direcciones)
            $sucursal = Empresa_direcciones::where('empresa_id', $empresa_id)
            ->where('direccion_id', $direccion_id)
            ->update([
                'nombre' => $camposValidados['nombre'],
                'direccion_id' => $camposValidados['direccion_id'],
            ]);
            // Si no encuentra sucursal, lo indica y muestra los id's ingresados
            if (!$sucursal) {
                return response()->json(['mensaje' => 'Sucursal no encontrada, empresa_id: '.$empresa_id.'direccion_id: '.$direccion_id, 'status' => 404], 404);
            }
            // busca en la tabla direccion para actualizar registros
            $direccion = Direccion::find($direccion_id);
            error_log($direccion);

            $direccion->detalles = $camposValidados['detalles'];
            $direccion->provincia_id = $camposValidados['provincia_id'];
            $direccion->codigo_postal = $camposValidados['codigo_postal'];
            $direccion->telefono = $camposValidados['telefono'];

            $direccion->save();

            return $this->getSucursal($empresa_id);
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
