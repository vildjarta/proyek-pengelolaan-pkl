<?php
namespace App\Http\Controllers;

use App\Models\Penilaian_perusahaan;
use App\Models\Kriteria;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kriteria</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f4f8ff;
        }

        .main-content-wrapper {
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0, 60, 130, 0.1);
            margin: 2rem;
            padding: 2rem;
        }

        h1 {
            color: #0d6efd;
            font-weight: 600;
        }

        table {
            border-radius: 10px;
            overflow: hidden;
        }

        thead {
            background-color: #0d6efd !important;
            color: white;
        }

        tbody tr:hover {
            background-color: #e9f2ff;
            transition: 0.2s;
        }

        .btn {
            border-radius: 8px;
        }

        .btn i {
            margin-right: 4px;
        }

        .table-wrapper {
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        #kriteriaTable td {
            font-size: 0.95rem;
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        @include('layout.header')
    </div>

    <div class="d-flex">
        @include('layout.sidebar')
    </div>

    <div class="main-content-wrapper">
        <div class="content">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Daftar Penilaian Perusahaan</h3>
                <a href="{{ route('penilaian_perusahaan.create') }}" class="btn btn-primary">Tambah Penilaian</a>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-striped mb-4">
                <thead class="table table-bordered align-middle text-center table-sm">
                    <tr>
                        <th>No</th>
                        <th>Nama Perusahaan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penilaians as $index => $p)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $p->nama }}</td>
                            <td class="text-center">
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#detailModal{{ $p->id_perusahaan }}">
                                    <i class="bi bi-eye"></i> Nilai
                                </button>
                                {{-- <a href="{{ route('penilaian_perusahaan.edit', $p->id_penilaian_perusahaan) }}"
                                    class="btn btn-warning btn-sm">Edit</a> --}}

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Hasil SAW (Simple Additive Weighting) --}}
            @if (isset($hasilSaw) && count($hasilSaw) > 0)
                <div class="mt-5">
                    <h4>Hasil SAW</h4>
                    <table class="table table-bordered table-striped">
                        <thead class="table table-bordered align-middle text-center table-sm">
                            <tr>
                                <th>Peringkat</th>
                                <th>Nama Perusahaan</th>
                                <th>Nilai Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $peringkat = 1; @endphp
                            @foreach ($hasilSaw as $hasil)
                                <tr>
                                    <td>{{ $peringkat++ }}</td>
                                    <td>{{ $hasil['nama_perusahaan'] }}</td>
                                    <td>{{ $hasil['nilai_akhir'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @elseif(isset($hasilSaw))
                <div class="alert alert-info mt-4">
                    Hasil SAW belum tersedia.
                </div>
            @endif

        </div>
    </div>
</body>
@foreach ($penilaians as $p)
    @php
        $detail = \App\Models\Penilaian_perusahaan::leftJoin(
            'kriteria',
            'kriteria.id_kriteria',
            '=',
            'penilaian_perusahaan.id_kriteria',
        )
            ->select('penilaian_perusahaan.nilai', 'kriteria.kriteria')
            ->where('penilaian_perusahaan.id_perusahaan', $p->id_perusahaan)
            ->get();
    @endphp
    <div class="modal fade" id="detailModal{{ $p->id_perusahaan }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-building"></i> Detail Perusahaan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <h5 class="fw-bold">{{ $p->nama }}</h5>

                    <div class="mt-3 p-3 rounded" style="background:#f7faff;">
                        @foreach ($detail as $d)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>{{ $d->kriteria }}</span>
                                <span class="badge bg-secondary">{{ $d->nilai }}</span>
                            </div>
                        @endforeach
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>

            </div>
        </div>
    </div>
@endforeach
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</html>
