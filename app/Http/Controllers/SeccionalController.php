<?php

namespace App\Http\Controllers;

use App\Http\Resources\SeccionalResource;
use App\Services\SeccionalService;

class SeccionalController extends Controller
{
    protected $seccionalService;

    public function __construct(SeccionalService $seccionalService)
    {
        $this->seccionalService = $seccionalService;
    }

    public function index()
    {
        $seccional = $this->seccionalService->seccionalLista();

        return SeccionalResource::collection($seccional);
    }
}
