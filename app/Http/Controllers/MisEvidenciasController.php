<?php
    
    namespace App\Http\Controllers;

    use App\Models\Evidencia;
    use App\Models\OrdenCorte;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Storage;

    class MisEvidenciasController extends Controller
    {
        public function index(Request $request)
        {
            $user = Auth::user();
            
            // Verificar que es cliente
            if ($user->role->name !== 'cliente') {
                abort(403, 'Solo los clientes pueden acceder a esta sección.');
            }

            // Obtener evidencias de las órdenes del cliente
            $query = Evidencia::with(['ordenCorte.zona', 'ordenCorte.tecnico'])
                ->whereHas('ordenCorte', function($q) use ($user) {
                    $q->where('afectado_id', $user->id);
                });

            // Aplicar filtros
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('observaciones', 'like', "%{$search}%")
                    ->orWhere('tipo', 'like', "%{$search}%")
                    ->orWhereHas('ordenCorte', function($oq) use ($search) {
                        $oq->where('direccion', 'like', "%{$search}%");
                    });
                });
            }

            if ($request->filled('tipo')) {
                $query->where('tipo', $request->tipo);
            }

            if ($request->filled('orden_id')) {
                $query->where('orden_corte_id', $request->orden_id);
            }

            $evidencias = $query->orderBy('created_at', 'desc')->paginate(12);

            // Obtener órdenes del cliente para filtros
            $ordenes = OrdenCorte::where('afectado_id', $user->id)->with('zona')->orderBy('created_at', 'desc')->get();
            $misOrdenes = OrdenCorte::where('afectado_id', $user->id)->with('zona')->orderBy('created_at', 'desc')->get();

            // Estadísticas
            $stats = [
                'total' => Evidencia::whereHas('ordenCorte', function($q) use ($user) {
                    $q->where('afectado_id', $user->id);
                })->count(),
                'antes' => Evidencia::whereHas('ordenCorte', function($q) use ($user) {
                    $q->where('afectado_id', $user->id);
                })->where('tipo', 'antes')->count(),
                'durante' => Evidencia::whereHas('ordenCorte', function($q) use ($user) {
                    $q->where('afectado_id', $user->id);
                })->where('tipo', 'durante')->count(),
                'despues' => Evidencia::whereHas('ordenCorte', function($q) use ($user) {
                    $q->where('afectado_id', $user->id);
                })->where('tipo', 'despues')->count(),
            ];

            return view('mis-evidencias.index', compact('evidencias', 'misOrdenes', 'stats'));
        }

        public function show(Evidencia $evidencia)
        {
            $user = Auth::user();
            
            // Verificar que es cliente y que la evidencia pertenece a una de sus órdenes
            if ($user->role->name !== 'cliente' || $evidencia->ordenCorte->afectado_id !== $user->id) {
                abort(403, 'No tienes permisos para ver esta evidencia.');
            }

            $evidencia->load(['ordenCorte.zona', 'ordenCorte.tecnico']);
            
            return view('mis-evidencias.show', compact('evidencia'));
        }
    }
