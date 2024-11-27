<?php

namespace App\Http\Controllers;

use App\Models\Placa;
use App\Models\Tramite;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlacaController extends Controller
{
    /**
     * Mostrar el formulario de creación de una nueva placa.
     */
    public function create()
    {
        // Obtener trámites aprobados que no tienen una placa asociada
        $tramites = Tramite::where('status', 'Aprobado') // Filtrar trámites aprobados
            ->whereDoesntHave('placa') // Excluir trámites que ya tienen una placa
            ->get();

        return Inertia::render('Placas/Create', [
            'tramites' => $tramites,
        ]);
    }

    /**
     * Guardar una nueva placa en la base de datos.
     */
    public function store(Request $request)
    {
        try {
            // Validar los datos de entrada
            $validated = $request->validate([
                'tramite_id' => 'required|exists:tramites,id',
                'placa' => 'required|string|max:255',
                'motor' => 'required|string|max:255',
                'chasis' => 'required|string|max:255',
                'poliza' => 'required|string|max:255',
                'pago' => 'required|in:Pendiente,Pagado', // Validar el estado del pago
            ]);

            // Crear la placa
            Placa::create($validated);

            // Redirigir con mensaje de éxito
            return redirect()
                ->route('placas.index')
                ->with('success', 'Placa registrada exitosamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Capturar errores de validación y retornarlos al cliente
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            // Manejar otros errores
            return redirect()
                ->back()
                ->with('error', 'Ocurrió un error al registrar la placa.')
                ->withInput();
        }
    }

    /**
     * Mostrar todas las placas existentes.
     */
    public function index()
    {
        $placas = Placa::with('tramite')->paginate(10); // Paginar datos
        return Inertia::render('Placas/Index', [
            'placas' => $placas, // Enviar los datos paginados
        ]);
    }

    /**
     * Eliminar una placa específica.
     */
    public function destroy($id)
    {
        // Buscar y eliminar la placa
        $placa = Placa::findOrFail($id);
        $placa->delete();

        // Redirigir con mensaje de éxito
        return redirect()
            ->route('placas.index')
            ->with('success', 'Placa eliminada exitosamente.');
    }
}
