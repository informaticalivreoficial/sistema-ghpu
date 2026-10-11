{{--
    Filtros da listagem de ocorrências em uma única linha + botão de cadastro.
    Tipos: apenas os da empresa do usuário. Empresa: só admin/super. Status: só manager+.
--}}
<div class="d-flex flex-wrap align-items-center gap-2">
    <select wire:model.live="filterType" class="form-control form-control-sm" style="width: auto; min-width: 170px;">
        <option value="">Tipo: todos</option>
        @foreach ($typeOptions as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
        @endforeach
    </select>

    @if (!auth()->user()->isEmployee())
        <select wire:model.live="filterStatus" class="form-control form-control-sm" style="width: auto; min-width: 120px;">
            <option value="">Status: todos</option>
            @foreach ($statusOptions as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
    @endif

    @if (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
        <select wire:model.live="filterCompany" class="form-control form-control-sm" style="width: auto; min-width: 150px;">
            <option value="">Empresa: todas</option>
            @foreach ($companyOptions as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
            @endforeach
        </select>
    @endif

    <select wire:model.live="filterAuthor" class="form-control form-control-sm" style="width: auto; min-width: 150px;">
        <option value="">Autor: todos</option>
        @foreach ($authorOptions as $id => $name)
            <option value="{{ $id }}">{{ $name }}</option>
        @endforeach
    </select>

    <div class="input-group input-group-sm" style="width: 155px;">
        <div class="input-group-prepend">
            <span class="input-group-text">De</span>
        </div>
        <input type="date" wire:model.live="filterDateFrom" class="form-control">
    </div>

    <div class="input-group input-group-sm" style="width: 155px;">
        <div class="input-group-prepend">
            <span class="input-group-text">Até</span>
        </div>
        <input type="date" wire:model.live="filterDateTo" class="form-control">
    </div>

    @if ($filterType !== '' || $filterStatus !== '' || $filterCompany !== '' || $filterAuthor !== '' || $filterDateFrom !== '' || $filterDateTo !== '')
        <button type="button" wire:click="clearFilters" class="btn btn-sm btn-outline-secondary" title="Limpar filtros">
            <i class="fas fa-times"></i>
        </button>
    @endif

    <div class="ml-auto">
        <a href="{{ route('ocorrencia.create') }}" class="btn btn-sm btn-default">
            <i class="fas fa-plus mr-1"></i> Cadastrar Ocorrência
        </a>
    </div>
</div>
