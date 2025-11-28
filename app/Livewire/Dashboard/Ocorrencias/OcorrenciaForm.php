<?php

namespace App\Livewire\Dashboard\Ocorrencias;

use App\Models\Ocorrencia;
use Livewire\Component;
use Livewire\WithFileUploads;

class OcorrenciaForm extends Component
{
    use WithFileUploads;

    public ?Ocorrencia $ocorrencia = null;

    public $type = '';
    public $destinatario = null;
    public $celulares = [];
    public $chavesMec = [];

    protected $messages = [
        'destinatario' => 'nome do funcionário que vai assumir o turno',
        'form.sauna' => 'Sauna',
        'form.chama_aquecedor' => 'Chama do aquecedor',
        'form.motor_piscina' => 'Motor da piscina',
        'form.piscina_coberta' => 'Piscina coberta',
        'form.ibrain_fechado' => 'Salão IBrain',
        'form.ac_ibrain' => 'Ar condicionado iBrain',
        'form.luzes_tv' => 'Luzes da TV',
        'form.tv_3andar' => 'TV do 3º andar',
        'form.temperatura_aquecedor' => 'Temperatura do aquecedor',
        'form.nome' => 'Nome',
        'form.title' => 'Título',
        'type' => 'Tipo de Ocorrência',

        'form.porta_interna' => 'Porta interna',
        'form.porta_externa' => 'Porta externa',
        'form.luzes_estacionamento1' => 'Luzes do estacionamento 1',

        'form.maquina_safra_1' => 'Máquina Safra 1',    
        'form.maquina_safra_2' => 'Máquina Safra 2',
        'form.celular_1' => 'Celular 1',
        'form.celular_2' => 'Celular 2',

        'form.maquina_safra_1.required' => 'Informe a porcentagem da bateria.',
        'form.maquina_safra_1.numeric' => 'A bateria deve ser um número.',
        'form.maquina_safra_1.min' => 'A bateria mínima é 0%.',
        'form.maquina_safra_1.max' => 'A bateria máxima é 100%.',

        'form.maquina_safra_2.required' => 'Informe a porcentagem da bateria.',
        'form.maquina_safra_2.numeric' => 'A bateria deve ser um número.',
        'form.maquina_safra_2.min' => 'A bateria mínima é 0%.',
        'form.maquina_safra_2.max' => 'A bateria máxima é 100%.',

        'form.celular_1.required' => 'Informe a porcentagem da bateria.',
        'form.celular_1.numeric' => 'A bateria deve ser um número.',
        'form.celular_1.min' => 'A bateria mínima é 0%.',
        'form.celular_1.max' => 'A bateria máxima é 100%.',

        'form.celular_2.required' => 'Informe a porcentagem da bateria.',
        'form.celular_2.numeric' => 'A bateria deve ser um número.',
        'form.celular_2.min' => 'A bateria mínima é 0%.',
        'form.celular_2.max' => 'A bateria máxima é 100%.',
        
        'form.tvbox_103.required' => 'Informe o status do TV Box do apto 103.',
        'form.tvbox_201.required' => 'Informe o status do TV Box do apto 201.',
        'form.tvbox_203.required' => 'Informe o status do TV Box do apto 203.',
        'form.tvbox_204.required' => 'Informe o status do TV Box do apto 204.',

        // Chaves fixas
        'form.chaves.*.status.required' => 'Selecione o status da chave.',
        'form.chaves.*.pessoa.required_if' => 'Informe com quem está a chave.',
        'form.chaves.*.pessoa.min' => 'O nome deve ter no mínimo 3 caracteres.',

        // Celulares
        'form.celulares.*.bateria.required' => 'Informe a porcentagem da bateria.',
        //'form.celulares.*.funcionario.required' => 'Informe com quem está o celular.',

        'form.celulares.*.bateria.required' => 'Informe a porcentagem da bateria.',
        'form.celulares.*.bateria.numeric' => 'A bateria deve ser um número.',
        'form.celulares.*.bateria.min' => 'A bateria mínima é 0%.',
        'form.celulares.*.bateria.max' => 'A bateria máxima é 100%.',
        //'form.celulares.*.funcionario.required' => 'Informe com quem está o celular.',
        //'form.celulares.*.funcionario.min' => 'O nome deve ter no mínimo 3 caracteres.',

        // Chaves mecânicas
        'form.chaves_mecanicas.*.status.required' => 'Selecione o status da chave mecânica.',
        'form.chaves_mecanicas.*.pessoa.required_if' => 'Informe com quem está a chave.',

        // Turno
        'form.turno.caixa_responsavel.required' => 'Informe o responsável pelo caixa.',

        // Secadores
        'form.secadores.1.required' => 'Selecione o status do Secador 1.',
        'form.secadores.2.required' => 'Selecione o status do Secador 2.',
        'form.secadores.3.required' => 'Selecione o status do Secador 3.',
        'form.secadores.4.required' => 'Selecione o status do Secador 4.',
        'form.secadores.5.required' => 'Selecione o status do Secador 5.',
        
        'form.secadores_apto.1.required_if' => 'Informe o número do apartamento para o Secador 1.',
        'form.secadores_apto.2.required_if' => 'Informe o número do apartamento para o Secador 2.',
        'form.secadores_apto.3.required_if' => 'Informe o número do apartamento para o Secador 3.',
        'form.secadores_apto.4.required_if' => 'Informe o número do apartamento para o Secador 4.',
        'form.secadores_apto.5.required_if' => 'Informe o número do apartamento para o Secador 5.',

        // Seção 7 - Rádios
        'form.radios.1.status.required' => 'Selecione o status do Rádio 1.',
        'form.radios.1.status.in' => 'O status deve ser "base" ou "funcionario".',
        'form.radios.1.funcionario.required_if' => 'Informe o nome do funcionário com o Rádio 1.',
        'form.radios.1.funcionario.min' => 'O nome deve ter no mínimo 3 caracteres.',
        
        'form.radios.2.status.required' => 'Selecione o status do Rádio 2.',
        'form.radios.2.status.in' => 'O status deve ser "base" ou "funcionario".',
        'form.radios.2.funcionario.required_if' => 'Informe o nome do funcionário com o Rádio 2.',
        'form.radios.2.funcionario.min' => 'O nome deve ter no mínimo 3 caracteres.',
        
        'form.radios.3.status.required' => 'Selecione o status do Rádio 3.',
        'form.radios.3.status.in' => 'O status deve ser "base" ou "funcionario".',
        'form.radios.3.funcionario.required_if' => 'Informe o nome do funcionário com o Rádio 3.',
        'form.radios.3.funcionario.min' => 'O nome deve ter no mínimo 3 caracteres.',
        
        'form.radios.4.status.required' => 'Selecione o status do Rádio 4.',
        'form.radios.4.status.in' => 'O status deve ser "base" ou "funcionario".',
        'form.radios.4.funcionario.required_if' => 'Informe o nome do funcionário com o Rádio 4.',
        'form.radios.4.funcionario.min' => 'O nome deve ter no mínimo 3 caracteres.',
        
        'form.radios.5.status.required' => 'Selecione o status do Rádio 5.',
        'form.radios.5.status.in' => 'O status deve ser "base" ou "funcionario".',
        'form.radios.5.funcionario.required_if' => 'Informe o nome do funcionário com o Rádio 5.',
        'form.radios.5.funcionario.min' => 'O nome deve ter no mínimo 3 caracteres.',
        
        'form.radios.6.status.required' => 'Selecione o status do Rádio 6.',
        'form.radios.6.status.in' => 'O status deve ser "base" ou "funcionario".',
        'form.radios.6.funcionario.required_if' => 'Informe o nome do funcionário com o Rádio 6.',
        'form.radios.6.funcionario.min' => 'O nome deve ter no mínimo 3 caracteres.',
        
        'form.radios.7.status.required' => 'Selecione o status do Rádio 7.',
        'form.radios.7.status.in' => 'O status deve ser "base" ou "funcionario".',
        'form.radios.7.funcionario.required_if' => 'Informe o nome do funcionário com o Rádio 7.',
        'form.radios.7.funcionario.min' => 'O nome deve ter no mínimo 3 caracteres.',

        // Seção 9 - Gavetas
        'form.gavetas.1.required' => 'Selecione o status do Controle PS4 — Nº 1.',
        'form.gavetas.2.required' => 'Selecione o status do Controle PS4 — Nº 2.',
        'form.gavetas.3.required' => 'Selecione o status do Jogo FIFA 25.',
        'form.gavetas.4.required' => 'Selecione o status do Jogo Days Gone.',
        'form.gavetas.5.required' => 'Selecione o status do Jogo Gran Turismo.',
        'form.gavetas.6.required' => 'Selecione o status do God of War.',
        'form.gavetas.7.required' => 'Selecione o status do Controle TV 3° Andar.',
        'form.gavetas.8.required' => 'Selecione o status do Controle Ar iBrain.',
        'form.gavetas.9.required' => 'Selecione o status do Controle Ar Recepção.',
        'form.gavetas.10.required' => 'Selecione o status do FIFA FC 26.',
        'form.gavetas.11.required' => 'Selecione o status do Formula 1 — 24.',
        
        'form.apto_emprestado.1.required_if' => 'Informe o nº do apartamento para o Controle PS4 — Nº 1.',
        'form.apto_emprestado.2.required_if' => 'Informe o nº do apartamento para o Controle PS4 — Nº 2.',
        'form.apto_emprestado.3.required_if' => 'Informe o nº do apartamento para o Jogo FIFA 25.',
        'form.apto_emprestado.4.required_if' => 'Informe o nº do apartamento para o Jogo Days Gone.',
        'form.apto_emprestado.5.required_if' => 'Informe o nº do apartamento para o Jogo Gran Turismo.',
        'form.apto_emprestado.6.required_if' => 'Informe o nº do apartamento para o God of War.',
        'form.apto_emprestado.7.required_if' => 'Informe o nº do apartamento para o Controle TV 3° Andar.',
        'form.apto_emprestado.8.required_if' => 'Informe o nº do apartamento para o Controle Ar iBrain.',
        'form.apto_emprestado.9.required_if' => 'Informe o nº do apartamento para o Controle Ar Recepção.',
        'form.apto_emprestado.10.required_if' => 'Informe o nº do apartamento para o FIFA FC 26.',
        'form.apto_emprestado.11.required_if' => 'Informe o nº do apartamento para o Formula 1 — 24.',
        
        // Seção 10 - Turno
        'form.turno.hospedes.required' => 'Informe o número de hóspedes.',
        'form.turno.hospedes.numeric' => 'O número de hóspedes deve ser numérico.',
        'form.turno.hospedes.min' => 'O número mínimo de hóspedes é 0.',
        
        'form.turno.apto_ocupados.required' => 'Informe o número de apartamentos ocupados.',
        'form.turno.apto_ocupados.numeric' => 'Deve ser um número.',
        'form.turno.apto_ocupados.min' => 'O número mínimo é 0.',
        
        'form.turno.reservas.required' => 'Informe o número de reservas.',
        'form.turno.reservas.numeric' => 'Deve ser um número.',
        'form.turno.reservas.min' => 'O número mínimo é 0.',
        
        'form.turno.checkouts.required' => 'Informe o número de checkouts.',
        'form.turno.checkouts.numeric' => 'Deve ser um número.',
        'form.turno.checkouts.min' => 'O número mínimo é 0.',
        
        'form.turno.interditados.required' => 'Informe o número de apartamentos interditados.',
        'form.turno.interditados.numeric' => 'Deve ser um número.',
        'form.turno.interditados.min' => 'O número mínimo é 0.',
        
        'form.turno.cartoes_emprestados.required' => 'Informe a quantidade de cartões emprestados.',
        'form.turno.cartoes_emprestados.numeric' => 'Deve ser um número.',
        'form.turno.cartoes_emprestados.min' => 'O número mínimo é 0.',
        
        'form.turno.cartoes_aguardando.required' => 'Informe a quantidade de cartões aguardando.',
        'form.turno.cartoes_aguardando.numeric' => 'Deve ser um número.',
        'form.turno.cartoes_aguardando.min' => 'O número mínimo é 0.',
        
        'form.turno.luzes_calcada.required' => 'Informe o status das luzes da calçada.',
        'form.turno.luzes_calcada.in' => 'O status deve ser "ligada" ou "desligada".',
        
        'form.turno.caixa_responsavel.required' => 'Informe o responsável pelo caixa.',
        'form.turno.caixa_responsavel.string' => 'O nome deve ser um texto.',
        'form.turno.caixa_responsavel.min' => 'O nome deve ter no mínimo 3 caracteres.',
        
        'form.turno.cartao_mestre.required' => 'Informe se o cartão mestre está no caixa.',
        'form.turno.cartao_mestre.in' => 'A resposta deve ser "sim" ou "não".',
        
        'form.turno.aquecedor.required' => 'Informe se a chave do aquecedor está no caixa.',
        'form.turno.aquecedor.in' => 'A resposta deve ser "sim" ou "não".',
        
        'form.turno.cartoes_extras.required' => 'Informe se os cartões extras estão no caixa.',
        'form.turno.cartoes_extras.in' => 'A resposta deve ser "sim" ou "não".',
        
        'form.turno.cartoes_extras_local.required_if' => 'Informe onde estão os cartões extras.',
        'form.turno.cartoes_extras_local.string' => 'Deve ser um texto.',
        'form.turno.cartoes_extras_local.min' => 'A informação deve ter no mínimo 3 caracteres.',
        
        'form.turno.codigo_cores.required' => 'Informe se foi batido o código de cores.',
        'form.turno.codigo_cores.in' => 'A resposta deve ser "sim" ou "não".',
        
        'form.turno.caixa_dinheiro.required' => 'Informe o valor em dinheiro no caixa.',
        'form.turno.caixa_dinheiro.numeric' => 'O valor deve ser numérico.',
        'form.turno.caixa_dinheiro.min' => 'O valor mínimo é 0.',
        
        'form.turno.caixa_cartoes.required' => 'Informe o valor em cartões no caixa.',
        'form.turno.caixa_cartoes.numeric' => 'O valor deve ser numérico.',
        'form.turno.caixa_cartoes.min' => 'O valor mínimo é 0.',
        
        'form.turno.caixa_faturamento.required' => 'Informe o valor do faturamento no caixa.',
        'form.turno.caixa_faturamento.numeric' => 'O valor deve ser numérico.',
        'form.turno.caixa_faturamento.min' => 'O valor mínimo é 0.',

        'form.turno.cartoes_extras_local.required_if' => 'Informe onde estão os cartões extras.',
        'form.turno.cartoes_extras_local.min' => 'A informação deve ter no mínimo 3 caracteres.',

        // Seção 11 - Chaves Mecânicas
        'form.chaves_mecanicas.0.status.required' => 'Selecione o status da chave 108 CASAL.',
        'form.chaves_mecanicas.1.status.required' => 'Selecione o status da chave 109 CASAL.',
        'form.chaves_mecanicas.2.status.required' => 'Selecione o status da chave 109 SOLT.',
        'form.chaves_mecanicas.3.status.required' => 'Selecione o status da chave 119 CASAL.',
        'form.chaves_mecanicas.4.status.required' => 'Selecione o status da chave 119 SOLT.',
        'form.chaves_mecanicas.5.status.required' => 'Selecione o status da chave 208 CASAL.',
        'form.chaves_mecanicas.6.status.required' => 'Selecione o status da chave 209 CASAL.',
        'form.chaves_mecanicas.7.status.required' => 'Selecione o status da chave 209 SOLT.',
        'form.chaves_mecanicas.8.status.required' => 'Selecione o status da chave 219 CASAL.',
        'form.chaves_mecanicas.9.status.required' => 'Selecione o status da chave 219 SOLT.',
        
        'form.chaves_mecanicas.*.status.in' => 'O status deve ser "recepcao" ou "pessoa".',
        
        'form.chaves_mecanicas.0.pessoa.required_if' => 'Informe com quem está a chave 108 CASAL.',
        'form.chaves_mecanicas.1.pessoa.required_if' => 'Informe com quem está a chave 109 CASAL.',
        'form.chaves_mecanicas.2.pessoa.required_if' => 'Informe com quem está a chave 109 SOLT.',
        'form.chaves_mecanicas.3.pessoa.required_if' => 'Informe com quem está a chave 119 CASAL.',
        'form.chaves_mecanicas.4.pessoa.required_if' => 'Informe com quem está a chave 119 SOLT.',
        'form.chaves_mecanicas.5.pessoa.required_if' => 'Informe com quem está a chave 208 CASAL.',
        'form.chaves_mecanicas.6.pessoa.required_if' => 'Informe com quem está a chave 209 CASAL.',
        'form.chaves_mecanicas.7.pessoa.required_if' => 'Informe com quem está a chave 209 SOLT.',
        'form.chaves_mecanicas.8.pessoa.required_if' => 'Informe com quem está a chave 219 CASAL.',
        'form.chaves_mecanicas.9.pessoa.required_if' => 'Informe com quem está a chave 219 SOLT.',
        
        'form.chaves_mecanicas.*.pessoa.min' => 'O nome deve ter no mínimo 3 caracteres.',
        'form.chaves_mecanicas.*.pessoa.max' => 'O nome deve ter no máximo 100 caracteres.',
    ];

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

