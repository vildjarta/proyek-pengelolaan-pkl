<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Penilaian Dosen Penguji - Sistem PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style-pkl.css">
    <link rel="stylesheet" href="/assets/css/nilai.css">
</head>
<body>

<div class="d-flex">
    @include('layout.header')
</div>

<div class="d-flex">
    @include('layout.sidebar')
</div>

<div class="main-content-wrapper">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Daftar Penilaian Dosen Penguji</h2>
        <a href="{{ route('penilaian-penguji.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Penilaian
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="result-card">
        @if(count($penilaian) > 0)
            <div class="table-wrapper">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIP</th>
                            <th>Nama Dosen</th>
                            <th>Nama Mahasiswa</th>
                            <th>Total Nilai</th>
                            <th>Nilai Akhir (20%)</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penilaian as $index => $p)
                        <tr>
                            <td data-label="No">{{ $index + 1 }}</td>
                            <td data-label="NIP">{{ $p->dosen->nip ?? '-' }}</td>
                            <td data-label="Nama Dosen">{{ $p->dosen->nama ?? '-' }}</td>
                            <td data-label="Nama Mahasiswa">{{ $p->nama_mahasiswa }}</td>
                            <td data-label="Total Nilai">{{ $p->total_nilai }}</td>
                            <td data-label="Nilai Akhir">{{ $p->nilai_akhir }}</td>
                            <td data-label="Tanggal">{{ $p->tanggal_ujian }}</td>
                            <td data-label="Aksi">
                                <a href="{{ route('penilaian-penguji.edit', $p->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('penilaian-penguji.destroy', $p->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus penilaian ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Belum ada data penilaian.</p>
                <a href="{{ route('penilaian-penguji.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Penilaian Pertama
                </a>
            </div>
        @endif
    </div>
</div>
</body>
</html>
