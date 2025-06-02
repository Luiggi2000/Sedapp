<?php

namespace App\Http\Controllers;

use App\Models\OrdenCorte;
use App\Models\Evidencia;

use App\Models\User;
use App\Models\Zona;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class OrdenCorteController extends Controller
{
    // Mostrar las órdenes asignadas al técnico autenticado
public function misOrdenes(): View
{
    $user = auth()->user();

    $ordenes = OrdenCorte::where('user_id', $user->id)
                ->orderByRaw("CASE WHEN estado = 'en proceso' THEN 0 ELSE 1 END")
                ->orderBy('fecha', 'desc')
                ->paginate(15);

    // Buscar la orden que está en proceso para resaltarla
    $ordenTomada = $ordenes->firstWhere('estado', 'en proceso');
    $ordenTomadaId = $ordenTomada ? $ordenTomada->id : null;

    return view('orden-corte.mis-ordenes', compact('ordenes', 'user', 'ordenTomadaId'));
}


    // Mostrar detalle de una orden asignada al técnico autenticado
    public function showMisOrden(OrdenCorte $orden): View
    {
        $user = auth()->user();

        if ($orden->user_id !== $user->id) {
            abort(403, 'No tienes permiso para ver esta orden.');
        }

        return view('orden-corte.showmis-ordenes', compact('orden'));
    }

    // Acción para que el técnico tome una orden pendiente sin asignar
    public function tomarOrden(OrdenCorte $orden): RedirectResponse
{
    $user = auth()->user();

    if ($orden->estado !== 'pendiente') {
        return redirect()->route('mis-ordenes.index')->with('error', 'No puedes tomar esta orden.');
    }

    $orden->user_id = $user->id;
    $orden->estado = 'en proceso';
    $orden->save();

    return redirect()->route('mis-ordenes.index')->with('success', 'Orden tomada correctamente.');
}


    // Actualizar el estado de la orden (completada o no realizada)
    public function actualizarEstado(Request $request, OrdenCorte $orden): RedirectResponse
    {
        $request->validate([
            'estado' => 'required|in:completada,no realizada',
        ]);

        if ($orden->tecnico_id !== auth()->id()) {
            return redirect()->route('mis-ordenes.index')->with('error', 'No autorizado.');
        }

        $orden->estado = $request->estado;
        $orden->save();

        return redirect()->route('mis-ordenes.showmis-ordenes', $orden->id)
            ->with('success', 'Estado actualizado.');
    }

 public function subirEvidencia(Request $request, $ordenId)
{
    $request->validate([
        'evidencia' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // max 5MB
        'observaciones' => 'nullable|string|max:1000',
        'estado_final' => 'nullable|in:completado,no realizado',
    ]);

    $orden = OrdenCorte::findOrFail($ordenId);

    // Guardar archivo en storage (puedes ajustar carpeta y disco)
    $path = $request->file('evidencia')->store('evidencias');

    // Crear evidencia en BD
    $evidencia = new Evidencia();
    $evidencia->orden_corte_id = $orden->id;
    $evidencia->imagen = $path;
    $evidencia->observaciones = $request->input('observaciones');
    $evidencia->save();

    // Cambiar estado final si viene
    if ($request->filled('estado_final')) {
        $orden->estado = $request->input('estado_final');
        $orden->save();
    }

    return redirect()->route('mis-ordenes.index', $orden->id)
        ->with('success', 'Evidencia subida correctamente.');
}


    // Métodos CRUD básicos (index, create, store, show, edit, update, destroy)
    public function index(Request $request): View
    {
        $ordenCortes = OrdenCorte::paginate();

        return view('orden-corte.index', compact('ordenCortes'))
            ->with('i', ($request->input('page', 1) - 1) * $ordenCortes->perPage());
    }

    public function create(): View
    {
        $ordenCorte = new OrdenCorte();
        $zonas = Zona::all();
        $usuarios = User::where('rol_id', 2)->get(); // Ajustar ID de rol según sistema

        return view('orden-corte.create', compact('ordenCorte', 'zonas', 'usuarios'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'zona_id' => 'required|exists:zonas,id',
            'user_id' => 'required|exists:users,id',
            'fecha' => 'required|date',
            'direccion' => 'required|string|max:255',
        ]);

        OrdenCorte::create([
            'zona_id' => $request->zona_id,
            'user_id' => $request->user_id,
            'fecha' => $request->fecha,
            'direccion' => $request->direccion,
            'estado' => 'pendiente',
        ]);

        return redirect()->route('orden-cortes.index')->with('success', 'Orden de corte creada correctamente.');
    }

    public function show($id): View
    {
        $ordenCorte = OrdenCorte::findOrFail($id);
        $zonas = Zona::all();
        $usuarios = User::all();

        return view('orden-corte.show', compact('ordenCorte', 'zonas', 'usuarios'));
    }

    public function edit($id): View
    {
        $ordenCorte = OrdenCorte::findOrFail($id);
        $zonas = Zona::all();
        $usuarios = User::all();

        return view('orden-corte.edit', compact('ordenCorte', 'zonas', 'usuarios'));
    }

    public function update(Request $request, OrdenCorte $ordenCorte): RedirectResponse
    {
        $request->validate([
            'zona_id' => 'required|exists:zonas,id',
            'user_id' => 'required|exists:users,id',
            'fecha' => 'required|date',
            'direccion' => 'required|string|max:255',
            'estado' => 'required|string',
        ]);

        $ordenCorte->update($request->all());

        return redirect()->route('orden-cortes.index')->with('success', 'Orden actualizada correctamente.');
    }

    public function destroy($id): RedirectResponse
    {
        $ordenCorte = OrdenCorte::findOrFail($id);
        $ordenCorte->delete();

        return redirect()->route('orden-cortes.index')->with('success', 'Orden eliminada correctamente.');
    }
}
