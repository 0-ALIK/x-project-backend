<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\ChatEvent;
use App\Models\Mensaje;
use Exception;

class ChatController extends Controller
{
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

        // LISTAR TODOS LOS MENSAJES DE UN MISMO RECLAMO
        $chat = Mensaje::where('reclamo_id', $reclamo_id)->get();
        return response()->json($chat); //CSRF
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
            return response()->json( $data );
        }
        catch (Exception $e){
            print($e);
            return response()->json( ["mensaje" => "ha ocurrido un problema al enviar el mensaje"], 500);
        }
    }

    public function receive(Request $request){
        return response()->json( $request->get("message") );
    }
}
