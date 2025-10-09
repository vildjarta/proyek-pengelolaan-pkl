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
    {{-- HEADER & SIDEBAR --}}
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

            {{-- LIST REVIEW --}}
            <div class="review-list">
                @forelse ($reviews as $review)
                    @php
                        $nama = $review->nama_mahasiswa ?? 'Mahasiswa';
                        $inisial = strtoupper(substr($nama, 0, 1));
                        $namaTersembunyi = substr($nama, 0, 2) . str_repeat('*', max(3, strlen($nama) - 2));
                    @endphp

                    <div class="review-card">
                        <div class="review-header">
                            <div class="avatar">{{ $inisial }}</div>
                            <div class="reviewer-info">
                                <span class="reviewer-name">{{ $namaTersembunyi }}</span>
                                <div class="review-rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <div class="review-text">
                            {{ $review->review }}
                        </div>

                        <div class="review-footer">
                            <span class="review-date">
                                <i class="fa-regular fa-calendar"></i>
                                {{ \Carbon\Carbon::parse($review->tanggal_review)->translatedFormat('d F Y') }}
                            </span>

                            <div class="action-buttons">
                                <a href="{{ route('ratingdanreview.edit', $review->id_review) }}" class="btn-edit" title="Edit Review">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('ratingdanreview.destroy', $review->id_review) }}" method="POST" onsubmit="return confirm('Hapus review ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" title="Hapus Review">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
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
