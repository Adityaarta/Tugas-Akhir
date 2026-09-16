<?php

namespace App\Http\Controllers;

use App\Models\Sopir;
use Illuminate\Http\Request;

class SopirController extends Controller
{
    public function index()
    {
        $sopir = Sopir::latest()->get();

        return view('sopir.index', compact('sopir'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'no_sim' => ['nullable', 'string', 'max:100'],
            'no_hp' => ['nullable', 'string', 'max:30'],
        ]);

        Sopir::create($validated);

        return redirect('/sopir')->with('success', 'Data sopir berhasil ditambah');
    }

    public function edit($id)
    {
        $sopir = Sopir::findOrFail($id);

        return view('sopir.edit', compact('sopir'));
    }

    public function update(Request $request, $id)
    {
        $sopir = Sopir::findOrFail($id);

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'no_sim' => ['nullable', 'string', 'max:100'],
            'no_hp' => ['nullable', 'string', 'max:30'],
        ]);

        $sopir->update($validated);

        return redirect('/sopir')->with('success', 'Data sopir berhasil diupdate');
    }

    public function destroy($id)
    {
        Sopir::findOrFail($id)->delete();

        return redirect('/sopir')->with('success', 'Data sopir berhasil dihapus');
    }
}
