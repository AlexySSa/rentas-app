@extends('layouts.app')

@section('title', 'Editar auto')

@section('content')
    <div class="card">
        <div class="page-header">
            <div>
                <h2>Editar auto</h2>
                <p>Actualiza la información del vehículo.</p>
            </div>
            <a href="{{ route('autos.index') }}" class="btn-secondary">Volver</a>
        </div>

        <form method="POST" action="{{ route('autos.update', $auto) }}" class="grid">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="field">
                    <label for="marca">Marca</label>
                    <input id="marca" type="text" name="marca" value="{{ old('marca', $auto->marca) }}" required>
                </div>

                <div class="field">
                    <label for="modelo">Modelo</label>
                    <input id="modelo" type="text" name="modelo" value="{{ old('modelo', $auto->modelo) }}" required>
                </div>

                <div class="field">
                    <label for="placa">Placa</label>
                    <input id="placa" type="text" name="placa" value="{{ old('placa', $auto->placa) }}" required>
                </div>

                <div class="field">
                    <label for="estado">Estado</label>
                    <select id="estado" name="estado" required>
                        @foreach ($statuses as $key => $label)
                            <option value="{{ $key }}" @selected(old('estado', $auto->estado) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="precio_diario">Precio diario</label>
                    <input id="precio_diario" type="number" step="0.01" min="0" name="precio_diario" value="{{ old('precio_diario', $auto->precio_diario) }}" required>
                </div>

                <div class="field field--full">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion">{{ old('descripcion', $auto->descripcion) }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn-primary">Actualizar auto</button>
        </form>
    </div>
@endsection
