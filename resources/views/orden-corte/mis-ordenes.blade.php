<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Órdenes de Corte') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full table-auto border-collapse border border-gray-300">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">#</th>
                            <th class="border border-gray-300 px-4 py-2">Zona</th>
                            <th class="border border-gray-300 px-4 py-2">Fecha</th>
                            <th class="border border-gray-300 px-4 py-2">Dirección</th>
                            <th class="border border-gray-300 px-4 py-2">Estado</th>
                            <th class="border border-gray-300 px-4 py-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ordenes as $index => $orden)
                            <tr class="text-center">
                                <td class="border border-gray-300 px-4 py-2">{{ $index + 1 }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $orden->zona->nombre ?? 'N/A' }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $orden->fecha }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $orden->direccion }}</td>
                                <td class="border border-gray-300 px-4 py-2 capitalize">{{ $orden->estado }}</td>
                                <td class="border border-gray-300 px-4 py-2">

                                    <a href="{{ route('orden-cortes.show', $orden->id) }}" class="inline-block px-3 py-1 text-white bg-blue-600 rounded hover:bg-blue-700">Ver</a>

                                    @if($orden->estado === 'pendiente')
                                        <form action="{{ route('orden-cortes.tomar', $orden->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-block px-3 py-1 ml-2 text-white bg-yellow-500 rounded hover:bg-yellow-600">Tomar Orden</button>
                                        </form>
                                    @endif

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
