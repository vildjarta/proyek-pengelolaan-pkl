<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Daftar Rating & Review</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <style>
    /* Background */
    body {
      background: linear-gradient(135deg, #F7FBFC 0%, #D6E6F2 35%, #B9D7EA 70%, #769FCD 100%);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      min-height: 100vh;
      padding: 30px 10px;
    }

    /* Panah kembali bulat */
    .back-button-small {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
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
      color: #769FCD;
      font-weight: bold;
      text-align: center;
      margin-bottom: 30px;
      font-size: 2.2rem;
    }

    /* Form Search */
    .search-form input {
      border-radius: 50px;
      padding: 10px 20px;
    }
    .search-form button {
      border-radius: 50px;
      padding: 10px 20px;
      background-color: #769FCD;
      border-color: #769FCD;
      font-weight: 600;
    }
    .search-form button:hover {
      background-color: #5f88b2;
      border-color: #5f88b2;
    }

    /* Card review */
    .card {
      border-radius: 15px;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
      border: none;
    }
    .card-body {
      position: relative;
      padding: 25px;
    }
    .review-header {
      font-size: 1.2rem;
      font-weight: bold;
      color: #007bff;
      margin-bottom: 5px;
    }

    /* Bintang */
    .star {
      color: #ffc107;
      font-size: 1.3rem;
    }

    .review-text {
      font-size: 1rem;
      color: #555;
      margin-top: 10px;
      margin-bottom: 5px;
    }
    .tanggal-review {
      font-size: 0.9rem;
      color: #888;
    }

    /* Tombol aksi */
    .action-buttons .btn {
      border-radius: 50px;
      padding: 6px 15px;
      font-size: 0.9rem;
    }

    .pagination {
      justify-content: center;
    }
  </style>
</head>
<body>

  <div class="container">

    <!-- Panah kembali -->
    <a href="{{ route('ratingperusahaan') }}" class="back-button-small">
      <i class="fas fa-arrow-left"></i>
    </a>

    <h1 class="page-title">Daftar Rating & Review</h1>

    <!-- Form Search -->
    <form class="search-form d-flex mb-4 justify-content-center" 
          action="{{ route('lihatratingdanreview') }}" 
          method="GET" style="max-width:500px;margin:auto;">
      <input class="form-control me-2" 
             type="text" 
             name="search" 
             placeholder="Cari berdasarkan ID Mahasiswa atau Perusahaan..." 
             value="{{ request('search') }}">
      <button class="btn btn-primary" type="submit">
        <i class="fas fa-search"></i> Cari
      </button>
    </form>

    <!-- List Reviews -->
    @forelse ($reviews as $review)
      <div class="card mb-4">
        <div class="card-body">
          <div class="review-header">Mahasiswa ID: {{ $review->id_mahasiswa }}</div>
          <div>Perusahaan ID: <strong>{{ $review->id_perusahaan }}</strong></div>
          
          <!-- Rating stars -->
          <div class="mt-2">
            @for ($i = 1; $i <= $review->rating; $i++)
              <i class="fas fa-star star"></i>
            @endfor
            @for ($i = $review->rating + 1; $i <= 5; $i++)
              <i class="far fa-star star"></i>
            @endfor
          </div>

          <p class="review-text">{{ $review->review }}</p>
          <small class="tanggal-review">
            Tanggal: {{ \Carbon\Carbon::parse($review->tanggal_review)->translatedFormat('d F Y') }}
          </small>

          <div class="mt-3 d-flex justify-content-end gap-2 action-buttons">
            <a href="{{ route('ratingdanreview.edit', $review) }}" class="btn btn-warning btn-sm">
              <i class="fas fa-edit"></i> Edit
            </a>
            <form action="{{ route('ratingdanreview.destroy', $review) }}" method="POST" onsubmit="return confirm('Hapus review ini?')">
              @csrf
              @method('DELETE')
              <button class="btn btn-danger btn-sm">
                <i class="fas fa-trash"></i> Hapus
              </button>
            </form>
          </div>
        </div>
      </div>
    @empty
      <div class="alert alert-info text-center">
        Belum ada rating & review.
      </div>
    @endforelse

    <!-- Pagination -->
    <div class="mt-4">
      {{ $reviews->links() }}
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
