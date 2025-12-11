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
        $req->validate([
            'nim' => 'required|exists:mahasiswa,nim',
            'pdf' => 'required|mimes:pdf|max:2048',
        ]);

        $nim = $req->nim;
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        if (!$mahasiswa) {
            return response()->json(['error' => 'Mahasiswa dengan NIM tersebut tidak ditemukan.']);
        }

        $file = $req->file('pdf');
        $parser = new Parser();
        $pdf = $parser->parseFile($file->getRealPath());
        $text = $pdf->getText();

        // --- Ambil nilai dari teks PDF ---
        preg_match('/Jumlah SKS Yang Lulus\s*[:=]?\s*([\d.,]+)/i', $text, $sksMatch);
        preg_match('/Jumlah Mutu\s*[:=]?\s*([\d.,]+)/i', $text, $mutuMatch);
        preg_match('/Index Prestasi Kumulatif.*?([\d.,]+)/i', $text, $ipkMatch);

        $totalSks = isset($sksMatch[1]) ? floatval(str_replace(',', '.', $sksMatch[1])) : null;
        $totalMutu = isset($mutuMatch[1]) ? floatval(str_replace(',', '.', $mutuMatch[1])) : null;
        $ipk = isset($ipkMatch[1]) ? floatval(str_replace(',', '.', $ipkMatch[1])) : null;

        // Hitung IPK dari mutu / sks jika tidak tertulis di file
        if (!$ipk && $totalSks && $totalMutu) {
            $ipk = round($totalMutu / $totalSks, 2);
        }

        // --- Deteksi nilai D dan E ---
        preg_match_all('/\bD\b\s*\d+/i', $text, $dMatches);
        $totalSksD = count($dMatches[0]);

        $hasE = preg_match('/\bE\b/i', $text) > 0;

        // --- Tentukan kelayakan PKL ---
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
        ]);
    }

}
