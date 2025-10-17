<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Perusahaan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        #map {
            width: 100%;
            height: 400px;
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
                <h1 class="mb-4">Edit Perusahaan</h1>

                {{-- Tampilkan error validasi --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('perusahaan.update', $perusahaan->id_perusahaan) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Perusahaan</label>
                        <input type="text" name="nama" id="nama" class="form-control"
                            value="{{ old('nama', $perusahaan->nama) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="3" required>{{ old('alamat', $perusahaan->alamat) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="bidang_usaha" class="form-label">Bidang Usaha</label>
                        <input type="text" name="bidang_usaha" id="bidang_usaha" class="form-control"
                            value="{{ old('bidang_usaha', $perusahaan->bidang_usaha) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="Aktif"
                                {{ old('status', $perusahaan->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Non-Aktif"
                                {{ old('status', $perusahaan->status) == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="fasilitas" class="form-label">Fasilitas (Bintang)</label>
                        <select name="fasilitas" id="fasilitas" class="form-control" required>
                            <option value="">-- Pilih Fasilitas --</option>
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}"
                                    {{ old('fasilitas', $perusahaan->fasilitas) == $i ? 'selected' : '' }}>
                                    Bintang {{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="level_legalitas" class="form-label">Level Legalitas</label>
                        <select name="level_legalitas" id="level_legalitas" class="form-control" required>
                            <option value="">-- Pilih Level Legalitas --</option>
                            <option value="1"
                                {{ old('level_legalitas', $perusahaan->level_legalitas) == 1 ? 'selected' : '' }}>CV /
                                Lokal</option>
                            <option value="2"
                                {{ old('level_legalitas', $perusahaan->level_legalitas) == 2 ? 'selected' : '' }}>
                                Tingkat Kabupaten</option>
                            <option value="3"
                                {{ old('level_legalitas', $perusahaan->level_legalitas) == 3 ? 'selected' : '' }}>
                                Tingkat Provinsi</option>
                            <option value="4"
                                {{ old('level_legalitas', $perusahaan->level_legalitas) == 4 ? 'selected' : '' }}>
                                Tingkat Nasional</option>
                            <option value="5"
                                {{ old('level_legalitas', $perusahaan->level_legalitas) == 5 ? 'selected' : '' }}>
                                Tingkat Internasional</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="hari_operasi" class="form-label">Total Hari Operasi</label>
                        <div class="input-group">
                            <input type="number" name="hari_operasi" id="hari_operasi" class="form-control"
                                value="{{ old('hari_operasi', $perusahaan->hari_operasi) }}" min="0"
                                required>
                            <span class="input-group-text">hari</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="jumlah_mahasiswa" class="form-label">Jumlah Mahasiswa</label>
                        <input type="number" name="jumlah_mahasiswa" id="jumlah_mahasiswa" class="form-control"
                            value="{{ old('jumlah_mahasiswa', $perusahaan->jumlah_mahasiswa) }}" min="0"
                            required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="lat" class="form-label">Latitude</label>
                            <input type="text" name="lat" id="lat" class="form-control"
                                value="{{ old('lat', $perusahaan->lat) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="lng" class="form-label">Longitude</label>
                            <input type="text" name="lng" id="lng" class="form-control"
                                value="{{ old('lng', $perusahaan->lng) }}">
                        </div>
                    </div>

                    <!-- Tombol buka peta -->
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                        data-bs-target="#mapModal">
                        Pilih Lokasi di Peta
                    </button>

                    <br>

                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="{{ route('perusahaan.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </main>
        </div>
    </div>

    <!-- Modal Peta -->
    <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Lokasi di Peta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap & Maps -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBM6yhmdJP1BPXmzo852fIlEc4GlZtXtXU"></script>

    <script>
        let map;
        let marker;

        function initMap() {
            const latInput = document.getElementById("lat");
            const lngInput = document.getElementById("lng");

            const lat = parseFloat(latInput.value) || -2.5489; // default Indonesia
            const lng = parseFloat(lngInput.value) || 118.0149;

            const location = {
                lat,
                lng
            };

            map = new google.maps.Map(document.getElementById("map"), {
                center: location,
                zoom: latInput.value && lngInput.value ? 13 : 5,
            });

            // Jika sudah ada koordinat, tampilkan marker awal
            if (latInput.value && lngInput.value) {
                marker = new google.maps.Marker({
                    position: location,
                    map: map,
                });
            }

            // Klik peta untuk ganti lokasi
            map.addListener("click", (event) => {
                const newLat = event.latLng.lat();
                const newLng = event.latLng.lng();

                // Hapus marker lama
                if (marker) marker.setMap(null);

                // Tambahkan marker baru
                marker = new google.maps.Marker({
                    position: {
                        lat: newLat,
                        lng: newLng
                    },
                    map: map,
                });

                // Isi input
                latInput.value = newLat;
                lngInput.value = newLng;
            });
        }

        // Inisialisasi peta saat modal dibuka
        const mapModal = document.getElementById('mapModal');
        mapModal.addEventListener('shown.bs.modal', function() {
            if (!map) {
                initMap();
            } else {
                google.maps.event.trigger(map, "resize");
                if (marker) map.setCenter(marker.getPosition());
            }
        });
    </script>
</body>

</html>
