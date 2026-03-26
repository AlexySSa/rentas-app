<?php

use App\Models\Alquiler;
use App\Models\Auto;
use App\Models\Cliente;
use App\Models\Pago;
use Illuminate\Support\Facades\Route;

Route::get('/health', fn () => response()->json([
    'status' => 'ok',
    'timestamp' => now()->toDateTimeString(),
]));

Route::prefix('v1')->group(function (): void {
    Route::get('/stats', fn () => response()->json([
        'autos' => Auto::query()->count(),
        'autos_disponibles' => Auto::query()->where('estado', Auto::ESTADO_DISPONIBLE)->count(),
        'clientes' => Cliente::query()->count(),
        'alquileres_activos' => Alquiler::query()->where('estado', Alquiler::ESTADO_ACTIVO)->count(),
        'pagos_pendientes' => Pago::query()->where('estado', Pago::ESTADO_PENDIENTE)->count(),
    ]));

    Route::get('/autos', fn () => Auto::query()->latest()->get());
    Route::get('/clientes', fn () => Cliente::query()->latest()->get());

    Route::get('/alquileres', fn () => Alquiler::query()
        ->with(['cliente', 'auto', 'pagos'])
        ->latest()
        ->get());

    Route::get('/pagos', fn () => Pago::query()
        ->with(['alquiler.cliente', 'alquiler.auto'])
        ->latest()
        ->get());
});
