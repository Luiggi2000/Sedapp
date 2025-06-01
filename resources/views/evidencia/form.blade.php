<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20">
            <label for="orden_corte_id" class="form-label">{{ __('Orden Corte') }}</label>
            <select id="orden_corte_id" name="orden_corte_id"
                class="form-control @error('orden_corte_id') is-invalid @enderror" required>
                <option value="" disabled
                    {{ old('orden_corte_id', $evidencia?->orden_corte_id ?? '') == '' ? 'selected' : '' }}>
                    -- Seleccione una Orden Corte --
                </option>
                @foreach ($ordenCortes as $ordenCorte)
                    <option value="{{ $ordenCorte->id }}"
                        {{ old('orden_corte_id', $evidencia?->orden_corte_id ?? '') == $ordenCorte->id ? 'selected' : '' }}>
                        {{ $ordenCorte->id }} - {{ $ordenCorte->fecha }} {{-- Puedes mostrar lo que ayude a identificar --}}
                    </option>
                @endforeach
            </select>
            {!! $errors->first(
                'orden_corte_id',
                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
            ) !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="imagen" class="form-label">{{ __('Imagen') }}</label>
            <input type="file" name="imagen" class="form-control @error('imagen') is-invalid @enderror"
                value="{{ old('imagen', $evidencia?->imagen) }}" id="imagen" placeholder="Imagen">
            {!! $errors->first('imagen', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            <div class="form-group mb-2 mb20">
            <label for="observaciones" class="form-label">{{ __('Observaciones') }}</label>
            <input type="text" name="observaciones" class="form-control @error('observaciones') is-invalid @enderror"
                value="{{ old('observaciones', $ordenCorte?->observaciones) }}" id="observaciones"
                placeholder="Observaciones">
            {!! $errors->first(
                'observaciones',
                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
            ) !!}
        </div>
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>
