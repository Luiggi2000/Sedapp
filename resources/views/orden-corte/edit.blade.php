<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ isset($ordenCorte) ? __('Editar Orden de Corte') : __('Crear Orden de Corte') }}
            </h2>
            <a href="{{ route('orden-cortes.index') }}" class="btn btn-secondary">
                {{ __('Cancelar') }}
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-2xl p-6">
                <form method="POST" action="{{ isset($ordenCorte) ? route('orden-cortes.update', $ordenCorte) : route('orden-cortes.store') }}" enctype="multipart/form-data">
                    @csrf
                    @if(isset($ordenCorte))
                        @method('PATCH')
                    @endif

                    @include('orden-corte.form')

                    <div class="mt-6 flex justify-end gap-4">
                        <x-primary-button>
                            {{ isset($ordenCorte) ? __('Actualizar') : __('Guardar') }}
                        </x-primary-button>
                        <a href="{{ route('orden-cortes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-sm font-semibold rounded-md hover:bg-gray-300">
                            {{ __('Cancelar') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
