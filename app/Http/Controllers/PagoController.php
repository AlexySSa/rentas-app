<?php

namespace App\Http\Controllers;

use App\Models\Alquiler;
use App\Models\Pago;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PagoController extends Controller
{
    public function index(): View
    {
        $pagos = Pago::query()
            ->with(['alquiler.cliente', 'alquiler.auto'])
            ->latest()
            ->paginate(10);

        return view('pagos.index', compact('pagos'));
    }

    public function create(): View
    {
        return view('pagos.create', [
            'alquileres' => $this->availableRentals(),
            'paymentStatuses' => config('car_rental.payment_statuses'),
            'paymentMethods' => config('car_rental.payment_methods'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateRequest($request);
        $alquiler = Alquiler::query()->findOrFail($data['alquiler_id']);

        $this->ensureAmountDoesNotExceedBalance($alquiler, (float) $data['monto'], $data['estado']);

        Pago::query()->create($data);

        return redirect()->route('pagos.index')->with('success', 'Pago registrado correctamente.');
    }

    public function edit(Pago $pago): View
    {
        return view('pagos.edit', [
            'pago' => $pago->load(['alquiler.cliente', 'alquiler.auto']),
            'alquileres' => $this->availableRentals($pago),
            'paymentStatuses' => config('car_rental.payment_statuses'),
            'paymentMethods' => config('car_rental.payment_methods'),
        ]);
    }

    public function update(Request $request, Pago $pago): RedirectResponse
    {
        $data = $this->validateRequest($request);
        $alquiler = Alquiler::query()->findOrFail($data['alquiler_id']);

        $this->ensureAmountDoesNotExceedBalance($alquiler, (float) $data['monto'], $data['estado'], $pago);

        $pago->update($data);

        return redirect()->route('pagos.index')->with('success', 'Pago actualizado correctamente.');
    }

    public function destroy(Pago $pago): RedirectResponse
    {
        $pago->delete();

        return redirect()->route('pagos.index')->with('success', 'Pago eliminado correctamente.');
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'alquiler_id' => ['required', Rule::exists('alquileres', 'id')],
            'monto' => ['required', 'numeric', 'gt:0'],
            'fecha_pago' => ['required', 'date'],
            'metodo_pago' => ['required', Rule::in(array_keys(config('car_rental.payment_methods')))],
            'estado' => ['required', Rule::in(array_keys(config('car_rental.payment_statuses')))],
            'referencia' => ['nullable', 'string', 'max:100'],
            'observaciones' => ['nullable', 'string'],
        ]);
    }

    private function ensureAmountDoesNotExceedBalance(
        Alquiler $alquiler,
        float $monto,
        string $estado,
        ?Pago $pago = null
    ): void {
        if ($estado !== Pago::ESTADO_PAGADO) {
            return;
        }

        // Si estamos editando, excluimos el pago actual para recalcular bien el saldo.
        $pagado = (float) $alquiler->pagos()
            ->pagados()
            ->when($pago, fn ($query) => $query->where('id', '!=', $pago->id))
            ->sum('monto');

        if (($pagado + $monto) > ((float) $alquiler->total_estimado + 0.01)) {
            throw ValidationException::withMessages([
                'monto' => 'El monto excede el saldo pendiente del alquiler.',
            ]);
        }
    }

    private function availableRentals(?Pago $pago = null)
    {
        return Alquiler::query()
            ->with(['cliente', 'auto'])
            ->when($pago, fn ($query) => $query->orWhere('id', $pago->alquiler_id))
            ->latest()
            ->get();
    }
}
