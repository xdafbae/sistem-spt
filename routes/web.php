<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SptController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserManagementController;

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

        // Assign Role to User
        Route::get('/roles/assign-to-user', [RolePermissionController::class, 'assignRoleToUserForm'])->name('roles.assign-to-user-form');
        Route::post('/roles/assign-to-user', [RolePermissionController::class, 'assignRoleToUser'])->name('roles.assign-to-user');
    });

    // User Management
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/data', [UserManagementController::class, 'data'])->name('users.data'); // Route untuk DataTables
        Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('users.show');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    });



    // Assign Permission to Role
    Route::get('/permissions/assign-to-role', [RolePermissionController::class, 'assignPermissionToRoleForm'])->name('permissions.assign-to-role-form');
    Route::post('/permissions/assign-to-role', [RolePermissionController::class, 'assignPermissionToRole'])->name('permissions.assign-to-role');


    // SPT Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('spts', [SptController::class, 'index'])->name('spts.index');

        // Karyawan routes
        Route::middleware(['role:karyawan'])->group(function () {
            Route::get('spts/create', [SptController::class, 'create'])->name('spts.create');
            Route::post('spts', [SptController::class, 'store'])->name('spts.store');
            Route::get('spts/{spt}/edit', [SptController::class, 'edit'])->name('spts.edit');
            Route::put('spts/{spt}', [SptController::class, 'update'])->name('spts.update');
        });

        // Operator routes
        Route::middleware(['role:operator'])->group(function () {
            Route::get('spts/{spt}/verify', [SptController::class, 'verify'])->name('spts.verify');
            Route::put('spts/{spt}/verify', [SptController::class, 'verifyUpdate'])->name('spts.verify.update');
        });

        // Atasan routes
        Route::middleware(['role:atasan'])->group(function () {
            Route::get('spts/{spt}/approve', [SptController::class, 'approve'])->name('spts.approve');
            Route::put('spts/{spt}/approve', [SptController::class, 'approveUpdate'])->name('spts.approve.update');
        });

        // Common routes
        Route::get('spts/{spt}', [SptController::class, 'show'])->name('spts.show');
        Route::get('spts/{spt}/print', [SptController::class, 'print'])->name('spts.print');
        Route::get('spts/{spt}/export-word', [SptController::class, 'exportWord'])->name('spts.export-word');

        // API untuk mendapatkan NIP
        Route::get('api/get-nip', [SptController::class, 'getNip'])->name('api.get-nip');

        Route::get('/api/users/search', [App\Http\Controllers\Api\UserController::class, 'search'])
            ->name('api.users.search');
    });
});
