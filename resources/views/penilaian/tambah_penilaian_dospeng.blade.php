@extends('layout.app')

@section('content')
<div class="d-flex">
    {{-- header --}}
    @include('layout.header')
</div>

<div class="d-flex">
    {{-- sidebar --}}
    @include('layout.sidebar')
</div>

<div class="card shadow border-0 rounded-3 mt-3">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0 fw-bold"><i class="fa fa-plus me-2"></i> Tambah Penilaian Dosen Penguji</h4>
        <a href="{{ route('penilaian.index') }}" class="btn btn-light btn-sm text-success fw-bold">
            <i class="fa fa-list me-1"></i> Daftar Penilaian
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('penilaian.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">NIP</label>
                <input type="text" name="nip" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Dosen</label>
                <input type="text" name="nama_dosen" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Mahasiswa</label>
                <input type="text" name="nama_mahasiswa" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Judul</label>
                <textarea name="judul" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Sikap</label>
                <textarea name="sikap" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Penguasaan</label>
                <textarea name="penguasaan" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Nilai</label>
                <input type="number" step="0.01" name="nilai" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal Ujian</label>
                <input type="date" name="tanggal_ujian" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Jenis Ujian</label>
                <select name="jenis_ujian" class="form-control">
                    <option value="Seminar Proposal">Seminar Proposal</option>
                    <option value="Seminar Hasil">Seminar Hasil</option>
                    <option value="Sidang Akhir">Sidang Akhir</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Komentar</label>
                <textarea name="komentar" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-success fw-bold"><i class="fa fa-save me-1"></i> Simpan</button>
            <a href="{{ route('penilaian.index') }}" class="btn btn-secondary fw-bold">Kembali</a>
        </form>
    </div>
</div>
@endsection
