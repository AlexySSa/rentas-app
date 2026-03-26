@extends('layouts.app')

@section('title', 'Editar cliente')

@section('content')
    <div class="card">
        <div class="page-header">
            <div>
                <h2>Editar cliente</h2>
                <p>Actualiza la información del cliente.</p>
            </div>
            <a href="{{ route('clientes.index') }}" class="btn-secondary">Volver</a>
        </div>

        <form method="POST" action="{{ route('clientes.update', $cliente) }}" class="grid">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="field">
                    <label for="nombre">Nombre</label>
                    <input id="nombre" type="text" name="nombre" value="{{ old('nombre', $cliente->nombre) }}" required>
                </div>

                <div class="field">
                    <label for="apellido">Apellido</label>
                    <input id="apellido" type="text" name="apellido" value="{{ old('apellido', $cliente->apellido) }}" required>
                </div>

                <div class="field">
                    <label for="documento">Documento</label>
                    <input id="documento" type="text" name="documento" value="{{ old('documento', $cliente->documento) }}" required>
                </div>

                <div class="field">
                    <label for="telefono">Teléfono</label>
                    <input id="telefono" type="text" name="telefono" value="{{ old('telefono', $cliente->telefono) }}" required>
                </div>

                <div class="field">
                    <label for="email">Correo electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $cliente->email) }}">
                </div>

                <div class="field">
                    <label for="licencia_conducir">Licencia de conducir</label>
                    <input id="licencia_conducir" type="text" name="licencia_conducir" value="{{ old('licencia_conducir', $cliente->licencia_conducir) }}" required>
                </div>

                <div class="field field--full">
                    <label for="direccion">Dirección</label>
                    <textarea id="direccion" name="direccion">{{ old('direccion', $cliente->direccion) }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn-primary">Actualizar cliente</button>
        </form>
    </div>
@endsection
