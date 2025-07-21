@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-bold flex items-center gap-2 mb-2" style="color: #023A91;">
                Bienvenido {{ $user->name ?? 'Usuario' }}
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><path d="M18 11.5V9a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v7.5"/><path d="M14 17.5V19a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-7.5"/><path d="M10 13.5V15a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-7.5"/><path d="M22 15.5V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-7.5"/></svg>
            </h1>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-1">Resumen</h2>
                    <p class="text-sm text-gray-500">Del 1 - 13 Enero, 2025</p>
                </div>
                <form action="{{ route('export.dashboard.data') }}" method="GET">
                    <button type="submit" onclick="addActivity('exportó datos del dashboard')" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-white border border-gray-300 text-gray-700 shadow-sm hover:bg-gray-50 h-9 px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                        Exportar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="rounded-xl border bg-card text-card-foreground shadow bg-gradient-to-br from-pink-100 to-pink-200 border-pink-200 cursor-pointer hover:shadow-md transition-shadow" onclick="addActivity('revisó total de órdenes')">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalOrdenes }}</p>
                        <p class="text-sm font-medium text-gray-700">Total Órdenes</p>
                        <p class="text-xs text-blue-600 mt-1">General</p>
                    </div>
                    <div class="w-10 h-10 bg-pink-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fillRule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clipRule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-xl border bg-card text-card-foreground shadow bg-gradient-to-br from-green-100 to-green-200 border-green-200 cursor-pointer hover:shadow-md transition-shadow" onclick="addActivity('revisó zonas registradas')">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalZones }}</p>
                        <p class="text-sm font-medium text-gray-700">Zonas</p>
                        <p class="text-xs text-blue-600 mt-1">Registradas</p>
                    </div>
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fillRule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clipRule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-xl border bg-card text-card-foreground shadow bg-gradient-to-br from-purple-100 to-purple-200 border-purple-200 cursor-pointer hover:shadow-md transition-shadow" onclick="addActivity('revisó órdenes pendientes')">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $ordenesPendientes }}</p>
                        <p class="text-sm font-medium text-gray-700">Pendientes</p>
                        <p class="text-xs text-blue-600 mt-1">Espera de corte</p>
                    </div>
                    <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fillRule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clipRule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Bar Chart -->
        <div class="lg:col-span-2">
            <div class="rounded-xl border bg-card text-card-foreground shadow">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-lg font-semibold" style="color: #023A91;">Zonas con más órdenes activas</h3>
                    <p class="text-sm text-gray-500">Distribución semanal por zona con detalles por estado</p>
                </div>
                <div class="p-6 pt-0">
                    <div class="w-full h-[350px]">
                        <canvas id="dashboardChart"></canvas>
                    </div>
                    <div class="flex justify-center flex-wrap gap-4 mt-4">
                        @foreach($zoneColors as $zone => $color)
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 rounded-full" style="background-color: {{ $color }}"></div>
                                <span class="text-sm font-medium">{{ $zone }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Status -->
            <div class="rounded-xl border bg-card text-card-foreground shadow">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-lg font-semibold" style="color: #023A91;">Estado de las órdenes</h3>
                    <p class="text-sm text-gray-500">Datos en tiempo real del sistema</p>
                </div>
                <div class="p-6 pt-0 space-y-4">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center cursor-pointer hover:bg-gray-50 p-3 rounded-lg transition-colors" onclick="addActivity('revisó órdenes en curso')">
                            <span class="text-sm font-medium">En curso</span>
                            <div class="flex items-center space-x-3">
                                <div class="w-20 h-2 bg-blue-200 rounded-full">
                                    <div class="h-2 rounded-full transition-all duration-500" style="background-color: #023A91; width: {{ min(100, ($ordenesEnProceso / $totalOrdenes) * 100) }}%;"></div>
                                </div>
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-blue-600 text-blue-600">{{ $ordenesEnProceso }}</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center cursor-pointer hover:bg-gray-50 p-3 rounded-lg transition-colors" onclick="addActivity('revisó órdenes completadas')">
                            <span class="text-sm font-medium">Completadas</span>
                            <div class="flex items-center space-x-3">
                                <div class="w-20 h-2 bg-green-200 rounded-full">
                                    <div class="h-2 bg-green-500 rounded-full transition-all duration-500" style="width: {{ min(100, ($ordenesEjecutadas / $totalOrdenes) * 100) }}%;"></div>
                                </div>
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-green-600 text-green-600">{{ $ordenesEjecutadas }}</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center cursor-pointer hover:bg-gray-50 p-3 rounded-lg transition-colors" onclick="addActivity('revisó órdenes pendientes')">
                            <span class="text-sm font-medium">Pendientes</span>
                            <div class="flex items-center space-x-3">
                                <div class="w-20 h-2 bg-orange-200 rounded-full">
                                    <div class="h-2 bg-orange-500 rounded-full transition-all duration-500" style="width: {{ min(100, ($ordenesPendientes / $totalOrdenes) * 100) }}%;"></div>
                                </div>
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-orange-600 text-orange-600">{{ $ordenesPendientes }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen total -->
                    <div class="pt-3 border-t border-gray-200">
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-semibold text-gray-700">Total de órdenes:</span>
                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 bg-gray-100 text-gray-800">{{ $totalOrdenes }}</span>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
    
@endsection
