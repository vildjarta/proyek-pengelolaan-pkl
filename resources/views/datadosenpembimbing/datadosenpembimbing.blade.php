<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Dosen Pembimbing - PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font Awesome & Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- CSS Layout Global --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style-header-sidebar.css') }}">
    
    {{-- CSS Halaman Ini --}}
    <link rel="stylesheet" href="{{ asset('assets/css/datadosenpembimbing.css') }}">
</head>
<body>
    
    {{-- HEADER --}}
    @include('layout.header')

    {{-- SIDEBAR --}}
    @include('layout.sidebar')

    {{-- MAIN CONTENT --}}
    <div class="main-content-wrapper">
  <div class="content container-fluid">
        <div class="content">
            <div class="table-header">
                <h2 class="title">Daftar Dosen Pembimbing</h2>

                <div class="d-flex align-items-center gap-2">
                    <!-- Form Pencarian -->
                    <form action="{{ route('datadosenpembimbing.index') }}" method="GET" class="d-flex search-container">
                        <input type="text" name="search" value="{{ request('search') }}" class="search-input" placeholder="Cari dosen...">
                        <button type="submit" class="btn btn-primary ms-2"><i class="fa fa-search"></i></button>
                    </form>

                    <!-- Tombol Tambah -->
                    <a href="{{ route('datadosenpembimbing.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Tambah
                    </a>
                </div>
            </div>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Mahasiswa Bimbingan</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $row)
                        <tr>
                            <td>{{ $row->NIP }}</td>
                            <td>{{ $row->nama }}</td>
                            <td>{{ $row->email }}</td>
                            <td class="text-start">
                                @if($row->mahasiswa->count() > 0)
                                    @foreach($row->mahasiswa as $mhs)
                                        <div>{{ $loop->iteration }}. {{ $mhs->nama }} - {{ $mhs->nim }}</div>
                                    @endforeach
                                @else
                                    <span class="text-muted">Belum memiliki mahasiswa</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('datadosenpembimbing.edit', $row->id_pembimbing) }}" class="btn btn-edit-custom" title="Edit">
                                        <i class="fa fa-pen"></i>
                                    </a>
                                    <form action="{{ route('datadosenpembimbing.destroy', $row->id_pembimbing) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin hapus data ini?')" title="Hapus">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                @if(request('search'))
                                    Tidak ditemukan dosen dengan nama "<strong>{{ request('search') }}</strong>"
                                @else
                                    Belum ada data dosen pembimbing.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function(){
        const toggleButton = document.querySelector('.menu-toggle');
        const body = document.body;
        toggleButton?.addEventListener('click', () => {
            body.classList.toggle('sidebar-closed');
        });
    });
    </script>
</body>
</html>
