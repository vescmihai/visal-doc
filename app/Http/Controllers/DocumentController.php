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
        $documents = Document::with(['user', 'tramite'])->get();
    
        return Inertia::render('Documents/Index', [
            'documents' => $documents,
        ]);
    }
    

    public function create()
    {
        $tramites = Tramite::all();
    
        return Inertia::render('Documents/Create', [
            'tramites' => $tramites, 
        ]);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'file' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'tramite_id' => 'nullable|exists:tramites,id', 
        ]);

        $path = $request->file('file')->store('documents', 'public');

        Document::create([
            'user_id' => auth()->id(), 
            'type' => $validated['type'],
            'file_path' => $path,
            'tramite_id' => $validated['tramite_id'], 
        ]);

        return redirect()->route('documents.index')->with('message', 'Documento subido con éxito.');
    }

    public function update(Request $request, $id)
    {
        // Validación
        $validated = $request->validate([
            'status' => 'required|in:Pendiente,Aprobado,Rechazado',
            'observation' => 'nullable|string',
        ]);

        $document = Document::findOrFail($id);

        $document->update([
            'status' => $validated['status'],
            'observation' => $validated['observation'] ?? $document->observation, // Mantener observación anterior
        ]);

        return response()->json([
            'message' => 'Documento actualizado con éxito',
            'document' => $document,
        ]);
    }

    public function destroy(Document $document)
    {
        \Storage::disk('public')->delete($document->file_path);

        $document->delete();

        return redirect()->route('documents.index')->with('message', 'Documento eliminado con éxito.');
    }
}
