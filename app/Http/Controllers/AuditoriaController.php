<?php

namespace App\Http\Controllers;

use App\Exports\AuditoriaExport;
use App\Http\Resources\AuditoriaResource;
use App\Services\AuditoriaService;
use Maatwebsite\Excel\Facades\Excel;

class AuditoriaController extends Controller
{
    protected $AuditoriaService;

    public function __construct(AuditoriaService $AuditoriaService)
    {
        $this->AuditoriaService = $AuditoriaService;
    }

    public function index()
    {
        $auditoria = $this->AuditoriaService->AuditoriaLista();

        return AuditoriaResource::collection($auditoria);
    }

        public function exportarAuditoria()
    {
        return Excel::download(new AuditoriaExport, 'auditoria.xlsx');
    }
}
