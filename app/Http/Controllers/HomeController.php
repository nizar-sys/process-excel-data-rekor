<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rekor;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index()
    {
        $rekors = Rekor::all();
        return view('home', compact('rekors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rekor' => 'required|file|mimes:xlsx,xls|max:2048',
            'no_rek' => 'required|string|max:50',
            'nama_rek' => 'required|string|max:100',
        ]);

        if ($request->hasFile('rekor') && $request->file('rekor')->isValid()) {
            $file = $request->file('rekor');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('/uploads');

            // Pastikan direktori tujuan ada
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Pindahkan file ke direktori tujuan
            $file->move($destinationPath, $fileName);

            // Buat jalur lengkap dari file yang dipindahkan
            $fullPath = $destinationPath . '/' . $fileName;

            try {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fullPath);
            } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
                return Redirect::back()->withErrors(['rekor' => 'Error loading file: ' . $e->getMessage()]);
            }

            $worksheet = $spreadsheet->getActiveSheet();

            $data = [];
            foreach ($worksheet->getRowIterator(2) as $row) {
                $rowData = [];
                foreach ($row->getCellIterator() as $cell) {
                    $rowData[] = $cell->getValue();
                }

                $rowData[7] = isset($rowData[7]) ? (is_numeric($rowData[7]) ? (float) $rowData[7] : 0) : 0; // Jika POAMNT kosong, isi dengan 0
                $data[] = $rowData;
            }

            foreach ($data as $rowData) {
                // jika rowData dengan indexnya tidak ada, maka jangan lanjut create
                if (!isset($rowData[0]) || !isset($rowData[1]) || !isset($rowData[2]) || !isset($rowData[3]) || !isset($rowData[4]) || !isset($rowData[5]) || !isset($rowData[6]) || !isset($rowData[7])) {
                    continue;
                }
                Rekor::create([
                    'POSTAT' => $rowData[0] ? $rowData[0] : 'N/A',
                    'PORECO' => $rowData[1] ? $rowData[1] : 'N/A',
                    'PODTVL' => $rowData[2] ? date('Y-m-d H:i:s', strtotime($rowData[2] . ' 00:00:00')) : now(),
                    'POREFN' => $rowData[3] ? $rowData[3] : 'N/A',
                    'PODTPO' => $rowData[4] ? date('Y-m-d H:i:s', strtotime($rowData[4] . ' 00:00:00')) : now(),
                    'POTCRO' => $rowData[5] ? $rowData[5] : '0',
                    'PODESC' => $rowData[6] ? $rowData[6] : 'N/A',
                    'POAMNT' => $rowData[7] ? $rowData[7] : 0,
                    'file_path' => $fileName ? $fileName : 'N/A',
                ]);
            }

            Session::put('no_rek', $request->no_rek);
            Session::put('nama_rek', $request->nama_rek);

            return Redirect::back()->with('success', 'File berhasil diunggah dan disimpan.');
        } else {
            return Redirect::back()->withErrors(['rekor' => 'File tidak valid atau tidak ditemukan.']);
        }
    }

    public function olah(Request $request)
    {
        // Dapatkan semua file rekors yang belum diproses
        $rekors = Rekor::all();

        foreach ($rekors as $rekor) {
            $filePath = storage_path('app/public/' . $rekor->file_path);

            try {
                $spreadsheet = IOFactory::load($filePath);
            } catch (\Exception $e) {
                Log::error('Gagal membaca file excel: ' . $e->getMessage());
                return response()->json(['error' => 'Gagal membaca file excel.']);
            }

            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);
            unset($rows[1]); // Hapus header

            $processedData = [];

            // Proses olah data
            foreach ($rows as $row) {
                $saldo = $row['H'] / 100; // Bagi POAMNT dengan 100 untuk mendapatkan saldo
                $keterangan = $row['G'];
                $tanggalTransaksi = isset($row['C']) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['C'])->format('Y-m-d') : null;
                $debit = $saldo < 0 ? $saldo : null; // Jika saldo negatif, itu adalah debit
                $kredit = $saldo > 0 ? $saldo : null; // Jika saldo positif, itu adalah kredit

                $processedData[] = [
                    'Tanggal Transaksi' => $tanggalTransaksi,
                    'Keterangan' => $keterangan,
                    'Debit' => $debit,
                    'Kredit' => $kredit,
                    'Saldo' => $saldo,
                ];
            }

            Log::info('Processed Data: ', $processedData); // Log data untuk debug

            // Simpan data yang telah diproses ke database
            foreach ($processedData as $data) {
                Rekor::create($data);
            }
        }

        return response()->json(['message' => 'Data berhasil diolah dan disimpan.']);
    }
}
