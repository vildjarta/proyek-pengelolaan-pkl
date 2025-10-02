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
    <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0 fw-bold">
            <i class="fa fa-edit me-2"></i> Edit Penilaian Dosen Penguji
        </h4>
        <a href="{{ route('penilaian.index') }}" class="btn btn-light btn-sm text-warning fw-bold">
            <i class="fa fa-list me-1"></i> Daftar Penilaian
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('penilaian.update', $penilaian->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nilai</label>
                <input type="number" step="0.01" name="nilai" class="form-control" value="{{ $penilaian->nilai }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Komentar</label>
                <textarea name="komentar" class="form-control">{{ $penilaian->komentar }}</textarea>
            </div>

            <button type="submit" class="btn btn-warning fw-bold">
                <i class="fa fa-save me-1"></i> Update
            </button>
            <a href="{{ route('penilaian.index') }}" class="btn btn-secondary fw-bold">Kembali</a>
        </form>
    </div>
</div>
@endsection
