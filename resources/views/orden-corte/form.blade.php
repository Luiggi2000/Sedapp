<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="mb-4">
            <label for="zona" class="form-label">{{ __('Zona') }}</label>
            <select id="zona_id" name="zona_id" class="block mt-1 w-full border-gray-300 rounded-md" required>
                <option value="" disabled {{ empty(old('zona_id', $ordenCorte->zona_id ?? '')) ? 'selected' : '' }}>
                    -- Seleccione una zona --
                </option>
                @foreach ($zonas as $zona)
                    <option value="{{ $zona->id }}"
                        {{ old('zona_id', $ordenCorte->zona_id ?? '') == $zona->id ? 'selected' : '' }}>
                        {{ $zona->nombre }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('zona_id')" class="mt-2" />
        </div>

<div class="mt-4">
    <x-input-label for="direccion" :value="__('Dirección')" />
    <x-text-input id="direccion" class="block mt-1 w-full" type="text" name="direccion" :value="old('direccion', $ordenCorte->direccion ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('direccion')" class="mt-2" />
</div>


<div class="mb-4">
    <label for="tecnico_id" class="form-label">{{ __('Técnico asignado') }}</label>
    <select id="tecnico_id" name="tecnico_id" class="block mt-1 w-full border-gray-300 rounded-md">
        <option value="" disabled {{ empty(old('tecnico_id', $ordenCorte->tecnico_id ?? '')) ? 'selected' : '' }}>
            -- Seleccione un técnico --
        </option>
        @foreach ($usuarios as $usuario)
            <option value="{{ $usuario->id }}"
                {{ old('tecnico_id', $ordenCorte->tecnico_id ?? '') == $usuario->id ? 'selected' : '' }}>
                {{ $usuario->name }}
            </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('tecnico_id')" class="mt-2" />
</div>
<div class="mb-4">
    <label for="afectado_id" class="form-label">{{ __('Usuario afectado') }}</label>
    <select id="afectado_id" name="afectado_id" class="block mt-1 w-full border-gray-300 rounded-md" required>
        <option value="" disabled {{ empty(old('afectado_id', $ordenCorte->afectado_id ?? '')) ? 'selected' : '' }}>
            -- Seleccione un usuario --
        </option>
        @foreach ($clientes as $usuario)
            <option value="{{ $usuario->id }}"
                {{ old('afectado_id', $ordenCorte->afectado_id ?? '') == $usuario->id ? 'selected' : '' }}>
                {{ $usuario->name }}
            </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('afectado_id')" class="mt-2" />
</div>

        <div class="form-group mb-2 mb20">
            <label for="fecha" class="form-label">{{ __('Fecha') }}</label>
            <input type="date" name="fecha" class="form-control @error('fecha') is-invalid @enderror"
                value="{{ old('fecha', $ordenCorte?->fecha) }}" id="fecha" placeholder="Fecha" required>
            {!! $errors->first('fecha', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="estado" class="form-label">{{ __('Estado') }}</label>

            @if (isset($ordenCorte)) {{-- En edición --}}
                <select name="estado" id="estado" class="form-control @error('estado') is-invalid @enderror" required>
                    @php
                        $estados = ['pendiente','en proceso', 'completado', 'no realizado'];
                    @endphp
                    @foreach ($estados as $estado)
                        <option value="{{ $estado }}"
                            {{ old('estado', $ordenCorte?->estado) === $estado ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $estado)) }}
                        </option>
                    @endforeach
                </select>
            @else {{-- En creación --}}
                <input type="text" name="estado" id="estado" class="form-control" value="pendiente" readonly>
            @endif

            {!! $errors->first('estado', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>

    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
    </div>
</div>

