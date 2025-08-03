<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class MensajeriaController extends Controller
{
    private $apiUrl;
    private $apiKey;
    private $timeout;

    public function __construct()
    {
        $this->apiUrl = env('MENSAJERIA_API_URL', 'https://api-mensajeria.sedapp.com');
        $this->apiKey = env('MENSAJERIA_API_KEY', '');
        $this->timeout = env('MENSAJERIA_API_TIMEOUT', 30);
    }

    public function index()
    {
        try {
            // Obtener conversaciones del microservicio
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Accept' => 'application/json',
                ])
                ->get($this->apiUrl . '/api/conversations');

            if ($response->successful()) {
                $conversaciones = $response->json()['data'] ?? [];
            } else {
                $conversaciones = [];
                Log::error('Error al obtener conversaciones: ' . $response->body());
            }

            // Obtener estadísticas
            $statsResponse = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Accept' => 'application/json',
                ])
                ->get($this->apiUrl . '/api/stats');

            $stats = $statsResponse->successful() ? $statsResponse->json()['data'] : [
                'total_conversations' => 0,
                'active_conversations' => 0,
                'total_messages' => 0,
                'unread_messages' => 0
            ];

            // Obtener usuarios disponibles para crear conversaciones
            $availableUsers = User::select('id', 'name', 'email')
                ->where('id', '!=', auth()->id())
                ->where('email_verified_at', '!=', null)
                ->orderBy('name')
                ->get();

            return view('mensajeria.index', compact('conversaciones', 'stats', 'availableUsers'));

        } catch (\Exception $e) {
            Log::error('Error en MensajeriaController@index: ' . $e->getMessage());
            
            return view('mensajeria.index', [
                'conversaciones' => [],
                'stats' => [
                    'total_conversations' => 0,
                    'active_conversations' => 0,
                    'total_messages' => 0,
                    'unread_messages' => 0
                ],
                'availableUsers' => User::select('id', 'name', 'email')
                    ->where('id', '!=', auth()->id())
                    ->where('email_verified_at', '!=', null)
                    ->orderBy('name')
                    ->get()
            ]);
        }
    }

    public function getMessages($conversationId)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Accept' => 'application/json',
                ])
                ->get($this->apiUrl . "/api/conversations/{$conversationId}/messages");

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'messages' => $response->json()['data'] ?? []
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener mensajes'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Error al obtener mensajes: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error de conexión con el servicio de mensajería'
            ], 500);
        }
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|integer',
            'message' => 'required|string|max:1000',
            'type' => 'in:text,image,file'
        ]);

        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Accept' => 'application/json',
                ])
                ->post($this->apiUrl . '/api/messages', [
                    'conversation_id' => $request->conversation_id,
                    'message' => $request->message,
                    'type' => $request->type ?? 'text',
                    'sender_id' => auth()->id(),
                    'sender_name' => auth()->user()->name
                ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Mensaje enviado correctamente',
                    'data' => $response->json()['data']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error al enviar mensaje'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Error al enviar mensaje: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error de conexión con el servicio de mensajería'
            ], 500);
        }
    }

    public function createConversation(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'participants' => 'required|array|min:1',
            'participants.*' => 'integer|exists:users,id'
        ]);

        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Accept' => 'application/json',
                ])
                ->post($this->apiUrl . '/api/conversations', [
                    'name' => $request->name,
                    'description' => $request->description,
                    'participants' => $request->participants,
                    'created_by' => auth()->id(),
                    'created_by_name' => auth()->user()->name
                ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Conversación creada correctamente',
                    'conversation' => $response->json()['data']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error al crear conversación'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Error al crear conversación: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error de conexión con el servicio de mensajería'
            ], 500);
        }
    }

    public function markAsRead($conversationId)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Accept' => 'application/json',
                ])
                ->patch($this->apiUrl . "/api/conversations/{$conversationId}/mark-read", [
                    'user_id' => auth()->id()
                ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Mensajes marcados como leídos'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error al marcar mensajes como leídos'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Error al marcar como leído: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error de conexión con el servicio de mensajería'
            ], 500);
        }
    }

    public function deleteConversation($conversationId)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Accept' => 'application/json',
                ])
                ->delete($this->apiUrl . "/api/conversations/{$conversationId}");

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Conversación eliminada correctamente'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar conversación'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Error al eliminar conversación: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error de conexión con el servicio de mensajería'
            ], 500);
        }
    }
}