        //Seção 7 - CORRETO: usar array aninhado
        'radios' => [
            1 => ['status' => null, 'funcionario' => ''],
            2 => ['status' => null, 'funcionario' => ''],
            3 => ['status' => null, 'funcionario' => ''],
            4 => ['status' => null, 'funcionario' => ''],
            5 => ['status' => null, 'funcionario' => ''],
            6 => ['status' => null, 'funcionario' => ''],
            7 => ['status' => null, 'funcionario' => ''],
        ],

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

    

    public function mount($id = null)
    {

        // PRIMEIRO: Inicializa as estruturas base
        $this->initializeArrays();
        
        // SEGUNDO: Se for edição, carrega e SOBRESCREVE os dados
        if ($id) {
            $this->ocorrencia = Ocorrencia::findOrFail($id);
            
            // Carrega o tipo
            $this->type = $this->ocorrencia->type;
            
            // Carrega o destinatário
            $this->destinatario = $this->ocorrencia->destinatario;
            
            // IMPORTANTE: Sobrescreve o form inteiro com os dados salvos
            if ($this->ocorrencia->form) {
                $this->form = $this->ocorrencia->form;
            }
        }

        // $this->initializeArrays();

        // if ($id) {
        //     // Busca a ocorrência do banco de dados
        //     $this->ocorrencia = Ocorrencia::findOrFail($id);
            
        //     // Carrega o tipo (isso vai fazer o formulário aparecer)
        //     $this->type = $this->ocorrencia->type;
            
        //     // Carrega o destinatário
        //     $this->destinatario = $this->ocorrencia->destinatario ?? '';
            
        //     // Carrega os dados do formulário salvos
        //     if ($this->ocorrencia->form) {
        //         $formData = is_string($this->ocorrencia->form) 
        //             ? json_decode($this->ocorrencia->form, true) 
        //             : $this->ocorrencia->form;
                
        //         // IMPORTANTE: Sobrescreve apenas os campos que existem nos dados salvos
        //         foreach ($formData as $key => $value) {
        //             if (array_key_exists($key, $this->form)) {
        //                 if (is_array($value) && is_array($this->form[$key])) {
        //                     // Se for array, mescla mantendo a estrutura
        //                     $this->form[$key] = $this->mergeFormData($this->form[$key], $value);
        //                 } else {
        //                     // Se não for array, substitui diretamente
        //                     $this->form[$key] = $value;
        //                 }
        //             }
        //         }
        //     }
        // }

        // $this->chavesMec = [
        //     '108 CASAL',
        //     '109 CASAL',
        //     '109 SOLT',
        //     '119 CASAL',
        //     '119 SOLT',
        //     '208 CASAL',
        //     '209 CASAL',
        //     '209 SOLT',
        //     '219 CASAL',
        //     '219 SOLT',
        // ];

        // foreach ($this->chavesMec as $index => $apto) {
        //     $this->form['chaves_mecanicas'][$index] = [
        //         'status' => null,
        //         'pessoa' => '',
        //     ];
        // }

        // $this->celulares = [
        //     ['titulo' => 'Recepção 1080'],
        //     ['titulo' => 'Recepção 9664'],
        //     ['titulo' => 'Manutenção 01'],
        //     ['titulo' => 'Manutenção 02'],
        //     ['titulo' => 'Governança 01'],
        //     ['titulo' => 'Governança 02'],
        //     ['titulo' => 'Governança 03'],
        //     ['titulo' => 'Governança 04'],
        // ];

        // foreach ($this->celulares as $index => $c) {
        //     $this->form['celulares'][$index] = [
        //         'bateria' => '',
        //         'funcionario' => '',
        //     ];
        // }

        // foreach ($this->itensChavesFixas as $key => $label) {
        //     $this->form['chaves'][$key] = [
        //         'status' => null,
        //         'pessoa' => '',
        //     ];
        // }

        // // ADICIONE ISSO - Inicializa as gavetas (11 itens)
        // for ($i = 1; $i <= 11; $i++) {
        //     $this->form['gavetas'][$i] = null;
        //     $this->form['apto_emprestado'][$i] = '';
        // }
        
    }

