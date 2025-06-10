<!-- resources/views/graficadores/edit.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Graficador') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <h3 class="text-2xl font-semibold mb-6 text-pink-500">Editar Graficador</h3>

                    <form action="{{ route('graficadores.update', $graficador->IdGraficador) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Campo Título -->
                        <div>
                            <label for="Titulo" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Título</label>
                            <input type="text" name="Titulo" id="Titulo" value="{{ old('Titulo', $graficador->Titulo) }}" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-pink-400 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>

                        <!-- Campo Descripción -->
                        <div>
                            <label for="Descripcion" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Descripción</label>
                            <textarea name="Descripcion" id="Descripcion" rows="4" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-pink-400 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('Descripcion', $graficador->Descripcion) }}</textarea>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-center justify-between">
                            <button type="submit"
                                class="px-6 py-2 text-white bg-pink-500 hover:bg-pink-600 rounded-lg shadow-md">
                                Actualizar
                            </button>
                            <a href="{{ route('graficadores.index') }}"
                                class="px-6 py-2 bg-purple-500 text-white rounded-lg shadow-md hover:bg-purple-600">
                                Volver al Listado
                            </a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

