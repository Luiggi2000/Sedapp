<?php

namespace App\Http\Controllers;

use App\Models\OrdenCorte;
use App\Models\Evidencia;
use App\Models\Zona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas generales
        $totalOrdenes = OrdenCorte::count();
        $ordenesCompletadas = OrdenCorte::where('estado', 'completado')->count();
        $ordenesPendientes = OrdenCorte::where('estado', 'pendiente')->count();
        $totalEvidencias = Evidencia::count();

        // Órdenes por zona
        $ordenesPorZona = OrdenCorte::select('zona_id', DB::raw('count(*) as total'))
            ->with('zona')
            ->groupBy('zona_id')
            ->get();

        // Últimas órdenes
        $ultimasOrdenes = OrdenCorte::with('zona')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Últimas evidencias
        $ultimasEvidencias = Evidencia::with('ordencorte')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalOrdenes',
            'ordenesCompletadas', 
            'ordenesPendientes',
            'totalEvidencias',
            'ordenesPorZona',
            'ultimasOrdenes',
            'ultimasEvidencias'
        ));
    }
}
