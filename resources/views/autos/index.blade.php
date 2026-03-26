@extends('layouts.app')

@section('title', 'Autos')

@section('content')
    <div class="card">
        <div class="page-header">
            <div>
                <h2>Autos</h2>
                <p>CRUD completo para la flota de vehículos.</p>
            </div>
            <a href="{{ route('autos.create') }}" class="btn-primary">Nuevo auto</a>
        </div>

        @if ($autos->isEmpty())
            <div class="empty-state">Todavía no hay autos registrados.</div>
        @else
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Placa</th>
                            <th>Estado</th>
                            <th>Precio diario</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($autos as $auto)
                            <tr>
                                <td>{{ $auto->marca }}</td>
                                <td>{{ $auto->modelo }}</td>
                                <td>{{ $auto->placa }}</td>
                                <td>
                                    <span class="badge {{ $auto->estado }}">
                                        {{ config('car_rental.auto_statuses.' . $auto->estado, ucfirst($auto->estado)) }}
                                    </span>
                                </td>
                                <td>${{ number_format((float) $auto->precio_diario, 2) }}</td>
                                <td class="table-actions">
                                    <a href="{{ route('autos.edit', $auto) }}" class="btn-secondary">Editar</a>
                                    <form method="POST" action="{{ route('autos.destroy', $auto) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger" onclick="return confirm('¿Eliminar este auto?')">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                <span>Página {{ $autos->currentPage() }} de {{ $autos->lastPage() }}</span>
                <div class="table-actions">
                    @if ($autos->previousPageUrl())
                        <a href="{{ $autos->previousPageUrl() }}" class="btn-secondary">Anterior</a>
                    @endif
                    @if ($autos->nextPageUrl())
                        <a href="{{ $autos->nextPageUrl() }}" class="btn-secondary">Siguiente</a>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection
