<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\ChatEvent;
use App\Models\Mensaje;
use App\Models\Reclamo;

use Exception;

class ChatController extends Controller
{

    public function chats(){

        // $openChats = Reclamo::join('mensajes', 'mensajes.reclamo_id', '=', 'reclamo.id_reclamo')
        //             ->groupBy('reclamo.id_reclamo')
        //             ->pluck('reclamo.id_reclamo');

        $openChats = Reclamo::join('mensajes as msj', 'msj.reclamo_id', '=', 'reclamo.id_reclamo')
                    ->join('usuario as cli', 'reclamo.cliente_id', '=', 'cli.id_usuario')
                    ->groupBy('reclamo.id_reclamo')
                    ->select('reclamo.id_reclamo', 'cli.nombre', 'reclamo.descripcion')
                    ->get();

        return response()->json($openChats);

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
        $data = [
            "reclamo" => $request->get("reclamo"),
            "cliente" => $request->get("cliente"),
            "mensaje" => $request->get("mensaje"),
            "admin" => $request->get("admin"),
        ];

        try{
            Mensaje::create(
                [
                    'reclamo_id' => $request->get("reclamo"),
                    'cliente_id' => $request->get("cliente"),
                    'mensaje' => $request->get("mensaje"),
                    'admin_id' => $request->get("admin"),
                ]
            );

            broadcast( new ChatEvent( $data ) )->toOthers();
            return response($data, 200, ['Access-Control-Allow-Origin' => 'http://localhost:4200']);
        }
        catch (Exception $e){
            return response($e, 500, ['Access-Control-Allow-Origin' => 'http://localhost:4200']);
        }
    }

    public function receive(Request $request){
        return response()->json( $request->get("message") );
    }
}
