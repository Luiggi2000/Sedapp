<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MensajeriaController extends Controller
{
    private $apiBaseUrl;
    private $apiKey;

    public function __construct()
    {
        $this->apiBaseUrl = env('MENSAJERIA_API_URL', 'http://localhost:3001/api');
        $this->apiKey = env('MENSAJERIA_API_KEY', 'default-key');
    }

    public function index()
    {
        try {
            // Obtener conversaciones del microservicio
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->timeout(10)->get($this->apiBaseUrl . '/conversations');

            $conversaciones = $response->successful() ? $response->json('data') : [];
            
            // Obtener usuarios para el selector
            $usuarios = \App\Models\User::select('id', 'name', 'email')->get();

            return view('mensajeria.index', compact('conversaciones', 'usuarios'));
        } catch (\Exception $e) {
            Log::error('Error al obtener conversaciones: ' . $e->getMessage());
            return view('mensajeria.index', [
                'conversaciones' => [],
                'usuarios' => \App\Models\User::select('id', 'name', 'email')->get(),
                'error' => 'Error de conexión con el servicio de mensajería'
            ]);
        }
    }

    public function getMessages($conversationId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->timeout(10)->get($this->apiBaseUrl . "/conversations/{$conversationId}/messages");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(['error' => 'Error al obtener mensajes'], 500);
        } catch (\Exception $e) {
            Log::error('Error al obtener mensajes: ' . $e->getMessage());
            return response()->json(['error' => 'Error de conexión'], 500);
        }
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|integer',
            'message' => 'required|string|max:1000',
            'recipient_id' => 'required|integer'
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->timeout(10)->post($this->apiBaseUrl . '/messages', [
                'conversation_id' => $request->conversation_id,
                'sender_id' => auth()->id(),
                'recipient_id' => $request->recipient_id,
                'message' => $request->message,
                'timestamp' => now()->toISOString()
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(['error' => 'Error al enviar mensaje'], 500);
        } catch (\Exception $e) {
            Log::error('Error al enviar mensaje: ' . $e->getMessage());
            return response()->json(['error' => 'Error de conexión'], 500);
        }
    }

    public function createConversation(Request $request)
    {
        $request->validate([
            'participant_ids' => 'required|array|min:1',
            'participant_ids.*' => 'integer|exists:users,id',
            'title' => 'required|string|max:255'
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->timeout(10)->post($this->apiBaseUrl . '/conversations', [
                'title' => $request->title,
                'created_by' => auth()->id(),
                'participant_ids' => array_merge($request->participant_ids, [auth()->id()]),
                'created_at' => now()->toISOString()
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(['error' => 'Error al crear conversación'], 500);
        } catch (\Exception $e) {
            Log::error('Error al crear conversación: ' . $e->getMessage());
            return response()->json(['error' => 'Error de conexión'], 500);
        }
    }

    public function deleteConversation($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->timeout(10)->delete($this->apiBaseUrl . "/conversations/{$id}");

            if ($response->successful()) {
                return response()->json(['message' => 'Conversación eliminada exitosamente']);
            }

            return response()->json(['error' => 'Error al eliminar conversación'], 500);
        } catch (\Exception $e) {
            Log::error('Error al eliminar conversación: ' . $e->getMessage());
            return response()->json(['error' => 'Error de conexión'], 500);
        }
    }
}
