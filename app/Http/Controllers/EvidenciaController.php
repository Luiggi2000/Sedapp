<?php

namespace App\Http\Controllers;

use App\Models\Evidencia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\EvidenciaRequest;
use App\Models\OrdenCorte;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class EvidenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $evidencias = Evidencia::paginate();

        return view('evidencia.index', compact('evidencias'))
            ->with('i', ($request->input('page', 1) - 1) * $evidencias->perPage());
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $evidencia = new Evidencia();
        $ordenCortes = OrdenCorte::all();

        return view('evidencia.create', compact('evidencia', 'ordenCortes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'orden_corte_id' => 'required',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'observaciones' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('imagen')) {
            $imagePath = $request->file('imagen')->store('public');
            // Guarda la imagen en storage/app/public/evidencias
            $validated['imagen'] = $imagePath;  // guarda solo el path relativo
        }

        Evidencia::create($validated);

        return redirect()->route('evidencias.index')->with('success', 'Evidencia creada correctamente.');
    }


    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $evidencia = Evidencia::find($id);

        return view('evidencia.show', compact('evidencia'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $evidencia = Evidencia::find($id);
        $ordenCortes = OrdenCorte::all();

        return view('evidencia.edit', compact('evidencia', 'ordenCortes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EvidenciaRequest $request, Evidencia $evidencia): RedirectResponse
    {
        $evidencia->update($request->validated());

        return Redirect::route('evidencias.index')
            ->with('success', 'Evidencia updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Evidencia::find($id)->delete();

        return Redirect::route('evidencias.index')
            ->with('success', 'Evidencia deleted successfully');
    }
}