    private function mergeFormData($base, $data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value) && isset($base[$key]) && is_array($base[$key])) {
                $base[$key] = $this->mergeFormData($base[$key], $value);
            } else {
                $base[$key] = $value;
            }
        }
        return $base;
    }

    private function initializeArrays()
    {           
        // Só inicializa se NÃO for edição (ou seja, se form estiver vazio)
        // Na edição, isso será sobrescrito
        
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

        // Inicializa apenas se estiver vazio
        if (empty($this->form['chaves_mecanicas'])) {
            foreach ($this->chavesMec as $index => $apto) {
                $this->form['chaves_mecanicas'][$index] = [
                    'status' => null,
                    'pessoa' => '',
                ];
            }
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

        if (empty($this->form['celulares'])) {
            foreach ($this->celulares as $index => $c) {
                $this->form['celulares'][$index] = [
                    'bateria' => '',
                    'funcionario' => '',
                ];
            }
        }

        if (empty($this->form['chaves'])) {
            foreach ($this->itensChavesFixas as $key => $label) {
                $this->form['chaves'][$key] = [
                    'status' => null,
                    'pessoa' => '',
                ];
            }
        }

        if (empty($this->form['gavetas'])) {
            for ($i = 1; $i <= 11; $i++) {
                $this->form['gavetas'][$i] = null;
                $this->form['apto_emprestado'][$i] = '';
            }
        }
    }

    public function save()
    {
        try {
            // Se não tiver tipo selecionado, valida apenas o tipo
            if (empty($this->type)) {
                $this->validate([
                    'type' => 'required|string',
                ], [
                    'type.required' => 'Selecione o tipo de ocorrência antes de continuar.',
                ]);
                return; 
            }
            
            // Validação completa quando há tipo selecionado
            $rules = [
                'type' => 'required|string',
                'destinatario' => 'required|string|min:6|max:100',
                'form' => 'required|array',
            ];
            
            // Adiciona regras específicas baseadas no tipo
            $rules = array_merge($rules, $this->getRulesForType($this->type));
            
            $this->validate($rules);

            // Salva
            $data = [
                'company_id'   => auth()->user()->company_id,
                'user_id'      => auth()->id(),
                'type'         => $this->type,
                'title'        => $this->titleFromType($this->type),
                'destinatario' => $this->destinatario,
                'form'         => $this->form,
                'status'       => 1,
            ];

            $ocorrencia = Ocorrencia::updateOrCreate(
                ['id' => $this->ocorrencia->id ?? null],
                $data
            );

            $this->ocorrencia = $ocorrencia;

            // Mensagem de sucesso diferente para criar/editar
            $foiCriacao = $this->ocorrencia->wasRecentlyCreated;

            // Mensagem de sucesso diferente para criar/editar
            $mensagem = $foiCriacao 
                ? 'Ocorrência cadastrada com sucesso!' 
                : 'Ocorrência atualizada com sucesso!';

            $this->dispatch('swal-redirect', [
                'title' => 'Sucesso!',
                'text' => $mensagem,
                'icon' => 'success',
                'redirect' => $foiCriacao ? route('ocorrencias.index') : null, // ← null = não redireciona
            ]);
            
            //$this->dispatch('toast', type: 'success', message: $mensagem);
            
            // Redireciona para a lista após salvar (opcional)
            //return $this->redirect(route('ocorrencias.index'), navigate: true);

            //$this->dispatch('toast', type: 'success', message: 'Ocorrência salva com sucesso!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errorCount = count($e->validator->errors()->all());
            
            // Só dispara toast se tiver mais de 1 erro
            if ($errorCount > 1) {
                $this->dispatch('toast', 
                    type: 'error', 
                    message: "Por favor, preencha os {$errorCount} campos destacados no formulário."
                );
            }
            
            throw $e;
        }
    }

    // Método auxiliar para retornar regras específicas por tipo
    private function getRulesForType($type)
    {
        $rulesMap = [
            'passagem-de-turno' => [
                //Seção 1
                'form.sauna' => 'required|in:ligada,desligada',
                'form.temperatura_aquecedor' => 'required|string|min:2|max:100',
                'form.chama_aquecedor' => 'required|in:acesa,apagada',
                'form.motor_piscina' => 'required|in:ligado,desligado',
                'form.piscina_coberta' => 'required|in:coberta,descoberta',
                'form.ibrain_fechado' => 'required|in:fechadas,uso',
                'form.ac_ibrain' => 'required|in:ligado,desligado',
                'form.luzes_tv' => 'required|in:ligadas,desligadas',
                'form.tv_3andar' => 'required|in:ligada,desligada',

                //Seção 2
                'form.porta_interna' => 'required|in:aberta,fechada',
                'form.porta_externa' => 'required|in:aberta,fechada',
                'form.luzes_estacionamento1' => 'required|in:ligada,desligada',

                //Seção 3
                'form.maquina_safra_1' => 'required|numeric|min:1|max:100',
                'form.maquina_safra_2' => 'required|numeric|min:1|max:100',
                'form.celular_1' => 'required|numeric|min:1|max:100',
                'form.celular_2' => 'required|numeric|min:1|max:100',

                //Seção 5
                'form.tvbox_103' => 'required|in:pote,uso',
                'form.tvbox_201' => 'required|in:pote,uso',
                'form.tvbox_203' => 'required|in:pote,uso',
                'form.tvbox_204' => 'required|in:pote,uso',

                //Seção 6
                'form.secadores.1' => 'required|in:gaveta,emprestado',
                'form.secadores.2' => 'required|in:gaveta,emprestado',
                'form.secadores.3' => 'required|in:gaveta,emprestado',
                'form.secadores.4' => 'required|in:gaveta,emprestado',
                'form.secadores.5' => 'required|in:gaveta,emprestado',
                'form.secadores_apto.1' => 'required_if:form.secadores.1,emprestado|nullable|string',
                'form.secadores_apto.2' => 'required_if:form.secadores.2,emprestado|nullable|string',
                'form.secadores_apto.3' => 'required_if:form.secadores.3,emprestado|nullable|string',
                'form.secadores_apto.4' => 'required_if:form.secadores.4,emprestado|nullable|string',
                'form.secadores_apto.5' => 'required_if:form.secadores.5,emprestado|nullable|string',

                // Seção 7 - Rádios
                'form.radios.1.status' => 'required|in:base,funcionario',
                'form.radios.1.funcionario' => 'required_if:form.radios.1.status,funcionario|nullable|string|min:3',
                'form.radios.2.status' => 'required|in:base,funcionario',
                'form.radios.2.funcionario' => 'required_if:form.radios.2.status,funcionario|nullable|string|min:3',
                'form.radios.3.status' => 'required|in:base,funcionario',
                'form.radios.3.funcionario' => 'required_if:form.radios.3.status,funcionario|nullable|string|min:3',
                'form.radios.4.status' => 'required|in:base,funcionario',
                'form.radios.4.funcionario' => 'required_if:form.radios.4.status,funcionario|nullable|string|min:3',
                'form.radios.5.status' => 'required|in:base,funcionario',
                'form.radios.5.funcionario' => 'required_if:form.radios.5.status,funcionario|nullable|string|min:3',
                'form.radios.6.status' => 'required|in:base,funcionario',
                'form.radios.6.funcionario' => 'required_if:form.radios.6.status,funcionario|nullable|string|min:3',
                'form.radios.7.status' => 'required|in:base,funcionario',
                'form.radios.7.funcionario' => 'required_if:form.radios.7.status,funcionario|nullable|string|min:3',
                
                // Seção 8 - Celulares (específicas por índice)
                'form.celulares.0.bateria.required' => 'Informe a bateria da Recepção 1080.',
                //'form.celulares.0.funcionario.required' => 'Informe com quem está o celular da Recepção 1080.',
                'form.celulares.1.bateria.required' => 'Informe a bateria da Recepção 9664.',
                //'form.celulares.1.funcionario.required' => 'Informe com quem está o celular da Recepção 9664.',
                'form.celulares.2.bateria.required' => 'Informe a bateria da Manutenção 01.',
                //'form.celulares.2.funcionario.required' => 'Informe com quem está o celular da Manutenção 01.',
                'form.celulares.3.bateria.required' => 'Informe a bateria da Manutenção 02.',
                //'form.celulares.3.funcionario.required' => 'Informe com quem está o celular da Manutenção 02.',
                'form.celulares.4.bateria.required' => 'Informe a bateria da Governança 01.',
                //'form.celulares.4.funcionario.required' => 'Informe com quem está o celular da Governança 01.',
                'form.celulares.5.bateria.required' => 'Informe a bateria da Governança 02.',
                //'form.celulares.5.funcionario.required' => 'Informe com quem está o celular da Governança 02.',
                'form.celulares.6.bateria.required' => 'Informe a bateria da Governança 03.',
                //'form.celulares.6.funcionario.required' => 'Informe com quem está o celular da Governança 03.',
                'form.celulares.7.bateria.required' => 'Informe a bateria da Governança 04.',
                //'form.celulares.7.funcionario.required' => 'Informe com quem está o celular da Governança 04.',

                // Seção 9 - Gavetas (11 itens)
                'form.gavetas.1' => 'required|in:gaveta,emprestado',
                'form.gavetas.2' => 'required|in:gaveta,emprestado',
                'form.gavetas.3' => 'required|in:gaveta,emprestado',
                'form.gavetas.4' => 'required|in:gaveta,emprestado',
                'form.gavetas.5' => 'required|in:gaveta,emprestado',
                'form.gavetas.6' => 'required|in:gaveta,emprestado',
                'form.gavetas.7' => 'required|in:gaveta,emprestado',
                'form.gavetas.8' => 'required|in:gaveta,emprestado',
                'form.gavetas.9' => 'required|in:gaveta,emprestado',
                'form.gavetas.10' => 'required|in:gaveta,emprestado',
                'form.gavetas.11' => 'required|in:gaveta,emprestado',
                
                'form.apto_emprestado.1' => 'required_if:form.gavetas.1,emprestado|nullable|string|min:1',
                'form.apto_emprestado.2' => 'required_if:form.gavetas.2,emprestado|nullable|string|min:1',
                'form.apto_emprestado.3' => 'required_if:form.gavetas.3,emprestado|nullable|string|min:1',
                'form.apto_emprestado.4' => 'required_if:form.gavetas.4,emprestado|nullable|string|min:1',
                'form.apto_emprestado.5' => 'required_if:form.gavetas.5,emprestado|nullable|string|min:1',
                'form.apto_emprestado.6' => 'required_if:form.gavetas.6,emprestado|nullable|string|min:1',
                'form.apto_emprestado.7' => 'required_if:form.gavetas.7,emprestado|nullable|string|min:1',
                'form.apto_emprestado.8' => 'required_if:form.gavetas.8,emprestado|nullable|string|min:1',
                'form.apto_emprestado.9' => 'required_if:form.gavetas.9,emprestado|nullable|string|min:1',
                'form.apto_emprestado.10' => 'required_if:form.gavetas.10,emprestado|nullable|string|min:1',
                'form.apto_emprestado.11' => 'required_if:form.gavetas.11,emprestado|nullable|string|min:1',

                'form.turno.hospedes' => 'required|numeric|min:0',
                'form.turno.apto_ocupados' => 'required|numeric|min:0',
                'form.turno.reservas' => 'required|numeric|min:0',
                'form.turno.checkouts' => 'required|numeric|min:0',
                'form.turno.interditados' => 'required|numeric|min:0',
                'form.turno.cartoes_emprestados' => 'required|numeric|min:0',
                'form.turno.cartoes_aguardando' => 'required|numeric|min:0',
                'form.turno.luzes_calcada' => 'required|in:ligada,desligada',
                'form.turno.caixa_responsavel' => 'required|string|min:3',
                'form.turno.cartao_mestre' => 'required|in:sim,nao',
                'form.turno.aquecedor' => 'required|in:sim,nao',
                'form.turno.cartoes_extras' => 'required|in:sim,nao',
                'form.turno.cartoes_extras_local' => 'required_if:form.turno.cartoes_extras,nao|nullable|string|min:3',
                'form.turno.codigo_cores' => 'required|in:sim,nao',
                'form.turno.caixa_dinheiro' => 'required|numeric|min:0',
                'form.turno.caixa_cartoes' => 'required|numeric|min:0',
                'form.turno.caixa_faturamento' => 'required|numeric|min:0',
                
            ],
            'ocorrencias-diarias' => [
                // Regras específicas para ocorrências diárias
            ],
            'varreduras-fichas-sistemas' => [
                // Regras específicas para varreduras
            ],
            'branco' => [
                // Regras específicas para formulário em branco
            ],
        ];

        if ($type === 'passagem-de-turno') {
            foreach ($this->itensChavesFixas as $key => $label) {
                $rulesMap[$type]["form.chaves.{$key}.status"] = 'required|in:gaveta,com';
                $rulesMap[$type]["form.chaves.{$key}.pessoa"] = "required_if:form.chaves.{$key}.status,com|nullable|string|min:3|max:100";
            }

            // Adiciona validação dos celulares (8 itens)
            foreach ($this->celulares as $index => $c) {
                $rulesMap[$type]["form.celulares.{$index}.bateria"] = 'required|numeric|min:2|max:100';
               // $rulesMap[$type]["form.celulares.{$index}.funcionario"] = 'required|string|min:3';
            }

            // Adiciona validação das chaves mecânicas
            foreach ($this->chavesMec as $index => $apto) {
                $rulesMap[$type]["form.chaves_mecanicas.{$index}.status"] = 'required|in:gaveta,emprestado';
                $rulesMap[$type]["form.chaves_mecanicas.{$index}.pessoa"] = "required_if:form.chaves_mecanicas.{$index}.status,emprestado|nullable|string|min:3|max:100";
            }

            foreach ($this->chavesMec as $index => $apto) {
                $rulesMap[$type]["form.chaves_mecanicas.{$index}.status"] = 'required|in:recepcao,pessoa';
                $rulesMap[$type]["form.chaves_mecanicas.{$index}.pessoa"] = "required_if:form.chaves_mecanicas.{$index}.status,pessoa|nullable|string|min:3|max:100";
            }
        }
        
        return $rulesMap[$type] ?? [];
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
