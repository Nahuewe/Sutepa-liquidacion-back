<?php

namespace App\Exports;

use App\Models\Liquidacion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LiquidacionesExport implements FromCollection, WithHeadings {
    protected $periodo;
    public function __construct($periodo = null) { $this->periodo = $periodo; }

    public function collection() {
        $q = Liquidacion::with('empleado');
        if ($this->periodo) $q->where('periodo', $this->periodo);
        return $q->get()->map(function($l) {
            return [
                'id' => $l->id,
                'empleado' => $l->empleado->nombre . ' ' . $l->empleado->apellido,
                'periodo' => $l->periodo,
                'haberes' => $l->total_haberes,
                'descuentos' => $l->total_descuentos,
                'neto' => $l->neto,
                'estado' => $l->estado,
            ];
        });
    }

    public function headings(): array {
        return ['ID','Empleado','Periodo','Haberes','Descuentos','Neto','Estado'];
    }
}
