<?php

namespace App\Http\Controllers;

use App\Models\Zona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ZonaController extends Controller
{
    public function index()
    {
        $zonas = Zona::withCount('ordenCortes')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('zonas.index', compact('zonas'));
    }

    public function create()
    {
        return view('zonas.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:zonas,nombre',
            'descripcion' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $zona = Zona::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Zona creada exitosamente',
            'zona' => $zona
        ]);
    }

    public function show(Zona $zona)
    {
        $zona->load('ordenCortes.tecnico', 'ordenCortes.afectado');
        return view('zonas.show', compact('zona'));
    }

    public function edit(Zona $zona)
    {
        return view('zonas.edit', compact('zona'));
    }

    public function update(Request $request, Zona $zona)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:zonas,nombre,' . $zona->id,
            'descripcion' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $zona->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Zona actualizada exitosamente',
            'zona' => $zona
        ]);
    }

    public function destroy(Zona $zona)
    {
        // Verificar si tiene órdenes asociadas
        if ($zona->ordenCortes()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar la zona porque tiene órdenes asociadas'
            ], 422);
        }

        $zona->delete();

        return response()->json([
            'success' => true,
            'message' => 'Zona eliminada exitosamente'
        ]);
    }
}
