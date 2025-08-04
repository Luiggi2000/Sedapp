<?php

namespace App\Http\Controllers;

use App\Models\OrdenCorte;
use App\Models\User;
use App\Models\Zona;
use Illuminate\Http\Request;

class OrdenesController extends Controller
{
    public function index()
    {
        $ordenes = OrdenCorte::with(['tecnico', 'afectado', 'zona'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $tecnicos = User::whereHas('role', function($query) {
            $query->where('name', 'Tecnico');
        })->get();
        
        $afectados = User::whereHas('role', function($query) {
            $query->where('name', 'cliente');
        })->get();
        
        $zonas = Zona::all();
        
        return view('ordenes.index', compact('ordenes', 'tecnicos', 'afectados', 'zonas'));
    }
    
    public function store(Request $request)
{
    $validated = $request->validate([
        'zona_id' => 'required|exists:zonas,id',
        'tecnico_id' => 'required|exists:users,id',
        'afectado_id' => 'required|exists:users,id',
        'fecha' => 'required|date',
        'direccion' => 'required|string|max:255',
        'estado' => 'required|in:pendiente,en_proceso,completada,cancelada',
    ]);

    $orden = OrdenCorte::create($validated);

    // Carga relaciones para devolverlas al frontend
    $orden->load('zona', 'tecnico', 'afectado');

    return response()->json([
        'message' => 'Orden creada exitosamente',
        'orden' => $orden,
    ]);
}

    
    public function update(Request $request, OrdenCorte $orden)
    {
        $request->validate([
            'zona_id' => 'required|exists:zonas,id',
            'tecnico_id' => 'required|exists:users,id',
            'afectado_id' => 'required|exists:users,id',
            'fecha' => 'required|date',
            'direccion' => 'required|string|max:255',
            'estado' => 'required|in:pendiente,en_proceso,completada,cancelada',
            'observaciones' => 'nullable|string',
        ]);

        $orden->update($request->all());

        return redirect()->route('ordenes.index')->with('success', 'Orden actualizada exitosamente.');
    }
    
    public function destroy(OrdenCorte $orden)
    {
        $orden->delete();
        return redirect()->route('ordenes.index')->with('success', 'Orden eliminada exitosamente.');
    }
}
