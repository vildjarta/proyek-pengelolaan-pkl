<?php

namespace App\Http\Controllers;

use App\Models\RatingDanReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RatingDanReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = RatingDanReview::query();

        if ($request->filled('search')) {
            $reviews->where('id_mahasiswa', 'like', '%' . $request->input('search') . '%');
        }

        $reviews = $reviews->paginate(10);

        return view('Rating.lihatratingdanreview', compact('reviews'));
    }

    // ✅ Form tambah rating & review
    public function create($id_perusahaan, $nama_perusahaan)
    {
        return view('Rating.ratingdanreview', compact('id_perusahaan', 'nama_perusahaan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_mahasiswa'   => 'required|integer',
            'id_perusahaan'  => 'required|integer',
            'rating'         => 'required|integer|min:1|max:5',
            'review'         => 'required|string|max:500',
            'tanggal_review' => 'nullable|date',
        ]);

        RatingDanReview::create($request->only([
            'id_mahasiswa',
            'id_perusahaan',
            'rating',
            'review',
            'tanggal_review',
        ]));

        return redirect()->route('lihatratingdanreview')
            ->with('success', 'Review berhasil ditambahkan!');
    }

    public function edit(RatingDanReview $ratingdanreview)
    {
        return view('Rating.editratingdanreview', [
            'review' => $ratingdanreview
        ]);
    }

    public function update(Request $request, RatingDanReview $ratingdanreview)
    {
        $request->validate([
            'id_mahasiswa'   => 'required|integer',
            'id_perusahaan'  => 'required|integer',
            'rating'         => 'required|integer|min:1|max:5',
            'review'         => 'required|string|max:500',
            'tanggal_review' => 'nullable|date',
        ]);

        $ratingdanreview->update($request->only([
            'id_mahasiswa',
            'id_perusahaan',
            'rating',
            'review',
            'tanggal_review',
        ]));

        return redirect()->route('lihatratingdanreview')
            ->with('success', 'Review berhasil diperbarui!');
    }

    public function destroy(RatingDanReview $ratingdanreview)
    {
        $ratingdanreview->delete();

        return redirect()->route('lihatratingdanreview')
            ->with('success', 'Review berhasil dihapus!');
    }

    // ✅ Ranking perusahaan
    public function showRanking()
    {
        $reviews = DB::table('perusahaan')
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

        return view('Rating.ratingperusahaan', compact('reviews'));
    }
}
