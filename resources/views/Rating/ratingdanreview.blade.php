<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rating & Review</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f6fa; margin: 0; padding: 20px; }
        h2 { text-align: center; margin-bottom: 20px; color: #2c3e50; }
        .card { background: #fff; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); overflow: hidden; }
        .card-header { padding: 12px 16px; font-weight: bold; color: #fff; }
        .bg-primary { background: #007bff; }
        .bg-dark { background: #343a40; }
        .card-body { padding: 16px; }
        .form-label { display: block; margin-bottom: 6px; font-weight: 600; }
        .form-control, .form-select, textarea { width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc; margin-bottom: 12px; font-size: 14px; }
        button { padding: 8px 14px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; }
        .btn-success { background: #28a745; color: #fff; }
        .btn-warning { background: #ffc107; color: #000; }
        .btn-danger { background: #dc3545; color: #fff; }
        table { width: 100%; border-collapse: collapse; text-align: center; }
        th, td { border: 1px solid #ddd; padding: 10px; }
        thead th { background: #2c3e50; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }
        .pagination { display: flex; justify-content: center; list-style: none; padding: 0; margin-top: 15px; }
        .pagination li { margin: 0 4px; }
        .pagination li a, .pagination li span { display: block; padding: 6px 12px; border: 1px solid #007bff; border-radius: 4px; color: #007bff; text-decoration: none; font-size: 14px; }
        .pagination li.active span { background: #007bff; color: #fff; }
        .pagination li a:hover { background: #007bff; color: #fff; }
    </style>
</head>
<body>

    <h2>Rating & Review</h2>

    {{-- Form Tambah Rating & Review --}}
    <div class="card">
        <div class="card-header bg-primary">Tambah Rating & Review</div>
        <div class="card-body">
            <form action="{{ route('ratingdanreview.store') }}" method="POST">
                @csrf
                
                <label for="id_mahasiswa" class="form-label">ID Mahasiswa</label>
                <input type="number" name="id_mahasiswa" id="id_mahasiswa" class="form-control" required placeholder="Contoh: 123">

                <label for="id_perusahaan" class="form-label">ID / Nama Perusahaan</label>
                <input type="number" name="id_perusahaan" id="id_perusahaan" class="form-control" required placeholder="Contoh: 456">

                <label for="rating" class="form-label">Rating</label>
                <select name="rating" id="rating" class="form-select" required>
                    <option value="">-- Pilih Rating --</option>
                    <option value="1">1 ⭐</option>
                    <option value="2">2 ⭐⭐</option>
                    <option value="3">3 ⭐⭐⭐</option>
                    <option value="4">4 ⭐⭐⭐⭐</option>
                    <option value="5">5 ⭐⭐⭐⭐⭐</option>
                </select>

                <label for="review" class="form-label">Review</label>
                <textarea name="review" id="review" rows="3" class="form-control" required></textarea>

                <label for="tanggal_review" class="form-label">Tanggal Review</label>
                <input type="date" name="tanggal_review" id="tanggal_review" class="form-control" required>

                <button type="submit" class="btn-success">Simpan</button>
            </form>
        </div>
    </div>

    {{-- Tabel Daftar Rating & Review --}}
    <div class="card">
        <div class="card-header bg-dark">Daftar Rating & Review</div>
        <div class="card-body">
            <table>
                <thead>
                    <tr>
                        <th>ID Review</th>
                        <th>ID Mahasiswa</th>
                        <th>Perusahaan</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Tanggal Review</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $item)
                    <tr>
                        <td>{{ $item->id_review }}</td>
                        <td>{{ $item->id_mahasiswa }}</td>
                        <td>{{ $item->id_perusahaan }}</td>
                        <td>
                            @for ($i = 1; $i <= $item->rating; $i++)
                                ⭐
                            @endfor
                        </td>
                        <td>{{ $item->review }}</td>
                        <td>{{ $item->tanggal_review }}</td>
                        <td>
                            <a href="{{ route('ratingdanreview.edit', $item->id_review) }}" class="btn-warning">Edit</a>
                            <form action="{{ route('ratingdanreview.destroy', $item->id_review) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger" onclick="return confirm('Hapus review ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div>
                {{ $reviews->links() }}
            </div>
        </div>
    </div>

</body>
</html>