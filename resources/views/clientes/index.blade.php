@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
    <div class="card">
        <div class="page-header">
            <div>
                <h2>Clientes</h2>
                <p>Gestión integral de clientes del sistema.</p>
            </div>
            <a href="{{ route('clientes.create') }}" class="btn-primary">Nuevo cliente</a>
        </div>

        @if ($clientes->isEmpty())
            <div class="empty-state">Todavía no hay clientes registrados.</div>
        @else
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Documento</th>
                            <th>Licencia</th>
                            <th>Contacto</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clientes as $cliente)
                            <tr>
                                <td>{{ $cliente->nombre_completo }}</td>
                                <td>{{ $cliente->documento }}</td>
                                <td>{{ $cliente->licencia_conducir }}</td>
                                <td>{{ $cliente->telefono }}<br>{{ $cliente->email ?: 'Sin email' }}</td>
                                <td class="table-actions">
                                    <a href="{{ route('clientes.edit', $cliente) }}" class="btn-secondary">Editar</a>
                                    <form method="POST" action="{{ route('clientes.destroy', $cliente) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger" onclick="return confirm('¿Eliminar este cliente?')">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                <span>Página {{ $clientes->currentPage() }} de {{ $clientes->lastPage() }}</span>
                <div class="table-actions">
                    @if ($clientes->previousPageUrl())
                        <a href="{{ $clientes->previousPageUrl() }}" class="btn-secondary">Anterior</a>
                    @endif
                    @if ($clientes->nextPageUrl())
                        <a href="{{ $clientes->nextPageUrl() }}" class="btn-secondary">Siguiente</a>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection
