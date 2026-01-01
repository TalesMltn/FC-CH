<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('persons')->onDelete('cascade'); // Trabajador
            $table->date('payment_date'); // Fecha del pago
            $table->year('year');
            $table->unsignedTinyInteger('month'); // 1 a 12
            $table->decimal('base_salary', 10, 2); // Salario base
            $table->decimal('bonus', 10, 2)->default(0); // Bonos
            $table->decimal('discounts', 10, 2)->default(0); // Descuentos
            $table->decimal('loan_deduction', 10, 2)->default(0); // Deducción por préstamo
            $table->decimal('total_paid', 10, 2); // Total pagado (calculado)
            $table->enum('payment_method', ['efectivo', 'transferencia', 'yape', 'plin', 'otro'])->default('efectivo');
            $table->text('notes')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->unique(['person_id', 'year', 'month']); // Un pago por mes por trabajador
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};