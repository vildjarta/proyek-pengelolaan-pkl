<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Perusahaan;
use App\Models\JadwalBimbingan;
use App\Models\RatingDanReview;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Counts
        $countMahasiswa = Mahasiswa::count();
        $countPerusahaan = Perusahaan::count();

        // Reviews and rating
        $totalReviews = RatingDanReview::count();
        $distinctReviewers = RatingDanReview::distinct('id_mahasiswa')->count('id_mahasiswa');
        $percentComplete = $countMahasiswa > 0 ? round(($distinctReviewers / $countMahasiswa) * 100, 1) : 0;
        $notRatedCount = max(0, $countMahasiswa - $distinctReviewers);
        $avgRating = RatingDanReview::avg('rating') ?: 0;

        // Jadwal for calendar and this month stat
        $jadwalsRaw = JadwalBimbingan::with(['mahasiswa','dosen'])->get();
        $jadwals = $jadwalsRaw->map(function($j) {
            return [
                'id' => $j->id,
                'tanggal' => $j->tanggal,
                'waktu_mulai' => $j->waktu_mulai,
                'waktu_selesai' => $j->waktu_selesai,
                'mahasiswa' => optional($j->mahasiswa)->nama,
                'dosen' => optional($j->dosen)->nama,
            ];
        });
        $totalSchedulesThisMonth = JadwalBimbingan::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)->count();

        // Recent reviews for cards
        $reviews = RatingDanReview::with(['mahasiswa','perusahaan'])
            ->orderByDesc('tanggal_review')
            ->limit(8)
            ->get();

        return view('home', compact(
            'countMahasiswa',
            'countPerusahaan',
            'percentComplete',
            'notRatedCount',
            'avgRating',
            'jadwals',
            'totalSchedulesThisMonth',
            'reviews'
        ));
    }
}
