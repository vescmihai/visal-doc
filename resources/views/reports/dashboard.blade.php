<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { font-size: 24px; }
        .section { margin-bottom: 20px; }
        .section h2 { font-size: 18px; border-bottom: 2px solid #ccc; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th, table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        table th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte del Dashboard</h1>
        <p>Generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="section">
        <h2>Usuarios</h2>
        <table>
            <tr>
                <th>Total</th>
                <th>Clientes</th>
                <th>Gestores</th>
                <th>Administradores</th>
            </tr>
            <tr>
                <td>{{ $userCount }}</td>
                <td>{{ $clientCount }}</td>
                <td>{{ $gestorCount }}</td>
                <td>{{ $adminCount }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Trámites</h2>
        <table>
            <tr>
                <th>Total</th>
                <th>Pendientes</th>
                <th>Aprobados</th>
                <th>Rechazados</th>
            </tr>
            <tr>
                <td>{{ $tramiteCount }}</td>
                <td>{{ $pendingTramites }}</td>
                <td>{{ $approvedTramites }}</td>
                <td>{{ $rejectedTramites }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Documentos</h2>
        <table>
            <tr>
                <th>Total</th>
                <th>Pendientes</th>
                <th>Aprobados</th>
                <th>Rechazados</th>
            </tr>
            <tr>
                <td>{{ $documentCount }}</td>
                <td>{{ $pendingDocuments }}</td>
                <td>{{ $approvedDocuments }}</td>
                <td>{{ $rejectedDocuments }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Placas</h2>
        <table>
            <tr>
                <th>Total</th>
                <th>Pagadas</th>
                <th>Pendientes</th>
            </tr>
            <tr>
                <td>{{ $placasCount }}</td>
                <td>{{ $paidPlacas }}</td>
                <td>{{ $pendingPlacas }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Visitas</h2>
        <p>Total de visitas: {{ $pageVisitCount }}</p>
    </div>
</body>
</html>
