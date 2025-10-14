@include('layout.header')
@include('layout.sidebar')
<link rel="stylesheet" href="{{ asset('assets/css/style-edit-jadwal.css') }}">
<div class="main-content-wrapper">
    <div class="content">
        <div class="form-card">
            <h2>Edit Jadwal Bimbingan</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('jadwal.update', $jadwal->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="id_mahasiswa">Mahasiswa</label>
                    <select name="id_mahasiswa" id="id_mahasiswa" class="form-control" required>
                        <option value="">-- Pilih Mahasiswa --</option>
                        @foreach($mahasiswas as $mahasiswa)
                            <option value="{{ $mahasiswa->id_mahasiswa }}" {{ $jadwal->id_mahasiswa == $mahasiswa->id_mahasiswa ? 'selected' : '' }}>
                                {{ $mahasiswa->nim }} - {{ $mahasiswa->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="id_pembimbing">Dosen Pembimbing</label>
                    <select name="id_pembimbing" id="id_pembimbing" class="form-control" required>
                        <option value="">-- Pilih Dosen --</option>
                        @foreach($dosens as $dosen)
                            <option value="{{ $dosen->id_pembimbing }}" {{ $jadwal->id_pembimbing == $dosen->id_pembimbing ? 'selected' : '' }}>
                                {{ $dosen->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="tanggal">Tanggal Bimbingan</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" required value="{{ old('tanggal', $jadwal->tanggal) }}">
                </div>

                <div class="form-group">
                    <label for="waktu_mulai">Waktu Mulai</label>
                    <input type="time" name="waktu_mulai" id="waktu_mulai" class="form-control" required value="{{ old('waktu_mulai', $jadwal->waktu_mulai) }}">
                </div>
                
                <div class="form-group">
                    <label for="waktu_selesai">Waktu Selesai</label>
                    <input type="time" name="waktu_selesai" id="waktu_selesai" class="form-control" required value="{{ old('waktu_selesai', $jadwal->waktu_selesai) }}">
                </div>

                <div class="form-group">
                    <label for="topik">Topik Bimbingan (Opsional)</label>
                    <input type="text" name="topik" id="topik" class="form-control" placeholder="Contoh: Revisi Bab 1" value="{{ old('topik', $jadwal->topik) }}">
                </div>
                
                <div class="form-group">
                    <label for="catatan">Catatan (Opsional)</label>
                    <input type="text" name="catatan" id="catatan" class="form-control" placeholder="Contoh: Bawa laptop dan hard copy" value="{{ old('catatan', $jadwal->catatan) }}">
                </div>

                <div class="form-actions">
                    <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-success">Update Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>
