<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DocumentosController extends Controller
{
    private $apiBaseUrl;
    private $apiKey;

    public function __construct()
    {
        $this->apiBaseUrl = env('DOCUMENTOS_API_URL', 'http://localhost:3002/api');
        $this->apiKey = env('DOCUMENTOS_API_KEY', 'default-key');
    }

    public function index()
    {
        try {
            // Obtener documentos del microservicio
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->timeout(10)->get($this->apiBaseUrl . '/documents');

            $documentos = $response->successful() ? $response->json('data') : [];
            
            // Obtener categorías disponibles
            $categoriesResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->timeout(10)->get($this->apiBaseUrl . '/categories');

            $categorias = $categoriesResponse->successful() ? $categoriesResponse->json('data') : [];

            return view('documentos.index', compact('documentos', 'categorias'));
        } catch (\Exception $e) {
            Log::error('Error al obtener documentos: ' . $e->getMessage());
            return view('documentos.index', [
                'documentos' => [],
                'categorias' => [],
                'error' => 'Error de conexión con el servicio de documentos'
            ]);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'required|integer',
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png'
        ]);

        try {
            // Preparar el archivo para envío
            $file = $request->file('file');
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->timeout(30)->attach(
                'file', file_get_contents($file->getRealPath()), $file->getClientOriginalName()
            )->post($this->apiBaseUrl . '/documents', [
                'title' => $request->title,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'uploaded_by' => auth()->id(),
                'uploaded_at' => now()->toISOString()
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(['error' => 'Error al subir documento'], 500);
        } catch (\Exception $e) {
            Log::error('Error al subir documento: ' . $e->getMessage());
            return response()->json(['error' => 'Error de conexión'], 500);
        }
    }

    public function download($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->timeout(30)->get($this->apiBaseUrl . "/documents/{$id}/download");

            if ($response->successful()) {
                $documentInfo = $response->json();
                
                // Obtener el archivo
                $fileResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                ])->timeout(30)->get($documentInfo['download_url']);

                if ($fileResponse->successful()) {
                    return response($fileResponse->body())
                        ->header('Content-Type', $documentInfo['mime_type'])
                        ->header('Content-Disposition', 'attachment; filename="' . $documentInfo['filename'] . '"');
                }
            }

            return response()->json(['error' => 'Documento no encontrado'], 404);
        } catch (\Exception $e) {
            Log::error('Error al descargar documento: ' . $e->getMessage());
            return response()->json(['error' => 'Error de conexión'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->timeout(10)->delete($this->apiBaseUrl . "/documents/{$id}");

            if ($response->successful()) {
                return response()->json(['message' => 'Documento eliminado exitosamente']);
            }

            return response()->json(['error' => 'Error al eliminar documento'], 500);
        } catch (\Exception $e) {
            Log::error('Error al eliminar documento: ' . $e->getMessage());
            return response()->json(['error' => 'Error de conexión'], 500);
        }
    }

    public function createCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500'
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->timeout(10)->post($this->apiBaseUrl . '/categories', [
                'name' => $request->name,
                'description' => $request->description,
                'created_by' => auth()->id(),
                'created_at' => now()->toISOString()
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(['error' => 'Error al crear categoría'], 500);
        } catch (\Exception $e) {
            Log::error('Error al crear categoría: ' . $e->getMessage());
            return response()->json(['error' => 'Error de conexión'], 500);
        }
    }
}
