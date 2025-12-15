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
    protected function resolveMahasiswaCompanyId(Mahasiswa $mahasiswa): ?int
    {
        if (isset($mahasiswa->id_perusahaan) && !empty($mahasiswa->id_perusahaan)) {
            return (int) $mahasiswa->id_perusahaan;
        }

        if (!empty($mahasiswa->perusahaan)) {
            $perusahaan = Perusahaan::where('nama', $mahasiswa->perusahaan)->first();
            if ($perusahaan) {
                return (int) $perusahaan->id_perusahaan;
            }
        }

        return null;
    }

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

        $currentMahasiswa = null;
        $mahasiswaCompanyId = null;

        if (Auth::check()) {
            $currentMahasiswa = Mahasiswa::where('email', Auth::user()->email)->first();

            if ($currentMahasiswa) {
                $mahasiswaCompanyId = $this->resolveMahasiswaCompanyId($currentMahasiswa);
            }
        }

        $canAddMap = [];
        foreach ($perusahaans as $p) {
            $canAddMap[$p->id_perusahaan] = false;

            if ($currentMahasiswa && $mahasiswaCompanyId && $mahasiswaCompanyId == $p->id_perusahaan) {
                $already = RatingDanReview::where('id_mahasiswa', $currentMahasiswa->id_mahasiswa)
                    ->where('id_perusahaan', $p->id_perusahaan)
                    ->exists();

                $canAddMap[$p->id_perusahaan] = !$already;
            }
        }

        return view('rating.ratingperusahaan', compact('perusahaans', 'currentMahasiswa', 'canAddMap'));
    }

    public function index(Request $request, $id_perusahaan)
    {
        $perusahaan = Perusahaan::findOrFail($id_perusahaan);

        $reviewsQuery = RatingDanReview::with(['mahasiswa' => function($q) {
                $q->with('user');
            }])
            ->where('id_perusahaan', $id_perusahaan);

        if ($request->filled('search')) {
            $search = $request->search;
            $reviewsQuery->whereHas('mahasiswa', function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                    ->orWhere('nim', 'like', "%$search%");
            })->orWhere('review', 'like', "%$search%");
        }

        switch ($request->filter) {
            case 'highest':
                $reviewsQuery->orderByDesc('rating');
                break;
            case 'lowest':
                $reviewsQuery->orderBy('rating', 'asc');
                break;
            default:
                $reviewsQuery->orderByDesc('tanggal_review');
                break;
        }

        $reviews = $reviewsQuery->paginate(10)->withQueryString();

        foreach($reviews as $review) {
            if($review->mahasiswa && !isset($review->nama_mahasiswa)) {
                $review->nama_mahasiswa = $review->mahasiswa->nama;
            }
        }

        return view('rating.lihatratingdanreview', compact('reviews', 'perusahaan'));
    }

    public function create($id_perusahaan)
    {
        if (!is_numeric($id_perusahaan)) {
            return redirect()->route('ratingperusahaan')->with('error', 'ID perusahaan tidak valid.');
        }

        $perusahaan = Perusahaan::findOrFail($id_perusahaan);

        if (!Auth::check()) {
            return redirect()->route('ratingperusahaan')->with('error', 'Silakan login terlebih dahulu.');
        }

        // additional role guard: only mahasiswa and koordinator may access the create form
        $user = Auth::user();
        if (isset($user->role) && !in_array($user->role, ['mahasiswa', 'koordinator'])) {
            return redirect()->route('ratingperusahaan')->with('error', 'Anda tidak berwenang menambahkan review.');
        }

        $mahasiswa = Mahasiswa::where('email', Auth::user()->email)->first();
        if (!$mahasiswa) {
            return redirect()->route('ratingperusahaan')->with('error', 'Akun Anda belum terhubung dengan data mahasiswa.');
        }

        $mahasiswaCompanyId = $this->resolveMahasiswaCompanyId($mahasiswa);

        if (!$mahasiswaCompanyId || $mahasiswaCompanyId != $perusahaan->id_perusahaan) {
            return redirect()->route('ratingperusahaan')->with(
                'error',
                'Anda hanya dapat memberi rating pada perusahaan tempat Anda PKL.'
            );
        }

        $already = RatingDanReview::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
            ->where('id_perusahaan', $perusahaan->id_perusahaan)
            ->exists();

        if ($already) {
            return redirect()->route('lihatratingdanreview', ['id_perusahaan' => $perusahaan->id_perusahaan])
                ->with('error', 'Anda sudah memberikan review untuk perusahaan ini. Silakan edit jika ingin mengubah.');
        }

        return view('rating.ratingdanreview', compact('perusahaan', 'mahasiswa'));
    }

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

        // role guard: if logged in and in one of the read-only roles, prevent adding
        if (Auth::check()) {
            $user = Auth::user();
            if (isset($user->role) && in_array($user->role, ['ketua_prodi','perusahaan','dosen_pembimbing','dosen','dosen_penguji','staff'])) {
                return redirect()->route('ratingperusahaan')->with('error', 'Anda tidak berwenang menambahkan review.');
            }
        }

        if ($mahasiswa) {
            $validated = $request->validate([
                'id_perusahaan'  => 'required|exists:perusahaan,id_perusahaan',
                'rating'         => 'required|integer|min:1|max:5',
                'review'         => 'required|string|max:500',
                'tanggal_review' => 'nullable|date',
            ]);

            if (!$mahasiswaCompanyId || $mahasiswaCompanyId != $validated['id_perusahaan']) {
                return back()
                    ->withErrors(['id_perusahaan' => 'Anda hanya dapat memberi review pada perusahaan tempat Anda PKL.'])
                    ->withInput();
            }

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

    public function edit($id_review)
    {
        $ratingdanreview = RatingDanReview::findOrFail($id_review);
        $perusahaan = Perusahaan::findOrFail($ratingdanreview->id_perusahaan);

        if (!Auth::check()) {
            return redirect()->route('ratingperusahaan')->with('error', 'Silakan login untuk mengedit review.');
        }

        $mahasiswa = Mahasiswa::where('email', Auth::user()->email)->first();

        // hanya pemilik review yang boleh edit (walau user role koordinator tetap harus punya data mahasiswa & jadi owner)
        if (!$mahasiswa || $mahasiswa->id_mahasiswa != $ratingdanreview->id_mahasiswa) {
            return redirect()
                ->route('lihatratingdanreview', ['id_perusahaan' => $perusahaan->id_perusahaan])
                ->with('error', 'Anda tidak berwenang mengedit review ini.');
        }

        return view('rating.editratingdanreview', compact('ratingdanreview', 'perusahaan'));
    }

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

    public function destroy($id_review)
    {
        $ratingdanreview = RatingDanReview::findOrFail($id_review);

        if (!Auth::check()) {
            return redirect()->route('ratingperusahaan')->with('error', 'Silakan login untuk melakukan aksi ini.');
        }

        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('email', $user->email)->first();

        // cek role koordinator (sesuaikan jika memakai package role/permission)
        $isKoordinator = isset($user->role) && $user->role === 'koordinator';

        // pemilik review?
        $isOwner = false;
        if ($mahasiswa && $mahasiswa->id_mahasiswa == $ratingdanreview->id_mahasiswa) {
            $isOwner = true;
        }

        // izinkan hapus bila owner atau koordinator
        if (! $isOwner && ! $isKoordinator) {
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

    /**
     * Destroy all reviews (only for koordinator)
     */
    public function destroyAll(Request $request)
    {
        if (!Auth::check() || !isset(Auth::user()->role) || Auth::user()->role !== 'koordinator') {
            return redirect()->route('ratingperusahaan')->with('error', 'Anda tidak berwenang melakukan aksi ini.');
        }

        // delete all reviews
        try {
            RatingDanReview::query()->delete();
        } catch (\Throwable $e) {
            \Log::error('Failed to delete all reviews: ' . $e->getMessage());
            return redirect()->route('ratingperusahaan')->with('error', 'Terjadi kesalahan saat menghapus data.');
        }

        return redirect()->route('ratingperusahaan')->with('success', 'Semua rating berhasil dihapus.');
    }
}
