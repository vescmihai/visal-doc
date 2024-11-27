<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Tramite;
use Inertia\Inertia;

class DocumentController extends Controller
{
    /**
     * Muestra la lista de documentos, con opción de filtrar por tramite_id.
     */
    public function index(Request $request)
    {
        // Filtrar documentos por tramite_id si se proporciona
        $tramiteId = $request->get('tramite_id', null);  // Si no se pasa tramite_id, será null por defecto.

        $documents = Document::with(['user', 'tramite'])
            ->when($tramiteId, function ($query, $tramiteId) {
                $query->where('tramite_id', $tramiteId);
            })
            ->get();

        return Inertia::render('Documents/Index', [
            'documents' => $documents,
            'tramiteId' => $tramiteId, // Pasar tramite_id al frontend
            'tramites' => Tramite::all(), // Enviar lista de trámites para el filtro
        ]);
    }

    /**
     * Muestra el formulario para crear un nuevo documento.
     */
    public function create(Request $request)
    {
        // Se pasa el tramite_id automáticamente a la vista, asegurándonos de que sea un valor válido.
        $tramiteId = filter_var($request->get('tramite_id', null), FILTER_VALIDATE_INT) ?: null;

        // Recuperar los documentos ya registrados para este trámite (si existe tramite_id)
        $documentosRegistrados = $tramiteId ? Document::where('tramite_id', $tramiteId)->get() : [];

        return Inertia::render('Documents/Create', [
            'tramiteId' => $tramiteId, // Pasar tramite_id (null si no existe)
            'documentosRegistrados' => $documentosRegistrados, // Enviar documentos existentes
        ]);
    }

    /**
     * Guarda un nuevo documento.
     */
    public function store(Request $request)
    {
        // Validación de los datos
        $validated = $request->validate([
            'type' => 'required|string',
            'file' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'tramite_id' => 'required|exists:tramites,id',  // Asegúrate de que tramite_id exista en la base de datos
        ]);

        // Guardar el archivo en el directorio 'documents' (o cualquier directorio adecuado)
        $path = $request->file('file')->store('documents', 'public');

        // Crear el documento en la base de datos
        Document::create([
            'user_id' => auth()->id(),  // Suponiendo que el documento está asociado con el usuario
            'type' => $validated['type'],
            'file_path' => $path,
            'tramite_id' => $validated['tramite_id'],
        ]);

        // Redirigir con un mensaje de éxito
        return redirect()->route('documents.index', ['tramite_id' => $validated['tramite_id']])
        ->with('message', 'Documento subido con éxito.');
    }

    /**
     * Actualiza el estado y observación de un documento.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pendiente,Aprobado,Rechazado',
            'observation' => 'nullable|string',
        ]);

        $document = Document::findOrFail($id);

        $document->update([
            'status' => $validated['status'],
            'observation' => $validated['observation'] ?? $document->observation,
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
        \Storage::disk('public')->delete($document->file_path);

        $document->delete();

        return redirect()->route('documents.index')->with('message', 'Documento eliminado con éxito.');
    }
}
