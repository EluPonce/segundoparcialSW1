{{-- resources/views/graficadores/importar.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Importar Imagen para Analizar con IA
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('descripcion'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <strong>Descripción Generada:</strong> {{ session('descripcion') }}
                    </div>
                @endif

                <form action="/graficador/procesar-imagen" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="imagen" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">
                            Selecciona una imagen:
                        </label>
                        <input type="file" name="imagen" required
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 dark:bg-gray-700 dark:border-gray-600">
                    </div>

                    <button type="submit" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Analizar Imagen
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
