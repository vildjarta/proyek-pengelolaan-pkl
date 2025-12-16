<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Perhitungan Bobot AHP - PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Font Awesome & Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- CSS Layout Global --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style-header-sidebar.css') }}">
    
    <style>
        .ahp-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            padding: 24px;
            margin-top: 20px;
        }
        .ahp-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }
        .ahp-subtitle {
            color: #64748b;
            margin-bottom: 24px;
        }
        .comparison-table {
            width: 100%;
            border-collapse: collapse;
        }
        .comparison-table th {
            background: #f1f5f9;
            padding: 12px 16px;
            text-align: center;
            font-weight: 600;
            color: #334155;
            border-bottom: 2px solid #e2e8f0;
        }
        .comparison-table td {
            padding: 14px 16px;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .comparison-table tr:hover {
            background: #f8fafc;
        }
        .kriteria-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.9rem;
        }
        .kriteria-a {
            background: #dbeafe;
            color: #1e40af;
        }
        .kriteria-b {
            background: #fce7f3;
            color: #be185d;
        }
        .form-select {
            min-width: 160px;
        }
        .btn-simpan {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border: none;
            padding: 12px 32px;
            font-weight: 600;
            border-radius: 8px;
            color: #fff;
        }
        .btn-simpan:hover {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: #fff;
        }
        .bobot-result {
            background: #f0fdf4;
            border: 1px solid #86efac;
            border-radius: 8px;
            padding: 16px;
            margin-top: 20px;
        }
        .bobot-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #bbf7d0;
        }
        .bobot-item:last-child {
            border-bottom: none;
        }
        .bobot-value {
            font-weight: 700;
            color: #166534;
        }
        .vs-text {
            color: #94a3b8;
            font-weight: 500;
        }
    </style>
