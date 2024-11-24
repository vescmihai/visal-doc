<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Tramite;
use Inertia\Inertia;

class DocumentController extends Controller
{
    /**
     * Muestra la lista de documentos.
     */
    public function index()
    {
        // Obtiene todos los documentos con la relación de usuario y trámite
        $documents = Document::with(['user', 'tramite'])->get();
    
        return Inertia::render('Documents/Index', [
            'documents' => $documents,
        ]);
    }
    

    /**
     * Muestra el formulario para crear un nuevo documento.
     */
    public function create()
    {
        // Obtiene la lista de trámites disponibles
        $tramites = Tramite::all();
    
        return Inertia::render('Documents/Create', [
            'tramites' => $tramites, // Pasa los trámites a la vista
        ]);
    }
    

    /**
     * Almacena un nuevo documento en la base de datos.
     */
    public function store(Request $request)
    {
        // Validación de los datos de entrada
        $validated = $request->validate([
            'type' => 'required|string',
            'file' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'tramite_id' => 'nullable|exists:tramites,id', // Validar trámite asociado
        ]);

        // Guardar el archivo en el almacenamiento público
        $path = $request->file('file')->store('documents', 'public');

        // Crear el documento en la base de datos
        Document::create([
            'user_id' => auth()->id(), // Usuario autenticado
            'type' => $validated['type'],
            'file_path' => $path,
            'tramite_id' => $validated['tramite_id'], // Asociar al trámite si se selecciona
        ]);

        // Redirige a la lista de documentos con un mensaje de éxito
        return redirect()->route('documents.index')->with('message', 'Documento subido con éxito.');
    }

    /**
     * Actualiza el estado y la observación de un documento.
     */
    public function update(Request $request, $id)
    {
        // Validación
        $validated = $request->validate([
            'status' => 'required|in:Pendiente,Aprobado,Rechazado',
            'observation' => 'nullable|string',
        ]);

        // Encontrar el documento
        $document = Document::findOrFail($id);

        // Actualizar documento
        $document->update([
            'status' => $validated['status'],
            'observation' => $validated['observation'] ?? $document->observation, // Mantener observación anterior
        ]);

        return response()->json([
            'message' => 'Documento actualizado con éxito',
            'document' => $document,
        ]);
    }

    /**
     * Elimina un documento.
     */
    public function destroy(Document $document)
    {
        // Eliminar el archivo físico del almacenamiento (opcional)
        \Storage::disk('public')->delete($document->file_path);

        // Eliminar el documento de la base de datos
        $document->delete();

        return redirect()->route('documents.index')->with('message', 'Documento eliminado con éxito.');
    }
}
