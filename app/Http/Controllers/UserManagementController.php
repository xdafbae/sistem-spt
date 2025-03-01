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
                    $editBtn = '<button type="button" data-id="'.$user->id.'" class="btn btn-sm btn-primary edit-user-btn"><i class="fas fa-edit"></i></button>';
                    $deleteBtn = '<button type="button" data-id="'.$user->id.'" class="btn btn-sm btn-danger delete-user-btn ms-2"><i class="fas fa-trash"></i></button>';
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
            'pangkat' => 'nullable|string|max:100',
            'jabatan' => 'nullable|string|max:100',
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
            'pangkat' => $request->pangkat,
            'jabatan' => $request->jabatan,
        ]);

        $user->assignRole($request->role);

        return response()->json(['success' => true, 'message' => 'User created successfully']);
    }

    public function show(User $user)
    {
        $user->load('roles');
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => ['nullable', Password::defaults()],
            'no_hp' => 'required|string|max:15',
            'nip' => 'nullable|string|max:20|unique:users,nip,'.$user->id,
            'pangkat' => 'nullable|string|max:100',
            'jabatan' => 'nullable|string|max:100',
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
            'pangkat' => $request->pangkat,
            'jabatan' => $request->jabatan,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);
        
        // Sync roles
        $user->syncRoles([$request->role]);

        return response()->json(['success' => true, 'message' => 'User updated successfully']);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['success' => true, 'message' => 'User deleted successfully']);
    }
}

