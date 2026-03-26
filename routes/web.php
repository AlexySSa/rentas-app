<?php

use App\Http\Controllers\AlquilerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AutoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PagoController;
use App\Models\Alquiler;
use App\Models\Auto;
use App\Models\Cliente;
use App\Models\Pago;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        // El panel principal se resuelve aquí porque solo arma métricas simples.
        return view('inicio', [
            'stats' => [
                'autos' => Auto::query()->count(),
                'autos_disponibles' => Auto::query()->where('estado', Auto::ESTADO_DISPONIBLE)->count(),
                'clientes' => Cliente::query()->count(),
                'alquileres_activos' => Alquiler::query()->where('estado', Alquiler::ESTADO_ACTIVO)->count(),
                'pagos_pendientes' => Pago::query()->where('estado', Pago::ESTADO_PENDIENTE)->count(),
            ],
            'recentRentals' => Alquiler::query()
                ->with(['cliente', 'auto'])
                ->latest()
                ->take(5)
                ->get(),
        ]);
    })->name('dashboard');

    Route::middleware('role:admin,empleado')->group(function (): void {
        Route::resource('autos', AutoController::class)->except(['show']);
        Route::resource('clientes', ClienteController::class)->except(['show']);
        Route::resource('alquileres', AlquilerController::class)
            ->parameters(['alquileres' => 'alquiler'])
            ->except(['show']);
        Route::resource('pagos', PagoController::class)->except(['show']);
    });
});
