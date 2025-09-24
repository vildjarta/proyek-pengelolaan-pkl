<?php

namespace App\Http\Controllers;

use App\Models\RatingDanReview;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RatingDanReviewController extends Controller
{
    /**
     * Menampilkan daftar rating dan review dengan fitur pencarian dan paginasi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = RatingDanReview::query();

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            
            // Pencarian hanya berdasarkan ID karena tidak ada relasi
            $query->where('id_mahasiswa', 'like', '%' . $searchTerm . '%')
                  ->orWhere('id_perusahaan', 'like', '%' . $searchTerm . '%');
        }

        // Ambil data dengan paginasi dan tambahkan parameter pencarian ke URL
        $reviews = $query->paginate(5)->appends($request->query());

        return view('Rating.lihatratingdanreview', compact('reviews'));
    }

    /**
     * Menampilkan form untuk membuat rating dan review baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('Rating.ratingdanreview');
    }

    /**
     * Menyimpan rating dan review baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi data yang masuk dari formulir
        $validatedData = $request->validate([
            'id_mahasiswa' => 'required|numeric',
            'id_perusahaan' => 'required|numeric',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
            'tanggal_review' => 'required|date',
        ]);

        // Simpan data ke database
        RatingDanReview::create($validatedData);

        // Redirect pengguna kembali ke halaman daftar review
        return redirect()->route('lihatratingdanreview')->with('success', 'Rating dan review berhasil disimpan!');
    }

    /**
     * Menampilkan halaman ranking perusahaan.
     *
     * @return \Illuminate\View\View
     */
    public function showRanking()
    {
        // Logika untuk mengambil data ranking dapat ditambahkan di sini
        // Misalnya:
        // $ranking = Perusahaan::withCount('ratings')->orderByDesc('ratings_count')->get();
        // return view('Rating.ratingperusahaan', compact('ranking'));

        return view('Rating.ratingperusahaan');
    }
}