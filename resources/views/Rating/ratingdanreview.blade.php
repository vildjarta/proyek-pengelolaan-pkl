<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Rating & Review</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #F7FBFC 0%, #D6E6F2 35%, #B9D7EA 70%, #769FCD 100%);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #343a40;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
      padding: 20px;
    }
    .form-container {
      background-color: #fff;
      padding: 40px 30px;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
      width: 100%;
      max-width: 600px;
      position: relative;
    }
    .back-button-small {
      position: absolute;
      top: 15px;
      left: 15px;
      color: #007bff;
      font-size: 1.6rem;
      text-decoration: none;
      transition: 0.3s;
    }
    .back-button-small:hover {
      color: #0056b3;
      transform: translateX(-3px);
    }
    .header-title {
      font-size: 2rem;
      font-weight: bold;
      color: #007bff;
      text-align: center;
      margin-bottom: 30px;
    }
    .form-control {
      border-radius: 50px;
      padding: 12px 20px;
    }
    .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
      font-weight: bold;
      border-radius: 50px;
      padding: 12px 20px;
      transition: 0.3s;
    }
    .btn-primary:hover {
      background-color: #0056b3;
      border-color: #0056b3;
    }
    .rating-stars {
      unicode-bidi: bidi-override;
      direction: rtl;
      text-align: center;
      margin-top: 5px;
    }
    .rating-stars > input {
      display: none;
    }
    .rating-stars > label {
      display: inline-block;
      position: relative;
      width: 1.3em;
      cursor: pointer;
      font-size: 2.5rem;
      color: #ccc;
      transition: 0.3s;
    }
    .rating-stars > label:hover,
    .rating-stars > label:hover ~ label {
      color: #ffc107;
    }
    .rating-stars > input:checked ~ label {
      color: #ffc107;
    }
    .alert ul { margin-bottom: 0; }
  </style>
</head>
<body>
  <div class="form-container">
    <!-- Tombol kembali -->
    <a href="{{ route('ratingperusahaan') }}" class="back-button-small">
      <i class="fas fa-arrow-left"></i>
    </a>

    <h1 class="header-title">Tambah Rating & Review</h1>

    {{-- Pesan error --}}
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('ratingdanreview.store') }}" method="POST">
      @csrf

      {{-- NIM Mahasiswa --}}
      <div class="mb-3">
        <label for="nim" class="form-label">NIM Mahasiswa</label>
        <input type="text" class="form-control" id="nim" name="nim"
          value="{{ old('nim') }}" maxlength="10" minlength="10"
      </div>

      {{-- Perusahaan --}}
      <div class="mb-3">
        <label class="form-label">Perusahaan</label>
        <input type="text" class="form-control" 
               value="{{ $perusahaan->nama }}" readonly>
        <input type="hidden" name="id_perusahaan" 
               value="{{ $perusahaan->id_perusahaan }}">
      </div>

      {{-- Rating --}}
      <div class="mb-3">
        <label for="rating" class="form-label d-block text-center">Rating</label>
        <div class="rating-stars">
          @for ($i = 5; $i >= 1; $i--)
            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}"
              {{ old('rating') == $i ? 'checked' : '' }} required>
            <label for="star{{ $i }}" title="{{ $i }} Bintang"><i class="fas fa-star"></i></label>
          @endfor
        </div>
      </div>

      {{-- Review --}}
      <div class="mb-3">
        <label for="review" class="form-label">Review</label>
        <textarea class="form-control" id="review" name="review" rows="3" required>{{ old('review') }}</textarea>
      </div>

      {{-- Tanggal Review --}}
      <div class="mb-3">
        <label for="tanggal_review" class="form-label">Tanggal Review</label>
        <input type="date" class="form-control" id="tanggal_review" name="tanggal_review"
          value="{{ old('tanggal_review', now()->toDateString()) }}" required>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Simpan Review</button>
      </div>
    </form>
  </div>
</body>
</html>
