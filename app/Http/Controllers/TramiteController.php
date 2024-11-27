<?php

namespace App\Http\Controllers;

use App\Models\Tramite;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TramiteController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Determinar si es cliente o no para filtrar los trámites
        $query = Tramite::with('user:id,name'); // Incluir relación con el usuario

        if ($user->role === 'cliente') {
            // Filtrar trámites solo para el cliente autenticado
            $query->where('user_id', $user->id);
        }

        // Paginación (10 trámites por página)
        $tramites = $query->paginate(10);

        // Transformar los datos para enviar solo lo necesario al frontend
        $tramites->getCollection()->transform(function ($tramite) {
            return [
                'id' => $tramite->id,
                'title' => $tramite->title,
                'status' => $tramite->status,
                'client_name' => $tramite->user->name ?? 'N/A',
                'user_id' => $tramite->user_id,
                'observation' => $tramite->observation,
                'created_at' => $tramite->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $tramite->updated_at->format('Y-m-d H:i:s'),
            ];
        });

        return Inertia::render('Tramites/Index', [
            'tramites' => $tramites, // Enviar objeto paginado
        ]);
    }


    public function create()
    {
        return Inertia::render('Tramites/Create');
    }

    public function gestionar(Tramite $tramite)
    {
        return Inertia::render('Tramites/Gestionar', [
            'tramite' => $tramite,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $validated['user_id'] = auth()->id();  // Asignar el ID del usuario autenticado al trámite
        Tramite::create($validated);

        return redirect()->route('tramites.index')->with('success', 'Trámite creado con éxito.');
    }

    public function updateStatus(Request $request, Tramite $tramite)
    {
        $request->validate([
            'status' => 'required|string',
            'observation' => 'nullable|string', 
        ]);
        
        $tramite->status = $request->status;
        
        if ($request->has('observation')) {
            $tramite->observation = $request->observation;
        }
        
        $tramite->save();
        
        return response()->json([
            'message' => 'Estado y observación actualizados correctamente',
            'tramite' => $tramite
        ]);
    }

    public function updateObservation(Request $request, Tramite $tramite)
    {
        $request->validate([
            'observation' => 'required|string|min:4|max:16',
        ]);

        $tramite->observation = $request->observation;
        $tramite->save();

        return response()->json([
            'message' => 'Observación actualizada correctamente',
            'tramite' => $tramite
        ]);
    }
}
