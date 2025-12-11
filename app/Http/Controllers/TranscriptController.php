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
     * Proses analisa PDF dengan deteksi E grade yang advanced
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

            // --- Deteksi nilai D dan E dengan Advanced Logic ---
            $totalSksD = $this->countGradeD($text);
            $hasE = $this->detectGradeEAdvanced($text, $mahasiswa, $totalSks);

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
    }    /**
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
     * Advanced E-grade detection using 3-layer system
     * Layer 1: Direct detection - search for E pattern
     * Layer 2: Count inference - if visible courses < expected (32), likely has E
     * Layer 3: Pattern analysis - D grade presence suggests possible E grades
     */
    private function detectGradeEAdvanced($text, $mahasiswa, $totalSks)
    {
        // LAYER 1: Direct E detection
        if ($this->hasGradeEDirect($text)) {
            return true;
        }

        // LAYER 2: Count inference - compare visible courses to expected
        if ($this->hasGradeEByCountInference($text, $mahasiswa)) {
            return true;
        }

        // LAYER 3: Pattern-based inference
        if ($this->hasGradeEByPattern($text)) {
            return true;
        }

        return false;
    }

    /**
     * Layer 1: Direct E-grade detection
     */
    private function hasGradeEDirect($text)
    {
        // Look for E grade in transcript
        // Pattern 1: " E " followed by decimal and numbers (e.g., "E 0.00")
        if (preg_match('/\s+E\s+[\d.]+\s+[\d.,]+/i', $text)) {
            return true;
        }

        // Pattern 2: "E" at start of grade column with numbers
        if (preg_match('/\bE\s+\d+/i', $text)) {
            return true;
        }

        // Pattern 3: "E" as standalone grade between SKS values
        if (preg_match('/\d+\s+[A-Z]{2,4}\d{3,4}\s+[^\d\s]+\s+E\s+\d+/i', $text)) {
            return true;
        }

        return false;
    }

    /**
     * Layer 2: Count inference - detect missing courses (potential E grades)
     * SIPADU standard: 32-33 courses expected
     * If visible courses < 25, likely has E grades (student didn't take or failed courses)
     */
    private function hasGradeEByCountInference($text, $mahasiswa)
    {
        $visibleCourses = $this->countVisibleCourses($text);

        // If course count is significantly below expected (32-33), likely has E
        // Threshold: < 25 courses suggests student has E grades
        return $visibleCourses > 0 && $visibleCourses < 25;
    }

    /**
     * Layer 3: Pattern-based inference
     * If D grades are present, it's more likely E grades exist too
     */
    private function hasGradeEByPattern($text)
    {
        // If we can find D grades, it suggests transcript shows failures
        // This could correlate with E grades in other semesters
        $dCount = $this->countGradeD($text);

        // If multiple D grades (3+), higher likelihood of E grades
        return $dCount >= 3;
    }

    /**
     * Count visible courses in transcript
     * Uses course code pattern: [A-Z]+[0-9]+
     * Example: AII231203, AIK231307
     */
    private function countVisibleCourses($text)
    {
        // Remove newlines to make parsing consistent
        $cleanText = preg_replace('/\s+/', ' ', $text);

        // Pattern: Look for course codes like AII231203, AIK231307
        preg_match_all('/[A-Z]+[0-9]+/', $cleanText, $matches);
        if (count($matches[0]) > 0) {
            // Filter to keep only course codes (7+ chars like AII2312 or longer)
            $codes = array_filter($matches[0], function($code) {
                return strlen($code) >= 7;
            });
            return count(array_unique($codes));
        }

        return 0;
    }

}
