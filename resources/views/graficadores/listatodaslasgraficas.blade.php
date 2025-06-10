<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Lista de Graficadores') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-semibold mb-4">Graficadores</h3>

                    <!-- Tabla de Graficadores -->
                    <table class="min-w-full table-auto">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2">ID</th>
                                <th class="px-4 py-2">Título</th>
                                <th class="px-4 py-2">Descripción</th>
                                <th class="px-4 py-2">Fecha de Creación</th>
                                <th class="px-4 py-2">Hora de Creación</th>
                                <th class="px-4 py-2">Estado</th>
                                <th class="px-4 py-2">Contenido</th>
                                <th class="px-4 py-2">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($graficadores as $graficador)
                                <tr class="bg-white dark:bg-gray-800">
                                    <td class="border px-4 py-2">{{ $graficador->IdGraficador }}</td>
                                    <td class="border px-4 py-2">{{ $graficador->Titulo }}</td>
                                    <td class="border px-4 py-2">{{ $graficador->Descripcion }}</td>
                                    <td class="border px-4 py-2">{{ $graficador->FechaCreacion }}</td>
                                    <td class="border px-4 py-2">{{ $graficador->HoraCreacion }}</td>
                                    <td class="border px-4 py-2">{{ $graficador->Estado }}</td>
                                    <td class="border px-4 py-2">{{ $graficador->Contenido }}</td>
                                    <td class="border px-4 py-2">
                                        @if ($graficador->IdUser !== auth()->user()->id)
                                            <button
                                                class="bg-orange-500 text-black py-2 px-4 rounded-xl hover:bg-orange-600"
                                                onclick="openModal({{ $graficador->IdGraficador }})"
                                            >
                                                Invitación
                                            </button>
                                        @else
                                            <span class="text-gray-500">Eres el creador</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Modal -->
                    <div id="collaboratorModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md mx-4">
                            <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">
                                ¿Desea ser colaborador de este proyecto?
                            </h3>
                            <input type="hidden" id="graficadorId">
                            <div class="flex justify-center gap-4">
                                <button
                                    class="bg-green-500 text-black py-2 px-6 rounded hover:bg-green-600 transition duration-300"
                                    onclick="acceptCollaborator()"
                                >
                                    Sí, ser colaborador
                                </button>
                                <button
                                    class="bg-red-500 text-black py-2 px-6 rounded hover:bg-red-600 transition duration-300"
                                    onclick="closeModal()"
                                >
                                    Cancelar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Función para abrir el modal
        function openModal(id) {
            document.getElementById('graficadorId').value = id;
            document.getElementById('collaboratorModal').classList.remove('hidden');
        }

        // Función para cerrar el modal
        function closeModal() {
            document.getElementById('collaboratorModal').classList.add('hidden');
        }

        // Función para aceptar y agregar al colaborador
        function acceptCollaborator() {
            const graficadorId = document.getElementById('graficadorId').value;

            fetch('/graficador/colaborador', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ graficadorId: graficadorId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('¡Ahora eres colaborador!');
                    location.reload(); // refrescar para ver cambios
                } else {
                    alert('Error al intentar ser colaborador.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error de conexión.');
            });

            closeModal();
        }
    </script>
</x-app-layout>
