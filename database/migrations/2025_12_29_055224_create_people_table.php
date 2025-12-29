<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->id();
            $table->string('dni', 8)->unique(); // DNI peruano
            $table->string('name');
            $table->string('lastname');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->enum('type', ['trabajador', 'cliente', 'proveedor', 'otro'])->default('cliente');
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persons');
    }
};