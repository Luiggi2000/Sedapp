<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Evidencias') }}
            </h2>
            <a href="{{ route('evidencias.create') }}" class="btn btn-primary">
                {{ __('Crear') }}
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Evidencias') }}</h3>

                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 table-auto">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Orden Corte Id</th>

                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Observaciones</th>
                                    <th class="px-4 py-2 text-center">Acciones</th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($evidencias as $evidencia)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $evidencia->orden_corte_id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $evidencia->observaciones }}</td>

                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            <a href="{{ route('evidencias.show', $evidencia->id) }}"
                                                class="text-blue-600 hover:text-blue-900">{{ __('Show') }}</a>
                                            <a href="{{ route('evidencias.edit', $evidencia->id) }}"
                                                class="text-green-600 hover:text-green-900">{{ __('Edit') }}</a>
                                            <form action="{{ route('evidencias.destroy', $evidencia->id) }}"
                                                method="POST" class="inline-block"
                                                onsubmit="return confirm('Are you sure to delete?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {!! $evidencias->withQueryString()->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
