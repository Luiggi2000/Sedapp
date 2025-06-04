<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zona_tecnico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zona_id')->constrained('zonas')->onDelete('cascade');
            $table->foreignId('tecnico_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('asignado_en')->useCurrent();
            $table->unique(['zona_id', 'tecnico_id']); // evita duplicados
        }); 
    }

    public function down(): void
    {
        Schema::dropIfExists('zona_tecnico');
    }
};