</head>
<body>

    {{-- HEADER --}}
    @include('layout.header')

    {{-- SIDEBAR --}}
    @include('layout.sidebar')

    {{-- MAIN CONTENT --}}
    <div class="main-content-wrapper" id="mainContent">
        <div class="content container-fluid">
            <div class="ahp-card">
                <h2 class="ahp-title"><i class="fa fa-calculator me-2"></i>Perhitungan Bobot AHP</h2>
                <p class="ahp-subtitle">Bandingkan tingkat kepentingan antar kriteria untuk menentukan bobot masing-masing kriteria.</p>

                <!-- Pesan sukses -->
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                    </div>
                @endif

                <!-- Tampilkan Hasil Perhitungan AHP (setelah simpan) -->
                @if($hasilPerhitungan)
                    <div class="mb-4">
                        <!-- Matriks Perbandingan Berpasangan -->
                        <div class="card mb-3">
                            <div class="card-header bg-primary text-white">
                                <i class="fa fa-table me-2"></i>Matriks Perbandingan Berpasangan
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-sm text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Kriteria</th>
                                            @foreach($hasilPerhitungan['kriteria'] as $key => $label)
                                                <th>{{ $label }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($hasilPerhitungan['kriteria'] as $rowKey => $rowLabel)
                                            <tr>
                                                <td class="fw-bold bg-light">{{ $rowLabel }}</td>
                                                @foreach($hasilPerhitungan['kriteria'] as $colKey => $colLabel)
                                                    <td>{{ number_format($hasilPerhitungan['matrix'][$rowKey][$colKey], 3) }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                        <tr class="table-warning">
                                            <td class="fw-bold">Jumlah</td>
                                            @foreach($hasilPerhitungan['kriteria'] as $colKey => $colLabel)
                                                <td class="fw-bold">{{ number_format($hasilPerhitungan['columnSum'][$colKey], 3) }}</td>
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Matriks Ternormalisasi -->
                        <div class="card mb-3">
                            <div class="card-header bg-info text-white">
                                <i class="fa fa-percentage me-2"></i>Matriks Ternormalisasi
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-sm text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Kriteria</th>
                                            @foreach($hasilPerhitungan['kriteria'] as $key => $label)
                                                <th>{{ $label }}</th>
                                            @endforeach
                                            <th class="bg-success text-white">Bobot</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($hasilPerhitungan['kriteria'] as $rowKey => $rowLabel)
                                            <tr>
                                                <td class="fw-bold bg-light">{{ $rowLabel }}</td>
                                                @foreach($hasilPerhitungan['kriteria'] as $colKey => $colLabel)
                                                    <td>{{ number_format($hasilPerhitungan['normalizedMatrix'][$rowKey][$colKey], 4) }}</td>
                                                @endforeach
                                                <td class="fw-bold bg-success text-white">{{ number_format($hasilPerhitungan['bobot'][$rowKey], 4) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Consistency Ratio -->
                        <div class="card mb-3">
                            <div class="card-header {{ $hasilPerhitungan['cr']['konsisten'] ? 'bg-success' : 'bg-danger' }} text-white">
                                <i class="fa fa-check-circle me-2"></i>Uji Konsistensi
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="p-3 bg-light rounded text-center">
                                            <small class="text-muted">Lambda Max (λmax)</small>
                                            <h5 class="mb-0">{{ number_format($hasilPerhitungan['cr']['lambda_max'], 4) }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="p-3 bg-light rounded text-center">
                                            <small class="text-muted">Consistency Index (CI)</small>
                                            <h5 class="mb-0">{{ number_format($hasilPerhitungan['cr']['ci'], 4) }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="p-3 bg-light rounded text-center">
                                            <small class="text-muted">Random Index (RI)</small>
                                            <h5 class="mb-0">{{ number_format($hasilPerhitungan['cr']['ri'], 2) }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="p-3 {{ $hasilPerhitungan['cr']['konsisten'] ? 'bg-success' : 'bg-danger' }} text-white rounded text-center">
                                            <small>Consistency Ratio (CR)</small>
                                            <h5 class="mb-0">{{ number_format($hasilPerhitungan['cr']['cr'], 4) }}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 text-center">
                                    @if($hasilPerhitungan['cr']['konsisten'])
                                        <span class="badge bg-success fs-6"><i class="fa fa-check me-1"></i>KONSISTEN (CR ≤ 0.1)</span>
                                    @else
                                        <span class="badge bg-danger fs-6"><i class="fa fa-times me-1"></i>TIDAK KONSISTEN (CR > 0.1)</span>
                                        <p class="text-danger mt-2 mb-0"><small>Silakan ulangi perbandingan dengan lebih konsisten.</small></p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Tampilkan bobot yang sudah tersimpan -->
                @if($bobot)
                    <div class="bobot-result mb-4">
                        <h5 class="mb-3"><i class="fa fa-chart-pie me-2"></i>Bobot Kriteria Tersimpan</h5>
                        <div class="bobot-item">
                            <span>Jumlah Mahasiswa</span>
                            <span class="bobot-value">{{ number_format($bobot->jumlah_mahasiswa, 4) }}</span>
                        </div>
                        <div class="bobot-item">
                            <span>Fasilitas</span>
                            <span class="bobot-value">{{ number_format($bobot->fasilitas, 4) }}</span>
                        </div>
                        <div class="bobot-item">
                            <span>Hari Operasi</span>
                            <span class="bobot-value">{{ number_format($bobot->hari_operasi, 4) }}</span>
                        </div>
                        <div class="bobot-item">
                            <span>Level Legalitas</span>
                            <span class="bobot-value">{{ number_format($bobot->level_legalitas, 4) }}</span>
                        </div>
                        <div class="bobot-item" style="background: #dcfce7; margin-top: 10px; padding: 12px; border-radius: 6px;">
                            <span><strong>Total Bobot</strong></span>
                            <span class="bobot-value">{{ number_format($bobot->jumlah_mahasiswa + $bobot->fasilitas + $bobot->hari_operasi + $bobot->level_legalitas, 4) }}</span>
                        </div>
                    </div>
                @endif

                <form action="{{ route('ahp.store') }}" method="POST">
                    @csrf
                    
                    <div class="table-responsive">
                        <table class="comparison-table">
                            <thead>
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th style="width: 22%">Kategori A</th>
                                    <th style="width: 6%"></th>
                                    <th style="width: 22%">Kategori B</th>
                                    <th style="width: 25%">Lebih Penting</th>
                                    <th style="width: 20%">Skala (1-9)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pairs as $index => $pair)
                                    <tr>
                                        <td><strong>{{ $index + 1 }}</strong></td>
                                        <td>
                                            <span class="kriteria-badge kriteria-a">{{ $pair['a_label'] }}</span>
                                        </td>
                                        <td><span class="vs-text">vs</span></td>
                                        <td>
                                            <span class="kriteria-badge kriteria-b">{{ $pair['b_label'] }}</span>
                                        </td>
                                        <td>
                                            <input type="hidden" name="comparisons[{{ $index }}][pair]" value="{{ $pair['a'] }}|{{ $pair['b'] }}">
                                            <select name="comparisons[{{ $index }}][lebih_penting]" class="form-select" required>
                                                <option value="">-- Pilih --</option>
                                                <option value="{{ $pair['a'] }}">{{ $pair['a_label'] }}</option>
                                                <option value="{{ $pair['b'] }}">{{ $pair['b_label'] }}</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="comparisons[{{ $index }}][skala]" class="form-select" required>
                                                <option value="">-- Pilih --</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-simpan">
                            <i class="fa fa-save me-2"></i>Hitung & Simpan Bobot
                        </button>
                    </div>
                </form>

                <!-- Info AHP -->
                <div class="mt-4 p-3" style="background: #fffbeb; border: 1px solid #fcd34d; border-radius: 8px;">
                    <h6><i class="fa fa-info-circle me-2 text-warning"></i>Panduan Skala AHP</h6>
                    <small class="text-muted">
                        <strong>1</strong> = Sama penting |
                        <strong>3</strong> = Sedikit lebih penting |
                        <strong>5</strong> = Lebih penting |
                        <strong>7</strong> = Sangat lebih penting |
                        <strong>9</strong> = Mutlak lebih penting |
                        <strong>2,4,6,8</strong> = Nilai tengah
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>