<?php

namespace App\Http\Controllers;

use App\Models\Zona;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ZonaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ZonaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $zonas = Zona::paginate();

        return view('zona.index', compact('zonas'))
            ->with('i', ($request->input('page', 1) - 1) * $zonas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $zona = new Zona();

        return view('zona.create', compact('zona'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ZonaRequest $request): RedirectResponse
    {
        Zona::create($request->validated());

        return Redirect::route('zonas.index')
            ->with('success', 'Zona created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $zona = Zona::find($id);

        return view('zona.show', compact('zona'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $zona = Zona::find($id);

        return view('zona.edit', compact('zona'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ZonaRequest $request, Zona $zona): RedirectResponse
    {
        $zona->update($request->validated());

        return Redirect::route('zonas.index')
            ->with('success', 'Zona updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Zona::find($id)->delete();

        return Redirect::route('zonas.index')
            ->with('success', 'Zona deleted successfully');
    }
}
