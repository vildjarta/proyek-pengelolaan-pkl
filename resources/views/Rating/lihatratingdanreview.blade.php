<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lihat Rating & Review - PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- CSS Global Layout -->
    <link rel="stylesheet" href="{{ asset('assets/css/style-pkl.css') }}">
    <!-- CSS Halaman Ini -->
    <link rel="stylesheet" href="{{ asset('assets/css/lihatratingdanreview.css') }}">
</head>
<body>
    @include('layout.header')
    @include('layout.sidebar')

    <div class="main-content-wrapper">
        <div class="content container-fluid">

            <!-- Tombol Kembali -->
            <a href="{{ route('ratingperusahaan') }}" class="btn-back" title="Kembali">
                <i class="fas fa-arrow-left"></i>
            </a>

            <div class="table-header">
                <h2 class="title">Rating & Review Perusahaan</h2>
            </div>

            {{-- Notifikasi Sukses --}}
            @if(session('success'))
                <div class="alert alert-success alert-custom">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            {{-- Notifikasi Error --}}
            @if($errors->any())
                <div class="alert alert-danger alert-custom">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            @php
                use Illuminate\Support\Facades\Auth;
                use App\Models\Mahasiswa;

                // Ambil data mahasiswa yang terhubung dengan akun login (sekali saja)
                $authMahasiswa = null;
                $isAdmin = false;

                if (Auth::check()) {
                    $user = Auth::user();

                    // Jika tabel mahasiswa menyimpan email yang sama dengan auth user
                    try {
                        $authMahasiswa = Mahasiswa::where('email', $user->email)->first();
                    } catch (\Throwable $e) {
                        // jika tidak ada kolom email atau error, biarkan null
                        $authMahasiswa = null;
                    }

                    // Cek bila sistem role tersedia (opsional). Jika Anda tidak menggunakan role, ini akan false.
                    if (method_exists($user, 'hasRole')) {
                        $isAdmin = $user->hasRole('admin') || $user->hasRole('koordinator');
                    } else {
                        $isAdmin = false;
                    }
                }
            @endphp

            {{-- LIST REVIEW --}}
            <div class="review-list">
                @forelse ($reviews as $review)
                    @php
                        $nama = $review->nama_mahasiswa ?? 'Mahasiswa';
                        $inisial = strtoupper(substr($nama, 0, 1));
                        $namaTersembunyi = substr($nama, 0, 2) . str_repeat('*', max(3, strlen($nama) - 2));

                        // Pastikan kita punya id_mahasiswa pada $review (rating_dan_reviews.* seharusnya menyertakan id_mahasiswa)
                        $reviewMahasiswaId = $review->id_mahasiswa ?? null;

                        // Pemilik review?
                        $isOwner = false;
                        if ($authMahasiswa && $reviewMahasiswaId) {
                            $isOwner = ((int)$authMahasiswa->id_mahasiswa === (int)$reviewMahasiswaId);
                        }

                        // Jika admin, boleh edit/hapus juga (opsional)
                        $canModify = $isOwner || $isAdmin;
                    @endphp

                    <div class="review-card">
                        <div class="review-header d-flex align-items-center">
                            <div class="avatar me-3">{{ $inisial }}</div>
                            <div class="reviewer-info">
                                <span class="reviewer-name">{{ $namaTersembunyi }}</span>
                                <div class="review-rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <div class="review-text mt-3">
                            {{ $review->review }}
                        </div>

                        <div class="review-footer d-flex justify-content-between align-items-center mt-3">
                            <span class="review-date">
                                <i class="fa-regular fa-calendar"></i>
                                {{ \Carbon\Carbon::parse($review->tanggal_review)->translatedFormat('d F Y') }}
                            </span>

                            <div class="action-buttons">
                                {{-- Tautan Lihat tidak perlu, karena kita sudah di halaman lihat --}}
                                {{-- Tampilkan edit/hapus hanya jika pemilik atau admin --}}
                                @if($canModify)
                                    <a href="{{ route('ratingdanreview.edit', $review->id_review) }}" class="btn-edit btn btn-sm btn-outline-primary me-2" title="Edit Review">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    <form action="{{ route('ratingdanreview.destroy', $review->id_review) }}" method="POST" onsubmit="return confirm('Hapus review ini?')" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete btn btn-sm btn-outline-danger" title="Hapus Review">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info text-center mt-4">
                        Belum ada rating & review untuk perusahaan ini.
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
