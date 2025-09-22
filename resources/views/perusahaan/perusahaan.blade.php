<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Perusahaan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="container py-4">

    <h1 class="mb-4">Daftar Perusahaan</h1>

    {{-- Tombol Tambah --}}
    <a href="{{ route('perusahaan.create') }}" class="btn btn-primary mb-3">+ Tambah Perusahaan</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID Perusahaan</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Bidang Usaha</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($perusahaans as $prs)
                <tr>
                    <td>{{ $prs->id_perusahaan }}</td>
                    <td>{{ $prs->nama }}</td>
                    <td>{{ $prs->alamat }}</td>
                    <td>{{ $prs->bidang_usaha }}</td>
                    <td>{{ $prs->status }}</td>
                    <td>
                        {{-- Tombol Edit --}}
                        <a href="{{ route('perusahaan.edit', $prs->id_perusahaan) }}"
                            class="btn btn-warning btn-sm">Edit</a>

                        {{-- Tombol Hapus --}}
                        <form action="{{ route('perusahaan.destroy', $prs->id_perusahaan) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin mau hapus data ini?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada data perusahaan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>
