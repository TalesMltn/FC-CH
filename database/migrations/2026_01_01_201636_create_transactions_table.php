<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained('contracts')->onDelete('cascade'); // Contrato al que pertenece
            $table->decimal('amount', 10, 2); // Monto pagado
            $table->date('date'); // Fecha del pago
            $table->enum('payment_method', ['efectivo', 'transferencia', 'yape', 'plin', 'tarjeta', 'cheque', 'otro'])->default('efectivo');
            $table->string('reference')->nullable(); // Número de operación, voucher, etc.
            $table->text('notes')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};