<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('dni');
            $table->string('legajo')->unique();
            $table->unsignedBigInteger('roles_id');
            $table->foreign('roles_id')->references('id')->on('roles');
            $table->unsignedBigInteger('seccional_id');
            $table->foreign('seccional_id')->references('id')->on('seccional');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
