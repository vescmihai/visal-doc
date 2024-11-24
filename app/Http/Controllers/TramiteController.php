<?php

namespace App\Http\Controllers;

use App\Models\Tramite;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TramiteController extends Controller
{
    public function index()
{
    // Recuperar todos los trámites junto con el nombre del usuario (cliente)
    $tramites = Tramite::with('user')->get();

    // Pasar los trámites a la vista
    return Inertia::render('Tramites/Index', [
        'tramites' => $tramites->map(function ($tramite) {
            return [
                'id' => $tramite->id,
                'title' => $tramite->title,
                'status' => $tramite->status,
                'client_name' => $tramite->user->name, // Relación con el cliente (usuario)
                'observation' => $tramite->observation,
                'created_at' => $tramite->created_at->format('Y-m-d H:i:s'), // Fecha de ingreso
                'updated_at' => $tramite->updated_at->format('Y-m-d H:i:s'), // Última revisión
            ];
        }),
    ]);
}


    public function create()
    {
        // Mostrar el formulario para crear un nuevo trámite
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
        // Validar los datos de entrada
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        // Asignar el 'user_id' del usuario autenticado al crear un trámite
        $validated['user_id'] = auth()->id(); // Agregar el ID del usuario autenticado

        // Crear el trámite
        Tramite::create($validated);

        // Redirigir con un mensaje de éxito
        return redirect()->route('tramites.index')->with('success', 'Trámite creado con éxito.');
    }

    public function updateStatus(Request $request, Tramite $tramite)
{
    // Validación de estado y observación
    $request->validate([
        'status' => 'required|string',
        'observation' => 'nullable|string', // La observación es opcional en este caso
    ]);
    
    // Actualizar el estado
    $tramite->status = $request->status;
    
    // Si hay una observación, actualizarla también
    if ($request->has('observation')) {
        $tramite->observation = $request->observation;
    }
    
    // Guardar los cambios
    $tramite->save();
    
    return response()->json([
        'message' => 'Estado y observación actualizados correctamente',
        'tramite' => $tramite
    ]);
}

    

    public function updateObservation(Request $request, Tramite $tramite)
{
    // Validar la observación
    $request->validate([
        'observation' => 'required|string',
    ]);

    // Actualizar la observación en el tramite
    $tramite->observation = $request->observation;
    $tramite->save();

    return response()->json([
        'message' => 'Observación actualizada correctamente',
        'tramite' => $tramite
    ]);
}

}
