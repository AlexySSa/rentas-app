@extends('layouts.app')

@section('title', 'Crear alquiler')

@section('content')
    <div class="card">
        <div class="page-header">
            <div>
                <h2>Crear alquiler</h2>
                <p>Asigna un auto disponible a un cliente y calcula el total estimado.</p>
            </div>
            <a href="{{ route('alquileres.index') }}" class="btn-secondary">Volver</a>
        </div>

        <form method="POST" action="{{ route('alquileres.store') }}" class="grid">
            @csrf

            <div class="form-grid">
                <div class="field">
                    <label for="cliente_id">Cliente</label>
                    <select id="cliente_id" name="cliente_id" required>
                        <option value="">Seleccione un cliente</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id }}" @selected((int) old('cliente_id') === $cliente->id)>
                                {{ $cliente->nombre_completo }} | {{ $cliente->documento }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="auto_id">Auto disponible</label>
                    <select id="auto_id" name="auto_id" required>
                        <option value="">Seleccione un auto</option>
                        @foreach ($autos as $auto)
                            <option value="{{ $auto->id }}" @selected((int) old('auto_id') === $auto->id)>
                                {{ $auto->marca }} {{ $auto->modelo }} | {{ $auto->placa }} | ${{ number_format((float) $auto->precio_diario, 2) }}/día
                            </option>
                        @endforeach
                    </select>
                    <span class="helper">El precio diario del alquiler se toma automáticamente del auto seleccionado.</span>
                </div>

                <div class="field">
                    <label for="fecha_inicio">Fecha de inicio</label>
                    <input id="fecha_inicio" type="date" name="fecha_inicio" value="{{ old('fecha_inicio', now()->toDateString()) }}" required>
                </div>

                <div class="field">
                    <label for="fecha_fin">Fecha de fin</label>
                    <input id="fecha_fin" type="date" name="fecha_fin" value="{{ old('fecha_fin', now()->addDay()->toDateString()) }}" required>
                </div>

                <div class="field">
                    <label for="estado">Estado</label>
                    <select id="estado" name="estado" required>
                        @foreach ($statuses as $key => $label)
                            <option value="{{ $key }}" @selected(old('estado', 'activo') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field field--full">
                    <label for="observaciones">Observaciones</label>
                    <textarea id="observaciones" name="observaciones">{{ old('observaciones') }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn-primary">Guardar alquiler</button>
        </form>
    </div>
@endsection
