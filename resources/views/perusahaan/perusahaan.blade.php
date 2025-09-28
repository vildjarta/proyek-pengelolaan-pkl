<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Perusahaan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .company-card {
            transition: 0.3s;
        }

        .company-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .status-badge {
            font-size: 0.85rem;
            padding: 5px 10px;
            border-radius: 12px;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        {{-- header --}}
        @include('layout.header')
    </div>

    <div class="d-flex">
        {{-- sidebar --}}
        @include('layout.sidebar')
    </div>

    <div class="main-content-wrapper">
        <div class="content">
            <h1 class="mb-4 text-center">Daftar Perusahaan untuk PKL</h1>

            {{-- Tombol Tambah --}}
            <div class="text-end mb-3">
                <a href="{{ route('perusahaan.create') }}" class="btn btn-primary">+ Tambah Perusahaan</a>
            </div>

            <div class="row g-4">
                @forelse($perusahaans as $prs)
                    <div class="col-md-4 col-sm-6">
                        <div class="card company-card h-100">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $prs->nama }}</h5>
                                <p class="card-text mb-1"><strong>Alamat:</strong> {{ $prs->alamat }}</p>
                                <p class="card-text mb-1"><strong>Bidang Usaha:</strong> {{ $prs->bidang_usaha }}</p>
                                <p class="card-text">
                                    <strong>Status:</strong>
                                    <span
                                        class="status-badge 
                                {{ $prs->status == 'Aktif' ? 'bg-success text-white' : 'bg-secondary text-white' }}">
                                        {{ $prs->status }}
                                    </span>
                                </p>

                                <div class="mt-auto">
                                    <a href="{{ route('perusahaan.edit', $prs->id_perusahaan) }}"
                                        class="btn btn-warning btn-sm">Edit</a>

                                    <form action="{{ route('perusahaan.destroy', $prs->id_perusahaan) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin mau hapus data ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                    <a href="{{ route('perusahaan.show', $prs->id_perusahaan) }}"
                                        class="btn btn-warning btn-sm">Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">Belum ada data perusahaan</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/hhd.js') }}"></script>
</body>

</html>
