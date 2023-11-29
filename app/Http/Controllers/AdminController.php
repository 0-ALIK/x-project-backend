<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Usuario;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    //
    public function crearAdmin(Request $request){
        $camposValidados = $request->validate([
            'nombre' => ['required', 'min:3'],
            'apellido' => ['required', 'min:3'],
            'cedula' => 'required',
            'genero' => ['required', 'in:M,F'],
            'telefono' => 'required',
            'correo' => ['required', 'email'],
            'pass' => 'required',
            'detalles',
            'foto' =>'image|mimes:jpeg,png,jpg,svg|max:2048',
            'permisos' => 'required|array',
            'permisos.*' => ['required', 'in:admin_inventario,admin_clientes,admin_ventas,admin_soporte,admin_analitica,admin']
        ]);

        $camposValidados['pass'] = bcrypt($request->input('pass'));
        db::beginTransaction();
        error_log($request->hasFile('foto'));

         //creamos el usuario
        $usuario = Usuario::create([
            'nombre' => $camposValidados['nombre'],
            'correo' => $camposValidados['correo'],
            'telefono' => $camposValidados['telefono'],
            'pass' => $camposValidados['pass'],
            'rol' => 'admin',
            'foto' => '-',
            'detalles' => 'prueba',
        ]);

        if($request->hasFile('foto')){
            //crea una carpeta con el nombre foto_empresa si no existe
            if (!Storage::disk('public')->exists('foto_admin')){
                Storage::disk('public')->makeDirectory('foto_admin');
                }
            $result = $camposValidados['foto']->storeOnCloudinary('foto_admin');
            $usuario->foto = $result->getSecurePath();
        }

        $admin = Admin::create([
            'usuario_id' => $usuario->id_usuario,
            'apellido' => $camposValidados['apellido'],
            'cedula' => $camposValidados['cedula'],
            'genero' => $camposValidados['genero'],
            'permisos' => $camposValidados['permisos'],
            'permisos_id' => 1,
        ]);
        db::commit();
        $token = $usuario->createToken('Personal Access Token',[...$admin->permisos])->plainTextToken;
        return response()->json(['token' => $token, 'usuario'=>$usuario, 'dato_adicional'=>$admin, 'rol'=>$usuario->rol] , 200);
    }

    public function actualizarAdmin(Request $request, $id){
        $camposValidados = $request->validate([
            'nombre' => ['required', 'min:3'],
            'apellido' => ['required', 'min:3'],
            'cedula' => 'required',
            'genero' => ['required', 'in:M,F'],
            'telefono' => 'required',
            'correo' => ['required', 'email'],
            'detalles',
            'foto' =>'image|mimes:jpeg,png,jpg,svg|max:2048',
            'permisos' => 'required|array',
            'permisos.*' => ['required', 'in:admin_inventario,admin_clientes,admin_ventas,admin_soporte,admin_analitica,admin']
        ]);

        db::beginTransaction();
        $admin = Admin::find($id);
        $usuario = Usuario::find($admin->usuario_id);
        $usuario->nombre = $camposValidados['nombre'];
        $usuario->correo = $camposValidados['correo'];
        $usuario->telefono = $camposValidados['telefono'];

        if($request->hasFile('foto')){
            $key = explode('/', pathinfo(parse_url($usuario->foto, PHP_URL_PATH), PATHINFO_DIRNAME));
            $public_id = end($key) . '/' . pathinfo(parse_url($usuario->foto, PHP_URL_PATH), PATHINFO_FILENAME);
            //borra la imagen en donde esta almacenada
            Cloudinary::destroy($public_id);

            //sube la nueva foto
            //crea una carpeta con el nombre foto_empresa si no existe
            if (!Storage::disk('public')->exists('foto_admin')){
                Storage::disk('public')->makeDirectory('foto_admin');
            }
            $result = $camposValidados['foto']->storeOnCloudinary('foto_admin');
            $usuario->foto = $result->getSecurePath();
        } 


        $usuario->save();

        $admin->apellido = $camposValidados['apellido'];
        $admin->cedula = $camposValidados['cedula'];
        $admin->genero = $camposValidados['genero'];
        $admin->permisos = $camposValidados['permisos'];
        $admin->save();
        db::commit();
        $token = $usuario->createToken('Personal Access Token',[...$admin->permisos])->plainTextToken;
        return response()->json(['mensaje' => 'datos actualizados correctamente', 'token'=>$token, 'usuario'=>$usuario, 'dato_adicional'=>$admin, 'rol'=>$usuario->rol] , 200);
    }

    public function borrarAdmin(Request $request, $id){
        $admin = Admin::find($id);
        $usuario = Usuario::find($admin->usuario_id);
        
        $admin->delete();
        $usuario->delete();
        $key = explode('/', pathinfo(parse_url($usuario->foto, PHP_URL_PATH), PATHINFO_DIRNAME));
        $public_id = end($key) . '/' . pathinfo(parse_url($usuario->foto, PHP_URL_PATH), PATHINFO_FILENAME);
        //borra la imagen en donde esta almacenada
        Cloudinary::destroy($public_id);

        return response()->json(['mensaje' => 'Admin borrado correctamente'] , 200);
    }

}
