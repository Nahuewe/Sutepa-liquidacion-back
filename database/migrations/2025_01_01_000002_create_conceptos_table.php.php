<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConceptosTable extends Migration {
    public function up() {
        Schema::create('conceptos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('descripcion');
            $table->enum('tipo', ['HABER', 'DESCUENTO']);
            $table->decimal('monto_default', 14, 2)->nullable(); // opcional
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('conceptos');
    }
}
