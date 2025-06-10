
<!-- resources/views/graficadores/index.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-pink-600 dark:text-pink-400 leading-tight">
            {{ __('Lista de Graficadores') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-pink-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-8">

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white">
                        Graficadores Disponibles
                    </h3>
                    <a href="{{ route('graficadores.create') }}" 
                       class="bg-pink-500 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition transform hover:scale-105">
                        Crear Nuevo
                    </a>
                </div>

                <table class="min-w-full bg-white dark:bg-gray-700 rounded-lg overflow-hidden">
                    <thead class="bg-pink-400 dark:bg-pink-600 text-white">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium">ID</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Título</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Fecha de Creación</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 dark:text-gray-300">
                        @foreach($graficadores as $graficador)
                            <tr class="border-b dark:border-gray-600">
                                <td class="px-6 py-4">{{ $graficador->IdGraficador }}</td>
                                <td class="px-6 py-4">{{ $graficador->Titulo }}</td>
                                <td class="px-6 py-4">{{ $graficador->FechaCreacion }}</td>
                                <td class="px-6 py-4 flex space-x-2">
                                    <a href="{{ route('graficadores.edit', $graficador->IdGraficador) }}"
                                       class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition transform hover:scale-105">
                                        Editar
                                    </a>
                                    <form action="{{ route('graficadores.destroy', $graficador->IdGraficador) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este graficador?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition transform hover:scale-105">
                                            Eliminar
                                        </button>
                                    </form>
                                    <a href="{{ route('graficador.usuarios', $graficador->IdGraficador) }}"
                                        class="bg-pink-500 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded-lg transition transform hover:scale-105">
                                         Ver Usuarios
                                     </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>
