<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\ChatEvent;
use App\Models\Mensaje;
use App\Models\Reclamo;
use App\Models\Usuario;
use App\Models\Cliente;
use App\Models\Admin;

use Exception;

class ChatController extends Controller
{

    public function chats(Request $request){

        try{

            $priv = $request->user()->currentAccessToken()->tokenable->rol;
            $usrid = $request->user()->currentAccessToken()->tokenable_id;

            if ($priv == 'admin') {

                $openChats = Reclamo::select('reclamo.id_reclamo', 'cli.id_cliente', 'usr.nombre', 'cli.apellido', 'reclamo.descripcion')
                        ->join('cliente as cli', 'reclamo.cliente_id', '=', 'cli.id_cliente')
                        ->join('mensajes as msj', function ($join) {
                            $join->on('reclamo.cliente_id', '=', 'msj.cliente_id');
                            $join->on('reclamo.id_reclamo', '=', 'msj.reclamo_id');
                        })
                        ->join('usuario as usr', 'cli.usuario_id', '=', 'usr.id_usuario')
                        ->groupBy('reclamo.id_reclamo', 'cli.id_cliente', 'usr.nombre', 'cli.apellido', 'reclamo.descripcion')
                        ->get();

            } else {

                $openChats = Reclamo::select('reclamo.id_reclamo', 'cli.id_cliente', 'usr.nombre', 'cli.apellido', 'reclamo.descripcion')
                        ->join('cliente as cli', 'reclamo.cliente_id', '=', 'cli.id_cliente')
                        ->join('mensajes as msj', function ($join) {
                            $join->on('reclamo.cliente_id', '=', 'msj.cliente_id');
                            $join->on('reclamo.id_reclamo', '=', 'msj.reclamo_id');
                        })
                        ->join('usuario as usr', 'cli.usuario_id', '=', 'usr.id_usuario')
                        ->where('usr.id_usuario', $usrid)
                        ->groupBy('reclamo.id_reclamo', 'cli.id_cliente', 'usr.nombre', 'cli.apellido', 'reclamo.descripcion')
                        ->get();
            }

            if ( count($openChats) > 0 ){
                return response()->json($openChats);
            } else {
                return response()->json(["mensaje" => "No hay chats abiertos"]);
            }

        }
        catch (Exception $e){
            return response()->json(["mensaje" => $e], 500);
        }

    }

    private function userHasAccessToTicket()
    {
        return true;
    }

    public function index(Request $request, $reclamo_id)
    {
        $hasAccess = $this->userHasAccessToTicket();

        if (!$hasAccess) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $chat = Mensaje::where('reclamo_id', $reclamo_id)->get();
        return response()->json($chat);
    }

    public function broadcast(Request $request){

        $priv = $request->user()->currentAccessToken()->tokenable->rol;
        $usrid = $request->user()->currentAccessToken()->tokenable_id;

        
        try{
            
            if ($priv == 'admin') {
    
                $cliente_id = null;
                $admin_id = Usuario::select('adm.id_admin')
                            ->join('admin as adm', 'adm.usuario_id', '=', 'usuario.id_usuario')
                            ->where('usuario.id_usuario', $usrid)
                            ->first();

                $admin_id = $admin_id->id_admin;

            } else {

                // SELECT cli.id_cliente
                // FROM usuario AS usr
                // JOIN cliente AS cli ON cli.usuario_id = usr.id_usuario
                // WHERE usr.id_usuario = 44

                $admin_id = null;
                $cliente_id = Usuario::select('cli.id_cliente')
                            ->join('cliente as cli', 'cli.usuario_id', '=', 'usuario.id_usuario')
                            ->where('usuario.id_usuario', $usrid)
                            ->first();
                $cliente_id = $cliente_id->id_cliente;
            }
    
            $data = [
                "reclamo" => $request->get("reclamo"),
                "cliente" => $cliente_id,
                "mensaje" => $request->get("mensaje"),
                "admin"   => $admin_id,
            ];

            Mensaje::create(
                [
                    'reclamo_id' => $request->get("reclamo"),
                    'cliente_id' => $cliente_id,
                    'mensaje' => $request->get("mensaje"),
                    'admin_id' => $admin_id,
                ]
            );

            broadcast( new ChatEvent( $data ) )->toOthers();
            return response($data, 200, ['Access-Control-Allow-Origin' => 'http://localhost:4200']);
        }
        catch (Exception $e){
            error_log($e);
            return response($e, 500, ['Access-Control-Allow-Origin' => 'http://localhost:4200']);
        }
    }
}
