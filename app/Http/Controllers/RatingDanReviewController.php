<?php

namespace App\Http\Controllers;

use App\Models\RatingDanReview;
use App\Models\Perusahaan;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RatingDanReviewController extends Controller
{
    /* ======================================================
     | Helper: Ambil ID Perusahaan Mahasiswa
     ====================================================== */
    protected function resolveMahasiswaCompanyId(Mahasiswa $mahasiswa): ?int
    {
        if ($mahasiswa->id_perusahaan) {
            return (int) $mahasiswa->id_perusahaan;
        }

        if ($mahasiswa->perusahaan) {
            $perusahaan = Perusahaan::where('nama', $mahasiswa->perusahaan)->first();
            return $perusahaan?->id_perusahaan;
        }

        return null;
    }

    /* ======================================================
     | RANKING PERUSAHAAN
     ====================================================== */
    public function showRanking(Request $request)
    {
        $search = $request->search;

        $perusahaans = DB::table('perusahaan')
            ->leftJoin('rating_dan_reviews', 'rating_dan_reviews.id_perusahaan', '=', 'perusahaan.id_perusahaan')
            ->select(
                'perusahaan.id_perusahaan',
                'perusahaan.nama as nama_perusahaan',
                DB::raw('COALESCE(AVG(rating_dan_reviews.rating),0) as avg_rating'),
                DB::raw('COUNT(rating_dan_reviews.id_review) as total_reviews')
            )
            ->when($search, fn ($q) =>
                $q->where('perusahaan.nama', 'like', "%{$search}%")
            )
            ->groupBy('perusahaan.id_perusahaan', 'perusahaan.nama')
            ->orderByDesc('avg_rating')
            ->get();

        $currentMahasiswa = Auth::check()
            ? Mahasiswa::where('email', Auth::user()->email)->first()
            : null;

        $companyId = $currentMahasiswa
            ? $this->resolveMahasiswaCompanyId($currentMahasiswa)
            : null;

        $canAddMap = [];

        foreach ($perusahaans as $p) {
            $canAddMap[$p->id_perusahaan] = false;

            if ($currentMahasiswa && $companyId == $p->id_perusahaan) {
                $already = RatingDanReview::where([
                    'id_mahasiswa' => $currentMahasiswa->id_mahasiswa,
                    'id_perusahaan' => $p->id_perusahaan
                ])->exists();

                $canAddMap[$p->id_perusahaan] = ! $already;
            }
        }

        return view('Rating.ratingperusahaan', compact(
            'perusahaans',
            'currentMahasiswa',
            'canAddMap'
        ));
    }

    /* ======================================================
     | LIST REVIEW PER PERUSAHAAN
     ====================================================== */
    public function index(Request $request, $id_perusahaan)
    {
        $perusahaan = Perusahaan::findOrFail($id_perusahaan);

        $reviews = RatingDanReview::with(['mahasiswa', 'perusahaan'])
            ->where('id_perusahaan', $id_perusahaan)
            ->when($request->search, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->whereHas('mahasiswa', function ($m) use ($search) {
                        $m->where('nama', 'like', "%{$search}%")
                          ->orWhere('nim', 'like', "%{$search}%");
                    })
                    ->orWhere('review', 'like', "%{$search}%");
                });
            })
            ->when($request->filter, function ($q, $filter) {
                match ($filter) {
                    'highest' => $q->orderByDesc('rating'),
                    'lowest'  => $q->orderBy('rating'),
                    default   => $q->orderByDesc('tanggal_review'),
                };
            }, fn ($q) => $q->orderByDesc('tanggal_review'))
            ->paginate(10)
            ->withQueryString();

        return view('Rating.lihatratingdanreview', compact('reviews', 'perusahaan'));
    }

    /* ======================================================
     | FORM TAMBAH REVIEW
     ====================================================== */
    public function create($id_perusahaan)
    {
        $perusahaan = Perusahaan::findOrFail($id_perusahaan);

        if (!Auth::check()) {
            return redirect()->route('ratingperusahaan')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        if (!in_array($user->role, ['mahasiswa', 'koordinator'])) {
            return redirect()->route('ratingperusahaan')
                ->with('error', 'Anda tidak berwenang.');
        }

        $mahasiswa = Mahasiswa::where('email', $user->email)->first();
        if (!$mahasiswa) {
            return redirect()->route('ratingperusahaan')
                ->with('error', 'Akun tidak terhubung dengan mahasiswa.');
        }

        $companyId = $this->resolveMahasiswaCompanyId($mahasiswa);
        if (!$companyId || $companyId != $perusahaan->id_perusahaan) {
            return redirect()->route('ratingperusahaan')
                ->with('error', 'Anda hanya boleh memberi review di tempat PKL.');
        }

        $exists = RatingDanReview::where([
            'id_mahasiswa' => $mahasiswa->id_mahasiswa,
            'id_perusahaan' => $perusahaan->id_perusahaan
        ])->exists();

        if ($exists) {
            return redirect()->route('lihatratingdanreview', $perusahaan->id_perusahaan)
                ->with('error', 'Anda sudah memberi review.');
        }

        return view('Rating.ratingdanreview', compact('perusahaan', 'mahasiswa'));
    }

    /* ======================================================
     | SIMPAN REVIEW
     ====================================================== */
    public function store(Request $request)
    {
        $user = Auth::user();
        // Ensure only mahasiswa or koordinator can attempt to store review
        if (!in_array($user->role, ['mahasiswa', 'koordinator'])) {
            return redirect()->route('ratingperusahaan')
                ->with('error', 'Anda tidak berwenang.');
        }

        $mahasiswa = Mahasiswa::where('email', $user->email)->first();

        if (!$mahasiswa) {
            return back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $validated = $request->validate([
            'id_perusahaan' => 'required|exists:perusahaan,id_perusahaan',
            'rating'        => 'required|integer|min:1|max:5',
            'review'        => 'required|string|max:500',
        ]);

        $companyId = $this->resolveMahasiswaCompanyId($mahasiswa);
        if (!$companyId || $companyId != $validated['id_perusahaan']) {
            return back()->with('error', 'Perusahaan tidak sesuai PKL.');
        }

        RatingDanReview::create([
            'id_mahasiswa'   => $mahasiswa->id_mahasiswa,
            'id_perusahaan'  => $validated['id_perusahaan'],
            'rating'         => $validated['rating'],
            'review'         => $validated['review'],
            'tanggal_review' => now(),
        ]);

        return redirect()
            ->route('lihatratingdanreview', $validated['id_perusahaan'])
            ->with('success', 'Review berhasil ditambahkan.');
    }

    /* ======================================================
     | EDIT & UPDATE
     ====================================================== */
    public function edit($id_review)
    {
        $review = RatingDanReview::findOrFail($id_review);
        $perusahaan = Perusahaan::findOrFail($review->id_perusahaan);

        $user = Auth::user();
        if (!$user) {
            abort(403, 'User tidak ditemukan.');
        }

        $mahasiswa = Mahasiswa::where('email', $user->email)->first();

        $isOwner = $mahasiswa && $mahasiswa->id_mahasiswa == $review->id_mahasiswa;
        $isKoordinator = $user->role === 'koordinator';

        if (! $isOwner && ! $isKoordinator) {
            return redirect()->route('lihatratingdanreview', $perusahaan->id_perusahaan)
                ->with('error', 'Tidak berwenang.');
        }

        // The edit view expects a variable named $ratingdanreview
        $ratingdanreview = $review;

        return view('Rating.editratingdanreview', compact('ratingdanreview', 'perusahaan'));
    }

    public function update(Request $request, $id_review)
    {
        $review = RatingDanReview::findOrFail($id_review);

        // authorization: only owner (mahasiswa) or koordinator may update
        $user = Auth::user();
        if (!$user) {
            abort(403, 'User tidak ditemukan.');
        }

        $mahasiswa = Mahasiswa::where('email', $user->email)->first();
        $isOwner = $mahasiswa && $mahasiswa->id_mahasiswa == $review->id_mahasiswa;
        $isKoordinator = $user->role === 'koordinator';

        if (! $isOwner && ! $isKoordinator) {
            return redirect()->route('lihatratingdanreview', $review->id_perusahaan)
                ->with('error', 'Tidak berwenang.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:500',
        ]);

        $review->update($validated + ['tanggal_review' => now()]);

        return redirect()
            ->route('lihatratingdanreview', $review->id_perusahaan)
            ->with('success', 'Review diperbarui.');
    }

    /* ======================================================
     | HAPUS SEMUA RATING (KOORDINATOR ONLY)
     ====================================================== */
    public function destroyAll(Request $request)
    {
        $user = Auth::user();
        if (! $user || $user->role !== 'koordinator') {
            return back()->with('error', 'Tidak berwenang.');
        }

        RatingDanReview::truncate();

        return back()->with('success', 'Semua rating telah dihapus.');
    }

    /* ======================================================
     | HAPUS REVIEW
     ====================================================== */
    public function destroy($id_review)
    {
        $review = RatingDanReview::findOrFail($id_review);
        $user = Auth::user();

        if (!$user) {
            abort(403, 'User tidak ditemukan.');
        }

        $mahasiswa = Mahasiswa::where('email', $user->email)->first();
        $isOwner = $mahasiswa && $mahasiswa->id_mahasiswa == $review->id_mahasiswa;
        $isKoordinator = $user->role === 'koordinator';

        if (!$isOwner && !$isKoordinator) {
            return back()->with('error', 'Tidak berwenang.');
        }

        $review->delete();

        return back()->with('success', 'Review dihapus.');
    }
}
