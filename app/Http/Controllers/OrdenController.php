<?php

namespace App\Http\Controllers;

use App\Models\OrdenCorte;
use App\Models\User;
use App\Models\Zona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrdenController extends Controller
{
    public function index()
    {
        $ordenes = OrdenCorte::with(['zona', 'tecnico', 'afectado'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $tecnicos = User::whereHas('role', function($query) {
            $query->where('name', 'tecnico');
        })->get();
        
        $afectados = User::whereHas('role', function($query) {
            $query->where('name', 'cliente');
        })->get();
        
        $zonas = Zona::all();
        
        return view('ordenes.index', compact('ordenes', 'tecnicos', 'afectados', 'zonas'));
    }

    public function create()
    {
        $tecnicos = User::whereHas('role', function($query) {
            $query->where('name', 'tecnico');
        })->get();
        
        $afectados = User::whereHas('role', function($query) {
            $query->where('name', 'cliente');
        })->get();
        
        $zonas = Zona::all();
        
        return view('ordenes.create', compact('tecnicos', 'afectados', 'zonas'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'zona_id' => 'required|exists:zonas,id',
            'tecnico_id' => 'required|exists:users,id',
            'afectado_id' => 'required|exists:users,id',
            'fecha' => 'required|date',
            'direccion' => 'required|string|max:255',
            'estado' => 'required|in:pendiente,en_proceso,completada,cancelada',
            'observaciones' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $orden = OrdenCorte::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Orden creada exitosamente',
            'orden' => $orden->load(['zona', 'tecnico', 'afectado'])
        ]);
    }

    public function show(OrdenCorte $orden)
    {
        $orden->load(['zona', 'tecnico', 'afectado', 'evidencias']);
        return view('ordenes.show', compact('orden'));
    }

    public function edit(OrdenCorte $orden)
    {
        $tecnicos = User::whereHas('role', function($query) {
            $query->where('name', 'tecnico');
        })->get();
        
        $afectados = User::whereHas('role', function($query) {
            $query->where('name', 'cliente');
        })->get();
        
        $zonas = Zona::all();
        
        return view('ordenes.edit', compact('orden', 'tecnicos', 'afectados', 'zonas'));
    }

    public function update(Request $request, OrdenCorte $orden)
    {
        $validator = Validator::make($request->all(), [
            'zona_id' => 'required|exists:zonas,id',
            'tecnico_id' => 'required|exists:users,id',
            'afectado_id' => 'required|exists:users,id',
            'fecha' => 'required|date',
            'direccion' => 'required|string|max:255',
            'estado' => 'required|in:pendiente,en_proceso,completada,cancelada',
            'observaciones' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $orden->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Orden actualizada exitosamente',
            'orden' => $orden->load(['zona', 'tecnico', 'afectado'])
        ]);
    }

    public function destroy(OrdenCorte $orden)
    {
        // Verificar si tiene evidencias asociadas
        if ($orden->evidencias()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar la orden porque tiene evidencias asociadas'
            ], 422);
        }

        $orden->delete();

        return response()->json([
            'success' => true,
            'message' => 'Orden eliminada exitosamente'
        ]);
    }
}
