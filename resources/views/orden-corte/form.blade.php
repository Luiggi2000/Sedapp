<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <x-input-label for="zona_id" :value="__('Zona')" />
        <select id="zona_id" name="zona_id" class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200" required>
            <option value="" disabled {{ empty(old('zona_id', $ordenCorte->zona_id ?? '')) ? 'selected' : '' }}>
                -- Seleccione una zona --
            </option>
            @foreach ($zonas as $zona)
                <option value="{{ $zona->id }}" {{ old('zona_id', $ordenCorte->zona_id ?? '') == $zona->id ? 'selected' : '' }}>
                    {{ $zona->nombre }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('zona_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="direccion" :value="__('Dirección')" />
        <x-text-input id="direccion" name="direccion" type="text" class="w-full mt-1" :value="old('direccion', $ordenCorte->direccion ?? '')" required autofocus />
        <x-input-error :messages="$errors->get('direccion')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="user_id" :value="__('Técnico Asignado')" />
        <select id="user_id" name="user_id" class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200" required>
            <option value="" disabled {{ empty(old('user_id', $ordenCorte->user_id ?? '')) ? 'selected' : '' }}>
                -- Seleccione un técnico --
            </option>
            @foreach ($usuarios as $usuario)
                <option value="{{ $usuario->id }}" {{ old('user_id', $ordenCorte->user_id ?? '') == $usuario->id ? 'selected' : '' }}>
                    {{ $usuario->name }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="fecha" :value="__('Fecha')" />
        <x-text-input id="fecha" name="fecha" type="date" class="w-full mt-1" :value="old('fecha', $ordenCorte->fecha ?? '')" required />
        <x-input-error :messages="$errors->get('fecha')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="estado" :value="__('Estado')" />
        @if (isset($ordenCorte))
            <select name="estado" id="estado" class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200" required>
                @php $estados = ['pendiente']; @endphp
                @foreach ($estados as $estado)
                    <option value="{{ $estado }}" {{ old('estado', $ordenCorte->estado) === $estado ? 'selected' : '' }}>
                        {{ ucfirst($estado) }}
                    </option>
                @endforeach
            </select>
        @else
            <x-text-input id="estado" name="estado" type="text" class="w-full mt-1 bg-gray-100" value="pendiente" readonly />
        @endif
        <x-input-error :messages="$errors->get('estado')" class="mt-2" />
    </div>
</div>
