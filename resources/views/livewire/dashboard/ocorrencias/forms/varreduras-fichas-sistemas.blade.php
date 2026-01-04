<div>
    <h1 class="text-xl text-center pb-4">Check-list de varreduras de fichas de apartamentos abertos</h1> 
    
    <div class="card">
        <div class="card-body">
            <div class="mb-4 @error('formVarreduras.horario') border border-danger rounded p-2 @enderror">
                <label class="fw-bold">Horário da conferência:</label><br>

                @foreach (['06h', '14h', '20h'] as $hora)
                    <label class="me-3">
                        <input type="radio" wire:model.live="formVarreduras.horario" value="{{ $hora }}">
                        {{ $hora }}
                    </label>
                @endforeach

                @error('formVarreduras.horario')
                    <br><small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <h5 class="mt-4 mb-2 font-bold">Conferência da Ficha Física e Sistema:</h5>

            @php
            $itensFicha = [
                'nome_hospede' => 'Nome do hóspede confere com o sistema?',
                'acompanhantes' => 'Acompanhantes corretamente registrados?',
                'data_entrada' => 'Data de entrada correta?',
                'data_saida' => 'Data de saída prevista correta?',
                'valores_diarias' => 'Valores das diárias conferidos com o sistema?',
                'consumacao' => 'Lançamentos de consumação (frigobar e outros) realizados?',
                'comandas' => 'Comandas lançadas no sistema e carimbadas na ficha?',
                'ficha_assinada' => 'Ficha assinada pelo hóspede na saída?',
                'placa_veiculo' => 'Placa do veículo registrada corretamente?',
                'observacoes' => 'Observações da ficha conferidas com o sistema?',
                'cnpj_nf' => 'CNPJ informado para emissão de Nota Fiscal?',
            ];
            @endphp

            @foreach ($itensFicha as $key => $label)
                <div class="mb-2 p-2 border rounded">

                    <div class="fw-semibold mb-1">{{ $label }}</div>

                    <label class="me-3">
                        <input
                            type="radio"
                            wire:model.live="formVarreduras.conferencia_ficha.{{ $key }}.status"
                            value="sim"
                        >
                        Sim
                    </label>

                    <label>
                        <input
                            type="radio"
                            wire:model.live="formVarreduras.conferencia_ficha.{{ $key }}.status"
                            value="nao"
                        >
                        Não
                    </label>

                    @if (
                        data_get($formVarreduras, "conferencia_ficha.$key.status") === 'nao'
                    )
                        <div class="mt-2">
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Informe o motivo"
                                wire:model.blur="formVarreduras.conferencia_ficha.{{ $key }}.motivo"
                            >
                        </div>
                    @endif

                </div>
            @endforeach

            <h5 class="mt-4 mb-2 font-bold">Conferência Adicional:</h5>

            @php
            $itensAdicionais = [
                'chaves_veiculos' => 'Chaves dos veículos etiquetadas corretamente?',
                'codigo_cores' => 'Códigos de cores conferem com o padrão?',
            ];
            @endphp

            @foreach ($itensAdicionais as $key => $label)
                <div class="mb-2 p-2 border rounded">

                    <div class="fw-semibold mb-1">{{ $label }}</div>

                    <label class="me-3">
                        <input
                            type="radio"
                            wire:model.live="formVarreduras.conferencia_adicional.{{ $key }}.status"
                            value="sim"
                        >
                        Sim
                    </label>

                    <label>
                        <input
                            type="radio"
                            wire:model.live="formVarreduras.conferencia_adicional.{{ $key }}.status"
                            value="nao"
                        >
                        Não
                    </label>

                    @if (
                        data_get($formVarreduras, "conferencia_adicional.$key.status") === 'nao'
                    )
                        <div class="mt-2">
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Informe o motivo"
                                wire:model.blur="formVarreduras.conferencia_adicional.{{ $key }}.motivo"
                            >
                        </div>
                    @endif

                </div>
            @endforeach

            <div class="mt-4">
                <label class="fw-bold">Observações sobre o turno:</label>
                <textarea
                    class="form-control"
                    rows="4"
                    wire:model.blur="formVarreduras.observacoes_turno"
                ></textarea>
            </div>

        </div>
    </div>
</div>