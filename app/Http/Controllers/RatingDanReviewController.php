<?php

namespace App\Http\Controllers;

use App\Models\RatingDanReview;
use App\Models\Perusahaan;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RatingDanReviewController extends Controller
{
    /**
     * Helper: cari id_perusahaan tempat mahasiswa tersebut PKL.
     * - Kalau suatu saat kamu menambah kolom id_perusahaan di tabel mahasiswa,
     *   fungsi ini tetap bisa dipakai (cek dulu id_perusahaan, kalau tidak ada baru pakai nama).
     */
    protected function resolveMahasiswaCompanyId(Mahasiswa $mahasiswa): ?int
    {
        // 1) Kalau tabel mahasiswa nanti punya kolom id_perusahaan
        if (isset($mahasiswa->id_perusahaan) && !empty($mahasiswa->id_perusahaan)) {
            return (int) $mahasiswa->id_perusahaan;
        }

        // 2) Sekarang yang dipakai adalah kolom "perusahaan" (nama perusahaan, string)
        if (!empty($mahasiswa->perusahaan)) {
            $perusahaan = Perusahaan::where('nama', $mahasiswa->perusahaan)->first();
            if ($perusahaan) {
                return (int) $perusahaan->id_perusahaan;
            }
        }

        return null; // tidak terhubung ke perusahaan manapun
    }

    /**
     * Halaman Ranking Perusahaan
     */
    public function showRanking(Request $request)
    {
        $search = $request->input('search');

        $perusahaans = DB::table('perusahaan')
            ->leftJoin('rating_dan_reviews', 'rating_dan_reviews.id_perusahaan', '=', 'perusahaan.id_perusahaan')
            ->select(
                'perusahaan.id_perusahaan',
                'perusahaan.nama as nama_perusahaan',
                DB::raw('COALESCE(AVG(rating_dan_reviews.rating), 0) as avg_rating'),
                DB::raw('COUNT(rating_dan_reviews.id_review) as total_reviews')
            )
            ->when($search, function ($query, $search) {
                $query->where('perusahaan.nama', 'like', "%$search%");
            })
            ->groupBy('perusahaan.id_perusahaan', 'perusahaan.nama')
            ->orderByDesc('avg_rating')
            ->get();

        // Mahasiswa yang sedang login (jika ada)
        $currentMahasiswa = null;
        $mahasiswaCompanyId = null;

        if (Auth::check()) {
            $currentMahasiswa = Mahasiswa::where('email', Auth::user()->email)->first();

            if ($currentMahasiswa) {
                $mahasiswaCompanyId = $this->resolveMahasiswaCompanyId($currentMahasiswa);
            }
        }

        /**
         * Map: id_perusahaan => boleh tambah review atau tidak
         * Syarat:
         *  - user login sebagai mahasiswa
         *  - mahasiswa punya perusahaan (mahasiswaCompanyId tidak null)
         *  - perusahaan di baris ini sama dengan perusahaan mahasiswa
         *  - mahasiswa belum pernah review perusahaan ini
         */
        $canAddMap = [];
        foreach ($perusahaans as $p) {
            $canAddMap[$p->id_perusahaan] = false;

            if ($currentMahasiswa && $mahasiswaCompanyId && $mahasiswaCompanyId == $p->id_perusahaan) {
                $already = RatingDanReview::where('id_mahasiswa', $currentMahasiswa->id_mahasiswa)
                    ->where('id_perusahaan', $p->id_perusahaan)
                    ->exists();

                // 1 akun 1 review per perusahaan
                $canAddMap[$p->id_perusahaan] = !$already;
            }
        }

        return view('rating.ratingperusahaan', compact('perusahaans', 'currentMahasiswa', 'canAddMap'));
    }

    /**
     * Daftar rating & review per perusahaan
     */
    public function index(Request $request, $id_perusahaan)
    {
        $perusahaan = Perusahaan::findOrFail($id_perusahaan);

        $reviews = RatingDanReview::leftJoin('mahasiswa', 'rating_dan_reviews.id_mahasiswa', '=', 'mahasiswa.id_mahasiswa')
            ->select(
                'rating_dan_reviews.*',
                'mahasiswa.nama as nama_mahasiswa',
                'mahasiswa.nim'
            )
            ->where('rating_dan_reviews.id_perusahaan', $id_perusahaan);

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $reviews->where(function ($q) use ($search) {
                $q->where('mahasiswa.nama', 'like', "%$search%")
                    ->orWhere('mahasiswa.nim', 'like', "%$search%")
                    ->orWhere('rating_dan_reviews.review', 'like', "%$search%");
            });
        }

        // Filter urutan
        switch ($request->filter) {
            case 'highest':
                $reviews->orderByDesc('rating');
                break;
            case 'lowest':
                $reviews->orderBy('rating', 'asc');
                break;
            default:
                $reviews->orderByDesc('tanggal_review');
                break;
        }

        $reviews = $reviews->paginate(10)->withQueryString();

        return view('rating.lihatratingdanreview', compact('reviews', 'perusahaan'));
    }

    /**
     * Form tambah review baru (untuk perusahaan tertentu)
     */
    public function create($id_perusahaan)
    {
        if (!is_numeric($id_perusahaan)) {
            return redirect()->route('ratingperusahaan')->with('error', 'ID perusahaan tidak valid.');
        }

        $perusahaan = Perusahaan::findOrFail($id_perusahaan);

        if (!Auth::check()) {
            return redirect()->route('ratingperusahaan')->with('error', 'Silakan login terlebih dahulu.');
        }

        $mahasiswa = Mahasiswa::where('email', Auth::user()->email)->first();
        if (!$mahasiswa) {
            return redirect()->route('ratingperusahaan')->with('error', 'Akun Anda belum terhubung dengan data mahasiswa.');
        }

        // Tentukan perusahaan tempat mahasiswa PKL (dari kolom "perusahaan" di tabel mahasiswa)
        $mahasiswaCompanyId = $this->resolveMahasiswaCompanyId($mahasiswa);

        if (!$mahasiswaCompanyId || $mahasiswaCompanyId != $perusahaan->id_perusahaan) {
            return redirect()->route('ratingperusahaan')->with(
                'error',
                'Anda hanya dapat memberi rating pada perusahaan tempat Anda PKL.'
            );
        }

        // Cek apakah sudah pernah review perusahaan ini
        $already = RatingDanReview::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
            ->where('id_perusahaan', $perusahaan->id_perusahaan)
            ->exists();

        if ($already) {
            return redirect()->route('lihatratingdanreview', ['id_perusahaan' => $perusahaan->id_perusahaan])
                ->with('error', 'Anda sudah memberikan review untuk perusahaan ini. Silakan edit jika ingin mengubah.');
        }

        // Kirim data mahasiswa agar NIM otomatis & readonly
        return view('rating.ratingdanreview', compact('perusahaan', 'mahasiswa'));
    }

    /**
     * Simpan review baru
     */
    public function store(Request $request)
    {
        $mahasiswa = null;
        $mahasiswaCompanyId = null;

        if (Auth::check()) {
            $mahasiswa = Mahasiswa::where('email', Auth::user()->email)->first();
            if ($mahasiswa) {
                $mahasiswaCompanyId = $this->resolveMahasiswaCompanyId($mahasiswa);
            }
        }

        // Jika mahasiswa login ➜ gunakan data dari akun, nim diabaikan
        if ($mahasiswa) {
            $validated = $request->validate([
                'id_perusahaan'  => 'required|exists:perusahaan,id_perusahaan',
                'rating'         => 'required|integer|min:1|max:5',
                'review'         => 'required|string|max:500',
                'tanggal_review' => 'nullable|date',
            ]);

            // Pastikan hanya bisa rating perusahaan tempat dia PKL
            if (!$mahasiswaCompanyId || $mahasiswaCompanyId != $validated['id_perusahaan']) {
                return back()
                    ->withErrors(['id_perusahaan' => 'Anda hanya dapat memberi review pada perusahaan tempat Anda PKL.'])
                    ->withInput();
            }

            // Cek duplikasi review (1 akun 1 review untuk perusahaan ini)
            $exists = RatingDanReview::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
                ->where('id_perusahaan', $validated['id_perusahaan'])
                ->exists();

            if ($exists) {
                return back()
                    ->withErrors(['review' => 'Anda sudah memberikan review untuk perusahaan ini.'])
                    ->withInput();
            }

            $data = [
                'id_mahasiswa'   => $mahasiswa->id_mahasiswa,
                'id_perusahaan'  => $validated['id_perusahaan'],
                'rating'         => $validated['rating'],
                'review'         => $validated['review'],
                'tanggal_review' => $validated['tanggal_review'] ?? now(),
            ];
        } else {
            // Bukan mahasiswa (misal admin input manual)
            $validated = $request->validate([
                'nim'            => 'required|digits:10',
                'id_perusahaan'  => 'required|exists:perusahaan,id_perusahaan',
                'rating'         => 'required|integer|min:1|max:5',
                'review'         => 'required|string|max:500',
                'tanggal_review' => 'nullable|date',
            ]);

            $mahasiswa = Mahasiswa::where('nim', $validated['nim'])->first();
            if (!$mahasiswa) {
                return back()
                    ->withErrors(['nim' => 'NIM tidak ditemukan.'])
                    ->withInput();
            }

            $exists = RatingDanReview::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
                ->where('id_perusahaan', $validated['id_perusahaan'])
                ->exists();

            if ($exists) {
                return back()
                    ->withErrors(['review' => 'Mahasiswa ini sudah memberikan review untuk perusahaan ini.'])
                    ->withInput();
            }

            $data = [
                'id_mahasiswa'   => $mahasiswa->id_mahasiswa,
                'id_perusahaan'  => $validated['id_perusahaan'],
                'rating'         => $validated['rating'],
                'review'         => $validated['review'],
                'tanggal_review' => $validated['tanggal_review'] ?? now(),
            ];
        }

        RatingDanReview::create($data);

        return redirect()
            ->route('lihatratingdanreview', ['id_perusahaan' => $data['id_perusahaan']])
            ->with('success', 'Review berhasil ditambahkan!');
    }

    /**
     * Form edit review
     */
    public function edit($id_review)
    {
        $ratingdanreview = RatingDanReview::findOrFail($id_review);
        $perusahaan = Perusahaan::findOrFail($ratingdanreview->id_perusahaan);

        if (!Auth::check()) {
            return redirect()->route('ratingperusahaan')->with('error', 'Silakan login untuk mengedit review.');
        }

        $mahasiswa = Mahasiswa::where('email', Auth::user()->email)->first();
        if ($mahasiswa && $mahasiswa->id_mahasiswa != $ratingdanreview->id_mahasiswa) {
            return redirect()
                ->route('lihatratingdanreview', ['id_perusahaan' => $perusahaan->id_perusahaan])
                ->with('error', 'Anda tidak berwenang mengedit review ini.');
        }

        return view('rating.editratingdanreview', compact('ratingdanreview', 'perusahaan'));
    }

    /**
     * Update review
     */
    public function update(Request $request, $id_review)
    {
        $ratingdanreview = RatingDanReview::findOrFail($id_review);

        if (!Auth::check()) {
            return redirect()->route('ratingperusahaan')->with('error', 'Silakan login untuk melakukan perubahan.');
        }

        $mahasiswa = Mahasiswa::where('email', Auth::user()->email)->first();
        if (!$mahasiswa || $mahasiswa->id_mahasiswa != $ratingdanreview->id_mahasiswa) {
            return redirect()
                ->route('lihatratingdanreview', ['id_perusahaan' => $ratingdanreview->id_perusahaan])
                ->with('error', 'Anda tidak berwenang mengubah review ini.');
        }

        $validated = $request->validate([
            'rating'         => 'required|integer|min:1|max:5',
            'review'         => 'required|string|max:500',
            'tanggal_review' => 'nullable|date',
        ], [
            'rating.required' => 'Rating wajib diisi.',
            'review.required' => 'Review wajib diisi.',
        ]);

        $validated['tanggal_review'] = $validated['tanggal_review'] ?? now();

        $ratingdanreview->update($validated);

        return redirect()
            ->route('lihatratingdanreview', ['id_perusahaan' => $ratingdanreview->id_perusahaan])
            ->with('success', 'Review berhasil diperbarui!');
    }

    /**
     * Hapus review
     */
    public function destroy($id_review)
    {
        $ratingdanreview = RatingDanReview::findOrFail($id_review);

        if (!Auth::check()) {
            return redirect()->route('ratingperusahaan')->with('error', 'Silakan login untuk melakukan aksi ini.');
        }

        $mahasiswa = Mahasiswa::where('email', Auth::user()->email)->first();
        if (!$mahasiswa || $mahasiswa->id_mahasiswa != $ratingdanreview->id_mahasiswa) {
            return redirect()
                ->route('lihatratingdanreview', ['id_perusahaan' => $ratingdanreview->id_perusahaan])
                ->with('error', 'Anda tidak berwenang menghapus review ini.');
        }

        $idPerusahaan = $ratingdanreview->id_perusahaan;
        $ratingdanreview->delete();

        return redirect()
            ->route('lihatratingdanreview', ['id_perusahaan' => $idPerusahaan])
            ->with('success', 'Review berhasil dihapus!');
    }
}
