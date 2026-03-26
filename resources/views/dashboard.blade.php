@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
    <div class="page-header">
        <div>
            <h2>Inicio</h2>
            <p>Resumen operativo del sistema de alquiler de autos.</p>
        </div>
    </div>

    <section class="grid stats-grid" style="margin-bottom: 24px;">
        <article class="stats-card">
            <small>Total de autos</small>
            <strong>{{ $stats['autos'] }}</strong>
        </article>
        <article class="stats-card">
            <small>Autos disponibles</small>
            <strong>{{ $stats['autos_disponibles'] }}</strong>
        </article>
        <article class="stats-card">
            <small>Clientes</small>
            <strong>{{ $stats['clientes'] }}</strong>
        </article>
        <article class="stats-card">
            <small>Alquileres activos</small>
            <strong>{{ $stats['alquileres_activos'] }}</strong>
        </article>
        <article class="stats-card">
            <small>Pagos pendientes</small>
            <strong>{{ $stats['pagos_pendientes'] }}</strong>
        </article>
    </section>

    <section class="grid">
        <div class="card panel-cover">
            <div class="page-header">
                <div>
                    <h2>Alquileres recientes</h2>
                    <p>Últimos movimientos registrados en el sistema.</p>
                </div>
                <a href="{{ route('alquileres.index') }}" class="btn-primary">Ver alquileres</a>
            </div>

            @if ($recentRentals->isEmpty())
                <div class="empty-state">No hay alquileres registrados todavía.</div>
            @else
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Auto</th>
                                <th>Periodo</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentRentals as $alquiler)
                                <tr>
                                    <td>{{ $alquiler->cliente->nombre_completo }}</td>
                                    <td>{{ $alquiler->auto->marca }} {{ $alquiler->auto->modelo }}<br>{{ $alquiler->auto->placa }}</td>
                                    <td>{{ $alquiler->fecha_inicio->format('d/m/Y') }} - {{ $alquiler->fecha_fin->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge {{ $alquiler->estado }}">
                                            {{ config('car_rental.alquiler_statuses.' . $alquiler->estado, ucfirst($alquiler->estado)) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="card">
            <div class="page-header">
                <div>
                    <h2>Acciones rápidas</h2>
                    <p>Accede a los módulos principales.</p>
                </div>
            </div>

            <div class="grid">
                <a href="{{ route('autos.create') }}" class="btn-primary" style="text-align: center;">Registrar auto</a>
                <a href="{{ route('clientes.create') }}" class="btn-secondary" style="text-align: center;">Registrar cliente</a>
                <a href="{{ route('alquileres.create') }}" class="btn-secondary" style="text-align: center;">Crear alquiler</a>
                <a href="{{ route('pagos.create') }}" class="btn-secondary" style="text-align: center;">Registrar pago</a>
            </div>
        </div>
    </section>
@endsection
