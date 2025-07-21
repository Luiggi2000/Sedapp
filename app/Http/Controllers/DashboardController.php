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
$zoneColors = [
    1 => 'bg-red-500',
    2 => 'bg-blue-500',
    3 => 'bg-green-500',
    4 => 'bg-yellow-500',
    5 => 'bg-purple-500',
    // agrega más según tus zonas
];
    $ordenesPorZona = OrdenCorte::selectRaw('zona_id, COUNT(*) as total')
        ->groupBy('zona_id')->with('zona')->get();
$ordenesEnProceso = OrdenCorte::where('estado', 'En Proceso')->count();

    $ultimosCortes = OrdenCorte::orderByDesc('updated_at')->take(5)->get();
    $ultimasEvidencias = Evidencia::orderByDesc('created_at')->take(5)->get();
    $totalZones = Zona::count();

    return view('dashboard', compact(
        'totalOrdenes',
        'ordenesPendientes',
            'ordenesEnProceso', // esta línea
        'ordenesEjecutadas',
        'ordenesPorZona',
        'ultimosCortes',
        'ultimasEvidencias',
            'totalZones', // <-- aquí
    'zoneColors' // <- aquí

    ));
}
}
