<?php

namespace App\Livewire\Dashboard\Ocorrencias;

use App\Models\Ocorrencia;
use Livewire\Component;
use Livewire\WithFileUploads;

class OcorrenciaForm extends Component
{
    use WithFileUploads;

    public ?Ocorrencia $ocorrencia = null;

    public $type;
    public $celulares = [];
    public $chavesMec = [];

    public array $itensChavesFixas = [
        17 => 'Chave Mestra Elevador',
        18 => 'Molho de Chave Mestra de Todos os Aptos',
        19 => 'Cartão TAG - Sala de Eventos Ibrain',
        20 => 'Chave da Porta de Vidro da Recepção',
        21 => 'Chave da Lixeira (2)',
        22 => 'Chave Porta Sauna',
        23 => 'Chave Porta Manutenção',
        24 => 'Controle Remoto P1',
        25 => 'Controle Remoto P2',
        26 => 'Chave Cadeado Bike Roxa',
        27 => 'Chave Cadeado Bike Vermelha',
        28 => 'Chave HUB 3° Andar',
        29 => 'Chave Porta Automática Recepção Entrada',
        30 => 'Chave (Vareta) Abertura do P2',
        31 => 'Chave Cartão Magnético Rouparia 3° Andar',
    ];
    

    public array $form = [   
        //Seção 1     
        'sauna' => null,
        'temperatura_aquecedor' => null,
        'chama_aquecedor' => null,
        'motor_piscina' => null,
        'piscina_coberta' => null,
        'ibrain_fechado' => null,
        'ac_ibrain' => null,
        'luzes_tv' => null,
        'tv_3andar' => null,

        //Seção 2
        'porta_interna' => null,
        'porta_externa' => null,
        'luzes_estacionamento1' => null,

        //Seção 3
        'maquina_safra_1' => null,
        'maquina_safra_2' => null,
        'celular_1' => null,
        'celular_2' => null,

        //Seção 4
        'chaves' => [],

        //Seção 5
        'tvbox_103' => null,
        'tvbox_201' => null,
        'tvbox_203' => null,
        'tvbox_204' => null,

        //Seção 6
        'secadores' => [
            1 => null,
            2 => null,
            3 => null,
            4 => null,
            5 => null,
        ],
        'secadores_apto' => [
            1 => '',
            2 => '',
            3 => '',
            4 => '',
            5 => '',
        ],

        //Seção 7
        'radios.1.status' => null,
        'radios.1.funcionario' => '',
        'radios.2.status' => null,
        'radios.2.funcionario' => '',
        'radios.3.status' => null,
        'radios.3.funcionario' => '',
        'radios.4.status' => null,
        'radios.4.funcionario' => '',
        'radios.5.status' => null,
        'radios.5.funcionario' => '',
        'radios.6.status' => null,
        'radios.6.funcionario' => '',
        'radios.7.status' => null,
        'radios.7.funcionario' => '',

        //Seção 8
        'celulares' => [],

        //Seção 9
        'gavetas' => [],
        'apto_emprestado' => [],

        //Seção 10        
        'turno' => [
            'hospedes' => null,
            'apto_ocupados' => null,
            'reservas' => null,
            'checkouts' => null,
            'interditados' => null,
            'cartoes_emprestados' => null,
            'cartoes_aguardando' => null,
            'luzes_calcada' => null,

            'caixa_responsavel' => '',
            'cartao_mestre' => null,
            'aquecedor' => null,
            'cartoes_extras' => null,
            'cartoes_extras_local' => '',

            'codigo_cores' => null,

            'caixa_dinheiro' => '',
            'caixa_cartoes' => '',
            'caixa_faturamento' => '',
        ],

        //Seção 11
        'chaves_mecanicas' => [],

        'item1_status' => null,
        'item1_apto'   => '',
        
    ];

    

    public function mount()
    {
        $this->chavesMec = [
            '108 CASAL',
            '109 CASAL',
            '109 SOLT',
            '119 CASAL',
            '119 SOLT',
            '208 CASAL',
            '209 CASAL',
            '209 SOLT',
            '219 CASAL',
            '219 SOLT',
        ];

        foreach ($this->chavesMec as $index => $apto) {
            $this->form['chaves_mecanicas'][$index] = [
                'status' => null,
                'pessoa' => '',
            ];
        }

        $this->celulares = [
            ['titulo' => 'Recepção 1080'],
            ['titulo' => 'Recepção 9664'],
            ['titulo' => 'Manutenção 01'],
            ['titulo' => 'Manutenção 02'],
            ['titulo' => 'Governança 01'],
            ['titulo' => 'Governança 02'],
            ['titulo' => 'Governança 03'],
            ['titulo' => 'Governança 04'],
        ];

        foreach ($this->celulares as $index => $c) {
            $this->form['celulares'][$index] = [
                'bateria' => '',
                'funcionario' => '',
            ];
        }

        foreach ($this->itensChavesFixas as $key => $label) {
            $this->form['chaves'][$key] = [
                'status' => null,
                'pessoa' => '',
            ];
        }
    }

    public function save()
    {
        $this->validate([
            'type' => 'required|string',
            'form' => 'required|array',           
        ]);

        $data = [
            'company_id' => auth()->user()->company_id, 
            'user_id'    => auth()->id(),
            'type'       => $this->type,
            'title'      => $this->titleFromType($this->type),
            'form'       => $this->form,     
            'status'     => 1,
        ];

        $ocorrencia = Ocorrencia::updateOrCreate(
            ['id' => $this->ocorrencia->id ?? null],
            $data
        );

        $this->ocorrencia = $ocorrencia;

        session()->flash('success', 'Ocorrência salva com sucesso!');
    }   
    
    protected function titleFromType(string $type): string
    {
        return match ($type) {
            'passagem-de-turno' => 'Passagem de Turno',
            'ocorrencias-diarias' => 'Ocorrências Diárias',
            default => $type,
        };
    }

    public function render()
    {
        $title = 'Cadastrar Ocorrência';
        return view('livewire.dashboard.ocorrencias.ocorrencia-form', [
            'title' => $title,
        ]);
    }
}
