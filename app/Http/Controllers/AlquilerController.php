<?php

namespace App\Http\Controllers;

use App\Models\Alquiler;
use App\Models\Auto;
use App\Models\Cliente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AlquilerController extends Controller
{
    public function index(): View
    {
        $alquileres = Alquiler::query()
            ->with(['cliente', 'auto'])
            // Guardamos el total pagado en la misma consulta para evitar cálculos repetidos en la vista.
            ->withSum(['pagos as total_pagado_sum' => fn ($query) => $query->pagados()], 'monto')
            ->latest()
            ->paginate(10);

        return view('alquileres.index', compact('alquileres'));
    }

    public function create(): View
    {
        return view('alquileres.create', [
            'clientes' => Cliente::query()->orderBy('nombre')->orderBy('apellido')->get(),
            'autos' => $this->availableAutos(),
            'statuses' => config('car_rental.alquiler_statuses'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateRequest($request);
        $auto = Auto::query()->findOrFail($data['auto_id']);

        // Antes de crear el alquiler confirmamos que el auto siga libre.
        $this->ensureAutoCanBeAssigned($auto);

        $precioDiario = (float) $auto->precio_diario;
        $totalEstimado = $this->calculateTotal($data['fecha_inicio'], $data['fecha_fin'], $precioDiario);

        DB::transaction(function () use ($data, $auto, $precioDiario, $totalEstimado): void {
            Alquiler::query()->create([
                'cliente_id' => $data['cliente_id'],
                'auto_id' => $auto->id,
                'fecha_inicio' => $data['fecha_inicio'],
                'fecha_fin' => $data['fecha_fin'],
                'precio_diario' => $precioDiario,
                'total_estimado' => $totalEstimado,
                'estado' => $data['estado'],
                'observaciones' => $data['observaciones'] ?? null,
            ]);

            $this->syncAutoStatus($auto->fresh(), $data['estado']);
        });

        return redirect()->route('alquileres.index')->with('success', 'Alquiler creado correctamente.');
    }

    public function edit(Alquiler $alquiler): View
    {
        return view('alquileres.edit', [
            'alquiler' => $alquiler->load(['cliente', 'auto']),
            'clientes' => Cliente::query()->orderBy('nombre')->orderBy('apellido')->get(),
            'autos' => $this->availableAutos($alquiler),
            'statuses' => config('car_rental.alquiler_statuses'),
        ]);
    }

    public function update(Request $request, Alquiler $alquiler): RedirectResponse
    {
        $data = $this->validateRequest($request);
        $autoAnterior = $alquiler->auto()->firstOrFail();
        $autoNuevo = Auto::query()->findOrFail($data['auto_id']);

        $this->ensureAutoCanBeAssigned($autoNuevo, $alquiler, $data['estado']);

        $precioDiario = $autoNuevo->is($autoAnterior)
            ? (float) $alquiler->precio_diario
            : (float) $autoNuevo->precio_diario;

        $totalEstimado = $this->calculateTotal($data['fecha_inicio'], $data['fecha_fin'], $precioDiario);

        DB::transaction(function () use ($alquiler, $data, $autoAnterior, $autoNuevo, $precioDiario, $totalEstimado): void {
            $alquiler->update([
                'cliente_id' => $data['cliente_id'],
                'auto_id' => $autoNuevo->id,
                'fecha_inicio' => $data['fecha_inicio'],
                'fecha_fin' => $data['fecha_fin'],
                'precio_diario' => $precioDiario,
                'total_estimado' => $totalEstimado,
                'estado' => $data['estado'],
                'observaciones' => $data['observaciones'] ?? null,
            ]);

            if (! $autoAnterior->is($autoNuevo)) {
                $this->syncAutoStatus($autoAnterior->fresh(), Alquiler::ESTADO_FINALIZADO);
            }

            $this->syncAutoStatus($autoNuevo->fresh(), $data['estado']);
        });

        return redirect()->route('alquileres.index')->with('success', 'Alquiler actualizado correctamente.');
    }

    public function destroy(Alquiler $alquiler): RedirectResponse
    {
        if ($alquiler->pagos()->exists()) {
            return redirect()->route('alquileres.index')
                ->with('error', 'No se puede eliminar un alquiler con pagos registrados.');
        }

        $auto = $alquiler->auto()->firstOrFail();

        DB::transaction(function () use ($alquiler, $auto): void {
            $alquiler->delete();
            $this->syncAutoStatus($auto->fresh(), Alquiler::ESTADO_FINALIZADO);
        });

        return redirect()->route('alquileres.index')->with('success', 'Alquiler eliminado correctamente.');
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'cliente_id' => ['required', Rule::exists('clientes', 'id')],
            'auto_id' => ['required', Rule::exists('autos', 'id')],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'estado' => ['required', Rule::in(array_keys(config('car_rental.alquiler_statuses')))],
            'observaciones' => ['nullable', 'string'],
        ]);
    }

    private function availableAutos(?Alquiler $alquiler = null)
    {
        return Auto::query()
            ->where(function ($query) use ($alquiler): void {
                $query->disponibles();

                if ($alquiler) {
                    $query->orWhere('id', $alquiler->auto_id);
                }
            })
            ->orderBy('marca')
            ->orderBy('modelo')
            ->get();
    }

    private function ensureAutoCanBeAssigned(Auto $auto, ?Alquiler $alquiler = null, string $estado = Alquiler::ESTADO_ACTIVO): void
    {
        $sameAuto = $alquiler && $alquiler->auto_id === $auto->id;

        if ($estado !== Alquiler::ESTADO_ACTIVO) {
            return;
        }

        if ($sameAuto) {
            return;
        }

        if ($auto->estado !== Auto::ESTADO_DISPONIBLE || $auto->tieneAlquilerActivo()) {
            throw ValidationException::withMessages([
                'auto_id' => 'El auto seleccionado no está disponible para alquiler.',
            ]);
        }
    }

    private function calculateTotal(string $fechaInicio, string $fechaFin, float $precioDiario): float
    {
        $inicio = Carbon::parse($fechaInicio);
        $fin = Carbon::parse($fechaFin);
        // Contamos al menos un día aunque inicio y fin sean la misma fecha.
        $dias = max(1, $inicio->diffInDays($fin) + 1);

        return round($dias * $precioDiario, 2);
    }

    private function syncAutoStatus(Auto $auto, string $estadoAlquiler): void
    {
        if ($estadoAlquiler === Alquiler::ESTADO_ACTIVO) {
            $auto->update(['estado' => Auto::ESTADO_ALQUILADO]);

            return;
        }

        // Solo liberamos el auto si ya no quedó enlazado a otro alquiler activo.
        if (! $auto->tieneAlquilerActivo() && $auto->estado === Auto::ESTADO_ALQUILADO) {
            $auto->update(['estado' => Auto::ESTADO_DISPONIBLE]);
        }
    }
}
