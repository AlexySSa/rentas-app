@extends('layouts.app')

@section('title', 'Editar pago')

@section('content')
    <div class="card">
        <div class="page-header">
            <div>
                <h2>Editar pago</h2>
                <p>Actualiza el registro de pago asociado a un alquiler.</p>
            </div>
            <a href="{{ route('pagos.index') }}" class="btn-secondary">Volver</a>
        </div>

        <form method="POST" action="{{ route('pagos.update', $pago) }}" class="grid">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="field field--full">
                    <label for="alquiler_id">Alquiler</label>
                    <select id="alquiler_id" name="alquiler_id" required>
                        <option value="">Seleccione un alquiler</option>
                        @foreach ($alquileres as $alquiler)
                            <option value="{{ $alquiler->id }}" @selected((int) old('alquiler_id', $pago->alquiler_id) === $alquiler->id)>
                                #{{ $alquiler->id }} | {{ $alquiler->cliente->nombre_completo }} | {{ $alquiler->auto->placa }} | Total: ${{ number_format((float) $alquiler->total_estimado, 2) }} | Saldo: ${{ number_format((float) $alquiler->saldo_pendiente, 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="monto">Monto</label>
                    <input id="monto" type="number" step="0.01" min="0.01" name="monto" value="{{ old('monto', $pago->monto) }}" required>
                </div>

                <div class="field">
                    <label for="fecha_pago">Fecha de pago</label>
                    <input id="fecha_pago" type="date" name="fecha_pago" value="{{ old('fecha_pago', $pago->fecha_pago->toDateString()) }}" required>
                </div>

                <div class="field">
                    <label for="metodo_pago">Método de pago</label>
                    <select id="metodo_pago" name="metodo_pago" required>
                        @foreach ($paymentMethods as $key => $label)
                            <option value="{{ $key }}" @selected(old('metodo_pago', $pago->metodo_pago) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="estado">Estado</label>
                    <select id="estado" name="estado" required>
                        @foreach ($paymentStatuses as $key => $label)
                            <option value="{{ $key }}" @selected(old('estado', $pago->estado) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="referencia">Referencia</label>
                    <input id="referencia" type="text" name="referencia" value="{{ old('referencia', $pago->referencia) }}">
                </div>

                <div class="field field--full">
                    <label for="observaciones">Observaciones</label>
                    <textarea id="observaciones" name="observaciones">{{ old('observaciones', $pago->observaciones) }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn-primary">Actualizar pago</button>
        </form>
    </div>
@endsection
