<?php

use App\Models\Alquiler;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alquileres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();
            $table->foreignId('auto_id')->constrained('autos')->restrictOnDelete();
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->decimal('precio_diario', 10, 2);
            $table->decimal('total_estimado', 10, 2);
            $table->string('estado')->default(Alquiler::ESTADO_ACTIVO)->index();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index(['auto_id', 'estado']);
            $table->index(['cliente_id', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alquileres');
    }
};
