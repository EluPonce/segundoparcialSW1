<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GraficadorController;

Route::resource('graficadores', GraficadorController::class);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Route::get('/usuarios-graficadores', [GraficadorController::class, 'listarUsuariosGraficadores'])->name('usuarios.graficadores');

Route::get('/usuarios-graficadores/{idGraficador}/usuarios', [GraficadorController::class, 'listarUsuariosPorGraficador'])->name('graficador.usuarios');

Route::post('/graficadorusuario/{idGraficador}/{id}/cambiar-estado', [GraficadorController::class, 'cambiarEstado'])
    ->name('graficadorusuario.cambiarEstado');

// Ruta para listar todas las gráficas
Route::get('/graficador/lista', [GraficadorController::class, 'listatodaslasgraficas'])->name('graficadores.lista');
// Ruta para añadir colaborador
Route::post('/graficador/colaborador', [GraficadorController::class, 'addCollaborator']);
Route::get('/graficador/portal', [GraficadorController::class, 'PortalGraficador']);


// Ruta para importar

Route::get('/graficador/importar', [GraficadorController::class, 'mostrarFormularioImportar'])->name('graficador.importar');
Route::post('/graficador/procesar-imagen', [GraficadorController::class, 'procesarImagen'])->name('graficador.procesar');


require __DIR__.'/auth.php';
