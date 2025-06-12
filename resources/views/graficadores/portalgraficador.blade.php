<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-pink-600 dark:text-pink-400 leading-tight">
            {{ __('Portal del Graficador') }}
        </h2>
    </x-slot>

    <style>
        .editor-container {
            display: flex;
            flex-direction: column;
            height: 85vh;
            background-color: #fff0f6;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .editor-tabs {
            display: flex;
            background-color: #fce7f3;
            border-bottom: 2px solid #f9a8d4;
        }

        .editor-tab {
            padding: 12px 24px;
            font-weight: 600;
            color: #9d174d;
            cursor: pointer;
            transition: all 0.2s;
        }

        .editor-tab:hover, .editor-tab.active {
            background-color: #f9a8d4;
        }

        .editor-body {
            display: flex;
            flex-grow: 1;
        }

        #blocks {
            width: 220px;
            background-color: #fbcfe8;
            padding: 12px;
            overflow-y: auto;
        }

        #gjs {
            flex-grow: 1;
            background-color: #fff0f6;
            padding: 12px;
            height: auto;
        }

        .tab-content {
            display: none;
            flex-grow: 1;
            padding: 12px;
        }

        .tab-content.active {
            display: block;
        }

        .gjs-cv-canvas {
            background-color: #fffafc !important;
        }

        .export-button {
            margin-top: 16px;
            padding: 10px 16px;
            background-color: #ec4899;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .export-button:hover {
            background-color: #db2777;
        }
    </style>

    <div class="editor-container">
        <div class="editor-tabs">
            <div class="editor-tab active" id="tab-btn-editor">Editar</div>
            <div class="editor-tab" id="tab-btn-import">Importar Imagen</div>
        </div>

        <div class="editor-body">
            <div id="blocks"></div>

            <!-- Sección de edición -->
            <div class="tab-content active" id="tab-editor">
                <div id="gjs"></div>
                <button class="export-button">Exportar</button>
            </div>

            <!-- Sección de importación -->
            <div class="tab-content" id="tab-import">
                @include('importador')
            </div>
        </div>
    </div>

    <script>
        // Tabs
        const tabEditor = document.getElementById('tab-editor');
        const tabImport = document.getElementById('tab-import');
        const btnEditor = document.getElementById('tab-btn-editor');
        const btnImport = document.getElementById('tab-btn-import');

        btnEditor.addEventListener('click', () => {
            tabEditor.classList.add('active');
            tabImport.classList.remove('active');
            btnEditor.classList.add('active');
            btnImport.classList.remove('active');
        });

        btnImport.addEventListener('click', () => {
            tabEditor.classList.remove('active');
            tabImport.classList.add('active');
            btnEditor.classList.remove('active');
            btnImport.classList.add('active');
        });
    </script>

    <link href="https://unpkg.com/grapesjs/dist/css/grapes.min.css" rel="stylesheet">

    <div class="py-12 bg-pink-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg p-6">
                <h3 class="text-2xl font-bold mb-6 text-pink-600 dark:text-pink-400">Portal del Graficador</h3>

                <div class="container-flex">
                    <!-- Sidebar con Tabs -->
                    <nav class="sidebar-tabs" role="tablist" aria-label="Pestañas del editor">
                        <button class="tab-button active" role="tab" aria-selected="true" aria-controls="tab-editor" id="tab-btn-editor">Editor</button>
                        <button class="tab-button" role="tab" aria-selected="false" aria-controls="tab-import" id="tab-btn-import">Importar Imagen</button>
                    </nav>

                    <!-- Contenido Tabs -->
                    <section id="tab-editor" class="tab-content active" role="tabpanel" aria-labelledby="tab-btn-editor">
                        <div id="blocks"></div>
                        <div id="gjs"></div>
                    </section>

                    <section id="tab-import" class="tab-content" role="tabpanel" aria-labelledby="tab-btn-import">
                        <label for="import-image-input" class="import-image-wrapper" tabindex="0">
                            Haz clic o arrastra aquí para importar una imagen
                            <input type="file" id="import-image-input" accept="image/*" />
                        </label>
                        <p style="margin-top: 12px; color: #9d174d; font-weight: 600;">
                            Las imágenes importadas aparecerán como bloques para arrastrar y usar en el editor.
                        </p>
                    </section>
                </div>

                <a href="{{ route('graficadores.index') }}" class="btn-secondary inline-block">
                    ← Volver a la lista de graficadores
                </a>
            </div>
        </div>
    </div>

    <!-- Botón exportar -->
    <button class="export-button" aria-label="Exportar código Flutter">Exportar Flutter</button>

    <!-- Librerías -->
    <script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
    <script src="https://unpkg.com/grapesjs"></script>
    <script src="https://unpkg.com/grapesjs-blocks-basic"></script>
    <script src="https://unpkg.com/grapesjs-plugin-forms"></script>
    <script src="https://unpkg.com/grapesjs-navbar"></script>
    <script src="https://unpkg.com/grapesjs-tabs"></script>

    <script>
        // Inicializar GrapesJS
        const editor = grapesjs.init({
            container: '#gjs',
            plugins: ['gjs-blocks-basic', 'grapesjs-plugin-forms', 'grapesjs-navbar', 'grapesjs-tabs'],
            pluginsOpts: {
                'gjs-blocks-basic': { flexGrid: true },
                'grapesjs-plugin-forms': {},
                'grapesjs-navbar': {},
                'grapesjs-tabs': {}
            },
            blockManager: {
                appendTo: '#blocks',
                blocks: [
                    {
                        id: 'image',
                        label: 'Imagen',
                        content: '<img src="https://via.placeholder.com/150" alt="Imagen" style="max-width: 100%;">'
                    },
                    {
                        id: 'video',
                        label: 'Video',
                        content: `
                            <div data-gjs-type="default">
                                <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" frameborder="0"
                                        allowfullscreen style="width:100%; height:315px;"></iframe>
                            </div>
                        `
                    },
                    {
                        id: 'button',
                        label: 'Botón',
                        content: '<button class="btn btn-primary">Haz clic aquí</button>'
                    },
                    {
                        id: 'form',
                        label: 'Formulario',
                        content: `
                            <form>
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" class="form-control" id="name" placeholder="Tu nombre">
                                </div>
                                <div class="form-group">
                                    <label for="email">Correo electrónico</label>
                                    <input type="email" class="form-control" id="email" placeholder="Tu correo">
                                </div>
                                <button type="submit" class="btn btn-primary">Enviar</button>
                            </form>
                        `
                    }
                ]
            }
        });

        // Mantener websocket intacto
        const socket = io('http://localhost:3000', {
            transports: ['websocket'],
        });

        socket.on('connect', () => console.log('Conectado al servidor WebSocket'));

        let isUpdating = false;

        const emitGraphUpdate = () => {
            if (!isUpdating) {
                const fullData = editor.getComponents();
                socket.emit('graph-update', { data: fullData });
            }
        };

        editor.on('component:add', emitGraphUpdate);
        editor.on('component:update', emitGraphUpdate);
        editor.on('storage:update', emitGraphUpdate);

        socket.on('graph-update', (data) => {
            isUpdating = true;
            editor.setComponents(data.data);
            isUpdating = false;
        });

        // Control de tabs
        const tabs = document.querySelectorAll('.tab-button');
        const contents = document.querySelectorAll('.tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => {
                    t.classList.remove('active');
                    t.setAttribute('aria-selected', 'false');
                });
                contents.forEach(c => c.classList.remove('active'));

                tab.classList.add('active');
                tab.setAttribute('aria-selected', 'true');

                const panel = document.getElementById(tab.getAttribute('aria-controls'));
                panel.classList.add('active');
            });
        });

        // Botón exportar llama función externa
        document.querySelector('.export-button').addEventListener('click', () => {
            if(typeof exportToFlutter === 'function'){
                exportToFlutter(editor);
            } else {
                alert('Función exportToFlutter no encontrada. Asegúrate que exportcomponenteflutter.js esté cargado correctamente.');
            }
        });
    </script>

    <!-- JS para exportar a Flutter -->
    <script src="{{ asset('exportcomponenteangular.js') }}"></script>

</x-app-layout>
