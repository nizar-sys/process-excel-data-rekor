<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rekor;
use App\Models\ProcessedData;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class UploadController extends Controller
{
    public function index()
    {
        Log::info('Menampilkan halaman upload');
        $uploadedFiles = Rekor::all();
        $no_rek = session('no_rek');
        $nama_rek = session('nama_rek');

        return view('upload', compact('uploadedFiles', 'no_rek', 'nama_rek'));
    }

    public function olah(Request $request)
    { {
            Log::info('Memulai proses olah data');
            $rekors = Rekor::all();
            $processedData = [];
            $saldo = 0;

            foreach ($rekors as $rekor) {
                $filePath = public_path('/uploads/' . $rekor->file_path);
                Log::info('Memproses file: ' . $filePath);

                // Tambahkan logging untuk memastikan file exist
                if (!file_exists($filePath)) {
                    Log::error('File tidak ditemukan: ' . $filePath);
                    return response()->json(['error' => 'File tidak ditemukan: ' . $filePath], 404);
                }

                try {
                    $spreadsheet = IOFactory::load($filePath);
                    Log::info('Berhasil membuka file: ' . $filePath);
                } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
                    Log::error('Reader error saat membaca file excel: ' . $e->getMessage());
                    return response()->json(['error' => 'Reader error saat membaca file excel: ' . $e->getMessage()], 500);
                } catch (\Exception $e) {
                    Log::error('Error saat membaca file excel: ' . $e->getMessage());
                    return response()->json(['error' => 'Error saat membaca file excel: ' . $e->getMessage()], 500);
                }

                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray(null, true, true, true);
                Log::info('Jumlah baris yang dibaca: ' . count($rows));
                unset($rows[1]); // Hapus header

                foreach ($rows as $row) {
                    try {
                        $data = $this->processRow($row, $saldo);
                        Log::info('Data yang diproses: ' . json_encode($data));
                        ProcessedData::create($data);

                        Rekor::create([
                            'POSTAT' => $row['A'] ?? null,
                            'PORECO' => $row['B'] ?? null,
                            'PODTVL' => isset($row['C']) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['C'])->format('Y-m-d') : null,
                            'POREFN' => $row['D'] ?? null,
                            'PODTPO' => isset($row['E']) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['E'])->format('Y-m-d') : null,
                            'POTCRO' => $row['F'] ?? null,
                            'PODESC' => $row['G'] ?? null,
                            'POAMNT' => is_numeric($row['H']) ? $row['H'] : 0,
                        ]);

                        $processedData[] = $data;
                    } catch (\Exception $e) {
                        Log::error('Kesalahan saat memproses baris: ' . json_encode($row) . ' - Pesan: ' . $e->getMessage());
                        $processedData[] = ['error' => 'Gagal memproses baris: ' . json_encode($row) . ' - Pesan: ' . $e->getMessage()];
                    }
                }
            }

            if (!empty($processedData)) {
                return response()->json(['errors' => $processedData], 400);
            }

            Session::put('processedData', $processedData);
            Log::info('Data berhasil diproses dan disimpan ke sesi.');
            return response()->json(['message' => 'Data berhasil diproses.']);
        }
    }


    public function hasil()
    {
        $processedData = Session::get('processedData', []);
        return view('hasil', compact('processedData'));
    }

    private function processRow($row, &$saldo)
    {
        $poamnt = is_numeric($row['H']) ? $row['H'] / 100 : 0;
        $debit = $poamnt < 0 ? $poamnt : 0;
        $kredit = $poamnt > 0 ? $poamnt : 0;
        $saldo += $poamnt;

        return [
            'tanggal_transaksi' => isset($row['C']) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['C'])->format('Y-m-d') : null,
            'keterangan' => $row['G'] ?? null,
            'debit' => $debit,
            'kredit' => $kredit,
            'saldo' => $saldo,
        ];
    }
}
