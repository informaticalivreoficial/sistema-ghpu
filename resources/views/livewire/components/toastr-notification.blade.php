<div></div>

@script
<script>
document.addEventListener('livewire:load', function () {
    // Configurações globais do Toastr
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    Livewire.on('toastr', event => {
        const { type, message, title } = event;
        toastr[type](message, title);
    });
});
</script>
@endscript

{{-- Listener para session flash --}}
@if(session()->has('toastr'))
    @script
    <script>
        const toastrData = @json(session('toastr'));
        toastr[toastrData.type](toastrData.message, toastrData.title);
    </script>
    @endscript
@endif