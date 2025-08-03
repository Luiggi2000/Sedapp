<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\OrdenCorte;
use App\Models\Zona;
use App\Models\Evidencia;
use App\Models\Role;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas generales
        $totalUsuarios = User::count();
        $totalOrdenes = OrdenCorte::count();
        $totalZonas = Zona::count();
        $totalEvidencias = Evidencia::count();
        
        // Estados de órdenes
        $ordenesPendientes = OrdenCorte::where('estado', 'pendiente')->count();
        $ordenesEnProceso = OrdenCorte::where('estado', 'en_proceso')->count();
        $ordenesCompletadas = OrdenCorte::where('estado', 'completada')->count();
        $ordenesCanceladas = OrdenCorte::where('estado', 'cancelada')->count();
        
        // Técnicos activos (usuarios con rol de técnico)
        $rolTecnico = Role::where('name', 'tecnico')->first();
        $tecnicosActivos = $rolTecnico ? User::where('rol_id', $rolTecnico->id)->count() : 0;
        
        // Órdenes recientes (últimas 5)
        $ordenesRecientes = OrdenCorte::with(['zona', 'tecnico', 'afectado'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Evidencias recientes (últimas 4)
        $evidenciasRecientes = Evidencia::with('ordenCorte')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();
        
        // Estadísticas por zona (zonas con más órdenes)
        $estadisticasZonas = Zona::withCount('ordenCortes')
            ->orderBy('orden_cortes_count', 'desc')
            ->limit(5)
            ->get();
        
        // Datos para gráfico de órdenes por zona
        $ordenesPorZona = Zona::withCount('ordenCortes')
            ->having('orden_cortes_count', '>', 0)
            ->orderBy('orden_cortes_count', 'desc')
            ->get()
            ->map(function ($zona) {
                return [
                    'zona' => $zona->nombre,
                    'cantidad' => $zona->orden_cortes_count
                ];
            });

        return view('dashboard', compact(
            'totalUsuarios',
            'totalOrdenes', 
            'totalZonas',
            'totalEvidencias',
            'ordenesPendientes',
            'ordenesEnProceso', 
            'ordenesCompletadas',
            'ordenesCanceladas',
            'tecnicosActivos',
            'ordenesRecientes',
            'evidenciasRecientes',
            'estadisticasZonas',
            'ordenesPorZona'
        ));
    }
}
