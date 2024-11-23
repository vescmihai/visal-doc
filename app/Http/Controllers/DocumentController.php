<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Inertia\Inertia;

class DocumentController extends Controller
{
    /**
     * Muestra la lista de documentos.
     */
    public function index()
    {
        // Obtiene documentos con la relación 'user' cargada
        $documents = Document::with('user')->get();
        
        return Inertia::render('Documents/Index', [
            'documents' => $documents
        ]);
    }

    /**
     * Muestra el formulario para crear un nuevo documento.
     */
    public function create()
    {
        return Inertia::render('Documents/Create'); // Vista para crear documentos
    }

    /**
     * Almacena un nuevo documento en la base de datos.
     */
    public function store(Request $request)
    {
        // Validación de los datos de entrada
        $validated = $request->validate([
            'type' => 'required|string',
            'file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Guardar el archivo en el almacenamiento público
        $path = $request->file('file')->store('documents', 'public');

        // Crear el documento en la base de datos
        Document::create([
            'user_id' => auth()->id(), // Asegúrate de que el usuario esté autenticado
            'type' => $validated['type'],
            'file_path' => $path,
        ]);

        // Redirige a la lista de documentos con un mensaje de éxito
        return redirect()->route('documents.index')->with('message', 'Documento subido con éxito.');
    }


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
            'observation' => $validated['observation'] ?? $document->observation, // Mantener la observación anterior si no se envía una nueva
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
        // Eliminar el documento de la base de datos
        $document->delete();

        return redirect()->route('documents.index')->with('message', 'Documento eliminado con éxito.');
    }
}
