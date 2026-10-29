<?php

namespace Tests\Feature;

use App\Models\Ocorrencia;
use App\Services\OcorrenciaPdfData;
use Tests\TestCase;

class OcorrenciaPdfDataTest extends TestCase
{
    private function buildPassagemDeTurno(array $form): array
    {
        $ocorrencia = new Ocorrencia([
            'type' => 'passagem-de-turno',
            'form' => $form,
        ]);

        return OcorrenciaPdfData::build($ocorrencia);
    }

    public function test_niveis_dos_reservatorios_aparecem_formatados_no_pdf(): void
    {
        $data = $this->buildPassagemDeTurno([
            'cisterna_nivel' => '25',
            'caixa_dagua_nivel' => '100',
        ]);

        $checklist = $data['checklist_estacionamento_lixeiras'];

        $this->assertSame('25% (avisar manutenção)', $checklist['Cisterna — nível de água']);
        $this->assertSame('100% (cheio)', $checklist['Caixa d\'água — nível de água']);
    }

    public function test_nivel_de_agua_ausente_retorna_traco(): void
    {
        $data = $this->buildPassagemDeTurno([]);

        $checklist = $data['checklist_estacionamento_lixeiras'];

        $this->assertSame('-', $checklist['Cisterna — nível de água']);
        $this->assertSame('-', $checklist['Caixa d\'água — nível de água']);
    }
}
