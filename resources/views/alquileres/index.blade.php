@extends('layouts.app')

@section('title', 'Alquileres')

@section('content')
    <div class="card">
        <div class="page-header">
            <div>
                <h2>Alquileres</h2>
                <p>Relación cliente-auto con control de estado y total estimado.</p>
            </div>
            <a href="{{ route('alquileres.create') }}" class="btn-primary">Nuevo alquiler</a>
        </div>

        @if ($alquileres->isEmpty())
            <div class="empty-state">Todavía no hay alquileres registrados.</div>
        @else
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Auto</th>
                            <th>Periodo</th>
                            <th>Total</th>
                            <th>Pagado</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($alquileres as $alquiler)
                            <tr>
                                <td>{{ $alquiler->cliente->nombre_completo }}</td>
                                <td>{{ $alquiler->auto->marca }} {{ $alquiler->auto->modelo }}<br>{{ $alquiler->auto->placa }}</td>
                                <td>{{ $alquiler->fecha_inicio->format('d/m/Y') }} - {{ $alquiler->fecha_fin->format('d/m/Y') }}</td>
                                <td>${{ number_format((float) $alquiler->total_estimado, 2) }}</td>
                                <td>${{ number_format((float) ($alquiler->total_pagado_sum ?? 0), 2) }}</td>
                                <td>
                                    <span class="badge {{ $alquiler->estado }}">
                                        {{ config('car_rental.alquiler_statuses.' . $alquiler->estado, ucfirst($alquiler->estado)) }}
                                    </span>
                                </td>
                                <td class="table-actions">
                                    <a href="{{ route('alquileres.edit', $alquiler) }}" class="btn-secondary">Editar</a>
                                    <form method="POST" action="{{ route('alquileres.destroy', $alquiler) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger" onclick="return confirm('¿Eliminar este alquiler?')">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                <span>Página {{ $alquileres->currentPage() }} de {{ $alquileres->lastPage() }}</span>
                <div class="table-actions">
                    @if ($alquileres->previousPageUrl())
                        <a href="{{ $alquileres->previousPageUrl() }}" class="btn-secondary">Anterior</a>
                    @endif
                    @if ($alquileres->nextPageUrl())
                        <a href="{{ $alquileres->nextPageUrl() }}" class="btn-secondary">Siguiente</a>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection
