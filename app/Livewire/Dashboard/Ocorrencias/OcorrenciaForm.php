<?php

namespace App\Livewire\Dashboard\Ocorrencias;

use App\Models\Ocorrencia;
use App\Models\OcorrenciaTemplate;
use App\Traits\WithToastr;
use App\Models\User;
use App\Notifications\OcorrenciaCriada;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;

class OcorrenciaForm extends Component
{
    use WithFileUploads;
    use WithToastr;

    public ?Ocorrencia $ocorrencia = null;

    public $type = '';
    public $destinatario = null;
    public $celulares = [];
    public $chavesMec = [];
    public $content = '';
    public $tituloContent = '';
    public $title = '';
    public $company_id;

    public array $types = [];

    protected $messages = [
        'destinatario' => 'nome do funcionário que vai assumir o turno',
        'form.sauna' => 'Sauna',
        'form.chama_aquecedor' => 'Chama do aquecedor',
        'form.aquecedor_ala_rua' => 'Selecione uma opção',
        'form.gas1' => 'Informe o nível de Gás 1',
        'form.gas2' => 'Informe o nível de Gás 2',
        'form.lixeira_cavalo' => 'Informe se a lixeira está vazia',
        'form.motor_piscina' => 'Motor da piscina',
        'form.motor_ofuro' => 'Motor Ofurô',
        'form.piscina_coberta' => 'Piscina coberta',
        'form.ibrain_fechado' => 'Salão IBrain',
        'form.ac_ibrain' => 'Ar condicionado iBrain',
        'form.luzes_tv' => 'Luzes da TV',
        'form.tv_3andar' => 'TV do 3º andar',
        'form.temperatura_aquecedor' => 'Temperatura do aquecedor',
        'form.nome' => 'Nome',
        //'form.title' => 'Título',
        'type' => 'Tipo de Ocorrência',

        'form.cartao_1_cavalo' => 'Informe onde está o cartão 1',
        'form.cartao_2_cavalo' => 'Informe onde está o cartão 2',
        'form.cartao_datacard1_cavalo' => 'Informe onde está o cartão DataCard 1',
        'form.cartao_datacard2_cavalo' => 'Informe onde está o cartão DataCard 2',
        'form.cartao_manutencao_cavalo' => 'Informe onde está o cartão da manutenção',

        'title.required' => 'O título é obrigatório.',
        'title.string' => 'O título deve ser um texto.',
        'title.min' => 'O título deve ter no mínimo 3 caracteres.',
        'title.max' => 'O título deve ter no máximo 255 caracteres.',
        'content.required' => 'O conteúdo é obrigatório.',
        'content.string' => 'O conteúdo deve ser um texto.',
        'content.min' => 'O conteúdo deve ter no mínimo 10 caracteres.',

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
        
        //'form.tvbox_103.required' => 'Informe o status do TV Box do apto 103.',
        //'form.tvbox_201.required' => 'Informe o status do TV Box do apto 201.',
        //'form.tvbox_203.required' => 'Informe o status do TV Box do apto 203.',
        //'form.tvbox_204.required' => 'Informe o status do TV Box do apto 204.',

        'form.cartoes_camareira_cavalo.*.status.required' => 'Selecione uma opção',
        'form.cartoes_camareira_cavalo.*.funcionario.required_if' => 'Informe com quem está o cartão.',

        'form.chaves_cavalo.*.required' => 'Selecione uma opção',

        'form.chave_apto.*.required_if' => 'Informe o nº do apartamento.',

        'form.rouparia.required' => 'Selecione aberto ou fechado',
        'form.portao_praia.required' => 'Selecione aberto ou fechado',
        'form.portao_bicicletario.required' => 'Selecione aberto ou fechado',
        'form.portao_entrega.required' => 'Selecione aberto ou fechado',
        'form.porta_recepcao.required' => 'Selecione aberto ou fechado',

        'form.celular1_bateria.required' => 'Informe a porcentagem da bateria.',
        'form.celular2_bateria.required' => 'Informe a porcentagem da bateria.',
        

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

        // Seção 9 - Chaves
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
        'form.turno.hospedes.min' => 'O número mínimo de hóspedes é 1.',
        
        'form.turno.apto_ocupados.required' => 'Informe o número de apartamentos ocupados.',
        'form.turno.apto_ocupados.numeric' => 'Deve ser um número.',
        'form.turno.apto_ocupados.min' => 'O número mínimo é 1.',
        
        'form.turno.reservas.required' => 'Informe o número de reservas.',
        'form.turno.reservas.numeric' => 'Deve ser um número.',
        'form.turno.reservas.min' => 'O número mínimo é 1.',
        
        'form.turno.checkouts.required' => 'Informe o número de checkouts.',
        'form.turno.checkouts.numeric' => 'Deve ser um número.',
        'form.turno.checkouts.min' => 'O número mínimo é 1.',

        'form.turno.latecheckouts.required' => 'Informe o número de Late checkouts.',
        'form.turno.latecheckouts.numeric' => 'Deve ser um número.',
        'form.turno.latecheckouts.min' => 'O número mínimo é 1.',
        
        'form.turno.interditados.required' => 'Informe o número de apartamentos interditados.',
        'form.turno.interditados.numeric' => 'Deve ser um número.',
        'form.turno.interditados.min' => 'O número mínimo é 0.',
        
        'form.turno.cartoes_emprestados.required' => 'Informe a quantidade de cartões emprestados.',
        'form.turno.cartoes_emprestados.numeric' => 'Deve ser um número.',
        'form.turno.cartoes_emprestados.min' => 'O número mínimo é 0.',
        
        'form.turno.cartoes_aguardando.required' => 'Informe a quantidade de cartões aguardando.',
        'form.turno.cartoes_aguardando.numeric' => 'Deve ser um número.',
        'form.turno.cartoes_aguardando.min' => 'O número mínimo é 0.',

        'form.turno.paes.required' => 'Informe a quantidade de pães.',
        
        'form.turno.luzes_calcada.required' => 'Informe o status das luzes da calçada.',
        'form.turno.luzes_calcada.in' => 'O status deve ser "ligada" ou "desligada".',

        'form.turno.luzes_calcada_cavalo.required' => 'Informe o status das luzes.',
        'form.turno.luzes_calcada_cavalo.in' => 'O status deve ser "ligada" ou "desligada".',
        
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

        'formVarreduras.horario.required' => 'Informe o horário da varredura.',
        'formVarreduras.horario.in' => 'O horário deve ser 06h, 14h ou 20h.',

    ];

    public array $itensChavesFixasCavalo = [
        1 => 'Rouparia',
        2 => 'Depósito de Água',
        3 => 'Depósito Camareiras',
        4 => 'Bicicletário',
        5 => 'Chave 1 Portão Praia',
        6 => 'Chave 2 Portão Praia',
        7 => 'Chave Mestra',
        8 => 'Chave Manutenção/ Eletrônicos',
        9 => 'Aquecedor',
        10 => 'Chave Copa Mamãe',
        11 => 'Chave Ala Mar Armário',
        12 => 'Sala da Gerência',
        13 => 'Sala Depósito (Antiga Gerência)',
        14 => 'Chave Portão de Entrega',
        15 => 'Chave porta da manutenção',
    ];

   
    public array $itensChavesFixas = [
        1 => 'Chave Mestra Elevador',
        2 => 'Molho de Chave Mestra de Todos os Aptos',
        3 => 'Cartão TAG - Sala de Eventos Ibrain',
        4 => 'Chave da Porta de Vidro da Recepção',
        5 => 'Chave da Lixeira (2)',
        6 => 'Chave Porta Sauna',
        7 => 'Chave Porta Manutenção',
        8 => 'Chave Cadeado Bike Roxa',
        9 => 'Chave Cadeado Bike Vermelha',
        10 => 'Chave HUB 3° Andar',
        11 => 'Chave Porta Automática Recepção Entrada',
        12 => 'Chave (Vareta) Abertura do P2',
        13 => 'Chave Cartão Magnético Rouparia 3° Andar',
        14 => 'Chave da máquina do café da manhã',
        15 => 'Chave da Academia',
    ];

