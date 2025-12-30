<?php

namespace App\Services;

use App\Models\Ocorrencia;

class OcorrenciaPdfData
{
    public static function build(Ocorrencia $ocorrencia): array
    {
        $form = $ocorrencia->form ?? [];
        //dd($form);
        return [
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
        ];
    }
}