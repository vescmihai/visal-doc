<?php

namespace App\Http\Controllers;

use App\Models\Tramite;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TramiteController extends Controller
{
    public function index()
{
    $tramites = Tramite::with('user')->get();

    return Inertia::render('Tramites/Index', [
        'tramites' => $tramites->map(function ($tramite) {
            return [
                'id' => $tramite->id,
                'title' => $tramite->title,
                'status' => $tramite->status,
                'client_name' => $tramite->user->name, 
                'observation' => $tramite->observation,
                'created_at' => $tramite->created_at->format('Y-m-d H:i:s'), 
                'updated_at' => $tramite->updated_at->format('Y-m-d H:i:s'), 
            ];
        }),
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

        $validated['user_id'] = auth()->id(); 
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
            'observation' => 'required|string',
        ]);

        $tramite->observation = $request->observation;
        $tramite->save();

        return response()->json([
            'message' => 'Observación actualizada correctamente',
            'tramite' => $tramite
        ]);
    }

}