    public array $formVarreduras = [
        'horario' => null,

        'conferencia_ficha' => [
            'nome_hospede' => [
                'status' => null, // sim | nao
                'motivo' => '',
            ],
            'acompanhantes' => [
                'status' => null,
                'motivo' => '',
            ],
            'data_entrada' => [
                'status' => null,
                'motivo' => '',
            ],
            'data_saida' => [
                'status' => null,
                'motivo' => '',
            ],
            'valores_diarias' => [
                'status' => null,
                'motivo' => '',
            ],
            'consumacao' => [
                'status' => null,
                'motivo' => '',
            ],
            'comandas' => [
                'status' => null,
                'motivo' => '',
            ],
            'ficha_assinada' => [
                'status' => null,
                'motivo' => '',
            ],
            'placa_veiculo' => [
                'status' => null,
                'motivo' => '',
            ],
            'observacoes' => [
                'status' => null,
                'motivo' => '',
            ],
            'cnpj_nf' => [
                'status' => null,
                'motivo' => '',
            ],
        ],

        'conferencia_adicional' => [
            'chaves_veiculos' => [
                'status' => null,
                'motivo' => '',
            ],
            'codigo_cores' => [
                'status' => null,
                'motivo' => '',
            ],
        ],

        'observacoes_turno' => '',
    ];
    

