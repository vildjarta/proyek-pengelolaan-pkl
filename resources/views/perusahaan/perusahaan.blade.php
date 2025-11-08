<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Perusahaan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        .company-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .company-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            color: #1e293b;
            font-weight: 600;
        }

        .status-badge {
            font-size: 0.8rem;
            padding: 5px 12px;
            border-radius: 20px;
        }

        .map-container {
            height: 250px;
            border-radius: 8px;
            overflow: hidden;
        }

        .btn-action {
            border-radius: 8px;
            padding: 6px 12px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.875rem;
        }

        .btn-add {
            border-radius: 8px;
            padding: 8px 16px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
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
            <main class="container py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="h4 mb-1 fw-bold text-dark">Daftar Perusahaan PKL</h2>
                        <small class="text-muted">Kelola perusahaan yang tersedia untuk penempatan PKL</small>
                    </div>
                    <a href="{{ route('perusahaan.create') }}" class="btn btn-primary btn-add">
                        <i class="fas fa-plus"></i> Tambah Perusahaan
                    </a>
                </div>

                <div class="row g-4">
                    @forelse($perusahaans as $prs)
                        <div class="col-md-4 col-sm-6">
                            <div class="card company-card h-100">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title">{{ $prs->nama }}</h5>
                                        <span
                                            class="status-badge {{ $prs->status == 'Aktif' ? 'bg-success' : 'bg-secondary' }} text-white">
                                            {{ $prs->status }}
                                        </span>
                                    </div>

                                    <p class="card-text text-muted small mb-3">
                                        <i class="fas fa-map-marker-alt me-1"></i> {{ Str::limit($prs->alamat, 60) }}
                                    </p>

                                    <div class="mt-auto d-flex gap-2 flex-wrap">
                                        <button type="button" class="btn btn-primary btn-sm btn-action"
                                            data-bs-toggle="modal"
                                            data-bs-target="#detailModal{{ $prs->id_perusahaan }}">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>

                                        <a href="{{ route('perusahaan.edit', $prs->id_perusahaan) }}"
                                            class="btn btn-warning btn-sm btn-action text-white">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>

                                        <form action="{{ route('perusahaan.destroy', $prs->id_perusahaan) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm btn-action"
                                                onclick="return confirm('Yakin mau hapus data ini?')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Detail Perusahaan -->
                        <div class="modal fade" id="detailModal{{ $prs->id_perusahaan }}" tabindex="-1"
                            aria-labelledby="detailModalLabel{{ $prs->id_perusahaan }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="detailModalLabel{{ $prs->id_perusahaan }}">
                                            <i class="fas fa-building me-2"></i>Detail Perusahaan
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h4 class="text-primary mb-3">{{ $prs->nama }}</h4>
                                                <div class="mb-3">
                                                    <strong><i class="fas fa-map-marker-alt me-2"></i>Alamat:</strong>
                                                    <p class="mb-0 mt-1">{{ $prs->alamat }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <strong><i class="fas fa-industry me-2"></i>Bidang Usaha:</strong>
                                                    <p class="mb-0 mt-1">{{ $prs->bidang_usaha }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <strong><i class="fas fa-tag me-2"></i>Status:</strong>
                                                    <span
                                                        class="status-badge {{ $prs->status == 'Aktif' ? 'bg-success' : 'bg-secondary' }} text-white ms-2">
                                                        {{ $prs->status }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <strong><i
                                                            class="fas fa-concierge-bell me-2"></i>Fasilitas:</strong>
                                                    <p class="mb-0 mt-1">{{ $prs->fasilitas }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <strong><i class="fas fa-scale-balanced me-2"></i>Level
                                                        Legalitas:</strong>
                                                    <p class="mb-0 mt-1">{{ $prs->level_legalitas }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <strong><i class="fas fa-users me-2"></i>Jumlah Mahasiswa:</strong>
                                                    <p class="mb-0 mt-1">{{ $prs->jumlah_mahasiswa }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <strong><i class="fas fa-clock me-2"></i>Hari Operasi:</strong>
                                                    <p class="mb-0 mt-1">{{ $prs->hari_operasi }}</p>
                                                </div>
                                            </div>
                                        </div><!--style="background-color: #0d6efd; color: white;"-->

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-1"></i> Tutup
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info text-center py-4">
                                <i class="fas fa-info-circle fa-2x mb-3"></i>
                                <h5>Belum ada data perusahaan</h5>
                                <p class="mb-0">Mulai dengan menambahkan perusahaan pertama Anda</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('assets/js/hhd.js') }}"></script>
</body>

</html>
