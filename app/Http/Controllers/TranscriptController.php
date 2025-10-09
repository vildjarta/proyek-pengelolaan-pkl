<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transcript;

class TranscriptController extends Controller
{
    public function index()
    {
        return view('transkrip.transkrip');
    }

public function analyze(Request $req)
{
    $rows = $req->input('table');
    if(!$rows || count($rows) < 2) {
        return response()->json(['error' => 'Data transkrip tidak valid']);
    }

    $header = array_map('strtolower', $rows[0]);
    $idxSks = array_search('sks', $header);
    $idxNilai = array_search('nilai', $header);

    $totalSksD = 0;
    $hasE = false;
    $sumQuality = 0;
    $sumSks = 0;
    $map = [ 'A'  => 4.0,
    'B+' => 3.5,
    'B'  => 3.0,
    'C+' => 2.5,
    'C'  => 2.0,
    'D'  => 1.0,
    'E'  => 0.0];

    for ($i=1; $i<count($rows); $i++) {
        $r = $rows[$i];
        if (!isset($r[$idxSks]) || !isset($r[$idxNilai])) continue;

        $sks = (int)$r[$idxSks];
        $nilai = strtoupper(trim($r[$idxNilai]));

        if ($nilai === 'D') $totalSksD += $sks;
        if ($nilai === 'E') $hasE = true;

        if (isset($map[$nilai])) {
            $sumQuality += $map[$nilai] * $sks;
            $sumSks += $sks;
        }
    }

    $ipk = $sumSks > 0 ? round($sumQuality/$sumSks, 2) : null;
    $eligible = ($ipk !== null && $ipk >= 2.5 && $totalSksD <= 6 && !$hasE);

    return response()->json([
        'ipk' => $ipk,
        'total_sks_d' => $totalSksD,
        'has_e' => $hasE,
        'eligible' => $eligible
    ]);
}

    public function save(Request $request)
    {
        Transcript::updateOrCreate(
            ['nim' => $request->nim], // jika sudah ada → update
            [
                'nama_mahasiswa' => $request->nama_mahasiswa,
                'ipk' => $request->ipk,
                'total_sks_d' => $request->total_sks_d,
                'has_e' => $request->has_e,
                'eligible' => $request->eligible,
            ]
        );

        return redirect()->route('transkrip_result')->with('success', 'Hasil analisa tersimpan.');
    }

    public function saveMultiple(Request $request)
    {
        $entries = $request->input('entries', []);
        
        if (empty($entries)) {
            return redirect()->back()->with('error', 'Tidak ada data yang disimpan.');
        }

        $savedCount = 0;
        $errors = [];

        foreach ($entries as $entry) {
            try {
                // Validasi data entry
                if (empty($entry['nim']) || empty($entry['nama_mahasiswa'])) {
                    continue;
                }

                Transcript::updateOrCreate(
                    ['nim' => $entry['nim']], // jika sudah ada → update
                    [
                        'nama_mahasiswa' => $entry['nama_mahasiswa'],
                        'ipk' => $entry['ipk'] ?? 0,
                        'total_sks_d' => $entry['total_sks_d'] ?? 0,
                        'has_e' => $entry['has_e'] ?? 0,
                        'eligible' => $entry['eligible'] ?? 0,
                    ]
                );
                $savedCount++;
            } catch (\Exception $e) {
                $errors[] = "Error menyimpan NIM {$entry['nim']}: " . $e->getMessage();
            }
        }

        if ($savedCount > 0) {
            $message = "Berhasil menyimpan {$savedCount} data transkrip.";
            if (!empty($errors)) {
                $message .= " Namun ada beberapa error: " . implode(', ', $errors);
            }
            return redirect()->route('transkrip_result')->with('success', $message);
        } else {
            return redirect()->back()->with('error', 'Gagal menyimpan data. ' . implode(', ', $errors));
        }
    }

    public function results()
    {
        $data = Transcript::latest()->get();
        return view('transkrip.transkrip_result', compact('data'));
    }
}
