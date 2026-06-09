<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:owner,admin,staff',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'is_active' => true,
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'module' => 'User',
            'action' => 'Create',
            'description' => 'Menambahkan user '.$user->name,
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role' => 'required|in:owner,admin,staff',
        ]);

        $user->update($data);

        AuditLog::create([
            'user_id' => auth()->id(),
            'module' => 'User',
            'action' => 'Update',
            'description' => 'Mengubah user '.$user->name,
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'module' => 'User',
            'action' => 'Delete',
            'description' => 'Menghapus user '.$user->name,
        ]);

        $user->delete();

        return back()->with(
            'success',
            'User berhasil dihapus.'
        );
    }

    public function toggleActive(User $user)
    {
        $user->update([
            'is_active' => !$user->is_active
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'module' => 'User',
            'action' => 'Status',
            'description' =>
                ($user->is_active ? 'Mengaktifkan ' : 'Menonaktifkan ')
                .$user->name,
        ]);

        return back()->with(
            'success',
            'Status user berhasil diperbarui.'
        );
    }
}