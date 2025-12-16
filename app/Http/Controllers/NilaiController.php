<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nilai;
use App\Models\Mahasiswa;
use App\Models\Penilaian_perusahaan;
use App\Models\PenilaianDospem;
use App\Models\PenilaianPenguji;

class NilaiController extends Controller
{
    /**
     * Display daftar resource.
     */
    public function index()
    {
        $data = Nilai::with('mahasiswa')->latest()->get();
        return view('nilai.index', compact('data'));
    }

    /**
     * Show form untuk membuat resource yang baru.
     */
    public function create()
    {
        $mahasiswa = Mahasiswa::all();
        return view('nilai.create', compact('mahasiswa'));
    }

    /**
     * Store resource yang baru dibuat ke dalam penyimpanan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_mahasiswa' => 'required',
            'id_nilai' => 'required|unique:nilai_mahasiswa,id_nilai',
        ]);

        $mahasiswa = Mahasiswa::where('nim', $request->id_mahasiswa)->first();
        if (! $mahasiswa) {
            return back()->withErrors(['id_mahasiswa' => 'Mahasiswa tidak ditemukan'])->withInput();
        }

        $perusahaan = \App\Models\Penilaian_perusahaan::where('id_mahasiswa', $request->id_mahasiswa)->latest()->first();
        $dospem = \App\Models\PenilaianDospem::where('id_mahasiswa', $mahasiswa->id_mahasiswa)->latest()->first();
        $penguji = \App\Models\PenilaianPenguji::where('nama_mahasiswa', $mahasiswa->nama)->latest()->first();

        // Map nilai dari sumber lain (fallback ke 0 bila tidak ada)
        $disiplin = $perusahaan->disiplin ?? 0;
        $komunikasi = $perusahaan->komunikasi ?? 0;
        $kerja_tim = $perusahaan->kerja_tim ?? 0;
        $kerja_mandiri = $perusahaan->kerja_mandiri ?? 0;
        $penampilan = $perusahaan->penampilan ?? 0;
        $sikap_etika_lapangan = $perusahaan->sikap_etika ?? ($perusahaan->sikap_etika_lapangan ?? 0);
        $pengetahuan = $perusahaan->pengetahuan ?? 0;

        $penguasaan_teori = $dospem->penguasaan_teori ?? ($dospem->penguasaan ?? 0);
        $kemampuan_analisis = $dospem->analisis_pemecahan_masalah ?? ($dospem->analisis ?? 0);
        $keaktifan_bimbingan = $dospem->keaktifan_bimbingan ?? 0;
        $kemampuan_penulisan_laporan = $dospem->penulisan_laporan ?? ($dospem->laporan ?? 0);
        $sikap_etika_dospem = $dospem->sikap ?? ($dospem->sikap_etika ?? 0);

        $penyajian_presentasi = $penguji->presentasi ?? 0;
        $pemahaman_materi = $penguji->materi ?? 0;
        $hasil_yang_dicapai = $penguji->hasil ?? 0;
        $objektivitas_menangapi = $penguji->objektif ?? 0;
        $penulisan_laporan_penguji = $penguji->laporan ?? 0;

        // Hitung total nilai dengan bobot persentase (0-100 → 0-300)
        $subtotalPembimbing =
            ($disiplin * 0.15) +
            ($komunikasi * 0.10) +
            ($kerja_tim * 0.15) +
            ($kerja_mandiri * 0.10) +
            ($penampilan * 0.10) +
            ($sikap_etika_lapangan * 0.20) +
            ($pengetahuan * 0.20);

        $subtotalDospem =
            ($penguasaan_teori * 0.20) +
            ($kemampuan_analisis * 0.25) +
            ($keaktifan_bimbingan * 0.15) +
            ($kemampuan_penulisan_laporan * 0.20) +
            ($sikap_etika_dospem * 0.20);

        $subtotalPenguji =
            ($penyajian_presentasi * 0.10) +
            ($pemahaman_materi * 0.15) +
            ($hasil_yang_dicapai * 0.40) +
            ($objektivitas_menangapi * 0.20) +
            ($penulisan_laporan_penguji * 0.15);

        $nilaiTotal = $subtotalPembimbing + $subtotalDospem + $subtotalPenguji;

        $nilaiHuruf = Nilai::konversiNilaiHuruf($nilaiTotal);
        $skor = Nilai::konversiSkor($nilaiTotal);

        Nilai::create([
            'id_mahasiswa' => $request->id_mahasiswa,
            'id_nilai' => $request->id_nilai,
            // Pembimbing Lapangan
            'disiplin' => $disiplin,
            'komunikasi' => $komunikasi,
            'kerja_tim' => $kerja_tim,
            'kerja_mandiri' => $kerja_mandiri,
            'penampilan' => $penampilan,
            'sikap_etika_lapangan' => $sikap_etika_lapangan,
            'pengetahuan' => $pengetahuan,
            // Dosen Pembimbing
            'penguasaan_teori' => $penguasaan_teori,
            'kemampuan_analisis' => $kemampuan_analisis,
            'keaktifan_bimbingan' => $keaktifan_bimbingan,
            'kemampuan_penulisan_laporan' => $kemampuan_penulisan_laporan,
            'sikap_etika_dospem' => $sikap_etika_dospem,
            // Penguji
            'penyajian_presentasi' => $penyajian_presentasi,
            'pemahaman_materi' => $pemahaman_materi,
            'hasil_yang_dicapai' => $hasil_yang_dicapai,
            'objektivitas_menangapi' => $objektivitas_menangapi,
            'penulisan_laporan' => $penulisan_laporan_penguji,
            // Catatan
            'catatan_pembimbing' => $perusahaan->catatan ?? null,
            'catatan_dospem' => $dospem->catatan ?? null,
            'catatan_penguji' => $penguji->catatan ?? null,
            // Total
            'nilai_total' => $nilaiTotal,
            'nilai_huruf' => $nilaiHuruf,
            'skor' => $skor,
            'id_user' => $request->id_user ?? null,
        ]);

        return redirect()->route('nilai.index')->with('success', 'Nilai berhasil ditambahkan.');
    }

    /**
     * Display nilai yang didalam penyimpanan.
     */
    public function show(string $id)
    {
        $nilai = Nilai::with('mahasiswa')->findOrFail($id);
        return view('nilai.show', compact('nilai'));
    }

