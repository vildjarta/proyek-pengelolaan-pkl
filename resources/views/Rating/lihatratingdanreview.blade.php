<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Daftar Rating & Review</title>

  <!-- Bootstrap & Font Awesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

  <style>
    body {
      background: linear-gradient(135deg, #F7FBFC 0%, #D6E6F2 35%, #B9D7EA 70%, #769FCD 100%);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      min-height: 100vh;
      padding: 30px 15px;
    }

    .container {
      max-width: 850px;
    }

    /* Tombol kembali */
    .back-button-small {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 42px;
      height: 42px;
      background-color: #ffffff;
      color: #769FCD;
      border-radius: 50%;
      text-decoration: none;
      font-size: 1.2rem;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
      transition: 0.3s;
      margin-bottom: 15px;
    }
    .back-button-small:hover {
      background-color: #f0f0f0;
      color: #5f88b2;
      transform: translateX(-3px);
    }

    /* Judul halaman */
    .page-title {
      color: #4169E1;
      font-weight: 700;
      text-align: center;
      margin-bottom: 30px;
      font-size: 2rem;
      letter-spacing: 0.5px;
    }

    /* Card review */
    .review-card {
      background-color: #ffffff;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
      border: none;
      padding: 25px 25px 15px 25px;
      margin-bottom: 20px;
      transition: all 0.2s ease-in-out;
      position: relative;
    }

    .review-card:hover {
      transform: scale(1.01);
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
    }

    .review-header {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    /* Avatar bulat default */
    .avatar {
      width: 45px;
      height: 45px;
      background-color: #B9D7EA;
      color: #fff;
      font-weight: 600;
      font-size: 1.1rem;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      text-transform: uppercase;
    }

    .reviewer-info {
      display: flex;
      flex-direction: column;
    }

    .reviewer-name {
      font-size: 1.05rem;
      font-weight: 600;
      color: #007bff;
    }

    .review-rating {
      margin-top: 3px;
    }

    .review-rating i {
      color: #ffc107;
      font-size: 1rem;
      margin-right: 2px;
    }

    .review-text {
      font-size: 1rem;
      color: #444;
      margin-top: 15px;
      line-height: 1.5;
    }

    .review-footer {
      margin-top: 12px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 0.9rem;
      color: #777;
    }

    /* Tombol abu halus */
    .action-buttons .btn {
      border-radius: 30px;
      font-size: 0.85rem;
      padding: 6px 14px;
      background-color: #e9ecef;
      border: none;
      color: #333;
      transition: 0.2s;
    }

    .action-buttons .btn:hover {
      background-color: #d6dee8;
      color: #000;
    }

    /* Tombol edit dan hapus di pojok kanan bawah */
    .action-buttons {
      position: absolute;
      bottom: 10px;
      right: 15px;
      display: flex;
      gap: 8px;
    }

    .alert-info {
      background-color: #eaf3ff;
      color: #4169E1;
      border: none;
      font-weight: 500;
    }

    footer {
      text-align: center;
      color: #555;
      font-size: 0.9rem;
      margin-top: 40px;
    }
  </style>
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

        <!-- Tombol di pojok kanan bawah -->
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
