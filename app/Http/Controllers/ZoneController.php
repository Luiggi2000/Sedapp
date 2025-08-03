<?php

namespace App\Http\Controllers;

use App\Models\Zona;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    public function index()
    {
        $zonas = Zona::withCount('ordenCortes')->paginate(10);
        return view('zonas.index', compact('zonas'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:zonas',
            'descripcion' => 'nullable|string',
        ]);

        Zona::create($request->all());

        return redirect()->route('zonas.index')->with('success', 'Zona creada exitosamente.');
    }
    
    public function update(Request $request, Zona $zona)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:zonas,nombre,' . $zona->id,
            'descripcion' => 'nullable|string',
        ]);

        $zona->update($request->all());

        return redirect()->route('zonas.index')->with('success', 'Zona actualizada exitosamente.');
    }
    
    public function destroy(Zona $zona)
    {
        $zona->delete();
        return redirect()->route('zonas.index')->with('success', 'Zona eliminada exitosamente.');
    }
}
