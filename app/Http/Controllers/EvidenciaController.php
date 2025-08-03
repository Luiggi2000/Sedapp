<?php

namespace App\Http\Controllers;

use App\Models\Evidencia;
use App\Models\OrdenCorte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EvidenciaController extends Controller
{
    public function index()
    {
        $evidencias = Evidencia::with('ordenCorte.zona')
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        
        $ordenes = OrdenCorte::with('zona')->get();
        
        return view('evidencias.index', compact('evidencias', 'ordenes'));
    }

    public function create()
    {
        $ordenes = OrdenCorte::with('zona')->get();
        return view('evidencias.create', compact('ordenes'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'orden_corte_id' => 'required|exists:orden_cortes,id',
            'tipo' => 'required|in:antes,durante,despues',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'observaciones' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        // Subir imagen
        $imagen = $request->file('imagen');
        $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
        $rutaImagen = $imagen->storeAs('evidencias', $nombreImagen, 'public');

        $evidencia = Evidencia::create([
            'orden_corte_id' => $request->orden_corte_id,
            'tipo' => $request->tipo,
            'imagen' => $rutaImagen,
            'observaciones' => $request->observaciones
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Evidencia subida exitosamente',
            'evidencia' => $evidencia->load('ordenCorte')
        ]);
    }

    public function show(Evidencia $evidencia)
    {
        $evidencia->load('ordenCorte.zona');
        return view('evidencias.show', compact('evidencia'));
    }

    public function edit(Evidencia $evidencia)
    {
        $ordenes = OrdenCorte::with('zona')->get();
        return view('evidencias.edit', compact('evidencia', 'ordenes'));
    }

    public function update(Request $request, Evidencia $evidencia)
    {
        $validator = Validator::make($request->all(), [
            'orden_corte_id' => 'required|exists:orden_cortes,id',
            'tipo' => 'required|in:antes,durante,despues',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'observaciones' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = [
            'orden_corte_id' => $request->orden_corte_id,
            'tipo' => $request->tipo,
            'observaciones' => $request->observaciones
        ];

        // Si se subió nueva imagen
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior
            if ($evidencia->imagen && Storage::disk('public')->exists($evidencia->imagen)) {
                Storage::disk('public')->delete($evidencia->imagen);
            }

            // Subir nueva imagen
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $rutaImagen = $imagen->storeAs('evidencias', $nombreImagen, 'public');
            $data['imagen'] = $rutaImagen;
        }

        $evidencia->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Evidencia actualizada exitosamente',
            'evidencia' => $evidencia->load('ordenCorte')
        ]);
    }

    public function destroy(Evidencia $evidencia)
    {
        // Eliminar imagen del storage
        if ($evidencia->imagen && Storage::disk('public')->exists($evidencia->imagen)) {
            Storage::disk('public')->delete($evidencia->imagen);
        }

        $evidencia->delete();

        return response()->json([
            'success' => true,
            'message' => 'Evidencia eliminada exitosamente'
        ]);
    }
}
