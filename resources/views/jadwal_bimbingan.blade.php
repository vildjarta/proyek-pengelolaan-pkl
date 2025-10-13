@include('layout.header')
@include('layout.sidebar')
    <link rel="stylesheet" href="{{ asset('assets/css/style-pkl-jadwal.css') }}">
    {{-- Pastikan Font Awesome sudah ada di layout utama Anda --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    <div class="main-content-wrapper">
        <div class="table-card">
            <div class="table-header">
                <a href="{{ route('jadwal.create') }}" class="btn btn-primary">Tambah Jadwal</a>
                <div class="search-container">
                    <form action="{{ route('jadwal.index') }}" method="GET">
                        <input type="text" name="search" class="form-control search-input" placeholder="Cari Mahasiswa/Dosen..." value="{{ $search ?? '' }}">
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table">
                <thead>
                    <tr>
                        <th><a href="{{ route('jadwal.index', ['sort' => 'mahasiswa', 'search' => $search]) }}">Mahasiswa @if($sort == 'mahasiswa')<i class="fas fa-sort-alpha-down"></i>@endif</a></th>
                        <th><a href="{{ route('jadwal.index', ['sort' => 'dosen', 'search' => $search]) }}">Dosen @if($sort == 'dosen')<i class="fas fa-sort-alpha-down"></i>@endif</a></th>
                        <th><a href="{{ route('jadwal.index', ['sort' => 'waktu_terdekat', 'search' => $search]) }}">Tanggal @if($sort == 'waktu_terdekat')<i class="fas fa-sort-amount-down"></i>@endif</a></th>
                        <th><a href="{{ route('jadwal.index', ['sort' => 'waktu', 'search' => $search]) }}">Waktu @if($sort == 'waktu')<i class="fas fa-sort-amount-down"></i>@endif</a></th>
                        <th>Topik</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwals as $jadwal)
                    <tr>
                        <td>{{ $jadwal->mahasiswa ?? 'N/A' }}</td>
                        <td>{{ $jadwal->dosen ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}</td>
                        <td>{{ $jadwal->topik ?? '-' }}</td>
                        <td>{{ $jadwal->catatan ?? '-' }}</td>
                        <td class="text-center">
                            <div class="action-buttons">
                                <a href="{{ route('jadwal.edit', $jadwal->id) }}" class="btn btn-edit-custom" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('jadwal.destroy', $jadwal->id) }}" method="POST" class="d-inline">
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
                        <td colspan="7" style="text-align: center;">
                            @if($search)
                                Tidak ada jadwal yang cocok dengan kata kunci "{{ $search }}".
                            @else
                                Belum ada jadwal bimbingan yang ditambahkan.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toggleButton = document.querySelector('.menu-toggle');
                const body = document.body;

                function updateSortingLinks() {
                    const sortingLinks = document.querySelectorAll('.table thead a');
                    const isSidebarClosed = body.classList.contains('sidebar-closed');

                    sortingLinks.forEach(link => {
                        let url = new URL(link.href);
                        if (isSidebarClosed) {
                            url.searchParams.set('sidebar', 'closed');
                        } else {
                            url.searchParams.delete('sidebar');
                        }
                        link.href = url.toString();
                    });
                }

                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.get('sidebar') === 'closed') {
                    body.classList.add('sidebar-closed');
                }

                updateSortingLinks();

                if (toggleButton) {
                    toggleButton.addEventListener('click', function() {
                        body.classList.toggle('sidebar-closed');
                        updateSortingLinks();
                    });
                }
                
                const profileWrapper = document.querySelector('.user-profile-wrapper');
                const userinfo = document.querySelector('.user-info');
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