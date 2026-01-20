<div>
    <div class="space-y-4">
        <div>
            <label class="font-semibold">Título</label>
            <input
                type="text"
                wire:model.live="title"
                class="w-full border rounded p-2"
            >
            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <textarea
                rows="18"
                wire:model.live="content"
                class="w-full border rounded p-2 resize-none"
            ></textarea>
            @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end">
            <button
                wire:click="save"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
            >
                Salvar
            </button>
        </div>
    </div>
</div>
