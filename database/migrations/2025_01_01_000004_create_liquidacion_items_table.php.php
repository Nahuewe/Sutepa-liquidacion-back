<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiquidacionItemsTable extends Migration {
    public function up() {
        Schema::create('liquidacion_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('liquidacion_id')->constrained('liquidaciones')->onDelete('cascade');
            $table->foreignId('concepto_id')->nullable()->constrained('conceptos')->nullOnDelete();
            $table->enum('tipo', ['HABER','DESCUENTO']);
            $table->string('codigo')->nullable();
            $table->string('descripcion')->nullable();
            $table->decimal('monto', 14, 2)->default(0);
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('liquidacion_items');
    }
}
