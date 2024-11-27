<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\TramiteController;
use App\Http\Controllers\PlacaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CallBackController;
use App\Http\Controllers\PagoFacilController;
use App\Http\Middleware\TrackPageVisits;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\PageVisit;

Route::post('/page-visit', function (Request $request) {
    // Obtener la URL enviada desde el cliente
    $pageUrl = $request->input('page_url', '/');

    // Buscar o crear un registro para la página específica
    $pageVisit = PageVisit::firstOrCreate(['page_url' => $pageUrl]);
    
    // Incrementar el contador de visitas
    $pageVisit->increment('visits');

    return response()->json([
        'visits' => $pageVisit->visits,
    ]);
})->name('page-visit.store');






Route::middleware(TrackPageVisits::class)->group(function () {
// Rutas públicas
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Rutas de autenticación
require __DIR__ . '/auth.php';

// Rutas de Pago Fácil
Route::prefix('pagofacil')->group(function () {
    Route::post('/consultar', [PagoFacilController::class, 'consultar'])->name('pagofacil.consultar');
    Route::post('/generar', [PagoFacilController::class, 'generar'])->name('pagofacil.generar');
    Route::post('/callback', CallBackController::class)->name('pagofacil.callback');
});

Route::get('/ventas/create/{placa}', [PagoFacilController::class, 'create'])->name('ventas.create');

// Rutas protegidas (requieren autenticación)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Gestión de usuarios
    Route::resource('users', UserController::class)->except(['show']);

    // Gestión de documentos
    Route::prefix('documents')->group(function () {
        Route::get('/', [DocumentController::class, 'index'])->name('documents.index');
        Route::get('/create', [DocumentController::class, 'create'])->name('documents.create');
        Route::post('/', [DocumentController::class, 'store'])->name('documents.store');
        Route::get('/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
        Route::put('/{document}', [DocumentController::class, 'update'])->name('documents.update');
        Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    });

    // Gestión de trámites
    Route::prefix('tramites')->group(function () {
        Route::get('/', [TramiteController::class, 'index'])->name('tramites.index');
        Route::get('/create', [TramiteController::class, 'create'])->name('tramites.create');
        Route::post('/', [TramiteController::class, 'store'])->name('tramites.store');
        Route::get('/{tramite}/gestionar', [TramiteController::class, 'gestionar'])->name('tramites.gestionar');
        Route::put('/{tramite}/update-observation', [TramiteController::class, 'updateObservation'])->name('tramites.update-observation');
        Route::put('/{tramite}/update-status', [TramiteController::class, 'updateStatus'])->name('tramites.update-status');
    });

    // Gestión de placas
    Route::prefix('placas')->group(function () {
        Route::get('/', [PlacaController::class, 'index'])->name('placas.index');
        Route::get('/create', [PlacaController::class, 'create'])->name('placas.create');
        Route::post('/', [PlacaController::class, 'store'])->name('placas.store');
        Route::delete('/{placa}', [PlacaController::class, 'destroy'])->name('placas.destroy');
    });

    // Gestión de perfiles
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});
});