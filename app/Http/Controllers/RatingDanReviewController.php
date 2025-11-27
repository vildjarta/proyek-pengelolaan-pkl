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

        // Tentukan mahasiswa yang login (jika ada)
        $currentMahasiswa = null;
        if (Auth::check()) {
            $currentMahasiswa = Mahasiswa::where('email', Auth::user()->email)->first();
        }

        // Map company id => apakah user dapat menambah review (hanya jika mahasiswa dan perusahaan sesuai & belum ada review)
        $canAddMap = [];
        foreach ($perusahaans as $p) {
            $canAddMap[$p->id_perusahaan] = false;
            if ($currentMahasiswa) {
                $belongs = ($currentMahasiswa->id_perusahaan == $p->id_perusahaan);
                if ($belongs) {
                    $already = RatingDanReview::where('id_mahasiswa', $currentMahasiswa->id_mahasiswa)
                        ->where('id_perusahaan', $p->id_perusahaan)
                        ->exists();
                    $canAddMap[$p->id_perusahaan] = !$already;
                }
            }
        }

        return view('rating.ratingperusahaan', compact('perusahaans', 'currentMahasiswa', 'canAddMap'));
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

        // Pastikan user login dan merupakan mahasiswa yang ditempatkan di perusahaan ini
        if (!Auth::check()) {
            return redirect()->route('ratingperusahaan')->with('error', 'Silakan login sebagai mahasiswa yang bekerja di perusahaan ini untuk menambahkan review.');
        }

        $mahasiswa = Mahasiswa::where('email', Auth::user()->email)->first();
        if (!$mahasiswa) {
            return redirect()->route('ratingperusahaan')->with('error', 'Akun Anda belum terhubung dengan data mahasiswa.');
        }

        if ($mahasiswa->id_perusahaan != $perusahaan->id_perusahaan) {
            return redirect()->route('ratingperusahaan')->with('error', 'Anda hanya dapat memberi review pada perusahaan tempat Anda melakukan PKL/kerja.');
        }

        // Cek apakah sudah memberi review sebelumnya
        $already = RatingDanReview::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
            ->where('id_perusahaan', $perusahaan->id_perusahaan)
            ->exists();

        if ($already) {
            return redirect()->route('lihatratingdanreview', ['id_perusahaan' => $perusahaan->id_perusahaan])
                ->with('error', 'Anda sudah memberikan review untuk perusahaan ini. Anda dapat mengeditnya jika perlu.');
        }

        // Berikan mahasiswa ke view agar form menampilkan NIM (readonly)
        return view('rating.ratingdanreview', compact('perusahaan', 'mahasiswa'));
    }

    /**
     * Simpan data review baru
     */
    public function store(Request $request)
    {
        // Jika user mahasiswa, ambil data mahasiswa dari email login
        $mahasiswa = null;
        if (Auth::check()) {
            $mahasiswa = Mahasiswa::where('email', Auth::user()->email)->first();
        }

        // Validation: jika mahasiswa ditemukan -> tidak perlu validasi nim, gunakan data mahasiswa terhubung.
        if ($mahasiswa) {
            $validated = $request->validate([
                'id_perusahaan'  => 'required|exists:perusahaan,id_perusahaan',
                'rating'         => 'required|integer|min:1|max:5',
                'review'         => 'required|string|max:500',
                'tanggal_review' => 'nullable|date',
            ]);
            // double check perusahaan sesuai
            if ($mahasiswa->id_perusahaan != $validated['id_perusahaan']) {
                return back()->withErrors(['id_perusahaan' => 'Anda hanya dapat memberi review untuk perusahaan tempat Anda bekerja.'])->withInput();
            }

            // cek duplicate
            $exists = RatingDanReview::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
                ->where('id_perusahaan', $validated['id_perusahaan'])
                ->exists();
            if ($exists) {
                return back()->withErrors(['review' => 'Anda sudah memberikan review untuk perusahaan ini.'])->withInput();
            }

            $data = [
                'id_mahasiswa' => $mahasiswa->id_mahasiswa,
                'id_perusahaan' => $validated['id_perusahaan'],
                'rating' => $validated['rating'],
                'review' => $validated['review'],
                'tanggal_review' => $validated['tanggal_review'] ?? now(),
            ];
        } else {
            // Jika bukan mahasiswa (mis. admin memasukkan manual), izinkan pengisian nim
            $validated = $request->validate([
                'nim'            => 'required|digits:10',
                'id_perusahaan'  => 'required|exists:perusahaan,id_perusahaan',
                'rating'         => 'required|integer|min:1|max:5',
                'review'         => 'required|string|max:500',
                'tanggal_review' => 'nullable|date',
            ]);
            $mahasiswa = Mahasiswa::where('nim', $validated['nim'])->first();
            if (!$mahasiswa) {
                return back()->withErrors(['nim' => 'NIM tidak ditemukan.'])->withInput();
            }

            // cek duplicate
            $exists = RatingDanReview::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
                ->where('id_perusahaan', $validated['id_perusahaan'])
                ->exists();
            if ($exists) {
                return back()->withErrors(['review' => 'Mahasiswa ini sudah memberikan review untuk perusahaan ini.'])->withInput();
            }

            $data = [
                'id_mahasiswa' => $mahasiswa->id_mahasiswa,
                'id_perusahaan' => $validated['id_perusahaan'],
                'rating' => $validated['rating'],
                'review' => $validated['review'],
                'tanggal_review' => $validated['tanggal_review'] ?? now(),
            ];
        }

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

        // Cek kepemilikan: hanya mahasiswa pemilik review (atau admin) yang boleh edit
        if (Auth::check()) {
            $mahasiswa = Mahasiswa::where('email', Auth::user()->email)->first();
            if ($mahasiswa && $mahasiswa->id_mahasiswa != $ratingdanreview->id_mahasiswa) {
                return redirect()->route('lihatratingdanreview', ['id_perusahaan' => $perusahaan->id_perusahaan])
                    ->with('error', 'Anda tidak berwenang mengedit review ini.');
            }
        } else {
            return redirect()->route('ratingperusahaan')->with('error', 'Silakan login untuk mengedit review.');
        }

        return view('rating.editratingdanreview', compact('ratingdanreview', 'perusahaan'));
    }

    /**
     * Update review
     */
    public function update(Request $request, $id_review)
    {
        $ratingdanreview = RatingDanReview::findOrFail($id_review);

        // Pastikan pemilik
        if (!Auth::check()) {
            return redirect()->route('ratingperusahaan')->with('error', 'Silakan login untuk melakukan perubahan.');
        }
        $mahasiswa = Mahasiswa::where('email', Auth::user()->email)->first();
        if (!$mahasiswa || $mahasiswa->id_mahasiswa != $ratingdanreview->id_mahasiswa) {
            return redirect()->route('lihatratingdanreview', ['id_perusahaan' => $ratingdanreview->id_perusahaan])
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

    /**
     * Hapus review
     */
    public function destroy($id_review)
    {
        $ratingdanreview = RatingDanReview::findOrFail($id_review);

        // Hanya pemilik (mahasiswa yang membuat review) yang dapat menghapus, atau admin (opsional)
        if (!Auth::check()) {
            return redirect()->route('ratingperusahaan')->with('error', 'Silakan login untuk melakukan aksi ini.');
        }
        $mahasiswa = Mahasiswa::where('email', Auth::user()->email)->first();
        if (!$mahasiswa || $mahasiswa->id_mahasiswa != $ratingdanreview->id_mahasiswa) {
            return redirect()->route('lihatratingdanreview', ['id_perusahaan' => $ratingdanreview->id_perusahaan])
                ->with('error', 'Anda tidak berwenang menghapus review ini.');
        }

        $idPerusahaan = $ratingdanreview->id_perusahaan;
        $ratingdanreview->delete();

        return redirect()
            ->route('lihatratingdanreview', ['id_perusahaan' => $idPerusahaan])
            ->with('success', 'Review berhasil dihapus!');
    }
}
