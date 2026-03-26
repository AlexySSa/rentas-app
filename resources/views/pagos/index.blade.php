@extends('layouts.app')

@section('title', 'Pagos')

@section('content')
    <div class="card">
        <div class="page-header">
            <div>
                <h2>Pagos</h2>
                <p>Registro de pagos con estado pagado o pendiente.</p>
            </div>
            <a href="{{ route('pagos.create') }}" class="btn-primary">Registrar pago</a>
        </div>

        @if ($pagos->isEmpty())
            <div class="empty-state">Todavía no hay pagos registrados.</div>
        @else
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Alquiler</th>
                            <th>Cliente</th>
                            <th>Monto</th>
                            <th>Fecha</th>
                            <th>Método</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pagos as $pago)
                            <tr>
                                <td>#{{ $pago->alquiler->id }} | {{ $pago->alquiler->auto->placa }}</td>
                                <td>{{ $pago->alquiler->cliente->nombre_completo }}</td>
                                <td>${{ number_format((float) $pago->monto, 2) }}</td>
                                <td>{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                                <td>{{ config('car_rental.payment_methods.' . $pago->metodo_pago, ucfirst($pago->metodo_pago)) }}</td>
                                <td>
                                    <span class="badge {{ $pago->estado }}">
                                        {{ config('car_rental.payment_statuses.' . $pago->estado, ucfirst($pago->estado)) }}
                                    </span>
                                </td>
                                <td class="table-actions">
                                    <a href="{{ route('pagos.edit', $pago) }}" class="btn-secondary">Editar</a>
                                    <form method="POST" action="{{ route('pagos.destroy', $pago) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger" onclick="return confirm('¿Eliminar este pago?')">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                <span>Página {{ $pagos->currentPage() }} de {{ $pagos->lastPage() }}</span>
                <div class="table-actions">
                    @if ($pagos->previousPageUrl())
                        <a href="{{ $pagos->previousPageUrl() }}" class="btn-secondary">Anterior</a>
                    @endif
                    @if ($pagos->nextPageUrl())
                        <a href="{{ $pagos->nextPageUrl() }}" class="btn-secondary">Siguiente</a>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection
