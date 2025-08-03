<?php

namespace App\Http\Controllers;

use App\Models\Evidencia;
use App\Models\OrdenCorte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EvidenciasController extends Controller
{
    public function index()
    {
        $evidencias = Evidencia::with('ordenCorte')
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        $ordenes = OrdenCorte::with(['tecnico', 'zona'])->get();
        
        return view('evidencias.index', compact('evidencias', 'ordenes'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'orden_corte_id' => 'required|exists:orden_cortes,id',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tipo' => 'required|in:antes,durante,despues',
            'observaciones' => 'nullable|string',
        ]);

        $imagePath = $request->file('imagen')->store('evidencias', 'public');

        Evidencia::create([
            'orden_corte_id' => $request->orden_corte_id,
            'imagen' => $imagePath,
            'tipo' => $request->tipo,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('evidencias.index')->with('success', 'Evidencia subida exitosamente.');
    }
    
    public function destroy(Evidencia $evidencia)
    {
        // Eliminar archivo físico
        if (Storage::disk('public')->exists($evidencia->imagen)) {
            Storage::disk('public')->delete($evidencia->imagen);
        }
        
        $evidencia->delete();
        return redirect()->route('evidencias.index')->with('success', 'Evidencia eliminada exitosamente.');
    }
}
