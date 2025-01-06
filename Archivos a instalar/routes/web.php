<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolePermissionController;



Route::get('/', function () {
    return view('welcome');
});

Route::resource('users', UserController::class)->middleware(['auth', 'verified']);
Route::post('users/{user}/roles', [UserController::class, 'assignRole'])->name('roles.assign');
Route::post('users/{user}/roles/remove', [UserController::class, 'removeRole'])->name('roles.remove');
Route::post('/users', [UserController::class, 'store'])->name('users.store')->middleware(['auth', 'verified']);

Route::resource('roles', RoleController::class);
Route::middleware(['auth'])->group(function () {
    Route::get('/roles-permissions', [RolePermissionController::class, 'index'])->name('roles-permission.index');
    Route::post('/roles', [RolePermissionController::class, 'storeRole'])->name('roles.store');
    Route::post('/permissions', [RolePermissionController::class, 'storePermission'])->name('permissions.store');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


Route::middleware(['auth'])->group(function () {
    // Listar roles
    Route::get('/', [RoleController::class, 'index'])->name('gestion-usuarios.index');

    // Asignar rol a un usuario
    Route::post('/{userId}/assign', [RoleController::class, 'assignRole'])->name('roles.assign');

    // Eliminar rol de un usuario
    Route::post('/{userId}/remove', [RoleController::class, 'removeRole'])->name('roles.remove');
});
