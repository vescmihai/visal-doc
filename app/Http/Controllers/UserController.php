<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Mostrar usuarios con paginación.
     */
    public function index()
    {
        $users = User::paginate(10); // Paginación de 10 usuarios por página
        return inertia('Users/Index', [
            'users' => $users,
            'successMessage' => session('success'), // Enviar mensaje de éxito al frontend
        ]);
    }

    /**
     * Mostrar formulario para crear un usuario.
     */
    public function create()
    {
        return inertia('Users/Create');
    }

    /**
     * Almacenar un nuevo usuario.
     */
    public function store(Request $request)
{
    // Validación de los datos
    $request->validate([
        'name' => 'required|string|min:4|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
        'role' => 'required|in:cliente,gestor,admin',
    ]);

    try {
        // Creación del usuario
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Redirección con mensaje de éxito
        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
    } catch (\Exception $e) {
        // Registrar el error en los logs
        \Log::error('Error al crear usuario: ' . $e->getMessage());

        // Redirección con mensaje de error
        return redirect()->back()->withErrors([
            'error' => 'Ocurrió un error inesperado al intentar crear el usuario. Inténtelo de nuevo más tarde.',
        ])->withInput();
    }
}


    /**
     * Mostrar formulario de edición de usuario.
     */
    public function edit(User $user)
    {
        return inertia('Users/Edit', [
            'user' => $user,
        ]);
    }

    /**
     * Actualizar datos de un usuario existente.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|min:4|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:cliente,gestor,admin',
        ]);

        try {
            $user->update($request->only('name', 'email', 'role'));

            return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Ocurrió un error al actualizar el usuario.']);
        }
    }

    /**
     * Eliminar un usuario.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Ocurrió un error al eliminar el usuario.']);
        }
    }
}
