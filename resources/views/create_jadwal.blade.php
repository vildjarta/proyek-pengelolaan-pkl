@include('layout.header')
@include('layout.sidebar')
<link rel="stylesheet" href="{{ asset('assets/css/style-create-jadwal.css') }}">

<div class="main-content-wrapper">
    <div class="content">
        <h2>Tambah Jadwal Bimbingan</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('jadwal.store') }}" method="POST">
            @csrf
            
            {{-- KODE BARU UNTUK MAHASISWA --}}
            <div class="form-group">
                <label for="mahasiswa_nama">Mahasiswa</label>
                <input type="text" id="mahasiswa_nama" list="mahasiswa-list" class="form-control" required placeholder="Ketik untuk mencari NIM atau Nama...">
                <datalist id="mahasiswa-list">
                    @foreach($mahasiswas as $mahasiswa)
                        <option data-id="{{ $mahasiswa->id_mahasiswa }}" value="{{ $mahasiswa->nim }} - {{ $mahasiswa->nama }}"></option>
                    @endforeach
                </datalist>
                {{-- Input tersembunyi untuk mengirim ID ke backend --}}
                <input type="hidden" name="id_mahasiswa" id="id_mahasiswa">
            </div>

            {{-- KODE BARU UNTUK DOSEN --}}
            <div class="form-group">
                <label for="dosen_nama">Dosen Pembimbing</label>
                <input type="text" id="dosen_nama" list="dosen-list" class="form-control" required placeholder="Ketik untuk mencari Nama Dosen...">
                <datalist id="dosen-list">
                    @foreach($dosens as $dosen)
                        <option data-id="{{ $dosen->id_pembimbing }}" value="{{ $dosen->nama }}"></option>
                    @endforeach
                </datalist>
                {{-- Input tersembunyi untuk mengirim ID ke backend --}}
                <input type="hidden" name="id_pembimbing" id="id_pembimbing">
            </div>

            {{-- Bagian form lainnya tetap sama --}}
            <div class="form-group">
                <label for="tanggal">Tanggal Bimbingan</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal') }}" required>
            </div>

            <div class="form-group">
                <label for="waktu_mulai">Waktu Mulai</label>
                <input type="time" name="waktu_mulai" id="waktu_mulai" class="form-control" value="{{ old('waktu_mulai') }}" required>
            </div>
            
            <div class="form-group">
                <label for="waktu_selesai">Waktu Selesai</label>
                <input type="time" name="waktu_selesai" id="waktu_selesai" class="form-control" value="{{ old('waktu_selesai') }}" required>
            </div>

            <div class="form-group">
                <label for="topik">Topik Bimbingan (Opsional)</label>
                <input type="text" name="topik" id="topik" class="form-control" value="{{ old('topik') }}" placeholder="Contoh: Revisi Bab 1">
            </div>
            
            <div class="form-group">
                <label for="catatan">Catatan (Opsional)</label>
                <input type="text" name="catatan" id="catatan" class="form-control" value="{{ old('catatan') }}" placeholder="Contoh: Bawa laptop dan hard copy">
            </div>

            <button type="submit" class="btn btn-success">Simpan Jadwal</button>
        </form>
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
<script>
    function confirmSubmit() {
        return confirm("Apakah Anda yakin ingin Mengedit Penilaian ini?");
    }
</script>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {

    function setupDatalistSync(textInputId, dataListId, hiddenInputId) {
        const textInput = document.getElementById(textInputId);
        const dataList = document.getElementById(dataListId);
        const hiddenInput = document.getElementById(hiddenInputId);

        textInput.addEventListener('input', function() {
            const typedValue = this.value;
            
            hiddenInput.value = '';

            const options = dataList.options;
            for (let i = 0; i < options.length; i++) {
                if (options[i].value === typedValue) {
                    // Jika ketemu, ambil 'data-id' dan set ke input hidden
                    hiddenInput.value = options[i].getAttribute('data-id');
                    return; // Hentikan pencarian jika sudah ketemu
                }
            }
        });
    }

    setupDatalistSync('mahasiswa_nama', 'mahasiswa-list', 'id_mahasiswa');
    setupDatalistSync('dosen_nama', 'dosen-list', 'id_pembimbing');
});
</script>
