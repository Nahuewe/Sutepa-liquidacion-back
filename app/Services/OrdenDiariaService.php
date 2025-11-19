<?php

namespace App\Services;

use App\Models\OrdenDiaria;

class OrdenDiariaService
{
    public function OrdenDiariaLista()
    {
        $OrdenDiaria = OrdenDiaria::orderBy('identificador', 'asc')->paginate(10);

        return $OrdenDiaria;
    }

    public function crearOrdenDiaria(array $data)
    {
        return OrdenDiaria::create($data);
    }

    public function obtenerPorId($id)
    {
        return OrdenDiaria::findOrFail($id);
    }

    public function editarOrdenDiaria($id, array $data)
    {
        $orden = OrdenDiaria::findOrFail($id);
        $orden->update($data);

        return $orden;
    }

    public function eliminarOrdenDiaria($id)
    {
        $orden = OrdenDiaria::findOrFail($id);
        $orden->delete();
    }
}
