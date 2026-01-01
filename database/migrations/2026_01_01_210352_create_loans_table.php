<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('persons')->onDelete('cascade'); // Trabajador
            $table->date('loan_date'); // Fecha del préstamo
            $table->decimal('amount', 10, 2); // Monto total del préstamo
            $table->unsignedTinyInteger('installments'); // Número de cuotas
            $table->decimal('installment_amount', 10, 2); // Valor por cuota (calculado)
            $table->decimal('paid_amount', 10, 2)->default(0); // Monto pagado hasta ahora
            $table->decimal('pending_amount', 10, 2); // Saldo pendiente (calculado)
            $table->enum('status', ['activo', 'pagado', 'cancelado'])->default('activo');
            $table->text('notes')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};