<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiquidacionesTable extends Migration {
    public function up() {
        Schema::create('liquidaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            $table->string('periodo'); // e.g. '2025-10' o 'Oct-2025'
            $table->decimal('total_haberes', 14, 2)->default(0);
            $table->decimal('total_descuentos', 14, 2)->default(0);
            $table->decimal('neto', 14, 2)->default(0);
            $table->enum('estado', ['PENDIENTE','APROBADA','PAGADA'])->default('PENDIENTE');
            $table->timestamp('pagada_at')->nullable();
            $table->json('metadata')->nullable(); // info extra si necesitás
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('liquidaciones');
    }
}
