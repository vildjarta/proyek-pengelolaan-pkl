@extends('layout.header')

@section('title', 'Manajemen Dokumen - SIMPKL-TI')

<!-- Add Bootstrap 5 CSS for styling -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<div class="d-flex">
    @include('layout.sidebar')
</div>

<div class="main-content-wrapper">
    <div class="container py-4">
        <!-- Page Header -->
        <div class="page-header mb-5 pb-3 border-bottom">
            <h1 class="h2 mb-2 fw-bold text-dark">
                <i class="fas fa-file-word me-2 text-primary"></i>
                Manajemen Dokumen PKL
            </h1>
            <p class="text-muted fs-5">Kelola template dokumen untuk kegiatan Praktik Kerja Lapangan</p>
        </div>

        <!-- Upload Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 shadow rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-bottom py-4 px-4">
                        <h5 class="mb-0 fw-bold text-dark">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary p-2 rounded me-3">
                                    <i class="fas fa-cloud-upload-alt fa-lg"></i>
                                </div>
                                Upload Dokumen Baru
                            </div>
                        </h5>
                    </div>
                    <div class="card-body p-4 bg-light bg-opacity-50">
                        @if(session('success'))
                            <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center" role="alert">
                                <i class="fas fa-check-circle fs-4 me-3"></i>
                                <div>{{ session('success') }}</div>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center" role="alert">
                                <i class="fas fa-exclamation-circle fs-4 me-3"></i>
                                <div>{{ session('error') }}</div>
                            </div>
                        @endif

                        <form action="{{ route('documents.upload') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row align-items-end g-4">
                                <div class="col-md-4">
                                    <label for="category" class="form-label fw-semibold text-secondary mb-2">Kategori Dokumen</label>
                                    <select class="form-select form-select-lg border-0 shadow-sm rounded-3" id="category" name="category" required style="cursor: pointer;">
                                        <option value="" disabled selected>-- Pilih Kategori --</option>
                                        <option value="proposal">📝 Proposal PKL</option>
                                        <option value="undangan">✉️ Undangan</option>
                                        <option value="laporan-pkl">📑 Laporan PKL</option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label for="document" class="form-label fw-semibold text-secondary mb-2">Pilih File (DOCX, DOC, PDF)</label>
                                    <input type="file" class="form-control form-control-lg border-0 shadow-sm rounded-3" id="document" name="document" 
                                           accept=".docx,.doc,.pdf" required>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-3 shadow-sm fw-semibold d-flex align-items-center justify-content-center gap-2" style="height: 48px;">
                                        <i class="fas fa-upload"></i> Unggah File
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents List -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-folder-open me-2"></i>
                            Daftar Dokumen
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(!empty($documents))
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Dokumen</th>
                                            <th>Kategori</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($documents as $doc)
                                        <tr class="align-middle">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-light rounded p-2 me-3">
                                                        <i class="fas fa-file-{{ $doc['type'] == 'pdf' ? 'pdf text-danger' : 'word text-primary' }} fa-lg"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 text-dark">{{ $doc['display_name'] }}</h6>
                                                        <small class="text-muted text-uppercase" style="font-size: 0.75rem;">{{ $doc['type'] }} • {{ number_format($doc['size']/1024, 2) }} KB</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge rounded-pill bg-{{ 
                                                    $doc['category'] == 'proposal' ? 'primary' : 
                                                    ($doc['category'] == 'undangan' ? 'info' : 'success') 
                                                }} bg-opacity-10 text-{{ 
                                                    $doc['category'] == 'proposal' ? 'primary' : 
                                                    ($doc['category'] == 'undangan' ? 'info' : 'success') 
                                                }} px-3 py-2 border border-{{ 
                                                    $doc['category'] == 'proposal' ? 'primary' : 
                                                    ($doc['category'] == 'undangan' ? 'info' : 'success') 
                                                }} border-opacity-25">
                                                    {{ $doc['label'] }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('documents.download', [$doc['category'], $doc['filename']]) }}" 
                                                       class="btn btn-sm btn-primary rounded-3 px-3" title="Download">
                                                        <i class="fas fa-download me-1"></i> Download
                                                    </a>
                                                    <form action="{{ route('documents.delete', [$doc['category'], $doc['filename']]) }}" 
                                                          method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-3 px-3" 
                                                                onclick="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')"
                                                                title="Hapus">
                                                            <i class="fas fa-trash me-1"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada dokumen yang tersedia.</p>
                                <p class="text-muted">Silakan upload dokumen terlebih dahulu.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.main-content-wrapper {
    margin-left: 250px;
    padding: 20px;
    background: var(--lavender);
    min-height: 100vh;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: var(--color-text-dark);
}

.table td {
    vertical-align: middle;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
}

.card {
    transition: transform 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
}
</style>
