<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update') }} Role
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('roles.update', $role->id) }}" enctype="multipart/form-data">
                        @method('PATCH')
                        @csrf

                        @include('role.form')

                        <div class="mt-4">
                        <x-primary-button>
                            {{ __('Actualizar') }}
                        </x-primary-button>
                        <a href="{{ route('roles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 ml-2">
                            {{ __('Cancelar') }}
                        </a>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
