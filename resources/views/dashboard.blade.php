@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Dashboard</h1>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Usuarios -->
        <div class="rounded-lg shadow-sm border border-gray-200 bg-gradient-to-br from-blue-100 to-blue-200 border-blue-200">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Usuarios</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalUsuarios }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-blue-600 mt-2">Registrados en el sistema</p>
            </div>
        </div>

        <!-- Total Órdenes -->
        <div class="rounded-lg shadow-sm border border-gray-200 bg-gradient-to-br from-green-100 to-green-200 border-green-200">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Órdenes</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalOrdenes }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-green-600 mt-2">Órdenes de corte</p>
            </div>
        </div>

        <!-- Total Zonas -->
        <div class="rounded-lg shadow-sm border border-gray-200 bg-gradient-to-br from-purple-100 to-purple-200 border-purple-200">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Zonas</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalZonas }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-purple-600 mt-2">Zonas registradas</p>
            </div>
        </div>

        <!-- Técnicos Activos -->
        <div class="rounded-lg shadow-sm border border-gray-200 bg-gradient-to-br from-orange-100 to-orange-200 border-orange-200">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Técnicos Activos</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $tecnicosActivos }}</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-orange-600 mt-2">Personal disponible</p>
            </div>
        </div>
    </div>

    <!-- Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Órdenes Pendientes -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Pendientes</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $ordenesPendientes }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Órdenes En Proceso -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">En Proceso</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $ordenesEnProceso }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Órdenes Completadas -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Completadas</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $ordenesCompletadas }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Total Evidencias -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Evidencias</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $totalEvidencias }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Zonas con más órdenes -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Zonas con más órdenes activas</h3>
                <p class="text-sm text-gray-600">Distribución por zona</p>
            </div>
            <div class="p-6">
                @if($ordenesPorZona->count() > 0)
                    <div class="space-y-4">
                        @foreach($ordenesPorZona as $zona)
                            @php
                                $maxValue = $ordenesPorZona->max('cantidad');
                                $percentage = $maxValue > 0 ? ($zona['cantidad'] / $maxValue) * 100 : 0;
                            @endphp
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium w-24">{{ $zona['zona'] }}</span>
                                <div class="flex-1 mx-3">
                                    <div class="w-full bg-gray-200 rounded-full h-4">
                                        <div class="bg-blue-600 h-4 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                                <span class="text-sm font-semibold w-8 text-right">{{ $zona['cantidad'] }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="h-64 bg-gray-100 rounded-md flex items-center justify-center text-gray-400">
                        No hay datos de órdenes por zona disponibles
                    </div>
                @endif
            </div>
        </div>

        <!-- Órdenes Recientes -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Órdenes Recientes</h3>
                <p class="text-sm text-gray-600">Últimas órdenes registradas</p>
            </div>
            <div class="p-6">
                @if($ordenesRecientes->count() > 0)
                    <div class="space-y-4">
                        @foreach($ordenesRecientes as $orden)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        Orden #{{ $orden->numero_orden }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $orden->zona->nombre ?? 'Sin zona' }} - {{ $orden->tecnico->nombre_completo ?? 'Sin técnico' }}
                                    </p>
                                </div>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($orden->estado === 'pendiente') bg-yellow-100 text-yellow-800
                                    @elseif($orden->estado === 'en_proceso') bg-blue-100 text-blue-800
                                    @elseif($orden->estado === 'completada') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $orden->texto_estado }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="h-64 bg-gray-100 rounded-md flex items-center justify-center text-gray-400">
                        No hay órdenes recientes
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Evidencias Recientes y Estadísticas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Evidencias Recientes -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Evidencias Recientes</h3>
                <p class="text-sm text-gray-600">Últimas evidencias subidas</p>
            </div>
            <div class="p-6">
                @if($evidenciasRecientes->count() > 0)
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($evidenciasRecientes as $evidencia)
                            <div class="relative">
                                <img src="{{ $evidencia->imagen_url }}" alt="Evidencia" class="w-full h-24 object-cover rounded-lg">
                                <div class="absolute bottom-2 left-2">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if($evidencia->tipo === 'antes') bg-yellow-100 text-yellow-800
                                        @elseif($evidencia->tipo === 'durante') bg-blue-100 text-blue-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ $evidencia->texto_tipo }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="h-64 bg-gray-100 rounded-md flex items-center justify-center text-gray-400">
                        No hay evidencias recientes
                    </div>
                @endif
            </div>
        </div>

        <!-- Estadísticas por Zona -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Estadísticas por Zona</h3>
                <p class="text-sm text-gray-600">Zonas más activas</p>
            </div>
            <div class="p-6">
                @if($estadisticasZonas->count() > 0)
                    <div class="space-y-4">
                        @foreach($estadisticasZonas as $zona)
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $zona->nombre }}</p>
                                    <p class="text-sm text-gray-500">{{ $zona->descripcion ?? 'Sin descripción' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">{{ $zona->orden_cortes_count }} órdenes</p>
                                    <div class="w-24 bg-gray-200 rounded-full h-2 mt-1">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $totalOrdenes > 0 ? ($zona->orden_cortes_count / $totalOrdenes) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="h-64 bg-gray-100 rounded-md flex items-center justify-center text-gray-400">
                        No hay datos de zonas disponibles
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
