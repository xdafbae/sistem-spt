<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RolePermissionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route untuk halaman utama
Route::get('/', function () {
    return redirect()->route('login'); // Arahkan ke halaman login
});

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Logout Route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard Route (hanya bisa diakses oleh user yang sudah login)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard'); // Arahkan ke view dashboard yang sama
    })->name('dashboard');


    // Role Management (Hanya bisa diakses oleh Admin)
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/roles', [RolePermissionController::class, 'indexRoles'])->name('roles.index');
        Route::post('/roles', [RolePermissionController::class, 'storeRole'])->name('roles.store');
        Route::put('/roles/{role}', [RolePermissionController::class, 'updateRole'])->name('roles.update');
        Route::delete('/roles/{role}', [RolePermissionController::class, 'destroyRole'])->name('roles.destroy');
        Route::post('/roles/{role}/assign-permission', [RolePermissionController::class, 'assignPermissionToRole'])->name('roles.assign-permission');
    });

    // Permission Management
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/permissions', [RolePermissionController::class, 'indexPermissions'])->name('permissions.index');
        Route::post('/permissions', [RolePermissionController::class, 'storePermission'])->name('permissions.store');
        Route::put('/permissions/{permission}', [RolePermissionController::class, 'updatePermission'])->name('permissions.update');
        Route::delete('/permissions/{permission}', [RolePermissionController::class, 'destroyPermission'])->name('permissions.destroy');
    });
    // Assign Role to User
    Route::get('/roles/assign-to-user', [RolePermissionController::class, 'assignRoleToUserForm'])->name('roles.assign-to-user-form');
    Route::post('/roles/assign-to-user', [RolePermissionController::class, 'assignRoleToUser'])->name('roles.assign-to-user');

    // Assign Permission to Role
    Route::get('/permissions/assign-to-role', [RolePermissionController::class, 'assignPermissionToRoleForm'])->name('permissions.assign-to-role-form');
    Route::post('/permissions/assign-to-role', [RolePermissionController::class, 'assignPermissionToRole'])->name('permissions.assign-to-role');
});
