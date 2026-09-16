<?php

namespace App\Services;

use App\Models\Kendaraan;
use App\Models\PeminjamanKendaraan;

class KendaraanStatusService
{
    public function refresh(int $kendaraanId): void
    {
        $kendaraan = Kendaraan::find($kendaraanId);

        if (! $kendaraan || $kendaraan->status === Kendaraan::STATUS_TIDAK_AKTIF) {
            return;
        }

        if ($this->hasActiveServis($kendaraan)) {
            $kendaraan->update(['status' => Kendaraan::STATUS_DALAM_SERVIS]);
            return;
        }

        if ($this->hasActivePemakaian($kendaraan->id)) {
            $kendaraan->update(['status' => Kendaraan::STATUS_DIPAKAI]);
            return;
        }

        $kendaraan->update(['status' => Kendaraan::STATUS_AKTIF]);
    }

    public function refreshAll(): void
    {
        Kendaraan::query()
            ->where('status', '!=', Kendaraan::STATUS_TIDAK_AKTIF)
            ->pluck('id')
            ->each(fn (int $id) => $this->refresh($id));
    }

    public function hasActivePemakaian(int $kendaraanId, ?int $excludeId = null): bool
    {
        return PeminjamanKendaraan::where('kendaraan_id', $kendaraanId)
            ->whereIn('status', ['dipakai', 'disetujui'])
            ->when($excludeId, fn ($query) => $query->where('id', '!=', $excludeId))
            ->exists();
    }

    private function hasActiveServis(Kendaraan $kendaraan): bool
    {
        return $kendaraan->servis()
            ->whereIn('status', ['Menunggu', 'Sedang Dikerjakan'])
            ->exists();
    }
}