    public array $form = [   
        //Cavalo Marinho
        'politicas_importantes' => null,
        
        'rouparia' => null,
        'porta_praia' => null,
        'portao_bicicletario' => null,
        'portao_entrega' => null,
        'porta_recepcao' => null,
        'luzes_calcada_cavalo' => null,

        'celular1_bateria' => null,
        'celular2_bateria' => null,
        'celular1_funcionario' => null,
        'celular2_funcionario' => null,
        'aquecedor_ala_rua' => null,
        'gas1' => null,
        'gas2' => null,
        'lixeira_cavalo' => null,
        'motor_ofuro' => null,
        'cartao_1_cavalo' => null,
        'cartao_2_cavalo' => null,
        'cartao_datacard1_cavalo' => null,
        'cartao_datacard2_cavalo' => null,
        'cartao_manutencao_cavalo' => null,
        'geladeira_recepcao' => null,

        'chaves_cavalo' => [
            1 => null, 2 => null, 3 => null, 4 => null, 5 => null,
            6 => null, 7 => null, 8 => null, 9 => null, 10 => null,
            11 => null, 12 => null, 13 => null, 14 => null, 15 => null,
            16 => null, 17 => null, 18 => null, 19 => null, 20 => null,
        ],

        'chave_apto' => [
            1 => null, 2 => null, 3 => null, 4 => null, 5 => null,
            6 => null, 7 => null, 8 => null, 9 => null, 10 => null,
            11 => null, 12 => null, 13 => null, 14 => null, 15 => null,
            16 => null, 17 => null, 18 => null, 19 => null, 20 => null,
        ],

        'cartoes_camareira_cavalo' => [
            1 => ['status' => null, 'funcionario' => ''],
            2 => ['status' => null, 'funcionario' => ''],
            3 => ['status' => null, 'funcionario' => ''],
            4 => ['status' => null, 'funcionario' => ''],
            5 => ['status' => null, 'funcionario' => ''],
            6 => ['status' => null, 'funcionario' => ''],
            7 => ['status' => null, 'funcionario' => ''],
        ],

        //Fim Cavalo Marinho

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
        'porta_academia' => null,

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
        //'tvbox_103' => null,
        //'tvbox_201' => null,
        //'tvbox_203' => null,
        //'tvbox_204' => null,

        //Seção 6
        'secadores' => [
            1 => null, 2 => null, 3 => null, 4 => null, 5 => null,
        ],
        'secadores_apto' => [
            1 => '', 2 => '', 3 => '', 4 => '',  5 => '',
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
            'latecheckouts' => null,
            'paes' => null,
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

        $user = auth()->user();

        // Todos veem isso select de ocorrencias
        $this->types = [
            'branco' => 'Em Branco',
            //'passagem-de-turno-cavalo'   => 'Passagem de Turno',
        ];

        if ((int) $user->company_id === 21 || $user->isAdmin() || $user->isSuperAdmin()) {
            $this->types += [
                'passagem-de-turno-cavalo'   => 'Passagem de Turno'                
            ];
        }

        // Apenas company 18 vê os demais
        if ((int) $user->company_id === 18 || $user->isAdmin() || $user->isSuperAdmin()) {
            $this->types += [
                'varreduras-fichas-sistemas' => 'Varreduras de fichas x sistemas',
                'ocorrencias-diarias'        => 'Ocorrências Diárias',
                'passagem-de-turno'          => 'Passagem de Turno',                
            ];
        }

        if (empty($this->form['geladeira_recepcao'])) {
            $this->form['geladeira_recepcao'] = implode("\n", [
                '· Água com Gás: 00',
                '· Água sem Gás: 00',
                '· Coca-Cola: 00',
                '· Coca-Cola Zero: 00',
                '· Sprite: 00',
                '· Soda Limonada: 00',
                '· Tônica: 00',
                '· Soda Zero: 00',
                '· Fanta Laranja: 00',
                '· Fanta Uva: 00',
                '· Guaraná: 00',
                '· Guaraná Zero: 00',
                '· Heineken: 00',
                '· Corona: 00',
                '· Império: 00',
                '· Budweiser: 00',
            ]);
        }

        // SEGUNDO: Se for edição, carrega e SOBRESCREVE os dados
        if ($id) {
            $this->ocorrencia = Ocorrencia::findOrFail($id);            
            
            $this->type = $this->ocorrencia->type;
            $this->title = $this->ocorrencia->title;
            $this->destinatario = $this->ocorrencia->destinatario;
            $this->content = $this->ocorrencia->content ?? '';
            $this->form = $this->ocorrencia->form ?? [];

            // 🔹 Preenche formVarreduras se for checklist
            if ($this->ocorrencia->type === 'varreduras-fichas-sistemas') {

                // Garante estrutura base
                $this->formVarreduras = array_replace_recursive(
                    $this->formVarreduras,
                    $this->ocorrencia->form ?? []
                );
            }
            // 🔥 NORMALIZA AS CHAVES AQUI (OBRIGATÓRIO)
            $this->normalizeChaves();
        } 
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
            ['id' => 1, 'titulo' => 'Recepção 1080'],
            ['id' => 2, 'titulo' => 'Recepção 9664'],
            ['id' => 3, 'titulo' => 'Manutenção 01'],
            ['id' => 4, 'titulo' => 'Manutenção 02'],
            ['id' => 5, 'titulo' => 'Governança 01'],
            ['id' => 6, 'titulo' => 'Governança 02'],
            ['id' => 7, 'titulo' => 'Governança 03'],
            ['id' => 8, 'titulo' => 'Governança 04'],
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
            
            // 🔒 BLOQUEIO DE EDIÇÃO APÓS 6 HORAS (SÓ SE FOR EDIÇÃO)
            if ($this->ocorrencia && $this->ocorrencia->id) {

                $user = auth()->user();

                if (! $this->ocorrencia->canBeEditedBy($user)) {
                    $this->dispatch('swal', [
                        'title' => 'Edição bloqueada',
                        'text'  => 'Após 6 horas, colaboradores não podem mais editar a ocorrência.',
                        'icon'  => 'warning',
                    ]);

                    return;
                }
            }

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
                'company_id' => 'nullable|exists:companies,id',            
            ];

            if($this->type === 'passagem-de-turno') {
                $rules['form'] = 'required|array';
                $rules['destinatario'] = 'required|string|min:6|max:100';                
            }

            if($this->type === 'passagem-de-turno-cavalo') {
                $rules['form'] = 'required|array';
                $rules['destinatario'] = 'required|string|min:6|max:100';                
            }

            if ($this->type === 'ocorrencias-diarias') {                
                $rules['content'] = 'required|string|min:10';
            }

            if ($this->type === 'branco') {
                $rules['title'] = 'required|string|min:3|max:255';
                $rules['content'] = 'required|string|min:10';
            }            
            
            // Adiciona regras específicas baseadas no tipo
            $rules = array_merge($rules, $this->getRulesForType($this->type));
            
            $this->validate($rules);

            $data = [
                'company_id'   => auth()->user()->company_id,
                'user_id'      => auth()->id(),
                'type'         => $this->type,                               
                'status'       => 1,
            ];
            
            if ($this->type === 'ocorrencias-diarias') {   
                $data['title']        = $this->titleFromType($this->type);             
                $data['content']      = $this->content;
                $data['destinatario'] = null;
                $data['form']         = null;
            }

            if($this->type === 'passagem-de-turno') {
                $data['title']   = $this->titleFromType($this->type);
                $data['destinatario'] = $this->destinatario;
                $data['content'] = null;
                $data['form'] = $this->form;
            }

            if($this->type === 'passagem-de-turno-cavalo') {
                $data['title']   = $this->titleFromType($this->type);
                $data['destinatario'] = $this->destinatario;
                $data['content'] = $this->content;
                $data['form'] = $this->form;
            }

            if($this->type === 'branco') {
                $data['title'] = $this->title;
                $data['destinatario'] = null;
                $data['content'] = $this->content;
                $data['form'] = null;
            }

            if ($this->type === 'varreduras-fichas-sistemas') {
                $data['title'] = $this->titleFromType($this->type);
                $data['destinatario'] = null;
                $data['content'] = null;
                $data['form'] = $this->formVarreduras;                
            }

            // Adiciona company_id e user_id APENAS se for CRIAÇÃO
            if (!$this->ocorrencia || !$this->ocorrencia->id) {
                // Se for SuperAdmin ou Admin, permite criar sem empresa (ou com empresa selecionada)
                if (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin()) {
                    $data['company_id'] = $this->company_id ?? null; // Pode ser null
                } else {
                    // Manager e Employee DEVEM usar a empresa deles
                    $data['company_id'] = auth()->user()->company_id;
                }
                
                $data['user_id'] = auth()->id();

                $ocorrencia = Ocorrencia::create($data);
            } else{
                //dd($data, $this->validate($rules));
                $ocorrencia = $this->ocorrencia;

                $ocorrencia->update(
                    collect($data)
                        ->except([
                            'company_id',
                            'user_id',
                            'status',
                        ])
                        ->merge([
                            'update_user_id' => auth()->id(),
                        ])
                        ->toArray()
                );
            }
                        
            // $ocorrencia = Ocorrencia::updateOrCreate(
            //     ['id' => $this->ocorrencia->id ?? null],
            //     $data
            // );

            $this->ocorrencia = $ocorrencia;
            // Mensagem de sucesso diferente para criar/editar
            $foiCriacao = $this->ocorrencia->wasRecentlyCreated;

            // Envia notificação APENAS para criação
            if ($foiCriacao) {

                $author = auth()->user();

                // Super Admin e Admin sempre recebem
                $superAdmins = User::role('super-admin')->get();
                $admins      = User::role('admin')->get();

                // Destinatários da empresa (definidos pela role de quem criou)
                if ($author->hasRole('employee')) {

                    // 🔹 Colaborador → só managers da empresa
                    $companyUsers = User::role('manager')
                        ->where('company_id', $ocorrencia->company_id)
                        ->get();

                } elseif ($author->hasRole('manager')) {

                    // 🔹 Manager → colaboradores da empresa
                    $companyUsers = User::role('employee')
                        ->where('company_id', $ocorrencia->company_id)
                        ->get();

                } else {

                    // 🔹 Admin / Super → managers + colaboradores da empresa
                    $companyUsers = User::role(['manager', 'employee'])
                        ->where('company_id', $ocorrencia->company_id)
                        ->get();
                }

                // Junta todos
                $users = $superAdmins->concat($admins)->concat($companyUsers)

                    // ❌ remove quem criou
                    ->reject(fn ($user) => $user->id === $author->id)

                    // ❌ evita duplicados
                    ->unique('id');

                Notification::send(
                    $users,
                    new OcorrenciaCriada($ocorrencia, $author)
                );
            }            

            $mensagem = $foiCriacao 
                ? 'Ocorrência cadastrada com sucesso!' 
                : 'Ocorrência atualizada com sucesso!';

            $this->dispatch('swal-redirect', [
                'title' => 'Sucesso!',
                'text' => $mensagem,
                'icon' => 'success',
                'redirect' => $foiCriacao ? route('ocorrencias.index') : null, // ← null = não redireciona
            ]);            
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            //dd($e->errors());
            $errorCount = count($e->validator->errors()->all());

            $this->dispatch('scroll-to-top');
            
            // Só dispara toast se tiver mais de 1 erro
            if ($errorCount > 1) {  
                $this->toastError("Por favor, preencha os {$errorCount} campos destacados no formulário.");
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
                'form.porta_academia' => 'required|in:aberta,fechada',

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
                //'form.tvbox_103' => 'required|in:pote,uso',
                //'form.tvbox_201' => 'required|in:pote,uso',
                //'form.tvbox_203' => 'required|in:pote,uso',
                //'form.tvbox_204' => 'required|in:pote,uso',

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
                //'form.content' => 'required|string|min:10',
            ],
            'varreduras-fichas-sistemas' => [
                // Horário da conferência
                'formVarreduras.horario' => 'required|in:06h,14h,20h',

                // Conferência da ficha
                'formVarreduras.conferencia_ficha' => 'required|array',
                'formVarreduras.conferencia_ficha.*.status' => 'required|in:sim,nao',
                'formVarreduras.conferencia_ficha.*.motivo' =>
                    'required_if:formVarreduras.conferencia_ficha.*.status,nao',

                // Conferência adicional
                'formVarreduras.conferencia_adicional' => 'required|array',
                'formVarreduras.conferencia_adicional.*.status' => 'required|in:sim,nao',
                'formVarreduras.conferencia_adicional.*.motivo' =>
                    'required_if:formVarreduras.conferencia_adicional.*.status,nao',

                // Observações do turno
                'formVarreduras.observacoes_turno' => 'nullable|string|max:1000',
            ],
            'branco' => [
                // Regras específicas para formulário em branco                
            ],
            'passagem-de-turno-cavalo' => [
                'form.maquina_safra_1' => 'required|numeric|min:1|max:100',
                'form.maquina_safra_2' => 'required|numeric|min:1|max:100',
                'form.turno.hospedes' => 'required|numeric|min:0',
                'form.turno.apto_ocupados' => 'required|numeric|min:0',
                'form.turno.reservas' => 'required|numeric|min:0',
                'form.turno.checkouts' => 'required|numeric|min:0',
                'form.turno.latecheckouts' => 'required|numeric|min:0',
                'form.turno.paes' => 'required|string',
                'form.cartao_1_cavalo' => 'required|string',
                'form.cartao_2_cavalo' => 'required|string',
                'form.cartao_datacard1_cavalo' => 'required|string',
                'form.turno.luzes_calcada_cavalo' => 'required|in:ligada,desligada',
                'form.cartao_datacard2_cavalo' => 'required|string',
                'form.cartao_manutencao_cavalo' => 'required|string',
                'form.rouparia' => 'required|in:aberto,fechado',
                'form.portao_praia' => 'required|in:aberto,fechado',
                'form.portao_bicicletario' => 'required|in:aberto,fechado',
                'form.portao_entrega' => 'required|in:aberto,fechado',
                'form.porta_recepcao' => 'required|in:aberto,fechado',
                'form.celular1_bateria' => 'required|numeric|min:1|max:100',
                'form.celular2_bateria' => 'required|numeric|min:1|max:100',  
                'form.temperatura_aquecedor' => 'required|numeric|min:0',  
                'form.chama_aquecedor' => 'required|in:aberto,fechado',
                'form.aquecedor_ala_rua' => 'required|in:aberto,fechado',
                'form.gas1' => 'required|string',
                'form.gas2' => 'required|string',
                'form.lixeira_cavalo' => 'required|in:sim,nao',
                'form.motor_piscina' => 'required|in:ligado,desligado',
                'form.motor_ofuro' => 'required|in:ligado,desligado',
                
                
                'form.turno.luzes_calcada_cavalo' => 'required|in:ligada,desligada',                
                'form.turno.codigo_cores' => 'required|in:sim,nao',
                'form.turno.caixa_dinheiro' => 'required|numeric|min:0',
                'form.turno.caixa_cartoes' => 'required|numeric|min:0',
                'form.turno.caixa_faturamento' => 'required|numeric|min:0',

                //Chaves Extras
                'form.chaves_cavalo.1' => 'required|in:disponivel,emprestado',
                'form.chaves_cavalo.2' => 'required|in:disponivel,emprestado',
                'form.chaves_cavalo.3' => 'required|in:disponivel,emprestado',
                'form.chaves_cavalo.4' => 'required|in:disponivel,emprestado',
                'form.chaves_cavalo.5' => 'required|in:disponivel,emprestado',
                'form.chaves_cavalo.6' => 'required|in:disponivel,emprestado',
                'form.chaves_cavalo.7' => 'required|in:disponivel,emprestado',
                'form.chaves_cavalo.8' => 'required|in:disponivel,emprestado',
                'form.chaves_cavalo.9' => 'required|in:disponivel,emprestado',
                'form.chaves_cavalo.10' => 'required|in:disponivel,emprestado',
                'form.chaves_cavalo.11' => 'required|in:disponivel,emprestado',
                'form.chaves_cavalo.12' => 'required|in:disponivel,emprestado',
                'form.chaves_cavalo.13' => 'required|in:disponivel,emprestado',
                'form.chaves_cavalo.14' => 'required|in:disponivel,emprestado',
                'form.chaves_cavalo.15' => 'required|in:disponivel,emprestado',
                'form.chaves_cavalo.16' => 'required|in:disponivel,emprestado',
                'form.chaves_cavalo.17' => 'required|in:disponivel,emprestado',
                'form.chaves_cavalo.18' => 'required|in:disponivel,emprestado',
                'form.chaves_cavalo.19' => 'required|in:disponivel,emprestado',
                'form.chaves_cavalo.20' => 'required|in:disponivel,emprestado',

                'form.chave_apto.1' => 'required_if:form.chaves_cavalo.1,emprestado|nullable|string|min:1',
                'form.chave_apto.2' => 'required_if:form.chaves_cavalo.2,emprestado|nullable|string|min:1',
                'form.chave_apto.3' => 'required_if:form.chaves_cavalo.3,emprestado|nullable|string|min:1',
                'form.chave_apto.4' => 'required_if:form.chaves_cavalo.4,emprestado|nullable|string|min:1',
                'form.chave_apto.5' => 'required_if:form.chaves_cavalo.5,emprestado|nullable|string|min:1',
                'form.chave_apto.6' => 'required_if:form.chaves_cavalo.6,emprestado|nullable|string|min:1',
                'form.chave_apto.7' => 'required_if:form.chaves_cavalo.7,emprestado|nullable|string|min:1',
                'form.chave_apto.8' => 'required_if:form.chaves_cavalo.8,emprestado|nullable|string|min:1',
                'form.chave_apto.9' => 'required_if:form.chaves_cavalo.9,emprestado|nullable|string|min:1',
                'form.chave_apto.10' => 'required_if:form.chaves_cavalo.10,emprestado|nullable|string|min:1',
                'form.chave_apto.11' => 'required_if:form.chaves_cavalo.11,emprestado|nullable|string|min:1',
                'form.chave_apto.12' => 'required_if:form.chaves_cavalo.12,emprestado|nullable|string|min:1',
                'form.chave_apto.13' => 'required_if:form.chaves_cavalo.13,emprestado|nullable|string|min:1',
                'form.chave_apto.14' => 'required_if:form.chaves_cavalo.14,emprestado|nullable|string|min:1',
                'form.chave_apto.15' => 'required_if:form.chaves_cavalo.15,emprestado|nullable|string|min:1',
                'form.chave_apto.16' => 'required_if:form.chaves_cavalo.16,emprestado|nullable|string|min:1',
                'form.chave_apto.17' => 'required_if:form.chaves_cavalo.17,emprestado|nullable|string|min:1',
                'form.chave_apto.18' => 'required_if:form.chaves_cavalo.18,emprestado|nullable|string|min:1',
                'form.chave_apto.19' => 'required_if:form.chaves_cavalo.19,emprestado|nullable|string|min:1',
                'form.chave_apto.20' => 'required_if:form.chaves_cavalo.20,emprestado|nullable|string|min:1',                

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
                'form.cartoes_camareira_cavalo.1.status' => 'required|in:disponivel,funcionario',
                'form.cartoes_camareira_cavalo.1.funcionario' => 'required_if:form.cartoes_camareira_cavalo.1.status,funcionario|nullable|string|min:3',
                'form.cartoes_camareira_cavalo.2.status' => 'required|in:disponivel,funcionario',
                'form.cartoes_camareira_cavalo.2.funcionario' => 'required_if:form.cartoes_camareira_cavalo.2.status,funcionario|nullable|string|min:3',
                'form.cartoes_camareira_cavalo.3.status' => 'required|in:disponivel,funcionario',
                'form.cartoes_camareira_cavalo.3.funcionario' => 'required_if:form.cartoes_camareira_cavalo.3.status,funcionario|nullable|string|min:3',
                'form.cartoes_camareira_cavalo.4.status' => 'required|in:disponivel,funcionario',
                'form.cartoes_camareira_cavalo.4.funcionario' => 'required_if:form.cartoes_camareira_cavalo.4.status,funcionario|nullable|string|min:3',
                'form.cartoes_camareira_cavalo.5.status' => 'required|in:disponivel,funcionario',
                'form.cartoes_camareira_cavalo.5.funcionario' => 'required_if:form.cartoes_camareira_cavalo.5.status,funcionario|nullable|string|min:3',               
                'form.secadores.1' => 'required|in:gaveta,emprestado',
                'form.secadores.2' => 'required|in:gaveta,emprestado',
                'form.secadores_apto.1' => 'required_if:form.secadores.1,emprestado|nullable|string',
                'form.secadores_apto.2' => 'required_if:form.secadores.2,emprestado|nullable|string',
            ]
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

        if($type === 'passagem-de-turno-cavalo'){
            foreach ($this->itensChavesFixasCavalo as $key => $label) {
                $rulesMap[$type]["form.chaves.{$key}.status"] = 'required|in:gaveta,com';
                $rulesMap[$type]["form.chaves.{$key}.pessoa"] = "required_if:form.chaves.{$key}.status,com|nullable|string|min:3|max:100";
            }
        }
        
        return $rulesMap[$type] ?? [];
    }
    
