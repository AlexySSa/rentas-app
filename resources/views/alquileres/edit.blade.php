@extends('layouts.app')

@section('title', 'Editar alquiler')

@section('content')
    <div class="card">
        <div class="page-header">
            <div>
                <h2>Editar alquiler</h2>
                <p>Actualiza fechas, cliente, auto asignado y estado del alquiler.</p>
            </div>
            <a href="{{ route('alquileres.index') }}" class="btn-secondary">Volver</a>
        </div>

        <form method="POST" action="{{ route('alquileres.update', $alquiler) }}" class="grid">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="field">
                    <label for="cliente_id">Cliente</label>
                    <select id="cliente_id" name="cliente_id" required>
                        <option value="">Seleccione un cliente</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id }}" @selected((int) old('cliente_id', $alquiler->cliente_id) === $cliente->id)>
                                {{ $cliente->nombre_completo }} | {{ $cliente->documento }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="auto_id">Auto</label>
                    <select id="auto_id" name="auto_id" required>
                        <option value="">Seleccione un auto</option>
                        @foreach ($autos as $auto)
                            <option value="{{ $auto->id }}" @selected((int) old('auto_id', $alquiler->auto_id) === $auto->id)>
                                {{ $auto->marca }} {{ $auto->modelo }} | {{ $auto->placa }} | ${{ number_format((float) $auto->precio_diario, 2) }}/día
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="fecha_inicio">Fecha de inicio</label>
                    <input id="fecha_inicio" type="date" name="fecha_inicio" value="{{ old('fecha_inicio', $alquiler->fecha_inicio->toDateString()) }}" required>
                </div>

                <div class="field">
                    <label for="fecha_fin">Fecha de fin</label>
                    <input id="fecha_fin" type="date" name="fecha_fin" value="{{ old('fecha_fin', $alquiler->fecha_fin->toDateString()) }}" required>
                </div>

                <div class="field">
                    <label for="estado">Estado</label>
                    <select id="estado" name="estado" required>
                        @foreach ($statuses as $key => $label)
                            <option value="{{ $key }}" @selected(old('estado', $alquiler->estado) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field field--full">
                    <label for="observaciones">Observaciones</label>
                    <textarea id="observaciones" name="observaciones">{{ old('observaciones', $alquiler->observaciones) }}</textarea>
                </div>
            </div>

            <div class="helper">Precio diario almacenado actualmente: ${{ number_format((float) $alquiler->precio_diario, 2) }}. Si cambia el auto, el sistema usará el precio diario del nuevo auto.</div>
            <button type="submit" class="btn-primary">Actualizar alquiler</button>
        </form>
    </div>
@endsection
