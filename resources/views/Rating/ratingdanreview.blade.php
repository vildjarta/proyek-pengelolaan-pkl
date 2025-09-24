<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Tambah Rating & Review</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f1f2f6;
            font-family: Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 0;
        }
        .form-card {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
            width: 100%;
            border-radius: 0;
            min-height: 100vh;
            position: relative;
        }
        .form-card h2 {
            text-align: center;
            margin-bottom: 25px;
            font-weight: bold;
            color: #333;
        }
        .form-control:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76,175,80,0.5);
        }
        .btn-submit {
            width: 100%;
            padding: 10px;
            background: #4CAF50;
            color: white;
            font-weight: bold;
            border-radius: 8px;
            border: none;
            cursor: pointer;
        }
        .btn-submit:hover {
            background: #45a049;
        }
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            text-decoration: none;
            color: #333;
            font-size: 24px;
        }
        .back-button:hover {
            color: #45a049;
        }
    </style>
</head>
<body>
    <div class="form-card">
        <a href="javascript:history.back()" class="back-button">
            &larr;
        </a>
        
        <h2>Form Tambah Rating & Review</h2>
        <form action="/simpanrating" method="POST">
            <div class="mb-3">
                <label class="form-label">ID Mahasiswa</label>
                <input type="text" class="form-control" placeholder="Contoh: 123" name="id_mahasiswa">
            </div>

            <div class="mb-3">
                <label class="form-label">ID / Nama Perusahaan</label>
                <input type="text" class="form-control" placeholder="Contoh: 456" name="id_perusahaan">
            </div>

            <div class="mb-3">
                <label class="form-label">Rating</label>
                <select class="form-control" name="rating">
                    <option value="1">⭐</option>
                    <option value="2">⭐⭐</option>
                    <option value="3">⭐⭐⭐</option>
                    <option value="4">⭐⭐⭐⭐</option>
                    <option value="5">⭐⭐⭐⭐⭐</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Review</label>
                <textarea class="form-control" rows="3" name="review"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal Review</label>
                <input type="date" class="form-control" name="tanggal_review">
            </div>

            <button type="submit" class="btn-submit">Simpan</button>
        </form>
    </div>
</body>
</html>