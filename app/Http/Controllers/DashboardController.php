<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tramite;
use App\Models\Document;
use App\Models\Placa;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $userCount = User::count();
            $approvedTramitesCount = Tramite::where('status', 'Aprobado')->count();
            $approvedDocumentsCount = Document::where('status', 'aprobado')->count();
            $placasCount = Placa::count();

            return Inertia::render('Dashboard/Index', [
                'userCount' => $userCount,
                'approvedTramitesCount' => $approvedTramitesCount,
                'approvedDocumentsCount' => $approvedDocumentsCount,
                'placasCount' => $placasCount,
            ]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            abort(500, 'Error al cargar los datos del dashboard');
        }
    }

}

