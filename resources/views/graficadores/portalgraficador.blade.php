<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-pink-600 dark:text-pink-400 leading-tight">
                {{ __('Portal del Graficador') }}
            </h2>
            <div class="space-x-4">
                <button onclick="mostrarPestana('editor')" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600">Editor</button>
                <button onclick="mostrarPestana('importar')" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600">Importar Imagen</button>
            </div>
        </div>
    </x-slot>

    <style>
        .pestana {
            display: none;
        }

        .pestana-activa {
            display: block;
        }

        .gjs-cv-canvas {
            height: 80vh !important;
        }

        #blocks .gjs-block {
            max-width: 100%;
            padding: 5px;
            font-size: 14px;
        }

        #gjs * {
            max-width: 100%;
            box-sizing: border-box;
        }

        .export-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #ec4899;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 999;
        }

        .export-button:hover {
            background-color: #db2777;
        }
    </style>

    <link href="https://unpkg.com/grapesjs/dist/css/grapes.min.css" rel="stylesheet">

    <div class="py-6 bg-pink-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Panel del Editor -->
            <div id="pestana-editor" class="pestana pestana-activa">
                <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg p-4">
                    <div class="flex h-[80vh]">
                        <div id="blocks" class="bg-pink-100 dark:bg-pink-900 p-4 overflow-y-auto rounded-l-lg w-72 text-sm"></div>
                        <div class="flex-1 flex flex-col rounded-r-lg border-l border-pink-300">
                            <div class="panel__top bg-white dark:bg-gray-700 px-4 py-2 border-b border-pink-300"></div>
                            <div id="gjs" class="flex-grow bg-white dark:bg-gray-900 rounded-b-lg"></div>
                        </div>
                    </div>
                    <a href="{{ route('graficadores.index') }}" class="btn btn-secondary mt-4 inline-block">← Volver</a>
                </div>
            </div>

            <!-- Panel de Importar -->
            <div id="pestana-importar" class="pestana">
                @include('graficadores.importador')
            </div>
        </div>
    </div>

    <!-- Botón Exportar -->
    <button class="export-button">Exportar a Flutter</button>

    <!-- Librerías -->
    <script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
    <script src="https://unpkg.com/grapesjs"></script>
    <script src="https://unpkg.com/grapesjs-blocks-basic"></script>
    <script src="https://unpkg.com/grapesjs-plugin-forms"></script>
    <script src="https://unpkg.com/grapesjs-navbar"></script>
    <script src="https://unpkg.com/grapesjs-tabs"></script>

    <script>
        // Inicializar editor
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
                        content: '<img src="https://via.placeholder.com/150" alt="Imagen" style="max-width:100%; height:auto;">'
                    },
                    {
                        id: 'video',
                        label: 'Video',
                        content: `<div data-gjs-type="default">
                                    <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" frameborder="0"
                                        allowfullscreen style="width:100%; height:315px;"></iframe>
                                  </div>`
                    },
                    {
                        id: 'button',
                        label: 'Botón',
                        content: '<button class="btn btn-primary" style="padding: 6px 12px;">Haz clic aquí</button>'
                    },
                    {
                        id: 'form',
                        label: 'Formulario',
                        content: `
                            <form style="max-width: 100%;">
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" class="form-control" id="name" placeholder="Tu nombre">
                                </div>
                                <div class="form-group">
                                    <label for="email">Correo</label>
                                    <input type="email" class="form-control" id="email" placeholder="Tu correo">
                                </div>
                                <button type="submit" class="btn btn-primary">Enviar</button>
                            </form>
                        `
                    }
                ]
            }
        });

        // Botón Exportar
        document.querySelector('.export-button').addEventListener('click', () => {
            editor.runCommand('export-dart');
        });

        // WebSocket
        const socket = io('http://localhost:3000', { transports: ['websocket'] });
        socket.on('connect', () => console.log('Conectado al WebSocket'));
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

        // Pestañas
        function mostrarPestana(id) {
            document.querySelectorAll('.pestana').forEach(p => p.classList.remove('pestana-activa'));
            document.getElementById('pestana-' + id).classList.add('pestana-activa');
        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.8.0/jszip.min.js"></script>
    <script src="{{ asset('exportcomponenteflutter.js') }}"></script>
</x-app-layout>
