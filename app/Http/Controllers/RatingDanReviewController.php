<?php

namespace App\Http\Controllers;

use App\Models\RatingDanReview;
use App\Models\Perusahaan;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RatingDanReviewController extends Controller
{
    /** 📊 Menampilkan Ranking Perusahaan */
    public function showRanking()
    {
        $perusahaans = DB::table('perusahaan')
            ->leftJoin('rating_dan_reviews', 'perusahaan.id_perusahaan', '=', 'rating_dan_reviews.id_perusahaan')
            ->select(
                'perusahaan.id_perusahaan',
                'perusahaan.nama as nama_perusahaan',
                DB::raw('COALESCE(AVG(rating_dan_reviews.rating), 0) as avg_rating'),
                DB::raw('COUNT(rating_dan_reviews.id_review) as total_reviews')
            )
            ->groupBy('perusahaan.id_perusahaan', 'perusahaan.nama')
            ->orderByDesc('avg_rating')
            ->get();

        return view('rating.ratingperusahaan', compact('perusahaans'));
    }

    /** 📋 Menampilkan daftar rating dan review */
    public function index(Request $request, $id_perusahaan)
    {
        $perusahaan = Perusahaan::findOrFail($id_perusahaan);

        // ✅ JOIN ke tabel mahasiswa menggunakan id_mahasiswa
        $reviews = RatingDanReview::leftJoin('mahasiswa', 'rating_dan_reviews.id_mahasiswa', '=', 'mahasiswa.id_mahasiswa')
            ->select(
                'rating_dan_reviews.*',
                'mahasiswa.nama as nama_mahasiswa',
                'mahasiswa.nim'
            )
            ->where('rating_dan_reviews.id_perusahaan', $id_perusahaan);

        // 🔍 Fitur pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $reviews->where(function ($q) use ($search) {
                $q->where('mahasiswa.nama', 'like', "%$search%")
                  ->orWhere('mahasiswa.nim', 'like', "%$search%")
                  ->orWhere('rating_dan_reviews.review', 'like', "%$search%");
            });
        }

        // ⏳ Filter urutan
        if ($request->filter === 'highest') {
            $reviews->orderByDesc('rating');
        } elseif ($request->filter === 'lowest') {
            $reviews->orderBy('rating', 'asc');
        } else {
            $reviews->orderByDesc('tanggal_review');
        }

        $reviews = $reviews->paginate(10)->withQueryString();

        return view('rating.lihatratingdanreview', compact('reviews', 'perusahaan'));
    }

    /** 📝 Form tambah review baru */
    public function create($id_perusahaan)
    {
        $perusahaan = Perusahaan::findOrFail($id_perusahaan);
        return view('rating.ratingdanreview', compact('perusahaan'));
    }

    /** 💾 Simpan data review baru */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim'            => 'required|digits:10|exists:mahasiswa,nim',
            'id_perusahaan'  => 'required|exists:perusahaan,id_perusahaan',
            'rating'         => 'required|integer|min:1|max:5',
            'review'         => 'required|string|max:500',
            'tanggal_review' => 'nullable|date',
        ]);

        // ✅ Ambil id_mahasiswa berdasarkan NIM
        $mahasiswa = Mahasiswa::where('nim', $validated['nim'])->first();

        if (!$mahasiswa) {
            return back()->withErrors(['nim' => 'NIM tidak ditemukan.'])->withInput();
        }

        $validated['id_mahasiswa'] = $mahasiswa->id_mahasiswa;

        if (empty($validated['tanggal_review'])) {
            $validated['tanggal_review'] = now();
        }

        RatingDanReview::create($validated);

        return redirect()->route('lihatratingdanreview', ['id_perusahaan' => $validated['id_perusahaan']])
            ->with('success', 'Review berhasil ditambahkan!');
    }

    /** ✏️ Form edit review */
    public function edit($id_review)
    {
        $ratingdanreview = RatingDanReview::findOrFail($id_review);
        $perusahaan = Perusahaan::findOrFail($ratingdanreview->id_perusahaan);

        return view('rating.editratingdanreview', compact('ratingdanreview', 'perusahaan'));
    }

    /** 🔄 Update review */
    public function update(Request $request, $id_review)
    {
        $ratingdanreview = RatingDanReview::findOrFail($id_review);

        $validated = $request->validate([
            'nim'            => 'required|digits:10|exists:mahasiswa,nim',
            'id_perusahaan'  => 'required|exists:perusahaan,id_perusahaan',
            'rating'         => 'required|integer|min:1|max:5',
            'review'         => 'required|string|max:500',
            'tanggal_review' => 'nullable|date',
        ]);

        // ✅ Update id_mahasiswa juga
        $mahasiswa = Mahasiswa::where('nim', $validated['nim'])->first();

        if (!$mahasiswa) {
            return back()->withErrors(['nim' => 'NIM tidak ditemukan.'])->withInput();
        }

        $validated['id_mahasiswa'] = $mahasiswa->id_mahasiswa;

        if (empty($validated['tanggal_review'])) {
            $validated['tanggal_review'] = now();
        }

        $ratingdanreview->update($validated);

        return redirect()->route('lihatratingdanreview', ['id_perusahaan' => $ratingdanreview->id_perusahaan])
            ->with('success', 'Review berhasil diperbarui!');
    }

    /** ❌ Hapus review */
    public function destroy($id_review)
    {
        $ratingdanreview = RatingDanReview::findOrFail($id_review);
        $idPerusahaan = $ratingdanreview->id_perusahaan;
        $ratingdanreview->delete();

        return redirect()->route('lihatratingdanreview', ['id_perusahaan' => $idPerusahaan])
            ->with('success', 'Review berhasil dihapus!');
    }
}
