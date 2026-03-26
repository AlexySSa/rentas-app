@extends('layouts.app')

@section('title', 'Registrar auto')

@section('content')
    <div class="card">
        <div class="page-header">
            <div>
                <h2>Registrar auto</h2>
                <p>Alta de un nuevo vehículo en la flota.</p>
            </div>
            <a href="{{ route('autos.index') }}" class="btn-secondary">Volver</a>
        </div>

        <form method="POST" action="{{ route('autos.store') }}" class="grid">
            @csrf

            <div class="form-grid">
                <div class="field">
                    <label for="marca">Marca</label>
                    <input id="marca" type="text" name="marca" value="{{ old('marca') }}" required>
                </div>

                <div class="field">
                    <label for="modelo">Modelo</label>
                    <input id="modelo" type="text" name="modelo" value="{{ old('modelo') }}" required>
                </div>

                <div class="field">
                    <label for="placa">Placa</label>
                    <input id="placa" type="text" name="placa" value="{{ old('placa') }}" required>
                </div>

                <div class="field">
                    <label for="estado">Estado</label>
                    <select id="estado" name="estado" required>
                        @foreach ($statuses as $key => $label)
                            <option value="{{ $key }}" @selected(old('estado', 'disponible') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="precio_diario">Precio diario</label>
                    <input id="precio_diario" type="number" step="0.01" min="0" name="precio_diario" value="{{ old('precio_diario') }}" required>
                </div>

                <div class="field field--full">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion">{{ old('descripcion') }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn-primary">Guardar auto</button>
        </form>
    </div>
@endsection
