<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Servis;
use App\Services\KendaraanStatusService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServisController extends Controller
{
    public function __construct(private KendaraanStatusService $kendaraanStatusService)
    {
    }

    private array $jenisServis = [
        'Periodic Maintenance',
        'General Repair',
        'Body Repair',
    ];

    private array $statusServis = [
        'Menunggu',
        'Sedang Dikerjakan',
        'Selesai',
    ];

    public function index()
    {
        $servis = Servis::with('kendaraan')->latest()->get();
        $kendaraan = Kendaraan::where('status', '!=', 'tidak_aktif')->orderBy('no_polisi')->get();

        $total = Servis::count();
        $menunggu = Servis::where('status', 'Menunggu')->count();
        $proses = Servis::where('status', 'Sedang Dikerjakan')->count();
        $selesai = Servis::where('status', 'Selesai')->count();
        $jenisServis = $this->jenisServis;
        $statusServis = $this->statusServis;

        return view('servis.index', compact(
            'servis',
            'kendaraan',
            'total',
            'menunggu',
            'proses',
            'selesai',
            'jenisServis',
            'statusServis'
        ));
    }

    public function store(Request $request)
    {
        $validated = $this->validateServis($request);
        $validated = $this->attachKendaraanSnapshot($validated);
        $validated['no_servis'] = $this->generateNoServis();

        $servis = Servis::create($validated);
        $this->syncKendaraanStatus($servis);

        return redirect('/servis')->with('success', 'Data servis berhasil ditambah');
    }

    public function edit($id)
    {
        $servis = Servis::findOrFail($id);
        $kendaraan = Kendaraan::where('status', '!=', 'tidak_aktif')->orderBy('no_polisi')->get();
        $jenisServis = $this->jenisServis;
        $statusServis = $this->statusServis;

        return view('servis.edit', compact('servis', 'kendaraan', 'jenisServis', 'statusServis'));
    }

    public function update(Request $request, $id)
    {
        $servis = Servis::findOrFail($id);
        $validated = $this->validateServis($request);
        $validated = $this->attachKendaraanSnapshot($validated);

        $servis->update($validated);
        $this->syncKendaraanStatus($servis);

        return redirect('/servis')->with('success', 'Data servis berhasil diupdate');
    }

    public function destroy($id)
    {
        $servis = Servis::findOrFail($id);
        $kendaraanId = $servis->kendaraan_id;

        $servis->delete();
        $this->kendaraanStatusService->refresh($kendaraanId);

        return redirect('/servis')->with('success', 'Data servis berhasil dihapus');
    }

    public function riwayat()
    {
        $servis = Servis::with('kendaraan')->latest('tanggal_servis')->get();

        return view('servis.riwayat', compact('servis'));
    }

    private function validateServis(Request $request): array
    {
        return $request->validate([
            'kendaraan_id' => ['required', 'exists:kendaraan,id'],
            'tanggal_servis' => ['required', 'date'],
            'kilometer' => ['nullable', 'integer', 'min:0'],
            'umur_tahun' => ['nullable', 'integer', 'min:0'],
            'lama_servis' => ['required', 'integer', 'min:0'],
            'frekuensi_servis' => ['required', 'integer', 'min:1'],
            'keluhan' => ['required', 'string', 'max:255'],
            'jenis_kerusakan' => ['required', 'string', 'max:255'],
            'jenis_servis' => ['required', Rule::in($this->jenisServis)],
            'mekanik' => ['required', 'string', 'max:255'],
            'biaya' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in($this->statusServis)],
            'catatan' => ['nullable', 'string'],
        ]);
    }

    private function attachKendaraanSnapshot(array $validated): array
    {
        $kendaraan = Kendaraan::findOrFail($validated['kendaraan_id']);
        $validated['kilometer'] = $kendaraan->kilometer ?? 0;
        $validated['umur_tahun'] = $kendaraan->umur;

        return $validated;
    }

    private function generateNoServis(): string
    {
        $prefix = 'SRV-'.now()->format('Ymd').'-';
        $last = Servis::where('no_servis', 'like', $prefix.'%')
            ->orderByDesc('no_servis')
            ->first();

        $next = $last ? ((int) substr($last->no_servis, -3)) + 1 : 1;

        return $prefix.str_pad((string) $next, 3, '0', STR_PAD_LEFT);
    }

    private function syncKendaraanStatus(Servis $servis): void
    {
        $kendaraan = Kendaraan::find($servis->kendaraan_id);

        if (! $kendaraan) {
            return;
        }

        if ($servis->kilometer > (int) $kendaraan->kilometer) {
            $kendaraan->kilometer = $servis->kilometer;
        }

        $kendaraan->save();
        $this->kendaraanStatusService->refresh($servis->kendaraan_id);
    }
}
