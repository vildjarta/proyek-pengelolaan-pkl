<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Analisa Transkrip - Sistem PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style-pkl.css">
</head>
<body>

<div class="d-flex">
    {{-- header --}}
    @include('layout.header')
</div>

<div class="d-flex">
    {{-- sidebar --}}
    @include('layout.sidebar')
</div>

<div class="main-content-wrapper">
    <h2>Hasil Analisa Tersimpan</h2>
    <p class="info-text">Berikut adalah daftar hasil analisa kelayakan PKL yang telah tersimpan dalam sistem.</p>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="result-card">
        @if(count($data) > 0)
            <div class="table-wrapper">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>IPK</th>
                            <th>SKS D</th>
                            <th>Ada E</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $row)
                        <tr>
                            <td data-label="Nama">{{ $row->nama_mahasiswa }}</td>
                            <td data-label="NIM">{{ $row->nim }}</td>
                            <td data-label="IPK">{{ number_format($row->ipk, 2) }}</td>
                            <td data-label="SKS D">{{ $row->total_sks_d }}</td>
                            <td data-label="Ada E">{{ $row->has_e ? 'Ya' : 'Tidak' }}</td>
                            <td data-label="Status">
                                @if($row->eligible)
                                    <span class="status-badge status-layak">
                                        <i class="fas fa-check-circle"></i> Layak
                                    </span>
                                @else
                                    <span class="status-badge status-tidak-layak">
                                        <i class="fas fa-times-circle"></i> Tidak Layak
                                    </span>
                                @endif
                            </td>
                            <td data-label="Tanggal">{{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i') }}</td>
                            <td data-label="Aksi">
                                <button class="btn btn-sm btn-primary" onclick="openEditModal({{ $row->id }}, '{{ $row->nama_mahasiswa }}', '{{ $row->nim }}', {{ $row->ipk }}, {{ $row->total_sks_d }}, {{ $row->has_e }}, {{ $row->eligible }})">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <form action="/transkrip/delete/{{ $row->id }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Belum ada data hasil analisa tersimpan.</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Data Transkrip</h3>
            <span class="close" onclick="closeEditModal()">&times;</span>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Mahasiswa:</label>
                    <input type="text" name="nama_mahasiswa" id="edit_nama" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>NIM:</label>
                    <input type="text" name="nim" id="edit_nim" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>IPK:</label>
                    <input type="number" step="0.01" name="ipk" id="edit_ipk" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Total SKS D:</label>
                    <input type="number" name="total_sks_d" id="edit_sks_d" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Ada Nilai E:</label>
                    <select name="has_e" id="edit_has_e" class="form-control" required>
                        <option value="0">Tidak</option>
                        <option value="1">Ya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Status Kelayakan:</label>
                    <select name="eligible" id="edit_eligible" class="form-control" required>
                        <option value="0">Tidak Layak</option>
                        <option value="1">Layak</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(id, nama, nim, ipk, sks_d, has_e, eligible) {
        document.getElementById('editForm').action = `/transkrip/update/${id}`;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_nim').value = nim;
        document.getElementById('edit_ipk').value = ipk;
        document.getElementById('edit_sks_d').value = sks_d;
        document.getElementById('edit_has_e').value = has_e;
        document.getElementById('edit_eligible').value = eligible;
        document.getElementById('editModal').style.display = 'block';
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('editModal');
        if (event.target == modal) {
            closeEditModal();
        }
    }
</script>

<script>
    // Script untuk toggle sidebar dan user menu dropdown
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

</body>
</html>
