<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        // Obtener todos los usuarios y roles
        $users = User::all();
        $roles = Role::all();

        return view('gestion-usuarios.index', compact('users', 'roles'));
    }

    public function assignRole(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        // Validar que el rol sea uno existente
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        // Asignar el rol al usuario
        $user->assignRole($request->role);

        return redirect()->back()->with('success', 'Rol asignado exitosamente');
    }

    public function removeRole(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        // Validar que el rol sea uno existente
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        // Eliminar el rol del usuario
        $user->removeRole($request->role);

        return redirect()->back()->with('success', 'Rol eliminado exitosamente');
    }
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        // Actualizar el nombre del rol
        $role->name = $request->input('name');
        $role->save();

        // Convertir los IDs de permisos en nombres
        $permissionIds = $request->input('permissions', []);
        $permissionNames = Permission::whereIn('id', $permissionIds)->pluck('name')->toArray();

        // Sincronizar los permisos por nombre
        $role->syncPermissions($permissionNames);
        // Sincronizar los permisos con los usuarios que tienen este rol
        $users = $role->users; // Obtener los usuarios que tienen este rol
        foreach ($users as $user) {
            $user->syncPermissions($permissionNames);  // Sincronizar los permisos del usuario
        }
        return redirect()->back()->with('success', 'Rol actualizado exitosamente.');
    }
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->back()->with('success', 'Rol eliminado exitosamente.');
    }
}
