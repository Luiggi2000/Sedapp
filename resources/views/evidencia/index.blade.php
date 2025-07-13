<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800 leading-tight">
                Evidencias
            </h2>
            <a href="{{ route('evidencias.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                Crear
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($message = Session::get('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded">
                    {{ $message }}
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Orden Corte</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Observaciones</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($evidencias as $evidencia)
                                    <tr>
                                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4">{{ $evidencia->orden_corte_id }}</td>
                                        <td class="px-6 py-4">{{ $evidencia->observaciones }}</td>
                                        <td class="px-6 py-4 text-center space-x-2">
                                            <a href="{{ route('evidencias.show', $evidencia->id) }}"
                                               class="text-blue-600 hover:text-blue-900 font-medium">Ver</a>
                                            <a href="{{ route('evidencias.edit', $evidencia->id) }}"
                                               class="text-green-600 hover:text-green-900 font-medium">Editar</a>
                                            <form action="{{ route('evidencias.destroy', $evidencia->id) }}"
                                                  method="POST"
                                                  class="inline-block"
                                                  onsubmit="return confirm('¿Seguro de eliminar esta evidencia?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-900 font-medium">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {!! $evidencias->withQueryString()->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
