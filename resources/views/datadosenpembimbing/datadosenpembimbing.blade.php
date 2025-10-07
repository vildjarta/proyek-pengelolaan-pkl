<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Dosen Pembimbing - Sistem PKL JOZZ</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap & FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- CSS Header & Sidebar --}}
  <link rel="stylesheet" href="{{ asset('assets/css/style-header-sidebar.css') }}">

  {{-- CSS Halaman Ini --}}
  <link rel="stylesheet" href="{{ asset('assets/css/datadosenpembimbing.css') }}">
</head>
<body>

{{-- HEADER --}}
@include('layouts.header')

{{-- SIDEBAR --}}
@include('layouts.sidebar')

{{-- MAIN CONTENT --}}
<div class="container-page">
  <div class="container-fluid">
    <div class="card-main">
      <div class="card-header-custom">
        <h4>Daftar Data Dosen Pembimbing</h4>
        <a href="{{ route('datadosenpembimbing.create') }}" class="btn-add">
          <i class="fa fa-plus"></i> Tambah
        </a>
      </div>

      <div class="p-0">
        <div class="table-container">
          <table class="table table-custom">
            <thead>
              <tr>
                <th>NIP</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Mahasiswa</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($data as $row)
                <tr>
                  <td data-label="NIP">{{ $row->NIP }}</td>
                  <td data-label="Nama">{{ $row->nama }}</td>
                  <td data-label="Email">{{ $row->email }}</td>
                  <td data-label="Mahasiswa" class="mahasiswa-cell">
                    @if($row->mahasiswa->count() > 0)
                      @foreach($row->mahasiswa as $mhs)
                        <div class="mahasiswa-item">
                          {{ $loop->iteration }}. {{ $mhs->nama }} - {{ $mhs->nim }}
                        </div>
                      @endforeach
                    @else
                      <span class="text-muted">Belum memiliki mahasiswa</span>
                    @endif
                  </td>
                  <td data-label="Aksi" class="action-cell">
                    <a href="{{ route('datadosenpembimbing.edit', $row->id_pembimbing) }}" class="btn-edit">
                      <i class="fa fa-pen"></i> Edit
                    </a>
                    <form action="{{ route('datadosenpembimbing.destroy', $row->id_pembimbing) }}" method="POST" class="d-inline">
                      @csrf @method('DELETE')
                      <button type="submit" class="btn-delete" onclick="return confirm('Yakin hapus data ini?')">
                        <i class="fa fa-trash"></i> Hapus
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center text-muted py-4">Belum ada data dosen pembimbing</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
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
