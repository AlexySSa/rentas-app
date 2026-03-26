<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    use HasFactory;

    public const ESTADO_PENDIENTE = 'pendiente';
    public const ESTADO_PAGADO = 'pagado';

    protected $fillable = [
        'alquiler_id',
        'monto',
        'fecha_pago',
        'metodo_pago',
        'estado',
        'referencia',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha_pago' => 'date',
            'monto' => 'decimal:2',
        ];
    }

    public function alquiler(): BelongsTo
    {
        return $this->belongsTo(Alquiler::class);
    }

    public function scopePagados(Builder $query): void
    {
        $query->where('estado', self::ESTADO_PAGADO);
    }
}
