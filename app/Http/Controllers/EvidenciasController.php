<?php

namespace App\Http\Controllers;

use App\Models\Evidencia;
use App\Models\OrdenCorte;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class EvidenciasController extends Controller
{
    public function index()
    {
        $evidencias = Evidencia::with('ordenCorte.zona')
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        $ordenes = OrdenCorte::with(['tecnico', 'zona'])->get();
        
        return view('evidencias.index', compact('evidencias', 'ordenes'));
    }
    
    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        // Verificar que tiene permisos para subir evidencias
        if (!in_array($user->role->name, ['Administrador', 'Supervisor', 'Tecnico'])) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para subir evidencias.'
            ], 403);
        }
        

        $request->validate([
            'orden_corte_id' => 'required|exists:orden_cortes,id',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tipo' => 'required|in:antes,durante,despues',
            'observaciones' => 'nullable|string',
        ]);

        // Verificar permisos específicos para técnicos
        if ($user->role->name === 'Tecnico') {
            $orden = OrdenCorte::find($request->orden_corte_id);
            
            if ($orden->tecnico_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para subir evidencias a esta orden.'
                ], 403);
            }

            if (!in_array($orden->estado, ['pendiente', 'en_proceso'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo puedes subir evidencias a órdenes pendientes o en proceso.'
                ], 400);
            }
        }

        $imagePath = $request->file('imagen')->store('evidencias', 'public');

        $evidencia = Evidencia::create([
            'orden_corte_id' => $request->orden_corte_id,
            'imagen' => $imagePath,
            'tipo' => $request->tipo,
            'observaciones' => $request->observaciones,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Evidencia subida exitosamente.',
            'evidencia' => $evidencia->load('ordenCorte.zona')
        ]);
    }
    
    public function destroy(Evidencia $evidencia): JsonResponse
    {
        $user = Auth::user();
        
        // Solo administradores, supervisores o el técnico que subió la evidencia pueden eliminarla
        if (!in_array($user->role->name, ['Administrador', 'Supervisor']) && 
            $evidencia->ordenCorte->tecnico_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para eliminar esta evidencia.'
            ], 403);
        }
        
        // Eliminar archivo físico
        if (Storage::disk('public')->exists($evidencia->imagen)) {
            Storage::disk('public')->delete($evidencia->imagen);
        }
        
        $evidencia->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Evidencia eliminada exitosamente.'
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        $evidencias = Evidencia::with('ordenCorte.zona')
            ->where('observaciones', 'like', "%{$query}%")
            ->orWhere('tipo', 'like', "%{$query}%")
            ->limit(10)
            ->get();

        return response()->json($evidencias);
    }
}