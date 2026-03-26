@extends('layouts.app')

@section('title', 'Registrar cliente')

@section('content')
    <div class="card">
        <div class="page-header">
            <div>
                <h2>Registrar cliente</h2>
                <p>Alta de un nuevo cliente para alquileres.</p>
            </div>
            <a href="{{ route('clientes.index') }}" class="btn-secondary">Volver</a>
        </div>

        <form method="POST" action="{{ route('clientes.store') }}" class="grid">
            @csrf

            <div class="form-grid">
                <div class="field">
                    <label for="nombre">Nombre</label>
                    <input id="nombre" type="text" name="nombre" value="{{ old('nombre') }}" required>
                </div>

                <div class="field">
                    <label for="apellido">Apellido</label>
                    <input id="apellido" type="text" name="apellido" value="{{ old('apellido') }}" required>
                </div>

                <div class="field">
                    <label for="documento">Documento</label>
                    <input id="documento" type="text" name="documento" value="{{ old('documento') }}" required>
                </div>

                <div class="field">
                    <label for="telefono">Teléfono</label>
                    <input id="telefono" type="text" name="telefono" value="{{ old('telefono') }}" required>
                </div>

                <div class="field">
                    <label for="email">Correo electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}">
                </div>

                <div class="field">
                    <label for="licencia_conducir">Licencia de conducir</label>
                    <input id="licencia_conducir" type="text" name="licencia_conducir" value="{{ old('licencia_conducir') }}" required>
                </div>

                <div class="field field--full">
                    <label for="direccion">Dirección</label>
                    <textarea id="direccion" name="direccion">{{ old('direccion') }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn-primary">Guardar cliente</button>
        </form>
    </div>
@endsection
