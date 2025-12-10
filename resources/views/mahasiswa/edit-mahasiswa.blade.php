<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Mahasiswa - Sistem PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style-pkl.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/nilai.css') }}">
    
    <style>
        /* Layout utama */
        .main-content-wrapper {
            padding: 30px;
            margin-left: 250px;
            transition: margin-left 0.3s;
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .form-container {
            max-width: 100%;
            margin: 0 auto;
        }

        /* Form styling seperti form tambah mahasiswa */
        .form-section {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-left: 5px solid #4a6baf;
        }
        
        .form-section h3 {
            color: #4a6baf;
            margin-top: 0;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f4f8;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.3em;
        }
        
        /* LAYOUT FORM-ROW SEPERTI FORM TAMBAH */
        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            flex: 1;
            min-width: 300px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e8ed;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background-color: #fafbfc;
            box-sizing: border-box;
        }
        
        .form-control:focus {
            border-color: #4a6baf;
            background-color: #fff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(74, 107, 175, 0.15);
        }
        
        .form-group select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%234a6baf' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 12px;
            padding-right: 40px;
        }
        
        .required {
            color: #e74c3c;
            font-weight: bold;
        }
        
        .invalid-feedback {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
            display: block;
            font-weight: 500;
        }
        
        .is-invalid {
            border-color: #e74c3c !important;
            background-color: #fdf2f2 !important;
        }
        
        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 2px solid #f0f4f8;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            min-width: 140px;
            justify-content: center;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #4a6baf, #3a5a9f);
            color: white;
            box-shadow: 0 2px 4px rgba(74, 107, 175, 0.3);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #3a5a9f, #2a4a8f);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(74, 107, 175, 0.4);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white;
            box-shadow: 0 2px 4px rgba(108, 117, 125, 0.3);
        }
        
        .btn-secondary:hover {
            background: linear-gradient(135deg, #5a6268, #495057);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(108, 117, 125, 0.4);
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }
        
        .page-header h2 {
            color: #2c3e50;
            margin: 0;
            font-size: 1.8em;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .page-header h2 i {
            color: #4a6baf;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .main-content-wrapper {
                margin-left: 0;
                padding: 20px 15px;
            }
            
            .form-row {
                flex-direction: column;
                gap: 15px;
            }
            
            .form-group {
                min-width: 100%;
            }
            
            .form-section {
                padding: 20px;
            }
            
            .btn {
                padding: 10px 20px;
                min-width: 120px;
                font-size: 13px;
            }
            
            .page-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .page-header h2 {
                font-size: 1.5em;
            }
        }

        /* Animation untuk form sections */
        .form-section {
            animation: fadeInUp 0.5s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Placeholder styling */
        .form-control::placeholder {
            color: #a0a0a0;
            font-size: 14px;
        }
        
        /* Hover effects */
        .form-control:hover {
            border-color: #c8d1e0;
        }
    </style>
</head>
<body>

{{-- Header dan Sidebar --}}
<div class="d-flex">
    @include('layout.header')
</div>

<div class="d-flex">
    @include('layout.sidebar')
</div>

{{-- Konten Utama --}}
<div class="main-content-wrapper">
    <div class="form-container">
        <div class="page-header">
            <h2><i class="fas fa-edit"></i> Edit Data Mahasiswa</h2>
            <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>

        <form action="{{ route('mahasiswa.update', $mahasiswa->id_mahasiswa) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Informasi Mahasiswa -->
            <div class="form-section">
                <h3><i class="fas fa-user"></i> Informasi Mahasiswa</h3>
                
                <!-- BARIS PERTAMA: NIM dan Nama -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="nim">NIM <span class="required">*</span></label>
                        <input type="number" name="nim" id="nim" class="form-control @error('nim') is-invalid @enderror"
                            required placeholder="Masukkan NIM" value="{{ old('nim', $mahasiswa->nim) }}">
                        @error('nim') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="nama">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                            required placeholder="Masukkan Nama Lengkap" value="{{ old('nama', $mahasiswa->nama) }}">
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- BARIS KEDUA: Pilih Email dari Users atau Input Manual -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="user_id">Pilih Email dari User (Opsional)</label>
                        <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror">
                            <option value="">-- Pilih User atau Isi Manual --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" data-email="{{ $user->email }}" 
                                    {{ old('user_id', $mahasiswa->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small style="color: #6c757d; display: block; margin-top: 5px;">
                            <i class="fas fa-info-circle"></i> Pilih dari user yang ada atau isi email manual di bawah
                        </small>
                    </div>
                </div>

                <!-- BARIS KETIGA: Email Manual dan No HP -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email <span class="required">*</span></label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                            required placeholder="Masukkan Email atau pilih dari user di atas" value="{{ old('email', $mahasiswa->email) }}">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small style="color: #6c757d; display: block; margin-top: 5px;">
                            <i class="fas fa-info-circle"></i> Email akan otomatis terisi jika memilih user di atas
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="no_hp">No. HP</label>
                        <input type="number" name="no_hp" id="no_hp" class="form-control @error('no_hp') is-invalid @enderror"
                            placeholder="Masukkan Nomor HP" value="{{ old('no_hp', $mahasiswa->no_hp) }}">
                        @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <!-- Akademik -->
            <div class="form-section">
                <h3><i class="fas fa-graduation-cap"></i> Data Akademik</h3>
                
                <!-- BARIS PERTAMA: Prodi, Angkatan, IPK -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="prodi">Program Studi <span class="required">*</span></label>
                        <select name="prodi" id="prodi" class="form-control @error('prodi') is-invalid @enderror" required>
                            <option value="">-- Pilih Prodi --</option>
                            <option value="Akuntansi" {{ old('prodi', $mahasiswa->prodi) == 'Akuntansi' ? 'selected' : '' }}>Akuntansi</option>
                            <option value="Agroindustri" {{ old('prodi', $mahasiswa->prodi) == 'Agroindustri' ? 'selected' : '' }}>Agroindustri</option>
                            <option value="Teknologi Informasi" {{ old('prodi', $mahasiswa->prodi) == 'Teknologi Informasi' ? 'selected' : '' }}>Teknologi Informasi</option>
                            <option value="Teknologi Otomotif" {{ old('prodi', $mahasiswa->prodi) == 'Teknologi Otomotif' ? 'selected' : '' }}>Teknologi Otomotif</option>
                            <option value="Akuntansi Perpajakan (D4)" {{ old('prodi', $mahasiswa->prodi) == 'Akuntansi Perpajakan (D4)' ? 'selected' : '' }}>Akuntansi Perpajakan (D4)</option>
                            <option value="Teknologi Pakan Ternak (D4)" {{ old('prodi', $mahasiswa->prodi) == 'Teknologi Pakan Ternak (D4)' ? 'selected' : '' }}>Teknologi Pakan Ternak (D4)</option>
                            <option value="Teknologi Rekayasa Komputer Jaringan (D4)" {{ old('prodi', $mahasiswa->prodi) == 'Teknologi Rekayasa Komputer Jaringan (D4)' ? 'selected' : '' }}>Teknologi Rekayasa Komputer Jaringan (D4)</option>
                            <option value="Teknologi Rekayasa Konstruksi Jalan dan Jembatan (D4)" {{ old('prodi', $mahasiswa->prodi) == 'Teknologi Rekayasa Konstruksi Jalan dan Jembatan (D4)' ? 'selected' : '' }}>Teknologi Rekayasa Konstruksi Jalan dan Jembatan (D4)</option>
                        </select>
                        @error('prodi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="angkatan">Angkatan <span class="required">*</span></label>
                        <input type="number" name="angkatan" id="angkatan" class="form-control @error('angkatan') is-invalid @enderror"
                            required placeholder="Masukkan Tahun Angkatan" value="{{ old('angkatan', $mahasiswa->angkatan) }}">
                        @error('angkatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="ipk">IPK</label>
                        <input type="number" step="0.01" min="0" max="4" name="ipk" id="ipk"
                            class="form-control @error('ipk') is-invalid @enderror"
                            placeholder="Masukkan IPK (0.00 - 4.00)" value="{{ old('ipk', $mahasiswa->ipk) }}">
                        @error('ipk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <!-- Tempat PKL -->
            <div class="form-section">
                <h3><i class="fas fa-building"></i> Tempat PKL</h3>
                
                <!-- BARIS: Perusahaan -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="perusahaan">Perusahaan (Tempat PKL)</label>
                        <input type="text" 
                               name="perusahaan" 
                               id="perusahaan"
                               class="form-control @error('perusahaan') is-invalid @enderror"
                               placeholder="Ketik atau pilih nama perusahaan"
                               value="{{ old('perusahaan', $mahasiswa->perusahaan) }}"
                               list="perusahaan-list"
                               autocomplete="off">
                        
                        <datalist id="perusahaan-list">
                            @foreach($perusahaan as $p)
                                <option value="{{ $p->nama }}">{{ $p->nama }}</option>
                            @endforeach
                        </datalist>
                        
                        @error('perusahaan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small style="color: #6c757d; display: block; margin-top: 5px;">
                            <i class="fas fa-info-circle"></i> Ketik untuk mencari atau pilih dari dropdown
                        </small>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Data
                </button>
                <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
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
    
    // Auto-format untuk input tahun angkatan
    const angkatanInput = document.getElementById('angkatan');
    if (angkatanInput) {
        angkatanInput.addEventListener('input', function() {
            if (this.value.length > 4) {
                this.value = this.value.slice(0, 4);
            }
        });
    }

    // Auto-fill email ketika memilih user dari dropdown
    const userSelect = document.getElementById('user_id');
    const emailInput = document.getElementById('email');
    
    if (userSelect && emailInput) {
        userSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const email = selectedOption.getAttribute('data-email');
            
            if (email) {
                emailInput.value = email;
            } else if (this.value === '') {
                // Jika "Pilih User atau Isi Manual" dipilih, kosongkan email
                emailInput.value = '';
            }
        });
    }
});
</script>

</body>
</html>
