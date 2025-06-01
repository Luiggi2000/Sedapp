<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Zonas') }}
            </h2>
            <a href="{{ route('zonas.create') }}" class="btn btn-primary">
                {{ __('Create New') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($message = Session::get('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ $message }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($zonas as $zona)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ ++$i }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $zona->nombre }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $zona->descripcion }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <form action="{{ route('zonas.destroy', $zona->id) }}" method="POST" onsubmit="return confirm('Are you sure to delete?')">
                                            <a href="{{ route('zonas.show', $zona->id) }}" class="text-blue-600 hover:text-blue-900 mr-2">
                                                {{ __('Show') }}
                                            </a>
                                            <a href="{{ route('zonas.edit', $zona->id) }}" class="text-green-600 hover:text-green-900 mr-2">
                                                {{ __('Edit') }}
                                            </a>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {!! $zonas->withQueryString()->links() !!}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
