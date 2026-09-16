<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\PeminjamanKendaraan;
use App\Models\Sopir;
use App\Services\KendaraanStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PeminjamanKendaraanController extends Controller
{
    public function __construct(private KendaraanStatusService $kendaraanStatusService)
    {
    }

    public function index()
    {
        $this->kendaraanStatusService->refreshAll();

        $peminjaman = PeminjamanKendaraan::with(['sopir', 'kendaraan', 'approver'])
            ->latest()
            ->get();

        $sopir = Sopir::orderBy('nama')->get();
        $kendaraan = Kendaraan::where('status', 'aktif')
            ->orderBy('no_polisi')
            ->get();

        $total = PeminjamanKendaraan::count();
        $dipakai = PeminjamanKendaraan::whereIn('status', ['dipakai', 'disetujui'])->count();
        $dikembalikan = PeminjamanKendaraan::where('status', 'dikembalikan')->count();

        return view('peminjaman.index', compact(
            'peminjaman',
            'sopir',
            'kendaraan',
            'total',
            'dipakai',
            'dikembalikan'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sopir_id' => ['required', 'exists:sopir,id'],
            'kendaraan_id' => ['required', 'exists:kendaraan,id'],
            'tanggal_pinjam' => ['required', 'date'],
            'tanggal_kembali' => ['nullable', 'date', 'after_or_equal:tanggal_pinjam'],
            'tujuan' => ['required', 'string', 'max:255'],
            'catatan' => ['nullable', 'string'],
        ]);

        $kendaraan = Kendaraan::findOrFail($validated['kendaraan_id']);

        if ($kendaraan->status !== 'aktif' || $this->kendaraanStatusService->hasActivePemakaian($kendaraan->id)) {
            return back()->withInput()->with('error', 'Kendaraan sedang tidak tersedia untuk dipakai');
        }

        $validated['user_id'] = Auth::id();
        $validated['km_awal'] = $kendaraan->kilometer ?? 0;
        $validated['status'] = 'dipakai';
        $validated['approved_by'] = Auth::id();
        $validated['approved_at'] = now();

        PeminjamanKendaraan::create($validated);
        $this->kendaraanStatusService->refresh($kendaraan->id);

        return redirect('/data-pemakai')->with('success', 'Data pemakai kendaraan berhasil ditambah');
    }

    public function edit($id)
    {
        $peminjaman = PeminjamanKendaraan::findOrFail($id);
        $sopir = Sopir::orderBy('nama')->get();
        $kendaraan = Kendaraan::where(function ($query) use ($peminjaman) {
                $query->where('status', 'aktif')
                    ->orWhere('id', $peminjaman->kendaraan_id);
            })
            ->orderBy('no_polisi')
            ->get();

        return view('peminjaman.edit', compact('peminjaman', 'sopir', 'kendaraan'));
    }

    public function update(Request $request, $id)
    {
        $peminjaman = PeminjamanKendaraan::findOrFail($id);
        $oldKendaraanId = $peminjaman->kendaraan_id;

        $validated = $request->validate([
            'sopir_id' => ['required', 'exists:sopir,id'],
            'kendaraan_id' => ['required', 'exists:kendaraan,id'],
            'tanggal_pinjam' => ['required', 'date'],
            'tanggal_kembali' => ['nullable', 'date', 'after_or_equal:tanggal_pinjam'],
            'tujuan' => ['required', 'string', 'max:255'],
            'km_akhir' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['dipakai', 'disetujui', 'ditolak', 'dikembalikan'])],
            'catatan' => ['nullable', 'string'],
        ]);

        $kendaraan = Kendaraan::findOrFail($validated['kendaraan_id']);
        $validated['user_id'] = $peminjaman->user_id ?: Auth::id();
        $validated['km_awal'] = (int) $validated['kendaraan_id'] === (int) $oldKendaraanId
            ? ($peminjaman->km_awal ?? $kendaraan->kilometer ?? 0)
            : ($kendaraan->kilometer ?? 0);

        if (in_array($validated['status'], ['dipakai', 'disetujui'], true)
            && ($kendaraan->status !== 'aktif' && (int) $validated['kendaraan_id'] !== (int) $oldKendaraanId)
        ) {
            return back()->withInput()->with('error', 'Kendaraan sedang tidak tersedia untuk dipakai');
        }

        if (in_array($validated['status'], ['dipakai', 'disetujui'], true) && $this->kendaraanStatusService->hasActivePemakaian($validated['kendaraan_id'], $peminjaman->id)) {
            return back()->withInput()->with('error', 'Kendaraan masih memiliki pemakaian aktif');
        }

        if ($validated['status'] === 'dikembalikan') {
            $validated['returned_at'] = now();
            $this->updateKilometerKendaraan((int) $validated['kendaraan_id'], $validated['km_akhir'] ?? null);
        }

        if (in_array($validated['status'], ['dipakai', 'disetujui'], true) && ! in_array($peminjaman->status, ['dipakai', 'disetujui'], true)) {
            $validated['approved_by'] = Auth::id();
            $validated['approved_at'] = now();
        }

        $peminjaman->update($validated);
        $this->kendaraanStatusService->refresh($oldKendaraanId);
        $this->kendaraanStatusService->refresh((int) $validated['kendaraan_id']);

        return redirect('/data-pemakai')->with('success', 'Data pemakai berhasil diupdate');
    }

    public function approve($id)
    {
        $peminjaman = PeminjamanKendaraan::findOrFail($id);

        if ($this->kendaraanStatusService->hasActivePemakaian($peminjaman->kendaraan_id, $peminjaman->id)) {
            return redirect('/data-pemakai')->with('error', 'Kendaraan masih memiliki pemakaian aktif');
        }

        $peminjaman->update([
            'status' => 'dipakai',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);
        $this->kendaraanStatusService->refresh($peminjaman->kendaraan_id);

        return redirect('/data-pemakai')->with('success', 'Pemakaian kendaraan berhasil diaktifkan');
    }

    public function reject($id)
    {
        $peminjaman = PeminjamanKendaraan::findOrFail($id);
        $kendaraanId = $peminjaman->kendaraan_id;

        $peminjaman->update([
            'status' => 'ditolak',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        $this->kendaraanStatusService->refresh($kendaraanId);

        return redirect('/data-pemakai')->with('success', 'Data pemakai berhasil ditolak');
    }

    public function returnForm($id)
    {
        $peminjaman = PeminjamanKendaraan::with(['sopir', 'kendaraan'])->findOrFail($id);

        return view('peminjaman.return', compact('peminjaman'));
    }

    public function returnStore(Request $request, $id)
    {
        $peminjaman = PeminjamanKendaraan::findOrFail($id);

        $validated = $request->validate([
            'tanggal_kembali' => ['required', 'date', 'after_or_equal:'.$peminjaman->tanggal_pinjam->format('Y-m-d')],
            'km_akhir' => ['required', 'integer', 'min:'.($peminjaman->km_awal ?? 0)],
            'catatan' => ['nullable', 'string'],
        ]);

        $peminjaman->update([
            'tanggal_kembali' => $validated['tanggal_kembali'],
            'km_akhir' => $validated['km_akhir'],
            'catatan' => $validated['catatan'] ?? $peminjaman->catatan,
            'status' => 'dikembalikan',
            'returned_at' => now(),
        ]);

        $this->updateKilometerKendaraan($peminjaman->kendaraan_id, $validated['km_akhir']);
        $this->kendaraanStatusService->refresh($peminjaman->kendaraan_id);

        return redirect('/data-pemakai')->with('success', 'Kendaraan berhasil dikembalikan');
    }

    public function destroy($id)
    {
        $peminjaman = PeminjamanKendaraan::findOrFail($id);
        $kendaraanId = $peminjaman->kendaraan_id;

        $peminjaman->delete();
        $this->kendaraanStatusService->refresh($kendaraanId);

        return redirect('/data-pemakai')->with('success', 'Data pemakai berhasil dihapus');
    }

    private function updateKilometerKendaraan(int $kendaraanId, ?int $kilometer): void
    {
        if ($kilometer === null) {
            return;
        }

        $kendaraan = Kendaraan::find($kendaraanId);

        if ($kendaraan && $kilometer > (int) $kendaraan->kilometer) {
            $kendaraan->update(['kilometer' => $kilometer]);
        }
    }

}
