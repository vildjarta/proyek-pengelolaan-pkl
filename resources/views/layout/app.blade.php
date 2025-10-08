<!DOCTYPE html>
<html lang="id">
<style>
        .company-card {
            transition: 0.3s;
        }

        .company-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .status-badge {
            font-size: 0.85rem;
            padding: 5px 10px;
            border-radius: 12px;
        }

        #map {
            width: 100%;
            height: 350px;
            border-radius: 12px;
            margin-top: 15px;
        }
</style>
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
    {{-- MAIN CONTENT --}}
    <div class="main-content-wrapper">
        <div class="content">
            @yield('content')
        </div>
    </div>
</div>

{{-- JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
