<?php

namespace App\Http\Controllers;

use App\Models\OrdenCorte;
use App\Models\Zona;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;

class MisOrdenesController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $userRole = $user->role->name;
        
        $query = OrdenCorte::with(['zona', 'tecnico', 'afectado']);

        // Filtrar según el rol del usuario
        if ($userRole === 'Tecnico') {
            // Para técnicos: órdenes asignadas a él O órdenes pendientes sin asignar
            $query->where(function($q) use ($user) {
                $q->where('tecnico_id', $user->id)
                  ->orWhere(function($subQ) {
                      $subQ->where('estado', 'pendiente')
                           ->whereNull('tecnico_id');
                  });
            });
        } elseif ($userRole === 'cliente') {
            // Para clientes: solo sus órdenes
            $query->where('afectado_id', $user->id);
        }

        // Aplicar filtros de búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('direccion', 'like', "%{$search}%")
                  ->orWhereHas('zona', function ($zq) use ($search) {
                      $zq->where('nombre', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('zona_id')) {
            $query->where('zona_id', $request->zona_id);
        }

        $ordenes = $query->latest()->paginate(15);
        
        // Obtener datos para filtros
        $zonas = Zona::orderBy('nombre')->get();
        
        // Estadísticas personalizadas
        $baseQuery = OrdenCorte::query();
        if ($userRole === 'Tecnico') {
            $baseQuery->where(function($q) use ($user) {
                $q->where('tecnico_id', $user->id)
                  ->orWhere(function($subQ) {
                      $subQ->where('estado', 'pendiente')
                           ->whereNull('tecnico_id');
                  });
            });
        } elseif ($userRole === 'cliente') {
            $baseQuery->where('afectado_id', $user->id);
        }

        $stats = [
            'total' => $baseQuery->count(),
            'pendientes' => (clone $baseQuery)->where('estado', 'pendiente')->count(),
            'en_proceso' => (clone $baseQuery)->where('estado', 'en_proceso')->count(),
            'completadas' => (clone $baseQuery)->where('estado', 'completada')->count(),
            'canceladas' => (clone $baseQuery)->where('estado', 'cancelada')->count(),
            'disponibles' => $userRole === 'Tecnico' ? 
                OrdenCorte::where('estado', 'pendiente')->whereNull('tecnico_id')->count() : 0
        ];

        return view('mis-ordenes.index', compact('ordenes', 'zonas', 'stats', 'userRole'));
    }

    public function show(OrdenCorte $orden)
    {
        $user = Auth::user();
        $userRole = $user->role->name;

        // Verificar que el usuario puede ver esta orden
        if ($userRole === 'Tecnico') {
            // Técnico puede ver si está asignada a él O si está pendiente sin asignar
            if ($orden->tecnico_id !== $user->id && !($orden->estado === 'pendiente' && !$orden->tecnico_id)) {
                abort(403, 'No tienes permisos para ver esta orden.');
            }
        } elseif ($userRole === 'cliente' && $orden->afectado_id !== $user->id) {
            abort(403, 'No tienes permisos para ver esta orden.');
        }

        $orden->load(['zona', 'tecnico', 'afectado', 'evidencias']);
        return view('mis-ordenes.show', compact('orden', 'userRole'));
    }

    public function tomarOrden(Request $request, OrdenCorte $orden): JsonResponse
    {
        $user = Auth::user();
        
        // Verificar que es técnico
        if ($user->role->name !== 'Tecnico') {
            return response()->json([
                'success' => false,
                'message' => 'Solo los técnicos pueden tomar órdenes.'
            ], 403);
        }

        // Verificar que la orden está pendiente y sin asignar
        if ($orden->estado !== 'pendiente' || $orden->tecnico_id) {
            return response()->json([
                'success' => false,
                'message' => 'Esta orden no está disponible para ser tomada.'
            ], 400);
        }

        // Asignar técnico y cambiar estado usando solo los campos que existen
        $orden->update([
            'tecnico_id' => $user->id,
            'estado' => 'en_proceso',
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Orden tomada exitosamente. Ahora está en proceso.'
        ]);
    }

    public function changeStatus(Request $request, OrdenCorte $orden): JsonResponse
    {
        $user = Auth::user();
        $userRole = $user->role->name;

        // Solo técnicos pueden actualizar el estado
        if ($userRole !== 'Tecnico' || $orden->tecnico_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para actualizar esta orden.'
            ], 403);
        }

        // Solo se puede actualizar si está pendiente o en proceso
        if (!in_array($orden->estado, ['pendiente', 'en_proceso'])) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede actualizar el estado de esta orden.'
            ], 400);
        }

        $validated = $request->validate([
            'estado' => 'required|in:pendiente,en_proceso,completada',
            'observaciones' => 'nullable|string|max:1000'
        ]);

        // Actualizar solo los campos que existen en la tabla
        $updateData = [
            'estado' => $validated['estado'],
            'updated_at' => now()
        ];

        // Si hay observaciones y existe el campo, las guardamos
        if (!empty($validated['observaciones']) && Schema::hasColumn('orden_cortes', 'observaciones')) {
            $updateData['observaciones'] = $validated['observaciones'];
        }

        $orden->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Estado de la orden actualizado correctamente.'
        ]);
    }

    public function cancelarOrden(Request $request, OrdenCorte $orden): JsonResponse
    {
        $user = Auth::user();
        $userRole = $user->role->name;

        // Solo técnicos pueden cancelar órdenes asignadas a ellos
        if ($userRole !== 'Tecnico' || $orden->tecnico_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para cancelar esta orden.'
            ], 403);
        }

        // Solo se puede cancelar si está pendiente o en proceso
        if (!in_array($orden->estado, ['pendiente', 'en_proceso'])) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede cancelar esta orden.'
            ], 400);
        }

        $validated = $request->validate([
            'motivo_cancelacion' => 'required|string|max:500'
        ]);

        // Actualizar solo los campos que existen en la tabla
        $updateData = [
            'estado' => 'cancelada',
            'updated_at' => now()
        ];

        // Si tienes un campo para observaciones, agregar el motivo
        if (Schema::hasColumn('orden_cortes', 'observaciones')) {
            $updateData['observaciones'] = 'Cancelada por técnico: ' . $validated['motivo_cancelacion'];
        }

        $orden->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Orden cancelada exitosamente.'
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $user = Auth::user();
        $userRole = $user->role->name;
        $query = $request->get('q', '');
        
        $ordenesQuery = OrdenCorte::with(['tecnico', 'afectado', 'zona'])
            ->where(function($q) use ($query) {
                $q->where('direccion', 'like', "%{$query}%")
                  ->orWhereHas('zona', function($zq) use ($query) {
                      $zq->where('nombre', 'like', "%{$query}%");
                  });
            });

        // Aplicar filtros según rol
        if ($userRole === 'Tecnico') {
            $ordenesQuery->where(function($q) use ($user) {
                $q->where('tecnico_id', $user->id)
                  ->orWhere(function($subQ) {
                      $subQ->where('estado', 'pendiente')
                           ->whereNull('tecnico_id');
                  });
            });
        } elseif ($userRole === 'cliente') {
            $ordenesQuery->where('afectado_id', $user->id);
        }

        $ordenes = $ordenesQuery->limit(10)->get();

        return response()->json($ordenes);
    }

    public function stats(): JsonResponse
    {
        $user = Auth::user();
        $userRole = $user->role->name;
        
        $baseQuery = OrdenCorte::query();
        
        if ($userRole === 'Tecnico') {
            $baseQuery->where(function($q) use ($user) {
                $q->where('tecnico_id', $user->id)
                  ->orWhere(function($subQ) {
                      $subQ->where('estado', 'pendiente')
                           ->whereNull('tecnico_id');
                  });
            });
        } elseif ($userRole === 'cliente') {
            $baseQuery->where('afectado_id', $user->id);
        }

        $stats = [
            'total' => $baseQuery->count(),
            'pendientes' => (clone $baseQuery)->where('estado', 'pendiente')->count(),
            'en_proceso' => (clone $baseQuery)->where('estado', 'en_proceso')->count(),
            'completadas' => (clone $baseQuery)->where('estado', 'completada')->count(),
            'canceladas' => (clone $baseQuery)->where('estado', 'cancelada')->count(),
        ];

        if ($userRole === 'Tecnico') {
            $stats['disponibles'] = OrdenCorte::where('estado', 'pendiente')
                ->whereNull('tecnico_id')
                ->count();
        }

        return response()->json($stats);
    }
}
