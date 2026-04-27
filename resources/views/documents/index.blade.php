@extends('layout.header')

@section('title', 'Manajemen Dokumen - SIMPKL-TI')

<div class="d-flex">
    @include('layout.sidebar')
</div>

<div class="main-content-wrapper">
    <div class="container">
        <div class="page-header mb-4">
            <h1 class="h2 mb-3">
                <i class="fas fa-file-word me-2"></i>
                Manajemen Dokumen PKL
            </h1>
            <p class="text-muted">Kelola template, undangan, dan laporan PKL</p>
        </div>

        <!-- Document Cards -->
        <div class="row">
            <!-- Proposal Card -->
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="icon-box mb-3">
                            <i class="fas fa-file-alt fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">Proposal PKL</h5>
                        <p class="card-text text-muted">Template dan pengelolaan proposal PKL</p>
                        <a href="{{ route('documents.proposal.index') }}" class="btn btn-primary">
                            <i class="fas fa-folder-open me-2"></i>Kelola Proposal
                        </a>
                    </div>
                </div>
            </div>

            <!-- Undangan Card -->
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="icon-box mb-3">
                            <i class="fas fa-envelope fa-3x text-info"></i>
                        </div>
                        <h5 class="card-title">Undangan</h5>
                        <p class="card-text text-muted">Template undangan seminar dan bimbingan</p>
                        <a href="{{ route('documents.undangan.index') }}" class="btn btn-info">
                            <i class="fas fa-envelope-open-text me-2"></i>Kelola Undangan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Laporan PKL Card -->
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="icon-box mb-3">
                            <i class="fas fa-book fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">Laporan PKL</h5>
                        <p class="card-text text-muted">Template dan pedoman laporan PKL</p>
                        <a href="{{ route('documents.laporan.index') }}" class="btn btn-success">
                            <i class="fas fa-book-open me-2"></i>Kelola Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Templates -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-folder-open me-2"></i>
                            Template Tersedia
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Proposal Templates -->
                        @if(!empty($proposalTemplates))
                            <h6 class="text-primary mb-3">Template Proposal</h6>
                            <div class="row mb-4">
                                @foreach($proposalTemplates as $file => $info)
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center p-2 border rounded">
                                        <i class="fas fa-file-word text-primary me-2"></i>
                                        <div class="flex-grow-1">
                                            <small class="fw-bold">{{ $info['name'] }}</small>
                                            <br><small class="text-muted">{{ number_format($info['size']/1024, 2) }} KB</small>
                                        </div>
                                        <a href="{{ route('documents.proposal.download', $file) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Undangan Templates -->
                        @if(!empty($undanganTemplates))
                            <h6 class="text-info mb-3">Template Undangan</h6>
                            <div class="row mb-4">
                                @foreach($undanganTemplates as $file => $info)
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center p-2 border rounded">
                                        <i class="fas fa-file-word text-info me-2"></i>
                                        <div class="flex-grow-1">
                                            <small class="fw-bold">{{ $info['name'] }}</small>
                                            <br><small class="text-muted">{{ number_format($info['size']/1024, 2) }} KB</small>
                                        </div>
                                        <a href="{{ route('documents.undangan.download', $file) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Laporan Templates -->
                        @if(!empty($laporanTemplates))
                            <h6 class="text-success mb-3">Template Laporan</h6>
                            <div class="row mb-4">
                                @foreach($laporanTemplates as $file => $info)
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center p-2 border rounded">
                                        <i class="fas fa-file-{{ $info['type'] == 'pdf' ? 'pdf' : 'word' }} text-{{ $info['type'] == 'pdf' ? 'danger' : 'success' }} me-2"></i>
                                        <div class="flex-grow-1">
                                            <small class="fw-bold">{{ $info['name'] }}</small>
                                            <br><small class="text-muted">{{ number_format($info['size']/1024, 2) }} KB</small>
                                        </div>
                                        <a href="{{ route('documents.laporan.download', $file) }}" class="btn btn-sm btn-outline-{{ $info['type'] == 'pdf' ? 'danger' : 'success' }}">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif

                        @if(empty($proposalTemplates) && empty($undanganTemplates) && empty($laporanTemplates))
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle me-2"></i>
                                Belum ada template yang tersedia. Silakan upload template terlebih dahulu.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Statistik Dokumen</h6>
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="stat-box">
                                    <h4 class="text-primary">{{ $proposalCount }}</h4>
                                    <small class="text-muted">Template Proposal</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-box">
                                    <h4 class="text-info">{{ $undanganCount }}</h4>
                                    <small class="text-muted">Template Undangan</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-box">
                                    <h4 class="text-success">{{ $laporanCount }}</h4>
                                    <small class="text-muted">Template Laporan</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-box">
                                    <h4 class="text-warning">0</h4>
                                    <small class="text-muted">Dokumen Diupload</small>
                                </div>
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

.icon-box {
    padding: 20px;
    border-radius: 50%;
    background: rgba(49, 72, 122, 0.1);
    display: inline-block;
}

.card {
    transition: transform 0.2s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.stat-box {
    padding: 15px;
    border-radius: 8px;
    background: var(--lavender);
}
</style>
