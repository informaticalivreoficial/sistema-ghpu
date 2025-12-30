<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OcorrenciaPdfData;
use App\Models\Ocorrencia;
use Barryvdh\DomPDF\Facade\Pdf;

class OcorrenciaPdfController extends Controller
{
    public function show(Ocorrencia $ocorrencia)
    {
        $company = $ocorrencia->company;

        if (! $company) {
            abort(404, 'Empresa não encontrada');
        }

        $data = OcorrenciaPdfData::build($ocorrencia);

        $pdf = Pdf::loadView('pdf.ocorrencia', [
            'ocorrencia' => $ocorrencia,
            'company' => $company,
            'data' => $data,
        ]);

        return $pdf->stream(
            'ocorrencia-' . $ocorrencia->id . '.pdf'
        );
    }
}
