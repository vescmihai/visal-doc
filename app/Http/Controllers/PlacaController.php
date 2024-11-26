<?php

namespace App\Http\Controllers;

use App\Models\Placa;
use App\Models\Tramite;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlacaController extends Controller
{
    public function create()
    {
        $tramites = Tramite::all(); 
        return inertia('Placas/Create', [
            'tramites' => $tramites,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tramite_id' => 'required|exists:tramites,id',
            'placa' => 'required|string|max:255',
            'motor' => 'required|string|max:255',
            'chasis' => 'required|string|max:255',
            'poliza' => 'required|string|max:255',
            'pago' => 'required|string',
        ]);

        Placa::create([
            'tramite_id' => $validated['tramite_id'],
            'placa' => $validated['placa'],
            'motor' => $validated['motor'],
            'chasis' => $validated['chasis'],
            'poliza' => $validated['poliza'],
            'pago' => $validated['pago'],
        ]);

        return redirect()->route('placas.index');
    }
    public function index()
    {
        $placas = Placa::with('tramite')->get();
        return Inertia::render('Placas/Index', [
            'placas' => $placas,
        ]);
    }

    public function destroy($id)
    {
        $placa = Placa::findOrFail($id);
        $placa->delete();
        return response()->json(['message' => 'Placa eliminada exitosamente.']);
    }
}
