<?php

namespace App\Services;

use App\Models\Seccional;

class SeccionalService
{
    public function SeccionalLista()
    {
        $Seccional = Seccional::all();

        return $Seccional;
    }
}
