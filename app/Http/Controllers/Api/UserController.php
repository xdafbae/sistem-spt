<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->get('search');
        
        $users = User::role('karyawan')
            ->where('nama', 'LIKE', "%{$search}%")
            ->orWhere('nip', 'LIKE', "%{$search}%")
            ->select('id', 'nama', 'nip', 'pangkat', 'jabatan')
            ->limit(10)
            ->get();
            
        return response()->json($users);
    }
}