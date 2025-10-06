@include('layout.header')
@include('layout.sidebar')
<link rel="stylesheet" href="{{ asset('assets/css/table-penilaian.css') }}">

<div class="main-content-wrapper">
    <div class="content">
        <h2>Daftar Penilaian Mahasiswa PKL / Seminar</h2>

        <a href="{{ route('penilaian.create') }}" class="btn btn-success" style="margin-bottom: 20px;">+ Tambah Penilaian</a>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Judul</th>
                    <th>Laporan</th>
                    <th>Presentasi</th>
                    <th>Penguasaan</th>
                    <th>Sikap</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penilaian as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->mahasiswa->nim ?? 'N/A' }}</td>
                        <td>{{ $item->nama_mahasiswa }}</td>
                        <td>{{ $item->judul }}</td>
                        <td>{{ $item->laporan }}</td>
                        <td>{{ $item->presentasi }}</td>
                        <td>{{ $item->penguasaan }}</td>
                        <td>{{ $item->sikap }}</td>
                        <td>
                            {{-- KODE TOMBOL AKSI DIUBAH DI SINI --}}
                            <div class="action-buttons">
                                <a href="{{ route('penilaian.edit', $item->id) }}" class="btn btn-edit-custom" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('penilaian.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin hapus data?')" title="Hapus">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Belum ada data penilaian.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
                <script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.querySelector('.menu-toggle');
        const body = document.body;
        const profileWrapper = document.querySelector('.user-profile-wrapper');
        const userinfo = document.querySelector('.user-info');
        
        if (toggleButton) {
            toggleButton.addEventListener('click', function() {
                body.classList.toggle('sidebar-closed');
            });
        }
        
        if (userinfo) {
            userinfo.addEventListener('click', function(e) {
                e.preventDefault(); 
                profileWrapper.classList.toggle('active');
            });
            
            document.addEventListener('click', function(e) {
                if (!profileWrapper.contains(e.target) && profileWrapper.classList.contains('active')) {
                    profileWrapper.classList.remove('active');
                }
            });
        }
    });
</script>
</div>