    /**
     * Show form editing nilai yang didalam penyimpanan.
     */
    public function edit(string $id)
    {
        $nilai = Nilai::findOrFail($id);
        $mahasiswa = Mahasiswa::all();
        return view('nilai.edit', compact('nilai', 'mahasiswa'));
    }

    /**
     * Perbarui nilai yang ditentukan dalam penyimpanan.
     */
    public function update(Request $request, string $id)
    {
        $nilai = Nilai::findOrFail($id);

        $request->validate([
            'id_mahasiswa' => 'required',
            'id_nilai' => 'required|unique:nilai_mahasiswa,id_nilai,' . $id,
        ]);

        $mahasiswa = Mahasiswa::where('nim', $request->id_mahasiswa)->first();
        if (! $mahasiswa) {
            return back()->withErrors(['id_mahasiswa' => 'Mahasiswa tidak ditemukan'])->withInput();
        }

        $perusahaan = \App\Models\Penilaian_perusahaan::where('id_mahasiswa', $request->id_mahasiswa)->latest()->first();
        $dospem = \App\Models\PenilaianDospem::where('id_mahasiswa', $mahasiswa->id_mahasiswa)->latest()->first();
        $penguji = \App\Models\PenilaianPenguji::where('nama_mahasiswa', $mahasiswa->nama)->latest()->first();

        // Map nilai dari sumber lain (fallback ke 0 bila tidak ada)
        $disiplin = $perusahaan->disiplin ?? 0;
        $komunikasi = $perusahaan->komunikasi ?? 0;
        $kerja_tim = $perusahaan->kerja_tim ?? 0;
        $kerja_mandiri = $perusahaan->kerja_mandiri ?? 0;
        $penampilan = $perusahaan->penampilan ?? 0;
        $sikap_etika_lapangan = $perusahaan->sikap_etika ?? ($perusahaan->sikap_etika_lapangan ?? 0);
        $pengetahuan = $perusahaan->pengetahuan ?? 0;

        $penguasaan_teori = $dospem->penguasaan_teori ?? ($dospem->penguasaan ?? 0);
        $kemampuan_analisis = $dospem->analisis_pemecahan_masalah ?? ($dospem->analisis ?? 0);
        $keaktifan_bimbingan = $dospem->keaktifan_bimbingan ?? 0;
        $kemampuan_penulisan_laporan = $dospem->penulisan_laporan ?? ($dospem->laporan ?? 0);
        $sikap_etika_dospem = $dospem->sikap ?? ($dospem->sikap_etika ?? 0);

        $penyajian_presentasi = $penguji->presentasi ?? 0;
        $pemahaman_materi = $penguji->materi ?? 0;
        $hasil_yang_dicapai = $penguji->hasil ?? 0;
        $objektivitas_menangapi = $penguji->objektif ?? 0;
        $penulisan_laporan_penguji = $penguji->laporan ?? 0;

        // Hitung total nilai dengan bobot persentase (0-100 → 0-300)
        $subtotalPembimbing =
            ($disiplin * 0.15) +
            ($komunikasi * 0.10) +
            ($kerja_tim * 0.15) +
            ($kerja_mandiri * 0.10) +
            ($penampilan * 0.10) +
            ($sikap_etika_lapangan * 0.20) +
            ($pengetahuan * 0.20);

        $subtotalDospem =
            ($penguasaan_teori * 0.20) +
            ($kemampuan_analisis * 0.25) +
            ($keaktifan_bimbingan * 0.15) +
            ($kemampuan_penulisan_laporan * 0.20) +
            ($sikap_etika_dospem * 0.20);

        $subtotalPenguji =
            ($penyajian_presentasi * 0.10) +
            ($pemahaman_materi * 0.15) +
            ($hasil_yang_dicapai * 0.40) +
            ($objektivitas_menangapi * 0.20) +
            ($penulisan_laporan_penguji * 0.15);

        $nilaiTotal = $subtotalPembimbing + $subtotalDospem + $subtotalPenguji;

        $nilaiHuruf = Nilai::konversiNilaiHuruf($nilaiTotal);
        $skor = Nilai::konversiSkor($nilaiTotal);

        $nilai->update([
            'id_mahasiswa' => $request->id_mahasiswa,
            'id_nilai' => $request->id_nilai,
            // Pembimbing Lapangan
            'disiplin' => $disiplin,
            'komunikasi' => $komunikasi,
            'kerja_tim' => $kerja_tim,
            'kerja_mandiri' => $kerja_mandiri,
            'penampilan' => $penampilan,
            'sikap_etika_lapangan' => $sikap_etika_lapangan,
            'pengetahuan' => $pengetahuan,
            // Dosen Pembimbing
            'penguasaan_teori' => $penguasaan_teori,
            'kemampuan_analisis' => $kemampuan_analisis,
            'keaktifan_bimbingan' => $keaktifan_bimbingan,
            'kemampuan_penulisan_laporan' => $kemampuan_penulisan_laporan,
            'sikap_etika_dospem' => $sikap_etika_dospem,
            // Penguji
            'penyajian_presentasi' => $penyajian_presentasi,
            'pemahaman_materi' => $pemahaman_materi,
            'hasil_yang_dicapai' => $hasil_yang_dicapai,
            'objektivitas_menangapi' => $objektivitas_menangapi,
            'penulisan_laporan' => $penulisan_laporan_penguji,
            // Catatan
            'catatan_pembimbing' => $perusahaan->catatan ?? null,
            'catatan_dospem' => $dospem->catatan ?? null,
            'catatan_penguji' => $penguji->catatan ?? null,
            // Total
            'nilai_total' => $nilaiTotal,
            'nilai_huruf' => $nilaiHuruf,
            'skor' => $skor,
        ]);

        return redirect()->route('nilai.index')->with('success', 'Nilai berhasil diperbarui.');
    }

