<?php


namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserManagementController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('users.index', compact('roles'));
    }

    public function data(Request $request)
    {
        try {
            $users = User::with('roles');

            return DataTables::of($users)
                ->addColumn('role', function ($user) {
                    return $user->roles->pluck('name')->implode(', ');
                })
                ->addColumn('action', function ($user) {
                    $editBtn = '<button type="button" data-id="'.$user->id.'" class="btn btn-sm btn-primary edit-user-btn">Edit</button>';
                    $deleteBtn = '<button type="button" data-id="'.$user->id.'" class="btn btn-sm btn-danger delete-user-btn ms-2">Hapus</button>';
                    return '<div class="d-flex">' . $editBtn . $deleteBtn . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Exception $e) {
            \Log::error('DataTables Error: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while processing your request.'], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', Password::defaults()],
            'no_hp' => 'required|string|max:15',
            'nip' => 'nullable|string|max:20|unique:users',
            'role' => 'required|exists:roles,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp,
            'nip' => $request->nip,
        ]);

        $user->assignRole($request->role);

        return response()->json(['success' => true, 'message' => 'User created successfully']);
    }

    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'password' => ['nullable', Password::defaults()],
            'no_hp' => 'required|string|max:15',
            'nip' => 'nullable|string|max:20|unique:users,nip,'.$id,
            'role' => 'required|exists:roles,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userData = [
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'nip' => $request->nip,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);
        
        // Sync roles
        $user->syncRoles([$request->role]);

        return response()->json(['success' => true, 'message' => 'User updated successfully']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully']);
    }
}