    protected function titleFromType(string $type): string
    {
        return match ($type) {
            'passagem-de-turno'          => 'Passagem de Turno',
            'passagem-de-turno-cavalo'   => 'Passagem de Turno',
            'ocorrencias-diarias'        => 'Ocorrências Diárias',
            'varreduras-fichas-sistemas' => 'Varreduras, Fichas e Sistemas',
            //'branco' => 'Formulário em Branco',
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

    public function updatedType($value)
    {
        // Só no cadastro
        if ($this->ocorrencia) {
            return;
        }

        if ($value === 'branco') {
            $this->content = '';
        }

        if ($value === 'ocorrencias-diarias') {
            $this->content = '<p><em style="color:rgb(255, 0, 0); font-size:18px"><strong><span style="background-color:#FFFF00">DURANTE O MEU TURNO DE TRABALHO FICA REGISTRADO QUE:</span></strong></em></p>';
        }

        $template = OcorrenciaTemplate::where('company_id', auth()->user()->company_id)
            ->where('type', $value)
            ->first();
        
        if ($template) {
            $this->content = $template->content;
            $this->tituloContent = $template->title;
        }
    }

    private function enviarNotificacoes(Ocorrencia $ocorrencia)
    {
        $destinatarios = collect();
        
        // Super-admin e Admin recebem TODAS as notificações
        $destinatarios = $destinatarios->merge(
            User::whereHas('roles', function($query) {
                $query->whereIn('name', ['super-admin', 'admin']);
            })->get()
        );
        
        // Se a ocorrência tem empresa, notifica os gerentes dessa empresa
        if ($ocorrencia->company_id) {
            $destinatarios = $destinatarios->merge(
                User::whereHas('roles', function($query) {
                    $query->where('name', 'manager');
                })
                ->where('company_id', $ocorrencia->company_id)
                ->get()
            );
        }
        
        // Remove duplicados e envia
        $destinatarios = $destinatarios->unique('id');
        
        if ($destinatarios->isNotEmpty()) {
            Notification::send($destinatarios, new OcorrenciaCriada($ocorrencia, auth()->user()));
        }
    }

    protected function normalizeChaves(): void
    {
        $fixas = collect($this->itensChavesFixas)
            ->map(fn ($label, $id) => [
                'id'     => $id,
                'label'  => $label,
                'status' => null,
            ]);

        $existentes = collect($this->form['chaves'] ?? []);

        $this->form['chaves'] = $fixas
            ->mapWithKeys(function ($item) use ($existentes) {
                $id = $item['id'];

                return [
                    $id => $existentes->get($id, $item),
                ];
            })
            ->toArray();
    }
    
}
