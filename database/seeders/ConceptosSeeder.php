<?php

use Illuminate\Database\Seeder;
use App\Models\Concepto;
class ConceptosSeeder extends Seeder {
    public function run() {
        $conceptos = [
            ['codigo'=>'BAS','descripcion'=>'Sueldo Básico','tipo'=>'HABER','monto_default'=>0],
            ['codigo'=>'AGU','descripcion'=>'Aguinaldo','tipo'=>'HABER','monto_default'=>0],
            ['codigo'=>'AFP','descripcion'=>'Aporte Jubilatorio','tipo'=>'DESCUENTO','monto_default'=>0],
            ['codigo'=>'OBR','descripcion'=>'Obra Social','tipo'=>'DESCUENTO','monto_default'=>0],
        ];
        foreach($conceptos as $c) Concepto::create($c);
    }
}
