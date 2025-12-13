@props([
    'model',
    'value' => ''
])

@php
    $editorId = 'trix_' . uniqid();
@endphp

<div wire:ignore>
    <input
        id="{{ $editorId }}"
        type="hidden"
        wire:model.defer="{{ $model }}"
        value="{{ $value }}"
    >

    <trix-editor
        input="{{ $editorId }}"
        class="border rounded bg-white"
    ></trix-editor>
</div>

@push('scripts')
<script>
(function () {
    const editorId = @js($editorId);
    let loaded = false;

    function initTrix() {
        const input = document.getElementById(editorId);
        const editor = document.querySelector(`trix-editor[input="${editorId}"]`);

        if (!editor || !input) return;

        editor.addEventListener('trix-initialize', () => {
            if (!loaded && input.value) {
                editor.editor.loadHTML(input.value);
                loaded = true;
            }
        });

        editor.addEventListener('trix-change', () => {
            input.dispatchEvent(new Event('input', { bubbles: true }));
        });
    }

    document.readyState === 'loading'
        ? document.addEventListener('DOMContentLoaded', initTrix)
        : initTrix();
})();
</script>
@endpush
