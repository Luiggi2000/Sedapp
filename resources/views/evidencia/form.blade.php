<div class="space-y-6">
    {{-- Orden Corte --}}
    <div>
        <label for="orden_corte_id" class="block text-sm font-medium text-gray-700">
            Orden Corte
        </label>
        <select name="orden_corte_id" id="orden_corte_id"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm"
            required>
            <option value="" disabled {{ old('orden_corte_id', $evidencia?->orden_corte_id) == '' ? 'selected' : '' }}>
                -- Seleccione una Orden Corte --
            </option>
            @foreach ($ordenCortes as $ordenCorte)
                <option value="{{ $ordenCorte->id }}"
                    {{ old('orden_corte_id', $evidencia?->orden_corte_id) == $ordenCorte->id ? 'selected' : '' }}>
                    {{ $ordenCorte->id }} - {{ $ordenCorte->fecha }}
                </option>
            @endforeach
        </select>
        @error('orden_corte_id')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Imagen --}}
    <div>
        <label for="imagen" class="block text-sm font-medium text-gray-700">
            Imagen
        </label>
        <input type="file" name="imagen" id="imagen"
            class="mt-1 block w-full border border-gray-300 rounded-md text-sm shadow-sm file:bg-blue-50 file:text-blue-700 file:border-blue-300 file:rounded file:px-3 file:py-1"
        >
        @error('imagen')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Observaciones --}}
    <div>
        <label for="observaciones" class="block text-sm font-medium text-gray-700">
            Observaciones
        </label>
        <input type="text" name="observaciones" id="observaciones"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm"
            value="{{ old('observaciones', $evidencia?->observaciones) }}"
            placeholder="Observaciones"
        >
        @error('observaciones')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>
