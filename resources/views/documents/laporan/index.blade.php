@extends('layout.header')

@section('title', 'Template Laporan PKL - SIMPKL-TI')

<div class="d-flex">
    @include('layout.sidebar')
</div>

<div class="main-content-wrapper">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Dokumen</a></li>
                <li class="breadcrumb-item active">Laporan PKL</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="page-header mb-4">
            <h1 class="h2 mb-3">
                <i class="fas fa-book me-2"></i>
                Template Laporan PKL
            </h1>
            <p class="text-muted">Download template laporan dan pedoman penulisan</p>
        </div>

        <!-- Templates Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-download me-2"></i>
                            Pedoman Laporan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if(!empty($templates))
                                @foreach($templates as $file => $title)
                                <div class="col-md-6 mb-3">
                                    <div class="template-item d-flex align-items-center p-3 border rounded">
                                        <div class="icon-box me-3">
                                            <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $title }}</h6>
                                            <small class="text-muted">{{ $file }}</small>
                                        </div>
                                        <div>
                                            <a href="{{ route('documents.laporan.download', $file) }}"
                                               class="btn btn-sm btn-danger">
                                                <i class="fas fa-download me-1"></i>Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Pedoman laporan tidak tersedia saat ini.
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Section -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-upload me-2"></i>
                            Upload Laporan PKL
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('documents.laporan.upload') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="nim" class="form-label">NIM</label>
                                        <input type="text" class="form-control" id="nim" name="nim" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="judul_laporan" class="form-label">Judul Laporan</label>
                                        <input type="text" class="form-control" id="judul_laporan" name="judul_laporan" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="laporan_file" class="form-label">File Laporan (.docx)</label>
                                        <input type="file" class="form-control" id="laporan_file" name="laporan_file"
                                               accept=".docx" required>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload me-2"></i>Upload Laporan
                                </button>
                                <a href="{{ route('documents.laporan.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo me-2"></i>Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Guidelines Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Panduan Upload Laporan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary">Format File</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success me-2"></i>Format .docx (Microsoft Word 2007+)</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Ukuran maksimal 20MB</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Nama file: NIM_Judul_Laporan</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary">Struktur Laporan</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-file-alt text-info me-2"></i>Cover</li>
                                    <li><i class="fas fa-list text-info me-2"></i>Daftar Isi</li>
                                    <li><i class="fas fa-book-open text-info me-2"></i>Bab 1-5</li>
                                    <li><i class="fas fa-paperclip text-info me-2"></i>Lampiran</li>
                                </ul>
                            </div>
                        </div>
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

.template-item {
    transition: all 0.2s ease;
    background: white;
}

.template-item:hover {
    background: var(--lavender);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.icon-box {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(220, 53, 69, 0.1);
}
</style>
