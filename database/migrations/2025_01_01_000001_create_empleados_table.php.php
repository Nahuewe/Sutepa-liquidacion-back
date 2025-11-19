<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadosTable extends Migration {
    public function up() {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('cuil')->nullable();
            $table->string('legajo')->nullable();
            $table->decimal('sueldo_basico', 14, 2)->default(0);
            $table->string('puesto')->nullable();
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('empleados');
    }
}
