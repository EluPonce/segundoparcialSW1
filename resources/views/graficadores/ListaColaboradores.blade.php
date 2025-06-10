<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-pink-700 dark:text-pink-400 leading-tight">
            {{ __('Usuarios en Graficadores') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-lg p-6">

                <table class="min-w-full table-auto text-sm text-gray-600 dark:text-gray-300">
                    <thead class="bg-pink-200 dark:bg-pink-700">
                        <tr>
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">Nombre</th>
                            <th class="px-4 py-2 text-left">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usuarios as $usuario)
                            <tr class="border-b dark:border-gray-700 hover:bg-pink-100 dark:hover:bg-pink-800">
                                <td class="px-4 py-2">{{ $usuario->id }}</td>
                                <td class="px-4 py-2">{{ $usuario->name }}</td>
                                <td class="px-4 py-2">{{ $usuario->email }}</td>
                                <td class="px-4 py-2">
                                    @if ($usuario->estadoGraficador == 1)
                                        <span class="px-3 py-1 bg-yellow-300 text-yellow-900 rounded-full text-xs font-semibold">Pendiente</span>
                                    @elseif ($usuario->estadoGraficador == 2)
                                        <span class="px-3 py-1 bg-green-300 text-green-900 rounded-full text-xs font-semibold">Aceptado</span>
                                    @else
                                        <span class="px-3 py-1 bg-gray-300 text-gray-700 rounded-full text-xs font-semibold">Desconocido</span>
                                    @endif
                                </td>
                    
                                <!-- Botón Activar solo si Estado es 1 -->
                                <td class="px-6 py-4">
                                    @if ($usuario->estadoGraficador == 1)
                                        <form action="{{ route('graficadorusuario.cambiarEstado', ['idGraficador' => $usuario->IdGraficador, 'id' => $usuario->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition transform hover:scale-105">
                                                Activar
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-center text-gray-500">
                                    No hay usuarios registrados en graficadores.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>
