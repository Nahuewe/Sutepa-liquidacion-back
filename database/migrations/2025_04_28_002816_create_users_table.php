<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('dni');
            $table->string('legajo');
            $table->string('username')->unique();
            $table->string('password');
            $table->integer('login_attempts')->default(0);
            $table->timestamp('last_login_attempt')->nullable();
            $table->boolean('is_blocked')->default(false);
            $table->string('telefono')->nullable();
            $table->string('correo')->nullable();
            $table->unsignedBigInteger('roles_id');
            $table->foreign('roles_id')->references('id')->on('roles');
            $table->unsignedBigInteger('estados_id');
            $table->foreign('estados_id')->references('id')->on('estados');
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
