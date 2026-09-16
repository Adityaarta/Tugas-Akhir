<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PenggunaController extends Controller
{
    private array $roles = [
        'admin' => 'Admin',
        'pengguna_operasional' => 'Pengguna Operasional',
        'kepala_bagian' => 'Kepala Bagian',
    ];

    public function index()
    {
        $pengguna = User::latest()->get();
        $roles = $this->roles;

        return view('pengguna.index', compact('pengguna', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(array_keys($this->roles))],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect('/pengguna')->with('success', 'Data pengguna berhasil ditambah');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = $this->roles;

        return view('pengguna.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(array_keys($this->roles))],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect('/pengguna')->with('success', 'Data pengguna berhasil diupdate');
    }

    public function destroy($id)
    {
        if ((int) $id === Auth::id()) {
            return redirect('/pengguna')->with('error', 'Akun yang sedang login tidak dapat dihapus');
        }

        User::findOrFail($id)->delete();

        return redirect('/pengguna')->with('success', 'Data pengguna berhasil dihapus');
    }
}
