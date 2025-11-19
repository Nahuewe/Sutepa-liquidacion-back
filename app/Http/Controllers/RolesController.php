<?php

namespace App\Http\Controllers;

use App\Http\Resources\RolesResource;
use App\Services\RolesService;

class RolesController extends Controller
{
    protected $RolesService;

    public function __construct(RolesService $RolesService)
    {
        $this->RolesService = $RolesService;
    }

    public function index()
    {
        $Roles = $this->RolesService->RolesLista();

        return RolesResource::collection($Roles);
    }
}
