<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bobot;

class AHPController extends Controller
{
    // Kriteria AHP
    private $kriteria = [
        'jumlah_mahasiswa' => 'Jumlah Mahasiswa',
        'fasilitas' => 'Fasilitas',
        'hari_operasi' => 'Hari Operasi',
        'level_legalitas' => 'Level Legalitas',
    ];

    // Menampilkan halaman AHP
    public function index()
    {
        $kriteria = $this->kriteria;
        $pairs = $this->generatePairs();
        $bobot = Bobot::first();
        $hasilPerhitungan = session('hasil_perhitungan');
        
        return view('ahp.ahp', compact('kriteria', 'pairs', 'bobot', 'hasilPerhitungan'));
    }

    // Generate pasangan kriteria untuk perbandingan
    private function generatePairs()
    {
        $keys = array_keys($this->kriteria);
        $pairs = [];
        
        for ($i = 0; $i < count($keys); $i++) {
            for ($j = $i + 1; $j < count($keys); $j++) {
                $pairs[] = [
                    'a' => $keys[$i],
                    'a_label' => $this->kriteria[$keys[$i]],
                    'b' => $keys[$j],
                    'b_label' => $this->kriteria[$keys[$j]],
                ];
            }
        }
        
        return $pairs;
    }

    // Proses simpan bobot AHP
    public function store(Request $request)
    {
        $request->validate([
            'comparisons' => 'required|array',
            'comparisons.*.lebih_penting' => 'required|string',
            'comparisons.*.skala' => 'required|integer|min:1|max:9',
        ]);

        // Hitung bobot menggunakan metode AHP
        $hasil = $this->calculateAHP($request->comparisons);

        // Insert atau update bobot
        $bobot = Bobot::updateOrCreate(
            ['id_bobot' => 1], // Hanya 1 record bobot
            [
                'jumlah_mahasiswa' => $hasil['bobot']['jumlah_mahasiswa'],
                'fasilitas' => $hasil['bobot']['fasilitas'],
                'hari_operasi' => $hasil['bobot']['hari_operasi'],
                'level_legalitas' => $hasil['bobot']['level_legalitas'],
            ]
        );

        return redirect()->route('ahp.index')
            ->with('success', 'Bobot AHP berhasil disimpan!')
            ->with('hasil_perhitungan', $hasil);
    }

    // Perhitungan AHP
    private function calculateAHP($comparisons)
    {
        $keys = array_keys($this->kriteria);
        $n = count($keys);
        
        // Inisialisasi matriks perbandingan berpasangan
        $matrix = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $matrix[$keys[$i]][$keys[$j]] = ($i == $j) ? 1 : 0;
            }
        }

        // Isi matriks berdasarkan input user
        foreach ($comparisons as $comparison) {
            $lebihPenting = $comparison['lebih_penting'];
            $skala = (float) $comparison['skala'];
            
            // Parse pair dari key (format: "kriteria_a|kriteria_b")
            $pairKey = $comparison['pair'];
            list($a, $b) = explode('|', $pairKey);

            if ($lebihPenting === $a) {
                // A lebih penting dari B
                $matrix[$a][$b] = $skala;
                $matrix[$b][$a] = 1 / $skala;
            } else {
                // B lebih penting dari A
                $matrix[$b][$a] = $skala;
                $matrix[$a][$b] = 1 / $skala;
            }
        }

        // Hitung jumlah kolom
        $columnSum = [];
        foreach ($keys as $col) {
            $sum = 0;
            foreach ($keys as $row) {
                $sum += $matrix[$row][$col];
            }
            $columnSum[$col] = $sum;
        }

        // Normalisasi matriks
        $normalizedMatrix = [];
        foreach ($keys as $row) {
            foreach ($keys as $col) {
                $normalizedMatrix[$row][$col] = $matrix[$row][$col] / $columnSum[$col];
            }
        }

        // Hitung bobot (rata-rata baris dari matriks ternormalisasi)
        $bobot = [];
        foreach ($keys as $row) {
            $rowSum = 0;
            foreach ($keys as $col) {
                $rowSum += $normalizedMatrix[$row][$col];
            }
            $bobot[$row] = $rowSum / $n;
        }

        // Hitung Consistency Ratio (CR)
        $cr = $this->calculateConsistencyRatio($matrix, $bobot, $n);

        return [
            'matrix' => $matrix,
            'columnSum' => $columnSum,
            'normalizedMatrix' => $normalizedMatrix,
            'bobot' => $bobot,
            'cr' => $cr,
            'kriteria' => $this->kriteria,
        ];
    }

    // Hitung Consistency Ratio
    private function calculateConsistencyRatio($matrix, $bobot, $n)
    {
        $keys = array_keys($this->kriteria);
        
        // Random Index (RI) untuk n kriteria
        $ri = [1 => 0, 2 => 0, 3 => 0.58, 4 => 0.90, 5 => 1.12, 6 => 1.24, 7 => 1.32, 8 => 1.41, 9 => 1.45, 10 => 1.49];
        
        // Hitung weighted sum
        $weightedSum = [];
        foreach ($keys as $row) {
            $sum = 0;
            foreach ($keys as $col) {
                $sum += $matrix[$row][$col] * $bobot[$col];
            }
            $weightedSum[$row] = $sum;
        }
        
        // Hitung lambda max
        $lambdaMax = 0;
        foreach ($keys as $key) {
            if ($bobot[$key] > 0) {
                $lambdaMax += $weightedSum[$key] / $bobot[$key];
            }
        }
        $lambdaMax = $lambdaMax / $n;
        
        // Hitung CI dan CR
        $ci = ($lambdaMax - $n) / ($n - 1);
        $cr = ($ri[$n] > 0) ? $ci / $ri[$n] : 0;
        
        return [
            'lambda_max' => $lambdaMax,
            'ci' => $ci,
            'ri' => $ri[$n],
            'cr' => $cr,
            'konsisten' => $cr <= 0.1,
        ];
    }

    // Method lain tidak digunakan untuk AHP, bisa dikosongkan
    // public function create() {}
    // public function show($id) {}
    // public function edit($id) {}
    // public function update(Request $request, $id) {}
    // public function destroy($id) {}
}