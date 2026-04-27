@extends('layout.header')

@section('title', 'Template Undangan - SIMPKL-TI')

<div class="d-flex">
    @include('layout.sidebar')
</div>

<div class="main-content-wrapper">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Dokumen</a></li>
                <li class="breadcrumb-item active">Undangan</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="page-header mb-4">
            <h1 class="h2 mb-3">
                <i class="fas fa-envelope me-2"></i>
                Template Undangan
            </h1>
            <p class="text-muted">Download template undangan seminar dan bimbingan</p>
        </div>

        <!-- Templates Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-download me-2"></i>
                            Template Undangan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if(!empty($templates))
                                @foreach($templates as $file => $title)
                                <div class="col-md-6 mb-3">
                                    <div class="template-item d-flex align-items-center p-3 border rounded">
                                        <div class="icon-box me-3">
                                            <i class="fas fa-file-word fa-2x text-info"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $title }}</h6>
                                            <small class="text-muted">{{ $file }}</small>
                                        </div>
                                        <div>
                                            <a href="{{ route('documents.undangan.download', $file) }}"
                                               class="btn btn-sm btn-info">
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
                                        Template undangan tidak tersedia saat ini.
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
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-upload me-2"></i>
                            Upload Undangan
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

                        <form action="{{ route('documents.undangan.upload') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="jenis" class="form-label">Jenis Undangan</label>
                                        <select class="form-select" id="jenis" name="jenis" required>
                                            <option value="">Pilih Jenis</option>
                                            <option value="seminar">Undangan Seminar</option>
                                            <option value="bimbingan">Undangan Bimbingan</option>
                                            <option value="sidang">Undangan Sidang</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama/NIM</label>
                                        <input type="text" class="form-control" id="nama" name="nama" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="undangan_file" class="form-label">File Undangan (.docx)</label>
                                        <input type="file" class="form-control" id="undangan_file" name="undangan_file"
                                               accept=".docx" required>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-upload me-2"></i>Upload Undangan
                                </button>
                                <a href="{{ route('documents.undangan.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo me-2"></i>Reset
                                </a>
                            </div>
                        </form>
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
    background: rgba(30, 46, 79, 0.1);
}
</style>
