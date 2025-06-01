<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Ordenes de Cortes') }}
            </h2>
            <a href="{{ route('orden-cortes.create') }}" class="btn btn-primary">
                {{ __('Create New') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($message = Session::get('success'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ $message }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-lg font-semibold">{{ __('Listado de Ordenes de Corte') }}</span>

                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto text-sm text-left">
                            <thead class="bg-gray-100 text-gray-700">
                                <tr>
                                    <th class="px-4 py-2">No</th>
                                    <th class="px-4 py-2">Zona</th>
                                    <th class="px-4 py-2">Usuario</th>
                                    <th class="px-4 py-2">Fecha</th>
                                    <th class="px-4 py-2">Estado</th>
                                    <th class="px-4 py-2 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ordenCortes as $ordenCorte)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">{{ ++$i }}</td>
                                        <td class="px-4 py-2">{{ $ordenCorte->zona->nombre }}</td>
                                        <td class="px-4 py-2">{{ $ordenCorte->user->name }}</td>
                                        <td class="px-4 py-2">{{ $ordenCorte->fecha }}</td>
                                        <td class="px-4 py-2">{{ $ordenCorte->estado }}</td>
                                        <td class="px-4 py-2 text-center">
                                            <div class="flex space-x-2 justify-center">
                                                <a href="{{ route('orden-cortes.show', $ordenCorte->id) }}"
                                                    class="text-blue-600 hover:text-blue-900 text-sm">
                                                    {{ __('Ver') }}
                                                </a>
                                                <a href="{{ route('orden-cortes.edit', $ordenCorte->id) }}"
                                                    class="text-green-600 hover:text-green-900 text-sm">
                                                    {{ __('Editar') }}
                                                </a>
                                                <form action="{{ route('orden-cortes.destroy', $ordenCorte->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('¿Estás seguro de eliminar esta orden?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900 text-sm">
                                                        {{ __('Eliminar') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {!! $ordenCortes->withQueryString()->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
