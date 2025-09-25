@extends('home')

@section('content')
<div class="main-content-wrapper">
    <div class="content">
        <h2>Tambah Jadwal Bimbingan</h2>

        <form action="{{ route('jadwal.store') }}" method="POST" onsubmit="return confirmSubmit()">
            @csrf
            
            <div class="form-group">
                <label for="mahasiswa">Mahasiswa (Opsional)</label>
                <input type="text" name="mahasiswa" id="mahasiswa" class="form-control" placeholder="Ketik nama mahasiswa...">
            </div>

            <div class="form-group">
                <label for="dosen">Dosen (Opsional)</label>
                <input type="text" name="dosen" id="dosen" class="form-control" placeholder="Ketik nama dosen...">
            </div>

            <div class="form-group">
                <label for="tanggal">Tanggal Bimbingan</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="waktu_mulai">Waktu Mulai (Contoh: 10:00)</label>
                <input type="time" name="waktu_mulai" id="waktu_mulai" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="waktu_selesai">Waktu Selesai (Contoh: 11:00)</label>
                <input type="time" name="waktu_selesai" id="waktu_selesai" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="topik">Topik Bimbingan (Opsional)</label>
                <input type="text" name="topik" id="topik" class="form-control" placeholder="Contoh: Revisi Bab 1">
            </div>
            
            <div class="form-group">
                <label for="catatan">Aksi / Catatan (Opsional)</label>
                <input type="text" name="catatan" id="catatan" class="form-control" placeholder="Contoh: Bawa laptop dan hard copy">
            </div>

            <button type="submit" class="btn btn-success">Simpan Jadwal</button>
        </form>
    </div>
</div>

<script>
    function confirmSubmit() {
        return confirm("Apakah Anda yakin ingin menyimpan jadwal ini?");
    }
</script>
@endsection