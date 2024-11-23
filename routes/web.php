<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocumentController;

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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
