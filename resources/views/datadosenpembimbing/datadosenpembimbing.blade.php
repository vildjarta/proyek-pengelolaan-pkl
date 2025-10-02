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

  <style>
    body {
      background-color:#eef6ff;
      transition:margin-left 0.3s ease;
      margin:0;
      font-family:Arial, sans-serif;
    }
    .container-page {
      width:calc(100% - 260px);
      margin-left:260px;
      margin-top:120px;
      padding:0 25px 40px;
      transition:all 0.3s ease;
    }
    body.sidebar-closed .container-page {
      width:calc(100% - 80px);
      margin-left:80px;
    }
    .card-main {
      border-radius:10px;
      overflow:hidden;
      box-shadow:0 6px 18px rgba(0,0,0,0.08);
      background:#fff;
      padding-bottom:20px;
    }
    .card-header-custom {
      background:#e6f0ff;
      padding:18px 24px 10px;
      border-bottom:2px solid #dbeafe;
      display:flex;
      justify-content:space-between;
      align-items:center;
    }
    .card-header-custom h4 {
      margin:0;
      font-weight:700;
      color:#1e3a8a;
    }
    .btn-add {
      background:#2563eb;
      color:#fff;
      border:none;
      padding:9px 16px;
      border-radius:8px;
      font-weight:600;
      text-decoration:none!important;
      display:inline-flex;
      align-items:center;
      gap:8px;
      box-shadow:0 2px 5px rgba(37,99,235,0.2);
      transition:0.2s;
    }
    .btn-add:hover {background:#1d4ed8;}

    /* Tabel */
    .table-custom {
      width:100%;
      border-collapse:collapse;
    }
    .table-custom th, 
    .table-custom td {
      border:1px solid #e2e8f0; /* full border supaya grid rapi */
      padding:14px 18px;
      vertical-align:middle;
    }
    .table-custom thead th {
      background:#f8fafc;
      font-weight:700;
      color:#0f172a;
      text-align:center;
    }
    .table-custom tbody td {
      color:#1e293b;
      text-align:center;
    }
    /* Kolom Mahasiswa */
    .table-custom tbody td.mahasiswa-cell {
      text-align:left;
    }

    /* Kolom Aksi */
    .action-cell {
      text-align:center;
      vertical-align:middle;
      white-space:nowrap;
    }

    /* Tombol */
    .btn-edit {
      background:#facc15;
      color:#000;
      border:0;
      padding:8px 14px;
      border-radius:8px;
      font-weight:600;
      display:inline-flex;
      align-items:center;
      gap:6px;
      text-decoration:none!important;
      transition:0.2s;
    }
    .btn-edit:hover {background:#eab308;}
    .btn-delete {
      background:#ef4444;
      color:#fff;
      border:0;
      padding:8px 14px;
      border-radius:8px;
      font-weight:600;
      display:inline-flex;
      align-items:center;
      gap:6px;
      transition:0.2s;
    }
    .btn-delete:hover {background:#dc2626;}

    /* Mahasiswa list vertikal */
    .mahasiswa-list {
      display:flex;
      flex-direction:column;
      gap:3px;
    }
    .mahasiswa-item {
      font-size:14px;
      color:#1e293b;
      font-weight:500;
    }

    /* Responsive */
    @media (max-width:992px){
      .container-page {width:100%!important;margin-left:0!important;margin-top:100px;padding:0 15px;}
      .table-custom thead{display:none;}
      .table-custom tbody tr{
        display:block;
        margin-bottom:15px;
        border-radius:10px;
        background:#fff;
        box-shadow:0 2px 6px rgba(0,0,0,0.08);
      }
      .table-custom tbody td{
        display:flex;
        justify-content:space-between;
        padding:10px 14px;
        border-bottom:1px solid #e2e8f0;
        text-align:left!important;
      }
      .table-custom tbody td::before{
        content:attr(data-label);
        font-weight:700;
        color:#475569;
      }
      .action-cell {
        justify-content:flex-start;
      }
    }
  </style>
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
        <div class="table-responsive">
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
                    @if($row->nama_mahasiswa)
                      <div class="mahasiswa-list">
                        @foreach(explode(',', $row->nama_mahasiswa) as $mahasiswa)
                          <span class="mahasiswa-item">{{ trim($mahasiswa) }}</span>
                        @endforeach
                      </div>
                    @else
                      <span class="text-muted">-</span>
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
  const toggleButton=document.querySelector('.menu-toggle');
  const body=document.body;
  toggleButton?.addEventListener('click',()=>{body.classList.toggle('sidebar-closed');});
});
</script>

</body>
</html>
