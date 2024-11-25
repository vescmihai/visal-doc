<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\TramiteController;
use App\Http\Controllers\PlacaController;

Route::middleware('auth')->group(function () {
    Route::get('/placas', [PlacaController::class, 'index'])->name('placas.index');
    Route::get('/placas/create', [PlacaController::class, 'create'])->name('placas.create');
    Route::post('/placas', [PlacaController::class, 'store'])->name('placas.store');
    Route::delete('/placas/{placa}', [PlacaController::class, 'destroy'])->name('placas.destroy');
});


Route::middleware(['auth'])->group(function () {
    // Ruta para mostrar la lista de trámites
    Route::get('/tramites', [TramiteController::class, 'index'])->name('tramites.index');

    // Ruta para crear un nuevo trámite
    Route::get('/tramites/create', [TramiteController::class, 'create'])->name('tramites.create');
    Route::post('/tramites', [TramiteController::class, 'store'])->name('tramites.store');

    // Ruta para gestionar un trámite específico
    Route::get('/tramites/{tramite}/gestionar', [TramiteController::class, 'gestionar'])->name('tramites.gestionar');
    
    // Ruta para actualizar la observación de un trámite
    Route::put('/tramites/{tramite}/update-observation', [TramiteController::class, 'updateObservation'])->name('tramites.update-observation');
    
    // Ruta para actualizar el estado de un trámite
    Route::put('/tramites/{tramite}/update-status', [TramiteController::class, 'updateStatus'])->name('tramites.update-status');
});

Route::middleware(['auth'])->group(function () {
    // Ruta para mostrar la lista de documentos
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');

    // Ruta para almacenar un nuevo documento
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');

    // Ruta para actualizar el estado de un documento (Completar/Rechazar)
    Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');

    // Ruta para mostrar el formulario de creación de un nuevo documento
    Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
    
    // Ruta para eliminar un documento
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::put('/documents/{id}', [DocumentController::class, 'update'])->name('documents.update');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Rutas para gestionar usuarios
    Route::resource('users', UserController::class)->except(['show']);
});

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Rutas para gestionar perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
