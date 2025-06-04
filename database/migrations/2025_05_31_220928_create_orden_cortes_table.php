<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('orden_cortes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('zona_id')->constrained('zonas')->onDelete('cascade');
    $table->foreignId('tecnico_id')->constrained('users')->onDelete('cascade'); // técnico que ejecuta
    $table->foreignId('afectado_id')->constrained('users')->onDelete('cascade'); // usuario general afectado
    $table->string('direccion');
    $table->date('fecha');
    $table->enum('estado', ['pendiente', 'en proceso', 'completado', 'no realizado'])->default('pendiente');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_cortes');
    }
};
