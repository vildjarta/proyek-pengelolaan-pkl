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

        .map-container {
            height: 300px;
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
                        <h2 class="h4 mb-1">Daftar Perusahaan PKL</h2>
                        <small class="text-muted">Kelola perusahaan yang tersedia untuk penempatan PKL</small>
                    </div>
                    <a href="{{ route('perusahaan.create') }}" class="btn btn-primary">+ Tambah Perusahaan</a>
                </div>
                <div class="row g-4">
                    @forelse($perusahaans as $prs)
                        <div class="col-md-4 col-sm-6">
                            <div class="card company-card h-100">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $prs->nama }}</h5>
                                    <p class="card-text mb-1"><strong>Alamat:</strong> {{ $prs->alamat }}</p>
                                    <p class="card-text">
                                        <strong>Status:</strong>
                                        <span
                                            class="status-badge {{ $prs->status == 'Aktif' ? 'bg-success text-white' : 'bg-secondary text-white' }}">
                                            {{ $prs->status }}
                                        </span>
                                    </p>

                                    <div class="mt-auto">
                                        <a href="{{ route('perusahaan.edit', $prs->id_perusahaan) }}"
                                            class="btn btn-warning btn-sm">Edit</a>

                                        <form action="{{ route('perusahaan.destroy', $prs->id_perusahaan) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin mau hapus data ini?')">
                                                Hapus
                                            </button>
                                        </form>

                                        <!-- Tombol Detail Modal -->
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#detailModal{{ $prs->id_perusahaan }}">
                                            Detail
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Detail Perusahaan -->
                        <div class="modal fade" id="detailModal{{ $prs->id_perusahaan }}" tabindex="-1"
                            aria-labelledby="detailModalLabel{{ $prs->id_perusahaan }}" aria-hidden="true">
                            <div class="modal-dialog modal-md">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                                <h4 class="card-title">{{ $prs->nama }}</h4>
                                                <p class="card-text"><strong>Alamat:</strong> {{ $prs->alamat }}</p>
                                                <p class="card-text"><strong>Bidang Usaha:</strong> {{ $prs->bidang_usaha }}</p>
                                                <p class="card-text">
                                                    <strong>Status:</strong>
                                                    <span class="status-badge {{ $prs->status == 'Aktif' ? 'bg-success text-white' : 'bg-secondary text-white' }}">
                                                        {{ $prs->status }}
                                                    </span>
                                                </p>
                                                <p class="card-text"><strong>Latitude:</strong> {{ $prs->lat }}</p>
                                                <p class="card-text"><strong>Longitude:</strong> {{ $prs->lng }}</p>

                                                <div id="map{{ $prs->id_perusahaan }}" class="map-container"></div>
                                            
                                            <div class="card-footer text-end">
                                                <button type="button" class="btn btn-secondary btn-sm"
                                                    data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        
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
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBM6yhmdJP1BPXmzo852fIlEc4GlZtXtXU"></script>
    <script>
        @foreach($perusahaans as $prs)
            var modal{{ $prs->id_perusahaan }} = document.getElementById('detailModal{{ $prs->id_perusahaan }}');
            modal{{ $prs->id_perusahaan }}.addEventListener('shown.bs.modal', function () {
                var lokasi = { lat: parseFloat('{{ $prs->lat }}'), lng: parseFloat('{{ $prs->lng }}') };
                var map = new google.maps.Map(document.getElementById('map{{ $prs->id_perusahaan }}'), {
                    center: lokasi,
                    zoom: 15
                });
                new google.maps.Marker({
                    position: lokasi,
                    map: map,
                    title: '{{ $prs->nama }}'
                });
            });
        @endforeach
    </script>

    <script src="{{ asset('assets/js/hhd.js') }}"></script>
</body>

</html>
