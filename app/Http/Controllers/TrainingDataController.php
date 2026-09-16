<?php

namespace App\Http\Controllers;

use App\Models\TrainingData;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use ZipArchive;

class TrainingDataController extends Controller
{
    private array $kelas = ['Layak', 'Perlu Servis'];

    private array $requiredColumns = [
        'umur_kendaraan',
        'jarak_tempuh_tahun',
        'frekuensi_servis_tahun',
        'km_oli',
        'km_rem',
        'km_busi',
        'km_ban',
        'kelas',
    ];

    public function index()
    {
        $trainingData = TrainingData::latest()->get();
        $kelas = $this->kelas;

        return view('training-data.index', compact('trainingData', 'kelas'));
    }

    public function store(Request $request)
    {
        TrainingData::create($this->validateTrainingData($request));

        return redirect('/training-data')->with('success', 'Data training berhasil ditambah');
    }

    public function edit($id)
    {
        $trainingData = TrainingData::findOrFail($id);
        $kelas = $this->kelas;

        return view('training-data.edit', compact('trainingData', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $trainingData = TrainingData::findOrFail($id);
        $trainingData->update($this->validateTrainingData($request));

        return redirect('/training-data')->with('success', 'Data training berhasil diupdate');
    }

    public function destroy($id)
    {
        TrainingData::findOrFail($id)->delete();

        return redirect('/training-data')->with('success', 'Data training berhasil dihapus');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt,xlsx'],
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        $rows = $extension === 'xlsx'
            ? $this->readXlsx($file->getRealPath())
            : $this->readCsv($file->getRealPath());

        if (count($rows) < 2) {
            return back()->withErrors(['file' => 'File import harus memiliki header dan minimal 1 baris data.']);
        }

        $header = array_map(fn ($value) => $this->normalizeHeader((string) $value), array_shift($rows));
        $missingColumns = array_diff($this->requiredColumns, $header);

        if (! empty($missingColumns)) {
            return back()->withErrors(['file' => 'Kolom wajib belum lengkap: '.implode(', ', $this->displayColumns($missingColumns))]);
        }

        $imported = 0;
        $skipped = 0;

        foreach ($rows as $rowIndex => $row) {
            if ($this->isEmptyRow($row)) {
                continue;
            }

            $data = $this->mapRow($header, $row);
            $validator = Validator::make($data, $this->rules());

            if ($validator->fails()) {
                $skipped++;
                continue;
            }

            TrainingData::create($validator->validated());
            $imported++;
        }

        return redirect('/training-data')
            ->with('success', "Import selesai. {$imported} data masuk, {$skipped} baris dilewati.");
    }

    public function template(): Response
    {
        $content = "umur_kendaraan,jarak_tempuh_tahun,servis_kali_tahun,km_oli,km_rem,km_busi,km_ban,kelas\n";
        $content .= "5,30000,1,7000,25000,22000,45000,Perlu Servis\n";
        $content .= "2,15000,4,3000,12000,10000,20000,Layak\n";

        return response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_training_data.csv"',
        ]);
    }

    private function validateTrainingData(Request $request): array
    {
        return $request->validate($this->rules());
    }

