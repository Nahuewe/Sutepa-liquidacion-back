<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('conceptos', function (Blueprint $table) {
            $table->string('modo_calculo')
                ->default('FIJO')
                ->after('monto_default');

            $table->decimal('valor_calculo', 14, 2)
                ->nullable()
                ->after('modo_calculo');
        });
    }

    public function down()
    {
        Schema::table('conceptos', function (Blueprint $table) {
            $table->dropColumn(['modo_calculo', 'valor_calculo']);
        });
    }
};
