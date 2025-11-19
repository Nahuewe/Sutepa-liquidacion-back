<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('votacions', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
            $table->string('identificador')->nullable();
            $table->text('contenido')->nullable();
            $table->timestamp('activa_hasta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votacions');
    }
};
