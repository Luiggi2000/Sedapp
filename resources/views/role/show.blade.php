<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Show Role') }}
            </h2>
            <a href="{{ route('roles.index') }}" class="btn btn-primary">
                {{ __('Back') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">{{ __('Name') }}</h3>
                    <p class="text-gray-900">{{ $role->name }}</p>
                </div>

                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">{{ __('Guard Name') }}</h3>
                    <p class="text-gray-900">{{ $role->guard_name }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
