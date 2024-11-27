<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tramite;
use App\Models\Document;
use App\Models\Placa;
use App\Models\PageVisit;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Estadísticas de usuarios
            $userCount = User::count();
            $clientCount = User::where('role', 'cliente')->count();
            $gestorCount = User::where('role', 'gestor')->count();
            $adminCount = User::where('role', 'admin')->count();

            // Estadísticas de trámites
            $tramiteCount = Tramite::count();
            $pendingTramites = Tramite::where('status', 'Pendiente')->count();
            $approvedTramites = Tramite::where('status', 'Aprobado')->count();
            $rejectedTramites = Tramite::where('status', 'Rechazado')->count();

            // Estadísticas de documentos
            $documentCount = Document::count();
            $pendingDocuments = Document::where('status', 'Pendiente')->count();
            $approvedDocuments = Document::where('status', 'Aprobado')->count();
            $rejectedDocuments = Document::where('status', 'Rechazado')->count();

            // Estadísticas de placas
            $placasCount = Placa::count();
            $pendingPlacas = Placa::where('pago', 'Pendiente')->count();
            $paidPlacas = Placa::where('pago', 'Pagado')->count();

            // Estadísticas de visitas
            $pageVisitCount = PageVisit::sum('visits');

            return Inertia::render('Dashboard/Index', [
                // Usuarios
                'userCount' => $userCount,
                'clientCount' => $clientCount,
                'gestorCount' => $gestorCount,
                'adminCount' => $adminCount,

                // Trámites
                'tramiteCount' => $tramiteCount,
                'pendingTramites' => $pendingTramites,
                'approvedTramites' => $approvedTramites,
                'rejectedTramites' => $rejectedTramites,

                // Documentos
                'documentCount' => $documentCount,
                'pendingDocuments' => $pendingDocuments,
                'approvedDocuments' => $approvedDocuments,
                'rejectedDocuments' => $rejectedDocuments,

                // Placas
                'placasCount' => $placasCount,
                'pendingPlacas' => $pendingPlacas,
                'paidPlacas' => $paidPlacas,

                // Visitas
                'pageVisitCount' => $pageVisitCount,
            ]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            abort(500, 'Error al cargar los datos del dashboard');
        }
    }
}
