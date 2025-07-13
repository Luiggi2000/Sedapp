<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Órdenes de Corte</h2>
            <a href="{{ route('orden-cortes.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nueva Orden
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($message = Session::get('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ $message }}
                </div>
            @endif

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-4 border-b flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Listado de órdenes</h3>
                    <input type="text" placeholder="Buscar..." class="border border-gray-300 rounded-md px-3 py-1 text-sm w-64 focus:outline-none focus:ring focus:border-blue-300" />
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm divide-y divide-gray-200">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">#</th>
                                <th class="px-6 py-3 text-left">Zona</th>
                                <th class="px-6 py-3 text-left">Técnico</th>
                                <th class="px-6 py-3 text-left">Cliente</th>
                                <th class="px-6 py-3 text-left">Fecha</th>
                                <th class="px-6 py-3 text-left">Estado</th>
                                <th class="px-6 py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($ordenCortes as $ordenCorte)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4">{{ $ordenCorte->zona->nombre }}</td>
                                    <td class="px-6 py-4">{{ $ordenCorte->tecnico?->name ?? 'Sin asignar' }}</td>
                                    <td class="px-6 py-4">{{ $ordenCorte->afectado?->name ?? 'Sin asignar' }}</td>
                                    <td class="px-6 py-4">{{ $ordenCorte->fecha }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $ordenCorte->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                            {{ ucfirst($ordenCorte->estado) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center space-x-2">
                                        <a href="{{ route('orden-cortes.show', $ordenCorte->id) }}"
                                           class="text-blue-600 hover:text-blue-800 text-sm">Ver</a>
                                        <a href="{{ route('orden-cortes.edit', $ordenCorte->id) }}"
                                           class="text-green-600 hover:text-green-800 text-sm">Editar</a>
                                        <form action="{{ route('orden-cortes.destroy', $ordenCorte->id) }}"
                                              method="POST" class="inline-block"
                                              onsubmit="return confirm('¿Estás seguro de eliminar esta orden?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t">
                    {!! $ordenCortes->withQueryString()->links() !!}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
