<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Dosen Penguji - PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style-header-sidebar.css') }}">
    <style>
    .main-content-wrapper { padding: 20px; }    
    /* TAMBAHAN BARU: Warna Header Tabel */
    .table thead.bg-blue-custom th {
        background-color: #261FB3 !important;
        color: white;
        border-color: #261FB3; /* Opsional: agar border menyatu */
    }
        </style>
</head>
<body>

    @include('layout.header')
    @include('layout.sidebar')

    <div class="main-content-wrapper" id="mainContent">
        <div class="content container-fluid">   
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Daftar Dosen Penguji</h2>
                
                {{-- LOGIKA 1: TOMBOL TAMBAH --}}
                {{-- HANYA KOORDINATOR yang bisa melihat tombol ini --}}
                {{-- Dosen Penguji, Staff, Mahasiswa TIDAK BISA --}}
                @if(Auth::user()->role == 'koordinator')
                    <a href="{{ route('dosen_penguji.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Tambah Penguji
                    </a>
                @endif
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    {{-- GANTI DARI table-dark KE bg-blue-custom --}}
                    <thead class="bg-blue-custom">
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th>Nama Dosen</th>
                            <th>NIP</th>
                            <th>Mahasiswa yang Diuji</th>
                            <th class="text-center" width="15%">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($dosenPenguji as $row)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            
                            <td>
                                <strong>{{ $row->nama_dosen }}</strong><br>
                                <small class="text-muted">{{ $row->email }}</small>
                            </td>
                            <td>{{ $row->nip }}</td>
                            <td>{{ $row->nama_mahasiswa }}</td>

                            <td class="text-center">
                                @php
                                    $user = Auth::user();
                                    $canEdit = false;
                                    $canDelete = false;

                                    // LOGIKA IZIN AKSES
                                    if ($user->role == 'koordinator') {
                                        // Koordinator: BISA Edit & Hapus
                                        $canEdit = true;
                                        $canDelete = true;
                                    } 
                                    elseif (in_array($user->role, ['dosen', 'dosen_penguji'])) {
                                        // Dosen Penguji: BISA Edit (Punya Sendiri), TAPI TIDAK BISA Hapus
                                        if ($row->id_user == $user->id) {
                                            $canEdit = true;
                                            $canDelete = false; // <--- Dosen dilarang hapus
                                        }
                                    }
                                    // Staff & Mahasiswa: Tetap False semua
                                @endphp

                                <div class="d-flex justify-content-center gap-1">
                                    @if($canEdit)
                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('dosen_penguji.edit', $row->id_penguji) }}" class="btn btn-sm btn-warning text-white" title="Edit">
                                            <i class="fa fa-pen"></i>
                                        </a>
                                    @endif

                                    @if($canDelete)
                                        {{-- Tombol Hapus (Hanya muncul untuk Koordinator) --}}
                                        <form action="{{ route('dosen_penguji.destroy', $row->id_penguji) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if(!$canEdit && !$canDelete)
                                        {{-- Tampilan Read Only (Staff, Mahasiswa, atau Dosen melihat data orang lain) --}}
                                        <span class="badge bg-secondary">
                                            <i class="fa fa-lock"></i> Read Only
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                Belum ada data dosen penguji.
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