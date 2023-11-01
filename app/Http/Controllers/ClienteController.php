<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Usuario;
use App\Models\Empresa;
use App\Models\Cliente_direcciones;
use App\Models\Direccion;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
Use Exception;

class ClienteController extends Controller
{
    public function getAllClientes(Request $request){
        $cedula = $request->input('cedula');
        $cliente = $request->input('cliente');
        $genero= $request->input('genero');
        $nombreEmpresa = $request->input('empresa');
        $correo = $request->input('correo');
        $telefono = $request->input('telefono');

        $query = Cliente::select('cliente.id_cliente as id', 'usuario.nombre as nombre', 'usuarioEmpresa.nombre as empresa','cliente.apellido as apellido', 'cliente.cedula', 'cliente.genero','usuario.correo', 'usuario.telefono', 'usuario.foto', DB::raw('69 as frecuencia'), DB::raw('96 as totalPedidos') ,DB::raw('CONCAT(usuario.nombre, " ", cliente.apellido) as cliente69'))
        ->join('usuario', 'cliente.usuario_id', '=', 'usuario.id_usuario')->join('empresa', 'empresa.id_empresa', '=', 'cliente.empresa_id')->join('usuario as usuarioEmpresa', 'usuarioEmpresa.id_usuario', '=', 'empresa.usuario_id');

        //aplicamos los filtros
        $cedula ? $query->where('cliente.cedula', '=', $cedula) : null;
        $genero ? $query->where('cliente.genero', '=', $genero) : null;
        $cliente ? $query->where(DB::raw('CONCAT(usuario.nombre, " ", cliente.apellido)'), 'LIKE', '%' . $cliente . '%'): null;
        $nombreEmpresa ? $query->where('usuarioEmpresa.nombre', 'like', '%'.$nombreEmpresa.'%') : null;
        $correo ? $query->where('usuario.correo', 'like', '%'.$correo.'%') : null;
        $telefono ? $query->where('usuario.telefono', 'like', $telefono.'%') : null;

        $clientes = $query->simplePaginate(10);

        return $clientes;
    }

    public function getCliente($id){
        $clientes = Cliente::select('cliente.id_cliente as id', 'usuario.nombre as nombre', 'usuarioEmpresa.nombre as empresa','cliente.apellido as apellido', 'cliente.cedula', 'cliente.genero','usuario.correo', 'usuario.telefono', 'usuario.foto', 'usuario.detalles',DB::raw('69 as frecuencia'), DB::raw('96 as totalPedidos') ,DB::raw('CONCAT(usuario.nombre, " ", cliente.apellido) as cliente69'))
        ->join('usuario', 'cliente.usuario_id', '=', 'usuario.id_usuario')->join('empresa', 'empresa.id_empresa', '=', 'cliente.empresa_id')->join('usuario as usuarioEmpresa', 'usuarioEmpresa.id_usuario', '=', 'empresa.usuario_id')
        ->where('cliente.id_cliente', '=', $id)
        ->get();

        return $clientes;
    }

    public function guardarCliente(Request $request){
        //validamos los campos

        //usamos db transaction para que si falla algo no se guarde nada
        DB::beginTransaction();
        $camposValidados = $request->validate([
            'nombre' => ['required', 'min:3'],
            'apellido' => ['required', 'min:3'],
            'cedula' => 'required',
            'genero' => ['required', 'in:M,F'],
            'telefono' => 'required',
            'correo' => ['required', 'email'],
            'pass' => 'required',
            'detalles',
            'foto' =>'image',
            'direccion' => 'required',
            'codigoPostal' => 'required',
            'provincia' => 'required',
            'telefonoDireccion'=> 'required',
        ]);

        try{
        $camposValidados['pass'] = bcrypt($request->input('pass'));
        //creamos el usuario
        $usuario = Usuario::create([
            'nombre' => $camposValidados['nombre'],
            'correo' => $camposValidados['correo'],
            'telefono' => $camposValidados['telefono'],
            'pass' => $camposValidados['pass'],
            'rol' => 'cliente',
            'foto' => '-',
            'detalles' => 'prueba',
        ]);

        //creamos el cliente
        //falta empresa_id  que viene de la sesion
        $cliente = Cliente::create([
            'usuario_id' => $usuario->id_usuario,
            'empresa_id' => 1,
            'apellido' => $camposValidados['apellido'],
            'cedula' => $camposValidados['cedula'],
            'genero' => $camposValidados['genero'],
            'estado' => 'activo'
        ]);

        $direccion = Direccion::create([
            'detalles' => $camposValidados['direccion'],
            'codigo_postal' => $camposValidados['codigoPostal'],
            'provincia_id' => $camposValidados['provincia'],
            'telefono' => $camposValidados['telefonoDireccion'],
        ]);

        $cliente_direccion = Cliente_direcciones::create([
            'cliente_id' => $cliente->id_cliente,
            'direccion_id' => $direccion->id_direccion,
        ]);

        //confirmamos que todo esta bien
        DB::commit();
        return Cliente::with('Usuario')->find($cliente->id_cliente);
        }catch(Exception $exc){
            DB::rollBack();
            return response()->json( ["mensaje" => "Ocurrió un error", "status" => 500],500 );
        }
    }

    public function actualizarCliente(Request $request, $id){
        $camposValidados = $request->validate([
            'nombre' => ['required', 'min:3'],
            'apellido' => ['required', 'min:3'],
            'cedula' => 'required',
            'genero' => ['required', 'in:M,F'],
            'telefono' => 'required',
            'correo' => ['required', 'email'],
            'foto' =>'image',
        ]);
        
        $cliente = Cliente::find($id);
        $usuario = Usuario::find($cliente->usuario_id);
        error_log($usuario);
        $usuario->nombre = $camposValidados['nombre'];
        $usuario->correo = $camposValidados['correo'];
        $usuario->telefono = $camposValidados['telefono'];
        $request->input('detalles') ? $usuario->detalles = $request->input('detalles'): null;
        $usuario->save();

        $cliente->apellido = $camposValidados['apellido'];
        $cliente->cedula = $camposValidados['cedula'];
        $cliente->genero = $camposValidados['genero'];
        $cliente->save();

        return $this->getCliente($cliente->id_cliente);
    }

    public function eliminarCliente($id){
        try{
        $cliente = Cliente::find($id);
        $usuario = Usuario::find($cliente->usuario_id);
        $direcciones = Cliente_direcciones::where('cliente_id', '=', $id)->get();

        //eliminamos las direcciones
        foreach($direcciones as $direccion){
            error_log($direccion);
            $direccion->delete();
        }

        //eliminamos el cliente
        $cliente->delete();

        //eliminamos el usuario
        $usuario->delete();

        return response()->json( ["mensaje" => "Cliente eliminado", "status" => 200],200 );
        }catch(Exception $exc){
            return $exc;
        }
    }
    
}
