<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Usuario;
use App\Models\Empresa_direcciones;
use App\Models\Cliente;
use App\Http\Requests\StoreEmpresaRequest;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateEmpresaRequest;
Use Exception;
use Illuminate\Support\Facades\DB;
use App\Utils\PermisoUtil;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    //obtiene toda las empresas
    //FALTA SOLO MOSTRAR CUANDO EL ESTADO ESTE EN APROBADO --- HECHO
    public function getAllEmpresas(Request $request){
        //obtenemos los filtros params
        $clienteId = $request->input('cliente');

        $query = Empresa::select('empresa.id_empresa as Id', 'usuario.nombre as nombre', 'empresa.razon_social', 'empresa.ruc', 'usuario.correo', 'usuario.telefono', 'empresa.documento', 'usuario.foto')
        ->join('usuario', 'empresa.usuario_id', '=', 'usuario.id_usuario')
        ->where('empresa.estado', '=', 'aprobado');

        //aplicamos los filtros
        $clienteId ? $query->where('cliente.id_cliente', '=', $clienteId)->join('cliente', 'empresa.id_empresa', '=', 'cliente.empresa_id') : null;

        $empresas = $query->simplePaginate(1000);
        return $empresas;
    }

    public function getEmpresa($id){
        $empresas = Empresa::select('empresa.id_empresa as Id', 'usuario.nombre as nombre', 'empresa.razon_social', 'empresa.ruc', 'usuario.correo', 'usuario.telefono', 'empresa.documento', 'usuario.foto')
        ->join('usuario', 'empresa.usuario_id', '=', 'usuario.id_usuario')
        ->where('empresa.id_empresa', '=', $id)
        ->get();
        return $empresas;
    }

    //FALTA VALIDAR SESION - HECHO
    //falta manejo de fotos
    public function guardarEmpresa(Request $request){
        //validamos los campos
        $camposValidados = $request->validate([
            'nombre' => ['required', 'min:3'],
            'ruc' => 'required',
            'telefono' => 'required',
            'correo' => ['required', 'email'],
            'pass' => 'required',
            'documento' => 'required',
        ]);

        //encriptamos la contraseña
        $camposValidados['pass'] = bcrypt($camposValidados['pass']);
        try{
        //creamos el usuario

        DB::beginTransaction();
        $usuario = Usuario::create([
            'nombre' => $camposValidados['nombre'],
            'telefono' => $camposValidados['telefono'],
            'correo' => $camposValidados['correo'],
            'pass' => $camposValidados['pass'],
            'foto' => '-',
            'detalles' => 'prueba',
            'rol' => 'empresa',
        ]);

        //creamos la empresa
        $empresa = Empresa::create([
            'usuario_id' => $usuario->id_usuario,
            'razon_social' => $camposValidados['nombre'],
            'ruc' => $camposValidados['ruc'],
            'documento' => $camposValidados['documento'],
            'estado' => 'pendiente',
        ]);

        DB::commit();
        //error_log($empresa);
        return response()->json( 
        ["mensaje" => "Cuenta creada correctamente", 
        "token" => $usuario->createToken('authToken',['empresa'])->plainTextToken,
        "data" => $this->getEmpresa($empresa->id_empresa),
        "status" => 200,
        ],200 );
        
        } catch(Exception $ex){
            error_log($ex);
            DB::rollBack();
            return response()->json( ["mensaje" => "Ocurrió un error", "status" => 500],500 );
        }
    }

    //falta manejo de fotos 
    public function actualizarEmpresa(Request $request, $id){
        error_log($request->user()->currentAccessToken()->tokenable_id);
        //validamos los campos
        $camposValidados = $request->validate([
            'nombre' => ['required', 'min:3'],
            'ruc' => 'required',
            'telefono' => 'required',
            'correo' => ['required', 'email'],
            'foto' => 'image'
        ]);

        $empresa = Empresa::find($id);
        $usuario = Usuario::find($empresa->usuario_id);
        
        //valida si el usuario que esta actualizando es el mismo que el de la sesion
        PermisoUtil::verificarPermisos($request, $usuario);

        //actualizamos los datos del usuario de la empresa
        $usuario->nombre = $camposValidados['nombre'];
        $usuario->telefono = $camposValidados['telefono'];
        $usuario->correo = $camposValidados['correo'];
        $usuario->foto = ''; //aqui va el url de la foto
        $usuario->save();   
        //actualizamos los datos de la empresa
        $empresa->razon_social = $camposValidados['nombre'];
        $empresa->ruc = $camposValidados['ruc'];
        $empresa->save();

        //error_log($empresa);
        return $this->getEmpresa($id);
    }


    //FALTA VALIDAR SESION 
    public function eliminarEmpresa(Request $request, $id){
        try{
        $empresa = Empresa::find($id);
        $usuario = Usuario::find($empresa->usuario_id);
        $sucursales =  Empresa_direcciones::where('empresa_id', '=', $id)->get();
        $clientes = Cliente::where('empresa_id', '=', $id)->get();

         //valida si el usuario que esta actualizando es el mismo que el de la sesion
         PermisoUtil::verificarPermisos($request, $usuario);

        //error_log($clientes);
        //verificamos si la empresa tiene sucursales
        if(!sizeof($sucursales) == 0){
            return response()->json([
                'message' => 'No se puede eliminar la empresa porque tiene sucursales'
            ], 400);
        }

        //verificamos si la empresa tiene clientes
        if(!sizeof($clientes) == 0){
            return response()->json([
                'message' => 'No se puede eliminar la empresa porque tiene clientes'
            ], 400);
        }

        $empresa->delete();
        $usuario->delete();

        return response()->json([
            'message' => 'Empresa eliminada correctamente'
        ], 200);
        } catch(Exception $ex){
        return response()->json( ["mensaje" => "Ocurrió un error", "status" => 500],500 );
    }
}
}
