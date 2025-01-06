@include('layouts.bootstrap')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Roles y Permisos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('gestion-usuarios.index') }}">Gestión de usuarios</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('roles-permission.index') }}" style="text-decoration: underline;">Gestión de roles y permisos</a></li>
                    </ol>
                </nav>
                <!-- Mostrar alertas -->
                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <h3 class="mt-2 text-lg">Roles Existentes:</h3>
                <table class="table table-bordered mt-4">
                    <thead>
                        <tr>
                            <th>Rol</th>
                            <th>Permisos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>
                                @foreach($role->permissions as $permission)
                                <span class="badge rounded-pill text-bg-secondary text-base">{{ $permission->name }}</span>@if(!$loop->last) @endif
                                @endforeach
                            </td>
                            <td>
                                <!-- Botón para editar -->
                                <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editRoleModal{{ $role->id }}">
                                    Editar
                                </button>

                                <!-- Formulario para eliminar -->
                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este rol?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal para editar el rol -->
                        <div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1" aria-labelledby="editRoleModalLabel{{ $role->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editRoleModalLabel{{ $role->id }}">Editar Rol: {{ $role->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('roles.update', $role->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <!-- Editar nombre del rol -->
                                            <div class="mb-3">
                                                <label for="role_name_{{ $role->id }}" class="form-label">Nombre del Rol</label>
                                                <input type="text" name="name" id="role_name_{{ $role->id }}" class="form-control" value="{{ $role->name }}" required>
                                            </div>

                                            @foreach($permissions as $permission)
                                            <div class="form-check">
                                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission_{{ $role->id }}_{{ $permission->id }}" class="form-check-input"
                                                    @if($role->permissions->contains($permission)) checked @endif>
                                                <label for="permission_{{ $role->id }}_{{ $permission->id }}" class="form-check-label">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                            @endforeach


                                            <!-- Botón para guardar cambios -->
                                            <div class="d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3>Crear rol</h3>
                <hr>
                <!-- Formulario para crear un rol -->
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf
                    <div class="mt-4 mb-2">
                        <label for="role-name" class="block text-sm font-medium text-gray-700">Nombre del Rol</label>
                        <input type="text" name="name" id="role-name" class="mt-1 block w-full" required>
                    </div>

                    <div class="mb-4">
                        <label for="permissions" class="block text-sm font-medium text-gray-700">Permisos</label>
                        <select name="permissions[]" id="permissions" multiple class="mt-1 block w-full">
                            @foreach($permissions as $permission)
                            <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="py-2 px-4 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Crear Rol</button>
                </form>
            </div>
        </div>
        <div class="mt-4 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3>Crear permiso</h3>
                <hr>
                <!-- Formulario para crear un permiso -->
                <form action="{{ route('permissions.store') }}" method="POST" class="mt-4">
                    @csrf
                    <div class="mb-4">
                        <label for="permission-name" class="block text-sm font-medium text-gray-700">Nombre del Permiso</label>
                        <input type="text" name="name" id="permission-name" class="mt-1 block w-full" required>
                    </div>

                    <button type="submit" class="py-2 px-4 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Crear Permiso</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>