<?php

namespace App\Http\Controllers;

use App\Models\RatingDanReview;
use Illuminate\Http\Request;

class RatingDanReviewController extends Controller
{
    /**
     * ✅ Tampilkan semua review (halaman daftar review)
     */
    public function index(Request $request)
    {
        // Ambil semua data review
        $reviews = RatingDanReview::query();

        // Jika ada parameter 'search', filter data berdasarkan ID mahasiswa
        if ($request->has('search')) {
            $reviews->where('id_mahasiswa', 'like', '%' . $request->input('search') . '%');
        }

        // Ambil data yang sudah difilter dan tambahkan paginasi
        $reviews = $reviews->paginate(10); // Menampilkan 10 item per halaman

        // pastikan file view: resources/views/Rating/lihatratingdanreview.blade.php
        return view('Rating.lihatratingdanreview', compact('reviews'));
    }

    /**
     * ✅ Form tambah review
     */
    public function create()
    {
        // pastikan file view: resources/views/Rating/ratingdanreview.blade.php
        return view('Rating.ratingdanreview');
    }

    /**
     * ✅ Simpan review baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_mahasiswa' => 'required|integer',
            'id_perusahaan' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:500',
            'tanggal_review' => 'date', // Validasi tanggal
        ]);

        RatingDanReview::create($request->all());

        // setelah simpan langsung ke daftar review
        return redirect()->route('lihatratingdanreview')
            ->with('success', 'Review berhasil ditambahkan!');
    }

    /**
     * ✅ Form edit review
     */
    public function edit(RatingDanReview $ratingdanreview)
    {
        // pastikan file view: resources/views/Rating/editratingdanreview.blade.php
        return view('Rating.editratingdanreview', [
            'review' => $ratingdanreview
        ]);
    }

    /**
     * ✅ Update review
     */
    public function update(Request $request, RatingDanReview $ratingdanreview)
    {
        $request->validate([
            'id_mahasiswa' => 'required|integer',
            'id_perusahaan' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:500',
        ]);

        $ratingdanreview->update($request->all());

        return redirect()->route('lihatratingdanreview')
            ->with('success', 'Review berhasil diperbarui!');
    }

    /**
     * ✅ Hapus review
     */
    public function destroy(RatingDanReview $ratingdanreview)
    {
        $ratingdanreview->delete();

        return redirect()->route('lihatratingdanreview')
            ->with('success', 'Review berhasil dihapus!');
    }

    /**
     * ✅ Ranking perusahaan berdasarkan rata-rata rating
     */
    public function showRanking()
    {
        $reviews = RatingDanReview::selectRaw('id_perusahaan, AVG(rating) as avg_rating')
            ->groupBy('id_perusahaan')
            ->orderByDesc('avg_rating')
            ->get();

        // pastikan file view: resources/views/Rating/ratingperusahaan.blade.php
        return view('Rating.ratingperusahaan', compact('reviews'));
    }
}