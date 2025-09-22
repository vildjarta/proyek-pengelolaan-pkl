<?php

namespace App\Http\Controllers;

use App\Models\RatingDanReview;
use Illuminate\Http\Request;

class RatingDanReviewController extends Controller
{
    /**
     * Menampilkan daftar semua rating dan review.
     * Menggunakan pagination untuk membatasi jumlah data yang ditampilkan.
     */
    public function index()
    {
        $reviews = RatingDanReview::paginate(5);
        return view('Rating.ratingdanreview', compact('reviews'));
    }

    /**
     * Menampilkan formulir untuk membuat rating dan review baru.
     */
    public function create()
    {
        return view('Rating.create');
    }

    /**
     * Menyimpan rating dan review baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_mahasiswa' => 'required|integer',
            'id_perusahaan' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
            'tanggal_review' => 'required|date',
        ]);
        
        RatingDanReview::create([
            'id_mahasiswa' => $request->id_mahasiswa,
            'id_perusahaan' => $request->id_perusahaan,
            'rating' => $request->rating,
            'review' => $request->review,
            'tanggal_review' => $request->tanggal_review,
        ]);
        
        return redirect()->route('ratingdanreview.index')->with('success', 'Review berhasil dikirim!');
    }

    /**
     * Menampilkan formulir edit untuk rating dan review tertentu.
     */
    public function edit(RatingDanReview $ratingdanreview)
    {
        return view('Rating.edit', compact('ratingdanreview'));
    }
    
    /**
     * Memperbarui rating dan review di database.
     */
    public function update(Request $request, RatingDanReview $ratingdanreview)
    {
        $request->validate([
            'id_mahasiswa' => 'required|integer',
            'id_perusahaan' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
            'tanggal_review' => 'required|date',
        ]);

        $ratingdanreview->update($request->all());

        return redirect()->route('ratingdanreview.index')->with('success', 'Review berhasil diupdate!');
    }

    /**
     * Menghapus rating dan review dari database.
     */
    public function destroy(RatingDanReview $ratingdanreview)
    {
        $ratingdanreview->delete();

        return redirect()->route('ratingdanreview.index')->with('success', 'Review berhasil dihapus!');
    }
}