<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validar los datos del formulario
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'confirmed', Password::defaults()],
                'role' => ['required', 'string', 'exists:roles,name']
            ]);

            // Crear el usuario
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // Asignar el rol
            $role = Role::findByName($validated['role']); // Buscar el rol por nombre
            $user->assignRole($role); // Asignar el rol al usuario

            return back()->with('success', 'Usuario creado exitosamente');
        } catch (ValidationException $e) {
            return back()->route('users.create')->withErrors($e->errors());
        } catch (\Exception $e) {
            return back()->route('users.create')->with('error', 'Error inesperado al crear el usuario');
        }
    }
    // Método para mostrar el formulario de edición (en el modal)
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    // Método para actualizar el usuario
    public function update(Request $request, $id)
    {
        // Validación
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|string|exists:roles,name',
        ]);

        try {
            $user = User::findOrFail($id);
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->save();

            // Asignar el nuevo rol
            $user->syncRoles($validated['role']);

            // Asignar permisos al usuario basado en su rol
            $role = Role::where('name', $validated['role'])->first();
            if ($role) {
                // Obtener los permisos asociados al rol
                $permissions = $role->permissions;
                // Asignar esos permisos al usuario
                $user->syncPermissions( $permissions);
            }

            return redirect()->back()->with('success', 'Usuario actualizado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar el usuario: ' . $e->getMessage());
        }
    }


    // Método para eliminar usuario
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->back()->with('success', 'Usuario eliminado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }
}
