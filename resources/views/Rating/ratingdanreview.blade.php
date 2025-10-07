<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Rating & Review</title>

  {{-- Bootstrap & FontAwesome --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

  {{-- File CSS Eksternal --}}
  <link rel="stylesheet" href="{{ asset('assets/css/ratingdanreview.css') }}">
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
               value="{{ old('nim') }}" maxlength="10" minlength="10" required>
      </div>

      {{-- Perusahaan --}}
      <div class="mb-3">
        <label class="form-label">Perusahaan</label>
        <input type="text" class="form-control" value="{{ $perusahaan->nama }}" readonly>
        <input type="hidden" name="id_perusahaan" value="{{ $perusahaan->id_perusahaan }}">
      </div>

      {{-- Rating --}}
      <div class="mb-3 text-center">
        <label for="rating" class="form-label d-block">Rating</label>
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
