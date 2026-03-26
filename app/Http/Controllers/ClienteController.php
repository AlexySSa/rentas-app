<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClienteController extends Controller
{
    public function index(): View
    {
        $clientes = Cliente::query()
            ->orderBy('nombre')
            ->orderBy('apellido')
            ->paginate(10);

        return view('clientes.index', compact('clientes'));
    }

    public function create(): View
    {
        return view('clientes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Cliente::query()->create($this->validateRequest($request));

        return redirect()->route('clientes.index')->with('success', 'Cliente registrado correctamente.');
    }

    public function edit(Cliente $cliente): View
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente): RedirectResponse
    {
        $cliente->update($this->validateRequest($request, $cliente));

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente): RedirectResponse
    {
        if ($cliente->alquileres()->exists()) {
            return redirect()->route('clientes.index')
                ->with('error', 'No se puede eliminar un cliente con historial de alquileres.');
        }

        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente.');
    }

    private function validateRequest(Request $request, ?Cliente $cliente = null): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'documento' => [
                'required',
                'string',
                'max:30',
                Rule::unique('clientes', 'documento')->ignore($cliente?->id),
            ],
            'telefono' => ['required', 'string', 'max:30'],
            'email' => [
                'nullable',
                'email',
                'max:150',
                Rule::unique('clientes', 'email')->ignore($cliente?->id),
            ],
            'direccion' => ['nullable', 'string'],
            'licencia_conducir' => [
                'required',
                'string',
                'max:50',
                Rule::unique('clientes', 'licencia_conducir')->ignore($cliente?->id),
            ],
        ]);
    }
}
