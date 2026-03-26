<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Auto extends Model
{
    use HasFactory;

    public const ESTADO_DISPONIBLE = 'disponible';
    public const ESTADO_ALQUILADO = 'alquilado';
    public const ESTADO_MANTENIMIENTO = 'mantenimiento';

    protected $fillable = [
        'marca',
        'modelo',
        'placa',
        'estado',
        'precio_diario',
        'descripcion',
    ];

    protected function casts(): array
    {
        return [
            'precio_diario' => 'decimal:2',
        ];
    }

    public function alquileres(): HasMany
    {
        return $this->hasMany(Alquiler::class);
    }

    public function scopeDisponibles(Builder $query): void
    {
        // Este scope se usa en formularios donde solo deben aparecer autos listos para asignar.
        $query->where('estado', self::ESTADO_DISPONIBLE);
    }

    public function tieneAlquilerActivo(): bool
    {
        return $this->alquileres()->activos()->exists();
    }
}
