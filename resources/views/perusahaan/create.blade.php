<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Perusahaan</title>
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
                <h1 class="mb-4">Tambah Perusahaan</h1>

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

                <form action="{{ route('perusahaan.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Perusahaan</label>
                        <input type="text" name="nama" id="nama" class="form-control"
                            value="{{ old('nama') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Kontak (opsional)</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="3" required>{{ old('alamat') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="bidang_usaha" class="form-label">Bidang Usaha</label>
                        <input type="text" name="bidang_usaha" id="bidang_usaha" class="form-control"
                            value="{{ old('bidang_usaha') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="fasilitas" class="form-label">Fasilitas (Bintang)</label>
                        <select name="fasilitas" id="fasilitas" class="form-control" required>
                            <option value="">-- Pilih Fasilitas --</option>
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ old('fasilitas') == $i ? 'selected' : '' }}>
                                    Bintang {{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="level_legalitas" class="form-label">Level Legalitas</label>
                        <select name="level_legalitas" id="level_legalitas" class="form-control" required>
                            <option value="">-- Pilih Level Legalitas --</option>
                            <option value="1" {{ old('level_legalitas') == 1 ? 'selected' : '' }}>CV / Lokal
                            </option>
                            <option value="2" {{ old('level_legalitas') == 2 ? 'selected' : '' }}>Tingkat
                                Kabupaten</option>
                            <option value="3" {{ old('level_legalitas') == 3 ? 'selected' : '' }}>Tingkat Provinsi
                            </option>
                            <option value="4" {{ old('level_legalitas') == 4 ? 'selected' : '' }}>Tingkat Nasional
                            </option>
                            <option value="5" {{ old('level_legalitas') == 5 ? 'selected' : '' }}>Tingkat
                                Internasional</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="hari_operasi" class="form-label">Total Hari Operasi</label>
                        <div class="input-group">
                            <input type="number" name="hari_operasi" id="hari_operasi" class="form-control"
                                value="{{ old('hari_operasi') }}" min="0" required>
                            <span class="input-group-text">hari</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="jumlah_mahasiswa" class="form-label">Jumlah Mahasiswa</label>
                        <input type="number" name="jumlah_mahasiswa" id="jumlah_mahasiswa" class="form-control"
                            value="{{ old('jumlah_mahasiswa') }}" min="0" required>
                    </div>

                    <br>

                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="{{ route('perusahaan.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </main>
        </div>
    </div>

    <!-- Script Bootstrap + Google Maps -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBM6yhmdJP1BPXmzo852fIlEc4GlZtXtXU"></script>

    <script>
        let map;
        let marker;

        function initMap() {
            // Lokasi default (misalnya Indonesia)
            const defaultLocation = {
                lat: -2.5489,
                lng: 118.0149
            };

            map = new google.maps.Map(document.getElementById("map"), {
                center: defaultLocation,
                zoom: 5,
            });

            // Klik di peta untuk memilih lokasi
            map.addListener("click", (event) => {
                const lat = event.latLng.lat();
                const lng = event.latLng.lng();

                // Hapus marker sebelumnya
                if (marker) {
                    marker.setMap(null);
                }

                // Tambahkan marker baru
                marker = new google.maps.Marker({
                    position: {
                        lat,
                        lng
                    },
                    map: map,
                });

                // Isi ke input form
                document.getElementById("lat").value = lat;
                document.getElementById("lng").value = lng;
            });
        }

        // Inisialisasi peta saat modal dibuka
        const mapModal = document.getElementById('mapModal');
        mapModal.addEventListener('shown.bs.modal', function() {
            if (!map) {
                initMap();
            } else {
                google.maps.event.trigger(map, "resize");
            }
        });
    </script>
</body>

</html>
