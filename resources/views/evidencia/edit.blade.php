<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            Editar Evidencia
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <form method="POST" action="{{ route('evidencias.update', $evidencia->id) }}" enctype="multipart/form-data">
                    @method('PATCH')
                    @csrf

                    @include('evidencia.form')

                    <div class="mt-6 flex justify-end space-x-4">
                        <x-primary-button>
                            Guardar
                        </x-primary-button>
                        <a href="{{ route('evidencias.index') }}" class="text-gray-600 hover:text-gray-900 text-sm">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
