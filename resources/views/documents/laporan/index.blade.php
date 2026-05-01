@extends('layout.header')

@section('title', 'Template Laporan - SIMPKL-TI')

<!-- Add Bootstrap 5 CSS for styling -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

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
            <p class="text-muted">Download template dan pedoman laporan PKL</p>
        </div>

        <!-- Templates Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-download me-2"></i>
                            Template Laporan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if(!empty($documents))
                                @foreach($documents as $file => $title)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="template-item card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                                        <div class="card-body d-flex flex-column p-4">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="icon-box rounded-circle bg-success bg-opacity-10 text-success me-3 flex-shrink-0" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                                                    @if(pathinfo($file, PATHINFO_EXTENSION) == 'pdf')
                                                        <i class="fas fa-file-pdf fa-lg text-danger"></i>
                                                    @else
                                                        <i class="fas fa-file-word fa-lg"></i>
                                                    @endif
                                                </div>
                                                <h6 class="mb-0 fw-bold text-dark lh-sm">{{ $title }}</h6>
                                            </div>
                                            <div class="mt-auto pt-3">
                                                <a href="{{ route('documents.download', ['laporan-pkl', $file]) }}"
                                                   class="btn btn-success text-white w-100 rounded-3 py-2 fw-semibold">
                                                    <i class="fas fa-download me-2"></i>Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Template laporan tidak tersedia saat ini.
                                    </div>
                                </div>
                            @endif
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
    background: rgba(40, 167, 69, 0.1);
}
</style>
