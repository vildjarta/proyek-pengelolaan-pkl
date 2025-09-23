<?php

namespace App\Http\Controllers;

use App\Models\RatingDanReview;
use Illuminate\Http\Request;

class RatingDanReviewController extends Controller
{
    /**
     * Menampilkan daftar semua rating dan review dengan fitur pencarian.
     */
    public function index(Request $request)
    {
        // Mulai query Eloquent
        $query = RatingDanReview::query();

        // Cek apakah ada input pencarian (search)
        if ($request->has('search')) {
            $searchTerm = $request->input('search');

            // Tambahkan filter berdasarkan ID perusahaan atau nama perusahaan
            // Di sini kita hanya bisa memfilter berdasarkan ID perusahaan karena tidak ada kolom nama perusahaan di tabel RatingDanReview.
            $query->where('id_perusahaan', 'LIKE', '%' . $searchTerm . '%');
        }

        // Terapkan pagination ke query yang sudah difilter
        // Gunakan appends() agar pagination tetap mempertahankan parameter pencarian
        $reviews = $query->paginate(5)->appends($request->query());

        return view('Rating.ratingdanreview', compact('reviews'));
    }

    public function create()
    {
        return view('Rating.create');
    }

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

    public function edit(RatingDanReview $ratingdanreview)
    {
        return view('Rating.edit', compact('ratingdanreview'));
    }
    
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

    public function destroy(RatingDanReview $ratingdanreview)
    {
        $ratingdanreview->delete();

        return redirect()->route('ratingdanreview.index')->with('success', 'Review berhasil dihapus!');
    }
}