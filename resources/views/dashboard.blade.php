<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-pink-600 dark:text-pink-400 leading-tight">
            {{ __('Bienvenido al Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-pink-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-8">

                <div class="text-center">
                    <h3 class="text-3xl font-bold text-gray-800 dark:text-white mb-4">
                        ¡Estás conectado exitosamente! 🎉
                    </h3>

                    <p class="text-gray-600 dark:text-gray-300 mb-8">
                        Gracias por iniciar sesión. ¿Listo para comenzar a crear algo increíble?
                    </p>

                    <a href="{{ route('graficadores.index') }}" 
                       class="bg-pink-500 hover:bg-pink-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition transform hover:scale-105">
                        Ir a Mis Graficadores
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

