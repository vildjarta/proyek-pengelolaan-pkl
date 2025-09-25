<!DOCTYPE html>
<html>
<head>
    <title>Edit Review</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow rounded">
        <div class="card-header bg-primary text-white">
            <h4>Edit Review</h4>
        </div>
        <div class="card-body">

            <form action="{{ route('ratingdanreview.update', $review) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>ID Mahasiswa</label>
                    <input type="number" name="id_mahasiswa" class="form-control" 
                        value="{{ old('id_mahasiswa', $review->id_mahasiswa) }}" required>
                </div>

                <div class="mb-3">
                    <label>ID Perusahaan</label>
                    <input type="number" name="id_perusahaan" class="form-control" 
                        value="{{ old('id_perusahaan', $review->id_perusahaan) }}" required>
                </div>

                <div class="mb-3">
                    <label>Rating</label>
                    <select name="rating" class="form-select" required>
                        @for ($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ $review->rating == $i ? 'selected' : '' }}>
                                {{ $i }} Bintang
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="mb-3">
                    <label>Review</label>
                    <textarea name="review" class="form-control" rows="4" required>{{ old('review', $review->review) }}</textarea>
                </div>

                <button type="submit" class="btn btn-success">💾 Simpan</button>
                <a href="{{ route('lihatratingdanreview') }}" class="btn btn-secondary">Batal</a>
            </form>

        </div>
    </div>
</div>

</body>
</html>
