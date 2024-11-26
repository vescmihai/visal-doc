<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\TramiteController;
use App\Http\Controllers\PlacaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CallBackController;
use App\Http\Controllers\PagoFacilController;

Route::post('/pagofacil/consultar', [PagoFacilController::class, 'consultar'])->name('pagofacil.consultar');
Route::get('/ventas/create/{placa}', [PagoFacilController::class, 'create'])->name('ventas.create');
Route::post('/pagofacil/generar', [PagoFacilController::class, 'generar'])->name('pagofacil.generar');
Route::post('/pagofacil/callback', CallBackController::class)->name('pagofacil.callback');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


Route::middleware('auth')->group(function () {
    Route::get('/placas', [PlacaController::class, 'index'])->name('placas.index');
    Route::get('/placas/create', [PlacaController::class, 'create'])->name('placas.create');
    Route::post('/placas', [PlacaController::class, 'store'])->name('placas.store');
    Route::delete('/placas/{placa}', [PlacaController::class, 'destroy'])->name('placas.destroy');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/tramites', [TramiteController::class, 'index'])->name('tramites.index');
    Route::get('/tramites/create', [TramiteController::class, 'create'])->name('tramites.create');
    Route::post('/tramites', [TramiteController::class, 'store'])->name('tramites.store');
    Route::get('/tramites/{tramite}/gestionar', [TramiteController::class, 'gestionar'])->name('tramites.gestionar');
    Route::put('/tramites/{tramite}/update-observation', [TramiteController::class, 'updateObservation'])->name('tramites.update-observation');
    Route::put('/tramites/{tramite}/update-status', [TramiteController::class, 'updateStatus'])->name('tramites.update-status');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index'); // Listar documentos
    Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create'); // Formulario de creación
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store'); // Guardar nuevo documento
    Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit'); // Formulario de edición
    Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update'); // Actualizar documento
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy'); // Eliminar documento
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

/*Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
