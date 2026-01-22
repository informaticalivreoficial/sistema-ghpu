<div>
    @section('title', $title)
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-file mr-2"></i>Editar Configuração</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Editar Configuração</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-primary card-outline">
        <div class="card-body text-muted">
            <div class="row mb-3">
                <div class="col-12">
                    <label class="font-semibold">Título</label>
                    <input
                        type="text"
                        wire:model.live="title"
                        class="w-full border rounded p-2"
                    >
                    @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <textarea
                        rows="18"
                        wire:model.live="content"
                        class="w-full border rounded p-2 resize-none"
                    ></textarea>
                    @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12 flex justify-end">
                    <button
                        wire:click="save"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                    >
                        Salvar
                </button>
                </div>
            </div>
        </div>
    </div>
</div>
