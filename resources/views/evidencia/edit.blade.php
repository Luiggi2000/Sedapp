<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update') }} Evidencia
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('evidencias.update', $evidencia->id) }}" enctype="multipart/form-data" role="form">
                    @method('PATCH')
                    @csrf

                    @include('evidencia.form')

                    <div class="mt-6 flex items-center space-x-4">
                        <x-primary-button>
                            {{ __('Guardar') }}
                        </x-primary-button>
                        <a href="{{ route('evidencias.index') }}" class="text-gray-600 hover:text-gray-900">
                            {{ __('Cancelar') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
