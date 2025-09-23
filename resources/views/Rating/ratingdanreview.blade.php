<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rating & Review</title>
    <style>
        :root {
            --primary-color: #4A90E2;
            --dark-bg: #34495e;
            --light-bg: #ecf0f1;
            --text-color: #34495e;
            --success-color: #2ecc71;
            --warning-color: #f1c40f;
            --danger-color: #e74c3c;
            --border-color: #bdc3c7;
            --card-bg: #fff;
            --card-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: var(--light-bg);
            margin: 0;
            padding: 20px;
            color: var(--text-color);
        }
        
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: var(--dark-bg);
            font-weight: 600;
        }

        /* --- PERUBAHAN DI SINI --- */
        .container {
            /* Hapus max-width untuk membuat konten melebar full layar */
            max-width: none; 
            margin: 0; /* Hapus margin otomatis */
        }

        .card {
            background: var(--card-bg);
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }

        .card-header {
            padding: 15px 20px;
            font-weight: bold;
            color: #fff;
        }

        .bg-primary { background: var(--primary-color); }
        .bg-dark { background: var(--dark-bg); }

        .card-body {
            padding: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .form-control, .form-select, textarea {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            margin-bottom: 15px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        .form-control:focus, .form-select:focus, textarea:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        button, .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.2s, background-color 0.2s;
        }

        button:hover, .btn:hover {
            transform: translateY(-2px);
        }

        .btn-success { background: var(--success-color); color: #fff; }
        .btn-success:hover { background: #27ae60; }
        
        .btn-warning { background: var(--warning-color); color: var(--text-color); }
        .btn-warning:hover { background: #f39c12; }
        
        .btn-danger { background: var(--danger-color); color: #fff; }
        .btn-danger:hover { background: #c0392b; }

        .btn-primary { background: var(--primary-color); color: #fff; }
        .btn-primary:hover { background: #3484d8; }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        th, td {
            border: 1px solid var(--border-color);
            padding: 12px;
        }

        thead th {
            background: var(--dark-bg);
            color: white;
        }

        tr:nth-child(even) { background: #f9fafb; }
        tr:hover { background: #eef1f4; }
        
        .search-form {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .search-form .form-control {
            margin-bottom: 0;
            flex-grow: 1;
        }

        .pagination {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
            margin-top: 20px;
        }

        .pagination li {
            margin: 0 4px;
        }

        .pagination li a, .pagination li span {
            display: block;
            padding: 8px 14px;
            border: 1px solid var(--primary-color);
            border-radius: 6px;
            color: var(--primary-color);
            text-decoration: none;
            font-size: 14px;
            transition: background 0.2s, color 0.2s;
        }

        .pagination li.active span {
            background: var(--primary-color);
            color: #fff;
        }

        .pagination li a:hover {
            background: var(--primary-color);
            color: #fff;
        }
    </style>
</head>
<body>

    <div class="container">
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
        
        {{-- Form Pencarian --}}
        <div class="card">
            <div class="card-header bg-dark">Cari Review</div>
            <div class="card-body">
                <form action="{{ route('ratingdanreview.index') }}" method="GET" class="search-form">
                    <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan ID Perusahaan..." value="{{ request('search') }}">
                    <button type="submit" class="btn-primary">Cari</button>
                    <a href="{{ route('ratingdanreview.index') }}" class="btn btn-warning">Reset</a>
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
                                <a href="{{ route('ratingdanreview.edit', $item->id_review) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('ratingdanreview.destroy', $item->id_review) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Hapus review ini?')">Hapus</button>
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
    </div>

</body>
</html>