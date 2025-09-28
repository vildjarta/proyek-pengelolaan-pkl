<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Perusahaan</title>
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

        #map {
            width: 100%;
            height: 350px;
            border-radius: 12px;
            margin-top: 15px;
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
            <h1 class="mb-4 text-center">Detail Perusahaan</h1>

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card company-card">
                        <div class="card-body">
                            <h4 class="card-title">{{ $perusahaan->nama }}</h4>
                            <p class="card-text"><strong>Alamat:</strong> {{ $perusahaan->alamat }}</p>
                            <p class="card-text"><strong>Bidang Usaha:</strong> {{ $perusahaan->bidang_usaha }}</p>
                            <p class="card-text">
                                <strong>Status:</strong>
                                <span
                                    class="status-badge {{ $perusahaan->status == 'Aktif' ? 'bg-success text-white' : 'bg-secondary text-white' }}">
                                    {{ $perusahaan->status }}
                                </span>
                            </p>
                            <p class="card-text"><strong>Latitude:</strong> {{ $perusahaan->lat }}</p>
                            <p class="card-text"><strong>Longitude:</strong> {{ $perusahaan->lng }}</p>

                            {{-- Peta --}}
                            <div id="map"></div>
                        </div>
                        <div class="card-footer text-end">
                            <a href="{{ route('perusahaan.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBM6yhmdJP1BPXmzo852fIlEc4GlZtXtXU"></script>
    <script>
        function initMap() {
            var perusahaanLatLng = {
                lat: parseFloat("{{ $perusahaan->lat }}"),
                lng: parseFloat("{{ $perusahaan->lng }}")
            };

            var map = new google.maps.Map(document.getElementById('map'), {
                center: perusahaanLatLng,
                zoom: 15
            });

            new google.maps.Marker({
                map: map,
                position: perusahaanLatLng,
                title: "{{ $perusahaan->nama }}"
            });
        }

        // Panggil fungsi setelah window load
        window.onload = initMap;
    </script>

    <script src="{{ asset('assets/js/hhd.js') }}"></script>
</body>

</html>
