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
                                {{ old('status', $perusahaan->status) == 'Aktif' ? 'selected' : '' }}>Aktif
                            </option>
                            <option value="Non-Aktif"
                                {{ old('status', $perusahaan->status) == 'Non-Aktif' ? 'selected' : '' }}>
                                Non-Aktif</option>
                        </select>
                    </div>

                    {{-- Dropdown Fasilitas 1-5 --}}
                    <div class="mb-3">
                        <label for="fasilitas" class="form-label">Fasilitas (Bintang)</label>
                        <select name="fasilitas" id="fasilitas" class="form-control" required>
                            <option value="">-- Pilih Fasilitas --</option>
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ old('fasilitas') == $i ? 'selected' : '' }}>
                                    Bintang
                                    {{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    {{-- Dropdown Level Legalitas (semakin besar semakin bagus) --}}
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
                                Internasional
                            </option>
                        </select>
                    </div>

                    {{-- Total Jam Operasi (integer) --}}
                    <div class="mb-3">
                        <label for="total_jam_operasi" class="form-label">Total Jam Operasi (jumlah total hari)</label>
                        <div class="input-group">
                            <input type="number" name="total_jam_operasi" id="total_jam_operasi" class="form-control"
                                value="{{ old('total_jam_operasi') }}" min="0" required>
                            <span class="input-group-text">hari</span>
                        </div>
                    </div>

                    {{-- Jumlah Mahasiswa (integer) --}}
                    <div class="mb-3">
                        <label for="jumlah_mahasiswa" class="form-label">Jumlah Mahasiswa</label>
                        <input type="number" name="jumlah_mahasiswa" id="jumlah_mahasiswa" class="form-control"
                            value="{{ old('jumlah_mahasiswa') }}" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label for="lat" class="form-label">Latitude</label>
                        <input type="text" name="lat" id="lat" class="form-control"
                            value="{{ old('lat', $perusahaan->lat) }}">
                    </div>

                    <div class="mb-3">
                        <label for="lng" class="form-label">Longitude</label>
                        <input type="text" name="lng" id="lng" class="form-control"
                            value="{{ old('lng', $perusahaan->lng) }}">
                    </div>


                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('perusahaan.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBM6yhmdJP1BPXmzo852fIlEc4GlZtXtXU"></script>


    <script src="{{ asset('assets/js/hhd.js') }}"></script>
</body>

</html>
