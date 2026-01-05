<div>
    <div wire:poll.10s="poll"></div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        let messageAlertOpen = false;

        Livewire.on('global-new-message', () => {
            if (messageAlertOpen) return;

            messageAlertOpen = true;

            Swal.fire({
                title: 'Nova mensagem 📩',
                text: 'Você recebeu uma nova mensagem.',
                icon: 'info',
                confirmButtonText: 'Ver mensagem',
                confirmButtonColor: '#22c55e',
                allowOutsideClick: false,
                allowEscapeKey: true,
            }).then((result) => {
                messageAlertOpen = false;

                if (result.isConfirmed) {
                    window.location.href = "{{ route('messages.inbox') }}";
                }
            });
        });
    });
</script>
