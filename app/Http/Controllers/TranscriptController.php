<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transcript;
use App\Models\Mahasiswa;
use Smalot\PdfParser\Parser;

class TranscriptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Transcript::latest()->get();
        return view('transkrip.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('transkrip.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|unique:transcripts,nim',
            'nama_mahasiswa' => 'required',
            'ipk' => 'required|numeric|min:0|max:4',
            'total_sks_d' => 'required|integer|min:0',
            'has_e' => 'required|boolean',
        ]);

        // Hitung kelayakan
        $eligible = ($request->ipk >= 2.5 && $request->total_sks_d <= 6 && !$request->has_e);

        Transcript::create([
            'nim' => $request->nim,
            'nama_mahasiswa' => $request->nama_mahasiswa,
            'ipk' => $request->ipk,
            'total_sks_d' => $request->total_sks_d,
            'has_e' => $request->has_e,
            'eligible' => $eligible,
        ]);

        return redirect()->route('transkrip.index')->with('success', 'Data transkrip berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transkrip = Transcript::findOrFail($id);
        return view('transkrip.show', compact('transkrip'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $transkrip = Transcript::findOrFail($id);
        return view('transkrip.edit', compact('transkrip'));
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $transkrip = Transcript::findOrFail($id);

        $request->validate([
            'nim' => 'required|unique:transcripts,nim,' . $id,
            'nama_mahasiswa' => 'required',
            'ipk' => 'required|numeric|min:0|max:4',
            'total_sks_d' => 'required|integer|min:0',
            'has_e' => 'required|boolean',
        ]);

        // Hitung kelayakan
        $eligible = ($request->ipk >= 2.5 && $request->total_sks_d <= 6 && !$request->has_e);

        $transkrip->update([
            'nim' => $request->nim,
            'nama_mahasiswa' => $request->nama_mahasiswa,
            'ipk' => $request->ipk,
            'total_sks_d' => $request->total_sks_d,
            'has_e' => $request->has_e,
            'eligible' => $eligible,
        ]);

        return redirect()->route('transkrip.index')->with('success', 'Data transkrip berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transkrip = Transcript::findOrFail($id);
        $transkrip->delete();

        return redirect()->route('transkrip.index')->with('success', 'Data transkrip berhasil dihapus.');
    }

    /**
     * Halaman upload dan analisa PDF
     */
    public function analyzePdfView()
    {
        return view('transkrip.analyze-pdf');
    }

    /**
     * Proses analisa PDF
     */
    public function uploadPdf(Request $req)
    {
        try {
            $req->validate([
                'nim' => 'required|exists:mahasiswa,nim',
                'pdf' => 'required|mimes:pdf|max:2048',
            ]);

            $nim = $req->nim;
            $mahasiswa = Mahasiswa::where('nim', $nim)->first();
            if (!$mahasiswa) {
                return response()->json(['error' => 'Mahasiswa dengan NIM tersebut tidak ditemukan.'], 400);
            }

            $file = $req->file('pdf');

            try {
                $parser = new Parser();
                $pdf = $parser->parseFile($file->getRealPath());
                $text = $pdf->getText();
            } catch (\Exception $e) {
                return response()->json(['error' => 'Gagal membaca file PDF. Pastikan file PDF valid dan tidak terenkripsi.'], 400);
            }

            if (empty($text)) {
                return response()->json(['error' => 'PDF tidak mengandung teks yang dapat dibaca. Gunakan transkrip PDF format teks, bukan scan/image.'], 400);
            }

            // --- Ekstrak IPK dari PDF (multiple patterns) ---
            $ipk = $this->extractIPK($text);

            // --- Ekstrak Total SKS (multiple patterns) ---
            $totalSks = $this->extractTotalSKS($text);
            $totalMutu = $this->extractTotalMutu($text);

            // Hitung IPK dari mutu / sks jika tidak tertulis di file
            if (!$ipk && $totalSks && $totalMutu) {
                $ipk = round($totalMutu / $totalSks, 2);
            }

            // --- Deteksi nilai D dan E ---
            $totalSksD = $this->countGradeD($text);
            $hasE = $this->hasGradeE($text);

            // --- Validasi data yang sudah diekstrak ---
            if ($ipk === null || $ipk < 0) {
                $ipk = 0;
            }
            if ($totalSksD === null || $totalSksD < 0) {
                $totalSksD = 0;
            }

            // --- Tentukan kelayakan PKL ---
            // Kriteria: IPK >= 2.5, Total SKS D <= 6, dan tidak ada nilai E
            $eligible = ($ipk >= 2.5 && $totalSksD <= 6 && !$hasE);

            // --- Simpan otomatis ke database ---
            Transcript::updateOrCreate(
                ['nim' => $nim],
                [
                    'nama_mahasiswa' => $mahasiswa->nama,
                    'ipk' => $ipk ?? 0,
                    'total_sks_d' => $totalSksD ?? 0,
                    'has_e' => $hasE,
                    'eligible' => $eligible,
                ]
            );

            return response()->json([
                'ipk' => $ipk,
                'total_sks_d' => $totalSksD,
                'has_e' => $hasE,
                'eligible' => $eligible,
                'message' => 'Data berhasil dianalisis dan disimpan',
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validasi gagal: ' . implode(', ', $e->errors()['pdf'] ?? [])], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Extract IPK from PDF text with multiple patterns
     */
    private function extractIPK($text)
    {
        // Pattern 1: "Index Prestasi Kumulatif : X.XX"
        if (preg_match('/Index\s+Prestasi\s+Kumulatif\s*[:=]?\s*([\d.,]+)/i', $text, $matches)) {
            return floatval(str_replace(',', '.', $matches[1]));
        }

        // Pattern 2: "IPK : X.XX"
        if (preg_match('/\bIPK\s*[:=]?\s*([\d.,]+)/i', $text, $matches)) {
            return floatval(str_replace(',', '.', $matches[1]));
        }

        // Pattern 3: "Indeks Prestasi : X.XX"
        if (preg_match('/Indeks\s+Prestasi\s*[:=]?\s*([\d.,]+)/i', $text, $matches)) {
            return floatval(str_replace(',', '.', $matches[1]));
        }

        // Pattern 4: "A.A : X.XX" (format tertentu)
        if (preg_match('/\bA\.A\s*[:=]?\s*([\d.,]+)/i', $text, $matches)) {
            return floatval(str_replace(',', '.', $matches[1]));
        }

        return null;
    }

    /**
     * Extract Total SKS from PDF text
     */
    private function extractTotalSKS($text)
    {
        // Pattern 1: "Jumlah SKS Yang Lulus : XXX"
        if (preg_match('/Jumlah\s+SKS\s+(?:Yang\s+)?Lulus\s*[:=]?\s*([\d.,]+)/i', $text, $matches)) {
            return floatval(str_replace(',', '.', $matches[1]));
        }

        // Pattern 2: "Total SKS : XXX"
        if (preg_match('/Total\s+SKS\s*[:=]?\s*([\d.,]+)/i', $text, $matches)) {
            return floatval(str_replace(',', '.', $matches[1]));
        }

        // Pattern 3: "SKS Total : XXX"
        if (preg_match('/SKS\s+Total\s*[:=]?\s*([\d.,]+)/i', $text, $matches)) {
            return floatval(str_replace(',', '.', $matches[1]));
        }

        return null;
    }

    /**
     * Extract Total Mutu (nilai) from PDF text
     */
    private function extractTotalMutu($text)
    {
        // Pattern 1: "Jumlah Mutu : XXXX.X"
        if (preg_match('/Jumlah\s+Mutu\s*[:=]?\s*([\d.,]+)/i', $text, $matches)) {
            return floatval(str_replace(',', '.', $matches[1]));
        }

        // Pattern 2: "Total Nilai : XXXX.X"
        if (preg_match('/Total\s+Nilai\s*[:=]?\s*([\d.,]+)/i', $text, $matches)) {
            return floatval(str_replace(',', '.', $matches[1]));
        }

        // Pattern 3: "Jumlah Bobot : XXXX.X"
        if (preg_match('/Jumlah\s+Bobot\s*[:=]?\s*([\d.,]+)/i', $text, $matches)) {
            return floatval(str_replace(',', '.', $matches[1]));
        }

        return null;
    }

    /**
     * Count grades with D (SKS D)
     */
    private function countGradeD($text)
    {
        // Look for "D" grades in transcript
        // Count occurrences of " D " or "D " in grade columns
        preg_match_all('/\s+D\s+[\d.]+\s+[\d.,]+/i', $text, $matches);

        if (count($matches[0]) > 0) {
            return count($matches[0]);
        }

        // Alternative: count line with D grade pattern
        preg_match_all('/\bD\s+\d+/i', $text, $matches);
        return count($matches[0]);
    }

    /**
     * Check if there's any grade E
     */
    private function hasGradeE($text)
    {
        // Look for E grade in transcript
        // Pattern: " E " or "E " followed by numbers (SKS and value)
        return preg_match('/\s+E\s+[\d.]+\s+[\d.,]+/i', $text) > 0 ||
               preg_match('/\bE\s+\d+/i', $text) > 0;
    }

}
