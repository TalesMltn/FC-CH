<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Código del contrato (ej. CONT-001)
            $table->foreignId('person_id')->constrained('persons')->onDelete('cascade'); // Cliente
            $table->foreignId('category_id')->constrained('categories')->onDelete('restrict'); // Tipo de concreto
            $table->date('date'); // Fecha del contrato
            $table->decimal('quantity', 8, 2); // Cantidad en m³
            $table->decimal('price_per_m3', 10, 2); // Precio por m³
            $table->decimal('total', 12, 2); // Total automático (quantity * price_per_m3)
            $table->enum('status', ['pendiente', 'en_produccion', 'entregado', 'pagado', 'cancelado'])->default('pendiente');
            $table->date('delivery_date')->nullable(); // Fecha de entrega prevista
            $table->text('notes')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};