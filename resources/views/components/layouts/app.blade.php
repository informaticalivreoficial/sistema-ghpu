<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield( 'title', env('APP_NAME') )</title>

    <link rel="icon" href="{{ asset('theme/images/chave.png')}}" type="image/x-icon">

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="{{ asset('theme/plugins/fontawesome-free/css/all.min.css') }}">
    {{-- Bootstrap 4 --}}
    <link rel="stylesheet" href="{{ asset('theme/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">

    <link rel="stylesheet" href="{{ asset('theme/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    {{-- JQVMap --}}
    <link rel="stylesheet" href="{{ asset('theme/plugins/jqvmap/jqvmap.min.css') }}">

    
    {{-- Theme style --}}
    <link rel="stylesheet" href="{{ asset('theme/dist/css/adminlte.min.css') }}">
    {{-- overlayScrollbars --}}
    <link rel="stylesheet" href="{{ asset('theme/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    {{-- Daterange picker --}}
    <link rel="stylesheet" href="{{ asset('theme/plugins/daterangepicker/daterangepicker.css') }}">
    {{-- summernote --}}
    <link rel="stylesheet" href="{{ asset('theme/plugins/summernote/summernote-bs4.min.css') }}">
    {{-- Tom Select --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    
    {{-- Toastr --}}
    <link rel="stylesheet" href="{{ asset('theme/plugins/toastr/toastr.min.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    {{-- General Styles --}}
    <link rel="stylesheet" href="{{ asset('theme/dist/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/dist/css/action-buttons.css') }}">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/basiclightbox@5/dist/basicLightbox.min.css">

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <style>
        .basicLightbox {
            z-index: 9999 !important;
        }

        .basicLightbox__placeholder {
            z-index: 9999 !important;
        }
        trix-editor:not([data-trix-placeholder]):empty::before,
        trix-editor[data-placeholder]:not(:focus):empty::before,
        trix-editor[data-placeholder]:not(:focus) div:only-child:where(:not([data-trix-placeholder])):where(:not([data-trix-attachment])):where(:not(:has(*)))::before {
            content: attr(data-placeholder);
            color: #999;
            font-style: italic;
        }
        trix-editor {
            min-height: 300px !important;
            padding: 15px;          /* deixa mais confortável para digitar */
            font-size: 16px;        /* texto maior */
            line-height: 1.6;
        }
    </style>

    {{-- Livewire Styles --}}
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="hold-transition sidebar-mini layout-fixed text-sm {{ auth()->user()->isEmployee() ? 'sidebar-closed sidebar-collapse' : '' }}">
    <div class="wrapper">
        <livewire:navigation.top-navigation />

        <livewire:navigation.side-navigation />

        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    {{ $slot }}
                </div>
            </section>
        </div>

        <livewire:navigation.footer />
    </div>

    {{-- jQuery --}}
    <script src="{{ asset('theme/plugins/jquery/jquery.min.js') }}"></script> 

    {{-- Bootstrap 4 --}}
    <script src="{{ asset('theme/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('theme/plugins/sparklines/sparkline.js') }}"></script>

    {{-- JQVMap --}}
    <script src="{{ asset('theme/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    
    {{-- daterangepicker --}}
    <script src="{{ asset('theme/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('theme/plugins/daterangepicker/daterangepicker.js') }}"></script>

    {{-- Tempusdominus Bootstrap 4 --}}
    <script src="{{ asset('theme/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    {{-- Summernote --}}
    <script src="{{ asset('theme/plugins/summernote/summernote-bs4.min.js') }}"></script>
    {{-- Tom Select --}}
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    {{-- overlayScrollbars --}}
    <script src="{{ asset('theme/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

    <script src="{{ asset('theme/dist/js/adminlte.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Toastr --}}
    <script src="{{ asset('theme/plugins/toastr/toastr.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/basiclightbox@5/dist/basicLightbox.min.js"></script>

    <!-- Quill Editor CSS -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    {{-- Livewire Scripts --}}

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('quillEditor', ({ value, model }) => ({
                quill: null,

                init() {
                    if (this.quill) return; // 🔥 evita duplicar editor

                    this.quill = new Quill(this.$refs.editor, {
                        theme: 'snow',
                        placeholder: 'Digite aqui...',
                        modules: {
                            toolbar: [
                                [{ header: [1, 2, 3, false] }],
                                [{ font: [] }, { size: ['small', false, 'large', 'huge'] }],
                                ['bold', 'italic', 'underline', 'strike'],
                                [{ color: [] }, { background: [] }],
                                [{ list: 'ordered' }, { list: 'bullet' }],
                                ['blockquote'],
                                ['link'],
                                ['clean'],
                            ],
                        },
                    });

                    // Conteúdo inicial (edit)
                    if (value) {
                        this.quill.root.innerHTML = value;
                    }

                    // 🔥 SINCRONIZAÇÃO INICIAL (create FIX)
                    this.sync();

                    // Atualização ao digitar
                    this.quill.on('text-change', () => {
                        this.sync();
                    });
                },

                sync() {
                    const html = this.quill.root.innerHTML;
                    const componentEl = this.$el.closest('[wire\\:id]');

                    if (!componentEl || typeof Livewire === 'undefined') return;

                    const component = Livewire.find(componentEl.getAttribute('wire:id'));
                    if (component) {
                        component.set(model, html, false);
                    }
                },
            }));
        });   
        
        document.addEventListener('livewire:init', () => {

            Livewire.on('swal', (params) => {

                const data = params[0] ?? {};

                Swal.fire({
                    title: data.title ?? 'Atenção',
                    text: data.text ?? '',
                    icon: data.icon ?? 'info',
                    confirmButtonText: data.confirmButtonText ?? 'OK',
                });

            });

        });
    </script>

    

    @stack('scripts') 
    
</body>
</html>