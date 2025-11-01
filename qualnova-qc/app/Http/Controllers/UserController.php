<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Tampilkan semua user
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    // Tampilkan form tambah user
    public function create()
    {
        return view('users.create');
    }

    // Simpan user baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email'    => 'required|email|unique:users',
            'whatsapp' => [
                'required',
                'string',
                'regex:/^(?:\+62|62|0)[0-9]{9,13}$/',
                'unique:users,whatsapp',
            ],
            'role'     => 'required|string',
            'password' => 'required|min:6|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    // Tampilkan form edit
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // Update user (support PUT ke /users atau /users/{id})
    public function update(Request $request, $id = null)
    {
        // Jika ID tidak dikirim di URL, ambil dari form
        $user = $id ? User::findOrFail($id) : User::findOrFail($request->input('id'));

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'whatsapp' => [
                'required',
                'string',
                'regex:/^(?:\+62|62|0)[0-9]{9,13}$/',
                'unique:users,whatsapp,' . $user->id,
            ],
            'role'     => 'required|string',
            'password' => 'nullable|min:6|confirmed',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui!');
    }

    // Hapus user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }
}
