<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Rating & Review</title>
    <style>
        /* Anda bisa menyalin CSS dari file ratingdanreview.blade.php di sini */
        body { font-family: Arial, sans-serif; background: #f5f6fa; padding: 20px; }
        .card { background: #fff; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); padding: 16px; }
        .form-label { display: block; margin-bottom: 6px; font-weight: 600; }
        .form-control, .form-select, textarea { width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc; margin-bottom: 12px; font-size: 14px; }
        .btn-success { background: #28a745; color: #fff; border: none; padding: 8px 14px; border-radius: 6px; cursor: pointer; }
    </style>
</head>
<body>

    <h2>Edit Rating & Review</h2>
    
    <div class="card">
        {{-- Mengarahkan form ke route update dengan ID yang spesifik --}}
        <form action="{{ route('ratingdanreview.update', $ratingdanreview->id_review) }}" method="POST">
            @csrf
            @method('PUT') {{-- Metode PUT diperlukan untuk operasi update --}}

            <label for="id_mahasiswa" class="form-label">ID Mahasiswa</label>
            <input type="number" name="id_mahasiswa" id="id_mahasiswa" class="form-control" value="{{ $ratingdanreview->id_mahasiswa }}" required>

            <label for="id_perusahaan" class="form-label">ID / Nama Perusahaan</label>
            <input type="number" name="id_perusahaan" id="id_perusahaan" class="form-control" value="{{ $ratingdanreview->id_perusahaan }}" required>

            <label for="rating" class="form-label">Rating</label>
            <select name="rating" id="rating" class="form-select" required>
                <option value="">-- Pilih Rating --</option>
                <option value="1" {{ $ratingdanreview->rating == 1 ? 'selected' : '' }}>1 ⭐</option>
                <option value="2" {{ $ratingdanreview->rating == 2 ? 'selected' : '' }}>2 ⭐⭐</option>
                <option value="3" {{ $ratingdanreview->rating == 3 ? 'selected' : '' }}>3 ⭐⭐⭐</option>
                <option value="4" {{ $ratingdanreview->rating == 4 ? 'selected' : '' }}>4 ⭐⭐⭐⭐</option>
                <option value="5" {{ $ratingdanreview->rating == 5 ? 'selected' : '' }}>5 ⭐⭐⭐⭐⭐</option>
            </select>

            <label for="review" class="form-label">Review</label>
            <textarea name="review" id="review" rows="3" class="form-control" required>{{ $ratingdanreview->review }}</textarea>

            <label for="tanggal_review" class="form-label">Tanggal Review</label>
            <input type="date" name="tanggal_review" id="tanggal_review" class="form-control" value="{{ $ratingdanreview->tanggal_review }}" required>

            <button type="submit" class="btn-success">Update</button>
        </form>
    </div>

</body>
</html>