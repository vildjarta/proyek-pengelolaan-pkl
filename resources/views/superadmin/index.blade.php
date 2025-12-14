@include('layout.header')
@include('layout.sidebar')

<link rel="stylesheet" href="{{ asset('assets/css/superadmin.css') }}">

<div class="main-content-wrapper">
    <div class="content sa-wrapper">

        <div class="sa-header">
            <div>
                <h2 class="sa-title">Manajemen Pengguna</h2>
                <small class="text-muted">Super Admin Control Panel</small>
            </div>
            
            <div class="sa-controls">
                {{-- TOMBOL TAMBAH (Link ke Halaman Create) --}}
                <a href="{{ route('manage-users.create') }}" class="btn-add-new text-decoration-none d-inline-flex align-items-center justify-content-center">
                    <i class="fa fa-plus-circle me-2"></i> Tambah Baru
                </a>
                {{-- FORM GABUNGAN (FILTER + SEARCH) --}}
                <form action="{{ route('manage-users.index') }}" method="GET" class="d-flex" style="gap: 5px;">
                    
                    {{-- INPUT FILTER ROLE (BARU) --}}
                    {{-- Otomatis submit saat dipilih --}}
                    <select name="role" class="sa-select" onchange="this.form.submit()">
                        <option value="">- Semua Role -</option>
                        <option value="mahasiswa" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                        <option value="dosen_pembimbing" {{ request('role') == 'dosen_pembimbing' ? 'selected' : '' }}>Dosen Pembimbing</option>
                        <option value="dosen_penguji" {{ request('role') == 'dosen_penguji' ? 'selected' : '' }}>Dosen Penguji</option>
                        <option value="koordinator" {{ request('role') == 'koordinator' ? 'selected' : '' }}>Koordinator</option>
                        <option value="ketua_prodi" {{ request('role') == 'ketua_prodi' ? 'selected' : '' }}>Ketua Prodi</option>
                        <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                        <option value="perusahaan" {{ request('role') == 'perusahaan' ? 'selected' : '' }}>Perusahaan</option>
                    </select>

                <form action="{{ route('manage-users.index') }}" method="GET">
                    <div class="sa-search-box">
                        <input type="text" name="search" class="sa-search-input" placeholder="Cari user..." value="{{ request('search') }}">
                        <button class="sa-search-btn" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

        <div class="sa-card">
            <div class="table-responsive">
                <table class="sa-table">
                    <thead>
                        <tr>
                            <th>Nama Lengkap</th>
                            <th>Email Akun</th>
                            <th class="text-center">Role</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td class="text-center">
                                <span class="role-badge">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span>
                            </td>
                            <td class="col-action">
                                <div class="action-wrapper">
                                    {{-- TOMBOL EDIT (Link ke Halaman Edit) --}}
                                    <a href="{{ route('manage-users.edit', $user->id) }}" class="btn-action btn-edit text-decoration-none d-inline-flex align-items-center">
                                        <i class="fa fa-pen fa-sm me-1"></i> Edit
                                    </a>

                                    {{-- TOMBOL HAPUS --}}
                                    <form action="{{ route('manage-users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete border-0">
                                            <i class="fa fa-trash fa-sm me-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4">Data tidak ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- BAGIAN PAGINATION SUDAH DIHAPUS DISINI --}}

        </div>
    </div>
</div>