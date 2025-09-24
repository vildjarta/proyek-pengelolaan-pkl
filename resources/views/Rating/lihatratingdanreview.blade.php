<!DOCTYPE html>
<html>
<head>
    <title>Daftar Rating dan Review</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #343a40;
        }
        .container {
            max-width: 900px;
            margin-top: 30px;
        }
        .header-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: #495057;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
            text-align: center;
            margin-bottom: 20px; /* Jarak bawah */
        }
        .back-button-small { /* Style baru untuk panah kecil */
            position: absolute; /* Posisikan secara absolut */
            top: 30px; /* Jarak dari atas */
            left: 30px; /* Jarak dari kiri */
            color: #495057;
            font-size: 1.5rem; /* Ukuran yang lebih kecil */
            text-decoration: none;
            z-index: 10;
        }
        .back-button-small:hover {
            color: #007bff;
        }

        /* --- Sisa CSS Anda --- */
        .search-form {
            margin-bottom: 20px;
        }
        .search-form .form-control {
            border-radius: 50px;
            padding: 10px 20px;
            border: 1px solid #ced4da;
        }
        .search-form .btn {
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: bold;
        }
        .review-list {
            display: grid;
            gap: 15px;
        }
        .review-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: none;
            border: 1px solid #e9ecef;
            padding: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .review-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .review-header {
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 10px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .review-header h5 {
            font-weight: 600;
            color: #007bff;
            margin: 0;
            font-size: 1.1rem;
        }
        .review-rating {
            font-size: 1.5rem;
            color: #ffc107;
            display: flex;
            align-items: center;
        }
        .review-rating .star {
            font-size: 1.2rem;
            margin-right: 1px;
        }
        .review-body p {
            color: #495057;
            line-height: 1.6;
            font-size: 0.95rem;
        }
        .review-meta {
            font-size: 0.8rem;
            color: #6c757d;
            text-align: right;
            margin-top: 10px;
        }
        .empty-state {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: none;
            border: 1px solid #e9ecef;
            padding: 30px;
            text-align: center;
        }
        .pagination-container {
            margin-top: 20px;
            padding-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <a href="/ratingperusahaan" class="back-button-small">
        <i class="fas fa-arrow-left"></i>
    </a>
    
    <h1 class="header-title">Daftar Rating dan Review</h1>

    <form class="search-form d-flex" action="{{ route('lihatratingdanreview') }}" method="GET">
        <input class="form-control me-3" type="text" name="search" placeholder="Cari berdasarkan ID..." value="{{ request('search') }}">
        <button class="btn btn-primary" type="submit">Cari</button>
    </form>

    <div class="review-list">
        @forelse ($reviews as $review)
            <div class="review-card">
                <div class="review-header">
                    <div>
                        <h5 class="mb-1">Review dari Mahasiswa ID: {{ $review->id_mahasiswa }}</h5>
                        <div>untuk Perusahaan ID: <span class="fw-bold">{{ $review->id_perusahaan }}</span></div>
                    </div>
                    <div class="review-rating">
                        @for ($i = 0; $i < $review->rating; $i++)
                            <span class="star">&#9733;</span>
                        @endfor
                    </div>
                </div>
                <div class="review-body">
                    <p>{{ $review->review }}</p>
                    <div class="review-meta">Tanggal Review: {{ \Carbon\Carbon::parse($review->tanggal_review)->translatedFormat('d F Y') }}</div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <p class="h4 text-muted">Tidak ada data review yang ditemukan.</p>
                <p class="text-secondary">Coba cari dengan ID lain atau tambahkan review baru.</p>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center pagination-container">
        {{ $reviews->links() }}
    </div>

</div>

</body>
</html>