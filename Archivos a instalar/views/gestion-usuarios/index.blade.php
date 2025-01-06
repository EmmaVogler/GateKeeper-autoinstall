<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de usuarios y roles</title>
    @include('layouts.bootstrap')
</head>
<style>
    .users-list {
        display: flex;
        flex-direction: column;
        /* Una fila por cada usuario */
        gap: 20px;
        /* Espacio entre cada usuario */
    }

    .user-row {
        display: flex;
        align-items: center;
        /* Alinea los elementos al centro verticalmente */
        gap: 20px;
        /* Espacio entre los elementos dentro de la fila */
    }

    .user-name {
        font-weight: bold;
        margin-right: 10px;
        width: 150px;
        /* Puedes ajustar el tamaño según el diseño */
    }

    .role-form {
        display: flex;
        align-items: center;
        gap: 10px;
    }
</style>

<body>
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Roles') }}
            </h2>
        </x-slot>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active" aria-current="page" style="text-decoration: underline;"><a href="{{ route('gestion-usuarios.index') }}">Gestión de usuarios</a></li>
                            <li class="breadcrumb-item active"><a href="{{ route('roles-permission.index') }}">Gestión de roles y permisos</a></li>
                        </ol>
                    </nav>

                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" class="py-2 px-4 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" data-bs-toggle="modal" data-bs-target="#registerModal">
                            Registrar nuevo usuario
                        </button>
                    </div>

                    <br>
                    <!-- Mostrar los mensajes de éxito o error -->
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
                    <!-- Tabla de usuarios -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ implode(', ', $user->roles->pluck('name')->toArray()) }}</td>
                                    <td>
                                        <!-- Botón para abrir el modal de edición -->
                                        <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                                            Editar
                                        </button>

                                        <!-- Formulario para eliminar usuario -->
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este usuario?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>



                                <!-- Modal para editar usuario -->
                                <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">Editar Usuario: {{ $user->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('users.update', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <!-- Campo para el nombre del usuario -->
                                                    <div class="form-group mb-3">
                                                        <label for="name">Nombre</label>
                                                        <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $user->name }}" required>
                                                    </div>

                                                    <!-- Campo para el email -->
                                                    <div class="form-group mb-3">
                                                        <label for="email">Email</label>
                                                        <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $user->email }}" required>
                                                    </div>

                                                    <!-- Campo para el rol -->
                                                    <div class="form-group mb-3">
                                                        <label for="role">Rol</label>
                                                        <select name="role" id="role" class="form-control focus:ring-indigo-500 focus:border-indigo-500" required>
                                                            <option value="">Seleccionar rol</option>
                                                            @foreach($roles as $role)
                                                            <option value="{{ $role->name }}" @if($user->hasRole($role->name)) selected @endif>
                                                                {{ $role->name }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <button type="submit" class="py-2 px-4 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Guardar Cambios</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Modal -->
                    <x-guest-layout>
                        <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="registerModalLabel">{{ __('Register') }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="{{ route('users.store') }}">
                                            @csrf

                                            <!-- Nombre -->
                                            <div>
                                                <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Name') }}</label>
                                                <input id="name" type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                @error('name')
                                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Correo Electrónico -->
                                            <div class="mt-4">
                                                <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
                                                <input id="email" type="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                @error('email')
                                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Contraseña -->
                                            <div class="mt-4">
                                                <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password') }}</label>
                                                <input id="password" type="password" name="password" required class="mt-1 block w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                @error('password')
                                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Confirmar Contraseña -->
                                            <div class="mt-4">
                                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">{{ __('Confirm Password') }}</label>
                                                <input id="password_confirmation" type="password" name="password_confirmation" required class="mt-1 block w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                @error('password_confirmation')
                                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Rol del Usuario -->
                                            <div class="mt-4">
                                                <label for="role" class="block text-sm font-medium text-gray-700">{{ __('Role') }}</label>
                                                <select id="role" name="role" required class="mt-1 block w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                    <option value="">Seleccionar rol</option>
                                                    @foreach($roles as $role)
                                                    <option value="{{ $role->name }}"
                                                        @if($user->hasRole($role->name)) selected @endif>
                                                        {{ $role->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @error('role')
                                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Botón de Enviar -->
                                            <div class="mt-6">
                                                <button type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    {{ __('Register User') }}
                                                </button>
                                            </div>
                                        </form>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </x-guest-layout>

                </div>
            </div>
        </div>
    </x-app-layout>
    <script>
        if (window.Livewire) {
            console.log('Livewire already loaded!');
        }
    </script>
</body>

</html>