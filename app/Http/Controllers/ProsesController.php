<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rekor;
use App\Models\ProcessedData;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ProsesController extends Controller
{
    public function process(Request $request)
    {
        Log::info('Memulai proses data.');

        $rekors = Rekor::all();
        $processedData = [];
        $saldo = 0;
        foreach ($rekors as $rekor) {
            $filePath = public_path('/uploads/' . $rekor->file_path);

            if (!file_exists($filePath)) {
                Log::error('File tidak ditemukan: ' . $filePath);
                return response()->json(['error' => 'File tidak ditemukan: ' . $filePath], 404);
            }

            try {
                $spreadsheet = IOFactory::load($filePath);
                Log::info('Berhasil membuka file: ' . $filePath);
            } catch (\Exception $e) {
                Log::error('Gagal membaca file excel: ' . $e->getMessage());
                return response()->json(['error' => 'Gagal membaca file excel: ' . $e->getMessage()], 500);
            }

            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);
            unset($rows[1]); // Hapus header

            foreach ($rows as $row) {
                try {
                    $poamnt = is_numeric($row['H']) ? $row['H'] / 100 : 0;
                    $debit = $poamnt < 0 ? $poamnt : 0;
                    $kredit = $poamnt > 0 ? $poamnt : 0;
                    $saldo += $poamnt;

                    $data = [
                        'tanggal_transaksi' => isset($row['C']) ? date('Y-m-d', strtotime($row['C'])) : null,
                        'keterangan' => $row['G'] ?? null,
                        'debit' => $debit,
                        'kredit' => $kredit,
                        'saldo' => $saldo,
                    ];

                    ProcessedData::create($data);
                    Log::info('Data diproses dan disimpan: ' . json_encode($data));

                    $processedData[] = $data;
                } catch (\Exception $e) {
                    Log::error('Kesalahan saat memproses baris: ' . json_encode($row) . ' - Pesan: ' . $e->getMessage());
                    return response()->json(['error' => 'Gagal memproses data: ' . $e->getMessage()], 500);
                }
            }
        }

        Session::put('processedData', $processedData);
        Log::info('Data berhasil diproses dan disimpan ke sesi.');

        return response()->json(['message' => 'Data berhasil diproses.']);
    }

    public function showResults()
    {
        $processedData = ProcessedData::all();
        return view('hasil', compact('processedData'));
    }
}
