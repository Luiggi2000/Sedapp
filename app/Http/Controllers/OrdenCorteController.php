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
use Illuminate\Support\Facades\Auth;

class OrdenCorteController extends Controller
{
    public function misOrdenes(Request $request): View
{
    $user = Auth::user();

    $ordenes = OrdenCorte::where('user_id', $user->id)->paginate();

    return view('orden-corte.mis-ordenes', compact('ordenes'))
        ->with('i', ($request->input('page', 1) - 1) * $ordenes->perPage());
}

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
$usuarios = User::where('rol_id', 2)->get(); // Usa el ID real del rol técnico

        return view('orden-corte.create', compact('ordenCorte', 'zonas', 'usuarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
 public function store(Request $request)
{
   $request->validate([
    'zona_id' => 'required',
    'user_id' => 'required',
    'fecha' => 'required|date',
    'direccion' => 'required|string|max:255',
]);
    OrdenCorte::create([
    'zona_id' => $request->zona_id,
    'user_id' => $request->user_id,
    'fecha' => $request->fecha,
    'direccion' => $request->direccion,
    'estado' => 'pendiente', // En minúscula como en la migración
]);


    return redirect()->route('orden-cortes.index')
        ->with('success', 'Orden de corte creada correctamente.');
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
