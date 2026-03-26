<?php

namespace App\Http\Controllers;

use App\Models\Auto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AutoController extends Controller
{
    public function index(): View
    {
        $autos = Auto::query()
            ->orderBy('marca')
            ->orderBy('modelo')
            ->paginate(10);

        return view('autos.index', compact('autos'));
    }

    public function create(): View
    {
        return view('autos.create', [
            'statuses' => config('car_rental.auto_statuses'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateRequest($request);

        if ($data['estado'] === Auto::ESTADO_ALQUILADO) {
            throw ValidationException::withMessages([
                'estado' => 'Un auto solo puede quedar alquilado desde el módulo de alquileres.',
            ]);
        }

        Auto::query()->create($data);

        return redirect()->route('autos.index')->with('success', 'Auto registrado correctamente.');
    }

    public function edit(Auto $auto): View
    {
        return view('autos.edit', [
            'auto' => $auto,
            'statuses' => config('car_rental.auto_statuses'),
        ]);
    }

    public function update(Request $request, Auto $auto): RedirectResponse
    {
        $data = $this->validateRequest($request, $auto);
        $hasActiveRental = $auto->tieneAlquilerActivo();

        if ($hasActiveRental && $data['estado'] !== Auto::ESTADO_ALQUILADO) {
            throw ValidationException::withMessages([
                'estado' => 'El auto tiene un alquiler activo y debe mantenerse como alquilado.',
            ]);
        }

        if (! $hasActiveRental && $data['estado'] === Auto::ESTADO_ALQUILADO) {
            throw ValidationException::withMessages([
                'estado' => 'Use el módulo de alquileres para marcar un auto como alquilado.',
            ]);
        }

        $auto->update($data);

        return redirect()->route('autos.index')->with('success', 'Auto actualizado correctamente.');
    }

    public function destroy(Auto $auto): RedirectResponse
    {
        if ($auto->alquileres()->exists()) {
            return redirect()->route('autos.index')
                ->with('error', 'No se puede eliminar un auto con historial de alquileres.');
        }

        $auto->delete();

        return redirect()->route('autos.index')->with('success', 'Auto eliminado correctamente.');
    }

    private function validateRequest(Request $request, ?Auto $auto = null): array
    {
        return $request->validate([
            'marca' => ['required', 'string', 'max:100'],
            'modelo' => ['required', 'string', 'max:100'],
            'placa' => [
                'required',
                'string',
                'max:20',
                Rule::unique('autos', 'placa')->ignore($auto?->id),
            ],
            'estado' => ['required', Rule::in(array_keys(config('car_rental.auto_statuses')))],
            'precio_diario' => ['required', 'numeric', 'min:0'],
            'descripcion' => ['nullable', 'string'],
        ]);
    }
}
