<?php

namespace App\Services;

use App\Models\Ocorrencia;

class OcorrenciaPdfData
{
    public static function build(Ocorrencia $ocorrencia): array
    {
        return match ($ocorrencia->type) {

            'varreduras-fichas-sistemas' =>
                self::buildVarreduraFichas($ocorrencia),

            'passagem-de-turno' =>
                self::buildPassagemTurno($ocorrencia),

            default => [],
        };
    }

    private static function buildPassagemTurno(Ocorrencia $ocorrencia): array
    {
        $form = $ocorrencia->form ?? [];

        return [
            'horario' => $form['horario'] ?? null,
            'atividades_realizadas' => $form['atividades_realizadas'] ?? '',
            'atividades_a_fazer' => $form['atividades_a_fazer'] ?? '',
            'observacoes_turno' => $form['observacoes_turno'] ?? '',
            'checklist_3_andar' => [
                'Sauna' => $form['sauna'] ?? '-',
                'Temperatura do aquecedor' => $form['temperatura_aquecedor'] ?? '-',
                'Chama do aquecedor' => $form['chama_aquecedor'] ?? '-',
                'Motor da piscina' => $form['motor_piscina'] ?? '-',
                'Piscina' => $form['piscina_coberta'] ?? '-',
                'Portas e janelas da sala do Ibrain' => $form['ibrain_fechado'] ?? '-',
                'Ar condicionado do salão Ibrain' => $form['ac_ibrain'] ?? '-',
                'Luzes da sala de TV' => $form['luzes_tv'] ?? '-',
                'TV do 3° andar' => $form['tv_3andar'] ?? '-',
            ],
            'checklist_estacionamento_lixeiras' => [
                'Porta interna da lixeira' => $form['porta_interna'] ?? 0,
                'Porta externa da lixeira' => $form['porta_externa'] ?? 0,
                'Luzes do Estacionamento 1' => $form['luzes_estacionamento1'] ?? '-',
            ],
            'checklist_recepcao' => [
                'Máquina Safra Nº 1' => $form['maquina_safra_1'] ?? '-',
                'Máquina Safra Nº 2' => $form['maquina_safra_2'] ?? '-',
                'Celular Nº 12996429664' => $form['celular_1'] ?? '-',
                'Celular Nº 1238321080' => $form['celular_2'] ?? '-',
            ],
            'chaves_servico' => collect($form['chaves'] ?? [])
                ->map(fn ($item) => [
                    'status' => $item['status'] ?? '-',
                    'pessoa' => $item['pessoa'] ?? '-',
                ])
                ->toArray(),
            'turno' => [
                'Hóspedes no hotel agora' => $form['turno']['hospedes'] ?? '-',
                'Aptos ocupados agora' => $form['turno']['apto_ocupados'] ?? '-',
                'Reservas para chegar' => $form['turno']['reservas'] ?? '-',
                'Check-outs à fazer' => $form['turno']['checkouts'] ?? '-',
                'Aptos interditados' => $form['turno']['interditados'] ?? '-',
                'Cartões magnéticos emprestados' => $form['turno']['cartoes_emprestados'] ?? '-',
                'Cartões aguardando para uso' => $form['turno']['cartoes_aguardando'] ?? '-',
                'Luzes da calçada' => $form['turno']['luzes_calcada'] ?? '-',
                'Responsabilidade do caixa entregue para' => $form['turno']['caixa_responsavel'] ?? '-',
                'O cartão mestre da recepção está no caixa?' => $form['turno']['cartao_mestre'] ?? '-',
                'A chave do aquecedor está no caixa?' => $form['turno']['aquecedor'] ?? '-',
                'Cartões extras (20 und.)' =>
                    ($form['turno']['cartoes_extras'] ?? null) === 'sim'
                        ? 'Sim — no caixa'
                        : 'Não — ' . ($form['turno']['cartoes_extras_local'] ?? 'local não informado'),
                'Foi batido o código de cores antes de passar o turno?' => $form['turno']['codigo_cores'] ?? '-',
                'Movimento do caixa agora dinheiro:' => \App\Helpers\Renato::formatCurrency($form['turno']['caixa_dinheiro'] ?? 0) ?? '-',
                'Movimento do caixa agora cartão:' => \App\Helpers\Renato::formatCurrency($form['turno']['caixa_cartoes'] ?? 0) ?? '-',
                'Movimento do caixa agora Total:' => \App\Helpers\Renato::formatCurrency($form['turno']['caixa_faturamento'] ?? 0) ?? '-',
            ],
            'tvbox_103' => $form['tvbox_103'] ?? '-',
            'tvbox_201' => $form['tvbox_201'] ?? '-',
            'tvbox_203' => $form['tvbox_203'] ?? '-',
            'tvbox_204' => $form['tvbox_204'] ?? '-',

            'secadores' => collect($form['secadores'] ?? [])
            ->map(fn ($status, $numero) => [
                'numero' => $numero,
                'status' => $status,
                'apto' => $form['secadores_apto'][$numero] ?? null,
            ])
            ->values()
            ->toArray(), 

            'radios' => collect($form['radios'] ?? [])
            ->map(fn ($item, $key) => [
                'numero' => $key,
                'status' => $item['status'] ?? '-',
                'funcionario' => $item['funcionario'] ?? '-',
            ])
            ->values()
            ->toArray(),  
            
            'celulares' => collect($form['celulares'] ?? [])
            ->map(fn ($item, $key) => [
                'numero' => $key + 1,
                'bateria' => $item['bateria'] ?? '-',
                'funcionario' => $item['funcionario'] ?? '-',
            ])
            ->toArray(),

            'gavetas' => collect($form['gavetas'] ?? [])
            ->map(fn ($status, $key) => [
                'numero' => $key,
                'status' => $status,
                'apto_emprestado' => $form['apto_emprestado'][$key] ?? null,
            ])
            ->toArray(),

            'chaves_mecanicas' => collect($form['chaves_mecanicas'] ?? [])
            ->map(fn ($item, $key) => [
                'numero' => $key + 1,
                'status' => $item['status'] ?? '-',
                'pessoa' => $item['pessoa'] ?? '-',
            ])
            ->toArray(),
        ];
    }

    private static function labelsVarredura(): array
    {
        return [
            'nome_hospede' => 'Nome do hóspede confere com o sistema',
            'acompanhantes' => 'Acompanhantes corretamente registrados',
            'data_entrada' => 'Data de entrada correta',
            'data_saida' => 'Data de saída prevista correta',
            'valores_diarias' => 'Valores das diárias conferidos',
            'consumacao' => 'Lançamentos de consumação realizados',
            'comandas' => 'Comandas lançadas no sistema',
            'ficha_assinada' => 'Ficha assinada pelo hóspede',
            'placa_veiculo' => 'Placa do veículo registrada',
            'observacoes' => 'Observações conferidas',
            'cnpj_nf' => 'CNPJ informado para Nota Fiscal',
        ];
    }

    private static function buildVarreduraFichas(Ocorrencia $ocorrencia): array
    {
        $form = $ocorrencia->form ?? [];

        return [
            'horario' => $form['horario'] ?? null,
            'conferencia_ficha' => $form['conferencia_ficha'] ?? [],
            'conferencia_adicional' => $form['conferencia_adicional'] ?? [],
            'observacoes_turno' => $form['observacoes_turno'] ?? '',
            'labels' => self::labelsVarredura(),
        ];
    }
}