    private function rules(): array
    {
        return [
            'umur_kendaraan' => ['required', 'integer', 'min:0'],
            'jarak_tempuh_tahun' => ['required', 'integer', 'min:0'],
            'frekuensi_servis_tahun' => ['required', 'integer', 'min:0'],
            'km_oli' => ['required', 'integer', 'min:0'],
            'km_rem' => ['required', 'integer', 'min:0'],
            'km_busi' => ['required', 'integer', 'min:0'],
            'km_ban' => ['required', 'integer', 'min:0'],
            'kelas' => ['required', Rule::in($this->kelas)],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    private function readCsv(string $path): array
    {
        $rows = [];
        $handle = fopen($path, 'r');

        if ($handle === false) {
            return $rows;
        }

        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            $rows[] = $row;
        }

        fclose($handle);

        return $rows;
    }

    private function readXlsx(string $path): array
    {
        $zip = new ZipArchive();

        if ($zip->open($path) !== true) {
            return [];
        }

        $sharedStrings = $this->readSharedStrings($zip);
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if ($sheetXml === false) {
            return [];
        }

        $sheet = simplexml_load_string($sheetXml);
        $rows = [];

        foreach ($sheet->sheetData->row as $row) {
            $cells = [];

            foreach ($row->c as $cell) {
                $columnIndex = $this->columnIndex((string) $cell['r']);
                $cells[$columnIndex] = $this->cellValue($cell, $sharedStrings);
            }

            if (! empty($cells)) {
                ksort($cells);
                $rows[] = array_values($cells);
            }
        }

        return $rows;
    }

    private function readSharedStrings(ZipArchive $zip): array
    {
        $xml = $zip->getFromName('xl/sharedStrings.xml');

        if ($xml === false) {
            return [];
        }

        $sharedStrings = [];
        $data = simplexml_load_string($xml);

        foreach ($data->si as $item) {
            $parts = [];

            if (isset($item->t)) {
                $parts[] = (string) $item->t;
            }

            if (isset($item->r)) {
                foreach ($item->r as $run) {
                    $parts[] = (string) $run->t;
                }
            }

            $sharedStrings[] = implode('', $parts);
        }

        return $sharedStrings;
    }

    private function cellValue(object $cell, array $sharedStrings): string
    {
        $type = (string) $cell['t'];
        $value = (string) $cell->v;

        if ($type === 's') {
            return $sharedStrings[(int) $value] ?? '';
        }

        if ($type === 'inlineStr' && isset($cell->is->t)) {
            return (string) $cell->is->t;
        }

        return $value;
    }

    private function columnIndex(string $cellReference): int
    {
        $letters = preg_replace('/[^A-Z]/', '', strtoupper($cellReference));
        $index = 0;

        foreach (str_split($letters) as $letter) {
            $index = ($index * 26) + (ord($letter) - 64);
        }

        return $index - 1;
    }

    private function normalizeHeader(string $header): string
    {
        $normalized = strtolower(trim($header));
        $normalized = str_replace([' ', '-', '/', '.'], '_', $normalized);

        return match ($normalized) {
            'umur', 'umur_kendaraan_tahun' => 'umur_kendaraan',
            'jarak_tempuh', 'jarak_tempuh_per_tahun', 'jarak_tempuh_tahunan' => 'jarak_tempuh_tahun',
            'servis', 'service', 'servis_kali_tahun', 'servis_kali_thn', 'servis_kali_per_tahun', 'frekuensi_servis', 'frekuensi_service', 'frekuensi_servis_per_tahun' => 'frekuensi_servis_tahun',
            'oli', 'km_oli_mesin' => 'km_oli',
            'rem', 'km_kampas_rem' => 'km_rem',
            'busi', 'km_busi_kendaraan' => 'km_busi',
            'ban', 'km_ban_kendaraan' => 'km_ban',
            'label', 'hasil', 'status' => 'kelas',
            default => $normalized,
        };
    }

    private function displayColumns(array $columns): array
    {
        return array_map(
            fn ($column) => $column === 'frekuensi_servis_tahun' ? 'servis_kali_tahun' : $column,
            $columns
        );
    }

    private function mapRow(array $header, array $row): array
    {
        $data = [];

        foreach ($header as $index => $column) {
            $data[$column] = trim((string) ($row[$index] ?? ''));
        }

        if (isset($data['kelas'])) {
            $kelas = strtolower($data['kelas']);
            $data['kelas'] = str_contains($kelas, 'perlu') ? 'Perlu Servis' : 'Layak';
        }

        return $data;
    }

    private function isEmptyRow(array $row): bool
    {
        return collect($row)->filter(fn ($value) => trim((string) $value) !== '')->isEmpty();
    }
}
