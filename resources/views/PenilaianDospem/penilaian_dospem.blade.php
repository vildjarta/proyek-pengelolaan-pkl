@include('layout.header')
@include('layout.sidebar')

    <link rel="stylesheet" href="{{ asset('assets/css/table-penilaian.css') }}"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    <div class="main-content-wrapper">
        <div class="table-card">
            <div class="table-header">
                @if(auth()->check() && isset(auth()->user()->role) && auth()->user()->role === 'koordinator')
                    <a href="{{ route('penilaian.create') }}" class="btn btn-primary">Tambah Penilaian</a>
                @endif
                <div class="search-container">
                    <form action="{{ route('penilaian.index') }}" method="GET">
                        <input type="text" name="search" class="form-control search-input" placeholder="Cari Mahasiswa/NIM..." value="{{ $search ?? '' }}">
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th><a href="{{ route('penilaian.index', ['sort' => 'mahasiswa', 'search' => $search ?? '']) }}">Nama Mahasiswa @if(isset($sort) && $sort == 'mahasiswa')<i class="fas fa-sort-amount-up"></i>@endif</a></th>
                        
                        <th><a href="{{ route('penilaian.index', ['sort' => 'nilai_internal', 'search' => $search ?? '']) }}">Nilai (100) @if(isset($sort) && $sort == 'nilai_internal')<i class="fas fa-sort-alpha-down"></i>@endif</a></th>
                        
                        <th><a href="{{ route('penilaian.index', ['sort' => 'nilai', 'search' => $search ?? '']) }}">Nilai Akhir (30%) @if(isset($sort) && $sort == 'nilai')<i class="fas fa-sort-alpha-down"></i>@endif</a></th>
                        <th><a href="{{ route('penilaian.index', ['sort' => 'grade', 'search' => $search ?? '']) }}">Grade @if(isset($sort) && $sort == 'grade')<i class="fas fa-sort-alpha-down"></i>@endif</a></th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penilaian as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->mahasiswa->nim ?? 'N/A' }}</td>
                        <td>{{ $item->nama_mahasiswa }}</td>
                        <td>{{ $item->nilai_dospem_internal }}</td>
                        <td>{{ $item->nilai_akhir }}</td>
                        <td>{{ $item->grade }}</td>
                        <td class="text-center">
                            <div class="action-buttons">
                                @if(auth()->check() && isset(auth()->user()->role) && auth()->user()->role === 'koordinator')
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
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center;">
                            @if(request('search'))
                                Tidak ada data penilaian yang cocok dengan kata kunci "{{ request('search') }}".
                            @else
                                Belum ada data penilaian yang ditambahkan.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
</div>
