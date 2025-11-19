<?php

namespace App\Services;

use App\Models\Auditoria;

class AuditoriaService
{
    public function AuditoriaLista()
    {
        $auditoria = Auditoria::orderBy('id', 'desc')->paginate(10);

        return $auditoria;
    }
}
