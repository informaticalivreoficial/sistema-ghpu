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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

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
        .modal-backdrop {
            display: none !important;
        }

        /* Alinhamento de imagens no editor */
        .ql-editor img {
            display: inline-block;
            max-width: 100%;
            height: auto;
        }
        
        /* Imagem alinhada à esquerda */
        .ql-editor .ql-align-left img,
        .ql-editor p.ql-align-left img {
            display: block;
            margin-left: 0;
            margin-right: auto;
        }
        
        /* Imagem centralizada */
        .ql-editor .ql-align-center img,
        .ql-editor p.ql-align-center img {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Imagem alinhada à direita */
        .ql-editor .ql-align-right img,
        .ql-editor p.ql-align-right img {
            display: block;
            margin-left: auto;
            margin-right: 0;
        }
        
        /* Imagem justificada */
        .ql-editor .ql-align-justify img,
        .ql-editor p.ql-align-justify img {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Estilos para visualização fora do editor */
        .ql-align-center {
            text-align: center;
        }
        
        .ql-align-right {
            text-align: right;
        }
        
        .ql-align-left {
            text-align: left;
        }
        
        .ql-align-justify {
            text-align: justify;
        }
    </style>

    {{-- Livewire Styles --}}
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles') 
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

    @auth
        <livewire:components.global-message-notifier />
        <livewire:components.support-modal />
        <livewire:components.toastr-notification />
    @endauth    

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
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script src="https://cdn.jsdelivr.net/npm/basiclightbox@5/dist/basicLightbox.min.js"></script>

    <!-- Quill Editor CSS -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill-image-resize-module@3.0.0/image-resize.min.js"></script>
    {{-- Livewire Scripts --}}

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('quillEditor', ({ value, model }) => ({
                quill: null,

                init() {
                    if (this.quill) return;

                    // 🔥 Registrar módulo de redimensionamento
                    if (typeof ImageResize !== 'undefined') {
                        Quill.register('modules/imageResize', ImageResize.default);
                    }

                    this.quill = new Quill(this.$refs.editor, {
                        theme: 'snow',
                        placeholder: 'Digite aqui...',
                        modules: {
                            toolbar: {
                                container: [
                                    [{ header: [1, 2, 3, false] }],
                                    [{ font: [] }, { size: ['small', false, 'large', 'huge'] }],
                                    ['bold', 'italic', 'underline', 'strike'],
                                    [{ align: [] }], // ✅ ALINHAMENTO ADICIONADO
                                    [{ color: [] }, { background: [] }],
                                    [{ list: 'ordered' }, { list: 'bullet' }],
                                    ['blockquote'],
                                    ['link', 'image'],
                                    ['clean'],
                                ],
                                handlers: {
                                    image: () => this.imageHandler()
                                }
                            },
                            // 🖼️ Módulo de redimensionamento
                            imageResize: {
                                displaySize: true,
                                modules: ['Resize', 'DisplaySize']
                            }
                        },
                    });

                    if (value) {
                        this.quill.root.innerHTML = value;
                    }

                    this.sync();

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

                imageHandler() {
                    const input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');
                    input.click();

                    input.onchange = async () => {
                        const file = input.files[0];
                        if (file) {
                            await this.uploadImage(file);
                        }
                    };
                },

                async uploadImage(file) {
                    const formData = new FormData();
                    formData.append('image', file);

                    try {
                        const range = this.quill.getSelection(true);
                        this.quill.insertText(range.index, 'Carregando...');

                        const response = await fetch('/livewire/upload-image', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: formData
                        });

                        const data = await response.json();

                        if (data.url) {
                            this.quill.deleteText(range.index, 'Carregando...'.length);
                            this.quill.insertEmbed(range.index, 'image', data.url);
                            this.quill.setSelection(range.index + 1);
                            this.sync();
                        }
                    } catch (error) {
                        console.error('Erro:', error);
                        alert('Erro ao fazer upload');
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

        function showToast(type, message) {
            const colors = {
                success: '#16a34a',
                error: '#dc2626',
                warning: '#f59e0b',
                info: '#2563eb',
            };

            Toastify({
                text: message,
                duration: 4000,
                gravity: "top",
                position: "right",
                close: true,
                style: {
                    background: colors[type] ?? '#2563eb',
                },
            }).showToast();
        }

        // 🔥 Toast via Livewire
        window.addEventListener('toast', event => {
            const { type, message } = event.detail[0];
            showToast(type, message);
        });

        // 🔥 Toast via session (redirect)
        @if (session()->has('toast'))
            document.addEventListener('DOMContentLoaded', () => {
                showToast(
                    "{{ session('toast.type') }}",
                    "{{ session('toast.message') }}"
                );
            });
        @endif
    </script>

    @stack('scripts') 
    
</body>
</html>