    /**
     * Remove nilai yang ditentukan dalam penyimpanan.
     */
    public function destroy(string $id)
    {
        $nilai = Nilai::findOrFail($id);
        $nilai->delete();

        return redirect()->route('nilai.index')->with('success', 'Nilai berhasil dihapus.');
    }

    /**
     * API: Get nilai data dari penilaian lain berdasarkan NIM mahasiswa
     */
    public function getNilaiData($nim)
    {
        try {
            $mahasiswa = Mahasiswa::where('nim', $nim)->first();
            if (!$mahasiswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mahasiswa tidak ditemukan'
                ], 404);
            }

            $perusahaan = Penilaian_perusahaan::where('id_mahasiswa', $nim)->latest()->first();
            $dospem = PenilaianDospem::where('id_mahasiswa', $mahasiswa->id_mahasiswa)->latest()->first();
            $penguji = PenilaianPenguji::where('nama_mahasiswa', $mahasiswa->nama)->latest()->first();

            $data = [
                'mahasiswa_found' => true,
                'mahasiswa_nama' => $mahasiswa->nama,
                'perusahaan_found' => !!$perusahaan,
                'dospem_found' => !!$dospem,
                'penguji_found' => !!$penguji,
                'nilai' => [
                    'disiplin' => $perusahaan->disiplin ?? 0,
                    'komunikasi' => $perusahaan->komunikasi ?? 0,
                    'kerja_tim' => $perusahaan->kerja_tim ?? 0,
                    'kerja_mandiri' => $perusahaan->kerja_mandiri ?? 0,
                    'penampilan' => $perusahaan->penampilan ?? 0,
                    'sikap_etika_lapangan' => $perusahaan->sikap_etika ?? ($perusahaan->sikap_etika_lapangan ?? 0),
                    'pengetahuan' => $perusahaan->pengetahuan ?? 0,
                    'penguasaan_teori' => $dospem->penguasaan_teori ?? ($dospem->penguasaan ?? 0),
                    'kemampuan_analisis' => $dospem->analisis_pemecahan_masalah ?? ($dospem->analisis ?? 0),
                    'keaktifan_bimbingan' => $dospem->keaktifan_bimbingan ?? 0,
                    'kemampuan_penulisan_laporan' => $dospem->penulisan_laporan ?? ($dospem->laporan ?? 0),
                    'sikap_etika_dospem' => $dospem->sikap ?? ($dospem->sikap_etika ?? 0),
                    'penyajian_presentasi' => $penguji->presentasi ?? 0,
                    'pemahaman_materi' => $penguji->materi ?? 0,
                    'hasil_yang_dicapai' => $penguji->hasil ?? 0,
                    'objektivitas_menangapi' => $penguji->objektif ?? 0,
                    'penulisan_laporan' => $penguji->laporan ?? 0,
                ],
                'catatan' => [
                    'catatan_pembimbing' => $perusahaan->catatan ?? null,
                    'catatan_dospem' => $dospem->catatan ?? null,
                    'catatan_penguji' => $penguji->catatan ?? null,
                ],
                'notifikasi' => [
                    'perusahaan' => !$perusahaan ? '⚠️ Data penilaian perusahaan tidak ditemukan' : null,
                    'dospem' => !$dospem ? '⚠️ Data penilaian dosen pembimbing tidak ditemukan' : null,
                    'penguji' => !$penguji ? '⚠️ Data penilaian penguji tidak ditemukan' : null,
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
