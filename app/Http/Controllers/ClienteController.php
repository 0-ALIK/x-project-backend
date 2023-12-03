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
use App\Utils\PermisoUtil;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\DireccionClienteController;

class ClienteController extends Controller
{
    public function getAllClientes(Request $request){
        $clDirecciones = new DireccionClienteController();
        $nombreEmpresa = $request->input('empresa');
        try{
        $query = Cliente::select('cliente.id_cliente', 'usuario.nombre as nombre', 'usuarioEmpresa.nombre as empresa','empresa.id_empresa','cliente.apellido as apellido', 'cliente.cedula', 'cliente.genero','usuario.correo', 'usuario.telefono', 'usuario.foto', 'cliente.created_at', DB::raw('69 as frecuencia'), DB::raw('96 as totalPedidos') ,DB::raw('CONCAT(usuario.nombre, " ", cliente.apellido) as cliente69'))
        ->join('usuario', 'cliente.usuario_id', '=', 'usuario.id_usuario')
        ->join('empresa', 'empresa.id_empresa', '=', 'cliente.empresa_id')
        ->join('usuario as usuarioEmpresa', 'usuarioEmpresa.id_usuario', '=', 'empresa.usuario_id');


        if($request->user()->currentAccessToken()->tokenable->rol == 'empresa'){
            $query->where('empresa.usuario_id', '=', $request->user()->currentAccessToken()->tokenable_id);
        }

        // aplicamos los filtros
        $nombreEmpresa ? $query->where('usuarioEmpresa.nombre', 'like', '%'.$nombreEmpresa.'%') : null;

        //$clientes = $query->simplePaginate(1000);
        $clientes = $query->get();

        foreach($clientes as &$cliente){
            $cliente->direcciones = $clDirecciones->getClienteDirecciones($cliente->id_cliente);
        }

        return $clientes;
        }catch(Exception $exc){
            error_log($exc);
        return $exc;}
    }

    public function getCliente($id){
        $clientes = Cliente::select('cliente.id_cliente', 'usuario.nombre as nombre', 'usuarioEmpresa.nombre as empresa','empresa.id_empresa','cliente.apellido as apellido', 'cliente.cedula', 'cliente.genero','usuario.correo', 'usuario.telefono', 'usuario.foto', 'usuario.detalles',DB::raw('69 as frecuencia'), DB::raw('96 as totalPedidos') ,DB::raw('CONCAT(usuario.nombre, " ", cliente.apellido) as cliente'))
        ->join('usuario', 'cliente.usuario_id', '=', 'usuario.id_usuario')
        ->join('empresa', 'empresa.id_empresa', '=', 'cliente.empresa_id')
        ->join('usuario as usuarioEmpresa', 'usuarioEmpresa.id_usuario', '=', 'empresa.usuario_id')
        ->where('cliente.id_cliente', '=', $id)
        ->get();

        if(isset($clientes[0])) {
            return $clientes[0];
        } else {
            // Aquí puedes devolver una respuesta de error apropiada
            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }
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
            'foto' =>['required','image|mimes:jpeg,png,jpg,svg|max:2048'],
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
            'foto' => 'https://definicion.de/wp-content/uploads/2019/07/perfil-de-usuario.png',
            'detalles' => 'prueba',
        ]);

        if($request->hasFile('foto')){
        //crea una carpeta con el nombre foto_empresa si no existe
        if (!Storage::disk('public')->exists('foto_cliente')){
            Storage::disk('public')->makeDirectory('foto_cliente');
        }
        $result = $camposValidados['foto']->storeOnCloudinary('foto_cliente');
        $usuario->foto = $result->getSecurePath();
        }

        //creamos el cliente
        //falta empresa_id  que viene de la sesion - HECHO
        $cliente = Cliente::create([
            'usuario_id' => $usuario->id_usuario,
            'empresa_id' => Empresa::where('usuario_id', '=', $request->user()->currentAccessToken()->tokenable_id)->first()->id_empresa,
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
        return response()->json(
            ["mensaje" => "Cuenta creada correctamente",
            "token" => $usuario->createToken('authToken',['cliente'])->plainTextToken,
            "data" => $this->getCliente($cliente->id_cliente),
            "status" => 200,
            ],200 );

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

        //verificamos que el usuario que esta actualizando es el mismo que el de la sesion
        PermisoUtil::verificarPermisos($request, $usuario);

        db::beginTransaction();
        //borra la foto anterior
        if($request->hasFile('foto')){
            $key = explode('/', pathinfo(parse_url($usuario->foto, PHP_URL_PATH), PATHINFO_DIRNAME));
            $public_id = end($key) . '/' . pathinfo(parse_url($usuario->foto, PHP_URL_PATH), PATHINFO_FILENAME);
            //borra la imagen en donde esta almacenada
            Cloudinary::destroy($public_id);

            //sube la nueva foto
            //crea una carpeta con el nombre foto_empresa si no existe
            if (!Storage::disk('public')->exists('foto_cliente')){
                Storage::disk('public')->makeDirectory('foto_cliente');
            }
            $result = $camposValidados['foto']->storeOnCloudinary('foto_cliente');
            $usuario->foto = $result->getSecurePath();
         }

        $usuario->nombre = $camposValidados['nombre'];
        $usuario->correo = $camposValidados['correo'];
        $usuario->telefono = $camposValidados['telefono'];
        $request->input('detalles') ? $usuario->detalles = $request->input('detalles'): null;
        $usuario->save();

        $cliente->apellido = $camposValidados['apellido'];
        $cliente->cedula = $camposValidados['cedula'];
        $cliente->genero = $camposValidados['genero'];
        $cliente->save();
        db::commit();
        return $this->getCliente($cliente->id_cliente);
    }

    public function eliminarCliente(Request $request, $id){
        try{
        $cliente = Cliente::find($id);
        $usuario = Usuario::find($cliente->usuario_id);
        $direcciones = Cliente_direcciones::where('cliente_id', '=', $id)->get();
        $empresa = Empresa::find($cliente->empresa_id);
        //error_log($empresa);
        //error_log($request->user()->currentAccessToken()->tokenable);
        //verificamos que el usuario que esta actualizando es el mismo que el de la sesion o el de la empresa a que pertenece
        PermisoUtil::verificarAccionCliente($request, $usuario, $empresa);
        if($request->user()->currentAccessToken()->tokenable->rol == 'empresa' && $request->user()->currentAccessToken()->tokenable_id !== $empresa->usuario_id){
            return response()->json( ["mensaje" => "No tiene permisos para eliminar este cliente", "status" => 401],401 );
        }

        //eliminamos las direcciones
        foreach($direcciones as $direccion){
            error_log($direccion);
            $direccion->delete();
        }

            $key = explode('/', pathinfo(parse_url($usuario->foto, PHP_URL_PATH), PATHINFO_DIRNAME));
            $public_id = end($key) . '/' . pathinfo(parse_url($usuario->foto, PHP_URL_PATH), PATHINFO_FILENAME);
            //borra la imagen en donde esta almacenada
            Cloudinary::destroy($public_id);

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
