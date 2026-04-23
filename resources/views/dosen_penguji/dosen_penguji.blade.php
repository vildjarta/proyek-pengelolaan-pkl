<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Dosen Penguji - PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- tetap pakai css lama --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style-header-sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/datadosenpembimbing.css') }}">

    <style>
        .table td,
        .table th {
            vertical-align: middle;
            white-space: normal;
            word-break: break-word;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 5px;
        }
    </style>
</head>

<body>

    @include('layout.header')
    @include('layout.sidebar')

    <div class="main-content-wrapper" id="mainContent">
        <div class="content container-fluid">

            <div class="table-header">
                <h2 class="title">Daftar Dosen Penguji</h2>

                <div class="d-flex align-items-center gap-2">

                    {{-- SEARCH --}}
                    <form action="{{ route('dosen_penguji.index') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="search-input"
                            placeholder="Cari dosen / mahasiswa..." value="{{ request('search') }}">

                        <button class="btn btn-primary ms-2">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>

                    {{-- TAMBAH --}}
                    @if (Auth::user()->role == 'koordinator')
                        <a href="{{ route('dosen_penguji.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Tambah
                        </a>
                    @endif
                </div>
            </div>

            {{-- alert --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif


            <div class="table-responsive">
                <table class="table table-striped">

                    <thead>
                        <tr>
                            <th style="width:7%">No</th>
                            <th style="width:13%">Nama Dosen</th>
                            <th style="width:15%">NIP</th>
                            <th style="width:20%">Email</th>
                            <th style="width:13%">No HP</th>
                            <th style="width:20%">Mahasiswa</th>

                            @if (in_array(Auth::user()->role, ['koordinator', 'dosen_penguji']))
                                <th style="width:12%">Aksi</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($dosenPenguji as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->nama_dosen }}</td>
                                <td>{{ $row->nip }}</td>
                                <td>{{ $row->email }}</td>
                                <td>{{ $row->no_hp ?? '-' }}</td>

                                <td>
                                    {{ $row->nama_mahasiswa }}
                                    @if ($row->nim_mahasiswa)
                                        ({{ $row->nim_mahasiswa }})
                                    @endif
                                </td>

                                @if (in_array(Auth::user()->role, ['koordinator', 'dosen_penguji']))
                                    <td>
                                        <div class="action-buttons">

                                            @php
                                                $user = Auth::user();
                                                $isKoordinator = $user->role == 'koordinator';
                                                $isOwnData =
                                                    in_array($user->role, ['dosen_penguji', 'dosen']) &&
                                                    $row->id_user == $user->id;
                                            @endphp

                                            {{-- edit --}}
                                            @if ($isKoordinator || $isOwnData)
                                                <a href="{{ route('dosen_penguji.edit', $row->id_penguji) }}"
                                                    class="btn btn-warning text-white">
                                                    <i class="fa fa-pen"></i>
                                                </a>
                                            @endif

                                            {{-- delete --}}
                                            @if ($isKoordinator)
                                                <form action="{{ route('dosen_penguji.destroy', $row->id_penguji) }}"
                                                    method="POST" onsubmit="return confirm('Yakin hapus data?')">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button class="btn btn-danger">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif

                                        </div>
                                    </td>
                                @endif
                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    Belum ada data dosen penguji
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>