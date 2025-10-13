<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Rating & Review</title>

  {{-- Bootstrap & FontAwesome --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

  {{-- CSS --}}
  <link rel="stylesheet" href="{{ asset('assets/css/style-pkl.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/ratingdanreview.css') }}">
</head>
<body>
  {{-- HEADER --}}
  @include('layout.header')

  {{-- SIDEBAR --}}
  @include('layout.sidebar')

  {{-- Konten Utama --}}
  <div class="main-content-wrapper">
    <div class="full-container">
      <h1 class="page-title">Tambah Rating & Review</h1>

      {{-- Pesan Error --}}
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
      <form action="{{ route('ratingdanreview.store') }}" method="POST" class="rating-form">
        @csrf

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="nim" class="form-label">NIM Mahasiswa</label>
            <input type="text" id="nim" name="nim" class="form-control"
                   value="{{ old('nim') }}" maxlength="10" minlength="10" required>
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Perusahaan</label>
            <input type="text" class="form-control" value="{{ $perusahaan->nama }}" readonly>
            <input type="hidden" name="id_perusahaan" value="{{ $perusahaan->id_perusahaan }}">
          </div>
        </div>

        {{-- Rating --}}
        <div class="mb-4 text-center">
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
          <textarea id="review" name="review" class="form-control" rows="4" required>{{ old('review') }}</textarea>
        </div>

        {{-- Tanggal --}}
        <div class="mb-4">
          <label for="tanggal_review" class="form-label">Tanggal Review</label>
          <input type="date" id="tanggal_review" name="tanggal_review" class="form-control"
                 value="{{ old('tanggal_review', now()->toDateString()) }}" required>
        </div>

        {{-- Tombol --}}
        <div class="d-flex justify-content-end gap-3">
          <a href="{{ route('ratingperusahaan') }}" class="btn btn-secondary">
            <i class="fas fa-times-circle me-1"></i> Batal
          </a>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
