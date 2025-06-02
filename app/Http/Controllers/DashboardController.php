<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdenCorte;
use App\Models\Evidencia;
use App\Models\Zona;
use Carbon\Carbon;

class DashboardController extends Controller
{
public function index()
{
    $totalOrdenes = OrdenCorte::count();
    $ordenesPendientes = OrdenCorte::where('estado', 'Pendiente')->count();
    $ordenesEjecutadas = OrdenCorte::where('estado', 'Completada')->count();

    $ordenesPorZona = OrdenCorte::selectRaw('zona_id, COUNT(*) as total')
        ->groupBy('zona_id')->with('zona')->get();

    $ultimosCortes = OrdenCorte::orderByDesc('updated_at')->take(5)->get();
    $ultimasEvidencias = Evidencia::orderByDesc('created_at')->take(5)->get();

    return view('dashboard', compact(
        'totalOrdenes',
        'ordenesPendientes',
        'ordenesEjecutadas',
        'ordenesPorZona',
        'ultimosCortes',
        'ultimasEvidencias'
    ));
}
}
