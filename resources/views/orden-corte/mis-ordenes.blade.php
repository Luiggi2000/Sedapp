<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Órdenes de Corte') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">#</th>
                            <th class="px-4 py-2">Zona</th>
                            <th class="px-4 py-2">Fecha</th>
                            <th class="px-4 py-2">Dirección</th>
                            <th class="px-4 py-2">Estado</th>
                            <th class="px-4 py-2">Acciones</th>
                        </tr>
                    </thead>
                 <tbody class="bg-white divide-y divide-gray-200">
    @foreach ($ordenes as $index => $orden)
        <tr @class([
            'bg-yellow-100 font-semibold' => $orden->id === $ordenTomadaId,
        ])>
            <td class="px-4 py-2">{{ $index + 1 }}</td>
            <td class="px-4 py-2">{{ $orden->zona->nombre ?? 'N/A' }}</td>
            <td class="px-4 py-2">{{ $orden->fecha }}</td>
            <td class="px-4 py-2">{{ $orden->direccion }}</td>
            <td class="px-4 py-2">{{ ucfirst($orden->estado) }}</td>
<td class="px-4 py-2">
    <a href="{{ route('mis-ordenes.showmis-ordenes', $orden->id) }}" class="text-blue-600 hover:underline mr-2">Ver</a>
<form action="{{ route('mis-ordenes.tomarOrden', $orden->id) }}" method="POST" class="inline">
    @csrf
    @method('PATCH')  {{-- Esto indica que el método es PATCH --}}
    <button type="submit" 
        class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600"
        onclick="return confirm('¿Estás seguro de tomar esta orden?')">
        Tomar Orden
    </button>
</form>
</td>
        </tr>
    @endforeach
</tbody>
                </table>

                <div class="mt-4">
                    {{ $ordenes->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
