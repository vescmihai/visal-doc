<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tramite;
use App\Models\Document;
use App\Models\Placa;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function generateDashboardReport()
    {
        try {
            // Datos necesarios para el reporte
            $userCount = User::count();
            $clientCount = User::where('role', 'cliente')->count();
            $gestorCount = User::where('role', 'gestor')->count();
            $adminCount = User::where('role', 'admin')->count();

            $tramiteCount = Tramite::count();
            $pendingTramites = Tramite::where('status', 'Pendiente')->count();
            $approvedTramites = Tramite::where('status', 'Aprobado')->count();
            $rejectedTramites = Tramite::where('status', 'Rechazado')->count();

            $documentCount = Document::count();
            $pendingDocuments = Document::where('status', 'Pendiente')->count();
            $approvedDocuments = Document::where('status', 'Aprobado')->count();
            $rejectedDocuments = Document::where('status', 'Rechazado')->count();

            $placasCount = Placa::count();
            $pendingPlacas = Placa::where('pago', 'Pendiente')->count();
            $paidPlacas = Placa::where('pago', 'Pagado')->count();

            $pageVisitCount = \App\Models\PageVisit::sum('visits');

            // Cargar los datos en la vista del PDF
            $pdf = Pdf::loadView('reports.dashboard', compact(
                'userCount',
                'clientCount',
                'gestorCount',
                'adminCount',
                'tramiteCount',
                'pendingTramites',
                'approvedTramites',
                'rejectedTramites',
                'documentCount',
                'pendingDocuments',
                'approvedDocuments',
                'rejectedDocuments',
                'placasCount',
                'pendingPlacas',
                'paidPlacas',
                'pageVisitCount'
            ));

            // Retornar el PDF generado como respuesta
            return $pdf->stream('reporte-dashboard.pdf');
        } catch (\Exception $e) {
            \Log::error('Error generando el reporte: ' . $e->getMessage());
            return response()->json(['message' => 'Error al generar el reporte'], 500);
        }
    }
}
