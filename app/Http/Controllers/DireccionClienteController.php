<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Usuario;
use App\Models\Cliente_direcciones;
use App\Models\Direccion;
use App\Models\Provincia;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
Use Exception;
use App\Utils\PermisoUtil;

class DireccionClienteController extends controller
{
    public function getClienteDirecciones($id){
        $direcciones = Cliente_direcciones::select('direccion.id_direccion as id_direccion', 'cliente_id','direccion.codigo_postal', 'direccion.detalles', 'direccion.telefono', 'provincia.nombre as provincia')
        ->join('direccion', 'cliente_direcciones.direccion_id', '=', 'direccion.id_direccion')
        ->join('provincia', 'direccion.provincia_id', '=', 'provincia.id_provincia')
        ->where('cliente_direcciones.cliente_id', '=', $id)
        ->get();

        return $direcciones;
    }

    public function guardarClienteDireccion(Request $request,$id){
        $camposValidados = $request->validate([
            'provincia_id' => 'required',
            'codigo_postal' => ['required','min:3'],
            'telefono' => 'required'
        ]);
        $detalles = $request->input('detalles');
        $cliente = Cliente::find($id);
        PermisoUtil::verificarUsuario($request, $cliente);
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
            $Cliente_direcciones = Cliente_direcciones::create([
                'cliente_id' => $id,
                'direccion_id' => $Direccion->id_direccion
            ]);

            DB::commit();
            return $this->getClienteDirecciones($id);
        }catch(Exception $exc){
            DB::rollBack();
            return response()->json( ["mensaje" => "Ocurrió un error", "status" => 500],500 );
        }
    }


    public function actualizarClienteDireccion(Request $request,$id,$id_direccion){
        $camposValidados = $request->validate([
            'provincia_id' => 'required',
            'codigo_postal' => ['required','min:3'],
            'telefono' => 'required'
        ]);
        $detalles = $request->input('detalles');
        $cliente = Cliente::find($id);
        PermisoUtil::verificarUsuario($request, $cliente);
        try{
            DB::beginTransaction();
            $Direccion = Direccion::find($id_direccion);
            $Direccion->provincia_id = $camposValidados['provincia_id'];
            $Direccion->codigo_postal = $camposValidados['codigo_postal'];
            $Direccion->telefono = $camposValidados['telefono'];
            $detalles ? $Direccion->detalles = $detalles : null;
            $Direccion->save();
            DB::commit();
            return $this->getClienteDirecciones($id);
        }catch(Exception $exc){
            DB::rollBack();
            return response()->json( ["mensaje" => "Ocurrió un error", "status" => 500],500 );
        }
    }

    public function eliminarClienteDireccion(Request $request,$id,$id_direccion){
        try{    

            $cliente = Cliente::find($id);
            PermisoUtil::verificarUsuario($request, $cliente);
            DB::beginTransaction();
            Cliente_direcciones::where('cliente_id', $id)
            ->where('direccion_id', $id_direccion)
            ->delete();
            $Direccion = Direccion::find($id_direccion);
            $Direccion->delete();
            DB::commit();
            return response()->json( ["mensaje" => "Se eliminó correctamente", "status" => 200],200 );
        }catch(Exception $exc){
            DB::rollBack();
            return response()->json( ["mensaje" => $exc, "status" => 500],500 );
        }
    }

}