@extends('home')

@section('content')
<div class="main-content-wrapper">
    <div class="content">
        <h2>Edit Jadwal Bimbingan</h2>

        {{-- Form ini akan mengirim data ke metode 'update' di JadwalBimbinganController --}}
        <form action="{{ route('jadwal.update', $jadwal->id) }}" method="POST">
            @csrf
            @method('PUT') {{-- Metode PUT atau PATCH wajib untuk proses update --}}
            
            <div class="form-group">
                <label for="mahasiswa">Mahasiswa (Opsional)</label>
                {{-- Mengisi input dengan data yang sudah ada --}}
                <input type="text" name="mahasiswa" id="mahasiswa" class="form-control" placeholder="Ketik nama mahasiswa..." value="{{ old('mahasiswa', $jadwal->mahasiswa) }}">
            </div>

            <div class="form-group">
                <label for="dosen">Dosen (Opsional)</label>
                {{-- Mengisi input dengan data yang sudah ada --}}
                <input type="text" name="dosen" id="dosen" class="form-control" placeholder="Ketik nama dosen..." value="{{ old('dosen', $jadwal->dosen) }}">
            </div>

            <div class="form-group">
                <label for="tanggal">Tanggal Bimbingan</label>
                {{-- Mengisi input dengan data yang sudah ada --}}
                <input type="date" name="tanggal" id="tanggal" class="form-control" required value="{{ old('tanggal', $jadwal->tanggal) }}">
            </div>

            <div class="form-group">
                <label for="waktu_mulai">Waktu Mulai</label>
                {{-- Mengisi input dengan data yang sudah ada --}}
                <input type="time" name="waktu_mulai" id="waktu_mulai" class="form-control" required value="{{ old('waktu_mulai', $jadwal->waktu_mulai) }}">
            </div>
            
            <div class="form-group">
                <label for="waktu_selesai">Waktu Selesai</label>
                {{-- Mengisi input dengan data yang sudah ada --}}
                <input type="time" name="waktu_selesai" id="waktu_selesai" class="form-control" required value="{{ old('waktu_selesai', $jadwal->waktu_selesai) }}">
            </div>

            <div class="form-group">
                <label for="topik">Topik Bimbingan (Opsional)</label>
                {{-- Mengisi input dengan data yang sudah ada --}}
                <input type="text" name="topik" id="topik" class="form-control" placeholder="Contoh: Revisi Bab 1" value="{{ old('topik', $jadwal->topik) }}">
            </div>
            
            <div class="form-group">
                <label for="catatan">Aksi / Catatan (Opsional)</label>
                {{-- Mengisi input dengan data yang sudah ada --}}
                <input type="text" name="catatan" id="catatan" class="form-control" placeholder="Contoh: Bawa laptop dan hard copy" value="{{ old('catatan', $jadwal->catatan) }}">
            </div>

            <button type="submit" class="btn btn-success">Update Jadwal</button>
            <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection