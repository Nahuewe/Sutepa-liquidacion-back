<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('votos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('votacion_id')->constrained('votacions')->onDelete('cascade');
            $table->unsignedBigInteger('asistente_id');
            $table->foreign('asistente_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('respuesta', ['afirmativo', 'negativo', 'abstencion']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votos');
    }
};
