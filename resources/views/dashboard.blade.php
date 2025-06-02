<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Dashboard</h2>
    </x-slot>

    <div class="py-6 px-4 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 bg-white r  ounded shadow">Total Órdenes: <strong>{{ $totalOrdenes }}</strong></div>
          
        </div>

        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-bold mb-2">Órdenes por Zona</h3>
            <ul>
                @foreach($ordenesPorZona as $item)
                    <li>{{ $item->zona->nombre }}: {{ $item->total }}</li>
                @endforeach
            </ul>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-bold mb-2">Últimos Cortes</h3>
                <ul>
                    @foreach($ultimosCortes as $orden)
                        <li>#{{ $orden->id }} - {{ $orden->estado }} - {{ $orden->updated_at->format('d/m/Y H:i') }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-bold mb-2">Últimas Evidencias</h3>
                <ul>
                    @foreach($ultimasEvidencias as $evidencia)
                        <li>{{ $evidencia->created_at->format('d/m/Y H:i') }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
