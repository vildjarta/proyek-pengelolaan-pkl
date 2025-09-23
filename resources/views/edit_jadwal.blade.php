@extends('home')

@section('content')
    <div class="main-content-wrapper">
        <div class="content">
            <h2>Edit Jadwal Bimbingan</h2>
            <form action="{{ route('jadwal.update', $jadwal->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ $jadwal->tanggal }}">
                </div>
                <div class="form-group">
                    <label for="waktu">Waktu</label>
                    <input type="time" name="waktu" id="waktu" class="form-control" value="{{ $jadwal->waktu }}">
                </div>
                <div class="form-group">
                    <label for="topik">Topik</label>
                    <input type="text" name="topik" id="topik" class="form-control" value="{{ $jadwal->topik }}">
                </div>
                <button type="submit" class="btn btn-warning">Update</button>
            </form>
        </div>
    </div>
@endsection