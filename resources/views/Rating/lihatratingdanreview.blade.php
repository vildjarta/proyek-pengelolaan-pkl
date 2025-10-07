<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Daftar Rating & Review</title>

  <!-- Bootstrap & Font Awesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

  <!-- CSS Eksternal -->
  <link rel="stylesheet" href="{{ asset('assets/css/lihatratingdanreview.css') }}">
</head>

<body>

  <div class="container">

    <!-- Tombol kembali -->
    <a href="{{ route('ratingperusahaan') }}" class="back-button-small">
      <i class="fas fa-arrow-left"></i>
    </a>

    <h1 class="page-title">Rating & Review Perusahaan</h1>

    {{-- List Review --}}
    @forelse ($reviews as $review)
      @php
        // 🔒 Sembunyikan sebagian nama mahasiswa
        $namaAsli = $review->nama_mahasiswa ?? 'Mahasiswa Tidak Ditemukan';
        $namaTersembunyi = strlen($namaAsli) > 2 
            ? substr($namaAsli, 0, 2) . str_repeat('*', max(3, strlen($namaAsli) - 2)) 
            : $namaAsli;

        // Inisial avatar
        $inisial = strtoupper(substr($namaAsli, 0, 1));
      @endphp

      <div class="review-card">
        <div class="review-header">
          <div class="avatar">{{ $inisial }}</div>

          <div class="reviewer-info">
            <span class="reviewer-name">{{ $namaTersembunyi }}</span>
            <div class="review-rating">
              @for ($i = 1; $i <= $review->rating; $i++)
                <i class="fas fa-star"></i>
              @endfor
              @for ($i = $review->rating + 1; $i <= 5; $i++)
                <i class="far fa-star"></i>
              @endfor
            </div>
          </div>
        </div>

        <div class="review-text">{{ $review->review }}</div>

        <div class="review-footer">
          <span>Tanggal: {{ \Carbon\Carbon::parse($review->tanggal_review)->translatedFormat('d F Y') }}</span>
        </div>

        <!-- Tombol Edit & Hapus -->
        <div class="action-buttons">
          <a href="{{ route('ratingdanreview.edit', $review->id_review) }}" class="btn">
            <i class="fas fa-pen"></i>
          </a>
          <form action="{{ route('ratingdanreview.destroy', $review->id_review) }}" 
                method="POST" onsubmit="return confirm('Hapus review ini?')">
            @csrf
            @method('DELETE')
            <button class="btn">
              <i class="fas fa-trash"></i>
            </button>
          </form>
        </div>
      </div>

    @empty
      <div class="alert alert-info text-center mt-4">
        Belum ada rating & review.
      </div>
    @endforelse

    <!-- Pagination -->
    <div class="mt-4 d-flex justify-content-center">
      {{ $reviews->links() }}
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
