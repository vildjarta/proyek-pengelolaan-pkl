@extends('home')

@section('content')
    <div class="main-content-wrapper">
        <div class="content">
            <h2>Tambah Jadwal Bimbingan</h2>
            <form action="{{ route('jadwal.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="mahasiswa_id">Mahasiswa</label>
                    <select name="mahasiswa_id" id="mahasiswa_id" class="form-control">
                        @foreach($mahasiswas as $mahasiswa)
                            <option value="{{ $mahasiswa->id }}">{{ $mahasiswa->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="dosen_id">Dosen</label>
                    <select name="dosen_id" id="dosen_id" class="form-control">
                        @foreach($dosens as $dosen)
                            <option value="{{ $dosen->id }}">{{ $dosen->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control">
                </div>
                <div class="form-group">
                    <label for="waktu">Waktu</label>
                    <input type="time" name="waktu" id="waktu" class="form-control">
                </div>
                <div class="form-group">
                    <label for="topik">Topik</label>
                    <input type="text" name="topik" id="topik" class="form-control">
                </div>
                <button type="submit" class="btn btn-success">Simpan</button>
            </form>
        </div>
    </div>
@endsection
