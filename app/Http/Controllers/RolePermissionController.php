<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    // Tampilkan daftar role
    public function indexRoles()
    {
        $roles = Role::all();
        $permissions = Permission::all(); // Ambil semua permissions
        return view('roles.index', compact('roles', 'permissions')); // Kirim data roles dan permissions ke view
    }
    // Tampilkan form tambah role
    public function createRole()
    {
        return view('roles.create');
    }

    // Simpan role baru
    public function storeRole(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|unique:roles,name',
            ]);
    
            Role::create(['name' => $request->name]);
    
            return redirect()->route('roles.index')->with('success', 'Role berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Log::error('Error saat menyimpan role: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan role.')->withInput();
        }
    }
    



    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id,
        ]);

        $role = Role::findOrFail($id);
        $role->update(['name' => $request->name]);

        return redirect()->route('roles.index')
            ->with('success', 'Role berhasil diperbarui.');
    }

    public function destroyRole($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role berhasil dihapus.');
    }

    // Tampilkan daftar permission
    public function indexPermissions()
    {
        $permissions = Permission::all();
        return view('permissions.index', compact('permissions'));
    }

    // Tampilkan form tambah permission
    public function createPermission()
    {
        return view('permissions.create');
    }

    // Simpan permission baru
    public function storePermission(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->name]);

        return redirect()->route('permissions.index')->with('success', 'Permission berhasil ditambahkan.');
    }


    public function editPermission($id)
    {
        $permission = Permission::findOrFail($id);
        return view('permissions.edit', compact('permission'));
    }

    /**
     * Update permission.
     */
    public function updatePermission(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $id,
        ]);

        $permission = Permission::findOrFail($id);
        $permission->update(['name' => $request->name]);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission berhasil diperbarui.');
    }

    /**
     * Hapus permission.
     */
    public function destroyPermission($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('success', 'Permission berhasil dihapus.');
    }


    // Tampilkan form untuk menetapkan role ke user
    public function assignRoleToUserForm()
    {
        $users = User::all();
        $roles = Role::all();
        return view('roles.assign-to-user', compact('users', 'roles'));
    }

    // Simpan role yang ditetapkan ke user
    public function assignRoleToUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::find($request->user_id);
        $role = Role::find($request->role_id);

        $user->assignRole($role);

        return redirect()->route('roles.assign-to-user-form')->with('success', 'Role berhasil ditetapkan ke user.');
    }

    // Tampilkan form untuk menetapkan permission ke role
    public function assignPermissionToRoleForm()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('permissions.assign-to-role', compact('roles', 'permissions'));
    }

    // Simpan permission yang ditetapkan ke role
    public function showAssignPermissionForm($roleId)
    {
        $role = Role::findOrFail($roleId);
        $permissions = Permission::all();
        return view('roles.assign-permission', compact('role', 'permissions'));
    }

    /**
     * Simpan permission yang ditetapkan ke role.
     */
    public function assignPermissionToRole(Request $request, $roleId)
    {
        $role = Role::findOrFail($roleId);
        $permissionIds = $request->input('permissions', []);

        // Ambil nama permission berdasarkan ID yang dipilih
        $permissionNames = Permission::whereIn('id', $permissionIds)->pluck('name')->toArray();

        try {
            // Sync permissions ke role
            $role->syncPermissions($permissionNames);
        } catch (\Exception $e) {
            // Log error
            \Log::error('Error syncing permissions: ' . $e->getMessage());
            return redirect()->route('roles.index')
                ->with('error', 'Terjadi kesalahan saat menetapkan permission.');
        }

        return redirect()->route('roles.index')
            ->with('success', 'Permission berhasil ditetapkan ke role.');
    }
}
