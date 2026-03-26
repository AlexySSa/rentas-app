<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alquiler extends Model
{
    use HasFactory;

    protected $table = 'alquileres';

    public const ESTADO_ACTIVO = 'activo';
    public const ESTADO_FINALIZADO = 'finalizado';
    public const ESTADO_CANCELADO = 'cancelado';

    protected $fillable = [
        'cliente_id',
        'auto_id',
        'fecha_inicio',
        'fecha_fin',
        'precio_diario',
        'total_estimado',
        'estado',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
            'precio_diario' => 'decimal:2',
            'total_estimado' => 'decimal:2',
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function auto(): BelongsTo
    {
        return $this->belongsTo(Auto::class);
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class);
    }

    public function scopeActivos(Builder $query): void
    {
        $query->where('estado', self::ESTADO_ACTIVO);
    }

    public function getTotalPagadoAttribute(): float
    {
        return (float) $this->pagos()->pagados()->sum('monto');
    }

    public function getSaldoPendienteAttribute(): float
    {
        return max(0, (float) $this->total_estimado - $this->total_pagado);
    }
}
