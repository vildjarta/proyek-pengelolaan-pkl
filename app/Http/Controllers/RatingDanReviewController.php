<?php

namespace App\Http\Controllers;

use App\Models\RatingDanReview;
use App\Models\Perusahaan;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RatingDanReviewController extends Controller
{
    /**
     * Menampilkan Ranking Perusahaan berdasarkan rata-rata rating
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

        return view('rating.ratingperusahaan', compact('perusahaans'));
    }

    /**
     * Menampilkan daftar rating dan review per perusahaan
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
     * Form tambah review baru
     */
    public function create($id_perusahaan)
    {
        // pastikan numeric dan exist
        if (!is_numeric($id_perusahaan)) {
            return redirect()->route('ratingperusahaan')->with('error', 'ID perusahaan tidak valid.');
        }

        $perusahaan = Perusahaan::findOrFail($id_perusahaan);
        return view('rating.ratingdanreview', compact('perusahaan'));
    }

    /**
     * Simpan data review baru
     */
    public function store(Request $request)
    {
        // Validasi
        $validated = $request->validate([
            'nim'            => 'required|digits:10',
            'id_perusahaan'  => 'required|exists:perusahaan,id_perusahaan',
            'rating'         => 'required|integer|min:1|max:5',
            'review'         => 'required|string|max:500',
            'tanggal_review' => 'nullable|date',
        ], [
            'nim.required' => 'NIM wajib diisi.',
            'nim.digits' => 'NIM harus terdiri dari 10 digit.',
            'rating.required' => 'Rating wajib diisi.',
            'review.required' => 'Review wajib diisi.',
        ]);

        // Cari mahasiswa berdasarkan NIM
        $mahasiswa = Mahasiswa::where('nim', $validated['nim'])->first();

        if (!$mahasiswa) {
            return back()->withErrors(['nim' => 'NIM tidak ditemukan.'])->withInput();
        }

        // Siapkan data untuk simpan
        $data = [
            'id_mahasiswa' => $mahasiswa->id_mahasiswa,
            'id_perusahaan' => $validated['id_perusahaan'],
            'rating' => $validated['rating'],
            'review' => $validated['review'],
            'tanggal_review' => $validated['tanggal_review'] ?? now(),
        ];

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

        return view('rating.editratingdanreview', compact('ratingdanreview', 'perusahaan'));
    }

    /**
     * Update review
     */
    public function update(Request $request, $id_review)
    {
        $ratingdanreview = RatingDanReview::findOrFail($id_review);

        $validated = $request->validate([
            'nim'            => 'required|digits:10',
            'id_perusahaan'  => 'required|exists:perusahaan,id_perusahaan',
            'rating'         => 'required|integer|min:1|max:5',
            'review'         => 'required|string|max:500',
            'tanggal_review' => 'nullable|date',
        ], [
            'nim.required' => 'NIM wajib diisi.',
            'nim.digits' => 'NIM harus terdiri dari 10 digit.',
            'rating.required' => 'Rating wajib diisi.',
            'review.required' => 'Review wajib diisi.',
        ]);

        // Cek mahasiswa
        $mahasiswa = Mahasiswa::where('nim', $validated['nim'])->first();

        if (!$mahasiswa) {
            return back()->withErrors(['nim' => 'NIM tidak ditemukan.'])->withInput();
        }

        $validated['id_mahasiswa'] = $mahasiswa->id_mahasiswa;
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
        $idPerusahaan = $ratingdanreview->id_perusahaan;
        $ratingdanreview->delete();

        return redirect()
            ->route('lihatratingdanreview', ['id_perusahaan' => $idPerusahaan])
            ->with('success', 'Review berhasil dihapus!');
    }
}
