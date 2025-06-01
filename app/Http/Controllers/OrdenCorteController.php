<?php

namespace App\Http\Controllers;

use App\Models\OrdenCorte;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\OrdenCorteRequest;
use App\Models\User;
use App\Models\Zona;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class OrdenCorteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $ordenCortes = OrdenCorte::paginate();

        return view('orden-corte.index', compact('ordenCortes'))
            ->with('i', ($request->input('page', 1) - 1) * $ordenCortes->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $ordenCorte = new OrdenCorte();
        $zonas = Zona::all();       // Ajusta el modelo si tienes otro nombre
        $usuarios = User::all();    // Usuarios registrados


        return view('orden-corte.create', compact('ordenCorte', 'zonas', 'usuarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrdenCorteRequest $request): RedirectResponse
    {
        OrdenCorte::create($request->validated());

        return Redirect::route('orden-cortes.index')
            ->with('success', 'OrdenCorte created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $ordenCorte = OrdenCorte::find($id);
        $zonas = Zona::all();
        $usuarios = User::all();

        return view('orden-corte.show', compact('ordenCorte', 'zonas', 'usuarios'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $ordenCorte = OrdenCorte::find($id);
        $zonas = Zona::all();
        $usuarios = User::all();

        return view('orden-corte.edit', compact('ordenCorte', 'zonas', 'usuarios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrdenCorteRequest $request, OrdenCorte $ordenCorte): RedirectResponse
    {
        $ordenCorte->update($request->validated());

        return Redirect::route('orden-cortes.index')
            ->with('success', 'OrdenCorte updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        OrdenCorte::find($id)->delete();

        return Redirect::route('orden-cortes.index')
            ->with('success', 'OrdenCorte deleted successfully');
    }
}
