<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Graficador - Iniciar Sesión / Registro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(to bottom right, #f3c1f5, #e0b3ff);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md relative">
        <!-- Imagen superior -->
        <div class="flex justify-center mb-6">
            <img src="https://cdn-icons-png.flaticon.com/512/2555/2555276.png" alt="Graficador" class="h-20">
        </div>

        <h2 class="text-2xl font-bold text-center mb-6 text-purple-700">Bienvenido al Graficador</h2>

        <div class="flex mb-4">
            <button id="btnLogin" class="w-1/2 py-2 bg-pink-500 text-white rounded-l-lg font-semibold">Iniciar Sesión</button>
            <button id="btnRegister" class="w-1/2 py-2 bg-purple-200 text-purple-800 rounded-r-lg font-semibold">Registrarse</button>
        </div>

        <!-- Login Form -->
        <form id="loginForm" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm text-purple-700">Correo</label>
                <input type="email" name="email" required class="w-full p-2 border rounded border-purple-300" />
            </div>
            <div class="mb-4">
                <label class="block text-sm text-purple-700">Contraseña</label>
                <input type="password" name="password" required class="w-full p-2 border rounded border-purple-300" />
            </div>
            <button type="submit" class="w-full bg-pink-500 text-white py-2 rounded hover:bg-pink-600 font-bold">Entrar</button>
        </form>

        <!-- Register Form -->
        <form id="registerForm" method="POST" action="{{ route('register') }}" class="hidden">
            @csrf
            <div class="mb-4">
                <label class="block text-sm text-purple-700">Nombre</label>
                <input type="text" name="name" required class="w-full p-2 border rounded border-purple-300" />
            </div>
            <div class="mb-4">
                <label class="block text-sm text-purple-700">Correo</label>
                <input type="email" name="email" required class="w-full p-2 border rounded border-purple-300" />
            </div>
            <div class="mb-4">
                <label class="block text-sm text-purple-700">Contraseña</label>
                <input type="password" name="password" required class="w-full p-2 border rounded border-purple-300" />
            </div>
            <div class="mb-4">
                <label class="block text-sm text-purple-700">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" required class="w-full p-2 border rounded border-purple-300" />
            </div>
            <button type="submit" class="w-full bg-purple-500 text-white py-2 rounded hover:bg-purple-600 font-bold">Registrarse</button>
        </form>
    </div>

    <script>
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        const btnLogin = document.getElementById('btnLogin');
        const btnRegister = document.getElementById('btnRegister');

        btnLogin.onclick = () => {
            loginForm.classList.remove('hidden');
            registerForm.classList.add('hidden');
            btnLogin.classList.add('bg-pink-500', 'text-white');
            btnLogin.classList.remove('bg-purple-200', 'text-purple-800');
            btnRegister.classList.add('bg-purple-200', 'text-purple-800');
            btnRegister.classList.remove('bg-purple-500', 'text-white');
        };

        btnRegister.onclick = () => {
            loginForm.classList.add('hidden');
            registerForm.classList.remove('hidden');
            btnRegister.classList.add('bg-purple-500', 'text-white');
            btnRegister.classList.remove('bg-purple-200', 'text-purple-800');
            btnLogin.classList.add('bg-purple-200', 'text-purple-800');
            btnLogin.classList.remove('bg-pink-500', 'text-white');
        };
    </script>
</body>
</html>
